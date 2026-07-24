<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (!Schema::hasColumn('quotations', 'carrier_id')) {
                $table->unsignedBigInteger('carrier_id')->nullable()->after('agent_id');
            }
            if (!Schema::hasColumn('quotations', 'via')) {
                $table->string('via', 100)->nullable()->after('ship_mode');
            }
            if (!Schema::hasColumn('quotations', 'tt')) {
                $table->string('tt', 50)->nullable()->after('via');
            }
            if (!Schema::hasColumn('quotations', 'departure')) {
                $table->string('departure', 100)->nullable()->after('tt');
            }
            if (!Schema::hasColumn('quotations', 'destination')) {
                $table->string('destination', 100)->nullable()->after('departure');
            }
            if (!Schema::hasColumn('quotations', 'liner_code')) {
                $table->string('liner_code', 50)->nullable()->after('destination');
            }
            if (!Schema::hasColumn('quotations', 'final_destination')) {
                $table->string('final_destination', 100)->nullable()->after('liner_code');
            }
            if (!Schema::hasColumn('quotations', 'place_of_receipt')) {
                $table->string('place_of_receipt', 100)->nullable()->after('final_destination');
            }
            if (!Schema::hasColumn('quotations', 'place_of_delivery')) {
                $table->string('place_of_delivery', 100)->nullable()->after('place_of_receipt');
            }
            if (!Schema::hasColumn('quotations', 'schedule_id')) {
                $table->unsignedBigInteger('schedule_id')->nullable()->after('place_of_delivery');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $columns = ['carrier_id', 'via', 'tt', 'departure', 'destination', 'liner_code', 'final_destination', 'place_of_receipt', 'place_of_delivery', 'schedule_id'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('quotations', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
