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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quote_no')->unique();
            $table->date('quote_date')->nullable();
            $table->date('expiry_date')->nullable();
            
            $table->foreignId('customer_id')->nullable()->constrained('trade_partners');
            $table->foreignId('sales_person_id')->nullable()->constrained('users');
            
            $table->string('transport_mode')->nullable(); // OCEAN, AIR, TRUCK
            $table->foreignId('pol_id')->nullable()->constrained('ports');
            $table->foreignId('pod_id')->nullable()->constrained('ports');
            
            $table->string('incoterms_id')->nullable();
            $table->string('service_term')->nullable();
            
            $table->enum('status', ['DRAFT', 'SENT', 'ACCEPTED', 'REJECTED', 'EXPIRED'])->default('DRAFT');
            
            $table->text('internal_remark')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['quote_no', 'customer_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
