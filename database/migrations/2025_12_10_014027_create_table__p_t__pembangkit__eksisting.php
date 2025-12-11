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
        Schema::create('table__p_t__pembangkit__eksisting', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('objectid')->nullable()->index();
            $table->string('namobj')->nullable();
            $table->integer('orde01')->nullable();
            $table->integer('orde02')->nullable();
            $table->integer('orde03')->nullable();
            $table->integer('orde04')->nullable();
            $table->integer('jnsrsr')->nullable();
            $table->integer('stsjrn')->nullable();
            $table->string('wadmpr')->nullable();
            $table->text('remark')->nullable();
            $table->string('sbdata')->nullable();
            $table->string('j_pmbngkt')->nullable();

            // Geometry (Point/Polygon)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__p_t__pembangkit__eksisting');
    }
};
