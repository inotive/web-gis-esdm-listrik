<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_ln_jalan_berau', function (Blueprint $table) {
            $table->id();
            $table->integer('OBJECTID')->nullable();
            $table->integer('NO_RUAS')->nullable();
            $table->string('NAMA_RUAS')->nullable();
            $table->string('KAB_KOTA')->nullable();
            $table->string('TTK_PNGKAL')->nullable();
            $table->string('TTK_AKHIR')->nullable();
            $table->decimal('PANJANG', 15, 6)->nullable();
            $table->integer('JKP_2')->nullable();
            $table->integer('JKP_3')->nullable();
            $table->integer('JKP_4')->nullable();
            $table->integer('JLP')->nullable();
            $table->decimal('Jling_P', 15, 6)->nullable();
            $table->integer('JAS')->nullable();
            $table->integer('JKS')->nullable();
            $table->integer('JLS')->nullable();
            $table->decimal('Jling_S', 15, 6)->nullable();
            $table->string('FUNGSI')->nullable();
            $table->string('STATUS')->nullable();
            $table->decimal('Shape_Leng', 15, 6)->nullable();
            $table->longText('geom')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_ln_jalan_berau');
    }
};