<?php

namespace App\Http\Controllers;

use App\Models\TradePartner;
use App\Models\TradePartnerMapping;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TradePartnerMappingController extends Controller
{
    /**
     * Display the mapping list page.
     */
    public function index()
    {
        $mappings = TradePartnerMapping::with('tradePartner')
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('target', 'like', "%{$search}%")
                      ->orWhere('sender_id', 'like', "%{$search}%")
                      ->orWhere('key', 'like', "%{$search}%")
                      ->orWhere('init_target_code', 'like', "%{$search}%")
                      ->orWhere('target_code', 'like', "%{$search}%");
                });
            })
            ->when(request('filter_target'), fn($q, $v) => $q->where('target', 'like', "%{$v}%"))
            ->when(request('filter_status'), fn($q, $v) => $q->where('status', $v))
            ->when(request('filter_sender_id'), fn($q, $v) => $q->where('sender_id', 'like', "%{$v}%"))
            ->when(request('filter_key'), fn($q, $v) => $q->where('key', 'like', "%{$v}%"))
            ->when(request('filter_tp'), fn($q, $v) => $q->where('trade_partner_id', $v))
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        $tradePartners = TradePartner::whereNotNull('name')->orderBy('name')->get(['id', 'name', 'code']);

        if (request()->ajax()) {
            return response()->json([
                'html' => view('trade-partner.partials.mapping-table-rows', compact('mappings'))->render(),
                'pagination' => view('vendor.pagination.custom', ['paginator' => $mappings])->render(),
                'first' => $mappings->firstItem() ?? 0,
                'last' => $mappings->lastItem() ?? 0,
                'total' => $mappings->total(),
            ]);
        }

        return view('trade-partner.mapping-list', compact('mappings', 'tradePartners'));
    }

    /**
     * Store a new mapping.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'target' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:100',
            'sender_id' => 'nullable|string|max:255',
            'key' => 'nullable|string|max:255',
            'init_target_code' => 'nullable|string|max:255',
            'trade_partner_id' => 'nullable|exists:trade_partners,id',
            'target_code' => 'nullable|string|max:255',
        ]);

        TradePartnerMapping::create($validated);

        return response()->json(['success' => true, 'message' => 'Mapping created successfully.']);
    }

    /**
     * Update an existing mapping.
     */
    public function update(Request $request, TradePartnerMapping $tradePartnerMapping)
    {
        $validated = $request->validate([
            'target' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:100',
            'sender_id' => 'nullable|string|max:255',
            'key' => 'nullable|string|max:255',
            'init_target_code' => 'nullable|string|max:255',
            'trade_partner_id' => 'nullable|exists:trade_partners,id',
            'target_code' => 'nullable|string|max:255',
        ]);

        $tradePartnerMapping->update($validated);

        return response()->json(['success' => true, 'message' => 'Mapping updated successfully.']);
    }

    /**
     * Delete a single mapping.
     */
    public function destroy(TradePartnerMapping $tradePartnerMapping)
    {
        $tradePartnerMapping->delete();

        return response()->json(['success' => true, 'message' => 'Mapping deleted.']);
    }

    /**
     * Bulk delete mappings.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:trade_partner_mappings,id',
        ]);

        TradePartnerMapping::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => count($request->ids) . ' mapping(s) deleted.']);
    }
}
