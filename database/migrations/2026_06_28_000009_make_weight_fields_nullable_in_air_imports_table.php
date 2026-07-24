<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_imports', function (Blueprint $table) {
            $table->decimal('gross_weight', 15, 2)->nullable()->default(null)->change();
            $table->decimal('chargeable_weight', 15, 2)->nullable()->default(null)->change();
            $table->decimal('volume', 15, 2)->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('air_imports', function (Blueprint $table) {
            $table->decimal('gross_weight', 15, 2)->default(0)->change();
            $table->decimal('chargeable_weight', 15, 2)->default(0)->change();
            $table->decimal('volume', 15, 2)->default(0)->change();
        });
    }
};
