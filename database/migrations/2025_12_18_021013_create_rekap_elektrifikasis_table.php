<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rekap_elektrifikasis', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->string('no_urut', 10)->comment('Nomor urut romawi: I, II, III, dst');
            $table->string('kabupaten_kota');
            $table->integer('jumlah_desa')->default(0);
            $table->integer('jumlah_kk')->default(0)->comment('Jumlah Kepala Keluarga');
            $table->integer('jumlah_penduduk')->default(0);
            $table->integer('desa_berlistrik_pln')->default(0);
            $table->integer('desa_berlistrik_non_pln')->default(0);
            $table->integer('desa_berlistrik_jumlah')->default(0);
            $table->integer('desa_belum_berlistrik')->default(0);
            $table->integer('kk_berlistrik_pln')->default(0);
            $table->integer('kk_berlistrik_non_pln')->default(0);
            $table->integer('kk_berlistrik_jumlah')->default(0);
            $table->decimal('rasio_desa_berlistrik', 6, 2)->default(0)->comment('Rasio dalam persen');
            $table->integer('jumlah_kk_belum_berlistrik')->default(0);
            $table->decimal('rasio_elektrifikasi', 6, 2)->default(0)->comment('Rasio dalam persen');
            $table->timestamps();

            // Index untuk query per tahun
            $table->index(['tahun', 'kabupaten_kota']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_elektrifikasis');
    }
};
