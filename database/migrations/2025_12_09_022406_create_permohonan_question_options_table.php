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
        Schema::create('permohonan_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_question_id')->constrained('permohonan_questions')->onDelete('cascade');
            $table->string('opsi');
            $table->string('keterangan')->nullable();
            $table->boolean('wajib')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_question_options');
    }
};
