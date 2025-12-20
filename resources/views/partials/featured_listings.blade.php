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
    </div>
</section>
