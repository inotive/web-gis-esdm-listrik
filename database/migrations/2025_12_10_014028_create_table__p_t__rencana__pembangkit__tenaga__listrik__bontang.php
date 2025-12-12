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
        Schema::create('table__p_t__rencana__pembangkit__tenaga__listrik__bontang', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_external')->nullable()->index('pt_rencana_bontang_id_idx');
            $table->string('nama')->nullable();
            $table->string('arahan')->nullable();
            $table->string('fungsi_eks')->nullable();
            $table->string('fungsi_ren')->nullable();
            $table->text('penjelasan')->nullable();
            $table->string('sumber')->nullable();

            // Geometry (Point / Polygon)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__p_t__rencana__pembangkit__tenaga__listrik__bontang');
    }
};
