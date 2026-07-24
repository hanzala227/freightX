<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocean_imports', function (Blueprint $table) {
            if (!Schema::hasColumn('ocean_imports', 'is_obl_received')) {
                $table->boolean('is_obl_received')->default(false)->after('obl_received_date');
            }
            if (!Schema::hasColumn('ocean_imports', 'is_released')) {
                $table->boolean('is_released')->default(false)->after('released_date');
            }
        });

        Schema::table('ocean_import_hbls', function (Blueprint $table) {
            if (!Schema::hasColumn('ocean_import_hbls', 'cfs_location_id')) {
                $table->foreignId('cfs_location_id')->nullable()->constrained('trade_partners')->after('delivery_location_id');
            }
            if (!Schema::hasColumn('ocean_import_hbls', 'is_rail')) {
                $table->boolean('is_rail')->default(false)->after('delivery_location_id');
            }
            if (!Schema::hasColumn('ocean_import_hbls', 'freight_released_by_id')) {
                $table->foreignId('freight_released_by_id')->nullable()->constrained('users')->after('sales_person_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ocean_imports', function (Blueprint $table) {
            $table->dropColumn(['is_obl_received', 'is_released']);
        });

        Schema::table('ocean_import_hbls', function (Blueprint $table) {
            $table->dropColumn(['cfs_location_id', 'is_rail', 'freight_released_by_id']);
        });
    }
};
