<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_imports', function (Blueprint $table) {
            $table->decimal('pkg_qty', 15, 2)->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('air_imports', function (Blueprint $table) {
            $table->decimal('pkg_qty', 15, 2)->default(0)->change();
        });
    }
};
