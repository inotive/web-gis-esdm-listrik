<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('perusahaans', function (Blueprint $table) {
            $table->string('kontak')->nullable()->after('alamat');
            $table->string('jenis_usaha')->nullable()->after('kontak');
            $table->string('kabupaten_kota')->nullable()->after('jenis_usaha');
        });
    }

    public function down(): void
    {
        Schema::table('perusahaans', function (Blueprint $table) {
            $table->dropColumn(['kontak', 'jenis_usaha', 'kabupaten_kota']);
        });
    }
};
