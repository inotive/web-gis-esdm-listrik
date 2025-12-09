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
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('tipe', ['folder', 'file'])->default('file');
            $table->foreignId('parent_id')->nullable()->constrained('dokumens')->onDelete('cascade');
            $table->string('path')->nullable(); // Path file di storage
            $table->string('mime_type')->nullable(); // MIME type untuk file
            $table->bigInteger('size')->nullable(); // Ukuran file dalam bytes
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};
