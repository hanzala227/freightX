<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\AccountingJournal;
use Illuminate\Http\Request;

class GeneralJournalController extends Controller
{
    public function index(Request $request)
    {
        $query = AccountingJournal::with(['office', 'creator', 'lines']);

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('entry_no', 'LIKE', "%{$term}%")
                  ->orWhere('remark', 'LIKE', "%{$term}%")
                  ->orWhere('description', 'LIKE', "%{$term}%");
            });
        }
        if ($request->filled('from_date')) {
            $query->where('entry_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('entry_date', '<=', $request->to_date);
        }
        if ($request->filled('office_id')) {
            $query->where('office_id', $request->office_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $entries = $query->orderByDesc('entry_date')->orderByDesc('id')->paginate(15);

        return view('accounting.general-journal', compact('entries'));
    }

    public function printReport(Request $request)
    {
        $data = $this->getEntries($request);
        return view('accounting.general-journal-print', $data);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getEntries($request);
        $entries = $data['entries'];

        $filename = 'general-journal-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($entries) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['General Journal Report']);
            fputcsv($handle, ['Generated', now()->format('Y-m-d H:i:s')]);
            fputcsv($handle, []);

            fputcsv($handle, ['Post Date', 'Entry No', 'Seq', 'Remark', 'Debit', 'Credit', 'Type', 'Issued By', 'Office']);

            foreach ($entries as $e) {
                $totalDebit = $e->lines->sum('local_debit');
                $totalCredit = $e->lines->sum('local_credit');
                fputcsv($handle, [
                    $e->entry_date?->format('Y-m-d'),
                    $e->entry_no,
                    $e->id,
                    $e->remark ?? $e->description ?? '',
                    number_format($totalDebit, 2),
                    number_format($totalCredit, 2),
                    'Entry',
                    $e->creator?->name ?? '',
                    $e->office?->code ?? $e->office?->name ?? '',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No entries selected.']);
        }
        AccountingJournal::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => count($ids) . ' entry(ies) deleted.']);
    }

    private function getEntries(Request $request)
    {
        $query = AccountingJournal::with(['office', 'creator', 'lines']);

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('entry_no', 'LIKE', "%{$term}%")
                  ->orWhere('remark', 'LIKE', "%{$term}%")
                  ->orWhere('description', 'LIKE', "%{$term}%");
            });
        }
        if ($request->filled('from_date')) {
            $query->where('entry_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('entry_date', '<=', $request->to_date);
        }
        if ($request->filled('office_id')) {
            $query->where('office_id', $request->office_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $entries = $query->orderByDesc('entry_date')->orderByDesc('id')->get();
        return compact('entries');
    }
}
