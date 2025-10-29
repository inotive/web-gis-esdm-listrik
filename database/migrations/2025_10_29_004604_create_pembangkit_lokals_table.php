<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembangkit_lokals', function (Blueprint $table) {
            $table->id();

            // Relasi lokasi -> tabel wilayah
            $table->unsignedBigInteger('wilayah_id')->nullable()->index();
            $table->foreign('wilayah_id')->references('id')->on('wilayah')->nullOnDelete();

            // Kapasitas gardu (string agar fleksibel pakai satuan)
            $table->string('kapasitas_gardu', 100);

            $table->timestamps();

            // Optional: index gabungan
            $table->index(['wilayah_id', 'kapasitas_gardu']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembangkit_lokals');
    }
};
