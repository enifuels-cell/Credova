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
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('ip_address');
            $table->text('user_agent');
            $table->string('email');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('successful')->default(false);
            $table->timestamp('attempted_at');
            $table->string('location')->nullable();
            $table->string('device_fingerprint')->nullable();
            $table->timestamps();

            $table->index(['ip_address', 'attempted_at']);
            $table->index(['email', 'attempted_at']);
            $table->index(['successful', 'attempted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};
