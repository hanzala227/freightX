<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('vessel_name')->nullable();
            $table->string('voyage')->nullable();
            $table->string('pol_name')->nullable();
            $table->string('pod_name')->nullable();
            $table->date('etd')->nullable();
            $table->date('eta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
