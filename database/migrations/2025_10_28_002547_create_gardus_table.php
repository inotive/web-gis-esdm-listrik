<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gardus', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('lokasi', 255)->nullable();
            $table->string('jenis_gardu_distribusi', 100);

            // Kolom indeks wilayah (tanpa FK)
            $table->string('province_id', 2)->nullable()->index();
            $table->string('regency_id', 4)->nullable()->index();
            $table->string('district_id', 7)->nullable()->index();
            $table->string('village_id', 10)->nullable()->index();

            $table->timestamps();

            // Opsi index gabungan (opsional, bisa membantu query)
            $table->index(['province_id', 'regency_id']);
            $table->index(['district_id', 'village_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gardus');
    }
};
