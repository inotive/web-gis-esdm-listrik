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
        Schema::table('perizinans', function (Blueprint $table) {
            $table->string('no_surat_izin_terbit')->nullable()->after('no_surat_keluar');
            $table->integer('jumlah')->nullable()->after('titik_koordinat')->comment('Jumlah Unit');
            $table->decimal('kapasitas', 15, 2)->nullable()->after('jumlah')->comment('Kapasitas per unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perizinans', function (Blueprint $table) {
            $table->dropColumn(['no_surat_izin_terbit', 'jumlah', 'kapasitas']);
        });
    }
};
