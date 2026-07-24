<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouse_receipts', function (Blueprint $table) {
            $table->json('charges_data')->nullable()->after('internal_remark');
            $table->json('memos_data')->nullable()->after('charges_data');
        });
    }

    public function down(): void
    {
        Schema::table('warehouse_receipts', function (Blueprint $table) {
            $table->dropColumn(['charges_data', 'memos_data']);
        });
    }
};
