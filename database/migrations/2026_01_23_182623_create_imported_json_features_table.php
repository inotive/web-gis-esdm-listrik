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
        Schema::create('imported_json_features', function (Blueprint $table) {
            $table->id();

            $table->string('kategori')->index();
            $table->string('sub_kategori')->nullable()->index();
            $table->char('regency_id', 4)->nullable()->index(); // Foreign key to reg_regencies.id

            $table->json('properties')->nullable();
            $table->json('geometry')->nullable();
            $table->timestamps();

            // Optional: Add foreign key constraint if reg_regencies table exists and is populated
            // $table->foreign('regency_id')->references('id')->on('reg_regencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imported_json_features');
    }
};
