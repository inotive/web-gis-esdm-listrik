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
        Schema::create('pt_trafo_gardu_distribusi_ppu', function (Blueprint $table) {
            $table->id();

            // Properties dari GeoJSON
            $table->integer('oid_')->nullable();           // "OID_": 0
            $table->string('name')->nullable();            // "Name": "0092"
            $table->string('folderpath')->nullable();      // "FolderPath": "Document/Waypoints"
            $table->integer('symbolid')->nullable();       // "SymbolID": 0
            $table->integer('altmode')->nullable();        // "AltMode": -1
            $table->double('base')->nullable();            // "Base": 0
            $table->integer('timespan')->nullable();       // "TimeSpan": 0
            $table->integer('timestamp')->nullable();      // "TimeStamp": 0
            $table->string('begintime')->nullable();       // "BeginTime": ""
            $table->string('endtime')->nullable();         // "EndTime": ""
            $table->text('snippet')->nullable();           // "Snippet": ""
            $table->text('popupinfo')->nullable();         // "PopupInfo": ""
            $table->integer('haslabel')->nullable();       // "HasLabel": -1
            $table->integer('labelid')->nullable();        // "LabelID": 0
            $table->string('nama')->nullable();            // "Nama": ""

            // Geometry (Point for waypoints)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pt_trafo_gardu_distribusi_ppu');
    }
};