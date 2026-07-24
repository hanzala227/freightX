<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gl_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->enum('type', ['ASSET', 'LIABILITY', 'EQUITY', 'REVENUE', 'EXPENSE'])->default('ASSET');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $accounts = [
            ['code' => '10101', 'name' => 'OTHER ACCOUNTS RECEIVABLE', 'type' => 'ASSET', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '10102', 'name' => 'ACCOUNTS RECEIVABLE - TRADE', 'type' => 'ASSET', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '10201', 'name' => 'CASH ON HAND', 'type' => 'ASSET', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '10202', 'name' => 'CASH IN BANK - LOCAL', 'type' => 'ASSET', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '10203', 'name' => 'CASH IN BANK - FOREIGN', 'type' => 'ASSET', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '10301', 'name' => 'PREPAID EXPENSES', 'type' => 'ASSET', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '10302', 'name' => 'PREPAID INSURANCE', 'type' => 'ASSET', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '10401', 'name' => 'SECURITY DEPOSITS', 'type' => 'ASSET', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '10501', 'name' => 'FIXED ASSETS - EQUIPMENT', 'type' => 'ASSET', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '10502', 'name' => 'ACCUMULATED DEPRECIATION', 'type' => 'ASSET', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '20101', 'name' => 'ACCOUNTS PAYABLE - TRADE', 'type' => 'LIABILITY', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '20102', 'name' => 'ACCOUNTS PAYABLE - OTHER', 'type' => 'LIABILITY', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '20201', 'name' => 'ACCRUED EXPENSES', 'type' => 'LIABILITY', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '20202', 'name' => 'ACCRUED WAGES', 'type' => 'LIABILITY', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '20301', 'name' => 'TAXES PAYABLE', 'type' => 'LIABILITY', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '20302', 'name' => 'VAT PAYABLE', 'type' => 'LIABILITY', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '20401', 'name' => 'UNEARNED REVENUE', 'type' => 'LIABILITY', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '30101', 'name' => 'PAID-IN CAPITAL', 'type' => 'EQUITY', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '30102', 'name' => 'RETAINED EARNINGS', 'type' => 'EQUITY', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '30103', 'name' => 'CURRENT YEAR EARNINGS', 'type' => 'EQUITY', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '40101', 'name' => 'OCEAN FREIGHT REVENUE', 'type' => 'REVENUE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '40102', 'name' => 'AIR FREIGHT REVENUE', 'type' => 'REVENUE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '40103', 'name' => 'TRUCKING REVENUE', 'type' => 'REVENUE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '40104', 'name' => 'WAREHOUSING REVENUE', 'type' => 'REVENUE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '40105', 'name' => 'COMMISSION REVENUE', 'type' => 'REVENUE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '40106', 'name' => 'HANDLING FEE REVENUE', 'type' => 'REVENUE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '40107', 'name' => 'DOCUMENTATION FEE REVENUE', 'type' => 'REVENUE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '40108', 'name' => 'CUSTOMS BROKERAGE REVENUE', 'type' => 'REVENUE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '40109', 'name' => 'INSURANCE REVENUE', 'type' => 'REVENUE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '40110', 'name' => 'OTHER REVENUE', 'type' => 'REVENUE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50101', 'name' => 'OCEAN FREIGHT COST', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50102', 'name' => 'AIR FREIGHT COST', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50103', 'name' => 'TRUCKING COST', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50104', 'name' => 'WAREHOUSING COST', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50105', 'name' => 'CARRIER CHARGES', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50106', 'name' => 'CUSTOMS DUTIES & TAXES', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50107', 'name' => 'INSURANCE EXPENSE', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50108', 'name' => 'OFFICE RENT', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50109', 'name' => 'SALARIES & WAGES', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50110', 'name' => 'UTILITIES', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50111', 'name' => 'TELECOMMUNICATION', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50112', 'name' => 'TRAVEL & ENTERTAINMENT', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50113', 'name' => 'PROFESSIONAL FEES', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50114', 'name' => 'BANK CHARGES', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50115', 'name' => 'DEPRECIATION EXPENSE', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
            ['code' => '50116', 'name' => 'OTHER EXPENSE', 'type' => 'EXPENSE', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('gl_accounts')->insert($accounts);
    }

    public function down(): void
    {
        Schema::dropIfExists('gl_accounts');
    }
};
