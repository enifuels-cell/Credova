<?php

require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Check CSRF middleware
echo "\n=== CSRF MIDDLEWARE CHECK ===\n";

$middlewareGroups = config('app');
echo "APP_DEBUG: " . (config('app.debug') ? 'true' : 'false') . "\n";

// Check if CSRF middleware is in the middleware stack
$middlewareConfig = app('config')->get('middleware');
echo "\nMiddleware configuration:\n";
print_r($middlewareConfig);

// Check session configuration
echo "\n=== SESSION CONFIGURATION ===\n";
echo "SESSION_DRIVER: " . env('SESSION_DRIVER') . "\n";
echo "SESSION_LIFETIME: " . env('SESSION_LIFETIME') . "\n";
echo "SESSION_ENCRYPT: " . env('SESSION_ENCRYPT') . "\n";
echo "SESSION_DOMAIN: " . env('SESSION_DOMAIN') . "\n";
echo "SESSION_SECURE_COOKIE: " . (env('SESSION_SECURE_COOKIE') !== null ? env('SESSION_SECURE_COOKIE') : 'null') . "\n";
echo "SESSION_HTTP_ONLY: " . env('SESSION_HTTP_ONLY') . "\n";
echo "SESSION_SAME_SITE: " . env('SESSION_SAME_SITE') . "\n";

// Check application configuration
echo "\n=== APPLICATION CONFIGURATION ===\n";
echo "APP_URL: " . env('APP_URL') . "\n";
echo "APP_KEY set: " . (env('APP_KEY') ? 'yes' : 'no') . "\n";

// Check route POST /login
echo "\n=== ROUTE CONFIGURATION ===\n";
$routes = collect(\Route::getRoutes())->filter(function($route) {
    return strpos($route->uri(), 'login') !== false;
});

foreach ($routes as $route) {
    echo "Route: " . implode(',', $route->methods()) . " " . $route->uri() . "\n";
    echo "  Name: " . ($route->name() ?? 'unnamed') . "\n";
    echo "  Middleware: " . implode(',', $route->middleware()) . "\n";
}

// Check CSRF exceptions
echo "\n=== CSRF EXCEPTIONS ===\n";
$csrfExcepts = config('middleware.except') ?? [];
echo "CSRF Exceptions: " . (count($csrfExcepts) ? implode(',', $csrfExcepts) : 'none') . "\n";

// Test actual CSRF token generation
echo "\n=== CSRF TOKEN TEST ===\n";
$token = csrf_token();
echo "CSRF Token generated: " . (strlen($token) > 0 ? "yes (length: " . strlen($token) . ")" : "no") . "\n";

echo "\n";
