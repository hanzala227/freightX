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
        Schema::create('warehouse_receiving_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_receiving_id')->constrained('warehouse_receivings')->cascadeOnDelete();
            $table->string('sku_no', 100)->nullable();
            $table->string('customer_po', 100)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('order_po_no', 100)->nullable();
            $table->decimal('order_qty', 12, 2)->default(0);
            $table->decimal('qty', 12, 2)->default(0);
            $table->string('qty_unit', 20)->nullable();
            $table->decimal('pack', 12, 2)->default(0);
            $table->string('pack_unit', 20)->nullable();
            $table->string('pallet', 100)->nullable();
            $table->decimal('weight_kg', 12, 2)->default(0);
            $table->decimal('measure_cbm', 12, 2)->default(0);
            $table->string('inventory', 100)->nullable();
            $table->timestamps();
        });

        Schema::table('warehouse_receivings', function (Blueprint $table) {
            $table->json('memos_data')->nullable()->after('internal_remark');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_receiving_items');
        Schema::table('warehouse_receivings', function (Blueprint $table) {
            $table->dropColumn('memos_data');
        });
    }
};
