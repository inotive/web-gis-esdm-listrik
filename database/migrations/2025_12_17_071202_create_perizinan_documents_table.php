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
        Schema::create('perizinan_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perizinan_id')->constrained('perizinans')->onDelete('cascade');
            $table->foreignId('dokumen_id')->constrained('dokumens')->onDelete('cascade');
            $table->string('nama');
            $table->string('no_surat_izin_terbit')->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perizinan_documents');
    }
};
