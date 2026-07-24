<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('truck_shipments', function (Blueprint $table) {
            if (!Schema::hasColumn('truck_shipments', 'instruction_text')) {
                $table->text('instruction_text')->nullable()->after('internal_remark');
            }
        });
    }

    public function down(): void
    {
        Schema::table('truck_shipments', function (Blueprint $table) {
            if (Schema::hasColumn('truck_shipments', 'instruction_text')) {
                $table->dropColumn('instruction_text');
            }
        });
    }
};
