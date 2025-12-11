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
        Schema::create('table__p_t__sistem__infrastruktur__energi__balikpapan', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('objectid')->nullable()->index('pt_sistem_energi_bpn_objectid_idx');
            $table->string('namobj')->nullable();
            $table->integer('orde01')->nullable();
            $table->integer('orde02')->nullable();
            $table->integer('orde03')->nullable();
            $table->integer('orde04')->nullable();
            $table->integer('jnsrsr')->nullable();
            $table->integer('stsjrn')->nullable();
            $table->string('wadmpr')->nullable();
            $table->string('wadmkk')->nullable();
            $table->text('remark')->nullable();
            $table->string('sbdata')->nullable();

            // Geometry (likely Point/Polygon)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__p_t__sistem__infrastruktur__energi__balikpapan');
    }
};
