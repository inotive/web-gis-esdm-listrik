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
        // Add perusahaan_id to infrastruktur_jaringan
        Schema::table('infrastruktur_jaringan', function (Blueprint $table) {
            $table->unsignedBigInteger('perusahaan_id')->nullable()->after('id');
            $table->foreign('perusahaan_id')
                ->references('id')
                ->on('perusahaans')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->index('perusahaan_id');
        });

        // Add perusahaan_id to gardus
        Schema::table('gardus', function (Blueprint $table) {
            $table->unsignedBigInteger('perusahaan_id')->nullable()->after('id');
            $table->foreign('perusahaan_id')
                ->references('id')
                ->on('perusahaans')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->index('perusahaan_id');
        });

        // Add perusahaan_id to pembangkit_lokals
        Schema::table('pembangkit_lokals', function (Blueprint $table) {
            $table->unsignedBigInteger('perusahaan_id')->nullable()->after('id');
            $table->foreign('perusahaan_id')
                ->references('id')
                ->on('perusahaans')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->index('perusahaan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove perusahaan_id from infrastruktur_jaringan
        Schema::table('infrastruktur_jaringan', function (Blueprint $table) {
            $table->dropForeign(['perusahaan_id']);
            $table->dropIndex(['perusahaan_id']);
            $table->dropColumn('perusahaan_id');
        });

        // Remove perusahaan_id from gardus
        Schema::table('gardus', function (Blueprint $table) {
            $table->dropForeign(['perusahaan_id']);
            $table->dropIndex(['perusahaan_id']);
            $table->dropColumn('perusahaan_id');
        });

        // Remove perusahaan_id from pembangkit_lokals
        Schema::table('pembangkit_lokals', function (Blueprint $table) {
            $table->dropForeign(['perusahaan_id']);
            $table->dropIndex(['perusahaan_id']);
            $table->dropColumn('perusahaan_id');
        });
    }
};
