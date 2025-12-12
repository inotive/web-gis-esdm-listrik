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
        Schema::create('pt_trafo_gardu_kubar', function (Blueprint $table) {
            $table->id();

            // Properties dari GeoJSON
            $table->string('id_prop')->nullable();      // "id": ""
            $table->string('name')->nullable();         // "Name": "Arrester"
            $table->text('descriptio')->nullable();     // "descriptio": "KAPASITAS: 5 kA<Br>FEEDER: MLK 1..."
            $table->string('timestamp')->nullable();    // "timestamp": ""
            $table->string('begin')->nullable();        // "begin": ""
            $table->string('end')->nullable();          // "end": ""
            $table->string('altitudemo')->nullable();   // "altitudeMo": ""
            $table->integer('tessellate')->nullable();  // "tessellate": -1
            $table->integer('extrude')->nullable();     // "extrude": 0
            $table->integer('visibility')->nullable();  // "visibility": -1
            $table->integer('draworder')->nullable();   // "drawOrder": 0
            $table->string('icon')->nullable();         // "icon": ""
            $table->string('kapasitas')->nullable();    // "KAPASITAS": "5 kA"
            $table->string('feeder')->nullable();       // "FEEDER": "MLK 1"
            $table->string('zona')->nullable();         // "ZONA": "ZONA 1"
            $table->string('nilai_pent')->nullable();   // "NILAI_PENT": "47,6 ohm"
            $table->string('latitude')->nullable();     // "LATITUDE": "-0.242498"
            $table->string('longitude')->nullable();    // "LONGITUDE": "115.800681"
            $table->string('layer')->nullable();        // "layer": "PETA JTM ULP MELAK (1) — DATA ARRESTER JARINGAN.xlsx"
            $table->string('path')->nullable();         // "path": "C:/Users/Lenovo/Downloads/PETA JTM ULP MELAK (1).kml..."

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
        Schema::dropIfExists('pt_trafo_gardu_kubar');
    }
};