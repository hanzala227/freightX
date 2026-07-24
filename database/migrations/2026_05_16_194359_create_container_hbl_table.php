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
        Schema::create('ocean_import_container_hbl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->constrained('ocean_import_containers')->onDelete('cascade');
            $table->foreignId('hbl_id')->constrained('ocean_import_hbls')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['container_id', 'hbl_id'], 'cont_hbl_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocean_import_container_hbl');
    }
};
