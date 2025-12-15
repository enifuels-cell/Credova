<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50 safe-top">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 sm:h-18">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center touch-target">
                        <span class="text-2xl sm:text-3xl font-bold text-emerald-600">Homy</span>
                        <span class="text-2xl sm:text-3xl font-bold text-gray-800">Go</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 lg:space-x-8 md:-my-px md:ms-8 md:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-base">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('properties.index')" :active="request()->routeIs('properties.*')" class="text-base">
                        {{ __('Properties') }}
                    </x-nav-link>
                    @auth
                        @if(Auth::user()->isLandlord() || Auth::user()->isAdmin())
                            <x-nav-link :href="route('landlord.dashboard')" :active="request()->routeIs('landlord.*')" class="text-base">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                        @endif
                        @if(Auth::user()->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-base">
                                {{ __('Admin') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden md:flex md:items-center md:ms-6 gap-3">
                @auth
                    <!-- Favorites Link -->
                    <a href="{{ route('favorites.index') }}" class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-full transition touch-target flex items-center justify-center" aria-label="Favorites">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </a>

                    <!-- Settings Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2.5 border border-gray-200 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition touch-target">
                                <div class="max-w-[120px] truncate">{{ Auth::user()->name }}</div>
                                <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ Auth::user()->isAdmin() ? 'bg-red-100 text-red-800' : (Auth::user()->isLandlord() ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                                <div class="ms-2">
                                    <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')" class="py-3 text-base">
                                {{ __('Dashboard') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('profile.edit')" class="py-3 text-base">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('favorites.index')" class="py-3 text-base">
                                {{ __('My Favorites') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('inquiries.my')" class="py-3 text-base">
                                {{ __('My Inquiries') }}
                            </x-dropdown-link>

                            @if(Auth::user()->isLandlord() || Auth::user()->isAdmin())
                                <hr class="my-2">
                                <x-dropdown-link :href="route('landlord.properties.create')" class="py-3 text-base text-emerald-600">
                                    {{ __('+ Add Property') }}
                                </x-dropdown-link>
                            @endif

                            <hr class="my-2">

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="py-3 text-base text-red-600"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2.5 text-gray-700 hover:text-emerald-600 font-medium text-base transition touch-target">Log in</a>
                    <a href="{{ route('register') }}" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-medium text-base shadow-sm touch-target">Register</a>
                @endauth
            </div>

            <!-- Hamburger - Enhanced for Mobile -->
            <div class="-me-2 flex items-center md:hidden">
                @auth
                    <a href="{{ route('favorites.index') }}" class="p-3 text-gray-500 hover:text-emerald-600 touch-target" aria-label="Favorites">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </a>
                @endauth
                <button @click="open = ! open" class="inline-flex items-center justify-center p-3 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition touch-target" aria-label="Toggle menu">
                    <svg class="h-7 w-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu - Enhanced for Touch -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-white border-t border-gray-100"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0">
        <div class="py-3 space-y-1">
            <a href="{{ route('home') }}" class="flex items-center px-4 py-4 text-lg font-medium {{ request()->routeIs('home') ? 'text-emerald-600 bg-emerald-50 border-l-4 border-emerald-600' : 'text-gray-700 hover:bg-gray-50' }} touch-target">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                {{ __('Home') }}
            </a>
            <a href="{{ route('properties.index') }}" class="flex items-center px-4 py-4 text-lg font-medium {{ request()->routeIs('properties.*') ? 'text-emerald-600 bg-emerald-50 border-l-4 border-emerald-600' : 'text-gray-700 hover:bg-gray-50' }} touch-target">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                {{ __('Browse Properties') }}
            </a>
            @auth
                @if(Auth::user()->isLandlord() || Auth::user()->isAdmin())
                    <a href="{{ route('landlord.dashboard') }}" class="flex items-center px-4 py-4 text-lg font-medium {{ request()->routeIs('landlord.*') ? 'text-emerald-600 bg-emerald-50 border-l-4 border-emerald-600' : 'text-gray-700 hover:bg-gray-50' }} touch-target">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        {{ __('Landlord Dashboard') }}
                    </a>
                @endif
            @endauth
        </div>

        @auth
            <!-- Responsive Settings Options - Enhanced -->
            <div class="py-4 border-t border-gray-200 bg-gray-50">
                <div class="px-4 pb-4 flex items-center">
                    <div class="w-12 h-12 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="ml-3">
                        <div class="font-semibold text-lg text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <span class="ml-auto px-3 py-1 text-xs font-medium rounded-full {{ Auth::user()->isAdmin() ? 'bg-red-100 text-red-800' : (Auth::user()->isLandlord() ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </div>

                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-4 text-base font-medium text-gray-700 hover:bg-white touch-target">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        {{ __('Dashboard') }}
                    </a>

                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-4 text-base font-medium text-gray-700 hover:bg-white touch-target">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ __('Profile Settings') }}
                    </a>

                    <a href="{{ route('favorites.index') }}" class="flex items-center px-4 py-4 text-base font-medium text-gray-700 hover:bg-white touch-target">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        {{ __('My Favorites') }}
                    </a>

                    <a href="{{ route('inquiries.my') }}" class="flex items-center px-4 py-4 text-base font-medium text-gray-700 hover:bg-white touch-target">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        {{ __('My Inquiries') }}
                    </a>

                    @if(Auth::user()->isLandlord() || Auth::user()->isAdmin())
                        <a href="{{ route('landlord.properties.create') }}" class="flex items-center px-4 py-4 text-base font-medium text-emerald-600 hover:bg-white touch-target">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('Add New Property') }}
                        </a>
                    @endif

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" class="flex items-center w-full px-4 py-4 text-base font-medium text-red-600 hover:bg-white touch-target">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="py-4 border-t border-gray-200">
                <div class="px-4 space-y-3">
                    <a href="{{ route('login') }}" class="flex items-center justify-center w-full py-4 px-6 border-2 border-emerald-600 text-emerald-600 rounded-xl font-semibold text-lg hover:bg-emerald-50 transition touch-target">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        {{ __('Log In') }}
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center justify-center w-full py-4 px-6 bg-emerald-600 text-white rounded-xl font-semibold text-lg hover:bg-emerald-700 transition shadow-sm touch-target">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        {{ __('Create Account') }}
                    </a>
                </div>
            </div>
        @endauth
    </div>
</nav>
