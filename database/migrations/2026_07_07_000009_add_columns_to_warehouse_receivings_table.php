<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouse_receivings', function (Blueprint $table) {
            $table->foreignId('office_id')->nullable()->after('warehouse_receipt_id')->constrained('offices')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->after('office_id')->constrained('trade_partners')->nullOnDelete();
            $table->foreignId('bill_to_id')->nullable()->after('customer_id')->constrained('trade_partners')->nullOnDelete();
            $table->foreignId('ship_from_id')->nullable()->after('bill_to_id')->constrained('trade_partners')->nullOnDelete();
            $table->string('quotation_no', 100)->nullable()->after('ship_from_id');
            $table->string('bl_no', 100)->nullable()->after('quotation_no');
            $table->foreignId('trucker_id')->nullable()->after('bl_no')->constrained('trade_partners')->nullOnDelete();
            $table->string('container_no', 100)->nullable()->after('trucker_id');
            $table->date('post_date')->nullable()->after('container_no');
            $table->date('order_date')->nullable()->after('post_date');
            $table->date('expect_date')->nullable()->after('order_date');
            $table->date('expiration_date')->nullable()->after('expect_date');
            $table->string('status', 50)->default('Pre-Receiving')->after('expiration_date');
            $table->string('pallet', 100)->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('warehouse_receivings', function (Blueprint $table) {
            $table->dropForeign(['office_id']);
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['bill_to_id']);
            $table->dropForeign(['ship_from_id']);
            $table->dropForeign(['trucker_id']);
            $table->dropColumn([
                'office_id', 'customer_id', 'bill_to_id', 'ship_from_id',
                'quotation_no', 'bl_no', 'trucker_id', 'container_no',
                'post_date', 'order_date', 'expect_date', 'expiration_date',
                'status', 'pallet',
            ]);
        });
    }
};
