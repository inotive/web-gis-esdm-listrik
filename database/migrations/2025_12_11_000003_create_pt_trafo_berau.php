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
        Schema::create('pt_trafo_berau', function (Blueprint $table) {
            $table->id();

            // OBJECTID dari GeoJSON
            $table->unsignedBigInteger('objectid')->nullable()->index();

            // Properti dari GeoJSON
            $table->string('globalid')->nullable();           // "globalid": "26548494"
            $table->integer('assetgroup')->nullable();        // "assetgroup": 7213
            $table->integer('assettype')->nullable();         // "assettype": 23300
            $table->timestamp('tglgambar')->nullable();       // "tglgambar": "1899-11-30T00:00:00.000Z"
            $table->string('usergambar')->nullable();         // "usergambar": ""
            $table->timestamp('tglupdate')->nullable();       // "tglupdate": "1899-11-30T00:00:00.000Z"
            $table->string('userupdate')->nullable();         // "userupdate": ""
            $table->string('assetnum')->nullable();           // "assetnum": "107450"
            $table->string('classifica')->nullable();         // "classifica": "Trafo"
            $table->string('descriptio')->nullable();         // "descriptio": "TRAFO GD MRT0001"
            $table->timestamp('installdat')->nullable();      // "installdat": "2019-05-17T00:00:00.000Z"
            $table->string('location')->nullable();           // "location": "GD01.21756122"
            $table->string('manufactur')->nullable();         // "manufactur": "400030626"
            $table->string('serialnum')->nullable();          // "serialnum": "180340064-P"
            $table->string('status')->nullable();             // "status": "OPERATING"
            $table->string('tujdnumber')->nullable();         // "tujdnumber": "72TF0108720422"
            $table->string('vendor1')->nullable();            // "vendor1": "400016982"
            $table->string('fasa_trafo')->nullable();         // "fasa_trafo": "3 FASA"
            $table->string('jenis_traf')->nullable();         // "jenis_traf": "Outdoor"
            $table->integer('kapasitas')->nullable();         // "kapasitas": 100
            $table->string('kode_peral')->nullable();         // "kode_peral": ""
            $table->string('no_trafo')->nullable();           // "no_trafo": ""
            $table->string('owner_peme')->nullable();         // "owner_peme": ""
            $table->string('peruntukan')->nullable();         // "peruntukan": "UMUM"
            $table->string('posisi_fas')->nullable();         // "posisi_fas": "RST"
            $table->string('rujukan_ko')->nullable();         // "rujukan_ko": ""
            $table->string('status_kep')->nullable();         // "status_kep": "PLN"
            $table->string('tap_change')->nullable();         // "tap_change": "Tujuh"
            $table->string('tegangan_t')->nullable();         // "tegangan_t": "20 kV/B2"
            $table->string('th_buat')->nullable();            // "th_buat": "2018"
            $table->string('prioritas')->nullable();          // "prioritas": ""
            $table->boolean('enabled')->nullable();           // "enabled": 1
            $table->string('globalid_1')->nullable();         // "globalid_1": "{D4444B93-4DA2-4537-AF50-DE17E1F41584}"
            $table->string('created_us')->nullable();         // "created_us": "ADMIN"
            $table->timestamp('created_da')->nullable();      // "created_da": "2024-08-24T00:00:00.000Z"
            $table->string('last_edite')->nullable();         // "last_edite": "creator.uiw_kaltimra"
            $table->timestamp('last_edi_1')->nullable();      // "last_edi_1": "2025-09-23T00:00:00.000Z"
            $table->string('relationsh')->nullable();         // "relationsh": "SUPPLIEDBY"
            $table->string('kode_hanta')->nullable();         // "kode_hanta": "SUTM SEGMENT MRT01-05-R18_MRT01-05-R54"
            $table->timestamp('operatingd')->nullable();      // "operatingd": "1899-11-30T00:00:00.000Z"
            $table->string('ownersysid')->nullable();         // "ownersysid": ""
            $table->integer('sourcestar')->nullable();        // "sourcestar": 0
            $table->integer('sourceendm')->nullable();        // "sourceendm": 0
            $table->string('no_slo')->nullable();             // "no_slo": ""
            $table->timestamp('sloactived')->nullable();      // "sloactived": "1899-11-30T00:00:00.000Z"
            $table->string('penyulang')->nullable();          // "penyulang": "117660"

            // Geometry (Point)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pt_trafo_berau');
    }
};