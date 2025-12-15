<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_ln_jalan_balikpapan', function (Blueprint $table) {
            $table->id();
            $table->string('Kecamatan')->nullable();
            $table->decimal('F18', 10, 2)->nullable();
            $table->string('F19')->nullable();
            $table->string('F20')->nullable();
            $table->string('F21')->nullable();
            $table->string('KODE_RUAS')->nullable();
            $table->string('NAMA_RUAS')->nullable();
            $table->integer('TAHUN_DATA')->nullable();
            $table->string('FUNGSI')->nullable();
            $table->decimal('LEBAR', 10, 2)->nullable();
            $table->decimal('PANJANG', 15, 2)->nullable();
            $table->string('KOORD_X_AW')->nullable();
            $table->string('KOORD_Y_AW')->nullable();
            $table->string('KOORD_X_AK')->nullable();
            $table->string('KOORD_Y_AK')->nullable();
            $table->decimal('Shape_Le_1', 15, 6)->nullable();
            $table->text('geom')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_ln_jalan_balikpapan');
    }
};