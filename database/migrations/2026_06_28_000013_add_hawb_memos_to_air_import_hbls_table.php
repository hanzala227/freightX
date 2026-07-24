<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_import_hbls', function (Blueprint $table) {
            $table->json('hawb_memos')->nullable()->after('hbl_is_ecommerce');
        });
    }

    public function down(): void
    {
        Schema::table('air_import_hbls', function (Blueprint $table) {
            $table->dropColumn('hawb_memos');
        });
    }
};
