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
        Schema::create('json_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imported_json_features_id')->references('id')->on('imported_json_features')->onDelete('cascade');
            $table->string('kodifikasi');
            $table->string('link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('json_videos');
    }
};
