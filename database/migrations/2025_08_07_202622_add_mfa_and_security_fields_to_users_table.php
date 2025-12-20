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
            if (!Schema::hasColumn('users', 'mfa_enabled')) {
                $table->boolean('mfa_enabled')->default(false)->after('identity_verified_at');
            }
            if (!Schema::hasColumn('users', 'force_mfa')) {
                $table->boolean('force_mfa')->default(false)->after('mfa_enabled');
            }
            if (!Schema::hasColumn('users', 'mfa_secret')) {
                $table->string('mfa_secret')->nullable()->after('force_mfa');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('mfa_secret');
            }
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->ipAddress('last_login_ip')->nullable()->after('last_login_at');
            }
            if (!Schema::hasColumn('users', 'preferred_locale')) {
                $table->string('preferred_locale', 5)->default('en')->after('last_login_ip');
            }
            if (!Schema::hasColumn('users', 'security_level')) {
                $table->enum('security_level', ['low', 'medium', 'high'])->default('medium')->after('preferred_locale');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['mfa_enabled', 'force_mfa', 'mfa_secret', 'last_login_at', 'last_login_ip', 'preferred_locale', 'security_level'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
