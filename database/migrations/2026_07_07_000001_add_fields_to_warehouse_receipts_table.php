<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouse_receipts', function (Blueprint $table) {
            // Add missing fields that the UI form expects
            $table->foreignId('office_id')->nullable()->constrained('offices')->after('shipper_id');
            $table->foreignId('operator_id')->nullable()->constrained('users')->after('office_id');
            $table->foreignId('consignee_id')->nullable()->constrained('trade_partners')->after('shipper_id');

            $table->string('cargo_type', 10)->nullable()->default('OTH')->after('carrier_name');
            $table->boolean('is_hazardous')->default(false)->after('cargo_type');
            $table->boolean('is_heat_treated')->default(false)->after('is_hazardous');
            $table->string('commodity', 500)->nullable()->after('is_heat_treated');
            $table->string('po_no', 255)->nullable()->after('commodity');

            $table->string('location_code', 50)->nullable()->after('po_no');
            $table->string('delivered_by', 100)->nullable()->after('location_code');
            $table->string('freight_charge_type', 20)->nullable()->default('Prepaid')->after('delivered_by');
            $table->decimal('freight_amount', 15, 2)->default(0)->after('freight_charge_type');
            $table->string('check_no', 100)->nullable()->after('freight_amount');
        });
    }

    public function down(): void
    {
        Schema::table('warehouse_receipts', function (Blueprint $table) {
            $table->dropColumn([
                'office_id', 'operator_id', 'consignee_id',
                'cargo_type', 'is_hazardous', 'is_heat_treated',
                'commodity', 'po_no', 'location_code',
                'delivered_by', 'freight_charge_type', 'freight_amount', 'check_no',
            ]);
        });
    }
};
