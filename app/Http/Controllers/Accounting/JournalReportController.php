<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\JournalEntryLine;
use App\Models\Currency;
use Illuminate\Http\Request;

class JournalReportController extends Controller
{
    public function index()
    {
        $offices = Office::where('is_active', true)->orderBy('name')->get();
        $currencies = Currency::orderBy('name')->get();
        return view('accounting.journal-report', compact('offices', 'currencies'));
    }

    private function getReportData(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $officeId = $request->input('office_id');

        $query = JournalEntryLine::with([
                'journalEntry',
                'glAccount',
                'tradePartner',
                'office',
                'currency',
            ])
            ->whereHas('journalEntry', function ($q) use ($startDate, $endDate) {
                $q->where('entry_date', '>=', $startDate)
                  ->where('entry_date', '<=', $endDate)
                  ->where('status', '!=', 'VOIDED');
            })
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->orderBy('journal_entry_id')
            ->orderBy('line_no');

        $lines = $query->get();

        $results = [];
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($lines as $line) {
            $je = $line->journalEntry;
            $foreignAmount = $line->foreign_debit ?: $line->foreign_credit;
            $results[] = [
                'date' => $je?->entry_date?->format('m-d-Y') ?? '',
                'gl_no' => $line->glAccount?->code ?? '',
                'gl_desc' => $line->glAccount?->name ?? '',
                'source' => '',
                'ref_no' => $je?->entry_no ?? '',
                'office' => $line->office?->name ?? $je?->office?->name ?? '',
                'company' => $line->tradePartner?->name ?? '',
                'description' => $line->description ?? $je?->description ?? '',
                'debit' => (float) $line->local_debit,
                'credit' => (float) $line->local_credit,
                'foreign_amount' => (float) $foreignAmount,
                'cur' => $line->currency?->code ?? '',
                'rate' => (float) ($line->foreign_rate ?? 1),
            ];
            $totalDebit += (float) $line->local_debit;
            $totalCredit += (float) $line->local_credit;
        }

        return [$results, $startDate, $endDate, $totalDebit, $totalCredit];
    }

    public function preview(Request $request)
    {
        [$results, $startDate, $endDate, $totalDebit, $totalCredit] = $this->getReportData($request);
        return view('accounting.journal-report-preview', compact(
            'results', 'startDate', 'endDate', 'totalDebit', 'totalCredit'
        ) + ['printMode' => false]);
    }

    public function printReport(Request $request)
    {
        [$results, $startDate, $endDate, $totalDebit, $totalCredit] = $this->getReportData($request);
        return view('accounting.journal-report-preview', compact(
            'results', 'startDate', 'endDate', 'totalDebit', 'totalCredit'
        ) + ['printMode' => true]);
    }

    public function exportExcel(Request $request)
    {
        [$results, $startDate, $endDate, $totalDebit, $totalCredit] = $this->getReportData($request);

        $filename = 'journal-report-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($results, $startDate, $endDate, $totalDebit, $totalCredit) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Journal Report']);
            fputcsv($handle, ['Period', $startDate, '~', $endDate]);
            fputcsv($handle, []);

            fputcsv($handle, [
                'Date', 'G/L No.', 'G/L Desc.', 'Source', 'Ref. No.',
                'Office', 'Company', 'Description',
                'Debit', 'Credit', 'Foreign Amount', 'Cur', 'Rate',
            ]);

            foreach ($results as $row) {
                fputcsv($handle, [
                    $row['date'],
                    $row['gl_no'],
                    $row['gl_desc'],
                    $row['source'],
                    $row['ref_no'],
                    $row['office'],
                    $row['company'],
                    $row['description'],
                    $row['debit'] ? number_format($row['debit'], 2) : '',
                    $row['credit'] ? number_format($row['credit'], 2) : '',
                    $row['foreign_amount'] ? number_format($row['foreign_amount'], 2) : '',
                    $row['cur'],
                    $row['rate'] != 1 ? number_format($row['rate'], 6) : '',
                ]);
            }

            fputcsv($handle, []);
            fputcsv($handle, [
                'TOTAL', '', '', '', '',
                '', '', '',
                number_format($totalDebit, 2),
                number_format($totalCredit, 2),
                '', '', '',
            ]);
            fputcsv($handle, [count($results) . ' Record(s)', '', '', '', '', '', '', '', '', '', '', '', '']);

            fclose($handle);
        }, 200, $headers);
    }
}
