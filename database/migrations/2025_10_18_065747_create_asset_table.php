<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset', function (Blueprint $table) {
            $table->id();

            // ✅ Wilayah gunakan STRING karena ID-nya string
            $table->string('reg_provinces_id')->nullable()->index();
            $table->string('reg_regencies_id')->nullable()->index();
            $table->string('reg_districts_id')->nullable()->index();
            $table->string('reg_villages_id')->nullable()->index();

            // Relasi lainnya tetap pakai foreign key Laravel
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_asset')->nullOnDelete();
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerja')->nullOnDelete();
            $table->foreignId('status_hukum_id')->nullable()->constrained('status_hukum_asset')->nullOnDelete();

            // Kolom informasi aset
            $table->string('kode_asset')->unique();
            $table->string('nama_asset');
            $table->string('no_register')->nullable();
            $table->string('nomor_hak')->nullable();
            $table->string('alamat')->nullable();
            $table->string('penggunaan_spma')->nullable();
            $table->string('jenis_hak')->nullable();
            
        
            $table->string('asal')->nullable()->comment('Asal perolehan tanah');
            $table->string('kat_tanah')->nullable()->comment('Kategori tanah');
            
            $table->string('kode')->nullable();
            $table->string('nui')->nullable();
            $table->string('nib')->nullable();
            $table->decimal('luas_m2', 12, 2)->nullable();
            $table->decimal('panjang_m', 12, 2)->nullable();
            $table->decimal('lebar_m', 12, 2)->nullable();
            $table->decimal('latitude', 10, 8)->nullable()->index();
            $table->decimal('longitude', 11, 8)->nullable()->index();
            $table->json('geojson')->nullable();
         
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asset');
    }
};