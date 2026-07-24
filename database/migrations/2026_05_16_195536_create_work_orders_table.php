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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('work_order_no')->unique();
            
            // Link to any shipment
            $table->string('workable_type');
            $table->unsignedBigInteger('workable_id');
            
            $table->foreignId('vendor_id')->nullable()->constrained('trade_partners');
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            
            $table->string('subject')->nullable();
            $table->text('instructions')->nullable();
            
            $table->enum('status', ['PENDING', 'IN_PROGRESS', 'COMPLETED', 'CANCELLED'])->default('PENDING');
            
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['workable_type', 'workable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
