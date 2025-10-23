<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kategori_asset', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->nullable();
            $table->string('name');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kategori_asset');
    }
};