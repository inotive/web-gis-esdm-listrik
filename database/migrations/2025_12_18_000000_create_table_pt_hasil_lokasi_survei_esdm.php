<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_pt_hasil_lokasi_survei_esdm', function (Blueprint $table) {
            $table->id();
            $table->string('Lokasi')->nullable();
            $table->string('NAMOBJ')->nullable();
            $table->decimal('LUASWH', 15, 2)->nullable();
            $table->integer('TIPADM')->nullable();
            $table->string('WADMKC')->nullable();
            $table->string('WADMKD')->nullable();
            $table->string('WADMKK')->nullable();
            $table->string('WADMPR')->nullable();
            $table->string('Status')->nullable();
            $table->string('Kode_Kota')->nullable();
            $table->string('Kode_L')->nullable();
            $table->integer('Lokasi_Ke')->nullable();
            $table->string('Kodifikasi')->nullable();
            $table->string('DUSUN')->nullable();
            $table->integer('JUMLAH_RT')->nullable();
            $table->string('KET_RT')->nullable();
            $table->integer('J_Pnddk')->nullable();
            $table->integer('J_KK')->nullable();
            $table->integer('J_BRumah')->nullable();
            $table->integer('J_BFasum')->nullable();
            $table->string('Ket_BFasum')->nullable();
            $table->string('S_L_Kom')->nullable();
            $table->string('N_S_L')->nullable();
            $table->string('K_S_L')->nullable();
            $table->string('S_P_L')->nullable();
            $table->string('W_NYALA')->nullable();
            $table->decimal('L_NYALA', 10, 2)->nullable();
            $table->decimal('T_SL', 10, 2)->nullable();
            $table->string('Knd_S_L')->nullable();
            $table->string('Koor_X')->nullable();
            $table->string('Koor_Y')->nullable();
            $table->string('PR_Prov')->nullable();
            $table->string('K_Hutan')->nullable();
            $table->string('Izin_Lain')->nullable();
            $table->string('Potensi')->nullable();
            $table->decimal('R_JUTAMA', 15, 2)->nullable();
            $table->decimal('R_JLISTRIK', 15, 2)->nullable();
            $table->string('K_Jalan')->nullable();
            $table->decimal('L_Jalan', 10, 2)->nullable();
            $table->string('P_Jalan')->nullable();
            $table->string('PENYULANG')->nullable();
            $table->string('R_S_L')->nullable();
            $table->string('KENDALA')->nullable();
            $table->string('I_IUPT')->nullable();
            $table->string('I_PPBH')->nullable();
            $table->string('I_IUPK')->nullable();
            $table->integer('J_Gardu')->nullable();
            $table->decimal('B_Gardu', 10, 2)->nullable();
            $table->string('S_L_P')->nullable();
            $table->string('K_RPLTS')->nullable();
            $table->decimal('Panjang', 15, 2)->nullable();
            $table->decimal('Tiang', 15, 2)->nullable();
            $table->decimal('Biaya', 20, 2)->nullable();
            $table->integer('Skor_A')->nullable();
            $table->integer('Skor_J')->nullable();
            $table->string('K_PR')->nullable();
            $table->string('K_Izin')->nullable();
            $table->string('K_Hutan_1')->nullable();
            $table->integer('S_Arah')->nullable();
            $table->integer('S_Potensi')->nullable();
            $table->integer('S_J_P')->nullable();
            $table->integer('T_S')->nullable();
            $table->string('Cek')->nullable();
            $table->string('Priorita_1')->nullable();
            $table->string('B_PLTS')->nullable();
            $table->text('geom')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_pt_hasil_lokasi_survei_esdm');
    }
};