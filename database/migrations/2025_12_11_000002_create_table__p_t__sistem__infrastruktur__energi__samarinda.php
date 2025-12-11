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
        Schema::create('pt_sistem_infrastruktur_energi_samarinda', function (Blueprint $table) {
            $table->id();

            // OBJECTID dari GeoJSON
            $table->unsignedBigInteger('objectid')->nullable()->index();

            // Properti dari GeoJSON
            $table->string('namobj')->nullable();       // "NAMOBJ": "Gardu Listrik"
            $table->string('orde01')->nullable();      // "ORDE01": 43020000
            $table->string('orde02')->nullable();      // "ORDE02": 43021000
            $table->string('orde03')->nullable();      // "ORDE03": 13021400
            $table->string('orde04')->nullable();      // "ORDE04": 13021400
            $table->string('jnsrsr')->nullable();      // "JNSRSR": 43000000
            $table->integer('stsjrn')->nullable();     // "STSJRN": 2
            $table->string('wadmpr')->nullable();      // "WADMPR": "Provinsi Kalimantan Timur"
            $table->string('wadmkk')->nullable();      // "WADMKK": "Kota Samarinda"
            $table->text('remark')->nullable();        // "REMARK": "Gardu Induk Bukuan"
            $table->string('sbdata')->nullable();      // "SBDATA": "Kepmen ESDM tentang RUPTL PT. PLN Tahun 2019-2028"

            // Geometry (Point)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__p_t__sistem__infrastruktur__energi__samarinda');
    }
};