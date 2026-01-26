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
        Schema::table('perizinan_listriks', function (Blueprint $table) {
            $table->enum('status_kelistrikan', ['berlistrik_pln', 'berlistrik_non_pln', 'tidak_berlistrik'])
                  ->nullable()
                  ->comment('Status visualisasi: PLN (Hijau), Non-PLN (Kuning), Tidak Ada (Merah)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perizinan_listriks', function (Blueprint $table) {
            $table->dropColumn('status_kelistrikan');
        });
    }
};
