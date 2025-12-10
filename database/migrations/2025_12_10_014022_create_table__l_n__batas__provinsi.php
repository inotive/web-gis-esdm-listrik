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
        Schema::create('table__l_n__batas__provinsi', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('fid_export')->nullable()->index();
            $table->string('wadmpr')->nullable();
            $table->double('shape_leng')->nullable();

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
        Schema::dropIfExists('table__l_n__batas__provinsi');
    }
};
