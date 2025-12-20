<?php

namespace App\Http\Middleware;

use App\Services\SecurityService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecurityMiddleware
{
    protected SecurityService $securityService;

    public function __construct(SecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('local') && $this->isAuthRoute($request)) {
            return $next($request);
        }

        $clientIp = $request->ip();
        $userAgent = $request->userAgent();
        $user = Auth::user();

        // 1. Rate Limiting Check
        if ($this->isRateLimited($request, $clientIp, $user)) {
            return response()->json([
                'error' => 'Rate limit exceeded. Please try again later.',
                'retry_after' => $this->getRateLimitRetryAfter($clientIp)
            ], 429);
        }

        // 2. Suspicious Activity Detection
        if ($this->detectSuspiciousActivity($request, $clientIp, $userAgent, $user)) {
            $this->logSecurityEvent('suspicious_activity', $request, $user);

            if ($this->isHighRiskRequest($request)) {
                return response()->json([
                    'error' => 'Additional verification required',
                    'requires_mfa' => true
                ], 403);
            }
        }

        // 3. Device Fingerprinting
        if ($user) {
            $this->trackDeviceFingerprint($request, $user);
        }

        // 4. Threat Intelligence Check
        if ($this->isThreatIP($clientIp)) {
            $this->logSecurityEvent('threat_ip_detected', $request, $user);
            return response()->json([
                'error' => 'Access denied for security reasons'
            ], 403);
        }

        // 5. Session Security
        if ($user && $this->isSessionCompromised($request, $user)) {
            Auth::logout();
            $this->logSecurityEvent('session_compromised', $request, $user);
            return response()->json([
                'error' => 'Session security violation. Please log in again.',
                'requires_login' => true
            ], 401);
        }

        // 6. Update user activity
        if ($user) {
            $this->updateUserActivity($user, $request);
        }

        $response = $next($request);

        // 7. Add security headers
        $this->addSecurityHeaders($response, $request);

        // 8. Post-request security checks
        $this->performPostRequestChecks($request, $response, $user);

        return $response;
    }

    protected function isRateLimited(Request $request, string $clientIp, $user = null): bool
    {
        try {
            $key = $user ? "rate_limit:user:{$user->id}" : "rate_limit:ip:{$clientIp}";
            
            $limits = config('security.rate_limits', [
                'login' => ['max' => 5, 'window' => 300],
                'api' => ['max' => 60, 'window' => 60],
                'booking' => ['max' => 10, 'window' => 300],
                'default' => ['max' => 120, 'window' => 60],
            ]);

            $requestType = $this->getRequestType($request);
            $limit = $limits[$requestType] ?? $limits['default'];

            $current = (int) Cache::get($key, 0);

            if ($current >= $limit['max']) {
                return true;
            }

            Cache::put($key, $current + 1, $limit['window']);
            return false;
        } catch (\Exception $e) {
            Log::warning('Rate limiting cache failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function getRequestType(Request $request): string
    {
        $path = $request->path();
        
        if (str_contains($path, 'login') || str_contains($path, 'auth')) {
            return 'login';
        }
        
        if (str_starts_with($path, 'api/')) {
            return 'api';
        }
        
        if (str_contains($path, 'booking')) {
            return 'booking';
        }
        
        return 'default';
    }

    protected function getRateLimitRetryAfter(string $clientIp): int
    {
        $key = "rate_limit:ip:{$clientIp}";
        $store = Cache::getStore();
        
        if (method_exists($store, 'ttl')) {
            return $store->ttl($key) ?? 60;
        }
        
        return 60;
    }

    protected function detectSuspiciousActivity(Request $request, string $clientIp, ?string $userAgent, $user = null): bool
    {
        $suspiciousPatterns = [
            $this->detectRapidRequests($clientIp),
            $this->detectSuspiciousUserAgent($userAgent),
            $this->detectLocationAnomalies($clientIp, $user),
            $this->detectSQLInjection($request),
            $this->detectXSS($request),
            $this->detectBotBehavior($request, $userAgent),
        ];

        return in_array(true, $suspiciousPatterns, true);
    }

    protected function detectRapidRequests(string $clientIp): bool
    {
        $key = "rapid_requests:{$clientIp}";
        $requests = Cache::get($key, []);
        $now = time();
        
        $requests = array_filter($requests, fn($timestamp) => $now - $timestamp < 60);
        $requests[] = $now;
        
        Cache::put($key, $requests, 300);
        
        return count($requests) > config('security.rapid_requests.threshold', 30);
    }

    protected function detectSuspiciousUserAgent(?string $userAgent): bool
    {
        if (!$userAgent || preg_match('/^(Mozilla|Opera|Chrome|Safari|Firefox)\/[0-9\.]+$/i', $userAgent)) {
            return true;
        }

        $suspiciousPatterns = config('security.suspicious_user_agents', [
            'bot', 'crawl', 'spider', 'scrape', 'hack', 'scan',
            'curl', 'wget', 'python', 'php', 'java', 'perl'
        ]);

        foreach ($suspiciousPatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    protected function detectLocationAnomalies(string $clientIp, $user = null): bool
    {
        if (!$user) {
            return false;
        }

        // Use a real IP geolocation service via the SecurityService
        $currentLocation = $this->securityService->getLocationFromIP($clientIp);
        $typicalLocations = $this->securityService->getUserTypicalLocations($user);
        
        if (empty($typicalLocations) || empty($currentLocation)) {
            return false;
        }

        foreach ($typicalLocations as $location) {
            $distance = $this->calculateDistance($currentLocation, $location);
            if ($distance < config('security.location_anomaly.threshold_km', 1000)) {
                return false;
            }
        }

        return true;
    }

    protected function detectSQLInjection(Request $request): bool
    {
        $sqlPatterns = config('security.detection.sql_injection', [
            "/(union|select|insert|update|delete|drop|create|alter|exec|execute)/i",
            "/('|(\x27)|(\x2D\x2D)|(%27)|(%2D%2D))/i",
            "/(\x00|\n|\r|\x1a)/i"
        ]);

        $input = json_encode($request->all());
        
        foreach ($sqlPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        return false;
    }

    protected function detectXSS(Request $request): bool
    {
        $xssPatterns = config('security.detection.xss', [
            "/<script[^>]*>.*?<\/script>/si",
            "/javascript:/i",
            "/on\w+\s*=/i",
            "/<iframe[^>]*>.*?<\/iframe>/si"
        ]);

        $input = json_encode($request->all());
        
        foreach ($xssPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        return false;
    }

    protected function detectBotBehavior(Request $request, ?string $userAgent): bool
    {
        $requiredHeaders = ['accept', 'accept-language', 'accept-encoding'];
        foreach ($requiredHeaders as $header) {
            if (!$request->header($header)) {
                return true;
            }
        }

        if (!$userAgent || strlen($userAgent) < 10) {
            return true;
        }

        return false;
    }

    protected function isHighRiskRequest(Request $request): bool
    {
        $highRiskPaths = config('security.high_risk_paths', [
            'admin', 'payment', 'booking', 'profile', 'settings'
        ]);

        $path = $request->path();
        
        foreach ($highRiskPaths as $riskPath) {
            if (str_contains($path, $riskPath)) {
                return true;
            }
        }

        return false;
    }

    protected function isThreatIP(string $clientIp): bool
    {
        // First check local blacklist for performance
        $blacklistedIPs = Cache::get('blacklisted_ips', []);
        if (in_array($clientIp, $blacklistedIPs)) {
            return true;
        }
        
        // Then check an external threat intelligence feed via the SecurityService
        return $this->securityService->isThreatIP($clientIp);
    }
    
    // ... (rest of the methods remain the same) ...

    protected function calculateRiskScore($user, Request $request): float
    {
        $riskFactors = [
            'location_anomaly' => $this->detectLocationAnomalies($request->ip(), $user),
            'suspicious_user_agent' => $this->detectSuspiciousUserAgent($request->userAgent()),
            'rapid_requests' => $this->detectRapidRequests($request->ip()),
            'sql_injection' => $this->detectSQLInjection($request),
            'xss' => $this->detectXSS($request),
            'bot_behavior' => $this->detectBotBehavior($request, $request->userAgent()),
            'threat_ip' => $this->isThreatIP($request->ip()),
            'is_high_risk_request' => $this->isHighRiskRequest($request),
        ];

        $weights = config('security.risk_weights', [
            'location_anomaly' => 0.3,
            'suspicious_user_agent' => 0.2,
            'rapid_requests' => 0.4,
            'sql_injection' => 0.6,
            'xss' => 0.5,
            'bot_behavior' => 0.3,
            'threat_ip' => 0.8,
            'is_high_risk_request' => 0.4,
            'mfa_enabled' => -0.1,
            'government_id_verified_at' => -0.1,
        ]);

        $baseScore = 0.0;

        foreach ($riskFactors as $factor => $detected) {
            if ($detected) {
                $baseScore += $weights[$factor];
            }
        }
        
        if ($user->mfa_enabled) {
            $baseScore += $weights['mfa_enabled'];
        }
        
        if ($user->government_id_verified_at) {
            $baseScore += $weights['government_id_verified_at'];
        }

        return max(0.0, min(1.0, $baseScore));
    }

    /**
     * Check if request is for authentication routes
     */
    protected function isAuthRoute(Request $request): bool
    {
        $authPaths = [
            'login',
            'register', 
            'password',
            'forgot-password',
            'reset-password',
            'verify-email',
            'logout'
        ];

        $path = $request->path();
        
        foreach ($authPaths as $authPath) {
            if (str_contains($path, $authPath)) {
                return true;
            }
        }

        return false;
    }
    
    // ... (rest of the code is unchanged) ...
}