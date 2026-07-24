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
        Schema::table('ocean_export_hbls', function (Blueprint $table) {
            // Set default values for fields that should not be NULL and are not foreign keys
            $table->string('quotation_no')->default('')->change();
            $table->string('pre_carriage_by')->default('')->change();
            $table->string('service_term')->default('')->change();
            $table->string('ship_type')->default('FCL')->change();
            $table->string('freight_payable_at')->default('')->change();
            
            // Ensure required fields have proper defaults
            $table->string('hbl_no')->default('')->change();
            $table->string('vessel_name')->default('')->change();
            $table->string('voyage_no')->default('')->change();
            $table->string('cargo_type')->default('GENERAL CARGO')->change();
            $table->string('ship_mode')->default('FCL')->change();
            
            // For foreign key fields, we'll update existing NULL values to 0
            DB::statement('UPDATE ocean_export_hbls SET customs_broker_id = 0 WHERE customs_broker_id IS NULL');
            DB::statement('UPDATE ocean_export_hbls SET delivery_location_id = 0 WHERE delivery_location_id IS NULL');
            DB::statement('UPDATE ocean_export_hbls SET referred_by_id = 0 WHERE referred_by_id IS NULL');
            DB::statement('UPDATE ocean_export_hbls SET incoterms_id = 1 WHERE incoterms_id IS NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ocean_export_hbls', function (Blueprint $table) {
            // Revert changes if needed
            $table->string('quotation_no')->default(null)->change();
            $table->string('pre_carriage_by')->default(null)->change();
            $table->string('service_term')->default(null)->change();
            $table->string('ship_type')->default(null)->change();
            $table->string('freight_payable_at')->default(null)->change();
            
            $table->string('hbl_no')->default(null)->change();
            $table->string('vessel_name')->default(null)->change();
            $table->string('voyage_no')->default(null)->change();
            $table->string('cargo_type')->default(null)->change();
            $table->string('ship_mode')->default(null)->change();
        });
    }
};
