<!DOCTYPE html>
<<<<<<< HEAD
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#059669">

        <title>{{ config('app.name', 'HomyGo') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .touch-target { min-height: 44px; min-width: 44px; }
            .safe-bottom { padding-bottom: env(safe-area-inset-bottom); }
            a, button { -webkit-tap-highlight-color: transparent; }
            :focus-visible { outline: 2px solid #059669; outline-offset: 2px; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center py-6 px-4 sm:py-12 bg-gradient-to-br from-emerald-50 via-white to-emerald-50 safe-bottom">
            <!-- Logo -->
            <div class="mb-6 sm:mb-8">
                <a href="/" class="flex items-center touch-target">
                    <span class="text-3xl sm:text-4xl font-bold text-emerald-600">Homy</span>
                    <span class="text-3xl sm:text-4xl font-bold text-gray-800">Go</span>
                </a>
                <p class="text-center text-gray-500 text-sm mt-1">Property Rentals in CDO</p>
            </div>

            <div class="w-full max-w-md px-6 py-6 sm:py-8 bg-white shadow-xl rounded-2xl sm:rounded-3xl">
                {{ $slot }}
            </div>

            <!-- Back to home link -->
            <a href="/" class="mt-6 text-gray-500 hover:text-emerald-600 text-sm font-medium touch-target py-2 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Homepage
            </a>
=======
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Global API Helper Script -->
        <script>
            // Global API helper functions for HTTPS enforcement
            window.API = {
                baseUrl: @json(config('app.url')),
                
                // Check if we're in local development
                isLocal: function() {
                    const host = window.location.hostname;
                    const port = window.location.port;
                    return host === 'localhost' || 
                           host === '127.0.0.1' || 
                           host.includes('.local') ||
                           ['8000', '3000', '5173', '8080'].includes(port);
                },
                
                // Create appropriate URL (respects local development)
                url: function(path) {
                    if (path.startsWith('http')) return path;
                    
                    // In local development, use current protocol/host
                    if (this.isLocal()) {
                        return window.location.origin + (path.startsWith('/') ? path : '/' + path);
                    }
                    
                    // In production, force HTTPS
                    return this.baseUrl + (path.startsWith('/') ? path : '/' + path);
                },
                
                // Get default headers with CSRF token
                headers: function(additionalHeaders = {}) {
                    const headers = {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    };
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
                    }
                    
                    return { ...headers, ...additionalHeaders };
                },
                
                // Enhanced fetch wrapper
                fetch: function(url, options = {}) {
                    return fetch(this.url(url), {
                        headers: this.headers(options.headers || {}),
                        ...options
                    });
                }
            };
            
            // Only override global fetch in production environments
            if (!window.API.isLocal() && '{{ app()->environment() }}' === 'production') {
                const originalFetch = window.fetch;
                window.fetch = function(url, options = {}) {
                    if (typeof url === 'string' && url.startsWith('/')) {
                        return originalFetch(window.API.url(url), options);
                    }
                    return originalFetch(url, options);
                };
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <!-- HomyGo Logo -->
            <div class="flex justify-center mt-8">
                <a href="/">
                    <img src="{{ asset('H.svg') }}" alt="Homygo Logo" class="w-16 h-16 hover:scale-110 transition-transform duration-300">
                </a>
            </div>

            <!-- Login card below -->
            <div class="bg-white rounded-lg shadow-md p-6 max-w-sm mx-auto mt-4 w-full sm:max-w-md">
                {{ $slot }}
            </div>
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
        </div>
    </body>
</html>
