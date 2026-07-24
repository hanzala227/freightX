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
        Schema::create('trade_partner_parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_partner_id')->constrained('trade_partners')->onDelete('cascade');
            $table->enum('party_type', [
                'BILL_TO', 'CUSTOMS_BROKER', 'TRUCKER', 'PICKUP_DELIVERY_LOCATION', 
                'CONSIGNEE', 'SHIPPER', 'NOTIFY', 'VENDOR'
            ]);
            $table->foreignId('related_partner_id')->constrained('trade_partners')->onDelete('cascade');
            $table->boolean('is_default')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['trade_partner_id', 'party_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_partner_parties');
    }
};
