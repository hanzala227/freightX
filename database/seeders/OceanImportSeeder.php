<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OceanImport;
use App\Models\OceanImportHbl;
use App\Models\OceanImportContainer;
use App\Models\User;
use App\Models\Office;
use App\Models\Port;
use App\Models\Vessel;
use App\Models\TradePartner;
use App\Models\ContainerType;
use App\Models\ServiceTerm;
use Illuminate\Support\Str;

class OceanImportSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id')->toArray();
        $offices = Office::pluck('id')->toArray();
        $ports = Port::pluck('id')->toArray();
        $vessels = Vessel::pluck('id')->toArray();
        $agents = TradePartner::pluck('id')->toArray();
        $containerTypes = ContainerType::pluck('id')->toArray();
        $serviceTerms = ServiceTerm::pluck('id')->toArray();

        if(empty($users) || empty($offices) || empty($ports) || empty($vessels) || empty($agents) || empty($containerTypes)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            $mbl = OceanImport::firstOrCreate(
                ['file_no' => 'MOI-250400' . str_pad($i, 2, '0', STR_PAD_LEFT)],
                [
                    'mbl_no' => 'MBL' . strtoupper(Str::random(8)),
                    'post_date' => now()->subDays(rand(1, 30)),
                    'office_id' => $offices[array_rand($offices)],
                    'op_id' => $users[array_rand($users)],
                    'carrier_id' => $agents[array_rand($agents)],
                    'forwarding_agent_id' => $agents[array_rand($agents)],
                    'oversea_agent_id' => $agents[array_rand($agents)],
                    'bl_type' => 'NORMAL',
                    'ship_mode' => 'FCL',
                    'vessel_id' => $vessels[array_rand($vessels)],
                    'voyage' => 'V' . rand(100, 999) . 'E',
                    'pol_id' => $ports[array_rand($ports)],
                    'pod_id' => $ports[array_rand($ports)],
                    'del_id' => $ports[array_rand($ports)],
                    'etd' => now()->addDays(rand(1, 10)),
                    'eta' => now()->addDays(rand(15, 30)),
                    'freight_term' => 'Prepaid',
                    'service_term_from_id' => $serviceTerms[array_rand($serviceTerms)],
                    'service_term_to_id' => $serviceTerms[array_rand($serviceTerms)],
                ]
            );

            if ($mbl->wasRecentlyCreated) {
                // Create HBLs
                for ($j = 1; $j <= rand(1, 3); $j++) {
                    OceanImportHbl::create([
                        'ocean_import_id' => $mbl->id,
                        'hbl_no' => 'HBL' . strtoupper(Str::random(8)),
                        'customer_id' => $agents[array_rand($agents)],
                        'shipper_id' => $agents[array_rand($agents)],
                        'consignee_id' => $agents[array_rand($agents)],
                        'notify_party_id' => $agents[array_rand($agents)],
                        'sales_person_id' => $users[array_rand($users)],
                        'customs_broker_id' => $agents[array_rand($agents)],
                        'date_of_issue' => now()->subDays(rand(1, 10)),
                        'pod_id' => $mbl->pod_id,
                        'del_id' => $mbl->del_id,
                        'fdest_id' => $mbl->fdest_id,
                    ]);
                }

                // Create Containers
                for ($k = 1; $k <= rand(1, 4); $k++) {
                    OceanImportContainer::create([
                        'ocean_import_id' => $mbl->id,
                        'container_no' => strtoupper(Str::random(4)) . rand(1000000, 9999999),
                        'container_type_id' => $containerTypes[array_rand($containerTypes)],
                        'seal_no' => 'SEAL' . rand(1000, 9999),
                        'pkg_qty' => rand(10, 500),
                        'weight_kg' => rand(1000, 20000),
                        'measure_cbm' => rand(10, 60),
                    ]);
                }
            }
        }
    }
}
