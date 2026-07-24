<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_import_hbls', function (Blueprint $table) {
            $table->json('sub_hawbs')->nullable()->after('frt_released_date');
            $table->json('commodities')->nullable()->after('sub_hawbs');
            $table->json('po_numbers')->nullable()->after('commodities');
            $table->text('mark_text')->nullable()->after('po_numbers');
            $table->text('description_text')->nullable()->after('mark_text');
        });
    }

    public function down(): void
    {
        Schema::table('air_import_hbls', function (Blueprint $table) {
            $table->dropColumn(['sub_hawbs', 'commodities', 'po_numbers', 'mark_text', 'description_text']);
        });
    }
};
