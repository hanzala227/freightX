<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocean_imports', function (Blueprint $table) {
            $table->text('mark')->nullable()->after('internal_remark');
            $table->text('description')->nullable()->after('mark');
        });
    }

    public function down(): void
    {
        Schema::table('ocean_imports', function (Blueprint $table) {
            $table->dropColumn(['mark', 'description']);
        });
    }
};
