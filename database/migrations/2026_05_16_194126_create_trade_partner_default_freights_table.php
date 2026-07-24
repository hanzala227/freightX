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
        Schema::create('trade_partner_default_freights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_partner_id')->constrained('trade_partners')->onDelete('cascade');
            $table->string('transport_mode'); // ocean-import, air-export, etc.
            $table->string('section'); // Invoice, AP, DC Note
            $table->string('ship_mode')->nullable(); // FCL, LCL, All
            
            $table->string('freight_code')->nullable();
            $table->text('description')->nullable();
            $table->enum('pc', ['PREPAID', 'COLLECT'])->default('COLLECT');
            $table->string('type')->nullable(); // Our Sales, Our Cost
            $table->string('unit')->nullable(); // UNIT, KG, CBM
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
            $table->decimal('volume', 15, 4)->default(1.0000);
            $table->decimal('rate', 15, 2)->default(0.00);
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->decimal('agent_amount', 15, 2)->default(0.00);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['trade_partner_id', 'transport_mode'], 'tp_def_fr_tp_mode_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_partner_default_freights');
    }
};
