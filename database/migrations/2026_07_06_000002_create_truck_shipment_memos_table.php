<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('truck_shipment_memos')) {
            Schema::create('truck_shipment_memos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('truck_shipment_id')->constrained('truck_shipments')->onDelete('cascade');
                $table->string('subject');
                $table->text('content')->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('truck_shipment_memos');
    }
};
