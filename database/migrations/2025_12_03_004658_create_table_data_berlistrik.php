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
        Schema::create('table_data_berlistrik', function (Blueprint $table) {
            $table->id();
            $table->string('NAMOBJ')->nullable(); // Nama objek
            $table->decimal('LUASWH', 10, 2)->nullable(); // Luas wilayah hutan
            $table->string('TIPADM')->nullable(); // Tipe administrasi
            $table->string('WADMKC')->nullable(); // Wilayah administrasi kecamatan
            $table->string('WADMKD')->nullable(); // Wilayah administrasi desa
            $table->string('WADMKK')->nullable(); // Wilayah administrasi kelurahan
            $table->string('WADMPR')->nullable(); // Wilayah administrasi provinsi
            $table->decimal('H_Survei', 10, 2)->nullable(); // Hasil survei
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_data_berlistrik');
    }
};
