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
        Schema::create('inspeksis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('referensi_izin');
            $table->foreignId('perusahaan_id')->constrained('perusahaans')->onDelete('cascade');
            $table->foreignId('ditambahkan_oleh')->constrained('users')->onDelete('cascade');
            $table->string('berita_acara')->nullable();
            $table->string('lampiran')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspeksis');
    }
};
