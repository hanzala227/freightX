<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('truck_shipments', function (Blueprint $table) {
            if (!Schema::hasColumn('truck_shipments', 'hbl_no')) {
                $table->string('hbl_no')->nullable()->after('mbl_no');
            }
            if (!Schema::hasColumn('truck_shipments', 'vessel_flight_no')) {
                $table->string('vessel_flight_no')->nullable()->after('hbl_no');
            }
            if (!Schema::hasColumn('truck_shipments', 'carrier_bkg_no')) {
                $table->string('carrier_bkg_no')->nullable()->after('vessel_flight_no');
            }
            if (!Schema::hasColumn('truck_shipments', 'customer_ref_no')) {
                $table->string('customer_ref_no')->nullable()->after('consignee_id');
            }
            if (!Schema::hasColumn('truck_shipments', 'sales_id')) {
                $table->foreignId('sales_id')->nullable()->after('consignee_id')->constrained('users');
            }
            if (!Schema::hasColumn('truck_shipments', 'ship_type')) {
                $table->string('ship_type')->default('Trucking')->after('sales_id');
            }
            if (!Schema::hasColumn('truck_shipments', 'final_destination_id')) {
                $table->foreignId('final_destination_id')->nullable()->after('pod_id')->constrained('ports');
            }
            if (!Schema::hasColumn('truck_shipments', 'feta')) {
                $table->date('feta')->nullable()->after('final_destination_id');
            }
            if (!Schema::hasColumn('truck_shipments', 'empty_pickup_location_id')) {
                $table->foreignId('empty_pickup_location_id')->nullable()->after('feta')->constrained('trade_partners');
            }
            if (!Schema::hasColumn('truck_shipments', 'freight_pickup_location_id')) {
                $table->foreignId('freight_pickup_location_id')->nullable()->after('empty_pickup_location_id')->constrained('trade_partners');
            }
            if (!Schema::hasColumn('truck_shipments', 'delivery_to_location_id')) {
                $table->foreignId('delivery_to_location_id')->nullable()->after('freight_pickup_location_id')->constrained('trade_partners');
            }
            if (!Schema::hasColumn('truck_shipments', 'empty_return_location_id')) {
                $table->foreignId('empty_return_location_id')->nullable()->after('delivery_to_location_id')->constrained('trade_partners');
            }
            if (!Schema::hasColumn('truck_shipments', 'measure_cft')) {
                $table->decimal('measure_cft', 15, 3)->default(0)->after('volume_cbm');
            }
            if (!Schema::hasColumn('truck_shipments', 'est_delivery_date')) {
                $table->date('est_delivery_date')->nullable()->after('measure_cft');
            }
            if (!Schema::hasColumn('truck_shipments', 'is_delivered')) {
                $table->boolean('is_delivered')->default(false)->after('est_delivery_date');
            }
            if (!Schema::hasColumn('truck_shipments', 'delivered_date')) {
                $table->date('delivered_date')->nullable()->after('is_delivered');
            }
            if (!Schema::hasColumn('truck_shipments', 'is_ecommerce')) {
                $table->boolean('is_ecommerce')->default(false)->after('delivered_date');
            }
            if (!Schema::hasColumn('truck_shipments', 'quotation_id')) {
                $table->foreignId('quotation_id')->nullable()->after('is_ecommerce')->constrained('quotations');
            }
        });
    }

    public function down(): void
    {
        Schema::table('truck_shipments', function (Blueprint $table) {
            $columns = [
                'hbl_no', 'vessel_flight_no', 'carrier_bkg_no', 'customer_ref_no',
                'ship_type', 'feta', 'measure_cft', 'est_delivery_date',
                'is_delivered', 'delivered_date', 'is_ecommerce'
            ];

            $foreignKeys = [
                'sales_id', 'final_destination_id', 'empty_pickup_location_id',
                'freight_pickup_location_id', 'delivery_to_location_id',
                'empty_return_location_id', 'quotation_id'
            ];

            foreach ($foreignKeys as $fk) {
                if (Schema::hasColumn('truck_shipments', $fk)) {
                    $table->dropForeign([$fk]);
                }
            }

            foreach ($columns as $col) {
                if (Schema::hasColumn('truck_shipments', $col)) {
                    $table->dropColumn($col);
                }
            }

            foreach ($foreignKeys as $fk) {
                if (Schema::hasColumn('truck_shipments', $fk)) {
                    $table->dropColumn($fk);
                }
            }
        });
    }
};
