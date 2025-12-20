<x-app-layout>
<<<<<<< HEAD
    @section('title', 'Dashboard')

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-md p-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Welcome, {{ auth()->user()->name }}!</h1>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="bg-red-50 hover:bg-red-100 rounded-xl p-6 transition group">
                            <div class="flex items-center">
                                <div class="p-3 bg-red-100 rounded-full group-hover:bg-red-200 transition">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-800">Admin Dashboard</h3>
                                    <p class="text-sm text-gray-500">Manage the platform</p>
                                </div>
                            </div>
                        </a>
                    @endif

                    @if(auth()->user()->role === 'landlord' || auth()->user()->role === 'admin')
                        <a href="{{ route('landlord.dashboard') }}"
                            class="bg-emerald-50 hover:bg-emerald-100 rounded-xl p-6 transition group">
                            <div class="flex items-center">
                                <div class="p-3 bg-emerald-100 rounded-full group-hover:bg-emerald-200 transition">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-semibold text-gray-800">Landlord Dashboard</h3>
                                    <p class="text-sm text-gray-500">Manage your properties</p>
                                </div>
                            </div>
                        </a>
                    @endif

                    <a href="{{ route('favorites.index') }}"
                        class="bg-pink-50 hover:bg-pink-100 rounded-xl p-6 transition group">
                        <div class="flex items-center">
                            <div class="p-3 bg-pink-100 rounded-full group-hover:bg-pink-200 transition">
                                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-800">My Favorites</h3>
                                <p class="text-sm text-gray-500">View saved properties</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('properties.index') }}"
                        class="bg-blue-50 hover:bg-blue-100 rounded-xl p-6 transition group">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full group-hover:bg-blue-200 transition">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-800">Browse Properties</h3>
                                <p class="text-sm text-gray-500">Find your next home</p>
                            </div>
                        </div>
                    </a>
=======
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
