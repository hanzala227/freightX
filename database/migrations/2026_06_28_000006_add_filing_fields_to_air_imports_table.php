<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_imports', function (Blueprint $table) {
            $table->string('cy_cfs_loc')->nullable()->after('ship_mode');
            $table->date('expiry_date')->nullable()->after('cy_cfs_loc');
            $table->string('ams_no')->nullable()->after('expiry_date');
            $table->string('isf_no')->nullable()->after('ams_no');
            $table->date('isf_matched_date')->nullable()->after('isf_no');
            $table->boolean('isf_3rd_party')->default(false)->after('isf_matched_date');
            $table->string('sales_type')->nullable()->after('isf_3rd_party');
            $table->date('c_released_date')->nullable()->after('sales_type');
            $table->string('entry_no')->nullable()->after('c_released_date');
            $table->boolean('ror')->default(false)->after('entry_no');
            $table->foreignId('released_by_id')->nullable()->constrained('users')->nullOnDelete()->after('ror');
            $table->boolean('do_sent')->default(false)->after('released_by_id');
            $table->date('do_sent_date')->nullable()->after('do_sent');
            $table->date('entry_doc_sent_date')->nullable()->after('do_sent_date');
            $table->boolean('hold')->default(false)->after('entry_doc_sent_date');
            $table->date('door_delivered_date')->nullable()->after('hold');
        });
    }

    public function down(): void
    {
        Schema::table('air_imports', function (Blueprint $table) {
            $table->dropColumn([
                'cy_cfs_loc', 'expiry_date', 'ams_no', 'isf_no', 'isf_matched_date',
                'isf_3rd_party', 'sales_type', 'c_released_date', 'entry_no', 'ror',
                'released_by_id', 'do_sent', 'do_sent_date', 'entry_doc_sent_date',
                'hold', 'door_delivered_date',
            ]);
        });
    }
};
