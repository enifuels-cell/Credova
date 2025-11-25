<?php
/**
 * Check what middleware is applied to login route
 */

require 'bootstrap/app.php';

$routes = \Route::getRoutes();

foreach ($routes as $route) {
    if (strpos($route->uri(), 'login') !== false) {
        echo "Route: " . implode(',', $route->methods()) . " " . $route->uri() . "\n";
        echo "Name: " . ($route->name() ?? 'unnamed') . "\n";
        echo "Middleware: " . json_encode($route->middleware()) . "\n";
        echo "\n";
    }
}
