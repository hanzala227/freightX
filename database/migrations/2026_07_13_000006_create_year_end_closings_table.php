<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('year_end_closings', function (Blueprint $table) {
            $table->id();
            $table->integer('fiscal_year');
            $table->date('closing_date');
            $table->string('action'); // CLOSE, CANCEL
            $table->unsignedBigInteger('office_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->text('summary')->nullable();
            $table->integer('entries_created')->default(0);
            $table->timestamps();

            $table->foreign('office_id')->references('id')->on('offices')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unique(['fiscal_year', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('year_end_closings');
    }
};
