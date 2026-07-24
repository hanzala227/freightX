<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Only add columns that DON'T already exist in the table.
            // The following columns already exist: office_id, agent_id, ship_mode,
            // country_of_origin, op_id, booking_no, po_no, commodity, hts_code,
            // pkg_qty, pkg_unit, weight_kg, weight_lb, volume_cbm, volume_cft,
            // description, transport_mode, quote_date, expiry_date, service_term.

            // Truly missing columns that the form sends:
            if (!Schema::hasColumn('quotations', 'shipping_type')) {
                $table->string('shipping_type', 50)->nullable()->after('transport_mode');
            }
            if (!Schema::hasColumn('quotations', 'valid_date')) {
                $table->date('valid_date')->nullable()->after('quote_date');
            }
            if (!Schema::hasColumn('quotations', 'create_date')) {
                $table->date('create_date')->nullable()->after('valid_date');
            }
            if (!Schema::hasColumn('quotations', 'service_term_origin')) {
                $table->string('service_term_origin', 10)->nullable()->after('service_term');
            }
            if (!Schema::hasColumn('quotations', 'service_term_dest')) {
                $table->string('service_term_dest', 10)->nullable()->after('service_term_origin');
            }
            if (!Schema::hasColumn('quotations', 'remark')) {
                $table->text('remark')->nullable()->after('description');
            }
            if (!Schema::hasColumn('quotations', 'contact')) {
                $table->string('contact', 100)->nullable()->after('customer_id');
            }
            if (!Schema::hasColumn('quotations', 'created_by_id')) {
                $table->foreignId('created_by_id')->nullable()->after('customer_id')->constrained('users');
            }
            if (!Schema::hasColumn('quotations', 'quotation_remark')) {
                $table->text('quotation_remark')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $columns = ['shipping_type', 'valid_date', 'create_date',
                'service_term_origin', 'service_term_dest', 'remark',
                'contact', 'created_by_id', 'quotation_remark'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('quotations', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
