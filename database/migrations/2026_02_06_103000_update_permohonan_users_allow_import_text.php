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
        Schema::table('permohonan_users', function (Blueprint $table) {
            // Make foreign keys nullable
            $table->foreignId('user_id')->nullable()->change();
            $table->foreignId('permohonan_id')->nullable()->change();

            // Add new columns for imported text data
            $table->string('nama_pemohon_import')->nullable()->after('user_id');
            $table->string('jenis_permohonan_import')->nullable()->after('permohonan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_users', function (Blueprint $table) {
            // Revert changes (Note: This might fail if there are nulls, but for rollback logic strictly:)
            // We cannot easily make them non-nullable again without data cleanup, 
            // but we can drop the new columns.
            $table->dropColumn('nama_pemohon_import');
            $table->dropColumn('jenis_permohonan_import');
            
            // Reverting nullable to non-nullable is risky in down() without data handling,
            // so we typically just leave them nullable or require manual intervention.
            // For this project scope, skipping the nullable revert is safer.
        });
    }
};
