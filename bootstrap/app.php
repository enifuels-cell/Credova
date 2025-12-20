<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
<<<<<<< HEAD
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'landlord' => \App\Http\Middleware\LandlordMiddleware::class,
        ]);
=======
        // Middleware aliases for route-specific use (e.g., 'auth:admin')
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'security.validation' => \App\Http\Middleware\SecurityValidationMiddleware::class,
            'intrusion.detection' => \App\Http\Middleware\IntrusionDetectionMiddleware::class,
            'csp' => \App\Http\Middleware\ContentSecurityPolicyMiddleware::class,
        ]);
        
        // --- Global Middleware Stack ---
        // Prepend the ForceHttpsMiddleware to ensure every request is secure first.
        // This should always be the highest priority middleware.
        $middleware->prepend(\App\Http\Middleware\ForceHttpsMiddleware::class);
        
        // Add Content Security Policy headers
        $middleware->append(\App\Http\Middleware\ContentSecurityPolicyMiddleware::class);
        
        // Add intrusion detection monitoring
        $middleware->append(\App\Http\Middleware\IntrusionDetectionMiddleware::class);
        
        // Add security validation middleware globally
        $middleware->append(\App\Http\Middleware\SecurityValidationMiddleware::class);
        
        // Append the SecurityMiddleware to the global stack, but only in production.
        // This prevents the overhead of security checks during local development.
        if (env('APP_ENV') === 'production') {
            $middleware->append(\App\Http\Middleware\SecurityMiddleware::class);
        }
    })
    ->withSchedule(function ($schedule) {
        // Database backups
        $schedule->command('backup:database --compress --encrypt')
            ->daily()
            ->at('02:00')
            ->onOneServer()
            ->runInBackground();
        
        // Weekly full backup (uncompressed for faster recovery)
        $schedule->command('backup:database')
            ->weekly()
            ->sundays()
            ->at('01:00')
            ->onOneServer();
        
        // Clear old logs
        $schedule->command('log:clear')
            ->weekly()
            ->mondays()
            ->at('03:00');
            
        // Clear expired sessions
        $schedule->command('session:gc')
            ->daily()
            ->at('04:00');
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
<<<<<<< HEAD
=======

>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
