@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Complete Your Payment</h1>
                <p class="text-gray-600 mt-2">Secure payment for your booking at {{ $booking->property->title }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
                <!-- Booking Summary -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Booking Summary</h2>
                        
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Property:</span>
                                <span class="font-medium">{{ $booking->property->title }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Check-in:</span>
                                <span class="font-medium">{{ $booking->start_date->format('M d, Y') }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Check-out:</span>
                                <span class="font-medium">{{ $booking->end_date->format('M d, Y') }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Guests:</span>
                                <span class="font-medium">{{ $booking->guest_count }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nights:</span>
                                <span class="font-medium">{{ $booking->start_date->diffInDays($booking->end_date) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Price Details</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span>${{ number_format($booking->total_price - $booking->cleaning_fee - $booking->service_fee - $booking->taxes, 2) }}</span>
                            </div>
                            
                            @if($booking->cleaning_fee > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Cleaning fee:</span>
                                <span>${{ number_format($booking->cleaning_fee, 2) }}</span>
                            </div>
                            @endif
                            
                            @if($booking->service_fee > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Service fee:</span>
                                <span>${{ number_format($booking->service_fee, 2) }}</span>
                            </div>
                            @endif
                            
                            @if($booking->taxes > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Taxes:</span>
                                <span>${{ number_format($booking->taxes, 2) }}</span>
                            </div>
                            @endif
                            
                            <div class="flex justify-between text-sm text-gray-500">
                                <span>Processing fee:</span>
                                <span id="processing-fee">Calculating...</span>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between text-lg font-semibold">
                                    <span>Total:</span>
                                    <span id="total-amount">Calculating...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h2>
                        
                        <form id="payment-form" class="space-y-4">
                            @csrf
                            
                            <!-- Stripe Elements will be mounted here -->
                            <div id="card-element" class="p-3 border border-gray-300 rounded-md bg-white">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                            
                            <!-- Used to display form errors -->
                            <div id="card-errors" role="alert" class="text-red-600 text-sm"></div>
                            
                            <button 
                                type="submit" 
                                id="submit-payment"
                                class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition duration-200"
                                disabled
                            >
                                <span id="button-text">Processing...</span>
                                <div id="spinner" class="hidden ml-2">
                                    <svg class="animate-spin h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </button>
                        </form>
                    </div>

                    <div class="text-sm text-gray-500">
                        <p class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            Your payment information is secure and encrypted
                        </p>
                        <p class="mt-2">We use Stripe to process payments securely. Your card details are never stored on our servers.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    const elements = stripe.elements();

    // Create card element
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
            invalid: {
                color: '#9e2146',
            },
        },
    });

    cardElement.mount('#card-element');

    // Handle form submission
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-payment');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');

    // Create payment intent when page loads
    let clientSecret = null;
    let paymentId = null;

    fetch(`/bookings/{{ $booking->id }}/payment-intent`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            document.getElementById('card-errors').textContent = data.error;
        } else {
            clientSecret = data.client_secret;
            paymentId = data.payment_id;
            
            // Update price display
            document.getElementById('processing-fee').textContent = '$' + data.processing_fee.toFixed(2);
            document.getElementById('total-amount').textContent = '$' + data.amount.toFixed(2);
            
            // Enable submit button
            submitButton.disabled = false;
            buttonText.textContent = 'Pay $' + data.amount.toFixed(2);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('card-errors').textContent = 'Failed to initialize payment. Please refresh the page.';
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!clientSecret) {
            return;
        }

        setLoading(true);

        const {error} = await stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: cardElement,
            }
        });

        if (error) {
            // Show error to customer
            document.getElementById('card-errors').textContent = error.message;
            setLoading(false);
        } else {
            // Payment succeeded, confirm on server
            fetch(`/payments/${paymentId}/confirm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to booking confirmation
                    window.location.href = `/bookings/{{ $booking->id }}?payment=success`;
                } else {
                    document.getElementById('card-errors').textContent = data.error || 'Payment confirmation failed';
                    setLoading(false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('card-errors').textContent = 'Payment confirmation failed. Please contact support.';
                setLoading(false);
            });
        }
    });

    function setLoading(loading) {
        if (loading) {
            submitButton.disabled = true;
            buttonText.textContent = 'Processing...';
            spinner.classList.remove('hidden');
        } else {
            submitButton.disabled = false;
            buttonText.textContent = 'Pay $' + (clientSecret ? document.getElementById('total-amount').textContent : '0.00');
            spinner.classList.add('hidden');
        }
    }

    // Handle real-time validation errors from the card Element
    cardElement.on('change', ({error}) => {
        const displayError = document.getElementById('card-errors');
        if (error) {
            displayError.textContent = error.message;
        } else {
            displayError.textContent = '';
        }
    });
</script>
@endpush
@endsection
