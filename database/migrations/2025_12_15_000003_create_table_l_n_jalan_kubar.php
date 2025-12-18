<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_ln_jalan_kubar', function (Blueprint $table) {
            $table->id();
            $table->string('Nm_Ruas')->nullable();
            $table->string('Fungsi')->nullable();
            $table->decimal('Panjang', 15, 8)->nullable();
            $table->longText('geom')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_ln_jalan_kubar');
    }
};