<?php
// database/migrations/2024_xx_xx_update_gardus_table_add_wilayah_relation.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gardus', function (Blueprint $table) {
            // Hapus kolom wilayah lama jika ada
            if (Schema::hasColumn('gardus', 'province_id')) {
                $table->dropColumn(['province_id', 'regency_id', 'district_id', 'village_id']);
            }
            
            // Tambah kolom wilayah_id sebagai relasi ke tabel wilayah
            $table->unsignedBigInteger('wilayah_id')->nullable()->after('jenis_gardu_distribusi');
            $table->index('wilayah_id');
            
            // Optional: Tambahkan foreign key jika ingin enforce constraint
            // $table->foreign('wilayah_id')->references('id')->on('wilayah')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('gardus', function (Blueprint $table) {
            $table->dropColumn('wilayah_id');
            
            // Kembalikan kolom lama
            $table->string('province_id', 20)->nullable();
            $table->string('regency_id', 20)->nullable();
            $table->string('district_id', 20)->nullable();
            $table->string('village_id', 20)->nullable();
        });
    }
};