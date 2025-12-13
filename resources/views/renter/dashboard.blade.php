<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Renter Dashboard - HomyGO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    @include('partials.header')

    <main>
        <!-- Hero Section -->
        <section class="relative h-[60vh] md:h-[70vh] flex items-center justify-center text-white">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://placehold.co/1920x1080/0d1321/e5e7eb?text=Welcome+to+Your+Renter+Dashboard');">
                <div class="absolute inset-0 bg-gray-900 opacity-60"></div>
            </div>
            <div class="relative z-10 text-center max-w-4xl px-4">
                <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4">Welcome, {{ Auth::user()->name ?? 'Guest' }}</h1>
                <p class="text-lg md:text-xl font-medium opacity-90 mb-8">
                    Find your perfect home in Cagayan de Oro
                </p>

                <form action="{{ route('properties.index') }}" method="GET" class="bg-white p-2 md:p-4 rounded-full shadow-2xl flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4">
                    <div class="flex items-center w-full md:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500 ml-3"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <input type="text" name="location" placeholder="Location" class="w-full bg-transparent px-2 py-3 md:py-2 text-gray-800 placeholder-gray-400 focus:outline-none" value="Cagayan de Oro"/>
                    </div>
                    <div class="flex items-center w-full md:w-auto md:border-l border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500 ml-3"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        <input type="text" name="dates" placeholder="Add Dates" class="w-full bg-transparent px-2 py-3 md:py-2 text-gray-800 placeholder-gray-400 focus:outline-none"/>
                    </div>
                    <div class="flex items-center w-full md:w-auto md:border-l border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500 ml-3"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <input type="number" name="guests" placeholder="Guests" class="w-full bg-transparent px-2 py-3 md:py-2 text-gray-800 placeholder-gray-400 focus:outline-none" min="1" value="2"/>
                    </div>
                    <button type="submit" class="w-full md:w-auto bg-teal-500 text-white font-bold px-8 py-3 rounded-full hover:bg-teal-600 transition-colors shadow-md flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="md:hidden"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <span class="md:hidden ml-2">Search</span>
                        <span class="hidden md:inline">Search</span>
                    </button>
                </form>
            </div>
        </section>

        <!-- Quick Stats Section -->
        <section class="py-12 md:py-16 bg-white">
            <div class="mx-auto max-w-7xl px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- My Bookings Card -->
                    <a href="{{ route('renter.bookings') }}" class="bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 hover:scale-[1.02] cursor-pointer border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 font-medium mb-2">My Bookings</p>
                                <h3 class="text-4xl font-extrabold text-blue-600">3</h3>
                                <p class="text-sm text-gray-500 mt-2">Active reservations</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500 opacity-30"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        </div>
                    </a>

                    <!-- Browse Properties Card -->
                    <a href="{{ route('properties.index') }}" class="bg-gradient-to-br from-teal-50 to-teal-100 p-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 hover:scale-[1.02] cursor-pointer border border-teal-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 font-medium mb-2">Browse Properties</p>
                                <h3 class="text-4xl font-extrabold text-teal-600">250+</h3>
                                <p class="text-sm text-gray-500 mt-2">Properties available</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-500 opacity-30"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v4"/></svg>
                        </div>
                    </a>

                    <!-- Profile Card -->
                    <a href="{{ route('profile.edit') }}" class="bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 hover:scale-[1.02] cursor-pointer border border-purple-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 font-medium mb-2">Profile</p>
                                <h3 class="text-lg font-extrabold text-purple-600">{{ Auth::user()->name ?? 'User' }}</h3>
                                <p class="text-sm text-gray-500 mt-2">View & edit profile</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-500 opacity-30"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- Featured Properties Section -->
        <section class="py-16 md:py-24 bg-white">
            <div class="mx-auto max-w-7xl px-4">
                <h2 class="text-3xl md:text-4xl font-extrabold text-center mb-12">Featured Properties</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @php
                        $listings = [
                            [
                                'image' => 'https://placehold.co/600x400/22c55e/ffffff?text=Modern+Condo+near+the+Mall',
                                'title' => 'Modern Condo near Centrio Mall',
                                'price' => '₱2,500 / night',
                                'rating' => 4.8,
                                'amenities' => ['2 Beds', '1 Bath', 'WiFi'],
                            ],
                            [
                                'image' => 'https://placehold.co/600x400/ef4444/ffffff?text=Cozy+Studio+near+the+University',
                                'title' => 'Cozy Studio near Liceo U',
                                'price' => '₱1,800 / night',
                                'rating' => 4.9,
                                'amenities' => ['1 Bed', '1 Bath', 'Pet-friendly'],
                            ],
                            [
                                'image' => 'https://placehold.co/600x400/3b82f6/ffffff?text=Riverfront+Apartment+in+CDO',
                                'title' => 'Riverfront Apartment with City View',
                                'price' => '₱3,500 / night',
                                'rating' => 5.0,
                                'amenities' => ['3 Beds', '2 Baths', 'Parking'],
                            ],
                        ];
                    @endphp
                    @foreach($listings as $listing)
                        <div class="bg-gray-100 rounded-xl shadow-lg overflow-hidden transition-transform duration-300 hover:scale-[1.02]">
                            <div class="relative h-60">
                                <img src="{{ $listing['image'] }}" alt="{{ $listing['title'] }}" class="w-full h-full object-cover">
                                <div class="absolute top-4 right-4 bg-teal-500 text-white px-3 py-1 rounded-full font-bold text-sm flex items-center">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> {{ $listing['rating'] }}
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-2">{{ $listing['title'] }}</h3>
                                <p class="text-gray-500 font-medium mb-4">{{ $listing['price'] }}</p>
                                <div class="flex flex-wrap gap-4 text-gray-600">
                                    @foreach($listing['amenities'] as $amenity)
                                        <span class="flex items-center text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v4"/><path d="M15 10v6a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-6"/><path d="M7 10v4a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-4"/></svg>
                                            {{ $amenity }}
                                        </span>
                                    @endforeach
                                </div>
                                <button class="mt-6 w-full bg-gray-900 text-white py-3 rounded-full font-bold hover:bg-gray-700 transition-colors">
                                    View Details
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- View All Button -->
                <div class="text-center mt-12">
                    <a href="{{ route('properties.index') }}" class="inline-block bg-white text-teal-600 border-2 border-teal-600 hover:bg-teal-600 hover:text-white px-8 py-3 rounded-full font-bold transition-colors duration-300">
                        View All Properties →
                    </a>
                </div>
            </div>
        </section>

        <!-- My Bookings Preview Section -->
        <section class="py-16 md:py-24 bg-gray-50">
            <div class="mx-auto max-w-7xl px-4">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-12">My Active Bookings</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Booking Card 1 -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="h-40 bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white opacity-50"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Booking #001</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <p><span class="font-medium">Check-in:</span> Dec 15, 2025</p>
                                <p><span class="font-medium">Check-out:</span> Dec 20, 2025</p>
                                <p><span class="font-medium">Status:</span> <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Confirmed</span></p>
                            </div>
                            <a href="{{ route('renter.bookings') }}" class="text-teal-600 font-bold hover:text-teal-700">View Details →</a>
                        </div>
                    </div>

                    <!-- Booking Card 2 -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="h-40 bg-gradient-to-r from-purple-400 to-purple-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white opacity-50"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Booking #002</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <p><span class="font-medium">Check-in:</span> Dec 25, 2025</p>
                                <p><span class="font-medium">Check-out:</span> Jan 2, 2026</p>
                                <p><span class="font-medium">Status:</span> <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">Pending</span></p>
                            </div>
                            <a href="{{ route('renter.bookings') }}" class="text-teal-600 font-bold hover:text-teal-700">View Details →</a>
                        </div>
                    </div>

                    <!-- Quick Book Card -->
                    <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl border-2 border-teal-200 p-8 flex flex-col items-center justify-center text-center hover:shadow-md transition-shadow cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-600 mb-4"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Plan Your Next Trip</h3>
                        <p class="text-sm text-gray-600 mb-4">Explore more properties and make your next reservation</p>
                        <a href="{{ route('properties.index') }}" class="bg-teal-600 text-white px-6 py-2 rounded-full font-bold hover:bg-teal-700 transition-colors">
                            Browse Now
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="py-16 md:py-24 bg-white">
            <div class="mx-auto max-w-7xl px-4">
                <h2 class="text-3xl md:text-4xl font-extrabold text-center mb-12">Why Choose HomyGO?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-teal-100 rounded-full mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-600"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Verified Hosts</h3>
                        <p class="text-gray-600">All our hosts are thoroughly verified to ensure your safety and comfort.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-teal-100 rounded-full mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-600"><circle cx="12" cy="12" r="1"/><path d="M12 1v6m0 6v6"/><path d="M4.22 4.22l4.24 4.24m0 5.08l-4.24 4.24"/><path d="M1 12h6m6 0h6"/><path d="M4.22 19.78l4.24-4.24m5.08 0l4.24 4.24"/><path d="M19.78 19.78l-4.24-4.24m0-5.08l4.24-4.24"/><path d="M23 12h-6m-6 0H5"/><path d="M19.78 4.22l-4.24 4.24m-5.08 0l-4.24-4.24"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Secure Payments</h3>
                        <p class="text-gray-600">Your payments are protected with our secure booking system.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-teal-100 rounded-full mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-600"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">24/7 Support</h3>
                        <p class="text-gray-600">Our support team is available anytime to help you with any concerns.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 md:py-24 bg-gradient-to-r from-gray-900 to-gray-800">
            <div class="mx-auto max-w-4xl px-4 text-center">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-6">Ready to Find Your Next Home?</h2>
                <p class="text-lg text-gray-300 mb-8">Explore hundreds of verified properties in Cagayan de Oro and book your stay today.</p>
                <a href="{{ route('properties.index') }}" class="inline-block bg-teal-500 hover:bg-teal-600 text-white font-bold px-10 py-4 rounded-full transition-colors duration-300 shadow-lg">
                    Start Exploring →
                </a>
            </div>
        </section>
    </main>

    @include('partials.footer')
</body>
</html>
