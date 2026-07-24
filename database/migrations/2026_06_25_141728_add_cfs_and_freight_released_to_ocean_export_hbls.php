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
        Schema::table('ocean_export_hbls', function (Blueprint $table) {
            $table->foreignId('cfs_location_id')->nullable()->after('line_code')->constrained('trade_partners');
            $table->foreignId('freight_released_by_id')->nullable()->after('cfs_location_id')->constrained('trade_partners');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ocean_export_hbls', function (Blueprint $table) {
            $table->dropForeign(['cfs_location_id']);
            $table->dropColumn('cfs_location_id');
            $table->dropForeign(['freight_released_by_id']);
            $table->dropColumn('freight_released_by_id');
        });
    }
};
