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
        Schema::table('ocean_exports', function (Blueprint $table) {
            // Restore string fields only
            $table->string('file_no')->nullable()->default(null)->change();
            $table->string('mbl_no')->nullable()->default(null)->change();
            $table->string('booking_no')->nullable()->default(null)->change();
            $table->string('voyage')->nullable()->default(null)->change();
            $table->string('cargo_type')->nullable()->default(null)->change();
            $table->string('ship_mode')->nullable()->default(null)->change();
            $table->string('bl_type')->nullable()->default(null)->change();
            $table->string('obl_type')->nullable()->default(null)->change();
            $table->string('freight_term')->nullable()->default(null)->change();
        });

        Schema::table('ocean_export_hbls', function (Blueprint $table) {
            // Restore string fields only
            $table->string('quotation_no')->nullable()->default(null)->change();
            $table->string('pre_carriage_by')->nullable()->default(null)->change();
            $table->string('service_term')->nullable()->default(null)->change();
            $table->string('ship_type')->nullable()->default(null)->change();
            $table->string('freight_payable_at')->nullable()->default(null)->change();
            $table->string('hbl_no')->nullable()->default(null)->change();
            $table->string('vessel_name')->nullable()->default(null)->change();
            $table->string('voyage_no')->nullable()->default(null)->change();
            $table->string('cargo_type')->nullable()->default(null)->change();
            $table->string('ship_mode')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
