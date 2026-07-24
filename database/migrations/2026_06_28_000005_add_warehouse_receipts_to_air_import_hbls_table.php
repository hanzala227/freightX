<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_import_hbls', function (Blueprint $table) {
            $table->json('warehouse_receipts')->nullable()->after('po_numbers');
        });
    }

    public function down(): void
    {
        Schema::table('air_import_hbls', function (Blueprint $table) {
            $table->dropColumn('warehouse_receipts');
        });
    }
};
