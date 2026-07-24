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
        Schema::create('trade_partner_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_partner_id')->constrained('trade_partners')->onDelete('cascade');
            $table->boolean('is_representative')->default(false);
            $table->string('email_name')->nullable();
            $table->string('title')->nullable();
            $table->string('division')->nullable();
            $table->string('cell_phone')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_partner_contacts');
    }
};
