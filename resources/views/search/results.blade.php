<x-app-layout>
    @section('title', $query ? "Search: {$query}" : 'Search Properties')

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <style>
        /* Map container styling */
        #property-map {
            height: 400px;
            width: 100%;
            border-radius: 1rem;
            z-index: 1;
        }
        @media (min-width: 1024px) {
            #property-map {
                height: 500px;
            }
        }
        /* Custom marker styling */
        .property-marker {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            padding: 4px 8px;
            font-weight: 600;
            font-size: 12px;
            color: #059669;
            border: 2px solid #059669;
            white-space: nowrap;
        }
        .leaflet-popup-content {
            margin: 0;
            min-width: 200px;
        }
        .popup-content {
            padding: 0;
        }
        .popup-content img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        .popup-content .popup-info {
            padding: 12px;
        }
        .popup-content .popup-title {
            font-weight: 600;
            color: #1f2937;
            font-size: 14px;
            margin-bottom: 4px;
        }
        .popup-content .popup-location {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        .popup-content .popup-price {
            font-weight: 700;
            color: #059669;
            font-size: 16px;
        }
        .popup-content .popup-link {
            display: block;
            background: #059669;
            color: white;
            text-align: center;
            padding: 8px;
            border-radius: 0 0 8px 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
        }
        .popup-content .popup-link:hover {
            background: #047857;
        }
    </style>

    <div class="bg-gray-50 min-h-screen py-4 sm:py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-4 sm:mb-6">
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-emerald-600">Home</a>
                    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-gray-700">Search Results</span>
                </nav>

                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                    @if($areaName)
                        Properties in {{ $areaName }}
                    @elseif($query)
                        Search Results for "{{ $query }}"
                    @else
                        Browse All Properties
                    @endif
                </h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">
                    {{ $properties->total() }} {{ Str::plural('property', $properties->total()) }} found
                    @if($showMap)
                        <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Map View
                        </span>
                    @endif
                </p>
            </div>

            @if($showMap && $properties->count() > 0)
            <!-- Map Section -->
            <div class="mb-6" x-data="{ mapExpanded: true }">
                <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                    <div class="flex items-center justify-between p-4 border-b border-gray-100">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <span class="font-semibold text-gray-800">Property Map</span>
                            @if($matchedBarangays->count() > 0)
                                <span class="ml-2 text-sm text-gray-500">
                                    ({{ $matchedBarangays->count() }} {{ Str::plural('barangay', $matchedBarangays->count()) }})
                                </span>
                            @endif
                        </div>
                        <button @click="mapExpanded = !mapExpanded" class="p-2 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-5 h-5 text-gray-600 transition-transform" :class="mapExpanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                    <div x-show="mapExpanded" x-collapse>
                        <div id="property-map"></div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Area Info Badge (for area searches) -->
            @if($showMap && $matchedBarangays->count() > 0)
            <div class="mb-6 flex flex-wrap gap-2">
                <span class="text-sm text-gray-600 py-2">Barangays included:</span>
                @foreach($matchedBarangays->take(10) as $brgy)
                    <a href="{{ route('search', ['q' => $brgy->name]) }}"
                       class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-emerald-100 hover:text-emerald-700 transition">
                        {{ $brgy->name }}
                    </a>
                @endforeach
                @if($matchedBarangays->count() > 10)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-500">
                        +{{ $matchedBarangays->count() - 10 }} more
                    </span>
                @endif
            </div>
            @endif

            <!-- Search/Filter Form -->
            <div class="mb-6">
                <form action="{{ route('search') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="q" value="{{ $query }}"
                            placeholder="Search by property name, barangay, or area (Uptown, Downtown)..."
                            class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800 text-base">
                    </div>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold px-6 py-3.5 rounded-xl transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </button>
                </form>

                <!-- Quick search suggestions -->
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="text-sm text-gray-500 py-1">Try:</span>
                    <a href="{{ route('search', ['q' => 'Uptown']) }}" class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-sm hover:bg-emerald-100 transition">Uptown</a>
                    <a href="{{ route('search', ['q' => 'Downtown']) }}" class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm hover:bg-blue-100 transition">Downtown</a>
                    <a href="{{ route('search', ['q' => 'Carmen']) }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition">Carmen</a>
                    <a href="{{ route('search', ['q' => 'Lapasan']) }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition">Lapasan</a>
                    <a href="{{ route('search', ['q' => 'Seda']) }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition">Seda Hotel</a>
                </div>
            </div>

            <!-- Properties Grid -->
            @if($properties->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($properties as $property)
                        <a href="{{ route('properties.show', $property) }}" class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                            <!-- Property Image -->
                            <div class="relative h-48 sm:h-52 overflow-hidden">
                                @if($property->images->count() > 0)
                                    <img src="{{ asset('storage/' . $property->images->first()->image_path) }}"
                                         alt="{{ $property->title }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Property Type Badge -->
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 bg-white/90 backdrop-blur-sm rounded-lg text-xs font-semibold text-gray-700">
                                        {{ $property->propertyType->name ?? 'Property' }}
                                    </span>
                                </div>

                                <!-- Featured Badge -->
                                @if($property->is_featured)
                                    <div class="absolute top-3 right-3">
                                        <span class="px-2.5 py-1 bg-yellow-400 rounded-lg text-xs font-semibold text-yellow-900">
                                            ⭐ Featured
                                        </span>
                                    </div>
                                @endif

                                <!-- Price Badge -->
                                <div class="absolute bottom-3 right-3">
                                    <span class="px-3 py-1.5 bg-emerald-600 rounded-lg text-sm font-bold text-white shadow-lg">
                                        ₱{{ number_format($property->price) }}
                                        @if($property->propertyType && $property->propertyType->category === 'rental')
                                            <span class="text-xs font-normal opacity-90">/mo</span>
                                        @else
                                            <span class="text-xs font-normal opacity-90">/night</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Property Details -->
                            <div class="p-4">
                                <h3 class="font-bold text-gray-800 text-base sm:text-lg mb-1 line-clamp-1 group-hover:text-emerald-600 transition-colors">
                                    {{ $property->title }}
                                </h3>
                                <div class="flex items-center text-gray-500 text-sm mb-3">
                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <span class="truncate">{{ $property->barangay->name ?? 'Cagayan de Oro' }}</span>
                                </div>

                                <!-- Property Features -->
                                <div class="flex items-center gap-4 text-gray-600 text-sm">
                                    @if($property->bedrooms > 0)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v0z"/>
                                            </svg>
                                            {{ $property->bedrooms }} bed
                                        </span>
                                    @endif
                                    @if($property->bathrooms > 0)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            {{ $property->bathrooms }} bath
                                        </span>
                                    @endif
                                    @if($property->floor_area)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                            </svg>
                                            {{ number_format($property->floor_area) }} sqm
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                    <div class="properties-grid">
                        @foreach($properties as $property)
                            @include('components.property-card', ['property' => $property])
                        @endforeach
                    </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $properties->links() }}
                </div>
            @else
                <!-- No Results -->
                <div class="bg-white rounded-2xl shadow-md p-8 sm:p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No properties found</h3>
                    <p class="text-gray-600 mb-6">We couldn't find any properties matching "{{ $query }}"</p>
                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="{{ route('properties.index') }}" class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition">
                            Browse All Properties
                        </a>
                        <a href="{{ route('home') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition">
                            Back to Home
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($showMap && $properties->count() > 0)
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map centered on CDO
            const map = L.map('property-map').setView([{{ $mapCenter['lat'] }}, {{ $mapCenter['lng'] }}], {{ $mapZoom }});

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Custom icon for property markers
            const propertyIcon = L.divIcon({
                className: 'property-marker',
                html: '₱',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });

            // Property data from Laravel
            const properties = @json($mapProperties);

            // Add markers for each property
            const markers = [];
            properties.forEach(property => {
                const marker = L.marker([property.lat, property.lng], {
                    icon: L.divIcon({
                        className: 'property-marker',
                        html: `₱${property.price.replace(/,/g, '').slice(0, -3)}k`,
                        iconSize: [50, 24],
                        iconAnchor: [25, 12]
                    })
                }).addTo(map);

                // Create popup content
                const popupContent = `
                    <div class="popup-content">
                        <img src="${property.image}" alt="${property.title}" onerror="this.src='https://via.placeholder.com/200x120?text=🏠'">
                        <div class="popup-info">
                            <div class="popup-title">${property.title}</div>
                            <div class="popup-location">📍 ${property.barangay}</div>
                            <div class="popup-price">₱${property.price}${property.type === 'Hotel' || property.type === 'Resort' ? '/night' : '/mo'}</div>
                        </div>
                        <a href="/properties/${property.slug}" class="popup-link">View Details →</a>
                    </div>
                `;

                marker.bindPopup(popupContent, {
                    maxWidth: 250,
                    minWidth: 200,
                    className: 'property-popup'
                });

                markers.push(marker);
            });

            // Fit map to show all markers if there are multiple
            if (markers.length > 1) {
                const group = L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }

            // Add zoom controls
            map.zoomControl.setPosition('topright');
        });
    </script>
    @endif
</x-app-layout>
