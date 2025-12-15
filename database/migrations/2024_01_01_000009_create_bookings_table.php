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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Tenant
            $table->foreignId('landlord_id')->constrained('users')->onDelete('cascade');

            // Booking Details
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('lease_duration_months');

            // Payment Info
            $table->decimal('monthly_rent', 12, 2);
            $table->decimal('security_deposit', 12, 2)->nullable();
            $table->decimal('advance_payment', 12, 2)->nullable();
            $table->decimal('total_amount', 12, 2);

            // Status
            $table->enum('status', ['pending', 'confirmed', 'active', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
