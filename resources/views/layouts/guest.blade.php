<!DOCTYPE html>
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
        </div>
    </body>
</html>
