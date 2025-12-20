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
        Schema::create('recommendation_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->enum('feedback_type', ['liked', 'disliked', 'viewed', 'booked', 'not_interested']);
            $table->decimal('rating', 2, 1)->nullable();
            $table->text('notes')->nullable();
            $table->json('context')->nullable(); // Store additional context like recommendation source
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'feedback_type']);
            $table->index(['property_id', 'feedback_type']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendation_feedback');
    }
};
