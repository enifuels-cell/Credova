<?php
/**
 * Direct login test without Laravel bootstrap
 * This will help us understand what's happening with CSRF
 */

// Simulate a POST request to /login
echo "Testing Login Route...\n\n";

// Start session for the test
session_start();

// Test 1: Check if we can access bootstrap
echo "Test 1: Bootstrap access\n";
try {
    $app = require __DIR__ . '/bootstrap/app.php';
    echo "✓ Bootstrap loaded successfully\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\nTest 2: Route information\n";

// Get all POST login routes
$postLoginRoutes = collect(\Route::getRoutes())
    ->filter(fn($route) => in_array('POST', $route->methods()) && strpos($route->uri(), 'login') !== false);

foreach ($postLoginRoutes as $route) {
    echo "POST Route: " . $route->uri() . "\n";
    echo "  Middleware: " . json_encode($route->middleware()) . "\n";
}

echo "\nTest 3: Check session status\n";
echo "SESSION_DRIVER from config: " . config('session.driver') . "\n";
echo "SESSION_LIFETIME from config: " . config('session.lifetime') . "\n";
echo "SESSION_COOKIE from config: " . config('session.cookie') . "\n";

echo "\nTest 4: Check CSRF configuration\n";
// Check if there's a VerifyCsrfToken middleware
$hasVerifycsrf = false;
foreach (config('middleware.web') ?? [] as $middleware) {
    if (strpos($middleware, 'VerifycsrfToken') !== false || strpos($middleware, 'Csrf') !== false) {
        $hasVerifycsrf = true;
        echo "✓ Found CSRF middleware: " . $middleware . "\n";
    }
}

if (!$hasVerifycsrf) {
    echo "✗ CSRF middleware not found in web middleware group\n";
    echo "Web middleware: " . json_encode(config('middleware.web') ?? []) . "\n";
}

echo "\n";
