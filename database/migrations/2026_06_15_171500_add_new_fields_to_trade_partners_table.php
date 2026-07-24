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
        Schema::table('trade_partners', function (Blueprint $table) {
            if (!Schema::hasColumn('trade_partners', 'credit_limit_group_name')) {
                $table->string('credit_limit_group_name')->nullable()->after('alias');
            }
            if (!Schema::hasColumn('trade_partners', 'aeo')) {
                $table->string('aeo')->nullable()->after('firms_code');
            }
            if (!Schema::hasColumn('trade_partners', 'credit_term_unit')) {
                $table->string('credit_term_unit')->default('Days')->after('credit_term_days');
            }
            if (!Schema::hasColumn('trade_partners', 'print_address_use_default')) {
                $table->boolean('print_address_use_default')->default(true)->after('print_name');
            }
            if (!Schema::hasColumn('trade_partners', 'print_address_show_name')) {
                $table->boolean('print_address_show_name')->default(true)->after('print_address_use_default');
            }
            if (!Schema::hasColumn('trade_partners', 'print_address_show_address')) {
                $table->boolean('print_address_show_address')->default(true)->after('print_address_show_name');
            }
            if (!Schema::hasColumn('trade_partners', 'print_address_show_contact')) {
                $table->boolean('print_address_show_contact')->default(true)->after('print_address_show_address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trade_partners', function (Blueprint $table) {
            $table->dropColumn([
                'credit_limit_group_name',
                'aeo',
                'credit_term_unit',
                'print_address_use_default',
                'print_address_show_name',
                'print_address_show_address',
                'print_address_show_contact'
            ]);
        });
    }
};
