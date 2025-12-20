<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only apply CSP to HTML responses
        if (!$response instanceof Response || 
            !str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            return $response;
        }

        $nonce = $this->generateNonce();
        
        // Store nonce for use in views
        app()->instance('csp-nonce', $nonce);

        $csp = $this->buildCspHeader($nonce);
        
        if (config('app.env') === 'production') {
            $response->headers->set('Content-Security-Policy', $csp);
        } else {
            // Use report-only mode in development
            $response->headers->set('Content-Security-Policy-Report-Only', $csp);
        }

        // Additional security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }

    /**
     * Generate a cryptographically secure nonce
     */
    private function generateNonce(): string
    {
        return base64_encode(random_bytes(16));
    }

    /**
     * Build the CSP header
     */
    private function buildCspHeader(string $nonce): string
    {
        $directives = [
            "default-src 'self'",
            "script-src 'self' 'nonce-{$nonce}' https://js.stripe.com https://cdn.tailwindcss.com https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.tailwindcss.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "img-src 'self' data: https: blob:",
            "connect-src 'self' https://api.stripe.com",
            "frame-src 'self' https://js.stripe.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
            "upgrade-insecure-requests"
        ];

        if (config('app.env') !== 'production') {
            // Allow eval and inline scripts in development for Vite
            $directives[1] = "script-src 'self' 'nonce-{$nonce}' 'unsafe-eval' 'unsafe-inline' https://js.stripe.com https://cdn.tailwindcss.com https://cdnjs.cloudflare.com";
            $directives[] = "report-uri /csp-report";
        }

        return implode('; ', $directives);
    }
}
