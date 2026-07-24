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
        Schema::create('trade_partner_memos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_partner_id')->constrained('trade_partners')->onDelete('cascade');
            $table->string('subject');
            $table->text('content')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_partner_memos');
    }
};
