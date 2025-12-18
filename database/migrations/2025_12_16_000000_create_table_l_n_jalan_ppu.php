<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_ln_jalan_ppu', function (Blueprint $table) {
            $table->id();
            $table->integer('OID_')->nullable();
            $table->string('Name')->nullable();
            $table->string('FolderPath')->nullable();
            $table->integer('SymbolID')->nullable();
            $table->integer('Clamped')->nullable();
            $table->decimal('Shape_Leng', 15, 6)->nullable();
            $table->longText('geom')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_ln_jalan_ppu');
    }
};