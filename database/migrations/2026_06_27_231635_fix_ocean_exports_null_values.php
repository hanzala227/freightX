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
            // Set default values for fields that should not be NULL and are not foreign keys
            $table->string('file_no')->default('')->change();
            $table->string('mbl_no')->default('')->change();
            $table->string('booking_no')->default('')->change();
            $table->string('voyage')->default('')->change();
            $table->string('cargo_type')->default('GENERAL CARGO')->change();
            $table->string('ship_mode')->default('FCL')->change();
            $table->string('bl_type')->default('NORMAL')->change();
            $table->string('obl_type')->default('ORIGINAL BILL OF LADING')->change();
            $table->string('freight_term')->default('Prepaid')->change();
            
            // For foreign key fields, we'll update existing NULL values to 0
            DB::statement('UPDATE ocean_exports SET dm_customer_id = 0 WHERE dm_customer_id IS NULL');
            DB::statement('UPDATE ocean_exports SET dm_shipper_id = 0 WHERE dm_shipper_id IS NULL');
            DB::statement('UPDATE ocean_exports SET dm_consignee_id = 0 WHERE dm_consignee_id IS NULL');
            DB::statement('UPDATE ocean_exports SET dm_notify_id = 0 WHERE dm_notify_id IS NULL');
            DB::statement('UPDATE ocean_exports SET dm_bill_to_id = 0 WHERE dm_bill_to_id IS NULL');
            DB::statement('UPDATE ocean_exports SET receipt_id = 0 WHERE receipt_id IS NULL');
            DB::statement('UPDATE ocean_exports SET incoterm_id = 1 WHERE incoterm_id IS NULL');
            DB::statement('UPDATE ocean_exports SET trucker_id = 0 WHERE trucker_id IS NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ocean_exports', function (Blueprint $table) {
            // Revert changes if needed
            $table->string('file_no')->default(null)->change();
            $table->string('mbl_no')->default(null)->change();
            $table->string('booking_no')->default(null)->change();
            $table->string('voyage')->default(null)->change();
            $table->string('cargo_type')->default(null)->change();
            $table->string('ship_mode')->default(null)->change();
            $table->string('bl_type')->default(null)->change();
            $table->string('obl_type')->default(null)->change();
            $table->string('freight_term')->default(null)->change();
        });
    }
};
