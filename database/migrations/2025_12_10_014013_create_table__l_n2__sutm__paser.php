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
        Schema::create('table__l_n2__sutm__paser', function (Blueprint $table) {
            $table->id();

            $table->string('objectid')->nullable()->index();
            $table->string('name')->nullable();
            $table->text('descriptio')->nullable();
            $table->string('timestamp')->nullable();
            $table->string('begin')->nullable();
            $table->string('end')->nullable();
            $table->string('altitudemo')->nullable();
            $table->integer('tessellate')->nullable();
            $table->integer('extrude')->nullable();
            $table->integer('visibility')->nullable();
            $table->integer('draworder')->nullable();
            $table->string('icon')->nullable();
            $table->string('layer')->nullable();
            $table->string('path')->nullable();
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
        Schema::dropIfExists('table__l_n2__sutm__paser');
    }
};
