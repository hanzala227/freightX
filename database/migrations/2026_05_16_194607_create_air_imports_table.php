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
        Schema::create('air_imports', function (Blueprint $table) {
            $table->id();
            $table->string('file_no')->unique();
            $table->string('mawb_no')->nullable();
            $table->date('post_date')->nullable();
            
            // Core Parties
            $table->foreignId('office_id')->nullable()->constrained('offices');
            $table->foreignId('op_id')->nullable()->constrained('users');
            $table->foreignId('forwarding_agent_id')->nullable()->constrained('trade_partners');
            $table->foreignId('oversea_agent_id')->nullable()->constrained('trade_partners');
            $table->foreignId('carrier_id')->nullable()->constrained('trade_partners');
            $table->foreignId('acct_carrier_id')->nullable()->constrained('trade_partners');
            
            // Flight Details
            $table->string('flight_no')->nullable();
            $table->foreignId('dep_port_id')->nullable()->constrained('ports'); // Departure Port
            $table->foreignId('dst_port_id')->nullable()->constrained('ports'); // Destination Port
            $table->date('etd')->nullable();
            $table->date('eta')->nullable();
            $table->date('atd')->nullable();
            $table->date('ata')->nullable();
            
            // Quantities (MAWB Total)
            $table->decimal('pkg_qty', 15, 2)->default(0);
            $table->foreignId('pkg_unit_id')->nullable()->constrained('package_units');
            $table->decimal('gross_weight', 15, 3)->default(0);
            $table->string('weight_unit')->default('KG');
            $table->decimal('chargeable_weight', 15, 3)->default(0);
            $table->decimal('volume', 15, 4)->default(0);
            
            $table->string('freight_term')->nullable();
            $table->boolean('is_ecommerce')->default(false);
            $table->text('internal_remark')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('air_imports');
    }
};
