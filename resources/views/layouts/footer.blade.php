<footer class="bg-gray-800 text-white mt-12 sm:mt-16 safe-bottom">
    <div class="max-w-7xl mx-auto py-10 sm:py-12 px-3 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- About -->
            <div class="sm:col-span-2 lg:col-span-2">
                <a href="{{ route('home') }}" class="inline-flex items-center touch-target">
                    <span class="text-2xl sm:text-3xl font-bold text-emerald-400">Homy</span>
                    <span class="text-2xl sm:text-3xl font-bold text-white">Go</span>
                </a>
                <p class="text-gray-300 mt-4 mb-4 text-sm sm:text-base leading-relaxed">
                    Your trusted property rental platform exclusive to Cagayan de Oro City.
                    Find your perfect home in the City of Golden Friendship.
                </p>
                <p class="text-gray-400 text-sm">
                    Serving all 80 barangays of Cagayan de Oro.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('properties.index') }}" class="flex items-center text-gray-300 hover:text-emerald-400 transition text-base touch-target py-1">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            Browse Properties
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}" class="flex items-center text-gray-300 hover:text-emerald-400 transition text-base touch-target py-1">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            Home
                        </a>
                    </li>
                    @guest
                        <li>
                            <a href="{{ route('register') }}" class="flex items-center text-gray-300 hover:text-emerald-400 transition text-base touch-target py-1">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                List Your Property
                            </a>
                        </li>
                    @endguest
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
                <ul class="space-y-3 text-gray-300">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-emerald-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm sm:text-base">Cagayan de Oro City, Philippines</span>
                    </li>
                    <li>
                        <a href="mailto:info@homygo.ph" class="flex items-center hover:text-emerald-400 transition touch-target py-1">
                            <svg class="w-5 h-5 mr-3 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm sm:text-base">info@homygo.ph</span>
                        </a>
                    </li>
                    <li>
                        <a href="tel:+63881234567" class="flex items-center hover:text-emerald-400 transition touch-target py-1">
                            <svg class="w-5 h-5 mr-3 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="text-sm sm:text-base">(088) 123-4567</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm sm:text-base">
            <p>&copy; {{ date('Y') }} HomyGo. All rights reserved. Made with ❤️ in Cagayan de Oro.</p>
        </div>
    </div>
</footer>
