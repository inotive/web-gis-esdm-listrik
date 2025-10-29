<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('infrastruktur_jaringan', function (Blueprint $table) {
            $table->id();
            // 1) Jaringan Distribusi/Transmisi
            $table->enum('jaringan', ['distribusi', 'transmisi']);
            // 2) Jenis (bebas: JTM/JTR/Gardu/Trafo, dll)
            $table->string('jenis', 100);
            // 3) Panjang Jaringan (km) — 2 desimal
            $table->decimal('panjang_jaringan', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infrastruktur_jaringan');
    }
};
