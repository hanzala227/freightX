<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('truck_shipment_containers', function (Blueprint $table) {
            if (!Schema::hasColumn('truck_shipment_containers', 'container_type_id')) {
                $table->foreignId('container_type_id')->nullable()->constrained('container_types')->nullOnDelete()->after('tp_sz');
            }
        });
    }

    public function down(): void
    {
        Schema::table('truck_shipment_containers', function (Blueprint $table) {
            $table->dropForeign(['container_type_id']);
            $table->dropColumn('container_type_id');
        });
    }
};
