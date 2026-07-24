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
        Schema::create('trade_partners', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['CLIENT', 'VENDOR', 'AGENT', 'CARRIER', 'TRUCKER', 'WAREHOUSE', 'OTHER'])->default('CLIENT');
            $table->string('code')->unique();
            $table->string('alias')->nullable();
            $table->string('name');
            $table->string('print_name')->nullable();
            $table->string('local_name')->nullable();
            $table->text('local_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries');
            
            // Operational Codes
            $table->string('iata_code')->nullable();
            $table->string('corporation_no')->nullable();
            $table->string('sita_profile')->nullable();
            $table->string('account_no')->nullable();
            $table->string('scac_code')->nullable();
            $table->string('firms_code')->nullable();
            $table->string('cbsa_carrier_code')->nullable();
            
            // Contact
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('url')->nullable();
            $table->string('email')->nullable();
            
            // Status & Office
            $table->enum('status', ['BUSINESS', 'PRE_BUSINESS', 'INACTIVE'])->default('BUSINESS');
            $table->foreignId('sales_office_id')->nullable()->constrained('offices');
            $table->foreignId('sales_person_id')->nullable()->constrained('users');
            $table->foreignId('cs_person_id')->nullable()->constrained('users'); // Customer Service / CP
            
            // Accounting Setting
            $table->text('billing_address')->nullable();
            $table->string('tax_id')->nullable();
            $table->enum('payment_type', ['COD', 'CREDIT', 'PREPAID'])->default('COD');
            $table->boolean('track_1099')->default(false);
            $table->boolean('bill_to_agent')->default(false);
            $table->string('clm_id')->nullable();
            $table->integer('credit_term_days')->default(0);
            $table->decimal('credit_limit', 15, 2)->default(0.00);
            $table->string('accountant_name')->nullable();
            
            // Bank Details
            $table->string('bank_account_name_1')->nullable();
            $table->string('bank_account_no_1')->nullable();
            $table->foreignId('bank_currency_1_id')->nullable()->constrained('currencies');
            $table->string('bank_account_name_2')->nullable();
            $table->string('bank_account_no_2')->nullable();
            $table->foreignId('bank_currency_2_id')->nullable()->constrained('currencies');
            
            $table->decimal('profit_share_percent', 5, 2)->default(0.00);
            $table->json('popup_tips')->nullable(); // For flags like 'Door to Door', 'Bad Customer'
            
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['type', 'code', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_partners');
    }
};
