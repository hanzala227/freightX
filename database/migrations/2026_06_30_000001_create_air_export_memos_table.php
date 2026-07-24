<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('air_export_memos')) {
            Schema::create('air_export_memos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('air_export_id')->constrained('air_exports')->onDelete('cascade');
                $table->string('subject')->nullable();
                $table->text('body')->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('air_export_memos');
    }
};
