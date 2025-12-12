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
        Schema::create('table__a_r__batas__kaltim__kabupaten__kota', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('objectid')->nullable()->index();
            $table->string('wadmpr')->nullable();
            $table->string('wadmkk')->nullable();
            $table->double('shape_leng')->nullable();
            $table->double('shape_area')->nullable();

            // Geometry (Polygon / MultiPolygon)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__a_r__batas__kaltim__kabupaten__kota');
    }
};
