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
        Schema::create('table__l_n__sistem__jaringan__energi__mahulu', function (Blueprint $table) {
            $table->id();

            // OBJECTID dari GeoJSON
            $table->unsignedBigInteger('objectid')->nullable()->index();

            // Properti dari GeoJSON
            $table->string('namobj')->nullable();
            $table->string('orde01')->nullable();
            $table->string('orde02')->nullable();
            $table->string('orde03')->nullable();
            $table->string('orde04')->nullable();
            $table->string('jnsrsr')->nullable();
            $table->integer('stsjrn')->nullable();
            $table->string('wadmpr')->nullable();       // "WADMPR": "Provinsi Kalimantan Timur"
            $table->string('wadmkk')->nullable();       // "WADMKK": "Kabupaten Mahakam Ulu"
            $table->text('remark')->nullable();         // "REMARK": "Tidak Ada"
            $table->string('sbdata')->nullable();       // "SBDATA": "Analisis RTRW, 2022"
            $table->double('shape_leng')->nullable();   // "SHAPE_Leng": 0.00428936791446

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
        Schema::dropIfExists('table__l_n__sistem__jaringan__energi__mahulu');
    }
};
