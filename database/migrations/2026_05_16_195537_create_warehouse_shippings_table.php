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
        Schema::create('warehouse_shippings', function (Blueprint $table) {
            $table->id();
            $table->string('shipping_no')->unique();
            $table->date('shipping_date')->nullable();
            
            $table->foreignId('warehouse_id')->nullable()->constrained('trade_partners');
            $table->foreignId('customer_id')->nullable()->constrained('trade_partners');
            
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
        Schema::dropIfExists('warehouse_shippings');
    }
};
