<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_bookings', function (Blueprint $table) {
            $table->foreignId('shipper_id')
                ->nullable()
                ->after('carrier_id')
                ->constrained('trade_partners')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('air_bookings', function (Blueprint $table) {
            $table->dropForeign(['shipper_id']);
            $table->dropColumn('shipper_id');
        });
    }
};
