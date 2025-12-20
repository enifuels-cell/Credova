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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->decimal('price_per_night', 10, 2);
            $table->string('image')->nullable();
            $table->integer('bedrooms')->default(1);
            $table->integer('bathrooms')->default(1);
            $table->integer('max_guests')->default(2);
            $table->enum('property_type', ['apartment', 'house', 'villa', 'condo', 'studio', 'loft'])->default('apartment');
            $table->json('amenities')->nullable();
            $table->text('house_rules')->nullable();
            $table->time('check_in_time')->default('15:00');
            $table->time('check_out_time')->default('11:00');
            $table->enum('cancellation_policy', ['flexible', 'moderate', 'strict'])->default('moderate');
            $table->boolean('instant_book')->default(false);
            $table->boolean('featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->timestamps();
            
            $table->index(['location', 'is_active']);
            $table->index(['price_per_night', 'is_active']);
            $table->index(['property_type', 'is_active']);
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