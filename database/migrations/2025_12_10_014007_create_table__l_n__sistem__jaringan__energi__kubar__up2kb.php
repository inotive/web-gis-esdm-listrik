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
        Schema::create('table__l_n__sistem__jaringan__energi__kubar__up2kb', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('objectid')->nullable();
            $table->text('descriptio')->nullable();
            $table->double('shape_leng')->nullable();

            // Geometry (LineString / MultiLineString)
            $table->json('geometry')->nullable();

            $table->timestamps();

            // Custom index name to avoid "Identifier name too long" error
            $table->index('objectid', 'idx_kubar_up2kb_objectid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__l_n__sistem__jaringan__energi__kubar__up2kb');
    }
};
