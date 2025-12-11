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
        Schema::create('table__p_t__gardu__induk__kutim', function (Blueprint $table) {
            $table->id();

            $table->string('classifica')->nullable();
            $table->string('globalid')->nullable()->index();
            $table->integer('orig_fid')->nullable();

            // Geometry (Point)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__p_t__gardu__induk__kutim');
    }
};
