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
        Schema::create('identity_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('verification_id')->unique();
            $table->enum('document_type', ['passport', 'driver_license', 'national_id', 'other']);
            $table->string('document_number');
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->text('address');
            $table->string('phone_number');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->string('emergency_contact_relationship');
            $table->string('document_front'); // Path to front of document
            $table->string('document_back')->nullable(); // Path to back of document
            $table->string('selfie_with_document'); // Path to selfie with document
            $table->json('additional_documents')->nullable(); // Array of additional document paths
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('admin_notes')->nullable();
            $table->enum('rejection_reason', [
                'document_quality',
                'document_mismatch', 
                'information_mismatch',
                'expired_document',
                'fraudulent',
                'other'
            ])->nullable();
            $table->boolean('terms_accepted')->default(false);
            $table->boolean('data_processing_consent')->default(false);
            $table->text('notes')->nullable(); // User notes
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('submitted_at');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identity_verifications');
    }
};
