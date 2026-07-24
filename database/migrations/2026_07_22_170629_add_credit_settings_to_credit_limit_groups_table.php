<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('credit_limit_groups', function (Blueprint $table) {
            if (!Schema::hasColumn('credit_limit_groups', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('description');
            }
            if (!Schema::hasColumn('credit_limit_groups', 'credit_term_unit')) {
                $table->string('credit_term_unit')->nullable()->after('payment_type');
            }
            if (!Schema::hasColumn('credit_limit_groups', 'credit_term_days')) {
                $table->integer('credit_term_days')->nullable()->after('credit_term_unit');
            }
            if (!Schema::hasColumn('credit_limit_groups', 'credit_limit')) {
                $table->decimal('credit_limit', 15, 2)->nullable()->after('credit_term_days');
            }
        });
    }

    public function down(): void
    {
        Schema::table('credit_limit_groups', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'credit_term_unit', 'credit_term_days', 'credit_limit']);
        });
    }
};
