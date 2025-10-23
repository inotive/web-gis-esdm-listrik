<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_has_attribute_asset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('asset')->cascadeOnDelete();
            $table->foreignId('attribute_asset_id')->constrained('attribute_asset')->cascadeOnDelete();
            $table->string('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asset_has_attribute_asset');
    }
};