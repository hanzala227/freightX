<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\AccountingJournal;
use App\Models\JournalEntryLine;
use App\Models\GlAccount;
use App\Models\Office;
use App\Models\TradePartner;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JournalEntryController extends Controller
{
    public function index()
    {
        $offices      = Office::where('is_active', true)->orderBy('name')->get();
        $currencies   = Currency::orderBy('code')->get();
        $partners     = TradePartner::where('status', '!=', 'INACTIVE')->orderBy('name')->get();
        $glAccounts   = GlAccount::active()->orderBy('code')->get(['id', 'code', 'name']);
        $nextEntryNo  = AccountingJournal::generateEntryNo();

        return view('accounting.journal-entry', compact('offices', 'currencies', 'partners', 'glAccounts', 'nextEntryNo'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entry_date'    => 'required|date',
            'description'   => 'nullable|string',
            'remark'        => 'nullable|string',
            'office_id'     => 'nullable|exists:offices,id',
            'lines'         => 'required|array|min:1',
            'lines.*.gl_account_id'    => 'required|exists:gl_accounts,id',
            'lines.*.sub'               => 'nullable|string|max:50',
            'lines.*.entity_type'       => 'nullable|in:COMPANY,BANK',
            'lines.*.trade_partner_id'  => 'nullable|exists:trade_partners,id',
            'lines.*.description'       => 'nullable|string',
            'lines.*.office_id'         => 'nullable|exists:offices,id',
            'lines.*.local_debit'       => 'nullable|numeric|min:0',
            'lines.*.local_credit'      => 'nullable|numeric|min:0',
            'lines.*.currency_id'       => 'nullable|exists:currencies,id',
            'lines.*.foreign_rate'      => 'nullable|numeric|min:0',
            'lines.*.foreign_debit'     => 'nullable|numeric|min:0',
            'lines.*.foreign_credit'    => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $totalDebit  = collect($request->lines)->sum('local_debit');
        $totalCredit = collect($request->lines)->sum('local_credit');

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return response()->json(['success' => false, 'message' => 'Total debit must equal total credit.'], 422);
        }

        try {
            DB::beginTransaction();

            $entry = AccountingJournal::create([
                'entry_no'    => AccountingJournal::generateEntryNo(),
                'entry_date'  => $request->entry_date,
                'description' => $request->description,
                'remark'      => $request->remark,
                'office_id'   => $request->office_id,
                'created_by'  => auth()->id(),
                'status'      => 'POSTED',
            ]);

            foreach ($request->lines as $idx => $line) {
                $entry->lines()->create([
                    'line_no'          => $idx + 1,
                    'gl_account_id'    => $line['gl_account_id'],
                    'sub'              => $line['sub'] ?? null,
                    'entity_type'      => $line['entity_type'] ?? 'COMPANY',
                    'trade_partner_id' => $line['trade_partner_id'] ?? null,
                    'description'      => $line['description'] ?? null,
                    'office_id'        => $line['office_id'] ?? $request->office_id,
                    'local_debit'      => $line['local_debit'] ?? 0,
                    'local_credit'     => $line['local_credit'] ?? 0,
                    'currency_id'      => $line['currency_id'] ?? null,
                    'foreign_rate'     => $line['foreign_rate'] ?? 1,
                    'foreign_debit'    => $line['foreign_debit'] ?? 0,
                    'foreign_credit'   => $line['foreign_credit'] ?? 0,
                ]);
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Journal entry saved successfully.',
                'entry_id' => $entry->id,
                'entry_no' => $entry->entry_no,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error saving journal entry: ' . $e->getMessage()], 500);
        }
    }

    public function getGlAccounts(Request $request)
    {
        $term = $request->input('q', '');
        $accounts = GlAccount::active()
            ->when($term, fn($q) => $q->search($term))
            ->orderBy('code')
            ->limit(50)
            ->get(['id', 'code', 'name', 'type']);

        return response()->json($accounts);
    }

    public function getNextEntryNo()
    {
        return response()->json(['entry_no' => AccountingJournal::generateEntryNo()]);
    }

    public function list(Request $request)
    {
        $query = AccountingJournal::with(['office', 'creator'])
            ->when($request->search, function ($q) use ($request) {
                $term = $request->search;
                $q->where(function ($qq) use ($term) {
                    $qq->where('entry_no', 'LIKE', "%{$term}%")
                       ->orWhere('description', 'LIKE', "%{$term}%");
                });
            })
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->from_date, fn($q) => $q->where('entry_date', '>=', $request->from_date))
            ->when($request->to_date, fn($q) => $q->where('entry_date', '<=', $request->to_date))
            ->orderByDesc('entry_date')
            ->orderByDesc('id');

        $entries = $query->paginate(25);

        return response()->json($entries);
    }

    public function show($id)
    {
        $entry = AccountingJournal::with(['lines.glAccount', 'lines.tradePartner', 'lines.office', 'lines.currency', 'office', 'creator'])
            ->findOrFail($id);

        return response()->json($entry);
    }
}
