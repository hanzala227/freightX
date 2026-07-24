<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounting_block_history', function (Blueprint $table) {
            $table->id();
            $table->string('program'); // Accounting Block, Block Maintenance
            $table->boolean('is_blocked')->default(true);
            $table->string('block_type')->nullable(); // SHIPMENT, etc.
            $table->string('ref_no')->nullable();
            $table->date('block_date')->nullable();
            $table->unsignedBigInteger('office_id')->nullable();
            $table->unsignedBigInteger('execute_by')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->unsignedBigInteger('record_id')->nullable();
            $table->string('record_table')->nullable();
            $table->timestamps();

            $table->foreign('office_id')->references('id')->on('offices')->nullOnDelete();
            $table->foreign('execute_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounting_block_history');
    }
};
