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
        Schema::create('ocean_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no')->unique();
            $table->date('booking_date')->nullable();
            
            $table->foreignId('customer_id')->nullable()->constrained('trade_partners');
            $table->foreignId('carrier_id')->nullable()->constrained('trade_partners');
            
            $table->foreignId('vessel_id')->nullable()->constrained('vessels');
            $table->string('voyage')->nullable();
            $table->foreignId('pol_id')->nullable()->constrained('ports');
            $table->foreignId('pod_id')->nullable()->constrained('ports');
            $table->date('etd')->nullable();
            $table->date('eta')->nullable();
            
            $table->enum('status', ['OPEN', 'CONFIRMED', 'CANCELLED', 'COMPLETED'])->default('OPEN');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocean_bookings');
    }
};
