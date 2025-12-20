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
            $table->integer('guest_count')->default(1)->after('total_price');
            $table->decimal('cleaning_fee', 8, 2)->default(0)->after('guest_count');
            $table->decimal('service_fee', 8, 2)->default(0)->after('cleaning_fee');
            $table->decimal('taxes', 8, 2)->default(0)->after('service_fee');
            $table->text('check_in_instructions')->nullable()->after('special_requests');
            $table->text('cancellation_reason')->nullable()->after('check_in_instructions');
            $table->timestamp('confirmed_at')->nullable()->after('cancellation_reason');
            $table->timestamp('cancelled_at')->nullable()->after('confirmed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'guest_count',
                'cleaning_fee',
                'service_fee',
                'taxes',
                'check_in_instructions',
                'cancellation_reason',
                'confirmed_at',
                'cancelled_at'
            ]);
        });
    }
};
