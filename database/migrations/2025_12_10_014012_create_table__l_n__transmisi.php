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
        Schema::create('table__l_n__transmisi', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('objectid')->nullable()->index();
            $table->string('namobj')->nullable();
            $table->string('orde01')->nullable();
            $table->string('orde02')->nullable();
            $table->string('orde03')->nullable();
            $table->string('orde04')->nullable();
            $table->string('jnsrsr')->nullable();
            $table->integer('stsjrn')->nullable();
            $table->string('wadmpr')->nullable();
            $table->text('remark')->nullable();
            $table->string('sbdata')->nullable();
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
        Schema::dropIfExists('table__l_n__transmisi');
    }
};
