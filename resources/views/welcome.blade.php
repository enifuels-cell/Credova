<x-app-layout>
    @section('title', 'Find Your Perfect Home in Cagayan de Oro')

    <!-- Hero Section - Mobile Optimized -->
    <section class="relative bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500 rounded-full -translate-y-1/2 translate-x-1/2 opacity-20"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-yellow-400 rounded-full translate-y-1/2 -translate-x-1/2 opacity-10"></div>

        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-12 sm:py-16 md:py-20 lg:py-28">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 sm:mb-6 leading-tight">
                    Find Your Perfect Home in
                    <br>
                    <span class="block text-yellow-400 mt-1">Cagayan de Oro</span>
                </h1>
                <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-emerald-100 mb-6 sm:mb-8 max-w-3xl mx-auto px-2">
                    Discover apartments, houses, hotels, and event spaces in the City of Golden Friendship.
                </p>

                <!-- Search Form - Mobile Optimized -->
                <div class="max-w-2xl mx-auto" x-data="searchDropdown()">
                    <form action="{{ route('properties.index') }}" method="GET" class="relative">
                        <div class="relative flex items-center bg-white/90 shadow-2xl rounded-3xl border-2 border-gray-100 focus-within:border-emerald-500 transition-all duration-200 px-4 sm:px-8 py-2 sm:py-3">
                            <div class="flex items-center w-full">
                                <span class="flex-shrink-0 flex items-center justify-center h-8 w-8 sm:h-10 sm:w-10">
                                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </span>
                                <div class="relative flex-1 flex items-center">
                                    <input type="text" name="search" x-model="query" @input="filterSuggestions()" @focus="showDropdown = true" @blur="showDropdown = false"
                                        placeholder=" "
                                        autocomplete="off"
                                        class="peer w-full bg-transparent pr-4 py-2 text-base sm:text-lg font-semibold text-emerald-900 rounded-3xl border-none focus:ring-0 placeholder-transparent focus:placeholder-emerald-300 transition-all duration-200">
                                    <label class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-400 text-base sm:text-lg font-semibold pointer-events-none transition-all duration-200 origin-left
                                        peer-focus:text-emerald-600 peer-focus:text-base"
                                        :class="{'opacity-0': query.length > 0}">
                                        Search apartments, hotels, venues...
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="ml-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold px-7 py-3 rounded-2xl shadow-lg transition-all duration-150 text-lg sm:text-xl flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span class="hidden sm:inline">Search</span>
                            </button>
                        </div>
                        <!-- Dropdown Suggestions - Modern Style -->
                        <div x-show="showDropdown && (filteredProperties.length > 0 || filteredTypes.length > 0 || query.length > 0)"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="absolute left-0 right-0 top-full mt-3 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 max-h-96 overflow-y-auto overscroll-contain">
                            <!-- ...existing code for dropdown suggestions... -->
                            <template x-if="filteredProperties.length > 0">
                                <div>
                                    <div class="px-6 py-3 bg-emerald-50 text-xs font-semibold text-emerald-700 uppercase tracking-wider sticky top-0 rounded-t-2xl">🏠 Properties</div>
                                    <template x-for="property in filteredProperties" :key="property.id">
                                        <a :href="'{{ url('/properties') }}/' + property.id"
                                            class="flex items-center px-6 py-4 hover:bg-emerald-50 active:bg-emerald-100 cursor-pointer transition touch-target border-b border-gray-50 last:border-0">
                                            <div class="w-16 h-16 rounded-xl overflow-hidden mr-4 flex-shrink-0 bg-gray-100">
                                                <img :src="property.image" :alt="property.title" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/64?text=🏠'">
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-semibold text-emerald-900 text-lg truncate" x-text="property.title"></div>
                                                <div class="text-sm text-gray-500 truncate" x-text="property.location"></div>
                                                <div class="text-sm font-semibold text-emerald-600" x-text="property.price"></div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </template>
                            <template x-if="filteredTypes.length > 0">
                                <div>
                                    <div class="px-6 py-3 bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider sticky top-0">📂 Property Types</div>
                                    <template x-for="type in filteredTypes" :key="type.id">
                                        <a :href="'{{ route('properties.index') }}?property_type=' + type.id"
                                            class="flex items-center px-6 py-4 hover:bg-emerald-50 active:bg-emerald-100 cursor-pointer transition touch-target">
                                            <div class="w-14 h-14 rounded-full flex items-center justify-center mr-4 flex-shrink-0"
                                                :class="type.category === 'rental' ? 'bg-emerald-100' : (type.category === 'stays' ? 'bg-blue-100' : 'bg-purple-100')">
                                                <svg class="w-7 h-7" :class="type.category === 'rental' ? 'text-emerald-600' : (type.category === 'stays' ? 'text-blue-600' : 'text-purple-600')" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-semibold text-emerald-900 text-lg" x-text="type.name"></div>
                                                <div class="text-sm text-gray-500 truncate" x-text="type.description"></div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </template>
                            <template x-if="query.length > 0">
                                <div class="border-t border-gray-100">
                                    <button type="submit" class="flex items-center w-full px-6 py-4 hover:bg-emerald-50 active:bg-emerald-100 cursor-pointer transition text-left touch-target">
                                        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                            <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-emerald-900 text-lg">Search for "<span x-text="query"></span>"</div>
                                            <div class="text-sm text-gray-500">Find properties matching your search</div>
                                        </div>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </form>
                </div>

                <!-- Quick Stats - Mobile Friendly -->

                @php
                    $typesForJs = $propertyTypes->map(function($t) {
                        return [
                            'id' => $t->id,
                            'name' => $t->name,
                            'category' => $t->category,
                            'description' => $t->description ?? ''
                        ];
                    })->values();

                    $propertiesForJs = $latestProperties->merge($featuredProperties)->unique('id')->map(function($p) {
                        return [
                            'id' => $p->id,
                            'title' => $p->title,
                            'location' => ($p->barangay->name ?? 'Cagayan de Oro') . ', CDO',
                            'address' => $p->address ?? '',
                            'price' => '₱' . number_format($p->price) . ($p->propertyType && $p->propertyType->category === 'rental' ? '/mo' : ($p->propertyType && $p->propertyType->category === 'events' ? '/event' : '/night')),
                            'image' => $p->images->first() ? asset('storage/' . $p->images->first()->image_path) : 'https://via.placeholder.com/56?text=🏠'
                        ];
                    })->values();
                @endphp
                <script>
                    function searchDropdown() {
                        return {
                            query: '',
                            showDropdown: false,
                            allTypes: @json($typesForJs),
                            allProperties: @json($propertiesForJs),
                            filteredTypes: [],
                            filteredProperties: [],
                            filterSuggestions() {
                                if (this.query.length === 0) {
                                    this.filteredTypes = this.allTypes.slice(0, 6);
                                    this.filteredProperties = [];
                                } else {
                                    const q = this.query.toLowerCase();
                                    // Filter properties by title, location, or address (Uptown, Downtown, etc.)
                                    this.filteredProperties = this.allProperties.filter(p =>
                                        p.title.toLowerCase().includes(q) ||
                                        p.location.toLowerCase().includes(q) ||
                                        (p.address && p.address.toLowerCase().includes(q))
                                    ).slice(0, 5);
                                    // Filter property types
                                    this.filteredTypes = this.allTypes.filter(type =>
                                        type.name.toLowerCase().includes(q) ||
                                        (type.description && type.description.toLowerCase().includes(q))
                                    ).slice(0, 4);
                                }
                            },
                            init() {
                                this.filteredTypes = this.allTypes.slice(0, 6);
                                this.filteredProperties = [];
                            }
                        }
                    }
                </script>
            </div>
        </div>
    </section>

    <!-- Property Types Section with Tabs - Mobile Optimized -->
    <section class="py-10 sm:py-12 md:py-16 bg-white" x-data="{ activeTab: 'homes' }">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="text-center mb-6 sm:mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-3 sm:mb-4">Browse by Property Type</h2>
                <p class="text-gray-600 mb-4 sm:mb-6 text-sm sm:text-base">Find the perfect space that fits your lifestyle</p>

                <!-- Tab Navigation - Scrollable on Mobile -->
                <div class="flex justify-start sm:justify-center gap-2 overflow-x-auto pb-2 -mx-4 px-4 sm:mx-0 sm:px-0 scrollbar-hide">
                    <button @click="activeTab = 'homes'"
                        :class="activeTab === 'homes' ? 'bg-emerald-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-full font-medium transition whitespace-nowrap text-sm sm:text-base touch-target flex-shrink-0">
                        🏠 Homes & Living
                    </button>
                    <button @click="activeTab = 'stays'"
                        :class="activeTab === 'stays' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-full font-medium transition whitespace-nowrap text-sm sm:text-base touch-target flex-shrink-0">
                        🏨 Stays & Hotels
                    </button>
                    <button @click="activeTab = 'events'"
                        :class="activeTab === 'events' ? 'bg-purple-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-full font-medium transition whitespace-nowrap text-sm sm:text-base touch-target flex-shrink-0">
                        🎉 Event Spaces
                    </button>
                </div>
            </div>

            <!-- Homes & Living Spaces -->
            <div x-show="activeTab === 'homes'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-data="{ showAll: false }">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
                    @php $totalTypes = count($rentalTypes ?? []); @endphp
                    @forelse($rentalTypes ?? [] as $i => $type)
                        <a href="{{ route('properties.index', ['property_type' => $type->id]) }}"
                            x-show="showAll || {{ $i }} < 2"
                            class="bg-gray-50 hover:bg-emerald-50 active:bg-emerald-100 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-center transition group touch-target"
                            x-transition:enter="transition ease-out duration-200" x-transition:leave="transition ease-in duration-150">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:bg-emerald-200 transition">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1 text-sm sm:text-base">{{ $type->name }}</h3>
                            <p class="text-xs sm:text-sm text-gray-500">{{ $type->properties_count ?? 0 }} listings</p>
                        </a>
                    @empty
                        <div class="col-span-full text-center text-gray-500 py-8">
                            No property types available yet.
                        </div>
                    @endforelse
                </div>
                @if(($rentalTypes ?? []) && count($rentalTypes) > 2)
                <div class="relative flex items-center justify-center my-4">
                    <div class="flex-grow border-t border-gray-200"></div>
                    <button @click="showAll = !showAll" type="button"
                        class="mx-4 bg-white border border-gray-300 rounded-full shadow hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-400 flex items-center justify-center w-10 h-10 transition">
                        <svg :class="{'rotate-180': showAll}" class="w-6 h-6 text-emerald-600 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="flex-grow border-t border-gray-200"></div>
                </div>
                @endif
            </div>

            <!-- Stays & Short-Term Rentals -->
            <div x-show="activeTab === 'stays'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
                    @forelse($staysTypes ?? [] as $type)
                        <a href="{{ route('properties.index', ['property_type' => $type->id]) }}"
                            class="bg-gray-50 hover:bg-blue-50 active:bg-blue-100 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-center transition group touch-target">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:bg-blue-200 transition">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1 text-sm sm:text-base">{{ $type->name }}</h3>
                            <p class="text-xs sm:text-sm text-gray-500">{{ $type->properties_count ?? 0 }} listings</p>
                        </a>
                    @empty
                        <div class="col-span-full text-center text-gray-500 py-8">
                            No stays available yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Event Spaces -->
            <div x-show="activeTab === 'events'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
                    @forelse($eventTypes ?? [] as $type)
                        <a href="{{ route('properties.index', ['property_type' => $type->id]) }}"
                            class="bg-gray-50 hover:bg-purple-50 active:bg-purple-100 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-center transition group touch-target">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:bg-purple-200 transition">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1 text-sm sm:text-base">{{ $type->name }}</h3>
                            <p class="text-xs sm:text-sm text-gray-500">{{ $type->properties_count ?? 0 }} listings</p>
                        </a>
                    @empty
                        <div class="col-span-full text-center text-gray-500 py-8">
                            No event spaces available yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Properties Section - Mobile Optimized -->
    @include('components.featured-properties-section')

    <!-- Divider between Featured and Latest Properties -->
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <hr class="my-8 border-gray-200">
    </div>

    <!-- Latest Properties Section - Mobile Optimized -->
    <section class="py-10 sm:py-12 md:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 sm:mb-12 gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Latest Properties</h2>
                    <p class="text-gray-600 text-sm sm:text-base">Newly listed properties in Cagayan de Oro</p>
                </div>
                <a href="{{ route('properties.index') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium text-base touch-target px-4 py-2 -mx-4 sm:mx-0 rounded-lg hover:bg-emerald-50 transition">
                    View All
                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="properties-grid">
                    @php $latest = ($latestProperties ?? collect())->take(4); @endphp
                    @foreach($latest as $property)
                        @include('components.property-card', ['property' => $property])
                    @endforeach
                    @if($latest->isEmpty())
                        <div class="col-span-full text-center py-12 px-4">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-base sm:text-lg">No properties listed yet. Be the first to list!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Barangays Section - Mobile Optimized -->
    @if(isset($popularBarangays) && $popularBarangays->count() > 0)
    <section class="py-10 sm:py-12 md:py-16 bg-emerald-50">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-3 sm:mb-4">Popular Locations</h2>
                <p class="text-gray-600 text-sm sm:text-base">Explore properties in popular Cagayan de Oro barangays</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                @foreach($popularBarangays as $barangay)
                    <a href="{{ route('properties.index', ['barangay' => $barangay->id]) }}"
                        class="bg-white hover:bg-emerald-100 active:bg-emerald-200 rounded-xl p-4 sm:p-5 text-center transition shadow-sm hover:shadow-md touch-target">
                        <h3 class="font-semibold text-gray-800 text-sm sm:text-base mb-1">{{ $barangay->name }}</h3>
                        <p class="text-xs sm:text-sm text-emerald-600 font-medium">{{ $barangay->properties_count ?? 0 }} properties</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section - Mobile Optimized -->
    <section class="py-12 sm:py-16 bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 text-white">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold mb-3 sm:mb-4">Are You a Property Owner?</h2>
            <p class="text-base sm:text-xl text-emerald-100 mb-6 sm:mb-8 max-w-2xl mx-auto">
                List your property on HomyGo and connect with thousands of potential tenants in Cagayan de Oro.
            </p>
            @auth
                @if(Auth::user()->isLandlord() || Auth::user()->isAdmin())
                    <a href="{{ route('landlord.properties.create') }}"
                        class="inline-flex items-center justify-center bg-white text-emerald-600 font-semibold py-4 px-8 rounded-xl hover:bg-gray-100 active:bg-gray-200 transition text-base sm:text-lg shadow-lg touch-target">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        List Your Property
                    </a>
                @endif
            @else
                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center bg-white text-emerald-600 font-semibold py-4 px-8 rounded-xl hover:bg-gray-100 active:bg-gray-200 transition text-base sm:text-lg shadow-lg touch-target">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Get Started - It's Free
                </a>
            @endauth
        </div>
    </section>
</x-app-layout>
