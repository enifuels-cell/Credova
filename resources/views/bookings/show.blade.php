@extends('layouts.mobile')

@section('title', 'Booking Confirmation - Homygo')

@section('home-route', auth()->user()->hasRole('landlord') ? route('owner.dashboard') : route('renter.dashboard'))

@section('content')
  <!-- Header Section -->
  <div class="text-center py-4">
    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
      <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
    </div>
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Booking Details</h1>
    <p class="text-gray-600">Your reservation information</p>
  </div>

  <!-- Booking Status -->
  <div class="dashboard-card">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-gray-800">Booking Status</h3>
      <span class="px-3 py-1 rounded-full text-xs font-medium status-{{ $booking->status }}">
        {{ ucfirst($booking->status) }}
      </span>
    </div>
    
    <div class="space-y-3 text-sm">
      <div class="flex justify-between">
        <span class="text-gray-600">Booking ID:</span>
        <span class="font-medium">#{{ $booking->id }}</span>
      </div>
      <div class="flex justify-between">
        <span class="text-gray-600">Booking Date:</span>
        <span class="font-medium">{{ $booking->created_at->format('M d, Y') }}</span>
      </div>
      @if($booking->status === 'pending')
        <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
          <p class="text-sm text-yellow-800">
            <strong>Pending Confirmation:</strong> Your booking request has been submitted and is awaiting approval from the property owner.
          </p>
        </div>
      @elseif($booking->status === 'confirmed')
        <div class="mt-4 p-3 bg-green-50 rounded-lg">
          <p class="text-sm text-green-800">
            <strong>Confirmed:</strong> Your booking has been approved! You'll receive further instructions from the property owner.
          </p>
        </div>
      @elseif($booking->status === 'cancelled')
        <div class="mt-4 p-3 bg-red-50 rounded-lg">
          <p class="text-sm text-red-800">
            <strong>Cancelled:</strong> This booking has been cancelled.
          </p>
        </div>
      @endif
    </div>
  </div>

  <!-- Property Details -->
  <div class="property-card">
    @if($booking->property->image)
      <img src="{{ asset('storage/' . $booking->property->image) }}" 
           alt="{{ $booking->property->title }}" 
           class="w-full h-40 object-cover">
    @else
      <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
        </svg>
      </div>
    @endif
    
    <div class="p-4">
      <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $booking->property->title }}</h3>
      <div class="flex items-center text-gray-600 text-sm mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
        {{ $booking->property->location }}
      </div>
      
      <!-- Property Owner Info -->
      <div class="mt-3 pt-3 border-t border-gray-100">
        <div class="flex items-center">
          <div class="w-8 h-8 bg-gray-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-3">
            {{ substr($booking->property->user->name, 0, 1) }}
          </div>
          <div>
            <p class="text-sm font-medium text-gray-800">{{ $booking->property->user->name }}</p>
            <p class="text-xs text-gray-600">Property Owner</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Booking Details -->
  <div class="dashboard-card">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Reservation Details</h3>
    
    <div class="space-y-4">
      <!-- Check-in/Check-out -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Check-in</label>
          <div class="flex items-center text-gray-800">
            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 12v-7"></path>
            </svg>
            <span class="font-medium">{{ $booking->start_date->format('M d, Y') }}</span>
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Check-out</label>
          <div class="flex items-center text-gray-800">
            <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2m4-4V9a2 2 0 012-2h2a2 2 0 012 2v2"></path>
            </svg>
            <span class="font-medium">{{ $booking->end_date->format('M d, Y') }}</span>
          </div>
        </div>
      </div>

      <!-- Duration -->
      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Duration</label>
        <div class="flex items-center text-gray-800">
          <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span class="font-medium">{{ $booking->start_date->diffInDays($booking->end_date) }} 
            {{ $booking->start_date->diffInDays($booking->end_date) === 1 ? 'night' : 'nights' }}
          </span>
        </div>
      </div>

      <!-- Price Breakdown -->
      <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-semibold text-gray-800 mb-2">Price Breakdown</h4>
        <div class="space-y-1 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-600">Rate per night:</span>
            <span>₱{{ number_format($booking->property->price_per_night) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Number of nights:</span>
            <span>{{ $booking->start_date->diffInDays($booking->end_date) }}</span>
          </div>
          <div class="border-t pt-2 mt-2">
            <div class="flex justify-between font-semibold text-lg">
              <span>Total Amount:</span>
              <span class="text-green-600">₱{{ number_format($booking->total_price) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Special Requests -->
  @if($booking->special_requests)
    <div class="dashboard-card">
      <h3 class="text-lg font-semibold text-gray-800 mb-3">Special Requests</h3>
      <p class="text-gray-700">{{ $booking->special_requests }}</p>
    </div>
  @endif

  <!-- Action Buttons -->
  <div class="space-y-3">
    @if(auth()->user()->hasRole('landlord') && $booking->status === 'pending')
      <!-- Landlord actions for pending bookings -->
      <div class="flex space-x-3">
        <form method="POST" action="{{ route('bookings.confirm', $booking) }}" class="flex-1">
          @csrf
          @method('PATCH')
          <button type="submit" class="w-full btn-primary">
            Confirm Booking
          </button>
        </form>
        
        <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="flex-1">
          @csrf
          @method('PATCH')
          <button type="submit" 
                  onclick="return confirm('Are you sure you want to cancel this booking?')"
                  class="w-full btn-danger">
            Reject
          </button>
        </form>
      </div>
    @endif

    <!-- Back to Bookings -->
    <div class="text-center">
      <a href="{{ route('bookings.index') }}" 
         class="inline-flex items-center nav-link">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Bookings
      </a>
    </div>

    <!-- Contact Support -->
    <div class="bg-blue-50 rounded-lg p-4 text-center">
      <p class="text-sm text-blue-800 mb-2">Need help with your booking?</p>
      <a href="mailto:support@homygo.com" class="text-blue-600 font-medium text-sm hover:text-blue-800">
        Contact Support
      </a>
    </div>
  </div>
@endsection
