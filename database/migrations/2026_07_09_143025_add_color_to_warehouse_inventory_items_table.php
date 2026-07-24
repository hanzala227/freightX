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
        Schema::table('warehouse_inventory_items', function (Blueprint $table) {
            if (!Schema::hasColumn('warehouse_inventory_items', 'color')) {
                $table->string('color', 20)->nullable()->after('volume_cbm');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_inventory_items', function (Blueprint $table) {
            if (Schema::hasColumn('warehouse_inventory_items', 'color')) {
                $table->dropColumn('color');
            }
        });
    }
};
