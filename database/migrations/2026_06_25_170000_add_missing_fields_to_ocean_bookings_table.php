<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocean_bookings', function (Blueprint $table) {
            $table->string('quotation_no')->nullable()->after('booking_date');
            $table->string('itn_no')->nullable()->after('quotation_no');
            $table->foreignId('sales_person_id')->nullable()->constrained('users')->after('itn_no');
            $table->foreignId('op_id')->nullable()->constrained('users')->after('sales_person_id');
            $table->string('carrier_bkg_no')->nullable()->after('op_id');
            $table->string('ship_mode', 50)->nullable()->after('carrier_id');
            $table->foreignId('svc_term_from_id')->nullable()->constrained('service_terms')->after('ship_mode');
            $table->foreignId('svc_term_to_id')->nullable()->constrained('service_terms')->after('svc_term_from_id');
            $table->string('incoterms', 10)->nullable()->after('svc_term_to_id');
            $table->foreignId('actual_shipper_id')->nullable()->constrained('trade_partners')->after('incoterms');
            $table->foreignId('bill_to_id')->nullable()->constrained('trade_partners')->after('actual_shipper_id');
            $table->foreignId('consignee_id')->nullable()->constrained('trade_partners')->after('bill_to_id');
            $table->foreignId('notify_id')->nullable()->constrained('trade_partners')->after('consignee_id');
            $table->string('shipping_agent', 100)->nullable()->after('notify_id');
            $table->foreignId('hbl_agent_id')->nullable()->constrained('trade_partners')->after('shipping_agent');
            $table->foreignId('forwarding_agent_id')->nullable()->constrained('trade_partners')->after('hbl_agent_id');
            $table->foreignId('co_loader_id')->nullable()->constrained('trade_partners')->after('forwarding_agent_id');
            $table->foreignId('por_id')->nullable()->constrained('ports')->after('co_loader_id');
            $table->foreignId('del_id')->nullable()->constrained('ports')->after('pod_id');
            $table->foreignId('fdest_id')->nullable()->constrained('ports')->after('del_id');
            $table->foreignId('office_id')->nullable()->constrained('offices')->after('fdest_id');
            $table->string('cargo_type', 50)->nullable()->after('office_id');
            $table->foreignId('trucker_id')->nullable()->constrained('trade_partners')->after('cargo_type');
            $table->string('container_no')->nullable()->after('trucker_id');
            $table->string('marks', 500)->nullable()->after('container_no');
            $table->string('description', 1000)->nullable()->after('marks');
            $table->string('remarks', 1000)->nullable()->after('description');
            $table->decimal('pkg_qty', 15, 2)->default(0)->after('remarks');
            $table->decimal('weight_kg', 15, 3)->default(0)->after('pkg_qty');
            $table->decimal('measure_cbm', 15, 3)->default(0)->after('weight_kg');
        });
    }

    public function down(): void
    {
        Schema::table('ocean_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'quotation_no', 'itn_no', 'sales_person_id', 'op_id', 'carrier_bkg_no',
                'ship_mode', 'svc_term_from_id', 'svc_term_to_id', 'incoterms',
                'actual_shipper_id', 'bill_to_id', 'consignee_id', 'notify_id',
                'shipping_agent', 'hbl_agent_id', 'forwarding_agent_id', 'co_loader_id',
                'por_id', 'del_id', 'fdest_id', 'office_id', 'cargo_type', 'trucker_id',
                'container_no', 'marks', 'description', 'remarks',
                'pkg_qty', 'weight_kg', 'measure_cbm',
            ]);
        });
    }
};
