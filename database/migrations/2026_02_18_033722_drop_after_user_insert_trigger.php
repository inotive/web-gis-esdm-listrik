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
        DB::unprepared('DROP TRIGGER IF EXISTS after_user_insert');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('
            CREATE TRIGGER after_user_insert AFTER INSERT ON users
            FOR EACH ROW
            BEGIN
                IF NEW.identity_type = "perusahaan" THEN
                    INSERT INTO perusahaans (nama, alamat, village_id, created_at, updated_at)
                    VALUES (NEW.company_name, NEW.address, NEW.village_id, NOW(), NOW());
                ELSEIF NEW.identity_type = "desa" THEN
                    UPDATE reg_villages
                    SET status_berlistrik = "strip"
                    WHERE id = NEW.village_id;
                END IF;
            END
        ');
    }
};
