<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembangkit_listriks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->onDelete('cascade');
            $table->foreignId('perizinan_listrik_id')->nullable()->constrained('perizinan_listriks')->onDelete('set null');

            // Data lokasi
            $table->text('lokasi')->nullable();
            $table->string('koordinat')->nullable();

            // Data pembangkit
            $table->integer('jumlah_unit')->nullable();
            $table->decimal('kapasitas', 12, 2)->nullable()->comment('Kapasitas per unit');
            $table->decimal('total_kapasitas', 12, 2)->nullable()->comment('Total Kapasitas (kVA)');
            $table->string('jenis')->nullable()->comment('PLTD, PLTS, dll');
            $table->string('sifat_penggunaan')->nullable()->comment('DARURAT, UTAMA, dll');
            $table->text('catatan')->nullable();

            $table->timestamps();

            // Index
            $table->index('jenis');
            $table->index('perusahaan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembangkit_listriks');
    }
};
