<x-app-layout>
    @section('title', 'Manage Bookings')

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Booking Management</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Confirmed</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['confirmed'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $stats['completed'] }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-md p-4 mb-6">
                <form action="{{ route('landlord.bookings.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div>
                        <select name="status" class="border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition">
                        Filter
                    </button>
                    @if(request('status'))
                        <a href="{{ route('landlord.bookings.index') }}" class="text-gray-600 hover:text-gray-800 py-2 px-4">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Bookings List -->
            @if($bookings->count() > 0)
                <div class="space-y-4">
                    @foreach($bookings as $booking)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                            <div class="md:flex">
                                <div class="md:flex-shrink-0">
                                    @if($booking->property->images->first())
                                        <img class="h-48 w-full md:w-48 object-cover"
                                            src="{{ asset('storage/' . $booking->property->images->first()->image_path) }}"
                                            alt="{{ $booking->property->title }}">
                                    @else
                                        <div class="h-48 w-full md:w-48 bg-gray-200 flex items-center justify-center">
                                            <span class="text-4xl">🏠</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6 flex-1">
                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="font-semibold text-lg text-gray-800">
                                                    {{ $booking->property->title }}
                                                </h3>
                                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                                    {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $booking->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $booking->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $booking->status == 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                                    {{ $booking->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">
                                                Booking #{{ $booking->booking_number }}
                                            </p>
                                            <p class="text-sm text-gray-600 mb-2">
                                                Tenant: {{ $booking->tenant->name }} ({{ $booking->tenant->email }})
                                            </p>
                                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-2">
                                                <span>📅 {{ $booking->start_date->format('M d, Y') }} - {{ $booking->end_date->format('M d, Y') }}</span>
                                                <span>⏱️ {{ $booking->lease_duration_months }} months</span>
                                            </div>
                                            <p class="text-lg font-semibold text-emerald-600">
                                                ₱{{ number_format($booking->monthly_rent, 2) }}/month
                                            </p>
                                        </div>
                                        <div class="flex md:flex-col gap-2">
                                            <a href="{{ route('landlord.bookings.show', $booking) }}"
                                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($bookings->hasPages())
                    <div class="mt-8">
                        {{ $bookings->links() }}
                    </div>
                @endif
            @else
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No bookings found</h3>
                    <p class="mt-2 text-gray-500">You don't have any bookings yet.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
