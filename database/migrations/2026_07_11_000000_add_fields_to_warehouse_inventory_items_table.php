<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouse_inventory_items', function (Blueprint $table) {
            if (!Schema::hasColumn('warehouse_inventory_items', 'customer_id')) {
                $table->foreignId('customer_id')->nullable()->after('warehouse_id')->constrained('trade_partners');
            }
            if (!Schema::hasColumn('warehouse_inventory_items', 'vendor_id')) {
                $table->foreignId('vendor_id')->nullable()->after('customer_id')->constrained('trade_partners');
            }
            if (!Schema::hasColumn('warehouse_inventory_items', 'upc_ean')) {
                $table->string('upc_ean', 100)->nullable()->after('sku');
            }
            if (!Schema::hasColumn('warehouse_inventory_items', 'mpn')) {
                $table->string('mpn', 100)->nullable()->after('upc_ean');
            }
            if (!Schema::hasColumn('warehouse_inventory_items', 'hts_code')) {
                $table->string('hts_code', 50)->nullable()->after('mpn');
            }
            if (!Schema::hasColumn('warehouse_inventory_items', 'inner_pack')) {
                $table->decimal('inner_pack', 15, 2)->default(0)->after('description');
            }
            if (!Schema::hasColumn('warehouse_inventory_items', 'dimension_length')) {
                $table->decimal('dimension_length', 10, 2)->nullable()->after('volume_cbm');
            }
            if (!Schema::hasColumn('warehouse_inventory_items', 'dimension_width')) {
                $table->decimal('dimension_width', 10, 2)->nullable()->after('dimension_length');
            }
            if (!Schema::hasColumn('warehouse_inventory_items', 'dimension_height')) {
                $table->decimal('dimension_height', 10, 2)->nullable()->after('dimension_width');
            }
            if (!Schema::hasColumn('warehouse_inventory_items', 'dimension_unit')) {
                $table->string('dimension_unit', 10)->default('cm')->after('dimension_height');
            }
            if (!Schema::hasColumn('warehouse_inventory_items', 'remark')) {
                $table->text('remark')->nullable()->after('color');
            }
            if (!Schema::hasColumn('warehouse_inventory_items', 'create_date')) {
                $table->date('create_date')->nullable()->after('remark');
            }
            if (!Schema::hasColumn('warehouse_inventory_items', 'status')) {
                $table->string('status', 20)->default('enable')->after('create_date');
            }
            if (!Schema::hasColumn('warehouse_inventory_items', 'product_photo')) {
                $table->string('product_photo', 255)->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('warehouse_inventory_items', function (Blueprint $table) {
            $columns = [
                'customer_id', 'vendor_id', 'upc_ean', 'mpn', 'hts_code',
                'inner_pack', 'dimension_length', 'dimension_width', 'dimension_height',
                'dimension_unit', 'remark', 'create_date', 'status', 'product_photo',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('warehouse_inventory_items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
