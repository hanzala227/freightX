<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->string('schedule_no')->nullable()->after('id');
            $table->string('carrier_bkg_no')->nullable()->after('voyage');
            $table->string('shipping_agent')->nullable()->after('carrier_bkg_no');
            $table->foreignId('office_id')->nullable()->constrained('offices')->after('shipping_agent');
            $table->string('itn_no')->nullable()->after('office_id');
            $table->foreignId('oversea_agent_id')->nullable()->constrained('trade_partners')->after('itn_no');
            $table->string('bl_type')->nullable()->after('oversea_agent_id');
            $table->foreignId('notify_id')->nullable()->constrained('trade_partners')->after('bl_type');
            $table->foreignId('op_id')->nullable()->constrained('users')->after('notify_id');
            $table->date('post_date')->nullable()->after('op_id');
            $table->foreignId('forwarding_agent_id')->nullable()->constrained('trade_partners')->after('post_date');
            $table->foreignId('vessel_id')->nullable()->constrained('vessels')->after('forwarding_agent_id');
            $table->foreignId('pol_id')->nullable()->constrained('ports')->after('vessel_id');
            $table->foreignId('pod_id')->nullable()->constrained('ports')->after('pol_id');
            $table->foreignId('fdest_id')->nullable()->constrained('ports')->after('pod_id');
            $table->date('final_eta')->nullable()->after('eta');
            $table->string('delivery_to_pier')->nullable()->after('final_eta');
            $table->foreignId('por_id')->nullable()->constrained('ports')->after('delivery_to_pier');
            $table->foreignId('del_id')->nullable()->constrained('ports')->after('por_id');
            $table->string('empty_pickup')->nullable()->after('del_id');
            $table->date('por_etd')->nullable()->after('empty_pickup');
            $table->date('del_eta')->nullable()->after('por_etd');
            $table->string('freight')->nullable()->after('del_eta');
            $table->string('obl_type')->nullable()->after('freight');
            $table->date('on_board_date')->nullable()->after('obl_type');
            $table->string('ship_mode')->nullable()->after('on_board_date');
            $table->date('doc_cutoff')->nullable()->after('ship_mode');
            $table->foreignId('svc_term_from_id')->nullable()->constrained('service_terms')->after('doc_cutoff');
            $table->foreignId('svc_term_to_id')->nullable()->constrained('service_terms')->after('svc_term_from_id');
            $table->date('port_cutoff')->nullable()->after('svc_term_to_id');
            $table->date('rail_cutoff')->nullable()->after('port_cutoff');
            $table->foreignId('carrier_id')->nullable()->constrained('trade_partners')->after('rail_cutoff');
            $table->foreignId('actual_shipper_id')->nullable()->constrained('trade_partners')->after('carrier_id');
            $table->foreignId('customer_id')->nullable()->constrained('trade_partners')->after('actual_shipper_id');
            $table->foreignId('bill_to_id')->nullable()->constrained('trade_partners')->after('customer_id');
            $table->foreignId('consignee_id')->nullable()->constrained('trade_partners')->after('bill_to_id');
            $table->foreignId('trucker_id')->nullable()->constrained('trade_partners')->after('consignee_id');
            $table->foreignId('referred_by_id')->nullable()->constrained('trade_partners')->after('trucker_id');
            $table->string('cargo_type')->nullable()->after('referred_by_id');
            $table->string('cargo_pickup')->nullable()->after('cargo_type');
            $table->date('cargo_ready')->nullable()->after('cargo_pickup');
            $table->date('wh_cutoff')->nullable()->after('cargo_ready');
            $table->date('vgm_cutoff')->nullable()->after('wh_cutoff');
            $table->string('color', 20)->nullable()->after('vgm_cutoff');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn([
                'schedule_no', 'carrier_bkg_no', 'shipping_agent', 'office_id', 'itn_no',
                'oversea_agent_id', 'bl_type', 'notify_id', 'op_id', 'post_date',
                'forwarding_agent_id', 'vessel_id', 'pol_id', 'pod_id', 'fdest_id',
                'final_eta', 'delivery_to_pier', 'por_id', 'del_id', 'empty_pickup',
                'por_etd', 'del_eta', 'freight', 'obl_type', 'on_board_date', 'ship_mode',
                'doc_cutoff', 'svc_term_from_id', 'svc_term_to_id', 'port_cutoff', 'rail_cutoff',
                'carrier_id', 'actual_shipper_id', 'customer_id', 'bill_to_id', 'consignee_id',
                'trucker_id', 'referred_by_id', 'cargo_type', 'cargo_pickup', 'cargo_ready',
                'wh_cutoff', 'vgm_cutoff', 'color',
            ]);
        });
    }
};
