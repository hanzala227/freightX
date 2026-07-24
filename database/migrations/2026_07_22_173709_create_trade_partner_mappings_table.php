<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_partner_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('target')->nullable();
            $table->string('status')->nullable();
            $table->string('sender_id')->nullable();
            $table->string('key')->nullable();
            $table->string('init_target_code')->nullable();
            $table->foreignId('trade_partner_id')->nullable()->constrained('trade_partners')->nullOnDelete();
            $table->string('target_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_partner_mappings');
    }
};
