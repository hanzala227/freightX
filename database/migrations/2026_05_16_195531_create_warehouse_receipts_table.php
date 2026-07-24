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
        Schema::create('warehouse_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no')->unique();
            $table->date('receipt_date')->nullable();
            
            $table->foreignId('warehouse_id')->nullable()->constrained('trade_partners');
            $table->foreignId('customer_id')->nullable()->constrained('trade_partners');
            $table->foreignId('shipper_id')->nullable()->constrained('trade_partners');
            
            $table->string('tracking_no')->nullable();
            $table->string('carrier_name')->nullable();
            
            $table->text('internal_remark')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_receipts');
    }
};
