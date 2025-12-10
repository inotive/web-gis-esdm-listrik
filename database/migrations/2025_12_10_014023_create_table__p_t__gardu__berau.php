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
        Schema::create('table__p_t__gardu__berau', function (Blueprint $table) {
            $table->id();

            $table->string('globalid')->nullable()->index();
            $table->integer('assetgroup')->nullable();
            $table->integer('assettype')->nullable();
            $table->dateTime('tglgambar')->nullable();
            $table->string('usergambar')->nullable();
            $table->dateTime('tglupdate')->nullable();
            $table->string('userupdate')->nullable();
            $table->string('assetnum')->nullable();
            $table->string('classifica')->nullable();
            $table->string('descriptio')->nullable();
            $table->dateTime('installdat')->nullable();
            $table->string('location')->nullable();
            $table->string('prioritas')->nullable();
            $table->string('vendor1')->nullable();
            $table->string('jenis_pela')->nullable();
            $table->string('kode_peral')->nullable();
            $table->string('status_kep')->nullable();
            $table->string('status_rc')->nullable();
            $table->string('type_gardu')->nullable();
            $table->string('status')->nullable();
            $table->string('tujdnumber')->nullable();
            $table->string('globalid_1')->nullable();
            $table->string('created_us')->nullable();
            $table->dateTime('created_da')->nullable();
            $table->string('last_edite')->nullable();
            $table->dateTime('last_edi_1')->nullable();
            $table->string('parent_loc')->nullable();
            $table->dateTime('operatingd')->nullable();
            $table->string('formatteda')->nullable();
            $table->string('streetaddr')->nullable();
            $table->string('city')->nullable();
            $table->string('kode_konst')->nullable();
            $table->string('owner_peme')->nullable();
            $table->string('ownersysid')->nullable();
            $table->string('penyulang')->nullable();
            $table->string('no_slo')->nullable();
            $table->dateTime('sloactived')->nullable();
            $table->double('longitudex')->nullable();
            $table->double('latitudey')->nullable();
            $table->double('shape_leng')->nullable();
            $table->double('shape_area')->nullable();
            $table->integer('orig_fid')->nullable();

            // Geometry (Point / Polygon)
            $table->json('geometry')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table__p_t__gardu__berau');
    }
};
