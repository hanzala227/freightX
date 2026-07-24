<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_receipt_id')->constrained('warehouse_receipts')->onDelete('cascade');
            
            // Dimensions (in cm)
            $table->decimal('length_cm', 10, 2)->nullable();
            $table->decimal('width_cm', 10, 2)->nullable();
            $table->decimal('height_cm', 10, 2)->nullable();
            $table->string('dimension', 50)->nullable();
            
            $table->decimal('pkg_qty', 10, 2)->nullable();
            $table->string('unit', 20)->nullable();
            $table->string('sku_po', 255)->nullable();
            $table->decimal('pallet_qty', 10, 2)->nullable();
            
            $table->decimal('weight_kg', 15, 3)->default(0);
            $table->decimal('weight_lbs', 15, 3)->default(0);
            $table->decimal('volume_cbm', 15, 6)->default(0);
            $table->decimal('volume_cft', 15, 6)->default(0);
            $table->decimal('act_weight_kg', 15, 3)->default(0);
            $table->decimal('act_weight_lbs', 15, 3)->default(0);
            
            $table->date('item_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_receipt_items');
    }
};
