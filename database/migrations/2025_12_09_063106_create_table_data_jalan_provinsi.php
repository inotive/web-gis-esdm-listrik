<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_data_jalan_provinsi', function (Blueprint $table) {
            $table->id();

            $table->integer('objectid')->nullable()->index();
            $table->string('kl_dat_das')->nullable();     // Kl_Dat_Das 
            $table->text('nm_ruas')->nullable();          // Nm_Ruas
            $table->integer('thn_data')->nullable();      // Thn_Data
            $table->string('status')->nullable();         // Status
            $table->string('fungsi')->nullable();         // Fungsi
            $table->string('mendukung')->nullable();      // Mendukung
            $table->text('ura_dukung')->nullable();       // Ura_Dukung
            $table->string('kd_bd_pu')->nullable();       // Kd_Bd_PU
            $table->string('kd_jns_inf')->nullable();     // Kd_Jns_Inf
            $table->string('kd_inf')->nullable();         // Kd_Inf
            $table->string('propinsi')->nullable();       // Propinsi
            $table->string('kab_kot')->nullable();        // Kab_Kot
            $table->string('kecamatan')->nullable();      // Kecamatan
            $table->text('desa_kel')->nullable();         // Desa_Kel
            $table->string('tk_ruas_aw')->nullable();     // Tk_Ruas_Aw
            $table->string('tk_ruas_ak')->nullable();     // Tk_Ruas_Ak
            $table->string('kd_patok')->nullable();       // Kd_Patok

            $table->double('km_awal')->nullable();        // Km_Awal
            $table->double('km_akhir')->nullable();       // Km_Akhir

            $table->string('nm_lintas')->nullable();      // Nm_Lintas

            $table->double('kon_baik')->nullable();       // Kon_Baik
            $table->double('kon_sdg')->nullable();        // Kon_Sdg
            $table->double('kon_rgn')->nullable();        // Kon_Rgn
            $table->double('kon_rusak')->nullable();      // Kon_Rusak
            $table->double('kon_mntp')->nullable();       // Kon_Mntp
            $table->double('kon_t_mntp')->nullable();     // Kon_T_Mntp

            $table->double('panjang')->nullable();        // Panjang
            $table->double('lbr_keras')->nullable();      // Lbr_Keras
            $table->double('lhrt')->nullable();           // LHRT
            $table->double('vcr')->nullable();            // VCR

            $table->integer('tipe_jln')->nullable();      // Tipe_Jln
            $table->integer('mst')->nullable();           // MST

            $table->double('tanah_kri')->nullable();      // Tanah_Kri
            $table->double('macadam')->nullable();        // Macadam
            $table->double('aspal')->nullable();          // Aspal
            $table->double('rigid')->nullable();          // Rigid

            $table->integer('thn_pen_ak')->nullable();    // Thn_Pen_Ak
            $table->string('jns_pen')->nullable();        // Jns_Pen

            $table->double('koord_x_aw')->nullable();     // Koord_X_Aw
            $table->double('koord_y_aw')->nullable();     // Koord_Y_Aw
            $table->double('koord_x_ak')->nullable();     // Koord_X_Ak
            $table->double('koord_y_ak')->nullable();     // Koord_Y_Ak

            $table->double('shape_leng')->nullable();     // Shape_Leng

            $table->string('status_j_1')->nullable();     // Status_J_1
            $table->text('keterangan')->nullable();       // Keterangan
            $table->string('masuk')->nullable();          // Masuk
            $table->double('panjangjal')->nullable();     // panjangjal

            // Simpan geometry GeoJSON apa adanya (LineString / MultiLineString)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_data_jalan_provinsi');
    }
};
