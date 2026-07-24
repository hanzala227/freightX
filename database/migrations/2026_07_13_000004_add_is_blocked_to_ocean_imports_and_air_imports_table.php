<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocean_imports', function (Blueprint $table) {
            if (!Schema::hasColumn('ocean_imports', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false)->after('color');
            }
        });

        Schema::table('air_imports', function (Blueprint $table) {
            if (!Schema::hasColumn('air_imports', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false)->after('color');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ocean_imports', function (Blueprint $table) {
            $table->dropColumn('is_blocked');
        });

        Schema::table('air_imports', function (Blueprint $table) {
            $table->dropColumn('is_blocked');
        });
    }
};
