<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('discount_pct', 5, 2)->default(0)->after('status');
            $table->decimal('tax_pct', 5, 2)->default(0)->after('discount_pct');
            $table->decimal('shipping_amount', 15, 2)->default(0)->after('tax_pct');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['discount_pct', 'tax_pct', 'shipping_amount']);
        });
    }
};
