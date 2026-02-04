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
        Schema::table('perizinans', function (Blueprint $table) {
            $table->string('nama_perusahaan')->nullable()->after('perusahaan_id');
            $table->unsignedBigInteger('perusahaan_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perizinans', function (Blueprint $table) {
            $table->dropColumn('nama_perusahaan');
            $table->unsignedBigInteger('perusahaan_id')->nullable(false)->change();
        });
    }
};
