<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_automobiles', function (Blueprint $table) {
            $table->id();
            $table->string('vin_no')->unique();
            $table->string('wh_receipt_no')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('received_date')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('trade_partners')->nullOnDelete();
            $table->string('maker')->nullable();
            $table->string('year', 10)->nullable();
            $table->string('model')->nullable();
            $table->string('engine_no')->nullable();
            $table->date('manufacture_date')->nullable();
            $table->boolean('title_received')->default(false);
            $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete();
            $table->string('color', 20)->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('internal_remark')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('vin_no');
            $table->index('wh_receipt_no');
            $table->index('maker');
            $table->index('is_blocked');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_automobiles');
    }
};
