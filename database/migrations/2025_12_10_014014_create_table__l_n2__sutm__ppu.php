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
        Schema::create('table__l_n2__sutm__ppu', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('objectid')->nullable()->index();
            $table->integer('fid_jalan')->nullable();
            $table->string('namobj')->nullable();
            $table->string('fcode')->nullable();
            $table->text('remark')->nullable();
            $table->string('metadata')->nullable();
            $table->string('srs_id')->nullable();
            $table->integer('arhrjl')->nullable();
            $table->integer('autrjl')->nullable();
            $table->integer('fgsrjl')->nullable();
            $table->integer('jarrjl')->nullable();
            $table->integer('jparjl')->nullable();
            $table->string('kllrjl')->nullable();
            $table->integer('konrjl')->nullable();
            $table->integer('kpmstr')->nullable();
            $table->string('lkonof')->nullable();
            $table->string('lksbsp')->nullable();
            $table->string('lksrta')->nullable();
            $table->integer('llhrrt')->nullable();
            $table->integer('locrjl')->nullable();
            $table->integer('lbrbhj')->nullable();
            $table->integer('lbrjln')->nullable();
            $table->integer('matrjl')->nullable();
            $table->integer('medrjl')->nullable();
            $table->integer('spcrjl')->nullable();
            $table->integer('starjl')->nullable();
            $table->integer('tolrjl')->nullable();
            $table->integer('utkrjl')->nullable();
            $table->integer('vlcprt')->nullable();
            $table->integer('wlyrjl')->nullable();
            $table->string('tgl_sk')->nullable();
            $table->integer('jlnlyg')->nullable();
            $table->integer('klsrjl')->nullable();
            $table->string('jalanlistr')->nullable();
            $table->integer('fid_batasp')->nullable();
            $table->string('wadmkc')->nullable();
            $table->string('wadmkd')->nullable();
            $table->string('wadmkk')->nullable();
            $table->string('wadmpr')->nullable();
            $table->double('shape_leng')->nullable();
            $table->double('panjang')->nullable();

            // Geometry (LineString / MultiLineString)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__l_n2__sutm__ppu');
    }
};
