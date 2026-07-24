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
        Schema::create('ocean_import_containers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ocean_import_id')->constrained('ocean_imports')->onDelete('cascade');
            $table->string('container_no')->nullable();
            $table->string('pp_ctf')->nullable();
            $table->foreignId('container_type_id')->nullable()->constrained('container_types');
            $table->string('seal_no')->nullable();
            $table->string('seal_no2')->nullable();
            
            // Logistics Dates
            $table->date('lfd')->nullable(); // Last Free Day
            $table->date('fdd')->nullable(); // Final Delivery Date
            $table->date('storage_start_date')->nullable();
            $table->date('storage_end_date')->nullable();
            $table->date('unload_vessel_date')->nullable();
            $table->date('gate_in_date')->nullable();
            $table->date('rail_start_date')->nullable();
            $table->date('pod_eta')->nullable();
            $table->date('appointment_date')->nullable();
            $table->date('pickup_date')->nullable();
            $table->date('gate_out_date')->nullable();
            $table->date('fdest_eta')->nullable();
            $table->date('eta_door')->nullable();
            $table->date('ata_door')->nullable();
            $table->date('empty_conf_date')->nullable();
            $table->date('empty_ret_date')->nullable();
            
            // Quantities
            $table->decimal('pkg_qty', 15, 2)->default(0);
            $table->foreignId('pkg_unit_id')->nullable()->constrained('package_units');
            $table->decimal('weight_kg', 15, 3)->default(0);
            $table->decimal('weight_lb', 15, 3)->default(0);
            $table->decimal('measure_cbm', 15, 3)->default(0);
            $table->decimal('measure_cft', 15, 3)->default(0);
            
            // Other details
            $table->string('pickup_no')->nullable();
            $table->string('cprs_no')->nullable();
            $table->string('cnru_no')->nullable();
            $table->string('it_no')->nullable();
            $table->boolean('is_dg')->default(false);
            $table->boolean('is_carrier_release')->default(false);
            $table->string('yard_location')->nullable();
            $table->boolean('is_avail_pickup')->default(false);
            $table->foreignId('trucker_id')->nullable()->constrained('trade_partners');
            $table->boolean('is_complete')->default(false);
            
            $table->text('remarks')->nullable();
            $table->text('internal_remarks')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['ocean_import_id', 'container_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocean_import_containers');
    }
};
