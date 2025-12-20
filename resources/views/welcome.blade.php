<<<<<<< HEAD
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
=======
{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HomyGO') }} - Exclusive Rentals in Cagayan de Oro</title>
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Custom font from Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-bg {
            background-image: linear-gradient(rgba(17, 24, 39, 0.6), rgba(17, 24, 39, 0.6)), 
                              url('https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    {{-- Header --}}
    <header class="absolute top-0 left-0 z-50 w-full p-4 md:px-8 bg-transparent">
        <div class="flex items-center justify-between mx-auto max-w-7xl">
            <div class="text-xl font-bold text-white tracking-tight">
                <a href="{{ route('welcome') }}">HomyGO</a>
            </div>
            <nav class="hidden md:flex space-x-6 text-white font-medium">
                <a href="{{ route('welcome') }}" class="hover:text-teal-300 transition-colors">Home</a>
                <a href="#properties" class="hover:text-teal-300 transition-colors">Properties</a>
                <a href="#host" class="hover:text-teal-300 transition-colors">Become a Host</a>
                <a href="#contact" class="hover:text-teal-300 transition-colors">Contact</a>
            </nav>
            <div class="hidden md:flex space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-white font-medium hover:text-teal-300 transition-colors">Sign In</a>
                    <a href="{{ route('register') }}" class="bg-white text-gray-900 px-4 py-2 rounded-full font-bold shadow-lg hover:bg-gray-200 transition-colors">Sign Up</a>
                @else
                    @if(Auth::user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="text-white font-medium hover:text-teal-300 transition-colors">Admin</a>
                    @elseif(Auth::user()->hasRole('landlord'))
                        <a href="{{ route('landlord.dashboard') }}" class="text-white font-medium hover:text-teal-300 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('renter.dashboard') }}" class="text-white font-medium hover:text-teal-300 transition-colors">Dashboard</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-white text-gray-900 px-4 py-2 rounded-full font-bold shadow-lg hover:bg-gray-200 transition-colors">Sign Out</button>
                    </form>
                @endguest
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main>
        {{-- Hero Section --}}
        <section class="relative h-[80vh] md:h-[90vh] flex items-center justify-center text-white hero-bg">
            <div class="relative z-10 text-center max-w-4xl px-4">
                <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4">Find your exclusive stay in Cagayan de Oro.</h1>
                <p class="text-lg md:text-xl font-medium opacity-90 mb-8">
                    Discover hand-picked properties for your perfect visit.
                </p>

                {{-- Search Bar --}}
                <form action="{{ route('properties.index') }}" method="GET" class="bg-white p-2 md:p-4 rounded-full shadow-2xl flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4">
                    @csrf
                    <div class="flex items-center w-full md:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500 ml-3"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <input type="text" name="location" placeholder="Location" class="w-full bg-transparent px-2 py-3 md:py-2 text-gray-800 placeholder-gray-400 focus:outline-none" value="Cagayan de Oro"/>
                    </div>
                    <div class="flex items-center w-full md:w-auto md:border-l border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500 ml-3"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        <input type="date" name="check_in" class="w-full bg-transparent px-2 py-3 md:py-2 text-gray-800 placeholder-gray-400 focus:outline-none"/>
                    </div>
                    <div class="flex items-center w-full md:w-auto md:border-l border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-500 ml-3"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <input type="number" name="guests" placeholder="Guests" class="w-full bg-transparent px-2 py-3 md:py-2 text-gray-800 placeholder-gray-400 focus:outline-none" min="1" value="2"/>
                    </div>
                    <button type="submit" class="w-full md:w-auto bg-teal-500 text-white font-bold px-8 py-3 rounded-full hover:bg-teal-600 transition-colors shadow-md flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="md:mr-0"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <span class="md:hidden ml-2">Search</span>
                    </button>
                </form>
            </div>
        </section>
};

        {{-- Features Section --}}
        <section class="py-16 md:py-24 bg-gray-50">
            <div class="mx-auto max-w-7xl px-4 text-center">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Why choose HomyGO?</h2>
                <p class="text-lg text-gray-600 mb-12 max-w-2xl mx-auto">
                    We provide a seamless and secure platform with verified hosts and curated properties.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-8 bg-white rounded-xl shadow-lg border border-gray-200">
                        <div class="mb-4 flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-500"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Verified Listings</h3>
                        <p class="text-gray-600">Every property is hand-inspected and verified for quality and accuracy.</p>
                    </div>
                    <div class="p-8 bg-white rounded-xl shadow-lg border border-gray-200">
                        <div class="mb-4 flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-500"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">24/7 Guest Support</h3>
                        <p class="text-gray-600">Our dedicated support team is available around the clock to assist you.</p>
                    </div>
                    <div class="p-8 bg-white rounded-xl shadow-lg border border-gray-200">
                        <div class="mb-4 flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-500"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Exclusive Experiences</h3>
                        <p class="text-gray-600">Access unique local experiences and amenities available only to our guests.</p>
                    </div>
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
                                'image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                                'title' => 'Modern Condo near Centrio Mall',
                                'price' => '₱2,500 / night',
                                'rating' => 4.8,
                                'amenities' => ['2 Beds', '1 Bath', 'WiFi'],
                            ],
                            [
                                'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                                'title' => 'Cozy Studio near Liceo U',
                                'price' => '₱1,800 / night',
                                'rating' => 4.9,
                                'amenities' => ['1 Bed', '1 Bath', 'Pet-friendly'],
                            ],
                            [
                                'image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
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
                                <a href="{{ route('properties.index') }}" class="block mt-6 w-full bg-gray-900 text-white py-3 text-center rounded-full font-bold hover:bg-gray-700 transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>        {{-- Why Choose Us Section --}}  
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
                                ['title' => 'Hand-Picked Properties', 'description' => 'We don't just list properties; we curate them. Every home is selected for its unique charm and quality.'],  
                                ['title' => 'Community Focused', 'description' => 'HomyGO is built by locals, for locals. We support our community by featuring small businesses and local hosts.'],  
                            ];  
                        @endphp  
                        @foreach($benefits as $benefit)  
                            <div class="flex items-start">  
                                <div class="flex-shrink-0">  
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-500 mt-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>  
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

// Why choose us section - as a more detailed list of benefits
const WhyChooseUs = () => {
  const benefits = [
    { title: 'Local Expertise', description: 'Our team lives and breathes Cagayan de Oro, ensuring you get the best local recommendations and support.' },
    { title: 'Hand-Picked Properties', description: 'We don\'t just list properties; we curate them. Every home is selected for its unique charm and quality.' },
    { title: 'Community Focused', description: 'Homygo is built by locals, for locals. We support our community by featuring small businesses and local hosts.' },
  ];

  return (
    <section className="py-16 md:py-24 bg-gray-50">
      <div className="mx-auto max-w-7xl px-4">
        <div className="md:grid md:grid-cols-2 md:gap-12">
          <div className="text-center md:text-left mb-10 md:mb-0">
            <p className="text-teal-500 text-sm font-bold uppercase mb-2">Our Promise</p>
            <h2 className="text-3xl md:text-4xl font-extrabold leading-tight">
              A rental platform designed for our city.
            </h2>
            <p className="mt-4 text-lg text-gray-600">
              We go beyond the typical rental experience by focusing exclusively on what makes our city great.
            </p>
          </div>
          <div className="space-y-8">
            {benefits.map((benefit, index) => (
              <div key={index} className="flex items-start">
                <div className="flex-shrink-0">
                  <CheckCircle size={24} className="text-teal-500 mt-1" />
                </div>
                <div className="ml-4">
                  <h3 className="text-xl font-bold mb-1">{benefit.title}</h3>
                  <p className="text-gray-600">{benefit.description}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
};


// Call-to-action section
const CTASection = () => {
  return (
    <section className="py-20 bg-gray-900 text-white text-center">
      <div className="mx-auto max-w-3xl px-4">
        <h2 className="text-3xl md:text-4xl font-extrabold mb-4">Become a Homygo Host</h2>
        <p className="text-lg font-medium opacity-90 mb-8">
          Share your space with a community of vetted guests. It's simple, secure, and rewarding.
        </p>
        <button className="bg-teal-500 text-white font-bold px-8 py-4 rounded-full shadow-lg hover:bg-teal-600 transition-colors">
          Start Hosting Today
        </button>
      </div>
    </section>
  );
};

// Footer component
const Footer = () => {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="bg-gray-800 text-gray-400 py-12">
      <div className="mx-auto max-w-7xl px-4">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
          <div>
            <h4 className="text-white font-bold mb-4">Homygo</h4>
            <p>Exclusive rentals for your city.</p>
          </div>
          <div>
            <h4 className="text-white font-bold mb-4">Company</h4>
            <ul className="space-y-2">
              <li><a href="#" className="hover:text-white transition-colors">About Us</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Careers</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Press</a></li>
            </ul>
          </div>
          <div>
            <h4 className="text-white font-bold mb-4">Support</h4>
            <ul className="space-y-2">
              <li><a href="#" className="hover:text-white transition-colors">Help Center</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Contact Us</a></li>
              <li><a href="#" className="hover:text-white transition-colors">Trust & Safety</a></li>
            </ul>
          </div>
          <div>
            <h4 className="text-white font-bold mb-4">Connect</h4>
            <div className="flex space-x-4">
              <a href="#" className="hover:text-white transition-colors"><Car /></a>
              <a href="#" className="hover:text-white transition-colors"><Dog /></a>
              <a href="#" className="hover:text-white transition-colors"><Heart /></a>
            </div>
          </div>
        </div>
        <div className="border-t border-gray-700 pt-8 text-center text-sm">
          <p>&copy; {currentYear} Homygo, Inc. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
};

export default App;
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
