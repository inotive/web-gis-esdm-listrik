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
        Schema::table('rencana_pengembangan_bantuan', function (Blueprint $table) {
            $table->dropColumn('prioritas');
            $table->enum('rencana_sumber_listrik', ['SUTM', 'PLTS'])->nullable()->after('total_skor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rencana_pengembangan_bantuan', function (Blueprint $table) {
            $table->string('prioritas')->nullable();
            $table->dropColumn('rencana_sumber_listrik');
        });
    }
};
