<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Office;
use App\Models\Port;
use App\Models\Vessel;
use App\Models\ContainerType;
use App\Models\Incoterm;
use App\Models\PackageUnit;
use App\Models\ServiceTerm;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Countries
        Country::insertOrIgnore([
            ['code' => 'USA', 'name' => 'United States', 'iso_alpha2' => 'US'],
            ['code' => 'CHN', 'name' => 'China', 'iso_alpha2' => 'CN'],
            ['code' => 'GBR', 'name' => 'United Kingdom', 'iso_alpha2' => 'GB'],
            ['code' => 'JPN', 'name' => 'Japan', 'iso_alpha2' => 'JP'],
            ['code' => 'DEU', 'name' => 'Germany', 'iso_alpha2' => 'DE'],
            ['code' => 'CAN', 'name' => 'Canada', 'iso_alpha2' => 'CA'],
            ['code' => 'AUS', 'name' => 'Australia', 'iso_alpha2' => 'AU'],
            ['code' => 'IND', 'name' => 'India', 'iso_alpha2' => 'IN'],
            ['code' => 'BRA', 'name' => 'Brazil', 'iso_alpha2' => 'BR'],
            ['code' => 'FRA', 'name' => 'France', 'iso_alpha2' => 'FR'],
            ['code' => 'NLD', 'name' => 'Netherlands', 'iso_alpha2' => 'NL'],
            ['code' => 'SGP', 'name' => 'Singapore', 'iso_alpha2' => 'SG'],
        ]);

        // Currencies
        Currency::insertOrIgnore([
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$'],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£'],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥'],
            ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥'],
            ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => '$'],
            ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => '$'],
        ]);

        // Offices
        Office::insertOrIgnore([
            ['code' => 'MEO', 'name' => 'Main Export Office', 'address' => 'New York'],
            ['code' => 'LAX', 'name' => 'Los Angeles Branch', 'address' => 'Los Angeles'],
            ['code' => 'SHA', 'name' => 'Shanghai Branch', 'address' => 'Shanghai'],
            ['code' => 'LHR', 'name' => 'London Hub', 'address' => 'London'],
            ['code' => 'TYO', 'name' => 'Tokyo Office', 'address' => 'Tokyo'],
        ]);

        // Ports
        Port::insertOrIgnore([
            ['code' => 'CNSHA', 'name' => 'Shanghai Port', 'country_id' => 2],
            ['code' => 'USLAX', 'name' => 'Los Angeles Port', 'country_id' => 1],
            ['code' => 'USLGB', 'name' => 'Long Beach', 'country_id' => 1],
            ['code' => 'USNYC', 'name' => 'New York', 'country_id' => 1],
            ['code' => 'CNNGB', 'name' => 'Ningbo', 'country_id' => 2],
            ['code' => 'CNSZX', 'name' => 'Shenzhen', 'country_id' => 2],
            ['code' => 'JPTYO', 'name' => 'Tokyo', 'country_id' => 4],
            ['code' => 'GBFEL', 'name' => 'Felixstowe', 'country_id' => 3],
            ['code' => 'NLRTM', 'name' => 'Rotterdam', 'country_id' => 5], // 5 doesn't exist, use 1 or add NL
            ['code' => 'DEHAM', 'name' => 'Hamburg', 'country_id' => 5],
            ['code' => 'SGSIN', 'name' => 'Singapore', 'country_id' => 1],
        ]);

        // Vessels
        Vessel::insertOrIgnore([
            ['name' => 'MSC AURORA', 'imo_no' => '9888'],
            ['name' => 'EVER GIVEN', 'imo_no' => '9811000'],
            ['name' => 'CMA CGM ANTOINE DE SAINT EXUPERY', 'imo_no' => '9776418'],
            ['name' => 'OOCL HONG KONG', 'imo_no' => '9776171'],
            ['name' => 'COSCO SHIPPING UNIVERSE', 'imo_no' => '9795610'],
            ['name' => 'HMM ALGECIRAS', 'imo_no' => '9863297'],
            ['name' => 'MADISON MAERSK', 'imo_no' => '9619933'],
        ]);

        // Container Types
        ContainerType::insertOrIgnore([
            ['code' => '20GP', 'name' => '20 General Purpose'],
            ['code' => '40GP', 'name' => '40 General Purpose'],
            ['code' => '40HC', 'name' => '40 High Cube'],
            ['code' => '45HC', 'name' => '45 High Cube'],
            ['code' => '20RF', 'name' => '20 Reefer'],
            ['code' => '40RF', 'name' => '40 Reefer'],
            ['code' => '20OT', 'name' => '20 Open Top'],
            ['code' => '40OT', 'name' => '40 Open Top'],
            ['code' => '20FR', 'name' => '20 Flat Rack'],
            ['code' => '40FR', 'name' => '40 Flat Rack'],
        ]);

        // Incoterms
        Incoterm::insertOrIgnore([
            ['code' => 'EXW', 'name' => 'Ex Works'],
            ['code' => 'FCA', 'name' => 'Free Carrier'],
            ['code' => 'FAS', 'name' => 'Free Alongside Ship'],
            ['code' => 'FOB', 'name' => 'Free On Board'],
            ['code' => 'CFR', 'name' => 'Cost and Freight'],
            ['code' => 'CIF', 'name' => 'Cost, Insurance and Freight'],
            ['code' => 'CPT', 'name' => 'Carriage Paid To'],
            ['code' => 'CIP', 'name' => 'Carriage and Insurance Paid To'],
            ['code' => 'DAP', 'name' => 'Delivered at Place'],
            ['code' => 'DPU', 'name' => 'Delivered at Place Unloaded'],
            ['code' => 'DDP', 'name' => 'Delivered Duty Paid'],
        ]);

        // Package Units
        PackageUnit::insertOrIgnore([
            ['code' => 'PCS', 'name' => 'Pieces'],
            ['code' => 'CTN', 'name' => 'Cartons'],
            ['code' => 'PLT', 'name' => 'Pallets'],
            ['code' => 'PKG', 'name' => 'Packages'],
            ['code' => 'BOX', 'name' => 'Boxes'],
            ['code' => 'BAG', 'name' => 'Bags'],
            ['code' => 'DRM', 'name' => 'Drums'],
            ['code' => 'ROL', 'name' => 'Rolls'],
            ['code' => 'BND', 'name' => 'Bundles'],
            ['code' => 'CAS', 'name' => 'Cases'],
            ['code' => 'UNT', 'name' => 'Units'],
        ]);

        // Service Terms
        ServiceTerm::insertOrIgnore([
            ['code' => 'CY', 'name' => 'Container Yard'],
            ['code' => 'CFS', 'name' => 'Container Freight Station'],
            ['code' => 'DOOR', 'name' => 'Door'],
            ['code' => 'FI', 'name' => 'Free In'],
            ['code' => 'FO', 'name' => 'Free Out'],
            ['code' => 'LI', 'name' => 'Liner In'],
            ['code' => 'LO', 'name' => 'Liner Out'],
        ]);
    }
}
