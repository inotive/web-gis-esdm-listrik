<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_ln_jalan_paser', function (Blueprint $table) {
            $table->id();
            $table->integer('OBJECTID_1')->nullable();
            $table->integer('OBJECTID_2')->nullable();
            $table->integer('OBJECTID')->nullable();
            $table->string('Kl_Dat_Das')->nullable();
            $table->string('Nm_Ruas')->nullable();
            $table->integer('Thn_Data')->nullable();
            $table->string('Status')->nullable();
            $table->string('Fungsi')->nullable();
            $table->string('Mendukung')->nullable();
            $table->string('Ura_Dukung')->nullable();
            $table->string('Kd_Bd_PU')->nullable();
            $table->string('Kd_Jns_inf')->nullable();
            $table->string('Kd_Inf')->nullable();
            $table->string('Propinsi')->nullable();
            $table->string('Kab_Kot')->nullable();
            $table->string('Kecamatan')->nullable();
            $table->string('Desa_Kel')->nullable();
            $table->string('Tk_Ruas_Aw')->nullable();
            $table->string('Tk_Ruas_Ak')->nullable();
            $table->string('Kd_Patok')->nullable();
            $table->string('Nm_Lintas')->nullable();
            $table->decimal('Km_Awal', 10, 8)->nullable();
            $table->decimal('Km_Akhir', 10, 8)->nullable();
            $table->decimal('Kon_Baik', 10, 8)->nullable();
            $table->decimal('Kon_Sdg', 10, 8)->nullable();
            $table->decimal('Kon_Rgn', 10, 8)->nullable();
            $table->decimal('Kon_Rusak', 10, 8)->nullable();
            $table->decimal('Kon_Mntp', 10, 8)->nullable();
            $table->decimal('Kon_T_Mntp', 10, 8)->nullable();
            $table->decimal('Panjang', 15, 8)->nullable();
            $table->decimal('Lbr_Keras', 8, 2)->nullable();
            $table->decimal('LHRT', 8, 2)->nullable();
            $table->decimal('VCR', 8, 2)->nullable();
            $table->integer('Tipe_Jln')->nullable();
            $table->decimal('MST', 8, 2)->nullable();
            $table->string('Tipe_Keras')->nullable();
            $table->decimal('Tanah_Kri', 8, 2)->nullable();
            $table->decimal('Macadam', 8, 2)->nullable();
            $table->decimal('Aspal', 8, 2)->nullable();
            $table->decimal('Rigid', 8, 2)->nullable();
            $table->integer('Thn_Pen_Ak')->nullable();
            $table->string('Jns_Pen')->nullable();
            $table->decimal('pnj', 15, 8)->nullable();
            $table->integer('Id_Paser')->nullable();  // Renamed to avoid conflict with existing 'Id' column
            $table->decimal('Shape_Leng', 15, 8)->nullable();
            $table->string('X_Ak')->nullable();
            $table->string('Y_Ak')->nullable();
            $table->string('X_Aw')->nullable();
            $table->string('Y_Aw')->nullable();
            $table->string('No')->nullable();
            $table->longText('geom')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_ln_jalan_paser');
    }
};