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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            
            // Link to any shipment or entity
            $table->string('documentable_type');
            $table->unsignedBigInteger('documentable_id');
            
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_extension')->nullable();
            $table->bigInteger('file_size')->nullable();
            
            $table->string('document_type')->nullable(); // MBL, HBL, Invoice, ID, etc.
            $table->text('description')->nullable();
            
            $table->foreignId('uploaded_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['documentable_type', 'documentable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
