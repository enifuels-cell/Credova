<?php
/**
 * Test login POST request with proper session and CSRF handling
 */

require 'bootstrap/app.php';

$app = app();

// Create a test request
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

// Simulate a browser session
$this->actingAs(null);

echo "Testing CSRF and Login Flow\n";
echo str_repeat("=", 60) . "\n\n";

// Test 1: Can we get the login form?
echo "Test 1: Getting login form...\n";
try {
    $response = app('Illuminate\Routing\Router')->getRoutes()->getByName('login');
    echo "✓ Login route found: " . $response->uri() . "\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

// Test 2: Check CSRF middleware is applied
echo "\nTest 2: Checking CSRF middleware...\n";
$loginRoute = collect(app('Illuminate\Routing\Router')->getRoutes())
    ->filter(fn($r) => $r->uri() === 'login' && in_array('POST', $r->methods()))
    ->first();

if ($loginRoute) {
    echo "✓ POST /login route found\n";
    $middleware = $loginRoute->middleware();
    echo "  Middleware: " . json_encode($middleware) . "\n";

    if (in_array('web', $middleware) || count($middleware) === 0 || in_array('verified', $middleware)) {
        echo "  Note: 'web' middleware applies CSRF automatically if not explicitly listed\n";
    }
} else {
    echo "✗ POST /login route not found!\n";
}

// Test 3: Check session and CSRF configuration
echo "\nTest 3: Checking Configuration...\n";
echo "SESSION_DRIVER: " . config('session.driver') . "\n";
echo "SESSION_LIFETIME: " . config('session.lifetime') . "\n";
echo "APP_KEY set: " . (config('app.key') ? "yes" : "no") . "\n";
echo "CSRF except: " . json_encode(config('middleware.except', [])) . "\n";

// Test 4: Try actual login with test HTTP request
echo "\nTest 4: Attempting test authentication...\n";
$credentials = ['email' => 'admin@example.com', 'password' => 'password'];

if (Auth::attempt($credentials)) {
    echo "✓ Auth::attempt() succeeded\n";
    echo "  Authenticated as: " . Auth::user()->email . "\n";
    Auth::logout();
} else {
    echo "✗ Auth::attempt() failed\n";
}

echo "\n";
