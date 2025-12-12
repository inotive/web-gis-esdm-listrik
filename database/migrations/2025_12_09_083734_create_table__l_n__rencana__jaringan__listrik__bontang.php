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
        Schema::create('table__l_n__rencana__jaringan__listrik__bontang', function (Blueprint $table) {
            $table->id();

            // Id dari GeoJSON
            $table->unsignedBigInteger('source_id')->nullable()->index();

            // Properti dari GeoJSON
            $table->string('rencana')->nullable();       // "Rencana": "Kawat Saluran Udara"
            $table->string('fungsi_eks')->nullable();    // "fungsi_eks": "SUTM"
            $table->string('fungsi_ren')->nullable();    // "fungsi_ren": "SUTM/SKTM"
            $table->text('keterangan')->nullable();      // "Keterangan": "Pemeliharaan dan Pengembangan"
            $table->string('sumber')->nullable();        // "Sumber": "PLN Rayon Bontang, Tahun 2015"

            // Geometry (LineString / MultiLineString)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__l_n__rencana__jaringan__listrik__bontang');
    }
};
