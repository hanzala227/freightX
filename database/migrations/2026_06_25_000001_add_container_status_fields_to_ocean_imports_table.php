<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocean_import_containers', function (Blueprint $table) {
            $table->decimal('chassis_days', 8, 1)->default(0)->after('it_no');
            $table->boolean('is_customs_hold')->default(false)->after('chassis_days');
            $table->boolean('is_an_sent')->default(false)->after('is_customs_hold');
            $table->date('an_sent_date')->nullable()->after('is_an_sent');
            $table->boolean('is_do_sent')->default(false)->after('an_sent_date');
            $table->date('do_sent_date')->nullable()->after('is_do_sent');
        });
    }

    public function down(): void
    {
        Schema::table('ocean_import_containers', function (Blueprint $table) {
            $table->dropColumn(['chassis_days', 'is_customs_hold', 'is_an_sent', 'an_sent_date', 'is_do_sent', 'do_sent_date']);
        });
    }
};
