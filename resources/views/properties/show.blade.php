<<<<<<< HEAD
<x-app-layout>
    @section('title', $property->title)

    <div class="bg-gray-50 min-h-screen py-4 sm:py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <!-- Breadcrumb - Hidden on mobile, shown on desktop -->
            <nav class="mb-4 sm:mb-6 hidden sm:block">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('home') }}" class="hover:text-emerald-600 touch-target py-1">Home</a></li>
                    <li>/</li>
                    <li><a href="{{ route('properties.index') }}" class="hover:text-emerald-600 touch-target py-1">Properties</a></li>
                    <li>/</li>
                    <li class="text-gray-800 truncate max-w-[200px]">{{ Str::limit($property->title, 30) }}</li>
                </ol>
            </nav>

            <!-- Mobile Back Button -->
            <div class="sm:hidden mb-4">
                <a href="{{ route('properties.index') }}" class="inline-flex items-center text-gray-600 hover:text-emerald-600 font-medium touch-target py-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Properties
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Images Gallery -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md overflow-hidden mb-4 sm:mb-6">
                        @if($property->images->count() > 0)
                            <div class="relative">
                                @php
                                    $mainImage = $property->images->first();
                                    $mainImageUrl = Str::startsWith($mainImage->image_path, ['http', 'storage/'])
                                        ? asset($mainImage->image_path)
                                        : asset($mainImage->image_path);
                                    if (!Str::startsWith($mainImage->image_path, ['http', 'storage/']) && !file_exists(public_path($mainImage->image_path))) {
                                        $mainImageUrl = asset('storage/' . $mainImage->image_path);
                                    }
                                @endphp
                                <img src="{{ $mainImageUrl }}"
                                    alt="{{ $property->title }}"
                                    class="w-full h-64 sm:h-80 md:h-96 object-cover"
                                    id="mainImage"
                                    srcset="{{ $mainImageUrl }} 600w, {{ $mainImageUrl }} 900w, {{ $mainImageUrl }} 1200w"
                                    sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 66vw"
                                    style="image-rendering: auto; image-rendering: crisp-edges; image-rendering: high-quality;">

                                @if($property->is_featured)
                                    <span class="absolute top-3 sm:top-4 left-3 sm:left-4 bg-yellow-400 text-yellow-900 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold shadow-sm">
                                        ⭐ Featured Property
                                    </span>
                                @endif
                            </div>

                            @if($property->images->count() > 1)
                                <div class="flex gap-2 p-3 sm:p-4 overflow-x-auto">
                                    @foreach($property->images as $image)
                                        @php
                                            $imgUrl = Str::startsWith($image->image_path, ['http', 'storage/'])
                                                ? asset($image->image_path)
                                                : asset($image->image_path);
                                            if (!Str::startsWith($image->image_path, ['http', 'storage/']) && !file_exists(public_path($image->image_path))) {
                                                $imgUrl = asset('storage/' . $image->image_path);
                                            }
                                        @endphp
                                        <img src="{{ $imgUrl }}"
                                            alt="Property image"
                                            onclick="document.getElementById('mainImage').src = this.src"
                                            class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg cursor-pointer hover:opacity-75 active:opacity-50 transition flex-shrink-0 touch-target">
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="w-full h-64 sm:h-80 md:h-96 bg-gray-200 flex items-center justify-center">
                                <svg class="w-20 h-20 sm:w-24 sm:h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Property Details -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-4">
                            <div class="flex-1">
                                <span class="inline-block bg-emerald-100 text-emerald-800 px-3 py-1.5 rounded-full text-sm font-medium mb-2">
                                    {{ $property->propertyType->name ?? 'Property' }}
                                </span>
                                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $property->title }}</h1>
                                <div class="flex items-start text-gray-500 mt-2">
                                    <svg class="w-5 h-5 mr-1.5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <span class="text-sm sm:text-base">{{ $property->address }}, {{ $property->barangay->name ?? '' }}, Cagayan de Oro City</span>
                                </div>
                            </div>
                            @auth
                                <form action="{{ route('favorites.toggle', $property) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-3 border-2 rounded-xl hover:bg-gray-50 active:bg-gray-100 transition touch-target" aria-label="Add to favorites">
                                        <svg class="w-6 h-6 {{ Auth::user()->favorites->contains('property_id', $property->id) ? 'text-red-500 fill-current' : 'text-gray-400' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                </form>
                            @endauth
                        </div>

                        <!-- Mobile Price Display (shown on mobile only) -->
                        <div class="lg:hidden bg-emerald-50 rounded-xl p-4 mb-6">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-emerald-600">{{ $property->formatted_price }}</p>
                                <p class="text-gray-500 text-sm">per month</p>
                            </div>
                        </div>

                        <!-- Key Features -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 py-4 sm:py-6 border-y border-gray-200">
                            <div class="text-center bg-gray-50 rounded-xl p-3 sm:p-4">
                                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $property->bedrooms }}</p>
                                <p class="text-xs sm:text-sm text-gray-500">Bedrooms</p>
                            </div>
                            <div class="text-center bg-gray-50 rounded-xl p-3 sm:p-4">
                                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $property->bathrooms }}</p>
                                <p class="text-xs sm:text-sm text-gray-500">Bathrooms</p>
                            </div>
                            <div class="text-center bg-gray-50 rounded-xl p-3 sm:p-4">
                                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $property->floor_area ?? '-' }}</p>
                                <p class="text-xs sm:text-sm text-gray-500">Sqm Area</p>
                            </div>
                            <div class="text-center bg-gray-50 rounded-xl p-3 sm:p-4">
                                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $property->max_occupants ?? '-' }}</p>
                                <p class="text-xs sm:text-sm text-gray-500">Max Occupants</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Description</h2>
                            <p class="text-gray-600 whitespace-pre-line text-sm sm:text-base leading-relaxed">{{ $property->description }}</p>
                        </div>

                        <!-- Property Details List -->
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Property Details</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="flex justify-between py-3 px-4 bg-gray-50 rounded-xl">
                                    <span class="text-gray-500 text-sm sm:text-base">Property Type</span>
                                    <span class="font-medium text-gray-800 text-sm sm:text-base">{{ $property->propertyType->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between py-3 px-4 bg-gray-50 rounded-xl">
                                    <span class="text-gray-500 text-sm sm:text-base">Floor Level</span>
                                    <span class="font-medium text-gray-800 text-sm sm:text-base">{{ $property->floor_level ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between py-3 px-4 bg-gray-50 rounded-xl">
                                    <span class="text-gray-500 text-sm sm:text-base">Furnished</span>
                                    <span class="font-medium {{ $property->is_furnished ? 'text-emerald-600' : 'text-gray-800' }} text-sm sm:text-base">{{ $property->is_furnished ? '✓ Yes' : 'No' }}</span>
                                </div>
                                <div class="flex justify-between py-3 px-4 bg-gray-50 rounded-xl">
                                    <span class="text-gray-500 text-sm sm:text-base">Pets Allowed</span>
                                    <span class="font-medium {{ $property->pets_allowed ? 'text-emerald-600' : 'text-gray-800' }} text-sm sm:text-base">{{ $property->pets_allowed ? '✓ Yes' : 'No' }}</span>
                                </div>
                                <div class="flex justify-between py-3 px-4 bg-gray-50 rounded-xl">
                                    <span class="text-gray-500 text-sm sm:text-base">Parking</span>
                                    <span class="font-medium text-gray-800 text-sm sm:text-base">{{ $property->parking_available ? $property->parking_slots . ' slots' : 'None' }}</span>
                                </div>
                                <div class="flex justify-between py-3 px-4 bg-gray-50 rounded-xl">
                                    <span class="text-gray-500 text-sm sm:text-base">Min. Lease</span>
                                    <span class="font-medium text-gray-800 text-sm sm:text-base">{{ $property->minimum_lease_months }} months</span>
                                </div>
                            </div>
                        </div>

                        <!-- Amenities -->
                        @if($property->amenities->count() > 0)
                            <div class="mt-6">
                                <h2 class="text-lg font-semibold text-gray-800 mb-3">Amenities</h2>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($property->amenities as $amenity)
                                        <span class="bg-emerald-50 text-emerald-700 px-3 py-2 rounded-xl text-sm font-medium">
                                            {{ $amenity->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Landmark -->
                        @if($property->landmark)
                            <div class="mt-6">
                                <h2 class="text-lg font-semibold text-gray-800 mb-3">Nearby Landmark</h2>
                                <p class="text-gray-600 text-sm sm:text-base">{{ $property->landmark }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Reviews Section -->
                    @if($property->reviews->count() > 0)
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-md p-4 sm:p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                                Reviews ({{ $property->reviews->count() }})
                            </h2>
                            <div class="space-y-4">
                                @foreach($property->reviews as $review)
                                    <div class="border-b border-gray-100 pb-4 last:border-0">
                                        <div class="flex items-center mb-2">
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-5 h-5 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20">
                                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm text-gray-500">by {{ $review->user->name }}</span>
                                        </div>
                                        <p class="text-gray-600 text-sm sm:text-base">{{ $review->comment }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Price Card -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md p-4 sm:p-6 mb-4 sm:mb-6 lg:sticky lg:top-20">
                        <!-- Desktop Price Display -->
                        <div class="hidden lg:block text-center border-b border-gray-200 pb-6 mb-6">
                            <p class="text-3xl sm:text-4xl font-bold text-emerald-600">{{ $property->formatted_price }}</p>
                            <p class="text-gray-500">per month</p>
                        </div>

                        <!-- Payment Details -->
                        <div class="space-y-3 mb-6">
                            @if($property->deposit)
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-500 text-sm sm:text-base">Security Deposit</span>
                                    <span class="font-medium text-sm sm:text-base">₱{{ number_format($property->deposit, 2) }}</span>
                                </div>
                            @endif
                            @if($property->advance)
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-500 text-sm sm:text-base">Advance Payment</span>
                                    <span class="font-medium text-sm sm:text-base">₱{{ number_format($property->advance, 2) }}</span>
                                </div>
                            @endif
                            @if($property->available_from)
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-500 text-sm sm:text-base">Available From</span>
                                    <span class="font-medium text-sm sm:text-base">{{ $property->available_from->format('M d, Y') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Inquiry Form -->
                        <form action="{{ route('inquiries.store', $property) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <input type="text" name="name" placeholder="Your Name" required
                                    value="{{ auth()->check() ? auth()->user()->name : old('name') }}"
                                    class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 py-3 text-base touch-target">
                                @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <input type="email" name="email" placeholder="Your Email" required
                                    value="{{ auth()->check() ? auth()->user()->email : old('email') }}"
                                    class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 py-3 text-base touch-target">
                                @error('email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <input type="tel" name="phone" placeholder="Phone Number"
                                    value="{{ auth()->check() ? auth()->user()->phone : old('phone') }}"
                                    class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 py-3 text-base touch-target">
                            </div>
                            <div>
                                <textarea name="message" rows="3" placeholder="I'm interested in this property..." required
                                    class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 py-3 text-base touch-target">{{ old('message') }}</textarea>
                                @error('message')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                            <button type="submit"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold py-4 px-6 rounded-xl transition touch-target text-base">
                                Send Inquiry
                            </button>
                        </form>
                    </div>

                    <!-- Landlord Info -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-md p-4 sm:p-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Property Owner</h3>
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-emerald-600 font-bold text-xl">
                                    {{ strtoupper(substr($property->landlord->name ?? 'U', 0, 1)) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-800 text-base">{{ $property->landlord->name ?? 'Unknown' }}</p>
                                <p class="text-sm text-gray-500">Property Owner</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Similar Properties -->
            @if($similarProperties->count() > 0)
                <div class="mt-8 sm:mt-12">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">Similar Properties</h2>
                    <div class="properties-grid">
                        @foreach($similarProperties as $similar)
                            @include('components.property-card', ['property' => $similar])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
=======
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $property->title }} - Homygo</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
      background-color: #f8f9fa;
    }
    
    .card-shadow {
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">

  <!-- Header -->
  <header class="bg-white/95 backdrop-blur-sm shadow-sm fixed top-0 left-0 right-0 z-50">
    <div class="flex items-center justify-between px-4 py-3">
      <!-- Logo -->
      <div class="flex items-center">
        <a href="{{ Auth::user() && Auth::user()->hasRole('landlord') ? route('owner.dashboard') : route('renter.dashboard') }}">
          <img src="{{ asset('header.svg') }}" alt="Homygo" class="h-8" />
        </a>
      </div>
      
      <!-- User Info & Menu -->
      @auth
        <div class="flex items-center space-x-3">
          <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
          <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">
            {{ substr(Auth::user()->name, 0, 1) }}
          </div>
          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 px-2 py-1 rounded">
              Logout
            </button>
          </form>
        </div>
      @else
        <div class="flex items-center space-x-2">
          <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 px-2 py-1 rounded">Login</a>
          <a href="{{ route('register') }}" class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Sign Up</a>
        </div>
      @endauth
    </div>
  </header>

  <!-- Main Content -->
  <main class="pt-20 px-4 pb-8">
    <div class="max-w-md mx-auto space-y-6">

      @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
          {{ session('success') }}
        </div>
      @endif

      <!-- Property Image -->
      <div class="bg-white rounded-lg card-shadow overflow-hidden">
        @if($property->image)
          <img src="{{ asset('storage/' . $property->image) }}" 
               alt="{{ $property->title }}" 
               class="w-full h-64 object-cover">
        @else
          <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
            </svg>
          </div>
        @endif
      </div>

      <!-- Property Details -->
      <div class="bg-white rounded-lg card-shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $property->title }}</h1>
        
        <!-- Location -->
        <div class="flex items-center text-gray-600 mb-4">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
          {{ $property->location }}
        </div>

        <!-- Price -->
        <div class="mb-6">
          <span class="text-3xl font-bold text-green-600">₱{{ number_format($property->price_per_night) }}</span>
          <span class="text-gray-600">/night</span>
        </div>

        <!-- Description -->
        <div class="mb-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-2">Description</h3>
          <p class="text-gray-700 leading-relaxed">{{ $property->description }}</p>
        </div>

        <!-- Property Owner -->
        <div class="border-t pt-4 mb-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-2">Property Owner</h3>
          <div class="flex items-center">
            <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">
              {{ substr($property->user->name, 0, 1) }}
            </div>
            <div>
              <p class="font-medium text-gray-800">{{ $property->user->name }}</p>
              <p class="text-sm text-gray-600">Property Owner</p>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
          @auth
            @if(!Auth::user()->hasRole('landlord') || Auth::id() !== $property->user_id)
              <!-- Book Now Button for Renters -->
              <a href="{{ route('bookings.create', $property) }}" class="block w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors duration-200 text-center">
                Book This Property
              </a>
              <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-medium transition-colors duration-200">
                Contact Owner
              </button>
            @endif

            @if(Auth::id() === $property->user_id)
              <!-- Owner Actions -->
              <div class="flex space-x-3">
                <a href="{{ route('properties.edit', $property) }}" 
                   class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors duration-200 text-center">
                  Edit Property
                </a>
                <form method="POST" action="{{ route('properties.destroy', $property) }}" class="flex-1">
                  @csrf
                  @method('DELETE')
                  <button type="submit" 
                          onclick="return confirm('Are you sure you want to delete this property?')"
                          class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-medium transition-colors duration-200">
                    Delete
                  </button>
                </form>
              </div>
            @endif
          @else
            <!-- Guest Actions -->
            <div class="text-center py-4">
              <p class="text-gray-600 mb-4">Please login to book this property</p>
              <div class="space-y-2">
                <a href="{{ route('login') }}" 
                   class="block w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors duration-200 text-center">
                  Login to Book
                </a>
                <a href="{{ route('register') }}" 
                   class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-medium transition-colors duration-200 text-center">
                  Create Account
                </a>
              </div>
            </div>
          @endauth
        </div>
      </div>

      <!-- Back Button -->
      <div class="text-center mt-8">
        <a href="{{ url()->previous() }}" 
           class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Back
        </a>
      </div>

    </div>
  </main>

</body>
</html>
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
