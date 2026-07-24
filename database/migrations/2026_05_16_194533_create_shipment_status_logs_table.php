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
        Schema::create('shipment_status_logs', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relation to shipments
            $table->string('shipment_type');
            $table->unsignedBigInteger('shipment_id');
            
            $table->string('status_code');
            $table->string('status_name');
            $table->text('details')->nullable();
            
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamp('event_time')->useCurrent();
            
            $table->timestamps();
            
            $table->index(['shipment_type', 'shipment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_status_logs');
    }
};
