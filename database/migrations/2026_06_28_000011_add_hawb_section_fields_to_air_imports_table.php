<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('air_imports', function (Blueprint $table) {
            $table->string('class_of_entry', 100)->nullable()->after('door_delivered_date');
            $table->string('cargo_released_to', 255)->nullable()->after('class_of_entry');
            $table->string('ship_type', 50)->nullable()->after('cargo_released_to');
        });
    }

    public function down(): void
    {
        Schema::table('air_imports', function (Blueprint $table) {
            $table->dropColumn(['class_of_entry', 'cargo_released_to', 'ship_type']);
        });
    }
};
