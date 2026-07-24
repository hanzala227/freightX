<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Office;
use App\Models\Port;
use App\Models\Vessel;
use App\Models\ContainerType;
use App\Models\Incoterm;
use App\Models\PackageUnit;
use App\Models\ServiceTerm;
use App\Models\TradePartner;
use App\Models\Quotation;
use App\Models\Charge;
use App\Models\OceanImport;
use App\Models\OceanImportHbl;
use App\Models\OceanImportContainer;
use App\Models\OceanImportCharge;
use App\Models\OceanExport;
use App\Models\OceanExportHbl;
use App\Models\AirImport;
use App\Models\AirImportHbl;
use App\Models\AirExport;
use App\Models\AirExportHbl;
use App\Models\TruckShipment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $skipTables = ['users', 'migrations', 'cache', 'cache_locks', 'sessions', 'password_reset_tokens', 'personal_access_tokens'];

        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        $key = "Tables_in_{$dbName}";

        foreach ($tables as $table) {
            $name = $table->$key;
            if (!in_array($name, $skipTables)) {
                DB::table($name)->truncate();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Demo Operator',
                'email' => 'operator@fms.com',
                'password' => Hash::make('password'),
            ]);
        }

        // === MASTER DATA ===
        Country::insertOrIgnore([
            ['code' => 'USA', 'name' => 'United States', 'iso_alpha2' => 'US'],
            ['code' => 'CHN', 'name' => 'China', 'iso_alpha2' => 'CN'],
            ['code' => 'CAN', 'name' => 'Canada', 'iso_alpha2' => 'CA'],
            ['code' => 'DEU', 'name' => 'Germany', 'iso_alpha2' => 'DE'],
            ['code' => 'NLD', 'name' => 'Netherlands', 'iso_alpha2' => 'NL'],
            ['code' => 'SGP', 'name' => 'Singapore', 'iso_alpha2' => 'SG'],
            ['code' => 'GBR', 'name' => 'United Kingdom', 'iso_alpha2' => 'GB'],
            ['code' => 'JPN', 'name' => 'Japan', 'iso_alpha2' => 'JP'],
            ['code' => 'KOR', 'name' => 'South Korea', 'iso_alpha2' => 'KR'],
            ['code' => 'VNM', 'name' => 'Vietnam', 'iso_alpha2' => 'VN'],
        ]);

        // Currencies
        Currency::insertOrIgnore([
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$'],
            ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => '$'],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£'],
            ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥'],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥'],
        ]);

        // Offices
        Office::insertOrIgnore([
            ['code' => 'MEO', 'name' => 'Main Export Office'],
            ['code' => 'LAX', 'name' => 'Los Angeles Branch'],
            ['code' => 'YVR', 'name' => 'Vancouver Office'],
        ]);

        // Ports
        Port::insertOrIgnore([
            ['code' => 'CNSHA', 'name' => 'Shanghai', 'country_id' => 2, 'type' => 'SEA'],
            ['code' => 'USLAX', 'name' => 'Los Angeles', 'country_id' => 1, 'type' => 'SEA'],
            ['code' => 'USNYC', 'name' => 'New York', 'country_id' => 1, 'type' => 'SEA'],
            ['code' => 'CAVAN', 'name' => 'Vancouver', 'country_id' => 3, 'type' => 'SEA'],
            ['code' => 'NLRTM', 'name' => 'Rotterdam', 'country_id' => 5, 'type' => 'SEA'],
            ['code' => 'DEHAM', 'name' => 'Hamburg', 'country_id' => 4, 'type' => 'SEA'],
            ['code' => 'SGSIN', 'name' => 'Singapore', 'country_id' => 6, 'type' => 'SEA'],
            ['code' => 'KRPUS', 'name' => 'Busan', 'country_id' => 9, 'type' => 'SEA'],
            ['code' => 'VNSGN', 'name' => 'Ho Chi Minh', 'country_id' => 10, 'type' => 'SEA'],
        ]);

        // Vessels
        Vessel::insertOrIgnore([
            ['name' => 'MSC AURORA', 'imo_no' => '9888123'],
            ['name' => 'EVER GIVEN', 'imo_no' => '9811000'],
            ['name' => 'CMA CGM BOUGAINVILLE', 'imo_no' => '9776418'],
            ['name' => 'OOCL BERLIN', 'imo_no' => '9776171'],
        ]);

        // Container Types
        ContainerType::insertOrIgnore([
            ['code' => '20GP', 'name' => '20 General Purpose'],
            ['code' => '40GP', 'name' => '40 General Purpose'],
            ['code' => '40HC', 'name' => '40 High Cube'],
            ['code' => '45HC', 'name' => '45 High Cube'],
        ]);

        // Package Units
        PackageUnit::insertOrIgnore([
            ['code' => 'PCS', 'name' => 'Pieces'],
            ['code' => 'CTN', 'name' => 'Cartons'],
            ['code' => 'PLT', 'name' => 'Pallets'],
            ['code' => 'PKG', 'name' => 'Packages'],
        ]);

        // Incoterms
        Incoterm::insertOrIgnore([
            ['code' => 'EXW', 'name' => 'Ex Works'],
            ['code' => 'FOB', 'name' => 'Free On Board'],
            ['code' => 'CIF', 'name' => 'Cost, Insurance and Freight'],
            ['code' => 'DAP', 'name' => 'Delivered at Place'],
        ]);

        // Service Terms
        ServiceTerm::insertOrIgnore([
            ['code' => 'CY', 'name' => 'Container Yard to Container Yard'],
            ['code' => 'CFS', 'name' => 'Container Yard to CFS'],
            ['code' => 'DOOR', 'name' => 'Door to Door'],
        ]);

        $currencies = Currency::pluck('id', 'code');
        $ports = Port::pluck('id', 'code');
        $incoterms = Incoterm::pluck('id', 'code');

        // === TRADE PARTNERS ===
        $customer = TradePartner::create([
            'type' => 'CLIENT',
            'code' => 'CUST001',
            'name' => 'ABC Trading Co., Ltd',
            'country_id' => 1,
            'status' => 'BUSINESS',
            'sales_person_id' => $user->id,
        ]);
        TradePartner::create([
            'type' => 'CLIENT',
            'code' => 'CUST002',
            'name' => 'XYZ Manufacturing Inc',
            'country_id' => 3,
            'status' => 'BUSINESS',
        ]);
        $carrier = TradePartner::create([
            'type' => 'CARRIER',
            'code' => 'MSC',
            'name' => 'MSC Mediterranean Shipping Co',
            'country_id' => 1,
            'status' => 'BUSINESS',
        ]);
        $agent = TradePartner::create([
            'type' => 'AGENT',
            'code' => 'AGT001',
            'name' => 'Global Freight Solutions Ltd',
            'country_id' => 6,
            'status' => 'BUSINESS',
        ]);
        TradePartner::create([
            'type' => 'WAREHOUSE',
            'code' => 'CY-LAX',
            'name' => 'LA Container Yard',
            'country_id' => 1,
            'status' => 'BUSINESS',
        ]);
        TradePartner::create([
            'type' => 'WAREHOUSE',
            'code' => 'CFS-LAX',
            'name' => 'LA CFS Terminal',
            'country_id' => 1,
            'status' => 'BUSINESS',
        ]);
        TradePartner::create([
            'type' => 'WAREHOUSE',
            'code' => 'WH-LAX01',
            'name' => 'LA Warehouse Services Inc',
            'country_id' => 1,
            'status' => 'BUSINESS',
        ]);

        // === QUOTATIONS WITH CHARGES ===
        $quote = Quotation::create([
            'quote_no' => 'QT-000001',
            'quote_date' => now()->subDays(5),
            'expiry_date' => now()->addDays(25),
            'customer_id' => $customer->id,
            'sales_person_id' => $user->id,
            'transport_mode' => 'OCEAN',
            'pol_id' => $ports['CNSHA'],
            'pod_id' => $ports['USLAX'],
            'incoterms_id' => $incoterms['CIF'] ?? 'CIF',
            'service_term' => 'CY',
            'status' => 'ACCEPTED',
            'internal_remark' => 'FCL shipment for ABC Trading',
        ]);

        Charge::insert([
            [
                'chargeable_type' => Quotation::class,
                'chargeable_id' => $quote->id,
                'type' => 'AR',
                'charge_code' => 'OFC',
                'charge_name' => 'Ocean Freight Charge',
                'pc' => 'PREPAID',
                'qty' => 1,
                'unit' => 'CONTAINER',
                'currency_id' => $currencies['USD'],
                'rate' => 2950.00,
                'amount' => 2950.00,
                'total_amount' => 2950.00,
            ],
            [
                'chargeable_type' => Quotation::class,
                'chargeable_id' => $quote->id,
                'type' => 'AR',
                'charge_code' => 'THC',
                'charge_name' => 'Terminal Handling Charge',
                'pc' => 'COLLECT',
                'qty' => 1,
                'unit' => 'CONTAINER',
                'currency_id' => $currencies['USD'],
                'rate' => 450.00,
                'amount' => 450.00,
                'total_amount' => 450.00,
            ],
            [
                'chargeable_type' => Quotation::class,
                'chargeable_id' => $quote->id,
                'type' => 'AR',
                'charge_code' => 'DOC',
                'charge_name' => 'Documentation Fee',
                'pc' => 'PREPAID',
                'qty' => 1,
                'unit' => 'B/L',
                'currency_id' => $currencies['USD'],
                'rate' => 85.00,
                'amount' => 85.00,
                'total_amount' => 85.00,
            ],
        ]);

        Quotation::create([
            'quote_no' => 'QT-000002',
            'quote_date' => now()->subDays(10),
            'expiry_date' => now()->addDays(20),
            'customer_id' => $customer->id,
            'sales_person_id' => $user->id,
            'transport_mode' => 'OCEAN',
            'pol_id' => $ports['KRPUS'],
            'pod_id' => $ports['CAVAN'],
            'incoterms_id' => $incoterms['FOB'] ?? 'FOB',
            'service_term' => 'CY',
            'status' => 'DRAFT',
        ]);

        // === OCEAN IMPORTS ===
        $oceanImport = OceanImport::create([
            'file_no' => 'MOI-25060001',
            'mbl_no' => 'MSCU1234567',
            'post_date' => now()->subDays(3),
            'office_id' => 1,
            'op_id' => $user->id,
            'carrier_id' => $carrier->id,
            'dm_customer_id' => $customer->id,
            'dm_sales_person_id' => $user->id,
            'forwarding_agent_id' => $agent->id,
            'oversea_agent_id' => $agent->id,
            'bl_type' => 'NORMAL',
            'cargo_type' => 'GENERAL CARGO',
            'ship_mode' => 'FCL',
            'vessel_id' => 1,
            'voyage' => 'V567E',
            'pol_id' => $ports['CNSHA'],
            'pod_id' => $ports['USLAX'],
            'etd' => now()->addDays(2),
            'eta' => now()->addDays(18),
            'freight_term' => 'PREPAID',
            'service_term_from_id' => 1,
            'service_term_to_id' => 1,
            'internal_remark' => 'Test ocean import shipment',
            'color' => '#E08283',
        ]);

        $hbl = OceanImportHbl::create([
            'ocean_import_id' => $oceanImport->id,
            'hbl_no' => 'HBL-ABC-001',
            'quotation_no' => 'QT-000001',
            'customer_id' => $customer->id,
            'shipper_id' => $customer->id,
            'consignee_id' => $customer->id,
            'sales_person_id' => $user->id,
            'pod_id' => $ports['USLAX'],
            'del_id' => $ports['USLAX'],
            'service_term' => 'CY',
            'ship_mode' => 'FCL',
            'cargo_type' => 'GENERAL CARGO',
        ]);

        OceanImportHbl::create([
            'ocean_import_id' => $oceanImport->id,
            'hbl_no' => 'HBL-ABC-002',
            'customer_id' => $customer->id,
            'sales_person_id' => $user->id,
            'pod_id' => $ports['USLAX'],
        ]);

        $container = OceanImportContainer::create([
            'ocean_import_id' => $oceanImport->id,
            'container_no' => 'MSCU9876543',
            'container_type_id' => 3,
            'seal_no' => 'SEAL8823',
            'pkg_qty' => 250,
            'pkg_unit_id' => 1,
            'weight_kg' => 18500.000,
            'measure_cbm' => 58.000,
        ]);

        OceanImportContainer::create([
            'ocean_import_id' => $oceanImport->id,
            'container_no' => 'MSCU9876544',
            'container_type_id' => 1,
            'seal_no' => 'SEAL8824',
            'pkg_qty' => 120,
            'weight_kg' => 9200.000,
            'measure_cbm' => 28.000,
        ]);

        DB::table('ocean_import_container_hbl')->insert([
            'container_id' => $container->id,
            'hbl_id' => $hbl->id,
        ]);

        OceanImportCharge::create([
            'ocean_import_id' => $oceanImport->id,
            'ocean_import_hbl_id' => $hbl->id,
            'type' => 'AR',
            'charge_code' => 'OFC',
            'charge_name' => 'Ocean Freight',
            'pc' => 'PREPAID',
            'qty' => 1,
            'unit' => 'CONTAINER',
            'currency_id' => $currencies['USD'],
            'rate' => 2950.00,
            'amount' => 2950.00,
            'total_amount' => 2950.00,
        ]);

        // === OCEAN EXPORTS ===
        $oe = OceanExport::create([
            'file_no' => 'MOE-25060001',
            'mbl_no' => 'OOLU8899001',
            'post_date' => now()->subDays(2),
            'office_id' => 1,
            'op_id' => $user->id,
            'carrier_id' => $carrier->id,
            'dm_customer_id' => $customer->id,
            'dm_sales_person_id' => $user->id,
            'pol_id' => $ports['USLAX'],
            'pod_id' => $ports['CNSHA'],
            'vessel_id' => 1,
            'voyage' => 'V890W',
            'etd' => now()->addDays(5),
            'eta' => now()->addDays(22),
            'ship_mode' => 'FCL',
            'cargo_type' => 'GENERAL CARGO',
        ]);

        OceanExportHbl::create([
            'ocean_export_id' => $oe->id,
            'hbl_no' => 'EXPHBL-001',
            'customer_id' => $customer->id,
            'sales_person_id' => $user->id,
            'cargo_type' => 'Electronics',
        ]);

        // === AIR IMPORTS ===
        $ai = AirImport::create([
            'file_no' => 'MAW-25060001',
            'mawb_no' => '176-12345678',
            'office_id' => 1,
            'op_id' => $user->id,
            'carrier_id' => $carrier->id,
            'dep_port_id' => $ports['SGSIN'],
            'dst_port_id' => $ports['USLAX'],
            'etd' => now()->addDays(1),
            'eta' => now()->addDays(2),
            'pkg_qty' => 50,
            'gross_weight' => 1200.000,
        ]);

        AirImportHbl::create([
            'air_import_id' => $ai->id,
            'hawb_no' => 'HAWB-001',
            'customer_id' => $customer->id,
            'shipper_id' => $customer->id,
            'consignee_id' => $customer->id,
            'pkg_qty' => 50,
            'gross_weight' => 1200.000,
            'commodity' => 'Computer Parts',
        ]);

        // === AIR EXPORTS ===
        $ae = AirExport::create([
            'file_no' => 'MAE-25060001',
            'mawb_no' => '176-87654321',
            'office_id' => 1,
            'op_id' => $user->id,
            'carrier_id' => $carrier->id,
            'dep_port_id' => $ports['USLAX'],
            'dst_port_id' => $ports['SGSIN'],
            'etd' => now()->addDays(3),
            'eta' => now()->addDays(4),
            'pkg_qty' => 30,
            'gross_weight' => 800.000,
        ]);

        AirExportHbl::create([
            'air_export_id' => $ae->id,
            'hawb_no' => 'EXPHW-001',
            'customer_id' => $customer->id,
            'sales_person_id' => $user->id,
            'commodity' => 'Medical Supplies',
        ]);

        // === TRUCK SHIPMENT ===
        TruckShipment::create([
            'file_no' => 'TRK-25060001',
            'office_id' => 1,
            'op_id' => $user->id,
            'customer_id' => $customer->id,
            'pol_id' => $ports['USLAX'],
            'pod_id' => $ports['USNYC'],
            'etd' => now()->addDays(1),
            'eta' => now()->addDays(3),
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Login: operator@fms.com / password');
    }
}
