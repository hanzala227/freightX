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
        Schema::table('charges', function (Blueprint $table) {
            $table->string('seal_no2')->nullable();
            $table->string('pickup_no')->nullable();
            $table->string('cprs_no')->nullable();
            $table->string('cnru_no')->nullable();
            $table->string('it_no')->nullable();
            $table->string('dg')->nullable();
            $table->string('temp')->nullable();
            $table->string('vent')->nullable();
            $table->date('storage_start_date')->nullable();
            $table->date('storage_end_date')->nullable();
            $table->boolean('carrier_release')->default(false);
            $table->string('yard_location')->nullable();
            $table->date('unload_vessel_date')->nullable();
            $table->date('gate_in_date')->nullable();
            $table->date('rail_start_date')->nullable();
            $table->date('pod_eta_date')->nullable();
            $table->boolean('available_pickup')->default(false);
            $table->decimal('weight_lb', 15, 2)->nullable();
            $table->date('appt_date')->nullable();
            $table->unsignedBigInteger('trucker_id')->nullable();
            $table->date('pickup_date')->nullable();
            $table->date('gate_out_date')->nullable();
            $table->date('fdest_eta_date')->nullable();
            $table->date('eta_door_date')->nullable();
            $table->date('ata_door_date')->nullable();
            $table->decimal('measurement_cft', 15, 2)->nullable();
            $table->text('container_remarks')->nullable();
            $table->text('internal_remarks')->nullable();
            $table->date('empty_confirmed_date')->nullable();
            $table->date('empty_return_date')->nullable();
            $table->boolean('complete')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('charges', function (Blueprint $table) {
            $table->dropColumn([
                'seal_no2', 'pickup_no', 'cprs_no', 'cnru_no', 'it_no', 'dg', 
                'temp', 'vent', 'storage_start_date', 'storage_end_date', 
                'carrier_release', 'yard_location', 'unload_vessel_date', 
                'gate_in_date', 'rail_start_date', 'pod_eta_date', 'available_pickup', 
                'weight_lb', 'appt_date', 'trucker_id', 'pickup_date', 'gate_out_date', 
                'fdest_eta_date', 'eta_door_date', 'ata_door_date', 'measurement_cft', 
                'container_remarks', 'internal_remarks', 'empty_confirmed_date', 
                'empty_return_date', 'complete'
            ]);
        });
    }
};
