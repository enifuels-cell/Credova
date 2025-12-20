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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->enum('sender_type', ['guest', 'host', 'admin'])->default('guest');
            $table->text('message');
            $table->json('attachments')->nullable(); // File attachments
            $table->boolean('is_automated')->default(false); // System-generated messages
            $table->enum('message_type', ['general', 'check_in', 'check_out', 'emergency', 'review_request', 'cancellation'])->default('general');
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_important')->default(false);
            $table->json('metadata')->nullable(); // Additional context
            $table->timestamps();

            // Indexes for performance
            $table->index(['booking_id', 'created_at']);
            $table->index(['sender_id', 'created_at']);
            $table->index(['recipient_id', 'read_at']);
            $table->index(['message_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
