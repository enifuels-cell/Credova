<x-app-layout>
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
</x-app-layout>
