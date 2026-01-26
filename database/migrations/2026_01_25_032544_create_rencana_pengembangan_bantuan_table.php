<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rencana_pengembangan_bantuan', function (Blueprint $table) {
            $table->id();

            // Region references - using char types to match region tables
            // Note: No foreign key constraints because region tables are created by seeder
            // Following the pattern used in other tables (pt_gardu, asset, etc.)
            $table->char('regency_id', 4)->nullable()->index();
            $table->char('district_id', 7)->nullable()->index();
            $table->char('village_id', 10)->nullable()->index();

            // Data fields
            $table->integer('jumlah_calon_pelanggan')->nullable();

            // Aksesibilitas
            $table->string('aksesibilitas')->nullable(); // Dropdown value
            $table->integer('skor_aksesibilitas')->nullable();

            // Radius ke Jaringan Terdekat
            $table->string('radius_jaringan')->nullable(); // Dropdown value
            $table->integer('skor_radius')->nullable();

            // Arah dan Kebijakan Tata Ruang
            $table->string('arah_kebijakan')->nullable(); // Dropdown value
            $table->integer('skor_arah_kebijakan')->nullable();

            // Potensi Kegiatan
            $table->string('potensi_kegiatan')->nullable(); // Dropdown value
            $table->integer('skor_potensi_kegiatan')->nullable();

            // Jumlah Pelanggan
            $table->string('jumlah_pelanggan')->nullable(); // Dropdown value
            $table->integer('skor_jumlah_pelanggan')->nullable();

            // Calculated fields
            $table->integer('total_skor')->nullable();
            $table->string('prioritas')->nullable(); // Prioritas 1 RKTS, Prioritas 1 SJTM, etc.

            $table->timestamps();

            // Foreign key constraints removed - region tables are created by WilayahSeeder
            // Indexes added instead for query performance
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rencana_pengembangan_bantuan');
    }
};
