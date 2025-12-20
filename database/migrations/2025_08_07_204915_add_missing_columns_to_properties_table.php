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
        Schema::table('properties', function (Blueprint $table) {
            $table->decimal('cleaning_fee', 8, 2)->nullable()->after('price_per_night');
            $table->integer('minimum_stay')->default(1)->after('cancellation_policy');
            $table->boolean('instant_booking')->default(false)->after('minimum_stay');
            $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])->default('active')->after('instant_booking');
            $table->json('images')->nullable()->after('status');
            $table->decimal('latitude', 10, 8)->nullable()->after('images');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'cleaning_fee',
                'minimum_stay', 
                'instant_booking',
                'status',
                'images',
                'latitude',
                'longitude'
            ]);
        });
    }
};
