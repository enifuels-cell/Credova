<!DOCTYPE html>
<<<<<<< HEAD
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#059669">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">

        <title>{{ config('app.name', 'HomyGo') }} - @yield('title', 'Property Rental in Cagayan de Oro')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Custom Property Grid Styles -->
        <link rel="stylesheet" href="{{ asset('css/custom-property-grid.css') }}">

        <!-- Mobile-Friendly & Accessibility Styles -->
        <style>
            /* Better touch targets for mobile */
            .touch-target { min-height: 44px; min-width: 44px; }

            /* Larger text for readability */
            @media (max-width: 640px) {
                html { font-size: 16px; }
                body { -webkit-text-size-adjust: 100%; }
            }

            /* Safe area for notched phones */
            .safe-bottom { padding-bottom: env(safe-area-inset-bottom); }
            .safe-top { padding-top: env(safe-area-inset-top); }

            /* Smooth momentum scrolling on iOS */
            .scroll-smooth { -webkit-overflow-scrolling: touch; }

            /* Remove tap highlight on mobile */
            a, button { -webkit-tap-highlight-color: transparent; }

            /* Focus visible for accessibility */
            :focus-visible { outline: 2px solid #059669; outline-offset: 2px; }

            /* Better scrollbar for touch */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
            ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
        </style>

        @stack('styles')
    </head>
    <body class="font-sans antialiased text-base">
        <div class="min-h-screen bg-gray-50">
            @include('layouts.navigation')

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-3 sm:px-6 lg:px-8">
=======
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- PWA Meta Tags -->
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="application-name" content="Homygo">
        <meta name="apple-mobile-web-app-title" content="Homygo">
        <meta name="theme-color" content="#4f46e5">
        <meta name="msapplication-navbutton-color" content="#4f46e5">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="msapplication-starturl" content="/">
        
        <!-- PWA Manifest -->
        <link rel="manifest" href="/manifest.json">
        
        <!-- Apple Touch Icons -->
        <link rel="apple-touch-icon" sizes="180x180" href="/icons/icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/icons/icon-192x192.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/icons/icon-192x192.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Global API Helper Script -->
        <script>
            // Global API helper functions for HTTPS enforcement
            window.API = {
                baseUrl: '{{ config('app.url') }}',
                
                // Create absolute HTTPS URL from relative path
                url: function(path) {
                    if (path.startsWith('http')) return path;
                    return this.baseUrl + (path.startsWith('/') ? path : '/' + path);
                },
                
                // Get default headers with CSRF token
                headers: function(additionalHeaders = {}) {
                    const headers = {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    };
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (csrfToken) {
                        headers['X-CSRF-TOKEN'] = csrfToken;
                    }
                    
                    return { ...headers, ...additionalHeaders };
                },
                
                // Enhanced fetch wrapper that ensures HTTPS
                fetch: function(url, options = {}) {
                    return fetch(this.url(url), {
                        headers: this.headers(options.headers || {}),
                        ...options
                    });
                }
            };
            
            // Override global fetch to use HTTPS by default
            const originalFetch = window.fetch;
            window.fetch = function(url, options = {}) {
                if (typeof url === 'string' && url.startsWith('/')) {
                    return originalFetch(window.API.url(url), options);
                }
                return originalFetch(url, options);
            };
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
<<<<<<< HEAD

            <!-- Footer -->
            @include('layouts.footer')
        </div>

=======
        </div>

        <!-- PWA Install Prompt -->
        <div id="pwa-install-prompt" class="hidden fixed bottom-4 left-4 right-4 bg-indigo-600 text-white p-4 rounded-lg shadow-lg z-50 md:left-auto md:right-4 md:max-w-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1 mr-3">
                    <p class="text-sm font-medium">Install Homygo App</p>
                    <p class="text-xs opacity-90">Get quick access and offline features</p>
                </div>
                <div class="flex space-x-2">
                    <button id="pwa-install-button" class="bg-white text-indigo-600 px-3 py-1 rounded text-sm font-medium">
                        Install
                    </button>
                    <button id="pwa-dismiss-button" class="text-white opacity-75 hover:opacity-100">
                        ✕
                    </button>
                </div>
            </div>
        </div>

        <!-- Service Worker Registration -->
        <script>
            // Service Worker Registration
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/sw.js')
                        .then(function(registration) {
                            console.log('SW registered: ', registration);
                        })
                        .catch(function(registrationError) {
                            console.log('SW registration failed: ', registrationError);
                        });
                });
            }

            // PWA Install Prompt
            let deferredPrompt;
            const installPrompt = document.getElementById('pwa-install-prompt');
            const installButton = document.getElementById('pwa-install-button');
            const dismissButton = document.getElementById('pwa-dismiss-button');

            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                
                // Show install prompt if not dismissed
                if (!localStorage.getItem('pwa-dismissed')) {
                    installPrompt.classList.remove('hidden');
                }
            });

            installButton.addEventListener('click', (e) => {
                installPrompt.classList.add('hidden');
                
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the install prompt');
                        }
                        deferredPrompt = null;
                    });
                }
            });

            dismissButton.addEventListener('click', () => {
                installPrompt.classList.add('hidden');
                localStorage.setItem('pwa-dismissed', 'true');
            });

            // Hide prompt if app is already installed
            window.addEventListener('appinstalled', (evt) => {
                console.log('PWA was installed');
                installPrompt.classList.add('hidden');
            });

            // Network status indicator
            function updateNetworkStatus() {
                const status = navigator.onLine ? 'online' : 'offline';
                console.log('Network status:', status);
                
                if (!navigator.onLine) {
                    // Show offline indicator
                    if (!document.getElementById('offline-indicator')) {
                        const indicator = document.createElement('div');
                        indicator.id = 'offline-indicator';
                        indicator.className = 'fixed top-0 left-0 right-0 bg-red-500 text-white text-center py-2 text-sm z-50';
                        indicator.textContent = 'You are offline. Some features may be limited.';
                        document.body.appendChild(indicator);
                    }
                } else {
                    // Hide offline indicator
                    const indicator = document.getElementById('offline-indicator');
                    if (indicator) {
                        indicator.remove();
                    }
                }
            }

            window.addEventListener('online', updateNetworkStatus);
            window.addEventListener('offline', updateNetworkStatus);
            updateNetworkStatus(); // Check initial status
        </script>

>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
        @stack('scripts')
    </body>
</html>
