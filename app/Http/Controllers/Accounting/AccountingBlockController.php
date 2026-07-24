<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\AccountingBlockDate;
use App\Models\AccountingBlockHistory;
use App\Models\Office;
use Illuminate\Http\Request;

class AccountingBlockController extends Controller
{
    public function index()
    {
        $offices = Office::where('is_active', true)->orderBy('name')->get();
        $lastBlockDates = AccountingBlockDate::with('office')
            ->orderByDesc('block_date')
            ->orderByDesc('id')
            ->get()
            ->unique('office_id');

        return view('accounting.accounting-block', compact('offices', 'lastBlockDates'));
    }

    public function apply(Request $request)
    {
        $request->validate([
            'office_id' => 'nullable|exists:offices,id',
            'action' => 'required|in:BLOCK,UNBLOCK',
            'block_date' => 'required|date',
        ]);

        AccountingBlockDate::create([
            'office_id' => $request->office_id,
            'block_date' => $request->block_date,
            'action' => $request->action,
            'created_by' => auth()->id(),
        ]);

        AccountingBlockHistory::create([
            'program' => 'Accounting Block',
            'is_blocked' => $request->action === 'BLOCK',
            'block_type' => null,
            'ref_no' => null,
            'block_date' => $request->block_date,
            'office_id' => $request->office_id,
            'execute_by' => auth()->id(),
            'executed_at' => now(),
        ]);

        $label = $request->action === 'BLOCK' ? 'blocked' : 'unblocked';
        $officeName = $request->office_id ? Office::find($request->office_id)?->name : 'All Offices';

        return response()->json([
            'success' => true,
            'message' => "Accounting data {$label} for {$officeName} before " . date('m-d-Y', strtotime($request->block_date)) . ".",
        ]);
    }
}
