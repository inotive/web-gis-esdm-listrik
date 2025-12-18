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
        Schema::table('t_pt_hasil_lokasi_survei_esdm', function (Blueprint $table) {
            $table->text('link_dokumen')->nullable()->after('Kodifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_pt_hasil_lokasi_survei_esdm', function (Blueprint $table) {
            $table->dropColumn('link_dokumen');
        });
    }
};
