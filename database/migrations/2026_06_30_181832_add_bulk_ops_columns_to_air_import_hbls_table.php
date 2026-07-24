<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_import_hbls', function (Blueprint $table) {
            if (!Schema::hasColumn('air_import_hbls', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false)->after('hbl_is_ecommerce');
            }
            if (!Schema::hasColumn('air_import_hbls', 'op_id')) {
                $table->foreignId('op_id')->nullable()->constrained('users')->nullOnDelete()->after('sales_person_id');
            }
        });

        Schema::table('air_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('air_bookings', 'op_id')) {
                $table->foreignId('op_id')->nullable()->constrained('users')->nullOnDelete()->after('office_id');
            }
            if (!Schema::hasColumn('air_bookings', 'color')) {
                $table->string('color', 20)->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('air_import_hbls', function (Blueprint $table) {
            $table->dropColumn(['is_blocked', 'op_id']);
        });
        Schema::table('air_bookings', function (Blueprint $table) {
            $table->dropColumn(['op_id', 'color']);
        });
    }
};
