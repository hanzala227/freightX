<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TradePartner;
use App\Models\Port;
use App\Models\Office;
use App\Models\User;
use App\Models\Location;
use App\Models\PackageUnit;
use App\Models\ContainerType;
use App\Models\Warehouse;
use App\Models\Currency;
use App\Models\Quotation;
use Illuminate\Http\Request;

class DropdownOptionsController extends Controller
{
    public function agents(Request $request)
    {
        try {
            $agents = TradePartner::orderBy('name')
                ->select('id', 'name', 'type')
                ->get()
                ->map(function ($agent) {
                    // Determine roles based on type - using actual type codes from the codebase
                    $type = strtoupper($agent->type ?? '');
                    return [
                        'id' => $agent->id,
                        'name' => $agent->name,
                        'company_name' => $agent->name, // For compatibility
                        // Customer types: CS, CLIENT, CUSTOMER
                        'is_customer' => in_array($type, ['CS', 'CLIENT', 'CUSTOMER']),
                        // Shipper types: SH, KS, SHIPPER_KNOWN, SHIPPER_UNKNOWN
                        'is_shipper' => in_array($type, ['SH', 'KS', 'SHIPPER_KNOWN', 'SHIPPER_UNKNOWN']),
                        // Consignee types: CN, CONSIGNEE
                        'is_consignee' => in_array($type, ['CN', 'CONSIGNEE']),
                        // Oversea Agent types: PR, AGENT, FR, FORWARDER, FW, AG, OA
                        'is_oversea_agent' => in_array($type, ['PR', 'AGENT', 'FR', 'FORWARDER', 'FW', 'AG', 'OA']),
                        // Trucker types: TK, TRUCKER, TR
                        'is_trucker' => in_array($type, ['TK', 'TRUCKER', 'TR']),
                        // Vendor types: VR, VENDOR
                        'is_vendor' => in_array($type, ['VR', 'VENDOR']),
                    ];
                });

            return response()->json(['data' => $agents]);
        } catch (\Exception $e) {
            \Log::error("Error in agents endpoint: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage(), 'data' => []], 500);
        }
    }

    public function ports(Request $request)
    {
        $ports = Port::orderBy('name')
            ->select('id', 'name', 'code', 'country')
            ->get();

        return response()->json(['data' => $ports]);
    }

    public function offices(Request $request)
    {
        $offices = Office::where('is_active', true)
            ->orderBy('name')
            ->select('id', 'name', 'code')
            ->get();

        return response()->json(['data' => $offices]);
    }

    public function users(Request $request)
    {
        $users = User::orderBy('name')
            ->select('id', 'name', 'email')
            ->get();

        return response()->json(['data' => $users]);
    }

    public function locations(Request $request)
    {
        $locations = Location::orderBy('name')
            ->select('id', 'name', 'code', 'address')
            ->get();

        return response()->json(['data' => $locations]);
    }

    public function packageUnits(Request $request)
    {
        $units = PackageUnit::orderBy('name')
            ->select('id', 'name', 'code')
            ->get();

        return response()->json(['data' => $units]);
    }

    public function containerTypes(Request $request)
    {
        $types = ContainerType::orderBy('code')
            ->select('id', 'code', 'name', 'description')
            ->get();

        return response()->json(['data' => $types]);
    }

    public function warehouses(Request $request)
    {
        $warehouses = Warehouse::orderBy('name')
            ->select('id', 'name', 'code', 'address')
            ->get();

        return response()->json(['data' => $warehouses]);
    }

    public function currencies(Request $request)
    {
        $currencies = Currency::orderBy('code')
            ->select('id', 'code', 'name', 'symbol')
            ->get();

        return response()->json(['data' => $currencies]);
    }

    public function quotations(Request $request)
    {
        $quotations = Quotation::with(['customer:id,company_name'])
            ->orderBy('quote_no', 'desc')
            ->select('id', 'quote_no', 'customer_id', 'status')
            ->limit(100)
            ->get();

        return response()->json(['data' => $quotations]);
    }

    public function truckers(Request $request)
    {
        $truckers = TradePartner::where('is_trucker', true)
            ->orderBy('company_name')
            ->select('id', 'company_name', 'name')
            ->get()
            ->map(function ($trucker) {
                return [
                    'id' => $trucker->id,
                    'name' => $trucker->company_name ?? $trucker->name,
                ];
            });

        return response()->json(['data' => $truckers]);
    }

    public function vendors(Request $request)
    {
        $vendors = TradePartner::where('is_vendor', true)
            ->orderBy('company_name')
            ->select('id', 'company_name', 'name')
            ->get()
            ->map(function ($vendor) {
                return [
                    'id' => $vendor->id,
                    'name' => $vendor->company_name ?? $vendor->name,
                ];
            });

        return response()->json(['data' => $vendors]);
    }
}
