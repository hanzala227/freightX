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
        // 1. Add new columns to ocean_import_hbls
        Schema::table('ocean_import_hbls', function (Blueprint $table) {
            $table->text('po_no')->nullable();
            $table->string('po_mapping_type')->default('container');
            $table->text('hbl_mark')->nullable();
            $table->text('hbl_description')->nullable();
            $table->text('arrival_notice_remark')->nullable();
            $table->text('delivery_order_remark')->nullable();
        });

        // 2. Add columns to pivot table ocean_import_container_hbl
        Schema::table('ocean_import_container_hbl', function (Blueprint $table) {
            $table->integer('pkg_qty')->nullable();
            $table->string('pkg_unit')->nullable();
            $table->decimal('weight_kg', 12, 2)->nullable();
            $table->string('weight_unit')->nullable();
            $table->decimal('measure_cbm', 12, 2)->nullable();
            $table->string('measure_unit')->nullable();
            $table->string('po_no')->nullable();
        });

        // 3. Create HBL commodities table
        Schema::create('ocean_import_hbl_commodities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hbl_id')->constrained('ocean_import_hbls')->onDelete('cascade');
            $table->string('commodity_desc')->nullable();
            $table->string('hts_code')->nullable();
            $table->string('container_no')->nullable();
            $table->string('po_no')->nullable();
            $table->timestamps();
        });

        // 4. Create HBL warehouse receipts table
        Schema::create('ocean_import_hbl_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hbl_id')->constrained('ocean_import_hbls')->onDelete('cascade');
            $table->string('receipt_no')->nullable();
            $table->string('vin_no')->nullable();
            $table->integer('total_pcs')->nullable();
            $table->integer('available_pcs')->nullable();
            $table->integer('allocated_pcs')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('actual_weight', 12, 2)->nullable();
            $table->decimal('measurement', 12, 2)->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocean_import_hbl_receipts');
        Schema::dropIfExists('ocean_import_hbl_commodities');

        Schema::table('ocean_import_container_hbl', function (Blueprint $table) {
            $table->dropColumn([
                'pkg_qty', 'pkg_unit', 'weight_kg', 'weight_unit', 'measure_cbm', 'measure_unit', 'po_no'
            ]);
        });

        Schema::table('ocean_import_hbls', function (Blueprint $table) {
            $table->dropColumn([
                'po_no', 'po_mapping_type', 'hbl_mark', 'hbl_description', 'arrival_notice_remark', 'delivery_order_remark'
            ]);
        });
    }
};
