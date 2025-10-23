<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_dokumen', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key ke Asset
            $table->foreignId('asset_id')
                ->constrained('asset')
                ->onDelete('cascade');
            
            // Informasi Sertifikat
            $table->string('no_sertif')->nullable()->comment('Nomor Sertifikat');
            $table->date('tgl_sertif')->nullable()->comment('Tanggal Sertifikat');
            $table->string('nama_sertifikat')->nullable()->comment('Nama Pemegang Sertifikat');
            
            // ✅ KOLOM BARU: Informasi Dokumen Tambahan
            $table->string('no_dokumen')->nullable()->comment('Nomor Dokumen');
            $table->date('tanggal_dokumen')->nullable()->comment('Tanggal Dokumen');
            $table->date('tanggal_oleh')->nullable()->comment('Tanggal Diolah/Diproses');
            $table->date('tanggal_buku')->nullable()->comment('Tanggal Buku Tanah');
            $table->boolean('has_konfir')->default(false)->comment('Status Konfirmasi Dokumen');
            
            // Status & Keterangan
            $table->string('sts_digit')->nullable()->comment('Status Digitalisasi');
            $table->text('sts_sertif')->nullable()->comment('Status Sertifikat');
            $table->text('ket_sertif')->nullable()->comment('Keterangan Sertifikat');
            $table->string('konf_tanah')->nullable()->comment('Konfirmasi Status Tanah');
            
            // File & Link Dokumen
            $table->string('file_sertif')->nullable()->comment('Path file sertifikat');
            $table->string('link_sertif')->nullable()->comment('Link eksternal sertifikat');
            
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index('asset_id');
            $table->index('no_sertif');
            $table->index('no_dokumen');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_dokumen');
    }
};