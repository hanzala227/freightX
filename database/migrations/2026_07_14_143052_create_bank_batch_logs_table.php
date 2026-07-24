<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_batch_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->nullOnDelete();
            $table->string('operation_type', 30);
            $table->date('post_date');
            $table->date('action_date')->nullable();
            $table->string('office', 100)->nullable();
            $table->string('bank_name', 200)->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->unsignedInteger('payment_count')->default(0);
            $table->json('payment_ids')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_batch_logs');
    }
};
