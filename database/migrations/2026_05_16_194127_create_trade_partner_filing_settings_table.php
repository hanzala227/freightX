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
        Schema::create('trade_partner_filing_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_partner_id')->constrained('trade_partners')->onDelete('cascade');
            $table->string('isf_submission_name')->nullable();
            $table->string('isf_submission_state')->nullable();
            $table->string('isf_zip_code')->nullable();
            $table->string('importer_code')->nullable();
            $table->string('importer_no')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_partner_filing_settings');
    }
};
