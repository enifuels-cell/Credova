<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Properties - Homygo</title>
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
        <a href="{{ route('owner.dashboard') }}">
          <img src="{{ asset('header.svg') }}" alt="Homygo" class="h-8" />
        </a>
      </div>
      
      <!-- User Info & Menu -->
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
    </div>
  </header>

  <!-- Main Content -->
  <main class="pt-20 px-4 pb-8">
    <div class="max-w-md mx-auto space-y-6">
      
      <!-- Header Section -->
      <div class="text-center py-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">My Properties</h1>
        <p class="text-gray-600">Manage your property listings</p>
      </div>

      @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
          {{ session('success') }}
        </div>
      @endif

      <!-- Add New Property Button -->
      <div class="text-center">
        <a href="{{ route('properties.create') }}" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Add New Property
        </a>
      </div>

      @if($properties->count() > 0)
        <!-- Properties List -->
        <div class="space-y-4">
          @foreach ($properties as $property)
            <div class="bg-white rounded-lg card-shadow overflow-hidden">
              <div class="flex">
                <!-- Property Image -->
                <div class="w-20 h-20 flex-shrink-0">
                  @if($property->image)
                    <img src="{{ asset('storage/' . $property->image) }}" 
                         alt="{{ $property->title }}" 
                         class="w-full h-full object-cover">
                  @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                      <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                      </svg>
                    </div>
                  @endif
                </div>

                <!-- Property Details -->
                <div class="flex-1 p-4">
                  <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $property->title }}</h3>
                  <p class="text-gray-600 text-sm mb-2">{{ $property->location }}</p>
                  <p class="text-green-600 font-bold">₱{{ number_format($property->price_per_night) }}/night</p>
                  
                  <!-- Action Buttons -->
                  <div class="flex space-x-2 mt-3">
                    <a href="{{ route('properties.show', $property) }}" 
                       class="px-3 py-1 bg-gray-100 text-gray-700 rounded text-sm hover:bg-gray-200 transition-colors">
                      View
                    </a>
                    <a href="{{ route('properties.edit', $property) }}" 
                       class="px-3 py-1 bg-green-100 text-green-700 rounded text-sm hover:bg-green-200 transition-colors">
                      Edit
                    </a>
                    <form method="POST" action="{{ route('properties.destroy', $property) }}" class="inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" 
                              onclick="return confirm('Are you sure you want to delete this property?')"
                              class="px-3 py-1 bg-red-100 text-red-700 rounded text-sm hover:bg-red-200 transition-colors">
                        Delete
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Pagination -->
        @if($properties->hasPages())
          <div class="mt-6">
            {{ $properties->links() }}
          </div>
        @endif

      @else
        <!-- Empty State -->
        <div class="text-center py-12">
          <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
          </svg>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No properties yet</h3>
          <p class="text-gray-500 mb-6">Start by adding your first property listing.</p>
          <a href="{{ route('properties.create') }}" 
             class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Your First Property
          </a>
        </div>
      @endif

      <!-- Back to Dashboard -->
      <div class="text-center mt-8">
        <a href="{{ route('owner.dashboard') }}" 
           class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Back to Dashboard
        </a>
      </div>

    </div>
  </main>

</body>
</html>
