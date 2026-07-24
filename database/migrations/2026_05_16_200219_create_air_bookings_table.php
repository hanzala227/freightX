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
        Schema::create('air_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no')->unique();
            $table->date('booking_date')->nullable();
            
            $table->foreignId('customer_id')->nullable()->constrained('trade_partners');
            $table->foreignId('carrier_id')->nullable()->constrained('trade_partners');
            
            $table->string('flight_no')->nullable();
            $table->foreignId('dep_port_id')->nullable()->constrained('ports');
            $table->foreignId('dst_port_id')->nullable()->constrained('ports');
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
        Schema::dropIfExists('air_bookings');
    }
};
