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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // reviewer (renter)
            $table->foreignId('host_id')->constrained('users')->onDelete('cascade'); // property owner
            $table->integer('rating')->unsigned()->min(1)->max(5);
            $table->integer('cleanliness_rating')->unsigned()->min(1)->max(5);
            $table->integer('communication_rating')->unsigned()->min(1)->max(5);
            $table->integer('location_rating')->unsigned()->min(1)->max(5);
            $table->integer('value_rating')->unsigned()->min(1)->max(5);
            $table->text('comment')->nullable();
            $table->json('photos')->nullable(); // Review photos
            $table->boolean('is_public')->default(true);
            $table->boolean('is_verified')->default(false); // Verified stay
            $table->text('host_response')->nullable();
            $table->timestamp('host_responded_at')->nullable();
            $table->timestamps();
            
            // Prevent duplicate reviews for same booking
            $table->unique(['booking_id', 'user_id']);
            $table->index(['property_id', 'is_public']);
            $table->index(['user_id', 'rating']);
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
