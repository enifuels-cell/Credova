<!DOCTYPE html>
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
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Footer -->
            @include('layouts.footer')
        </div>

        @stack('scripts')
    </body>
</html>
