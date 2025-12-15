@props(['property'])

<div class="property-card bg-white rounded-xl sm:rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 group flex flex-col h-full">
    <!-- Image -->
    <a href="{{ route('properties.show', $property->slug) }}" class="block relative w-full aspect-[16/9] bg-gray-100 overflow-hidden flex-shrink-0">
        @if($property->images->count() > 0)
            @php
                $img = $property->images->first();
                $imgUrl = Str::startsWith($img->image_path, ['http', 'storage/'])
                    ? asset($img->image_path)
                    : asset($img->image_path);
                if (!Str::startsWith($img->image_path, ['http', 'storage/']) && !file_exists(public_path($img->image_path))) {
                    $imgUrl = asset('storage/' . $img->image_path);
                }
            @endphp
            <img src="{{ $imgUrl }}"
                alt="{{ $property->title }}"
                loading="lazy"
                srcset="{{ $imgUrl }} 600w, {{ $imgUrl }} 900w, {{ $imgUrl }} 1200w"
                sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                style="image-rendering: auto; image-rendering: crisp-edges; image-rendering: high-quality;">
        @else
            <div class="absolute inset-0 w-full h-full bg-gray-200 flex items-center justify-center">
                <svg class="w-12 h-12 sm:w-14 sm:h-14 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        <!-- Featured Badge -->
        @if($property->is_featured)
            <div class="absolute top-3 left-3 bg-yellow-400 text-yellow-900 px-3 py-1.5 rounded-full text-xs font-bold shadow-sm">
                ⭐ Featured
            </div>
        @endif

        <!-- Favorite Button -->
        @auth
            <button onclick="toggleFavorite({{ $property->id }})"
                class="absolute top-3 right-3 p-2.5 sm:p-3 bg-white/90 backdrop-blur-sm rounded-full shadow-md hover:bg-white active:scale-95 transition touch-target favorite-btn-{{ $property->id }}"
                aria-label="Add to favorites">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 {{ Auth::user()->favorites->contains('property_id', $property->id) ? 'text-red-500 fill-current' : 'text-gray-500' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>
        @endauth

        <!-- Property Type -->
        <div class="absolute bottom-3 left-3 bg-emerald-600/95 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm">
            {{ $property->propertyType->name ?? 'Property' }}
        </div>
    </a>

    <!-- Content -->
    <div class="p-4 sm:p-5 flex flex-col flex-1 justify-between">
        <a href="{{ route('properties.show', $property->slug) }}" class="block touch-target -m-1 p-1">
            <h3 class="font-semibold text-base sm:text-lg text-gray-800 hover:text-emerald-600 transition truncate">
                {{ $property->title }}
            </h3>
        </a>

        <!-- Location -->
        <div class="flex items-center text-gray-500 text-sm mt-2">
            <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="truncate">{{ $property->barangay->name ?? 'CDO' }}, Cagayan de Oro</span>
        </div>

        <!-- Features -->
        <div class="flex items-center gap-3 sm:gap-4 mt-3 text-gray-600 text-sm">
            @if($property->bedrooms > 0)
                <div class="flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v11a1 1 0 001 1h16a1 1 0 001-1V7M3 7l9 5 9-5M3 7h18"/>
                    </svg>
                    <span class="font-medium">{{ $property->bedrooms }}</span>
                </div>
            @endif
            @if($property->bathrooms > 0)
                <div class="flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    <span class="font-medium">{{ $property->bathrooms }}</span>
                </div>
            @endif
            @if($property->floor_area)
                <div class="flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                    </svg>
                    <span class="font-medium">{{ $property->floor_area }}m²</span>
                </div>
            @endif
        </div>

        <!-- Price & CTA -->
        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
            <div>
                <span class="text-xl sm:text-2xl font-bold text-emerald-600">{{ $property->formatted_price }}</span>
                <span class="text-gray-500 text-xs sm:text-sm">/month</span>
            </div>
            <a href="{{ route('properties.show', $property->slug) }}"
                class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg font-medium text-sm hover:bg-emerald-100 active:bg-emerald-200 transition touch-target">
                View
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
    </div>
</div>
