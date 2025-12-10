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
        Schema::create('table__l_n__batas__desa', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('objectid')->nullable()->index();
            $table->unsignedBigInteger('fid_ar_bat')->nullable()->index();
            $table->string('wadmpr')->nullable();
            $table->string('wadmkk')->nullable();
            $table->string('wadmkc')->nullable();
            $table->string('wadmkd')->nullable();
            $table->string('namobj')->nullable();
            $table->integer('tipadm')->nullable();
            $table->text('remark')->nullable();
            $table->string('uupp')->nullable();
            $table->double('luaswh')->nullable();
            $table->double('luas')->nullable();
            $table->double('shape_leng')->nullable();

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
        Schema::dropIfExists('table__l_n__batas__desa');
    }
};
