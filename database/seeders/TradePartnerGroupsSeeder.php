<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountGroup;
use App\Models\CreditLimitGroup;

class TradePartnerGroupsSeeder extends Seeder
{
    public function run(): void
    {
        $accountGroups = [
            ['name' => 'DEFAULT', 'description' => 'Default Account Group'],
            ['name' => 'DOMESTIC', 'description' => 'Domestic Accounts'],
            ['name' => 'INTERNATIONAL', 'description' => 'International Accounts'],
            ['name' => 'GOVERNMENT', 'description' => 'Government Accounts'],
            ['name' => 'CORPORATE', 'description' => 'Corporate Accounts'],
            ['name' => 'SME', 'description' => 'Small and Medium Enterprise Accounts'],
        ];

        foreach ($accountGroups as $group) {
            AccountGroup::firstOrCreate(['name' => $group['name']], $group);
        }

        $creditLimitGroups = [
            ['name' => 'STANDARD', 'description' => 'Standard Credit Limit - Up to $50,000'],
            ['name' => 'PREMIUM', 'description' => 'Premium Credit Limit - Up to $200,000'],
            ['name' => 'UNLIMITED', 'description' => 'Unlimited Credit Limit'],
            ['name' => 'RESTRICTED', 'description' => 'Restricted - COD only'],
            ['name' => 'GOLD', 'description' => 'Gold Tier - Up to $500,000'],
        ];

        foreach ($creditLimitGroups as $group) {
            CreditLimitGroup::firstOrCreate(['name' => $group['name']], $group);
        }
    }
}
