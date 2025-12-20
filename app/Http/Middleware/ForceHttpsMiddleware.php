<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force HTTPS in production or when behind a proxy
        if ($this->shouldForceHttps($request)) {
            // Check if request is already secure
            if (!$request->isSecure()) {
                // Redirect to HTTPS version
                return redirect()->secure($request->getRequestUri(), 301);
            }
            
            // Force all URL generation to use HTTPS
            URL::forceScheme('https');
            URL::forceRootUrl(config('app.url'));
        }

        return $next($request);
    }

    /**
     * Determine if we should force HTTPS
     */
    private function shouldForceHttps(Request $request): bool
    {
        // Never force HTTPS in local development
        if (app()->environment('local') || 
            $request->getHost() === 'localhost' || 
            $request->getHost() === '127.0.0.1' ||
            str_contains($request->getHost(), '.local')) {
            return false;
        }

        // Always force in production
        if (app()->environment('production')) {
            return true;
        }

        // Check proxy headers for HTTPS (staging environments)
        return $this->isSecureConnection($request);
    }

    /**
     * Check if connection is secure via proxy headers
     */
    private function isSecureConnection(Request $request): bool
    {
        // Check standard Laravel methods first
        if ($request->isSecure()) {
            return true;
        }

        // Check proxy headers (Render.com, Cloudflare, etc.)
        $proxyHeaders = [
            'HTTP_X_FORWARDED_PROTO' => ['https'],
            'HTTP_X_FORWARDED_PROTOCOL' => ['https'],
            'HTTP_X_FORWARDED_SSL' => ['on', '1', 'true'],
            'HTTP_X_URL_SCHEME' => ['https'],
            'HTTP_CLOUDFRONT_FORWARDED_PROTO' => ['https'],
        ];

        foreach ($proxyHeaders as $header => $values) {
            if ($headerValue = $request->server($header)) {
                if (in_array(strtolower($headerValue), $values)) {
                    return true;
                }
            }
        }

        // Check if port is 443
        if ($request->server('SERVER_PORT') == 443) {
            return true;
        }

        return false;
    }
}
