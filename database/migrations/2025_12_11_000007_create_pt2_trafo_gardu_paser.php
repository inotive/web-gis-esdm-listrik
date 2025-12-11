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
        Schema::create('pt2_trafo_gardu_paser', function (Blueprint $table) {
            $table->id();

            // Properties dari GeoJSON
            $table->string('id_prop')->nullable();      // "id": ""
            $table->string('name')->nullable();         // "Name": "GARDU INDUK GROGOT"
            $table->text('descriptio')->nullable();     // "descriptio": "TES 1: GRT.1<br>TES 2: 1..."
            $table->string('timestamp')->nullable();    // "timestamp": "GI"
            $table->string('begin')->nullable();        // "begin": ""
            $table->string('end')->nullable();          // "end": ""
            $table->string('altitudemo')->nullable();   // "altitudeMo": ""
            $table->integer('tessellate')->nullable();  // "tessellate": -1
            $table->integer('extrude')->nullable();     // "extrude": 0
            $table->integer('visibility')->nullable();  // "visibility": -1
            $table->integer('draworder')->nullable();   // "drawOrder": 0
            $table->string('icon')->nullable();         // "icon": ""
            $table->string('tes_1')->nullable();        // "TES_1": "GRT.1"
            $table->string('tes_2')->nullable();        // "TES_2": "1.0"
            $table->string('tes_4')->nullable();        // "TES_4": "TEPIAN BATANG"
            $table->string('tes_5')->nullable();        // "TES_5": "-1.898404, 116.16562"
            $table->string('tes_6')->nullable();        // "TES_6": "NC"

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
        Schema::dropIfExists('pt2_trafo_gardu_paser');
    }
};