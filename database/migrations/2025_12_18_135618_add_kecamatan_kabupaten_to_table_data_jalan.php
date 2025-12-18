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
        Schema::table('table_data_jalan', function (Blueprint $table) {
            $table->string('kabupaten_kota')->nullable()->after('objectid');
            $table->string('kecamatan')->nullable()->after('kabupaten_kota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_data_jalan', function (Blueprint $table) {
            $table->dropColumn(['kabupaten_kota', 'kecamatan']);
        });
    }
};
