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
        Schema::create('perizinans', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->onDelete('cascade');
            $table->string('kontak')->nullable();
            $table->string('jenis')->nullable();
            $table->string('no_pengajuan')->nullable();
            $table->string('no_surat_keluar')->nullable()->comment('Rekomtek/Pertek');
            $table->date('tanggal')->nullable();
            $table->text('lokasi')->nullable();
            $table->string('titik_koordinat')->nullable();
            $table->decimal('jumlah_kapasitas', 15, 2)->nullable();
            $table->integer('total_kapasitas_kva')->nullable();
            $table->string('jenis_penggunaan')->nullable()->comment('Jenis kedua');
            $table->string('sifat_penggunaan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perizinans');
    }
};
