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
            $table->dropColumn('data_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_users', function (Blueprint $table) {
            $table->enum('data_source', ['import', 'user_input'])
                  ->default('user_input')
                  ->after('perusahaan_id')
                  ->comment('Sumber data: import dari Excel atau input manual user');
        });
    }
};
