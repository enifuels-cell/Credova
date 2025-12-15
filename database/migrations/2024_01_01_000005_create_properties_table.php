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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Landlord
            $table->foreignId('property_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('barangay_id')->constrained()->onDelete('cascade');

            // Basic Info
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('address');
            $table->string('landmark')->nullable();

            // Pricing (in Philippine Peso)
            $table->decimal('price', 12, 2); // Monthly rent
            $table->decimal('deposit', 12, 2)->nullable(); // Security deposit
            $table->decimal('advance', 12, 2)->nullable(); // Advance payment

            // Property Details
            $table->integer('bedrooms')->default(0);
            $table->integer('bathrooms')->default(0);
            $table->decimal('floor_area', 10, 2)->nullable(); // in sqm
            $table->decimal('lot_area', 10, 2)->nullable(); // in sqm
            $table->integer('floor_level')->nullable();
            $table->integer('max_occupants')->nullable();

            // Features
            $table->boolean('is_furnished')->default(false);
            $table->boolean('pets_allowed')->default(false);
            $table->boolean('parking_available')->default(false);
            $table->integer('parking_slots')->default(0);

            // Lease Terms
            $table->integer('minimum_lease_months')->default(6);
            $table->date('available_from')->nullable();

            // Status
            $table->enum('status', ['draft', 'pending', 'active', 'rented', 'inactive'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);

            // Coordinates for map
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
