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
        Schema::table('ocean_exports', function (Blueprint $table) {
            $table->string('ams_no')->nullable()->after('is_blocked');
            $table->string('isf_no')->nullable()->after('ams_no');
            $table->date('isf_matched_date')->nullable()->after('isf_no');
            $table->boolean('is_isf_3rd_party')->default(false)->after('isf_matched_date');
            $table->string('entry_no')->nullable()->after('is_isf_3rd_party');
            $table->date('entry_doc_sent_date')->nullable()->after('entry_no');
            $table->date('go_date')->nullable()->after('entry_doc_sent_date');
            $table->date('available_date')->nullable()->after('go_date');
            $table->date('c_released_date')->nullable()->after('available_date');
            $table->foreignId('released_by_id')->nullable()->constrained('users')->after('c_released_date');
            $table->boolean('is_ror')->default(false)->after('released_by_id');
            $table->boolean('is_hold')->default(false)->after('is_ror');
            $table->date('door_delivery_date')->nullable()->after('is_hold');
            $table->foreignId('trucker_id')->nullable()->constrained('trade_partners')->after('door_delivery_date');
            $table->date('expiry_date')->nullable()->after('trucker_id');
            $table->string('sales_type')->nullable()->after('expiry_date');
            $table->string('incoterm_id')->nullable()->after('sales_type');
        });
    }

    public function down(): void
    {
        Schema::table('ocean_exports', function (Blueprint $table) {
            $table->dropColumn([
                'ams_no', 'isf_no', 'isf_matched_date', 'is_isf_3rd_party',
                'entry_no', 'entry_doc_sent_date', 'go_date', 'available_date',
                'c_released_date', 'released_by_id', 'is_ror', 'is_hold',
                'door_delivery_date', 'trucker_id', 'expiry_date', 'sales_type', 'incoterm_id'
            ]);
        });
    }
};
