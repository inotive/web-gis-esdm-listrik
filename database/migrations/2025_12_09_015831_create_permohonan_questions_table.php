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
        Schema::create('permohonan_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('urutan')->default(0);
            $table->foreignId('permohonan_id')->constrained('permohonans')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->string('tipe');
            $table->boolean('wajib')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_questions');
    }
};
