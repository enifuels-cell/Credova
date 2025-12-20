<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->string('payment_method_id')->nullable();
            $table->enum('payment_provider', ['stripe', 'paypal', 'cash'])->default('stripe');
            $table->enum('status', ['pending', 'processing', 'succeeded', 'failed', 'canceled', 'refunded'])->default('pending');
            $table->decimal('amount', 10, 2);
            $table->decimal('platform_fee', 8, 2)->default(0);
            $table->decimal('processing_fee', 8, 2)->default(0);
            $table->decimal('net_amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->json('payment_metadata')->nullable();
            $table->json('stripe_metadata')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();
            
            $table->index(['booking_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('stripe_payment_intent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
