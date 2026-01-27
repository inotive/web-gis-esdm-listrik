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
        Schema::table('permohonan_users', function (Blueprint $table) {
            // Add perusahaan_id to permohonan_users for linking applications to companies
            if (!Schema::hasColumn('permohonan_users', 'perusahaan_id')) {
                $table->unsignedBigInteger('perusahaan_id')->nullable()->after('user_id');
                $table->foreign('perusahaan_id')
                    ->references('id')
                    ->on('perusahaans')
                    ->onDelete('set null');
                $table->index('perusahaan_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_users', function (Blueprint $table) {
            if (Schema::hasColumn('permohonan_users', 'perusahaan_id')) {
                $table->dropForeign(['perusahaan_id']);
                $table->dropIndex(['perusahaan_id']);
                $table->dropColumn('perusahaan_id');
            }
        });
    }
};
