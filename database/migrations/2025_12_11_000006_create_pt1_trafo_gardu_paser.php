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
        Schema::create('pt1_trafo_gardu_paser', function (Blueprint $table) {
            $table->id();

            // Properties dari GeoJSON
            $table->integer('id_prop')->nullable();      // "Id": 0
            $table->string('name')->nullable();          // "Name": "257101"
            $table->string('descript')->nullable();      // "Descript": "2023-01-17 6:29:51PM"
            $table->string('type')->nullable();          // "Type": "RTEPT"
            $table->string('comment')->nullable();       // "Comment": "2023-01-17 6:29:51PM"
            $table->string('symbol')->nullable();        // "Symbol": "Civil"
            $table->string('datetimes')->nullable();     // "DateTimeS": "2023-01-17T10:29:51Z"
            $table->double('elevation')->nullable();     // "Elevation": 32.263248
            $table->string('nama')->nullable();          // "Nama": ""
            $table->string('data')->nullable();          // "Data": "Gardu dan Trafo Lainnya"

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
        Schema::dropIfExists('pt1_trafo_gardu_paser');
    }
};