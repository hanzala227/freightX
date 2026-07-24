<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_bookings', function (Blueprint $table) {
            // Office & Sales
            $table->foreignId('office_id')->nullable()->constrained('offices')->after('booking_date');
            $table->foreignId('sales_person_id')->nullable()->constrained('users')->after('customer_id');
            $table->foreignId('oversea_agent_id')->nullable()->constrained('trade_partners')->after('carrier_id');
            $table->foreignId('incoterms_id')->nullable()->constrained('incoterms')->after('oversea_agent_id');

            // Cargo details
            $table->string('cargo_type')->nullable()->after('eta');
            $table->string('ship_type')->nullable()->after('cargo_type');
            $table->decimal('pkg_qty', 15, 2)->default(0)->after('ship_type');
            $table->foreignId('pkg_unit_id')->nullable()->constrained('package_units')->after('pkg_qty');
            $table->decimal('gross_weight', 15, 3)->default(0)->after('pkg_unit_id');
            $table->decimal('volume', 15, 4)->default(0)->after('gross_weight');
            $table->decimal('chargeable_weight', 15, 3)->default(0)->after('volume');

            // Charges/Payment terms
            $table->string('wt_val_payment')->default('PPD')->after('status');
            $table->string('other_charges_payment')->default('PPD')->after('wt_val_payment');
            $table->boolean('stackable')->default(true)->after('other_charges_payment');

            // Remarks
            $table->text('handling_info')->nullable()->after('stackable');
            $table->text('pickup_delivery_instructions')->nullable()->after('handling_info');
        });
    }

    public function down(): void
    {
        Schema::table('air_bookings', function (Blueprint $table) {
            $table->dropForeign(['office_id']);
            $table->dropForeign(['sales_person_id']);
            $table->dropForeign(['oversea_agent_id']);
            $table->dropForeign(['incoterms_id']);
            $table->dropForeign(['pkg_unit_id']);
            $table->dropColumn([
                'office_id', 'sales_person_id', 'oversea_agent_id', 'incoterms_id',
                'cargo_type', 'ship_type', 'pkg_qty', 'pkg_unit_id',
                'gross_weight', 'volume', 'chargeable_weight',
                'wt_val_payment', 'other_charges_payment', 'stackable',
                'handling_info', 'pickup_delivery_instructions',
            ]);
        });
    }
};
