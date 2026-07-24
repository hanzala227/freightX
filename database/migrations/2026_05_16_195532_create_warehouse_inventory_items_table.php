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
        Schema::create('warehouse_inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('trade_partners');
            $table->string('sku')->nullable();
            $table->string('item_name');
            $table->text('description')->nullable();
            
            $table->decimal('on_hand_qty', 15, 2)->default(0);
            $table->decimal('available_qty', 15, 2)->default(0);
            $table->foreignId('unit_id')->nullable()->constrained('package_units');
            
            $table->decimal('weight_kg', 15, 3)->default(0);
            $table->decimal('volume_cbm', 15, 3)->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['warehouse_id', 'sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_inventory_items');
    }
};
