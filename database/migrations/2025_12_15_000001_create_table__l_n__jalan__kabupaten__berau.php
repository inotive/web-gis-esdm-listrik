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
        Schema::create('table__l_n__jalan__kabupaten__berau', function (Blueprint $table) {
            $table->id();

            // OBJECTID dari GeoJSON
            $table->unsignedBigInteger('objectid')->nullable()->index();

            // Properti dari GeoJSON
            $table->integer('no_ruas')->nullable();         // "NO_RUAS": 2107
            $table->string('nama_ruas')->nullable();       // "NAMA_RUAS": "Gg Setia Lanjutan"
            $table->string('kab_kota')->nullable();        // "KAB_KOTA": "Berau"
            $table->text('ttk_pngkal')->nullable();        // "TTK_PNGKAL": "2° 8' 34.343\" N, 117° 29' 48.450\" E"
            $table->text('ttk_akhir')->nullable();         // "TTK_AKHIR": "2° 8' 25.235\" N, 117° 29' 57.510\" E"
            $table->double('panjang')->nullable();         // "PANJANG": 0.434
            $table->integer('jkp_2')->nullable();          // "JKP_2": 0
            $table->integer('jkp_3')->nullable();          // "JKP_3": 0
            $table->integer('jkp_4')->nullable();          // "JKP_4": 0
            $table->integer('jlp')->nullable();            // "JLP": 0
            $table->double('jling_p')->nullable();         // "Jling_P": 0.434
            $table->integer('jas')->nullable();            // "JAS": 0
            $table->integer('jks')->nullable();            // "JKS": 0
            $table->integer('jls')->nullable();            // "JLS": 0
            $table->double('jling_s')->nullable();         // "Jling_S": 0
            $table->string('fungsi')->nullable();          // "FUNGSI": "Jling-P"
            $table->string('status')->nullable();          // "STATUS": "Desa"
            $table->double('shape_leng')->nullable();      // "Shape_Leng": 434.123217259

            // Geometry (LineString)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__l_n__jalan__kabupaten__berau');
    }
};