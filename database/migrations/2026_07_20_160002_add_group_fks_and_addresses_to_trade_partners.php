<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_partners', function (Blueprint $table) {
            // Add foreign keys for account group and credit limit group
            if (!Schema::hasColumn('trade_partners', 'account_group_id')) {
                $table->foreignId('account_group_id')->nullable()->after('alias')->constrained('account_groups')->nullOnDelete();
            }
            if (!Schema::hasColumn('trade_partners', 'credit_limit_group_id')) {
                $table->foreignId('credit_limit_group_id')->nullable()->after('account_group_id')->constrained('credit_limit_groups')->nullOnDelete();
            }
            // Add JSON column for additional print addresses
            if (!Schema::hasColumn('trade_partners', 'additional_addresses')) {
                $table->json('additional_addresses')->nullable()->after('print_address_show_contact');
            }
        });
    }

    public function down(): void
    {
        Schema::table('trade_partners', function (Blueprint $table) {
            $table->dropForeign(['account_group_id']);
            $table->dropForeign(['credit_limit_group_id']);
            $table->dropColumn(['account_group_id', 'credit_limit_group_id', 'additional_addresses']);
        });
    }
};
