<x-app-layout>
    @section('title', 'My Bookings')

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">My Bookings</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

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
                                                📍 {{ $booking->property->address }}, {{ $booking->property->barangay->name ?? '' }}
                                            </p>
                                            <p class="text-sm text-gray-600 mb-2">
                                                Booking #{{ $booking->booking_number }}
                                            </p>
                                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-2">
                                                <span>📅 Start: {{ $booking->start_date->format('M d, Y') }}</span>
                                                <span>📅 End: {{ $booking->end_date->format('M d, Y') }}</span>
                                                <span>⏱️ Duration: {{ $booking->lease_duration_months }} months</span>
                                            </div>
                                            <p class="text-lg font-semibold text-emerald-600 mb-2">
                                                ₱{{ number_format($booking->monthly_rent, 2) }}/month
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                Total Amount: ₱{{ number_format($booking->total_amount, 2) }}
                                            </p>
                                        </div>
                                        <div class="flex md:flex-col gap-2">
                                            <a href="{{ route('bookings.show', $booking) }}"
                                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                View Details
                                            </a>
                                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                                <button onclick="showCancelModal({{ $booking->id }})"
                                                    class="inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                                    Cancel Booking
                                                </button>
                                            @endif
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
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No bookings yet</h3>
                    <p class="mt-2 text-gray-500">Start browsing properties and make your first booking!</p>
                    <div class="mt-6">
                        <a href="{{ route('properties.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
                            Browse Properties
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Cancel Booking</h3>
                <form id="cancelForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for cancellation</label>
                        <textarea name="cancellation_reason" id="cancellation_reason" rows="4" required
                            class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="Please provide a reason for cancelling this booking..."></textarea>
                    </div>
                    <div class="flex gap-2 justify-end">
                        <button type="button" onclick="hideCancelModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Confirm Cancellation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showCancelModal(bookingId) {
            document.getElementById('cancelForm').action = `/bookings/${bookingId}/cancel`;
            document.getElementById('cancelModal').classList.remove('hidden');
        }

        function hideCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.getElementById('cancellation_reason').value = '';
        }
    </script>
</x-app-layout>
