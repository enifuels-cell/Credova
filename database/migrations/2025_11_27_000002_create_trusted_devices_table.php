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
        Schema::create('trusted_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_fingerprint')->unique(); // Unique device identifier
            $table->string('device_name'); // User-friendly device name (e.g., "iPhone", "Desktop")
            $table->string('device_type'); // Type: mobile, tablet, desktop
            $table->string('browser'); // Browser name
            $table->string('os'); // Operating system
            $table->string('ip_address');
            $table->boolean('is_trusted')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('trusted_at');
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trusted_devices');
    }
};
