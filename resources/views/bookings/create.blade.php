<x-app-layout>
    @section('title', 'Book Property')

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('properties.show', $property) }}" class="text-emerald-600 hover:text-emerald-700">
                    ← Back to Property
                </a>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-8">Book Property</h1>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Property Details -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    @if($property->images->first())
                        <img class="w-full h-64 object-cover"
                            src="{{ asset('storage/' . $property->images->first()->image_path) }}"
                            alt="{{ $property->title }}">
                    @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                            <span class="text-6xl">🏠</span>
                        </div>
                    @endif
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $property->title }}</h2>
                        <p class="text-gray-600 mb-4">{{ $property->address }}, {{ $property->barangay->name ?? '' }}</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p><span class="font-medium">Type:</span> {{ $property->propertyType->name ?? 'N/A' }}</p>
                            <p><span class="font-medium">Bedrooms:</span> {{ $property->bedrooms }}</p>
                            <p><span class="font-medium">Bathrooms:</span> {{ $property->bathrooms }}</p>
                        </div>
                        <div class="border-t pt-4">
                            <p class="text-sm text-gray-600">Monthly Rent</p>
                            <p class="text-2xl font-bold text-emerald-600">₱{{ number_format($property->price, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Booking Details</h3>

                    <form action="{{ route('bookings.store', $property) }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Move-in Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="start_date" required
                                value="{{ old('start_date', now()->addDays(7)->format('Y-m-d')) }}"
                                min="{{ now()->format('Y-m-d') }}"
                                class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="lease_duration_months" class="block text-sm font-medium text-gray-700 mb-2">
                                Lease Duration (months) <span class="text-red-500">*</span>
                            </label>
                            <select name="lease_duration_months" id="lease_duration_months" required
                                class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                onchange="updatePaymentDetails()">
                                <option value="6" {{ old('lease_duration_months') == 6 ? 'selected' : '' }}>6 months</option>
                                <option value="12" {{ old('lease_duration_months', 12) == 12 ? 'selected' : '' }}>12 months (1 year)</option>
                                <option value="24" {{ old('lease_duration_months') == 24 ? 'selected' : '' }}>24 months (2 years)</option>
                                <option value="36" {{ old('lease_duration_months') == 36 ? 'selected' : '' }}>36 months (3 years)</option>
                            </select>
                            @error('lease_duration_months')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Notes (Optional)
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Any special requests or information...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Summary -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h4 class="font-semibold text-gray-800 mb-3">Payment Summary</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Monthly Rent</span>
                                    <span class="font-medium" id="monthly_rent">₱{{ number_format($property->price, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Security Deposit (1 month)</span>
                                    <span class="font-medium" id="security_deposit">₱{{ number_format($property->price, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Advance Payment (2 months)</span>
                                    <span class="font-medium" id="advance_payment">₱{{ number_format($property->price * 2, 2) }}</span>
                                </div>
                                <div class="border-t pt-2 flex justify-between">
                                    <span class="font-semibold text-gray-800">Total Due Upon Move-in</span>
                                    <span class="font-semibold text-emerald-600 text-lg" id="total_amount">₱{{ number_format($property->price * 3, 2) }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-3">
                                * This is an initial booking request. Final payment details will be confirmed by the landlord.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('properties.show', $property) }}"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
                                Submit Booking Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const monthlyRent = {{ $property->price }};

        function updatePaymentDetails() {
            // Update payment display
            const securityDeposit = monthlyRent;
            const advancePayment = monthlyRent * 2;
            const totalAmount = securityDeposit + advancePayment;

            document.getElementById('security_deposit').textContent = '₱' + securityDeposit.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('advance_payment').textContent = '₱' + advancePayment.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('total_amount').textContent = '₱' + totalAmount.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    </script>
</x-app-layout>
