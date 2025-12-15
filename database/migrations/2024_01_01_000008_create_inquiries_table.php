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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Tenant making inquiry

            // Contact Info (for non-logged in users)
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();

            // Inquiry Details
            $table->text('message');
            $table->date('preferred_move_in')->nullable();
            $table->integer('number_of_occupants')->nullable();

            // Status
            $table->enum('status', ['pending', 'read', 'responded', 'closed'])->default('pending');
            $table->text('admin_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
