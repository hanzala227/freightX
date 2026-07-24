<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Currency;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        $usd = Currency::where('code', 'USD')->value('id');
        $cad = Currency::where('code', 'CAD')->value('id');

        $banks = [
            ['name' => 'INTOGLO TRIAL BANK',     'account_no' => 'ITB-001',    'bank_name' => 'Intoglo Bank',       'currency_id' => $usd, 'opening_balance' => 50000, 'status' => 'active', 'type' => 'Bank'],
            ['name' => 'Chequing Account-CAD',    'account_no' => 'CHQ-001',    'bank_name' => 'Royal Bank',          'currency_id' => $cad, 'opening_balance' => 25000, 'status' => 'active', 'type' => 'Bank'],
            ['name' => 'WMX_BANK',                'account_no' => 'WMX-001',    'bank_name' => 'WMX Financial',       'currency_id' => $usd, 'opening_balance' => 10000, 'status' => 'active', 'type' => 'Bank'],
            ['name' => 'Bank15',                  'account_no' => 'B15-001',    'bank_name' => 'National Bank',       'currency_id' => $usd, 'opening_balance' => 75000, 'status' => 'active', 'type' => 'Bank'],
            ['name' => 'Bank16',                  'account_no' => 'B16-001',    'bank_name' => 'City National Bank',  'currency_id' => $usd, 'opening_balance' => 32000, 'status' => 'active', 'type' => 'Bank'],
            ['name' => 'Bank17',                  'account_no' => 'B17-001',    'bank_name' => 'Trade Bank',          'currency_id' => $usd, 'opening_balance' => 18000, 'status' => 'active', 'type' => 'Bank'],
            ['name' => 'Bank18',                  'account_no' => 'B18-001',    'bank_name' => 'Pacific Bank',        'currency_id' => $usd, 'opening_balance' => 45000, 'status' => 'inactive', 'type' => 'Bank'],
            ['name' => 'Bank19',                  'account_no' => 'B19-001',    'bank_name' => 'Atlantic Bank',       'currency_id' => $usd, 'opening_balance' => 22000, 'status' => 'active', 'type' => 'Bank'],
            ['name' => 'Bank2',                   'account_no' => 'B02-001',    'bank_name' => 'Merchant Bank',       'currency_id' => $usd, 'opening_balance' => 12000, 'status' => 'active', 'type' => 'Book'],
            ['name' => 'Bank3',                   'account_no' => 'B03-001',    'bank_name' => 'Commerce Bank',       'currency_id' => $usd, 'opening_balance' => 28000, 'status' => 'active', 'type' => 'Book'],
            ['name' => 'Bank4',                   'account_no' => 'B04-001',    'bank_name' => 'Heritage Bank',       'currency_id' => $usd, 'opening_balance' => 15000, 'status' => 'active', 'type' => 'Book'],
            ['name' => 'Bank5',                   'account_no' => 'B05-001',    'bank_name' => 'First Federal Bank',  'currency_id' => $usd, 'opening_balance' => 33000, 'status' => 'active', 'type' => 'Book'],
            ['name' => 'Bank6',                   'account_no' => 'B06-001',    'bank_name' => 'United Savings Bank', 'currency_id' => $usd, 'opening_balance' => 40000, 'status' => 'active', 'type' => 'Book'],
            ['name' => 'Bank7',                   'account_no' => 'B07-001',    'bank_name' => 'Central Trust Bank',  'currency_id' => $usd, 'opening_balance' => 55000, 'status' => 'inactive', 'type' => 'Book'],
            ['name' => 'Bank8',                   'account_no' => 'B08-001',    'bank_name' => 'Summit National Bank', 'currency_id' => $usd, 'opening_balance' => 19000, 'status' => 'active', 'type' => 'Book'],
            ['name' => 'Bank9',                   'account_no' => 'B09-001',    'bank_name' => 'Pioneer Bank',        'currency_id' => $usd, 'opening_balance' => 27000, 'status' => 'active', 'type' => 'Book'],
            ['name' => 'BANKQ',                   'account_no' => 'BQ-001',     'bank_name' => 'Q Bank International', 'currency_id' => $usd, 'opening_balance' => 60000, 'status' => 'active', 'type' => 'Bank'],
            ['name' => 'Deposit/Prepaid-others',  'account_no' => 'DPO-001',    'bank_name' => 'Prepaid Trust Account','currency_id' => $usd, 'opening_balance' => 8500, 'status' => 'active', 'type' => 'Bank'],
        ];

        foreach ($banks as $bank) {
            BankAccount::create($bank);
        }
    }
}
