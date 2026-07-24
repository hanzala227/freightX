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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            
            $table->foreignId('bill_to_id')->constrained('trade_partners');
            $table->text('billing_address')->nullable();
            
            // Reference to shipment (Polymorphic)
            $table->string('invoiceable_type')->nullable();
            $table->unsignedBigInteger('invoiceable_id')->nullable();
            
            $table->foreignId('currency_id')->constrained('currencies');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('balance_amount', 15, 2)->default(0);
            
            $table->enum('status', ['DRAFT', 'POSTED', 'PAID', 'PARTIAL', 'VOID'])->default('DRAFT');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['invoiceable_type', 'invoiceable_id']);
            $table->index(['bill_to_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
