<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_has_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('asset')->cascadeOnDelete();
            $table->foreignId('kategori_dokumen_id')->nullable()->constrained('kategori_dokumen')->nullOnDelete();
            $table->string('url');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asset_has_dokumen');
    }
};