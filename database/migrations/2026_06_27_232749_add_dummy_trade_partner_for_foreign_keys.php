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
        // Create a dummy trade partner with ID 0 for foreign key references
        DB::statement('INSERT IGNORE INTO trade_partners (id, name, type, created_at, updated_at) VALUES (0, "* Unassigned *", "UN", NOW(), NOW())');
        DB::statement('INSERT IGNORE INTO users (id, name, email, created_at, updated_at) VALUES (0, "* Unassigned *", "unassigned@example.com", NOW(), NOW())');
        
        // Update NULL foreign keys to point to the dummy records
        DB::statement('UPDATE ocean_exports SET dm_customer_id = 0 WHERE dm_customer_id IS NULL');
        DB::statement('UPDATE ocean_exports SET dm_shipper_id = 0 WHERE dm_shipper_id IS NULL');
        DB::statement('UPDATE ocean_exports SET dm_consignee_id = 0 WHERE dm_consignee_id IS NULL');
        DB::statement('UPDATE ocean_exports SET dm_notify_id = 0 WHERE dm_notify_id IS NULL');
        DB::statement('UPDATE ocean_exports SET dm_bill_to_id = 0 WHERE dm_bill_to_id IS NULL');
        DB::statement('UPDATE ocean_exports SET receipt_id = 0 WHERE receipt_id IS NULL');
        DB::statement('UPDATE ocean_exports SET trucker_id = 0 WHERE trucker_id IS NULL');
        
        DB::statement('UPDATE ocean_export_hbls SET customs_broker_id = 0 WHERE customs_broker_id IS NULL');
        DB::statement('UPDATE ocean_export_hbls SET delivery_location_id = 0 WHERE delivery_location_id IS NULL');
        DB::statement('UPDATE ocean_export_hbls SET referred_by_id = 0 WHERE referred_by_id IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the dummy records
        DB::statement('DELETE FROM trade_partners WHERE id = 0');
        DB::statement('DELETE FROM users WHERE id = 0');
    }
};
