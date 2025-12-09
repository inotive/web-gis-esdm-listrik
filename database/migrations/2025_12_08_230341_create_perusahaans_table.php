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
        Schema::create('perusahaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->char('village_id', 10)->index(); // Foreign key ke reg_villages (CHAR(10))
            $table->timestamps();

            // Foreign key constraint
            // $table->foreign('village_id')
            //     ->references('id')
            //     ->on('reg_villages')
            //     ->onDelete('restrict')
            //     ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaans');
    }
};
