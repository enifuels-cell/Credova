<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    private $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent for a booking
     */
    public function createPaymentIntent(Request $request, Booking $booking)
    {
        try {
            // Verify the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Check if booking already has a payment
            if ($booking->payment) {
                return response()->json(['error' => 'Payment already exists for this booking'], 400);
            }

            // Calculate total amount including fees
            $payment = new Payment();
            $processingFee = $payment->calculateProcessingFee($booking->total_price);
            $totalAmount = $payment->calculateTotalAmount($booking->total_price);

            // Create Stripe Payment Intent
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => round($totalAmount * 100), // Stripe expects cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'booking_id' => $booking->id,
                    'user_id' => auth()->id(),
                ],
                'description' => "Booking for {$booking->property->title}",
            ]);

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_price,
                'currency' => 'USD',
                'payment_method' => 'card',
                'provider' => 'stripe',
                'provider_payment_id' => $paymentIntent->id,
                'status' => 'pending',
                'processing_fee' => $processingFee,
                'total_amount' => $totalAmount,
                'metadata' => [
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'client_secret' => $paymentIntent->client_secret,
                ],
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'payment_id' => $payment->id,
                'amount' => $totalAmount,
                'processing_fee' => $processingFee,
            ]);

        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment processing error'], 500);
        } catch (\Exception $e) {
            Log::error('Payment Error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing payment'], 500);
        }
    }

    /**
     * Confirm payment after successful Stripe payment
     */
    public function confirmPayment(Request $request, Payment $payment)
    {
        try {
            // Verify the payment belongs to the authenticated user
            if ($payment->booking->user_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Get the payment intent from Stripe
            $paymentIntent = $this->stripe->paymentIntents->retrieve($payment->provider_payment_id);

            if ($paymentIntent->status === 'succeeded') {
                // Update payment status
                $payment->markAsCompleted();
                
                // Update booking status
                $payment->booking->update([
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment confirmed successfully',
                    'booking' => $payment->booking->load('property'),
                ]);
            } else {
                return response()->json(['error' => 'Payment not successful'], 400);
            }

        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment confirmation error'], 500);
        } catch (\Exception $e) {
            Log::error('Payment Confirmation Error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while confirming payment'], 500);
        }
    }

    /**
     * Handle Stripe webhook events
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook.secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpoint_secret
            );

            // Handle the event
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentSucceeded($event->data->object);
                    break;
                
                case 'payment_intent.payment_failed':
                    $this->handlePaymentFailed($event->data->object);
                    break;
                
                default:
                    Log::info('Received unknown event type: ' . $event->type);
            }

            return response('Webhook handled', 200);

        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload: ' . $e->getMessage());
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid signature: ' . $e->getMessage());
            return response('Invalid signature', 400);
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response('Webhook error', 500);
        }
    }

    /**
     * Handle successful payment
     */
    private function handlePaymentSucceeded($paymentIntent)
    {
        $payment = Payment::where('provider_payment_id', $paymentIntent->id)->first();
        
        if ($payment && $payment->status !== 'completed') {
            $payment->markAsCompleted();
            
            // Update booking status
            $payment->booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            Log::info("Payment completed for booking {$payment->booking_id}");
        }
    }

    /**
     * Handle failed payment
     */
    private function handlePaymentFailed($paymentIntent)
    {
        $payment = Payment::where('provider_payment_id', $paymentIntent->id)->first();
        
        if ($payment && $payment->status !== 'failed') {
            $payment->markAsFailed('Payment failed via webhook');
            
            Log::info("Payment failed for booking {$payment->booking_id}");
        }
    }

    /**
     * Get payment details
     */
    public function show(Payment $payment)
    {
        // Verify the payment belongs to the authenticated user
        if ($payment->booking->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'payment' => $payment->load('booking.property'),
        ]);
    }

    /**
     * Refund a payment
     */
    public function refund(Request $request, Payment $payment)
    {
        try {
            // Verify the payment belongs to the authenticated user or property owner
            $booking = $payment->booking;
            if ($booking->user_id !== auth()->id() && $booking->property->user_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Only allow refunds for completed payments
            if (!$payment->isCompleted()) {
                return response()->json(['error' => 'Payment is not completed'], 400);
            }

            $amount = $request->input('amount');
            if (!$amount || $amount <= 0 || $amount > $payment->total_amount) {
                return response()->json(['error' => 'Invalid refund amount'], 400);
            }

            // Create refund in Stripe
            $refund = $this->stripe->refunds->create([
                'payment_intent' => $payment->provider_payment_id,
                'amount' => round($amount * 100), // Stripe expects cents
                'reason' => $request->input('reason', 'requested_by_customer'),
                'metadata' => [
                    'booking_id' => $booking->id,
                    'refund_reason' => $request->input('reason', 'requested_by_customer'),
                ],
            ]);

            // Update payment metadata
            $metadata = $payment->metadata ?? [];
            $metadata['refunds'][] = [
                'refund_id' => $refund->id,
                'amount' => $amount,
                'reason' => $request->input('reason'),
                'created_at' => now()->toISOString(),
            ];
            
            $payment->update(['metadata' => $metadata]);

            // Update booking status if full refund
            if ($amount >= $payment->total_amount) {
                $booking->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => $request->input('reason', 'Refunded'),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Refund processed successfully',
                'refund' => $refund,
            ]);

        } catch (ApiErrorException $e) {
            Log::error('Stripe Refund Error: ' . $e->getMessage());
            return response()->json(['error' => 'Refund processing error'], 500);
        } catch (\Exception $e) {
            Log::error('Refund Error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing refund'], 500);
        }
    }
}
