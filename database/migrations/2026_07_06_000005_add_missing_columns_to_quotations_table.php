<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (!Schema::hasColumn('quotations', 'office_id')) {
                $table->foreignId('office_id')->nullable()->after('service_term')->constrained('offices');
            }
            if (!Schema::hasColumn('quotations', 'commodity')) {
                $table->text('commodity')->nullable()->after('office_id');
            }
            if (!Schema::hasColumn('quotations', 'pkg_qty')) {
                $table->decimal('pkg_qty', 15, 3)->nullable()->after('commodity');
            }
            if (!Schema::hasColumn('quotations', 'pkg_unit')) {
                $table->string('pkg_unit', 100)->nullable()->after('pkg_qty');
            }
            if (!Schema::hasColumn('quotations', 'weight_kg')) {
                $table->decimal('weight_kg', 15, 3)->nullable()->after('pkg_unit');
            }
            if (!Schema::hasColumn('quotations', 'weight_lb')) {
                $table->decimal('weight_lb', 15, 3)->nullable()->after('weight_kg');
            }
            if (!Schema::hasColumn('quotations', 'volume_cbm')) {
                $table->decimal('volume_cbm', 15, 3)->nullable()->after('weight_lb');
            }
            if (!Schema::hasColumn('quotations', 'volume_cft')) {
                $table->decimal('volume_cft', 15, 3)->nullable()->after('volume_cbm');
            }
            if (!Schema::hasColumn('quotations', 'description')) {
                $table->text('description')->nullable()->after('internal_remark');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $columns = [
                'office_id', 'commodity', 'pkg_qty', 'pkg_unit',
                'weight_kg', 'weight_lb', 'volume_cbm', 'volume_cft', 'description',
            ];

            $table->dropForeign(['office_id']);
            Schema::table('quotations', function (Blueprint $table) use ($columns) {
                foreach ($columns as $column) {
                    if (Schema::hasColumn('quotations', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        });
    }
};
