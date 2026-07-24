<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE trade_partners MODIFY COLUMN type VARCHAR(30) NOT NULL DEFAULT 'CLIENT'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE trade_partners MODIFY COLUMN type VARCHAR(10) NOT NULL DEFAULT 'CLIENT'");
    }
};
