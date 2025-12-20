<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SecurityValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Input sanitization and validation
        $this->sanitizeInput($request);
        
        // Check for common attack patterns
        if ($this->detectSuspiciousInput($request)) {
            abort(400, 'Invalid request detected');
        }

        return $next($request);
    }

    /**
     * Sanitize request input
     */
    private function sanitizeInput(Request $request): void
    {
        $input = $request->all();
        
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                // Remove potential XSS attempts
                $input[$key] = strip_tags($value);
                
                // Remove null bytes
                $input[$key] = str_replace("\0", '', $input[$key]);
            }
        }
        
        $request->replace($input);
    }

    /**
     * Detect suspicious input patterns
     */
    private function detectSuspiciousInput(Request $request): bool
    {
        $suspiciousPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/onclick\s*=/i',
            '/eval\s*\(/i',
            '/document\.cookie/i',
            '/union\s+select/i',
            '/drop\s+table/i',
            '/insert\s+into/i',
            '/delete\s+from/i',
        ];

        $allInput = json_encode($request->all());
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $allInput)) {
                return true;
            }
        }

        return false;
    }
}
