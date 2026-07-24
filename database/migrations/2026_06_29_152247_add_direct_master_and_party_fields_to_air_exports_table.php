<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_exports', function (Blueprint $table) {
            // Direct Master fields
            if (!Schema::hasColumn('air_exports', 'is_direct_master')) {
                $table->boolean('is_direct_master')->default(false)->after('internal_remark');
            }
            if (!Schema::hasColumn('air_exports', 'dm_customer_id')) {
                $table->foreignId('dm_customer_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('is_direct_master');
            }
            if (!Schema::hasColumn('air_exports', 'dm_shipper_id')) {
                $table->foreignId('dm_shipper_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('dm_customer_id');
            }
            if (!Schema::hasColumn('air_exports', 'dm_bill_to_id')) {
                $table->foreignId('dm_bill_to_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('dm_shipper_id');
            }
            if (!Schema::hasColumn('air_exports', 'dm_consignee_id')) {
                $table->foreignId('dm_consignee_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('dm_bill_to_id');
            }
            if (!Schema::hasColumn('air_exports', 'dm_notify_id')) {
                $table->foreignId('dm_notify_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('dm_consignee_id');
            }
            if (!Schema::hasColumn('air_exports', 'agent_ref_no')) {
                $table->string('agent_ref_no')->nullable()->after('dm_notify_id');
            }

            // Party fields for MAWB
            if (!Schema::hasColumn('air_exports', 'shipper_id')) {
                $table->foreignId('shipper_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('agent_ref_no');
            }
            if (!Schema::hasColumn('air_exports', 'consignee_id')) {
                $table->foreignId('consignee_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('shipper_id');
            }
            if (!Schema::hasColumn('air_exports', 'notify_id')) {
                $table->foreignId('notify_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('consignee_id');
            }
            if (!Schema::hasColumn('air_exports', 'actual_shipper_id')) {
                $table->foreignId('actual_shipper_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('notify_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('air_exports', function (Blueprint $table) {
            $columns = [
                'is_direct_master', 'dm_customer_id', 'dm_shipper_id', 'dm_bill_to_id', 'dm_consignee_id', 'dm_notify_id',
                'agent_ref_no', 'shipper_id', 'consignee_id', 'notify_id', 'actual_shipper_id',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('air_exports', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
