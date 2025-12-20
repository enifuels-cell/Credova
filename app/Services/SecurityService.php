<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SecurityLog;
use App\Models\LoginAttempt;
use Carbon\Carbon;

class SecurityService
{
    protected $maxLoginAttempts = 5;
    protected $lockoutDuration = 900; // 15 minutes
    protected $suspiciousActivityThreshold = 10;
    
    /**
     * Enhanced rate limiting with adaptive thresholds
     */
    public function checkRateLimit(Request $request, string $action): bool
    {
        $key = $this->getRateLimitKey($request, $action);
        $maxAttempts = $this->getMaxAttempts($action);
        $decayMinutes = $this->getDecayMinutes($action);
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $this->logSecurityEvent('rate_limit_exceeded', $request, [
                'action' => $action,
                'attempts' => RateLimiter::attempts($key)
            ]);
            return false;
        }
        
        RateLimiter::hit($key, $decayMinutes * 60);
        return true;
    }

    /**
     * Advanced brute force protection
     */
    public function handleLoginAttempt(Request $request, bool $successful, ?User $user = null): array
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $email = $request->input('email');
        
        // Log the attempt
        $attempt = LoginAttempt::create([
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'email' => $email,
            'user_id' => $user?->id,
            'successful' => $successful,
            'attempted_at' => now(),
            'location' => $this->getLocationFromIP($ip),
            'device_fingerprint' => $this->generateDeviceFingerprint($request)
        ]);

        if ($successful) {
            $this->handleSuccessfulLogin($user, $request);
            return ['status' => 'success'];
        }

        return $this->handleFailedLogin($request, $email);
    }

    /**
     * Multi-factor authentication check
     */
    public function requiresMFA(User $user, Request $request): bool
    {
        if (!$user->mfa_enabled) {
            return false;
        }

        // Check if device is trusted
        if ($this->isDeviceTrusted($user, $request)) {
            return false;
        }

        // Check risk factors
        $riskScore = $this->calculateRiskScore($user, $request);
        
        return $riskScore > 30 || $user->force_mfa;
    }

    /**
     * Generate and validate MFA codes
     */
    public function generateMFACode(User $user): string
    {
        $code = sprintf('%06d', random_int(0, 999999));
        $expiresAt = now()->addMinutes(10);
        
        Cache::put("mfa_code_{$user->id}", [
            'code' => Hash::make($code),
            'expires_at' => $expiresAt,
            'attempts' => 0
        ], 600); // 10 minutes
        
        return $code;
    }

    public function validateMFACode(User $user, string $code): bool
    {
        $cacheKey = "mfa_code_{$user->id}";
        $mfaData = Cache::get($cacheKey);
        
        if (!$mfaData || now()->gt($mfaData['expires_at'])) {
            return false;
        }
        
        if ($mfaData['attempts'] >= 3) {
            Cache::forget($cacheKey);
            return false;
        }
        
        $mfaData['attempts']++;
        Cache::put($cacheKey, $mfaData, 600);
        
        if (Hash::check($code, $mfaData['code'])) {
            Cache::forget($cacheKey);
            return true;
        }
        
        return false;
    }

    /**
     * Device fingerprinting and trust management
     */
    public function generateDeviceFingerprint(Request $request): string
    {
        $components = [
            $request->userAgent(),
            $request->header('Accept-Language'),
            $request->header('Accept-Encoding'),
            $request->ip()
        ];
        
        return hash('sha256', implode('|', $components));
    }

    public function isDeviceTrusted(User $user, Request $request): bool
    {
        $fingerprint = $this->generateDeviceFingerprint($request);
        
        return Cache::has("trusted_device_{$user->id}_{$fingerprint}");
    }

    public function trustDevice(User $user, Request $request, int $days = 30): void
    {
        $fingerprint = $this->generateDeviceFingerprint($request);
        $cacheKey = "trusted_device_{$user->id}_{$fingerprint}";
        
        Cache::put($cacheKey, [
            'trusted_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ], $days * 24 * 60 * 60);
    }

    /**
     * Risk scoring algorithm
     */
    public function calculateRiskScore(User $user, Request $request): int
    {
        $score = 0;
        
        // Check for suspicious IP
        if ($this->isSuspiciousIP($request->ip())) {
            $score += 40;
        }
        
        // Check for unusual location
        if ($this->isUnusualLocation($user, $request)) {
            $score += 30;
        }
        
        // Check login time patterns
        if ($this->isUnusualTime($user)) {
            $score += 20;
        }
        
        // Check for rapid successive attempts
        if ($this->hasRapidAttempts($request->ip())) {
            $score += 25;
        }
        
        // Check device reputation
        if ($this->hasDeviceBadReputation($request)) {
            $score += 35;
        }
        
        // Check for VPN/Proxy usage
        if ($this->isVPNorProxy($request->ip())) {
            $score += 15;
        }
        
        return min(100, $score);
    }

    /**
     * Content Security Policy management
     */
    public function generateCSPNonce(): string
    {
        $nonce = base64_encode(random_bytes(16));
        Cache::put("csp_nonce_" . session()->getId(), $nonce, 3600);
        return $nonce;
    }

    public function getCSPHeader(): string
    {
        $nonce = $this->generateCSPNonce();
        
        return "default-src 'self'; " .
               "script-src 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net https://js.stripe.com; " .
               "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
               "img-src 'self' data: https: blob:; " .
               "font-src 'self' https://cdnjs.cloudflare.com; " .
               "connect-src 'self' https://api.stripe.com; " .
               "frame-src https://js.stripe.com; " .
               "object-src 'none'; " .
               "base-uri 'self'; " .
               "form-action 'self'; " .
               "upgrade-insecure-requests;";
    }

    /**
     * Input validation and sanitization
     */
    public function sanitizeInput(string $input, string $type = 'general'): string
    {
        // Remove null bytes
        $input = str_replace("\0", '', $input);
        
        switch ($type) {
            case 'email':
                return filter_var($input, FILTER_SANITIZE_EMAIL);
            
            case 'url':
                return filter_var($input, FILTER_SANITIZE_URL);
            
            case 'html':
                return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            
            case 'sql':
                return addslashes($input);
            
            case 'filename':
                return preg_replace('/[^a-zA-Z0-9._-]/', '', $input);
            
            default:
                return htmlspecialchars(strip_tags($input), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }

    /**
     * File upload security
     */
    public function validateFileUpload($file, array $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf']): array
    {
        $results = [
            'valid' => true,
            'errors' => []
        ];
        
        // Check file size
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file->getSize() > $maxSize) {
            $results['valid'] = false;
            $results['errors'][] = 'File size exceeds maximum allowed size';
        }
        
        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedTypes)) {
            $results['valid'] = false;
            $results['errors'][] = 'File type not allowed';
        }
        
        // Check MIME type
        $mimeType = $file->getMimeType();
        $allowedMimes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf'
        ];
        
        if (isset($allowedMimes[$extension]) && $mimeType !== $allowedMimes[$extension]) {
            $results['valid'] = false;
            $results['errors'][] = 'File MIME type does not match extension';
        }
        
        // Scan for malware signatures (basic)
        if ($this->containsMaliciousContent($file)) {
            $results['valid'] = false;
            $results['errors'][] = 'File contains suspicious content';
        }
        
        return $results;
    }

    /**
     * Session security
     */
    public function secureSession(Request $request): void
    {
        // Regenerate session ID on login
        $request->session()->regenerate();
        
        // Set secure session configuration
        config([
            'session.secure' => $request->isSecure(),
            'session.http_only' => true,
            'session.same_site' => 'strict'
        ]);
        
        // Store session security data
        session([
            'security' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()->timestamp
            ]
        ]);
    }

    public function validateSession(Request $request): bool
    {
        $security = session('security');
        
        if (!$security) {
            return false;
        }
        
        // Check IP address
        if ($security['ip'] !== $request->ip()) {
            $this->logSecurityEvent('session_ip_mismatch', $request);
            return false;
        }
        
        // Check User-Agent
        if ($security['user_agent'] !== $request->userAgent()) {
            $this->logSecurityEvent('session_useragent_mismatch', $request);
            return false;
        }
        
        // Check session age
        $sessionAge = now()->timestamp - $security['created_at'];
        if ($sessionAge > 24 * 60 * 60) { // 24 hours
            return false;
        }
        
        return true;
    }

    /**
     * SQL injection detection
     */
    public function detectSQLInjection(string $input): bool
    {
        $patterns = [
            '/(\bunion\b.*\bselect\b)/i',
            '/(\bselect\b.*\bfrom\b)/i',
            '/(\binsert\b.*\binto\b)/i',
            '/(\bupdate\b.*\bset\b)/i',
            '/(\bdelete\b.*\bfrom\b)/i',
            '/(\bdrop\b.*\btable\b)/i',
            '/(\balter\b.*\btable\b)/i',
            '/(--|\#|\/\*|\*\/)/i',
            '/(\bor\b.*=.*\bor\b)/i',
            '/(\band\b.*=.*\band\b)/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * XSS detection and prevention
     */
    public function detectXSS(string $input): bool
    {
        $patterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/onclick\s*=/i',
            '/onmouseover\s*=/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Security logging
     */
    public function logSecurityEvent(string $type, Request $request, array $additionalData = []): void
    {
        $data = array_merge([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'timestamp' => now()
        ], $additionalData);
        
        try {
            SecurityLog::create([
                'type' => $type,
                'severity' => $this->getSeverityLevel($type),
                'data' => $data,
                'ip_address' => $request->ip(),
                'user_id' => auth()->id()
            ]);
        } catch (\Exception $e) {
            // If security_logs table doesn't exist or database error, just log to file
            // This prevents breaking the application during testing or when security tables aren't migrated
            Log::warning('Security log database error: ' . $e->getMessage());
        }
        
        // Log to file for external monitoring (always works)
        Log::channel('security')->warning("Security Event: {$type}", $data);
    }

    /**
     * Helper methods
     */
    private function getRateLimitKey(Request $request, string $action): string
    {
        return "rate_limit:{$action}:" . $request->ip();
    }

    private function getMaxAttempts(string $action): int
    {
        return match($action) {
            'login' => 5,
            'register' => 3,
            'password_reset' => 3,
            'api_call' => 60,
            default => 10
        };
    }

    private function getDecayMinutes(string $action): int
    {
        return match($action) {
            'login' => 15,
            'register' => 60,
            'password_reset' => 30,
            'api_call' => 1,
            default => 5
        };
    }

    private function handleSuccessfulLogin(User $user, Request $request): void
    {
        // Clear failed attempts
        Cache::forget("failed_login_attempts:" . $request->ip());
        Cache::forget("failed_login_attempts_email:" . $user->email);
        
        // Update last login
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip()
        ]);
        
        $this->logSecurityEvent('successful_login', $request, ['user_id' => $user->id]);
    }

    private function handleFailedLogin(Request $request, string $email): array
    {
        $ip = $request->ip();
        
        // Increment failure counters
        $ipAttempts = Cache::increment("failed_login_attempts:{$ip}", 1, 900);
        $emailAttempts = Cache::increment("failed_login_attempts_email:{$email}", 1, 900);
        
        $this->logSecurityEvent('failed_login', $request, [
            'email' => $email,
            'ip_attempts' => $ipAttempts,
            'email_attempts' => $emailAttempts
        ]);
        
        // Check for lockout
        if ($ipAttempts >= $this->maxLoginAttempts || $emailAttempts >= $this->maxLoginAttempts) {
            Cache::put("login_lockout:{$ip}", true, $this->lockoutDuration);
            Cache::put("login_lockout_email:{$email}", true, $this->lockoutDuration);
            
            return [
                'status' => 'locked',
                'message' => 'Too many failed attempts. Account locked for 15 minutes.'
            ];
        }
        
        $remaining = $this->maxLoginAttempts - max($ipAttempts, $emailAttempts);
        return [
            'status' => 'failed',
            'message' => "Invalid credentials. {$remaining} attempts remaining."
        ];
    }

    private function getLocationFromIP(string $ip): ?string
    {
        // Placeholder for IP geolocation service
        // In production, integrate with services like MaxMind or IPStack
        return null;
    }

    private function isSuspiciousIP(string $ip): bool
    {
        // Check against known malicious IP databases
        // Placeholder implementation
        return Cache::has("suspicious_ip:{$ip}");
    }

    private function isUnusualLocation(User $user, Request $request): bool
    {
        // Compare with user's typical login locations
        // Placeholder implementation
        return false;
    }

    private function isUnusualTime(User $user): bool
    {
        // Analyze user's typical login time patterns
        // Placeholder implementation
        return false;
    }

    private function hasRapidAttempts(string $ip): bool
    {
        $key = "rapid_attempts:{$ip}";
        $attempts = Cache::get($key, 0);
        
        Cache::increment($key, 1, 60); // Count attempts in last minute
        
        return $attempts > 5;
    }

    private function hasDeviceBadReputation(Request $request): bool
    {
        // Check device fingerprint against reputation database
        // Placeholder implementation
        return false;
    }

    private function isVPNorProxy(string $ip): bool
    {
        // Check if IP belongs to known VPN/Proxy services
        // Placeholder implementation
        return false;
    }

    private function containsMaliciousContent($file): bool
    {
        // Basic malware signature detection
        $content = file_get_contents($file->getRealPath());
        
        // Use safer pattern matching instead of listing dangerous functions
        $dangerousPatterns = [
            '/eval\s*\(/', // eval function calls
            '/base64_decode\s*\((?![\'"][\w+\/=]+[\'"])\)/', // suspicious base64 usage
            '/(?:system|exec|shell_exec|passthru|proc_open|popen)\s*\(/', // command execution
            '/<\?php\s+eval\s*\(/', // PHP eval in uploads
            '/\$\{[^}]+\}/', // variable substitution attacks
            '/javascript\s*:/', // javascript: protocol
            '/vbscript\s*:/', // vbscript: protocol
        ];
        
        // Use regex patterns instead of string matching for better security
        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        
        return false;
    }

    private function getSeverityLevel(string $type): string
    {
        return match($type) {
            'sql_injection', 'xss_attempt', 'file_upload_malware' => 'critical',
            'brute_force', 'rate_limit_exceeded', 'session_hijack' => 'high',
            'failed_login', 'suspicious_activity' => 'medium',
            'successful_login', 'logout' => 'low',
            default => 'medium'
        };
    }
}
