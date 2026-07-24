<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('air_import_containers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('air_import_id')->constrained('air_imports')->cascadeOnDelete();
            
            // Core container fields
            $table->string('container_no')->nullable();
            $table->string('pp_ctf')->nullable();
            $table->string('container_type')->nullable(); // 40'HQ, 20'GP, etc.
            $table->string('seal_no')->nullable();
            $table->string('seal_no2')->nullable();
            $table->date('lfd')->nullable();
            $table->date('fdd')->nullable();
            
            // Quantities
            $table->decimal('pkg_qty', 15, 2)->default(0);
            $table->decimal('weight_kg', 15, 3)->default(0);
            $table->decimal('weight_lb', 15, 3)->nullable();
            $table->decimal('measure_cbm', 15, 4)->default(0);
            $table->decimal('measure_cft', 15, 4)->nullable();
            
            // Expanded section fields
            $table->string('pickup_no')->nullable();
            $table->string('cprs_no')->nullable();
            $table->string('cnru_no')->nullable();
            $table->string('it_no')->nullable();
            $table->boolean('is_dg')->default(false);
            $table->date('storage_start_date')->nullable();
            $table->date('storage_end_date')->nullable();
            $table->boolean('is_carrier_release')->default(false);
            $table->string('yard_location')->nullable();
            $table->date('unload_vessel_date')->nullable();
            $table->date('gate_in_date')->nullable();
            $table->date('rail_start_date')->nullable();
            $table->date('pod_eta')->nullable();
            $table->boolean('is_avail_pickup')->default(false);
            $table->date('appointment_date')->nullable();
            $table->foreignId('trucker_id')->nullable()->constrained('trade_partners');
            $table->date('pickup_date')->nullable();
            $table->date('gate_out_date')->nullable();
            $table->date('fdest_eta')->nullable();
            $table->date('eta_door')->nullable();
            $table->date('ata_door')->nullable();
            $table->date('empty_conf_date')->nullable();
            $table->date('empty_ret_date')->nullable();
            $table->boolean('is_complete')->default(false);
            $table->text('remarks')->nullable();
            $table->text('internal_remarks')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('air_import_containers');
    }
};
