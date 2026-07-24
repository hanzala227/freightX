<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Change status from enum to string to support all form status values
            $table->string('status', 50)->default('Draft')->change();

            // Map form fields: shipping_type↔transport_mode, create_date↔quote_date, etc.
            // Add missing columns that the form sends
            $table->string('shipping_type', 50)->nullable()->after('transport_mode');
            $table->date('valid_date')->nullable()->after('quote_date');
            $table->date('create_date')->nullable()->after('valid_date');
            $table->foreignId('office_id')->nullable()->after('sales_person_id')->constrained('offices');
            $table->foreignId('agent_id')->nullable()->after('office_id')->constrained('trade_partners');
            $table->string('ship_mode', 20)->nullable()->after('agent_id');
            $table->string('service_term_origin', 10)->nullable()->after('service_term');
            $table->string('service_term_dest', 10)->nullable()->after('service_term_origin');
            $table->string('country_of_origin', 100)->nullable()->after('incoterms_id');
            $table->foreignId('op_id')->nullable()->after('sales_person_id')->constrained('users');
            $table->string('booking_no', 50)->nullable()->after('op_id');
            $table->string('po_no', 50)->nullable()->after('booking_no');
            $table->string('commodity', 255)->nullable()->after('po_no');
            $table->string('hts_code', 50)->nullable()->after('commodity');
            $table->decimal('pkg_qty', 10, 2)->nullable()->after('hts_code');
            $table->string('pkg_unit', 20)->nullable()->after('pkg_qty');
            $table->decimal('weight_kg', 10, 2)->nullable()->after('pkg_unit');
            $table->decimal('weight_lb', 10, 2)->nullable()->after('weight_kg');
            $table->decimal('volume_cbm', 10, 4)->nullable()->after('weight_lb');
            $table->decimal('volume_cft', 10, 4)->nullable()->after('volume_cbm');
            $table->text('description')->nullable()->after('internal_remark');
            $table->text('remark')->nullable()->after('description');
            $table->string('contact', 100)->nullable()->after('customer_id');
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_type', 'valid_date', 'create_date',
                'office_id', 'agent_id', 'ship_mode',
                'service_term_origin', 'service_term_dest', 'country_of_origin',
                'op_id', 'booking_no', 'po_no', 'commodity', 'hts_code',
                'pkg_qty', 'pkg_unit', 'weight_kg', 'weight_lb',
                'volume_cbm', 'volume_cft', 'description', 'remark', 'contact'
            ]);
        });
    }
};
