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
        Schema::create('truck_shipments', function (Blueprint $table) {
            $table->id();
            $table->string('file_no')->unique();
            $table->string('mbl_no')->nullable();
            $table->date('post_date')->nullable();
            
            $table->foreignId('office_id')->nullable()->constrained('offices');
            $table->foreignId('op_id')->nullable()->constrained('users');
            $table->foreignId('customer_id')->nullable()->constrained('trade_partners');
            $table->foreignId('shipper_id')->nullable()->constrained('trade_partners');
            $table->foreignId('consignee_id')->nullable()->constrained('trade_partners');
            $table->foreignId('trucker_id')->nullable()->constrained('trade_partners');
            
            $table->string('truck_no')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            
            $table->foreignId('pol_id')->nullable()->constrained('ports');
            $table->foreignId('pod_id')->nullable()->constrained('ports');
            $table->date('etd')->nullable();
            $table->date('eta')->nullable();
            
            $table->decimal('pkg_qty', 15, 2)->default(0);
            $table->foreignId('pkg_unit_id')->nullable()->constrained('package_units');
            $table->decimal('weight_kg', 15, 3)->default(0);
            $table->decimal('volume_cbm', 15, 3)->default(0);
            
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
        Schema::dropIfExists('truck_shipments');
    }
};
