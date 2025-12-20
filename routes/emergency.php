<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Emergency homepage - minimal dependencies
Route::get('/', function () {
    return view('emergency');
})->name('emergency-home');

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'environment' => app()->environment(),
        'php_version' => phpversion(),
        'laravel_version' => app()->version(),
    ]);
})->name('health');

// Database test
Route::get('/debug/db', function () {
    try {
        DB::connection()->getPdo();
        return response()->json([
            'status' => 'success',
            'message' => 'Database connected successfully!',
            'connection' => env('DB_CONNECTION'),
            'driver' => DB::connection()->getDriverName(),
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database connection failed: ' . $e->getMessage(),
            'connection' => env('DB_CONNECTION'),
        ], 500);
    }
});

// Environment debug
Route::get('/debug/env', function () {
    return response()->json([
        'APP_ENV' => env('APP_ENV'),
        'APP_DEBUG' => env('APP_DEBUG'),
        'APP_URL' => env('APP_URL'),
        'DB_CONNECTION' => env('DB_CONNECTION'),
        'CACHE_DRIVER' => env('CACHE_DRIVER'),
        'SESSION_DRIVER' => env('SESSION_DRIVER'),
        'PHP_VERSION' => phpversion(),
        'LARAVEL_VERSION' => app()->version(),
        'MEMORY_LIMIT' => ini_get('memory_limit'),
        'MAX_EXECUTION_TIME' => ini_get('max_execution_time'),
    ]);
});

// Simple test route
Route::get('/test', function () {
    return response()->json([
        'message' => 'HomyGo is working!',
        'timestamp' => now(),
        'server' => $_SERVER['HTTP_HOST'] ?? 'unknown',
    ]);
});
