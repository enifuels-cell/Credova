@extends('layouts.mobile')

@section('title', 'Manage Bookings - Homygo')

@section('home-route', Auth::user()->hasRole('landlord') ? route('owner.dashboard') : route('renter.dashboard'))

@section('content')
  <!-- Header Section -->
  <div class="text-center py-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">
      @if(Auth::user()->hasRole('landlord'))
        Manage Bookings
      @else
        My Bookings
      @endif
    </h1>
    <p class="text-gray-600">
      @if(Auth::user()->hasRole('landlord'))
        Review and manage property bookings
      @else
        Track your rental bookings
      @endif
    </p>
  </div>

  @if($bookings->count() > 0)
    <!-- Bookings List -->
    <div class="space-y-4">
      @foreach ($bookings as $booking)
        <div class="property-card">
          <div class="p-4">
            <!-- Property Title -->
            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $booking->property->title }}</h3>
            
            <!-- Booking Details -->
            <div class="space-y-2 mb-4">
              @if(Auth::user()->hasRole('landlord'))
                <!-- Show renter info for landlords -->
                <div class="flex items-center text-gray-600 text-sm">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  Renter: {{ $booking->user->name }}
                </div>
              @endif
              
              <div class="flex items-center text-gray-600 text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ $booking->property->location }}
              </div>
              
              <div class="flex items-center text-gray-600 text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ $booking->start_date->format('M d, Y') }} - {{ $booking->end_date->format('M d, Y') }}
              </div>
              
              <div class="flex items-center text-gray-600 text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Total: ₱{{ number_format($booking->total_price) }}
              </div>
            </div>

            <!-- Status Badge -->
            <div class="flex items-center justify-between mb-4">
              @if($booking->status === 'confirmed')
                <span class="status-confirmed">
                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                  </svg>
                  Confirmed
                </span>
              @elseif($booking->status === 'pending')
                <span class="status-pending">
                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                  </svg>
                  Pending
                </span>
              @elseif($booking->status === 'cancelled')
                <span class="status-cancelled">
                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                  </svg>
                  Cancelled
                </span>
              @else
                <span class="status-default">{{ ucfirst($booking->status) }}</span>
              @endif
              
              <span class="text-xs text-gray-500">
                {{ $booking->created_at->diffForHumans() }}
              </span>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-2">
              @if(Auth::user()->hasRole('landlord'))
                <!-- Landlord Actions -->
                @if($booking->status === 'pending')
                  <form method="POST" action="{{ route('bookings.confirm', $booking) }}" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full btn-primary text-sm py-2">
                      Confirm
                    </button>
                  </form>
                  <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to cancel this booking?')"
                            class="w-full btn-danger text-sm py-2">
                      Reject
                    </button>
                  </form>
                @else
                  <a href="{{ route('properties.show', $booking->property) }}" 
                     class="flex-1 btn-secondary text-center text-sm py-2">
                    View Property
                  </a>
                @endif
              @else
                <!-- Renter Actions -->
                <a href="{{ route('properties.show', $booking->property) }}" 
                   class="flex-1 btn-secondary text-center text-sm py-2">
                  View Property
                </a>
                
                @if($booking->status === 'pending')
                  <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to cancel this booking?')"
                            class="w-full bg-red-50 hover:bg-red-100 text-red-700 py-2 rounded-lg font-medium transition-colors duration-200 text-sm">
                      Cancel
                    </button>
                  </form>
                @endif
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
      <div class="flex justify-center mt-6">
        {{ $bookings->links() }}
      </div>
    @endif

  @else
    <!-- Empty State -->
    <div class="empty-state">
      <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
      </svg>
      <h3 class="text-lg font-medium text-gray-900 mb-2">
        @if(Auth::user()->hasRole('landlord'))
          No bookings yet
        @else
          No bookings yet
        @endif
      </h3>
      <p class="text-gray-500 mb-6">
        @if(Auth::user()->hasRole('landlord'))
          No one has booked your properties yet.
        @else
          Start exploring properties to make your first booking.
        @endif
      </p>
      @if(Auth::user()->hasRole('landlord'))
        <a href="{{ route('properties.create') }}" class="btn-primary inline-flex items-center">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Add Properties
        </a>
      @else
        <a href="{{ route('properties.index') }}" class="btn-primary inline-flex items-center">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          Browse Properties
        </a>
      @endif
    </div>
  @endif

  <!-- Back to Dashboard -->
  <div class="text-center mt-8">
    <a href="{{ Auth::user()->hasRole('landlord') ? route('owner.dashboard') : route('renter.dashboard') }}" 
       class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
      </svg>
      Back to Dashboard
    </a>
  </div>
@endsection
