<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('charges', function (Blueprint $table) {
            $table->string('party')->nullable()->after('charge_name');
            $table->string('sal')->nullable()->after('party');
        });
    }

    public function down(): void
    {
        Schema::table('charges', function (Blueprint $table) {
            $table->dropColumn(['party', 'sal']);
        });
    }
};
