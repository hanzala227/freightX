<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('type', 10)->nullable()->default('AR')->after('status');
            $table->foreignId('office_id')->nullable()->constrained('offices')->after('type');
            $table->foreignId('issued_by')->nullable()->constrained('users')->after('office_id');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn(['type', 'office_id', 'issued_by']);
        });
    }
};
