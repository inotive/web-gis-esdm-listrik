<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('t_ln_jalan_samarinda', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('NAME')->nullable();
            $table->string('LAYER')->nullable();
            $table->integer('OBJECTID_1')->nullable();
            $table->integer('OBJECTID')->nullable();
            $table->string('Kl_Dat_Das')->nullable();
            $table->string('Nm_Ruas')->nullable();
            $table->integer('Thn_Data')->nullable();
            $table->string('Status')->nullable();
            $table->string('Fungsi')->nullable();
            $table->string('Mendukung')->nullable();
            $table->string('Ura_Dukung')->nullable();
            $table->string('Kd_Bd_PU')->nullable();
            $table->string('Kd_Jns_Inf')->nullable();
            $table->string('Kd_Inf')->nullable();
            $table->string('Propinsi')->nullable();
            $table->string('Kab_Kot')->nullable();
            $table->string('Kecamatan')->nullable();
            $table->string('Desa_Kel')->nullable();
            $table->string('Tk_Ruas_Aw')->nullable();
            $table->string('Tk_Ruas_Ak')->nullable();
            $table->string('Kd_Patok')->nullable();
            $table->integer('Km_Awal')->nullable();
            $table->integer('Km_Akhir')->nullable();
            $table->string('Nm_Lintas')->nullable();
            $table->decimal('Kon_Baik', 5, 3)->nullable();
            $table->decimal('Kon_Sdg', 5, 3)->nullable();
            $table->decimal('Kon_Rgn', 5, 3)->nullable();
            $table->decimal('Kon_Rusak', 5, 3)->nullable();
            $table->decimal('Kon_Mntp', 5, 3)->nullable();
            $table->decimal('Kon_T_Mntp', 5, 3)->nullable();
            $table->decimal('Panjang', 10, 3)->nullable();
            $table->decimal('Lbr_Keras', 8, 1)->nullable();
            $table->decimal('LHRT', 8, 1)->nullable();
            $table->decimal('VCR', 8, 1)->nullable();
            $table->decimal('Tipe_Jln', 8, 1)->nullable();
            $table->decimal('MST', 8, 1)->nullable();
            $table->decimal('Tanah_Kri', 8, 1)->nullable();
            $table->decimal('Macadam', 8, 1)->nullable();
            $table->decimal('Aspal', 8, 1)->nullable();
            $table->decimal('Rigid', 8, 1)->nullable();
            $table->integer('Thn_Pen_Ak')->nullable();
            $table->string('Jns_Pen')->nullable();
            $table->decimal('Koord_X_Aw', 15, 9)->nullable();
            $table->decimal('Koord_Y_Aw', 15, 9)->nullable();
            $table->decimal('Koord_X_Ak', 15, 9)->nullable();
            $table->decimal('Koord_Y_Ak', 15, 9)->nullable();
            $table->decimal('Shape_Leng', 15, 10)->nullable();
            $table->decimal('Shape_Le_1', 15, 10)->nullable();
            $table->string('LENGTH')->nullable();
            $table->string('LENGTH_3D')->nullable();
            $table->string('BEARING')->nullable();
            $table->string('LINE_STYLE')->nullable();
            $table->string('LINE_COLOR')->nullable();
            $table->decimal('LINE_WIDTH', 5, 2)->nullable();
            $table->decimal('FONT_SIZE', 5, 2)->nullable();
            $table->string('FONT_COLOR')->nullable();
            $table->integer('FONT_CHARS')->nullable();
            $table->integer('FONT_WEIGH')->nullable();
            $table->string('ELEVATION')->nullable();
            $table->string('MAP_NAME')->nullable();
            $table->string('GM_LAYER')->nullable();
            $table->string('GM_TYPE')->nullable();
            $table->integer('version')->nullable();
            $table->string('highway')->nullable();
            $table->string('osm_id')->nullable();
            $table->string('oneway')->nullable();
            $table->string('boat')->nullable();
            $table->string('smoothness')->nullable();
            $table->string('START_TIME')->nullable();
            $table->string('END_TIME')->nullable();
            $table->string('Kord_X_Awa')->nullable();
            $table->string('Kord_X_Akh')->nullable();
            $table->string('Kord_Y_Awa')->nullable();
            $table->string('Kord_Y_Akh')->nullable();
            $table->string('Kord_Y_a_1')->nullable();
            $table->longText('geom')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_ln_jalan_samarinda');
    }
};