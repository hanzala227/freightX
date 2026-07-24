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
        Schema::create('air_export_hbls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('air_export_id')->constrained('air_exports')->onDelete('cascade');
            $table->string('hawb_no')->unique();
            
            $table->foreignId('customer_id')->nullable()->constrained('trade_partners');
            $table->foreignId('shipper_id')->nullable()->constrained('trade_partners');
            $table->foreignId('consignee_id')->nullable()->constrained('trade_partners');
            $table->foreignId('notify_party_id')->nullable()->constrained('trade_partners');
            $table->foreignId('sales_person_id')->nullable()->constrained('users');
            
            // Quantities
            $table->decimal('pkg_qty', 15, 2)->default(0);
            $table->foreignId('pkg_unit_id')->nullable()->constrained('package_units');
            $table->decimal('gross_weight', 15, 3)->default(0);
            $table->decimal('chargeable_weight', 15, 3)->default(0);
            $table->decimal('volume', 15, 4)->default(0);
            
            $table->string('commodity')->nullable();
            $table->string('incoterms_id')->nullable();
            $table->string('freight_term')->nullable();
            
            $table->text('hbl_remark')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('air_export_hbls');
    }
};
