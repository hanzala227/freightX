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
        Schema::create('charges', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relation to shipments (Ocean Import MBL/HBL, etc.)
            $table->string('chargeable_type');
            $table->unsignedBigInteger('chargeable_id');
            
            $table->enum('type', ['AR', 'AP', 'DC_NOTE'])->default('AR'); // Accounts Receivable, Accounts Payable
            $table->string('charge_code')->nullable();
            $table->string('charge_name');
            
            $table->foreignId('bill_to_id')->nullable()->constrained('trade_partners');
            $table->foreignId('vendor_id')->nullable()->constrained('trade_partners');
            
            $table->enum('pc', ['PREPAID', 'COLLECT'])->default('COLLECT');
            
            // Quantities & Rates
            $table->decimal('qty', 15, 3)->default(1);
            $table->string('unit')->default('UNIT');
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
            $table->decimal('rate', 15, 2)->default(0.00);
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->decimal('tax_percent', 5, 2)->default(0.00);
            $table->decimal('tax_amount', 15, 2)->default(0.00);
            $table->decimal('total_amount', 15, 2)->default(0.00);
            
            // Status
            $table->boolean('is_invoiced')->default(false);
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();
            
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['chargeable_type', 'chargeable_id']);
            $table->index(['bill_to_id', 'vendor_id']);
            $table->index(['type', 'invoice_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charges');
    }
};
