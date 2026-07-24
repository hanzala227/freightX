<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_export_hbls', function (Blueprint $table) {
            if (!Schema::hasColumn('air_export_hbls', 'color')) {
                $table->string('color', 20)->nullable()->after('hbl_remark');
            }
            if (!Schema::hasColumn('air_export_hbls', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false)->after('color');
            }
            if (!Schema::hasColumn('air_export_hbls', 'op_id')) {
                $table->foreignId('op_id')->nullable()->constrained('users')->after('sales_person_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('air_export_hbls', function (Blueprint $table) {
            $table->dropForeign(['op_id']);
            $table->dropColumn(['color', 'is_blocked', 'op_id']);
        });
    }
};
