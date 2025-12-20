<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IntrusionDetectionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $path = $request->path();
        $method = $request->method();

        // Check for suspicious patterns
        $suspiciousActivity = $this->detectSuspiciousActivity($request);
        
        if ($suspiciousActivity) {
            $this->logSuspiciousActivity($request, $suspiciousActivity);
            $this->incrementSuspiciousAttempts($ip);
            
            // Block IP if too many suspicious attempts
            if ($this->shouldBlockIp($ip)) {
                Log::critical('IP blocked due to suspicious activity', [
                    'ip' => $ip,
                    'user_agent' => $userAgent,
                    'path' => $path,
                    'method' => $method,
                    'reason' => $suspiciousActivity
                ]);
                
                abort(403, 'Access denied due to suspicious activity');
            }
        }

        // Monitor failed login attempts
        if ($request->routeIs('login') && $method === 'POST') {
            $this->monitorLoginAttempt($request);
        }

        // Log admin area access
        if (str_starts_with($path, 'admin/')) {
            $this->logAdminAccess($request);
        }

        return $next($request);
    }

    /**
     * Detect suspicious activity patterns
     */
    private function detectSuspiciousActivity(Request $request): ?string
    {
        $patterns = [
            // SQL Injection attempts
            'sql_injection' => [
                '/union\s+select/i',
                '/drop\s+table/i',
                '/insert\s+into/i',
                '/delete\s+from/i',
                '/update\s+set/i',
                '/or\s+1\s*=\s*1/i',
                '/\'\s*or\s*\'\s*=\s*\'/i'
            ],
            
            // XSS attempts
            'xss_attempt' => [
                '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
                '/javascript:/i',
                '/vbscript:/i',
                '/onload\s*=/i',
                '/onerror\s*=/i',
                '/onclick\s*=/i',
                '/eval\s*\(/i'
            ],
            
            // Path traversal
            'path_traversal' => [
                '/\.\.\//i',
                '/\.\.\\\/i',
                '/%2e%2e%2f/i',
                '/%252e%252e%252f/i'
            ],
            
            // Command injection
            'command_injection' => [
                '/;\s*cat\s+/i',
                '/;\s*ls\s+/i',
                '/;\s*wget\s+/i',
                '/;\s*curl\s+/i',
                '/\|\s*nc\s+/i'
            ]
        ];

        $requestData = json_encode([
            'url' => $request->fullUrl(),
            'input' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        foreach ($patterns as $type => $patternList) {
            foreach ($patternList as $pattern) {
                if (preg_match($pattern, $requestData)) {
                    return $type;
                }
            }
        }

        return null;
    }

    /**
     * Log suspicious activity
     */
    private function logSuspiciousActivity(Request $request, string $type): void
    {
        Log::warning('Suspicious activity detected', [
            'type' => $type,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'input' => $request->all(),
            'user_id' => Auth::id(),
            'timestamp' => now()
        ]);
    }

    /**
     * Monitor login attempts
     */
    private function monitorLoginAttempt(Request $request): void
    {
        $email = $request->input('email');
        $ip = $request->ip();
        
        // Log all login attempts
        Log::info('Login attempt', [
            'email' => $email,
            'ip' => $ip,
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);
        
        // Track failed attempts (this will be called from AuthenticatedSessionController)
        $key = "failed_login_attempts:{$ip}:{$email}";
        $attempts = Cache::get($key, 0);
        
        if ($attempts >= 3) {
            Log::warning('Multiple failed login attempts detected', [
                'email' => $email,
                'ip' => $ip,
                'attempts' => $attempts,
                'user_agent' => $request->userAgent()
            ]);
        }
    }

    /**
     * Log admin area access
     */
    private function logAdminAccess(Request $request): void
    {
        Log::info('Admin area access', [
            'user_id' => Auth::id(),
            'ip' => $request->ip(),
            'path' => $request->path(),
            'method' => $request->method(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);
    }

    /**
     * Increment suspicious attempts counter
     */
    private function incrementSuspiciousAttempts(string $ip): void
    {
        $key = "suspicious_attempts:{$ip}";
        $attempts = Cache::get($key, 0) + 1;
        Cache::put($key, $attempts, now()->addHours(24));
    }

    /**
     * Check if IP should be blocked
     */
    private function shouldBlockIp(string $ip): bool
    {
        $key = "suspicious_attempts:{$ip}";
        $attempts = Cache::get($key, 0);
        
        return $attempts >= 5; // Block after 5 suspicious attempts in 24 hours
    }
}
