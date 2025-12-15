<x-app-layout>
    @section('title', 'Booking Details')

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('landlord.bookings.index') }}" class="text-emerald-600 hover:text-emerald-700">
                    ← Back to Bookings
                </a>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-8">Booking Details</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
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

                    <!-- Tenant Details -->
                    <div class="border-t pt-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tenant Information</h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><span class="font-medium">Name:</span> {{ $booking->tenant->name }}</p>
                            <p><span class="font-medium">Email:</span> {{ $booking->tenant->email }}</p>
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

                    @if($booking->notes)
                        <div class="border-t pt-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Tenant Notes</h3>
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

            <!-- Actions -->
            @if($booking->status != 'cancelled' && $booking->status != 'completed')
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Booking Status</h3>
                    <form action="{{ route('landlord.bookings.status', $booking) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" required
                                class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                @if($booking->status == 'pending')
                                    <option value="confirmed">Confirm Booking</option>
                                    <option value="cancelled">Cancel Booking</option>
                                @elseif($booking->status == 'confirmed')
                                    <option value="active">Set as Active</option>
                                    <option value="cancelled">Cancel Booking</option>
                                @elseif($booking->status == 'active')
                                    <option value="completed">Mark as Completed</option>
                                @endif
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Add any notes about this status update..."></textarea>
                        </div>

                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
                            Update Status
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
