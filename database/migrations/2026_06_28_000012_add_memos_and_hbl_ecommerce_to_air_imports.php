<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_imports', function (Blueprint $table) {
            $table->json('memos')->nullable()->after('ship_type');
        });

        Schema::table('air_import_hbls', function (Blueprint $table) {
            $table->boolean('hbl_is_ecommerce')->default(false)->after('incoterms_id');
        });
    }

    public function down(): void
    {
        Schema::table('air_imports', function (Blueprint $table) {
            $table->dropColumn('memos');
        });

        Schema::table('air_import_hbls', function (Blueprint $table) {
            $table->dropColumn('hbl_is_ecommerce');
        });
    }
};
