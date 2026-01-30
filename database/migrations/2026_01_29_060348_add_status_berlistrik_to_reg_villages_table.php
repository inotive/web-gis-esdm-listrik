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
        Schema::table('reg_villages', function (Blueprint $table) {
            $table->string('status_berlistrik')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reg_villages', function (Blueprint $table) {
            $table->dropColumn('status_berlistrik');
        });
    }
};
