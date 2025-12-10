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
        Schema::create('table__l_n__sutm__berau', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('objectid')->nullable()->index();
            $table->string('globalid')->nullable();
            $table->unsignedBigInteger('assetgroup')->nullable();
            $table->unsignedBigInteger('assettype')->nullable();
            $table->string('tglgambar')->nullable();
            $table->string('usergambar')->nullable();
            $table->string('tglupdate')->nullable();
            $table->string('userupdate')->nullable();
            $table->string('assetnum')->nullable();
            $table->string('classifica')->nullable();
            $table->text('descriptio')->nullable();
            $table->string('installdat')->nullable();
            $table->string('location')->nullable();
            $table->string('manufactur')->nullable();
            $table->string('prioritas')->nullable();
            $table->string('vendor1')->nullable();
            $table->string('bahan_kawa')->nullable();
            $table->string('fasa_jarin')->nullable();
            $table->string('hantaran_n')->nullable();
            $table->string('jenis_kabe')->nullable();
            $table->string('jenis_kond')->nullable();
            $table->string('kode_peral')->nullable();
            $table->string('mainline')->nullable();
            $table->double('panjang_ha')->nullable();
            $table->string('posisi_fas')->nullable();
            $table->string('sirkuit')->nullable();
            $table->string('status_kep')->nullable();
            $table->string('tegangan_j')->nullable();
            $table->string('tingkat_is')->nullable();
            $table->string('ukuran_kaw')->nullable();
            $table->string('status')->nullable();
            $table->string('tujdnumber')->nullable();
            $table->string('serialnum')->nullable();
            $table->integer('enabled')->nullable();
            $table->string('globalid_1')->nullable();
            $table->string('created_us')->nullable();
            $table->string('created_da')->nullable();
            $table->string('last_edite')->nullable();
            $table->string('last_edi_1')->nullable();
            $table->string('penyulang')->nullable();
            $table->string('relationsh')->nullable();
            $table->string('lrm')->nullable();
            $table->string('kode_hanta')->nullable();
            $table->string('operatingd')->nullable();
            $table->string('owner_peme')->nullable();
            $table->string('ownersysid')->nullable();
            $table->double('startmeasu')->nullable();
            $table->double('endmeasure')->nullable();
            $table->double('shape_leng')->nullable();

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
        Schema::dropIfExists('table__l_n__sutm__berau');
    }
};
