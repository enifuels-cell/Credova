<x-app-layout>
<<<<<<< HEAD
    @section('title', 'Browse Properties')

    <div class="bg-gray-50 min-h-screen py-4 sm:py-6 md:py-8" x-data="{ showFilters: false }">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-4 sm:mb-6 md:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Properties for Rent</h1>
                <p class="text-gray-600 mt-1 sm:mt-2 text-sm sm:text-base">Find your perfect home in Cagayan de Oro City</p>
            </div>

            <!-- Mobile Filter Toggle Button -->
            <div class="lg:hidden mb-4">
                <button @click="showFilters = !showFilters"
                    class="w-full flex items-center justify-between bg-white rounded-xl shadow-sm px-4 py-4 text-gray-700 font-medium touch-target">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filters & Search
                    </span>
                    <svg class="w-5 h-5 transition-transform" :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>

            <div class="flex flex-col lg:flex-row gap-4 sm:gap-6 lg:gap-8">
                <!-- Filters Sidebar - Mobile Drawer -->
                <div class="lg:w-1/4" x-show="showFilters || window.innerWidth >= 1024"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak>
                    <form action="{{ route('properties.index') }}" method="GET" class="bg-white rounded-xl sm:rounded-2xl shadow-md p-4 sm:p-6 lg:sticky lg:top-20">
                        <h3 class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filters
                        </h3>

                        <!-- Search -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search properties..."
                                class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 py-3 text-base touch-target">
                        </div>

                        <!-- Property Type -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                            <select name="property_type" class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 py-3 text-base touch-target">
                                <option value="">All Types</option>
                                @foreach($propertyTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('property_type') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Barangay -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Barangay</label>
                            <select name="barangay" class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 py-3 text-base touch-target">
                                <option value="">All Barangays</option>
                                @foreach($barangays as $barangay)
                                    <option value="{{ $barangay->id }}" {{ request('barangay') == $barangay->id ? 'selected' : '' }}>
                                        {{ $barangay->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price Range (₱)</label>
                            <div class="flex gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}"
                                    placeholder="Min" min="0"
                                    class="w-1/2 rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 py-3 text-base touch-target">
                                <input type="number" name="max_price" value="{{ request('max_price') }}"
                                    placeholder="Max" min="0"
                                    class="w-1/2 rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 py-3 text-base touch-target">
                            </div>
                        </div>

                        <!-- Bedrooms -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bedrooms</label>
                            <select name="bedrooms" class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 py-3 text-base touch-target">
                                <option value="">Any</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>
                                        {{ $i }}+ Bedrooms
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Bathrooms -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bathrooms</label>
                            <select name="bathrooms" class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 py-3 text-base touch-target">
                                <option value="">Any</option>
                                @for($i = 1; $i <= 4; $i++)
                                    <option value="{{ $i }}" {{ request('bathrooms') == $i ? 'selected' : '' }}>
                                        {{ $i }}+ Bathrooms
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Features -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Features</label>
                            <div class="space-y-3">
                                <label class="flex items-center py-1 touch-target cursor-pointer">
                                    <input type="checkbox" name="furnished" value="1"
                                        {{ request('furnished') ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="ml-3 text-base text-gray-700">Furnished</span>
                                </label>
                                <label class="flex items-center py-1 touch-target cursor-pointer">
                                    <input type="checkbox" name="pets_allowed" value="1"
                                        {{ request('pets_allowed') ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="ml-3 text-base text-gray-700">Pets Allowed</span>
                                </label>
                                <label class="flex items-center py-1 touch-target cursor-pointer">
                                    <input type="checkbox" name="parking" value="1"
                                        {{ request('parking') ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="ml-3 text-base text-gray-700">Parking Available</span>
                                </label>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold py-3.5 px-4 rounded-xl transition touch-target text-base">
                                Apply Filters
                            </button>
                            <a href="{{ route('properties.index') }}" class="px-4 py-3.5 border-2 border-gray-300 rounded-xl hover:bg-gray-50 active:bg-gray-100 transition touch-target flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Properties -->
@if($properties->count() > 0)
    <div class="properties-grid">
        @foreach($properties as $property)
            @include('components.property-card', ['property' => $property])
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6 sm:mt-8">
        {{ $properties->links() }}
    </div>
@else


                    <!-- Properties -->
                    @if($properties->count() > 0)
                        <div class="properties-grid">
                            @foreach($properties as $property)
                                @include('components.property-card', ['property' => $property])
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6 sm:mt-8">
                            {{ $properties->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-md p-8 sm:p-12 text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">No Properties Found</h3>
                            <p class="text-gray-600 mb-6 text-sm sm:text-base">Try adjusting your filters or search criteria.</p>
                            <a href="{{ route('properties.index') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-semibold text-base touch-target">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Clear all filters
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateQueryParam(key, value) {
            const url = new URL(window.location.href);
            url.searchParams.set(key, value);
            return url.toString();
        }
    </script>
    @endpush
=======
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Property Listings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @can('create', App\Models\Property::class)
                <div class="mb-6">
                    <a href="{{ route('properties.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add New Property
                    </a>
                </div>
            @endcan

            @if($properties->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-6">
                    @foreach ($properties as $property)
                        <div class="bg-white shadow-lg rounded-2xl overflow-hidden transition-transform hover:scale-105 hover:shadow-xl">
                            @if($property->image)
                                <img src="{{ asset('storage/' . $property->image) }}" class="h-48 w-full object-cover" alt="{{ $property->title }}">
                            @else
                                <div class="h-48 w-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    </svg>
                                </div>
                            @endif

                            <div class="p-4">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $property->title }}</h3>
                                <p class="text-gray-600 text-sm mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $property->location }}
                                </p>
                                <p class="text-gray-700 text-sm mb-3 line-clamp-2">{{ Str::limit($property->description, 100) }}</p>
                                <p class="text-red-500 font-bold text-lg mb-3">₱{{ number_format($property->price_per_night, 2) }} <span class="text-sm font-normal text-gray-600">/ night</span></p>

                                <div class="flex space-x-2">
                                    <a href="{{ route('properties.show', $property) }}" class="flex-1 text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                        View Details
                                    </a>
                                    @can('update', $property)
                                        <a href="{{ route('properties.edit', $property) }}" class="px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 px-6">
                    {{ $properties->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No properties found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new property listing.</p>
                    @can('create', App\Models\Property::class)
                        <div class="mt-6">
                            <a href="{{ route('properties.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add New Property
                            </a>
                        </div>
                    @endcan
                </div>
            @endif
        </div>
    </div>
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
</x-app-layout>
