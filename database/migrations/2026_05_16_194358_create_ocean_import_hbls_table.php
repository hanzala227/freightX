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
        Schema::create('ocean_import_hbls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ocean_import_id')->constrained('ocean_imports')->onDelete('cascade');
            $table->string('hbl_no')->unique();
            $table->string('quotation_no')->nullable();
            
            // Parties
            $table->foreignId('customer_id')->nullable()->constrained('trade_partners');
            $table->foreignId('sales_person_id')->nullable()->constrained('users');
            $table->foreignId('shipper_id')->nullable()->constrained('trade_partners');
            $table->foreignId('consignee_id')->nullable()->constrained('trade_partners');
            $table->foreignId('notify_party_id')->nullable()->constrained('trade_partners');
            $table->foreignId('customs_broker_id')->nullable()->constrained('trade_partners');
            $table->foreignId('delivery_location_id')->nullable()->constrained('trade_partners');
            $table->foreignId('referred_by_id')->nullable()->constrained('trade_partners');
            
            // Logistics overrides/details
            $table->foreignId('pod_id')->nullable()->constrained('ports');
            $table->foreignId('del_id')->nullable()->constrained('ports');
            $table->foreignId('fdest_id')->nullable()->constrained('ports');
            $table->foreignId('receipt_id')->nullable()->constrained('ports');
            
            $table->string('vessel_name')->nullable();
            $table->string('voyage_no')->nullable();
            $table->string('pre_carriage_by')->nullable();
            $table->string('service_term')->nullable();
            $table->string('ship_mode')->nullable();
            $table->string('ship_type')->nullable();
            $table->string('cargo_type')->nullable();
            $table->string('incoterms_id')->nullable(); // String or foreign key
            
            // Customs & Release
            $table->date('date_of_issue')->nullable();
            $table->string('lc_no')->nullable();
            $table->string('sc_no')->nullable();
            $table->string('freight_payable_at')->nullable();
            
            $table->boolean('is_express_bl')->default(false);
            $table->boolean('is_door_move')->default(false);
            $table->boolean('is_customs_clear')->default(false);
            $table->boolean('is_customs_hold')->default(false);
            
            $table->boolean('is_obl_received')->default(false);
            $table->date('obl_received_date')->nullable();
            
            $table->boolean('is_fr_released')->default(false);
            $table->date('fr_released_date')->nullable();
            
            $table->boolean('is_an_sent')->default(false);
            $table->date('an_sent_date')->nullable();
            
            $table->boolean('is_do_sent')->default(false);
            $table->date('do_sent_date')->nullable();
            
            $table->string('name_account')->nullable();
            $table->string('group_comm')->nullable();
            $table->string('line_code')->nullable();
            $table->boolean('is_ecommerce')->default(false);
            $table->boolean('is_customs_doc')->default(false);
            
            $table->text('hbl_remark')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['ocean_import_id', 'hbl_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocean_import_hbls');
    }
};
