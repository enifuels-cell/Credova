<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Debug - Renter Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
  <div class="p-8">
    <h1 class="text-2xl font-bold mb-4">🏠 HomyGo Renter Dashboard</h1>
    <p class="mb-4">Welcome {{ Auth::user()->name }}!</p>
    
    <div class="bg-white p-4 rounded shadow mb-4">
      <h2 class="text-lg font-semibold">Quick Actions</h2>
      <div class="grid grid-cols-2 gap-4 mt-4">
        <a href="/properties" class="bg-blue-500 text-white p-3 rounded text-center">Browse Properties</a>
        <a href="/renter/bookings" class="bg-green-500 text-white p-3 rounded text-center">My Bookings</a>
      </div>
    </div>

    <div class="bg-white p-4 rounded shadow">
      <h2 class="text-lg font-semibold">Featured Properties</h2>
      @if(isset($featuredProperties) && $featuredProperties->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
          @foreach($featuredProperties as $property)
          <div class="border rounded p-4">
            <h3 class="font-bold">{{ $property->title }}</h3>
            <p class="text-gray-600">₱{{ number_format($property->price_per_night) }}/night</p>
            <p class="text-sm text-gray-500">{{ $property->location }}</p>
          </div>
          @endforeach
        </div>
      @else
        <p class="text-gray-500 mt-4">No properties available yet.</p>
      @endif
    </div>
  </div>
</body>
</html>
