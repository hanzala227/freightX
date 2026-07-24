<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quotation;
use App\Models\TradePartner;
use App\Models\User;
use App\Models\Port;
use Illuminate\Support\Str;

class QuotationSeeder extends Seeder
{
    public function run(): void
    {
        $customers = TradePartner::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();
        $ports = Port::pluck('id')->toArray();

        if (empty($customers) || empty($users) || empty($ports)) return;

        for ($i = 1; $i <= 10; $i++) {
            Quotation::create([
                'quote_no' => 'QT-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'quote_date' => now()->subDays(rand(1, 30)),
                'expiry_date' => now()->addDays(rand(10, 60)),
                'customer_id' => $customers[array_rand($customers)],
                'sales_person_id' => $users[array_rand($users)],
                'transport_mode' => 'OCEAN',
                'pol_id' => $ports[array_rand($ports)],
                'pod_id' => $ports[array_rand($ports)],
                'status' => ['DRAFT', 'SENT', 'ACCEPTED'][rand(0, 2)],
            ]);
        }
    }
}
