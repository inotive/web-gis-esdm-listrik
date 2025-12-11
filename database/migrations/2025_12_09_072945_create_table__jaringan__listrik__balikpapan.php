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
        Schema::create('table__jaringan__listrik__balikpapan', function (Blueprint $table) {
            $table->id();

            // OBJECTID dari GeoJSON (boleh unique kalau mau dipakai sebagai key)
            $table->unsignedBigInteger('objectid')->nullable()->index();

            // Nama objek, contoh: "Saluran Udara Tegangan Menengah (SUTM)"
            $table->string('namobj')->nullable();

            // ORDE & kode-kode lain (integer besar)
            $table->bigInteger('orde01')->nullable();
            $table->bigInteger('orde02')->nullable();
            $table->bigInteger('orde03')->nullable();
            $table->bigInteger('orde04')->nullable();
            $table->bigInteger('jnsrsr')->nullable();

            // Status jaringan (misal 1,2,dst)
            $table->integer('stsjrn')->nullable();

            // Wilayah administrasi
            $table->string('wadmpr')->nullable(); // Provinsi
            $table->string('wadmkk')->nullable(); // Kab/Kota

            // Keterangan & sumber data
            $table->text('remark')->nullable();
            $table->string('sbdata')->nullable();

            // Panjang & Shape_Leng (pakai double)
            $table->double('length', 20, 10)->nullable();
            $table->double('shape_leng', 20, 10)->nullable();

            // Geometry disimpan sebagai JSON (LineString / MultiLineString)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__jaringan__listrik__balikpapan');
    }
};
