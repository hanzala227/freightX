<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('truck_shipments', function (Blueprint $table) {
            if (!Schema::hasColumn('truck_shipments', 'bill_to_id')) {
                $table->foreignId('bill_to_id')->nullable()->after('customer_ref_no')->constrained('trade_partners');
            }
        });
    }

    public function down(): void
    {
        Schema::table('truck_shipments', function (Blueprint $table) {
            if (Schema::hasColumn('truck_shipments', 'bill_to_id')) {
                $table->dropForeign(['bill_to_id']);
                $table->dropColumn('bill_to_id');
            }
        });
    }
};
