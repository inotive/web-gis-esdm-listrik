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
        Schema::create('perizinan_listriks', function (Blueprint $table) {
            $table->id();
            $table->year('tahun')->default(2022);
            $table->string('kabupaten_kota');

            // Data Pemohon/Pelaku Usaha
            $table->string('nama_pemohon')->nullable();
            $table->string('kontak')->nullable();
            $table->string('jenis_usaha')->nullable();
            $table->string('no_pengajuan')->nullable();
            $table->string('no_surat_keluar')->nullable()->comment('No. Surat Keluar Rekomtek/Pertek');

            // Data Perizinan/Non Perizinan
            $table->date('tanggal_perizinan')->nullable();
            $table->string('no_surat_izin')->nullable()->comment('No. Surat Izin Terbit');
            $table->date('tanggal_terbit')->nullable();
            $table->date('tanggal_akhir')->nullable();

            // Lokasi
            $table->text('lokasi')->nullable();
            $table->string('koordinat')->nullable()->comment('Titik Koordinat (Lat, Long)');

            // Data Pembangkit Listrik
            $table->integer('jumlah_unit')->nullable()->comment('Jumlah unit pembangkit');
            $table->decimal('kapasitas', 12, 2)->nullable()->comment('Kapasitas per unit');
            $table->decimal('total_kapasitas', 12, 2)->nullable()->comment('Total Kapasitas (kVA)');

            // Klasifikasi
            $table->string('jenis')->nullable()->comment('PLTD, PLTS, dll');
            $table->string('sifat_penggunaan')->nullable()->comment('DARURAT, UTAMA, dll');
            $table->text('catatan')->nullable();

            $table->timestamps();

            // Index untuk query
            $table->index(['tahun', 'kabupaten_kota']);
            $table->index('jenis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perizinan_listriks');
    }
};
