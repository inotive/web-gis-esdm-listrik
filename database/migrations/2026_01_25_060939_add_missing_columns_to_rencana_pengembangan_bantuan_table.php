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
        Schema::table('rencana_pengembangan_bantuan', function (Blueprint $table) {
            // Add missing columns from Excel
            $table->string('lokasi')->nullable()->after('village_id'); // Nama lokasi/dusun
            $table->string('status_desa_berlistrik')->nullable()->after('lokasi'); // Status berlistrik
            $table->string('kodifikasi')->nullable()->after('status_desa_berlistrik'); // Kode desa
            $table->integer('jumlah_penduduk')->nullable()->after('kodifikasi'); // Total penduduk
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rencana_pengembangan_bantuan', function (Blueprint $table) {
            $table->dropColumn(['lokasi', 'status_desa_berlistrik', 'kodifikasi', 'jumlah_penduduk']);
        });
    }
};
