<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_imports', function (Blueprint $table) {
            // Filing partner fields
            $table->foreignId('shipper_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('acct_carrier_id');
            $table->foreignId('consignee_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('shipper_id');
            $table->foreignId('bill_to_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('consignee_id');
            $table->foreignId('trucker_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('bill_to_id');
            $table->foreignId('business_referred_by_id')->nullable()->constrained('users')->nullOnDelete()->after('trucker_id');
            
            // Location references
            $table->foreignId('final_destination_id')->nullable()->constrained('ports')->nullOnDelete()->after('business_referred_by_id');
            $table->foreignId('delivery_location_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('final_destination_id');
            $table->foreignId('freight_location_id')->nullable()->constrained('ports')->nullOnDelete()->after('delivery_location_id');

            // Filing date fields
            $table->date('pod_eta')->nullable()->after('freight_location_id');
            $table->string('ship_mode')->nullable()->after('pod_eta');
            $table->date('go_date')->nullable()->after('ship_mode');
            $table->string('sub_bl_no')->nullable()->after('go_date');
            $table->date('final_eta')->nullable()->after('sub_bl_no');
            $table->date('last_free_day')->nullable()->after('final_eta');
            $table->date('storage_start_date')->nullable()->after('last_free_day');
        });
    }

    public function down(): void
    {
        Schema::table('air_imports', function (Blueprint $table) {
            $table->dropColumn([
                'shipper_id', 'consignee_id', 'bill_to_id', 'trucker_id',
                'business_referred_by_id', 'final_destination_id',
                'delivery_location_id', 'freight_location_id',
                'pod_eta', 'ship_mode', 'go_date', 'sub_bl_no',
                'final_eta', 'last_free_day', 'storage_start_date',
            ]);
        });
    }
};
