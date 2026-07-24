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
        Schema::table('work_orders', function (Blueprint $table) {
            $table->foreignId('carrier_id')->nullable()->constrained('trade_partners');
            $table->string('carrier_bkg_no')->nullable();
            $table->string('place_of_receipt')->nullable();
            $table->string('vessel_info')->nullable();
            $table->string('etd')->nullable();
            
            $table->foreignId('empty_pickup_location_id')->nullable()->constrained('trade_partners');
            $table->text('empty_pickup_address')->nullable();
            $table->string('empty_pickup_ref')->nullable();
            $table->string('empty_pickup_date')->nullable();
            
            $table->foreignId('freight_pickup_location_id')->nullable()->constrained('trade_partners');
            $table->text('freight_pickup_address')->nullable();
            $table->string('freight_pickup_ref')->nullable();
            $table->string('freight_pickup_date')->nullable();
            
            $table->integer('total_packages')->nullable();
            $table->string('package_unit')->nullable();
            $table->string('container_qty')->nullable();
            $table->string('gross_weight_kgs')->nullable();
            $table->string('gross_weight_lbs')->nullable();
            
            $table->boolean('show_bill_to')->default(true);
            $table->foreignId('bill_to_id')->nullable()->constrained('trade_partners');
            $table->text('bill_to_address')->nullable();
            $table->string('bill_to_ref')->nullable();
            
            $table->boolean('do_not_break_down_pallet')->default(false);
            $table->json('extra_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['carrier_id']);
            $table->dropForeign(['empty_pickup_location_id']);
            $table->dropForeign(['freight_pickup_location_id']);
            $table->dropForeign(['bill_to_id']);
            
            $table->dropColumn([
                'carrier_id',
                'carrier_bkg_no',
                'place_of_receipt',
                'vessel_info',
                'etd',
                'empty_pickup_location_id',
                'empty_pickup_address',
                'empty_pickup_ref',
                'empty_pickup_date',
                'freight_pickup_location_id',
                'freight_pickup_address',
                'freight_pickup_ref',
                'freight_pickup_date',
                'total_packages',
                'package_unit',
                'container_qty',
                'gross_weight_kgs',
                'gross_weight_lbs',
                'show_bill_to',
                'bill_to_id',
                'bill_to_address',
                'bill_to_ref',
                'do_not_break_down_pallet',
                'extra_data'
            ]);
        });
    }
};
