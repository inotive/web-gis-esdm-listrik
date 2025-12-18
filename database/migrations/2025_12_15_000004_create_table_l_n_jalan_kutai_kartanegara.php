<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_ln_jalan_kutai_kartanegara', function (Blueprint $table) {
            $table->id();
            $table->integer('NO_LAMA')->nullable();
            $table->string('NO_BARU')->nullable();
            $table->string('NAMA_LAMA')->nullable();
            $table->string('NAMA_BARU')->nullable();
            $table->decimal('P_Km', 15, 8)->nullable();
            $table->string('KECAMATAN')->nullable();
            $table->integer('URUT')->nullable();
            $table->string('PANGKAL')->nullable();
            $table->string('UJUNG')->nullable();
            $table->string('KOOR_PANGK')->nullable();
            $table->string('KOOR_UJUNG')->nullable();
            $table->decimal('LEBAR_M', 8, 2)->nullable();
            $table->string('FUNGSI')->nullable();
            $table->string('HISTORY')->nullable();
            $table->decimal('Panjang', 15, 8)->nullable();
            $table->longText('geom')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_ln_jalan_kutai_kartanegara');
    }
};