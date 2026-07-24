<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_imports', function (Blueprint $table) {
            $table->boolean('is_direct_master')->default(false)->after('internal_remark');
            $table->foreignId('dm_customer_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('is_direct_master');
            $table->foreignId('dm_shipper_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('dm_customer_id');
            $table->foreignId('dm_consignee_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('dm_shipper_id');
            $table->foreignId('dm_notify_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('dm_consignee_id');
            $table->foreignId('dm_bill_to_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('dm_notify_id');
            $table->foreignId('dm_sales_person_id')->nullable()->constrained('users')->nullOnDelete()->after('dm_bill_to_id');
        });
    }

    public function down(): void
    {
        Schema::table('air_imports', function (Blueprint $table) {
            $table->dropForeign(['dm_sales_person_id']);
            $table->dropForeign(['dm_bill_to_id']);
            $table->dropForeign(['dm_notify_id']);
            $table->dropForeign(['dm_consignee_id']);
            $table->dropForeign(['dm_shipper_id']);
            $table->dropForeign(['dm_customer_id']);
            $table->dropColumn([
                'is_direct_master',
                'dm_customer_id',
                'dm_shipper_id',
                'dm_consignee_id',
                'dm_notify_id',
                'dm_bill_to_id',
                'dm_sales_person_id',
            ]);
        });
    }
};
