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
        Schema::table('bookings', function (Blueprint $table) {
            // Check-in/out management - only add columns that don't exist
            if (!Schema::hasColumn('bookings', 'actual_check_in')) {
                $table->timestamp('actual_check_in')->nullable()->after('message_count');
            }
            if (!Schema::hasColumn('bookings', 'actual_check_out')) {
                $table->timestamp('actual_check_out')->nullable()->after('actual_check_in');
            }
            if (!Schema::hasColumn('bookings', 'check_in_method')) {
                $table->enum('check_in_method', ['self', 'host', 'keybox', 'concierge'])->nullable()->after('actual_check_out');
            }
            
            // Extensions and modifications
            if (!Schema::hasColumn('bookings', 'is_extended')) {
                $table->boolean('is_extended')->default(false)->after('check_in_method');
            }
            if (!Schema::hasColumn('bookings', 'extension_fee')) {
                $table->decimal('extension_fee', 10, 2)->nullable()->after('is_extended');
            }
            if (!Schema::hasColumn('bookings', 'modification_history')) {
                $table->json('modification_history')->nullable()->after('extension_fee');
            }
            
            // Reviews and feedback
            if (!Schema::hasColumn('bookings', 'review_reminder_sent')) {
                $table->boolean('review_reminder_sent')->default(false)->after('modification_history');
            }
            if (!Schema::hasColumn('bookings', 'review_deadline')) {
                $table->timestamp('review_deadline')->nullable()->after('review_reminder_sent');
            }
            
            // Emergency and special circumstances
            if (!Schema::hasColumn('bookings', 'emergency_contacts')) {
                $table->json('emergency_contacts')->nullable()->after('review_deadline');
            }
            if (!Schema::hasColumn('bookings', 'has_pets')) {
                $table->boolean('has_pets')->default(false)->after('emergency_contacts');
            }
            if (!Schema::hasColumn('bookings', 'accessibility_needs')) {
                $table->text('accessibility_needs')->nullable()->after('has_pets');
            }
            
            // Analytics and tracking
            if (!Schema::hasColumn('bookings', 'property_views_before_booking')) {
                $table->integer('property_views_before_booking')->nullable()->after('accessibility_needs');
            }
            if (!Schema::hasColumn('bookings', 'referral_source')) {
                $table->string('referral_source')->nullable()->after('property_views_before_booking');
            }
            if (!Schema::hasColumn('bookings', 'booking_metadata')) {
                $table->json('booking_metadata')->nullable()->after('referral_source');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'booking_source',
                'confirmation_code',
                'guest_details',
                'cancellation_policy',
                'cancellation_fee',
                'cancellation_deadline',
                'host_notes',
                'last_message_at',
                'message_count',
                'check_in_instructions',
                'actual_check_in',
                'actual_check_out',
                'check_in_method',
                'is_extended',
                'extension_fee',
                'modification_history',
                'review_reminder_sent',
                'review_deadline',
                'emergency_contacts',
                'has_pets',
                'accessibility_needs',
                'property_views_before_booking',
                'referral_source',
                'booking_metadata'
            ]);
        });
    }
};
