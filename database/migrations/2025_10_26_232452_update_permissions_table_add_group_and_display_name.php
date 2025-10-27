<?php
// database/migrations/2025_01_27_000001_update_permissions_table_add_group_and_display_name.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('permissions', 'group')) {
                $table->string('group')->nullable()->after('guard_name');
            }
            if (!Schema::hasColumn('permissions', 'display_name')) {
                $table->string('display_name')->nullable()->after('group');
            }
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'group')) {
                $table->dropColumn('group');
            }
            if (Schema::hasColumn('permissions', 'display_name')) {
                $table->dropColumn('display_name');
            }
        });
    }
};