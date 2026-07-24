<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change payment_method from enum to string (MySQL requires raw ALTER for enum changes)
        DB::statement("ALTER TABLE payments MODIFY payment_method VARCHAR(50) DEFAULT 'BANK_TRANSFER' NOT NULL");

        Schema::table('payments', function (Blueprint $table) {
            // New fields for payment-make
            $table->string('payment_level', 20)->nullable()->after('payment_no');
            $table->boolean('show_party_on_check')->default(false)->after('payment_level');
            $table->string('check_no', 100)->nullable()->after('show_party_on_check');
            $table->date('clear_date')->nullable()->after('check_no');
            $table->date('void_date')->nullable()->after('clear_date');
            $table->foreignId('office_id')->nullable()->after('void_date')->constrained('offices')->nullOnDelete();
            $table->string('bank_name', 200)->nullable()->after('office_id');
            $table->foreignId('bank_currency_id')->nullable()->after('bank_name')->constrained('currencies')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['office_id']);
            $table->dropForeign(['bank_currency_id']);
            $table->dropColumn([
                'payment_level',
                'show_party_on_check',
                'check_no',
                'clear_date',
                'void_date',
                'office_id',
                'bank_name',
                'bank_currency_id',
            ]);
        });
    }
};
