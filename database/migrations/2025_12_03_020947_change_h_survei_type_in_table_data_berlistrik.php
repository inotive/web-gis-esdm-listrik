<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('table_data_berlistrik', function (Blueprint $table) {
            // butuh doctrine/dbal kalau mau pakai change()
            $table->string('H_Survei', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('table_data_berlistrik', function (Blueprint $table) {
            // kembalikan ke decimal kalau perlu
            $table->decimal('H_Survei', 10, 2)->nullable()->change();
        });
    }
};
