<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('perusahaans', function (Blueprint $table) {
            $table->char('village_id', 10)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('perusahaans', function (Blueprint $table) {
            $table->char('village_id', 10)->nullable(false)->change();
        });
    }
};
