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
        Schema::create('ocean_exports', function (Blueprint $table) {
            $table->id();
            $table->string('file_no')->unique(); // MOE-25040001
            $table->string('mbl_no')->nullable();
            $table->string('booking_no')->nullable();
            $table->date('post_date')->nullable();
            
            // Core Parties
            $table->foreignId('office_id')->nullable()->constrained('offices');
            $table->foreignId('op_id')->nullable()->constrained('users');
            $table->foreignId('forwarding_agent_id')->nullable()->constrained('trade_partners');
            $table->foreignId('oversea_agent_id')->nullable()->constrained('trade_partners');
            $table->foreignId('co_loader_id')->nullable()->constrained('trade_partners');
            $table->foreignId('carrier_id')->nullable()->constrained('trade_partners');
            $table->foreignId('acct_carrier_id')->nullable()->constrained('trade_partners');
            $table->foreignId('business_referred_by_id')->nullable()->constrained('trade_partners');
            
            // Direct Master Case
            $table->boolean('is_direct_master')->default(false);
            $table->foreignId('dm_customer_id')->nullable()->constrained('trade_partners');
            $table->foreignId('dm_shipper_id')->nullable()->constrained('trade_partners');
            $table->foreignId('dm_consignee_id')->nullable()->constrained('trade_partners');
            $table->foreignId('dm_notify_id')->nullable()->constrained('trade_partners');
            $table->foreignId('dm_bill_to_id')->nullable()->constrained('trade_partners');
            $table->foreignId('dm_sales_person_id')->nullable()->constrained('users');
            
            // Shipment Details
            $table->string('agent_ref_no')->nullable();
            $table->string('contract_no')->nullable();
            $table->string('sub_bl_no')->nullable();
            $table->string('bl_type')->default('NORMAL');
            $table->string('cargo_type')->default('GENERAL CARGO');
            $table->string('ship_mode')->default('FCL');
            
            // Logistics
            $table->foreignId('vessel_id')->nullable()->constrained('vessels');
            $table->string('voyage')->nullable();
            $table->foreignId('pol_id')->nullable()->constrained('ports');
            $table->foreignId('pod_id')->nullable()->constrained('ports');
            $table->foreignId('del_id')->nullable()->constrained('ports');
            $table->foreignId('fdest_id')->nullable()->constrained('ports');
            $table->foreignId('receipt_id')->nullable()->constrained('ports');
            
            $table->date('etd')->nullable();
            $table->date('eta')->nullable();
            $table->date('atd')->nullable();
            $table->date('ata')->nullable();
            $table->date('etb')->nullable();
            $table->date('final_eta')->nullable();
            $table->date('receipt_etd')->nullable();
            
            // Locations
            $table->foreignId('cy_location_id')->nullable()->constrained('trade_partners');
            $table->foreignId('cfs_location_id')->nullable()->constrained('trade_partners');
            $table->foreignId('return_location_id')->nullable()->constrained('trade_partners');
            
            // Terms
            $table->foreignId('service_term_from_id')->nullable()->constrained('service_terms');
            $table->foreignId('service_term_to_id')->nullable()->constrained('service_terms');
            $table->string('freight_term')->nullable();
            $table->string('obl_type')->nullable();
            $table->date('obl_received_date')->nullable();
            $table->date('released_date')->nullable();
            $table->date('latest_gate_in')->nullable();
            
            $table->boolean('is_ecommerce')->default(false);
            $table->text('internal_remark')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['file_no', 'mbl_no', 'booking_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocean_exports');
    }
};
