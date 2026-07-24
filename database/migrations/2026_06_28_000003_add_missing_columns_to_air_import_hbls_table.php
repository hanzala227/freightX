<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_import_hbls', function (Blueprint $table) {
            $table->foreignId('bill_to_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('notify_party_id');
            $table->foreignId('customs_broker_id')->nullable()->constrained('trade_partners')->nullOnDelete()->after('bill_to_id');
            $table->foreignId('quotation_id')->nullable()->constrained('quotations')->nullOnDelete()->after('customs_broker_id');
            $table->string('hsn_code')->nullable()->after('quotation_id');
            $table->boolean('is_frt_released')->default(false)->after('hsn_code');
            $table->date('frt_released_date')->nullable()->after('is_frt_released');
        });
    }

    public function down(): void
    {
        Schema::table('air_import_hbls', function (Blueprint $table) {
            $table->dropColumn([
                'bill_to_id', 'customs_broker_id', 'quotation_id',
                'hsn_code', 'is_frt_released', 'frt_released_date',
            ]);
        });
    }
};
