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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_no')->unique();
            $table->date('payment_date');
            
            $table->foreignId('trade_partner_id')->constrained('trade_partners');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->decimal('amount', 15, 2)->default(0);
            
            $table->enum('payment_method', ['CASH', 'CHECK', 'BANK_TRANSFER', 'CREDIT_CARD'])->default('BANK_TRANSFER');
            $table->string('reference_no')->nullable();
            
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
