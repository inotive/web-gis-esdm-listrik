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
        Schema::create('table__l_n__sutm__ppu', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('objectid')->nullable()->index();
            $table->integer('no_ruas')->nullable();
            $table->integer('kode_kelas')->nullable();
            $table->string('nama_jalan')->nullable();
            $table->string('nama_pangk')->nullable();
            $table->string('nama_ujung')->nullable();
            $table->string('titk_penge')->nullable();
            $table->string('titik_peng')->nullable();
            $table->double('panjang')->nullable();
            $table->double('lebar')->nullable();
            $table->double('aspal')->nullable();
            $table->double('rijit')->nullable();
            $table->double('perkerasan')->nullable();
            $table->double('tanah')->nullable();
            $table->string('kondisi')->nullable();
            $table->string('th_pekerja')->nullable();
            $table->string('ket')->nullable();
            $table->double('shape_leng')->nullable();
            $table->integer('legacy_id')->nullable(); // ID
            $table->string('foto')->nullable();
            $table->string('status_jal')->nullable();
            $table->string('fungsi_jal')->nullable();
            $table->string('sistem_jal')->nullable();
            $table->string('nama')->nullable();
            $table->string('dana')->nullable();
            $table->string('nama_jal_1')->nullable();
            $table->string('kode_rtrw')->nullable();
            $table->string('statusrtrw')->nullable();
            $table->double('shape_le_1')->nullable();

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
        Schema::dropIfExists('table__l_n__sutm__ppu');
    }
};
