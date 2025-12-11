<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_data_jalan', function (Blueprint $table) {
            $table->id();

            // Field mengikuti properties di GeoJSON
            $table->integer('objectid')->nullable()->index();
            $table->string('fungsi_jal')->nullable();     // Fungsi_Jal
            $table->string('nama_jln')->nullable();       // Nama_Jln
            $table->text('sumber')->nullable();           // Sumber
            $table->double('shape_leng')->nullable();     // Shape_Leng

            // Geometry GeoJSON (LineString/MultiLineString dsb)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_data_jalan');
    }
};
