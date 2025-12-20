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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_identity_verified')) {
                $table->boolean('is_identity_verified')->default(false)->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'identity_verified_at')) {
                $table->timestamp('identity_verified_at')->nullable()->after('is_identity_verified');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_identity_verified')) {
                $table->dropColumn('is_identity_verified');
            }
            if (Schema::hasColumn('users', 'identity_verified_at')) {
                $table->dropColumn('identity_verified_at');
            }
        });
    }
};
