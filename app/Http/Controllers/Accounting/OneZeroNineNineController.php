<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\AccountingPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OneZeroNineNineController extends Controller
{
    public function index()
    {
        $offices = Office::where('is_active', true)->orderBy('name')->get();
        $years = range(date('Y'), date('Y') - 5);

        return view('accounting.1099-report', compact('offices', 'years'));
    }

    public function view(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fiscal_year' => 'required|integer|min:2000|max:2100',
            'office_id' => 'nullable|exists:offices,id',
            'trade_partner_id' => 'nullable|exists:trade_partners,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $year = $request->input('fiscal_year');
        $startDate = "{$year}-01-01";
        $endDate = "{$year}-12-31";
        $officeId = $request->input('office_id');
        $partnerId = $request->input('trade_partner_id');

        $query = AccountingPayment::whereNotNull('trade_partner_id')
            ->whereNull('void_date')
            ->where('payment_date', '>=', $startDate)
            ->where('payment_date', '<=', $endDate)
            ->whereHas('tradePartner', fn($q) => $q->where('track_1099', true))
            ->with('tradePartner')
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->when($partnerId, fn($q) => $q->where('trade_partner_id', $partnerId));

        $payments = $query->get();

        $partnerGroups = [];
        foreach ($payments as $p) {
            $tp = $p->tradePartner;
            $tid = $tp->id;
            if (!isset($partnerGroups[$tid])) {
                $partnerGroups[$tid] = [
                    'partner_id' => $tid,
                    'name' => $tp->name ?? 'N/A',
                    'tax_id' => $tp->tax_id ?? '',
                    'address' => $tp->local_address ?? '',
                    'city' => $tp->city ?? '',
                    'state' => $tp->state ?? '',
                    'zip' => $tp->zip_code ?? '',
                    'payments' => [],
                    'total_amount' => 0,
                    'check_count' => 0,
                    'non_check_count' => 0,
                ];
            }
            $partnerGroups[$tid]['payments'][] = [
                'payment_no' => $p->payment_no ?? '',
                'payment_date' => $p->payment_date?->format('Y-m-d') ?? '',
                'amount' => (float) $p->amount,
                'check_no' => $p->check_no ?? '',
                'type' => $p->type ?? '',
                'reference_no' => $p->reference_no ?? '',
            ];
            $partnerGroups[$tid]['total_amount'] += (float) $p->amount;
            if ($p->check_no) {
                $partnerGroups[$tid]['check_count']++;
            } else {
                $partnerGroups[$tid]['non_check_count']++;
            }
        }

        $results = array_values($partnerGroups);
        $totalPayments = array_sum(array_column($results, 'total_amount'));
        $totalCount = count($payments);

        return response()->json([
            'success' => true,
            'fiscal_year' => $year,
            'results' => $results,
            'summary' => [
                'total_vendors' => count($results),
                'total_payments' => $totalCount,
                'total_amount' => $totalPayments,
            ],
        ]);
    }

    public function printReport(Request $request)
    {
        $data = $this->view($request)->getData(true);

        return view('accounting.1099-report-print', [
            'results' => $data['results'] ?? [],
            'summary' => $data['summary'] ?? [],
            'fiscalYear' => $request->fiscal_year ?? date('Y'),
        ]);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->view($request)->getData(true);
        $results = $data['results'] ?? [];
        $summary = $data['summary'] ?? [];
        $year = $request->fiscal_year ?? date('Y');

        $filename = '1099-report-' . $year . '-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($results, $summary, $year) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['1099 Report - Tax Year ' . $year]);
            fputcsv($handle, []);

            fputcsv($handle, [
                'Vendor Name', 'Tax ID / TIN', 'Address', 'City', 'State', 'Zip',
                'Total Paid Amount', 'Payment Count', 'Check Count',
            ]);

            foreach ($results as $row) {
                $addr = $row['address'] ?? '';
                $city = $row['city'] ?? '';
                $state = $row['state'] ?? '';
                $zip = $row['zip'] ?? '';
                $fullAddr = implode(', ', array_filter([$addr, $city, $state, $zip]));
                if (!$fullAddr && ($addr || $city || $state || $zip)) {
                    $fullAddr = trim("$addr $city $state $zip");
                }

                fputcsv($handle, [
                    $row['name'],
                    $row['tax_id'],
                    $fullAddr,
                    '',
                    '',
                    '',
                    number_format($row['total_amount'], 2),
                    count($row['payments']),
                    $row['check_count'],
                ]);

                foreach ($row['payments'] as $pmt) {
                    fputcsv($handle, [
                        '',
                        '',
                        '',
                        $pmt['payment_date'],
                        $pmt['payment_no'],
                        $pmt['check_no'],
                        number_format($pmt['amount'], 2),
                        $pmt['type'],
                        $pmt['reference_no'],
                    ]);
                }
            }

            fputcsv($handle, []);
            fputcsv($handle, [
                'GRAND TOTAL', '', '', '', '', '',
                number_format($summary['total_amount'] ?? 0, 2),
                $summary['total_payments'] ?? 0,
                '',
            ]);

            fclose($handle);
        }, 200, $headers);
    }
}
