<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pt_trafos', function (Blueprint $table) {
            $table->id();

            // Wilayah (optional, bisa diisi saat import)
            $table->string('reg_provinces_id')->nullable()->index();
            $table->string('reg_regencies_id')->nullable()->index();
            $table->string('reg_districts_id')->nullable()->index();
            $table->string('reg_villages_id')->nullable()->index();

            // Primary key dari data sumber
            $table->string('globalid')->unique();

            // Field dari GeoJSON PT_Trafo_Berau
            $table->integer('objectid')->nullable()->index();
            $table->integer('assetgroup')->nullable();
            $table->integer('assettype')->nullable();

            $table->dateTime('tglgambar')->nullable();
            $table->string('usergambar')->nullable();
            $table->dateTime('tglupdate')->nullable();
            $table->string('userupdate')->nullable();

            $table->string('assetnum')->nullable();
            $table->string('classifica')->nullable();
            $table->string('descriptio')->nullable(); // Nama/uraian trafo
            $table->dateTime('installdat')->nullable();
            $table->string('location')->nullable(); // Kode lokasi

            $table->string('manufactur')->nullable();
            $table->string('serialnum')->nullable();
            $table->string('status')->nullable();
            $table->string('tujdnumber')->nullable();
            $table->string('vendor1')->nullable();

            $table->string('fasa_trafo')->nullable();
            $table->string('jenis_traf')->nullable();
            $table->double('kapasitas')->nullable();

            $table->string('kode_peral')->nullable();
            $table->string('no_trafo')->nullable();
            $table->string('owner_peme')->nullable();
            $table->string('peruntukan')->nullable();
            $table->string('posisi_fas')->nullable();
            $table->string('rujukan_ko')->nullable();
            $table->string('status_kep')->nullable();
            $table->string('tap_change')->nullable();
            $table->string('tegangan_t')->nullable();
            $table->string('th_buat')->nullable();
            $table->string('prioritas')->nullable();

            $table->boolean('enabled')->nullable();

            $table->string('globalid_1')->nullable();
            $table->string('created_us')->nullable();
            $table->dateTime('created_da')->nullable();
            $table->string('last_edite')->nullable();
            $table->dateTime('last_edi_1')->nullable();

            $table->string('relationsh')->nullable();
            $table->string('kode_hanta')->nullable();
            $table->dateTime('operatingd')->nullable();
            $table->string('ownersysid')->nullable();
            $table->double('sourcestar')->nullable();
            $table->double('sourceendm')->nullable();

            $table->string('no_slo')->nullable();
            $table->dateTime('sloactived')->nullable();
            $table->string('penyulang')->nullable();

            // Opsional tambahan (kalau nanti mau isi)
            $table->string('streetaddr')->nullable();
            $table->string('city')->nullable()->index();

            // Koordinat & geometry
            $table->double('longitudex')->nullable();
            $table->double('latitudey')->nullable();
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pt_trafos');
    }
};
