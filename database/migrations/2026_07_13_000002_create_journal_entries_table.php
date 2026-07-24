<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_no', 30)->unique();
            $table->date('entry_date');
            $table->text('description')->nullable();
            $table->text('remark')->nullable();
            $table->foreignId('office_id')->nullable()->constrained('offices');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->enum('status', ['DRAFT', 'POSTED', 'VOIDED'])->default('DRAFT');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained()->cascadeOnDelete();
            $table->integer('line_no')->default(1);
            $table->foreignId('gl_account_id')->constrained('gl_accounts');
            $table->string('sub', 50)->nullable();
            $table->enum('entity_type', ['COMPANY', 'BANK'])->default('COMPANY');
            $table->foreignId('trade_partner_id')->nullable()->constrained('trade_partners');
            $table->text('description')->nullable();
            $table->foreignId('office_id')->nullable()->constrained('offices');
            $table->decimal('local_debit', 15, 2)->default(0);
            $table->decimal('local_credit', 15, 2)->default(0);
            $table->foreignId('currency_id')->nullable()->constrained('currencies');
            $table->decimal('foreign_rate', 15, 6)->default(1);
            $table->decimal('foreign_debit', 15, 2)->default(0);
            $table->decimal('foreign_credit', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entry_lines');
        Schema::dropIfExists('journal_entries');
    }
};
