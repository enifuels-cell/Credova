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
        Schema::table('barangays', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('district');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('area_type')->nullable()->after('longitude'); // uptown, downtown, suburban, etc.
            $table->text('description')->nullable()->after('area_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangays', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'area_type', 'description']);
        });
    }
};
