<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HomyGO - Exclusive Rentals in Cagayan de Oro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 font-inter text-gray-800 antialiased">
    {{-- Header --}}
    <header class="bg-white shadow-sm border-b">
        <div class="mx-auto max-w-7xl px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-extrabold text-teal-600">HomyGO</h1>
                </div>
                <nav class="hidden md:flex space-x-8">
                    <a href="#" class="text-gray-700 hover:text-teal-600 transition-colors">Explore</a>
                    <a href="#" class="text-gray-700 hover:text-teal-600 transition-colors">Become a Host</a>
                </nav>
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-teal-600 transition-colors">Sign In</a>
                        <a href="{{ route('register') }}" class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition-colors">Sign Up</a>
                    @else
                        <div class="relative">
                            <span class="text-gray-700">Welcome, {{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline ml-4">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-teal-600 transition-colors">Logout</button>
                            </form>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <main>
        {{-- Hero Section --}}
        <section class="relative h-[80vh] md:h-[90vh] flex items-center justify-center text-white">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://placehold.co/1920x1080/0d1321/e5e7eb?text=Cagayan+de+Oro+City');">
                <div class="absolute inset-0 bg-gray-900 opacity-60"></div>
            </div>
            <div class="relative z-10 text-center max-w-4xl px-4">
                <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4">Find your exclusive stay in Cagayan de Oro.</h1>
                <p class="text-lg md:text-xl font-medium opacity-90 mb-8">
                    Discover hand-picked properties for your perfect visit.
                </p>

                {{-- Search Bar --}}
                <form action="{{ route('properties.search') }}" method="GET" class="bg-white p-2 md:p-4 rounded-full shadow-2xl flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4">
                    @csrf
                    <div class="flex items-center w-full md:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500 ml-3"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <input type="text" name="location" placeholder="Location" value="Cagayan de Oro" class="w-full bg-transparent px-2 py-3 md:py-2 text-gray-800 placeholder-gray-400 focus:outline-none">
                    </div>
                    <div class="flex items-center w-full md:w-auto md:border-l border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500 ml-3"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        <input type="text" name="dates" placeholder="Add Dates" class="w-full bg-transparent px-2 py-3 md:py-2 text-gray-800 placeholder-gray-400 focus:outline-none">
                    </div>
                    <div class="flex items-center w-full md:w-auto md:border-l border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500 ml-3"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m22 21-3-3m0 0a2 2 0 0 0-3-3"/></svg>
                        <input type="number" name="guests" placeholder="Guests" min="1" value="2" class="w-full bg-transparent px-2 py-3 md:py-2 text-gray-800 placeholder-gray-400 focus:outline-none">
                    </div>
                    <button type="submit" class="w-full md:w-auto bg-teal-500 text-white font-bold px-8 py-3 rounded-full hover:bg-teal-600 transition-colors shadow-md flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <span class="md:hidden ml-2">Search</span>
                    </button>
                </form>
            </div>
        </section>

        {{-- Features Section --}}
        <section class="py-16 md:py-24 bg-gray-50">
            <div class="mx-auto max-w-7xl px-4 text-center">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Why choose HomyGO?</h2>
                <p class="text-lg text-gray-600 mb-12 max-w-2xl mx-auto">
                    We provide a seamless and secure platform with verified hosts and curated properties.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @php
                        $features = [
                            [
                                'title' => 'Verified Listings',
                                'description' => 'Every property is hand-inspected and verified for quality and accuracy.',
                                'icon' => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>'
                            ],
                            [
                                'title' => '24/7 Guest Support', 
                                'description' => 'Our dedicated support team is available around the clock to assist you.',
                                'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>'
                            ],
                            [
                                'title' => 'Exclusive Experiences',
                                'description' => 'Access unique local experiences and amenities available only to our guests.',
                                'icon' => '<polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>'
                            ]
                        ];
                    @endphp
                    @foreach($features as $feature)
                        <div class="p-8 bg-white rounded-xl shadow-lg border border-gray-200">
                            <div class="mb-4 flex justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-500">
                                    {!! $feature['icon'] !!}
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">{{ $feature['title'] }}</h3>
                            <p class="text-gray-600">{{ $feature['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Featured Listings --}}
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
                                'amenities' => ['2 Beds', '1 Bath', 'WiFi']
                            ],
                            [
                                'image' => 'https://placehold.co/600x400/ef4444/ffffff?text=Cozy+Studio+near+the+University',
                                'title' => 'Cozy Studio near Liceo U',
                                'price' => '₱1,800 / night',
                                'rating' => 4.9,
                                'amenities' => ['1 Bed', '1 Bath', 'Pet-friendly']
                            ],
                            [
                                'image' => 'https://placehold.co/600x400/3b82f6/ffffff?text=Riverfront+Apartment+in+CDO',
                                'title' => 'Riverfront Apartment with City View',
                                'price' => '₱3,500 / night',
                                'rating' => 5.0,
                                'amenities' => ['3 Beds', '2 Baths', 'Parking']
                            ]
                        ];
                    @endphp
                    @foreach($listings as $listing)
                        <div class="bg-gray-100 rounded-xl shadow-lg overflow-hidden transition-transform duration-300 hover:scale-[1.02]">
                            <div class="relative h-60">
                                <img src="{{ $listing['image'] }}" alt="{{ $listing['title'] }}" class="w-full h-full object-cover">
                                <div class="absolute top-4 right-4 bg-teal-500 text-white px-3 py-1 rounded-full font-bold text-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg>
                                    {{ $listing['rating'] }}
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-2">{{ $listing['title'] }}</h3>
                                <p class="text-gray-500 font-medium mb-4">{{ $listing['price'] }}</p>
                                <div class="flex flex-wrap gap-4 text-gray-600">
                                    @foreach($listing['amenities'] as $amenity)
                                        <span class="flex items-center text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
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
            </div>
        </section>

        {{-- Why Choose Us Section --}}
        <section class="py-16 md:py-24 bg-gray-50">
            <div class="mx-auto max-w-7xl px-4">
                <div class="md:grid md:grid-cols-2 md:gap-12">
                    <div class="text-center md:text-left mb-10 md:mb-0">
                        <p class="text-teal-500 text-sm font-bold uppercase mb-2">Our Promise</p>
                        <h2 class="text-3xl md:text-4xl font-extrabold leading-tight">
                            A rental platform designed for our city.
                        </h2>
                        <p class="mt-4 text-lg text-gray-600">
                            We go beyond the typical rental experience by focusing exclusively on what makes our city great.
                        </p>
                    </div>
                    <div class="space-y-8">
                        @php
                            $benefits = [
                                ['title' => 'Local Expertise', 'description' => 'Our team lives and breathes Cagayan de Oro, ensuring you get the best local recommendations and support.'],
                                ['title' => 'Hand-Picked Properties', 'description' => 'We don\'t just list properties; we curate them. Every home is selected for its unique charm and quality.'],
                                ['title' => 'Community Focused', 'description' => 'HomyGO is built by locals, for locals. We support our community by featuring small businesses and local hosts.']
                            ];
                        @endphp
                        @foreach($benefits as $benefit)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-500 mt-1"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-xl font-bold mb-1">{{ $benefit['title'] }}</h3>
                                    <p class="text-gray-600">{{ $benefit['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA Section --}}
        <section class="py-20 bg-gray-900 text-white text-center">
            <div class="mx-auto max-w-3xl px-4">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Become a HomyGO Host</h2>
                <p class="text-lg font-medium opacity-90 mb-8">
                    Share your space with a community of vetted guests. It's simple, secure, and rewarding.
                </p>
                @guest
                    <a href="{{ route('register') }}" class="bg-teal-500 text-white font-bold px-8 py-4 rounded-full shadow-lg hover:bg-teal-600 transition-colors inline-block">
                        Start Hosting Today
                    </a>
                @else
                    <a href="{{ route('properties.create') }}" class="bg-teal-500 text-white font-bold px-8 py-4 rounded-full shadow-lg hover:bg-teal-600 transition-colors inline-block">
                        List Your Property
                    </a>
                @endguest
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-gray-400 py-12">
        <div class="mx-auto max-w-7xl px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="text-white font-bold mb-4">HomyGO</h4>
                    <p>Exclusive rentals for your city.</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Company</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Press</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Trust & Safety</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Connect</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} HomyGO, Inc. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
