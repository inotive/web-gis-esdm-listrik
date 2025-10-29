<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wilayah', function (Blueprint $table) {
            $table->bigIncrements('id');

            // relasi sebagai string + index saja (tanpa FK)
            $table->string('regency_id', 20)->index();
            $table->string('district_id', 20)->index();
            $table->string('village_id', 20)->unique(); // 1 desa = 1 record (opsional unique)

            // koordinat opsional + polygon (GeoJSON string)
            $table->decimal('lat', 12, 8)->nullable();
            $table->decimal('lng', 12, 8)->nullable();
            $table->longText('polygon_geojson')->nullable();

            $table->timestamps();

            // TIDAK ADA foreign key di sini
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah');
    }
};
