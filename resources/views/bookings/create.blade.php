@extends('layouts.mobile')

@section('title', 'Book ' . $property->title . ' - Homygo')

@section('home-route', route('properties.show', $property))

@section('content')
  <!-- Header Section -->
  <div class="text-center py-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Book Property</h1>
    <p class="text-gray-600">Reserve your perfect home</p>
  </div>

  <!-- Property Summary -->
  <div class="property-card">
    @if($property->image)
      <img src="{{ asset('storage/' . $property->image) }}" 
           alt="{{ $property->title }}" 
           class="w-full h-40 object-cover">
    @else
      <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
        </svg>
      </div>
    @endif
    
    <div class="p-4">
      <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $property->title }}</h3>
      <div class="flex items-center text-gray-600 text-sm mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
        {{ $property->location }}
      </div>
      <div class="text-xl font-bold text-green-600">₱{{ number_format($property->price_per_night) }}<span class="text-sm font-normal text-gray-600">/night</span></div>
    </div>
  </div>

  <!-- Booking Form -->
  <div class="dashboard-card">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Booking Details</h3>
    
    <form method="POST" action="{{ route('bookings.store') }}" class="space-y-4">
      @csrf
      <input type="hidden" name="property_id" value="{{ $property->id }}">

      <!-- Check-in Date -->
      <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
        <input type="date" 
               id="start_date" 
               name="start_date" 
               value="{{ old('start_date') }}"
               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
               class="form-input" 
               required>
      </div>

      <!-- Check-out Date -->
      <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Check-out Date</label>
        <input type="date" 
               id="end_date" 
               name="end_date" 
               value="{{ old('end_date') }}"
               min="{{ date('Y-m-d', strtotime('+2 days')) }}"
               class="form-input" 
               required>
      </div>

      <!-- Special Requests -->
      <div>
        <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-2">Special Requests (Optional)</label>
        <textarea id="special_requests" 
                  name="special_requests" 
                  rows="3"
                  placeholder="Any special requirements or requests..."
                  class="form-textarea">{{ old('special_requests') }}</textarea>
      </div>

      <!-- Price Calculation -->
      <div class="bg-gray-50 rounded-lg p-4 mt-6">
        <h4 class="font-semibold text-gray-800 mb-2">Price Breakdown</h4>
        <div class="space-y-1 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-600">Price per night:</span>
            <span>₱{{ number_format($property->price_per_night) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Number of nights:</span>
            <span id="nights-count">-</span>
          </div>
          <div class="border-t pt-2 mt-2">
            <div class="flex justify-between font-semibold">
              <span>Total:</span>
              <span id="total-price">₱0</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="pt-4">
        <button type="submit" class="w-full btn-primary">
          Confirm Booking
        </button>
      </div>
    </form>
  </div>

  <!-- Back Button -->
  <div class="text-center mt-8">
    <a href="{{ route('properties.show', $property) }}" 
       class="inline-flex items-center nav-link">
      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
      </svg>
      Back to Property
    </a>
  </div>
@endsection

@push('scripts')
<script>
  // Price calculation
  document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const nightsCount = document.getElementById('nights-count');
    const totalPrice = document.getElementById('total-price');
    const pricePerNight = {{ $property->price_per_night }};

    function calculatePrice() {
      const startDate = new Date(startDateInput.value);
      const endDate = new Date(endDateInput.value);
      
      if (startDate && endDate && endDate > startDate) {
        const timeDiff = endDate.getTime() - startDate.getTime();
        const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
        const total = nights * pricePerNight;
        
        nightsCount.textContent = nights;
        totalPrice.textContent = '₱' + total.toLocaleString();
        
        // Update minimum end date
        const minEndDate = new Date(startDate);
        minEndDate.setDate(minEndDate.getDate() + 1);
        endDateInput.min = minEndDate.toISOString().split('T')[0];
      } else {
        nightsCount.textContent = '-';
        totalPrice.textContent = '₱0';
      }
    }

    startDateInput.addEventListener('change', calculatePrice);
    endDateInput.addEventListener('change', calculatePrice);
  });
</script>
@endpush
