<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('truck_shipment_containers')) {
            Schema::create('truck_shipment_containers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('truck_shipment_id')->constrained('truck_shipments')->onDelete('cascade');
                $table->string('container_no')->nullable();
                $table->string('tp_sz')->nullable();
                $table->string('seal_no')->nullable();
                $table->string('pickup_no')->nullable();
                $table->decimal('pkg', 15, 2)->default(0);
                $table->decimal('weight', 15, 3)->default(0);
                $table->decimal('measurement', 15, 3)->default(0);
                $table->date('lfd')->nullable();
                $table->date('appointment')->nullable();
                $table->date('pickup_date')->nullable();
                $table->date('empty_return_date')->nullable();
                $table->string('pier_pass')->nullable();
                $table->string('po_no')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('truck_shipment_containers');
    }
};
