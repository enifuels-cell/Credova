<x-app-layout>
    @section('title', 'Booking Details')

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('bookings.index') }}" class="text-emerald-600 hover:text-emerald-700">
                    ← Back to Bookings
                </a>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-8">Booking Details</h1>

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

            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">{{ $booking->property->title }}</h2>
                            <p class="text-gray-600">Booking #{{ $booking->booking_number }}</p>
                        </div>
                        <span class="px-4 py-2 rounded-full text-sm font-medium
                            {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $booking->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->status == 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $booking->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>

                    <!-- Property Details -->
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            @if($booking->property->images->first())
                                <img class="w-full h-64 object-cover rounded-lg"
                                    src="{{ asset('storage/' . $booking->property->images->first()->image_path) }}"
                                    alt="{{ $booking->property->title }}">
                            @else
                                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-6xl">🏠</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Property Information</h3>
                            <div class="space-y-2 text-sm text-gray-600">
                                <p><span class="font-medium">Type:</span> {{ $booking->property->propertyType->name ?? 'N/A' }}</p>
                                <p><span class="font-medium">Address:</span> {{ $booking->property->address }}</p>
                                <p><span class="font-medium">Barangay:</span> {{ $booking->property->barangay->name ?? 'N/A' }}</p>
                                <p><span class="font-medium">Bedrooms:</span> {{ $booking->property->bedrooms }}</p>
                                <p><span class="font-medium">Bathrooms:</span> {{ $booking->property->bathrooms }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="border-t pt-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Booking Information</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Start Date</p>
                                <p class="font-medium">{{ $booking->start_date->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">End Date</p>
                                <p class="font-medium">{{ $booking->end_date->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Lease Duration</p>
                                <p class="font-medium">{{ $booking->lease_duration_months }} months</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Monthly Rent</p>
                                <p class="font-medium text-emerald-600">₱{{ number_format($booking->monthly_rent, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="border-t pt-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Details</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Security Deposit</span>
                                <span class="font-medium">₱{{ number_format($booking->security_deposit, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Advance Payment</span>
                                <span class="font-medium">₱{{ number_format($booking->advance_payment, 2) }}</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="font-semibold text-gray-800">Total Amount</span>
                                <span class="font-semibold text-emerald-600 text-lg">₱{{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Landlord Details -->
                    <div class="border-t pt-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Landlord Information</h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><span class="font-medium">Name:</span> {{ $booking->landlord->name }}</p>
                            <p><span class="font-medium">Email:</span> {{ $booking->landlord->email }}</p>
                        </div>
                    </div>

                    @if($booking->notes)
                        <div class="border-t pt-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Notes</h3>
                            <p class="text-gray-600">{{ $booking->notes }}</p>
                        </div>
                    @endif

                    @if($booking->cancellation_reason)
                        <div class="border-t pt-6 mb-6 bg-red-50 -m-6 mt-0 p-6">
                            <h3 class="text-lg font-semibold text-red-800 mb-2">Cancellation Reason</h3>
                            <p class="text-red-600">{{ $booking->cancellation_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if(in_array($booking->status, ['pending', 'confirmed']) && $booking->user_id == auth()->id())
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                    <button onclick="showCancelModal()"
                        class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                        Cancel Booking
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Cancel Booking</h3>
                <form action="{{ route('bookings.cancel', $booking) }}" method="POST">
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
        function showCancelModal() {
            document.getElementById('cancelModal').classList.remove('hidden');
        }

        function hideCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
