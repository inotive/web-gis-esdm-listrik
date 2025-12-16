<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_ln_jalan_kutim', function (Blueprint $table) {
            $table->id();
            $table->string('Kl_Dat_Das')->nullable();
            $table->string('No_Ruas')->nullable();
            $table->string('Nm_Ruas')->nullable();
            $table->string('Fungsi')->nullable();
            $table->string('Kecamatan')->nullable();
            $table->string('Desa_Kel')->nullable();
            $table->string('Tk_Ruas_Aw')->nullable();
            $table->string('Tk_Ruas_Ak')->nullable();
            $table->decimal('Panjang', 15, 8)->nullable();
            $table->decimal('Koord_X_Aw', 15, 8)->nullable();
            $table->decimal('Koord_Y_Aw', 15, 8)->nullable();
            $table->decimal('Koord_X_Ak', 15, 8)->nullable();
            $table->decimal('Koord_Y_Ak', 15, 8)->nullable();
            $table->longText('geom')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_ln_jalan_kutim');
    }
};