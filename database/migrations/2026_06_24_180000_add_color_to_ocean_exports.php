<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocean_exports', function (Blueprint $table) {
            $table->string('color', 20)->nullable()->after('is_blocked');
        });
    }

    public function down(): void
    {
        Schema::table('ocean_exports', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
