<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_exports', function (Blueprint $table) {
            $table->decimal('buying_rate', 15, 4)->nullable()->after('volume');
            $table->decimal('selling_rate', 15, 4)->nullable()->after('buying_rate');
            $table->string('sales_type')->nullable()->after('is_ecommerce');
            $table->boolean('is_blocked')->default(false)->after('sales_type');
        });

        Schema::table('air_export_hbls', function (Blueprint $table) {
            $table->decimal('buying_rate', 15, 4)->nullable()->after('volume');
            $table->decimal('selling_rate', 15, 4)->nullable()->after('buying_rate');
            $table->string('sales_type')->nullable()->after('freight_term');
            $table->string('booking_no')->nullable()->after('hawb_no');
            $table->date('booking_date')->nullable()->after('booking_no');
            $table->string('quotation_no')->nullable()->after('booking_date');
            $table->foreignId('oversea_agent_id')->nullable()->constrained('trade_partners')->after('sales_person_id');
            $table->string('issuing_carrier')->nullable()->after('oversea_agent_id');
            $table->foreignId('bill_to')->nullable()->constrained('trade_partners')->after('issuing_carrier');
            $table->string('departure')->nullable()->after('bill_to');
            $table->string('destination')->nullable()->after('departure');
            $table->foreignId('cargo_pickup')->nullable()->constrained('trade_partners')->after('destination');
            $table->foreignId('delivery_to')->nullable()->constrained('trade_partners')->after('cargo_pickup');
            $table->string('cargo_type')->default('GENERAL CARGO')->after('delivery_to');
            $table->string('ship_type')->default('NORMAL')->after('cargo_type');
            $table->date('feta')->nullable()->after('ship_type');
            $table->string('itn_no')->nullable()->after('feta');
            $table->string('display_unit')->default('BOTH')->after('itn_no');
            $table->date('cargo_ready_date')->nullable()->after('display_unit');
            $table->string('dv_carriage')->nullable()->after('cargo_ready_date');
            $table->string('dv_customs')->nullable()->after('dv_carriage');
            $table->string('insurance')->nullable()->after('dv_customs');
            $table->string('other_charge_term')->nullable()->after('insurance');
            $table->text('mark')->nullable()->after('hbl_remark');
            $table->text('description')->nullable()->after('mark');
            $table->text('remark')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('air_exports', function (Blueprint $table) {
            $table->dropColumn(['buying_rate', 'selling_rate', 'sales_type', 'is_blocked']);
        });

        Schema::table('air_export_hbls', function (Blueprint $table) {
            $table->dropForeign(['oversea_agent_id']);
            $table->dropForeign(['bill_to']);
            $table->dropForeign(['cargo_pickup']);
            $table->dropForeign(['delivery_to']);
            $table->dropColumn([
                'buying_rate', 'selling_rate', 'sales_type',
                'booking_no', 'booking_date', 'quotation_no', 'oversea_agent_id',
                'issuing_carrier', 'bill_to', 'departure', 'destination',
                'cargo_pickup', 'delivery_to',
                'cargo_type', 'ship_type', 'feta', 'itn_no',
                'display_unit', 'cargo_ready_date',
                'dv_carriage', 'dv_customs', 'insurance', 'other_charge_term',
                'mark', 'description', 'remark'
            ]);
        });
    }
};
