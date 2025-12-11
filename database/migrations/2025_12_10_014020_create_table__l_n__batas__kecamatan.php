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
        Schema::create('table__l_n__batas__kecamatan', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('fid_ar_bat')->nullable()->index();
            $table->string('wadmpr')->nullable();
            $table->string('wadmkk')->nullable();
            $table->string('wadmkc')->nullable();
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
        Schema::dropIfExists('table__l_n__batas__kecamatan');
    }
};
