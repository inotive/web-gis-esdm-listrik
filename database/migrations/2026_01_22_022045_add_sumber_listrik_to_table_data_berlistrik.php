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
        Schema::table('table_data_berlistrik', function (Blueprint $table) {
            $table->enum('sumber_listrik', ['PLN', 'Non-PLN'])
                ->nullable()
                ->after('H_Survei')
                ->comment('Sumber listrik: PLN atau Non-PLN (genset, solar, dll)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_data_berlistrik', function (Blueprint $table) {
            $table->dropColumn('sumber_listrik');
        });
    }
};
