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
        Schema::create('trade_partner_commodities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_partner_id')->constrained('trade_partners')->onDelete('cascade');
            $table->text('description');
            $table->foreignId('package_unit_id')->nullable()->constrained('package_units');
            $table->string('hts_code')->nullable();
            $table->decimal('pcs', 15, 2)->default(0);
            $table->decimal('net_weight', 15, 3)->default(0);
            $table->string('net_weight_unit')->default('KG');
            $table->decimal('gross_weight', 15, 3)->default(0);
            $table->string('gross_weight_unit')->default('KG');
            $table->decimal('measurement', 15, 3)->default(0);
            $table->string('measurement_unit')->default('CBM');
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->text('details')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_partner_commodities');
    }
};
