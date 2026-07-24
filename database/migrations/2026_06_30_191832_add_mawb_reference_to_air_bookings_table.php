<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('air_bookings', 'mawb_reference')) {
                $table->foreignId('mawb_reference')->nullable()->constrained('air_exports')->nullOnDelete()->after('op_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('air_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('air_bookings', 'mawb_reference')) {
                $table->dropForeign(['mawb_reference']);
                $table->dropColumn('mawb_reference');
            }
        });
    }
};
