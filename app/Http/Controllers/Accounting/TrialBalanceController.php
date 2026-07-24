<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Invoice;
use App\Models\AccountingPayment;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrialBalanceController extends Controller
{
    /**
     * Show the Trial Balance report page with filter inputs.
     */
    public function index()
    {
        $offices = Office::where('is_active', true)->orderBy('name')->get();

        return view('accounting.trial-balance', compact('offices'));
    }

    /**
     * Generate the Trial Balance report data via AJAX.
     *
     * A Trial Balance lists all accounts with their debit and credit balances.
     * Total Debits must equal Total Credits.
     *
     * Account categories derived from existing data:
     * - Assets (Debit): Cash, AR
     * - Liabilities (Credit): AP
     * - Revenue (Credit): AR total
     * - Expenses (Debit): AP total
     */
    public function view(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'period_from' => 'nullable|date',
            'period_to'   => 'nullable|date|after_or_equal:period_from',
            'office_id'   => 'nullable|exists:offices,id',
            'format'      => 'nullable|in:standard,debit_credit,currency_detail',
            'group_by_sub' => 'nullable|boolean',
            'hide_zero'   => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $periodFrom = $request->input('period_from', date('Y-01-01'));
        $periodTo   = $request->input('period_to', date('Y-m-d'));
        $officeId   = $request->input('office_id');
        $format     = $request->input('format', 'standard');
        $groupBySub = filter_var($request->input('group_by_sub', true), FILTER_VALIDATE_BOOLEAN);
        $hideZero   = filter_var($request->input('hide_zero', false), FILTER_VALIDATE_BOOLEAN);

        $accounts = [];

        // ── 1. Cash & Cash Equivalents (Bank Accounts) ──
        $bankAccounts = BankAccount::with('currency')->orderBy('name')->get();

        foreach ($bankAccounts as $bank) {
            $openingBalance = (float) $bank->opening_balance;

            // Net activity in period
            $receipts = (float) AccountingPayment::where('type', 'RECEIVED')
                ->where('payment_date', '>=', $periodFrom)
                ->where('payment_date', '<=', $periodTo)
                ->when($officeId, fn($q) => $q->where('office_id', $officeId))
                ->sum('amount');

            $payments = (float) AccountingPayment::where('type', 'MADE')
                ->where('payment_date', '>=', $periodFrom)
                ->where('payment_date', '<=', $periodTo)
                ->when($officeId, fn($q) => $q->where('office_id', $officeId))
                ->sum('amount');

            $debitTotal  = $receipts;
            $creditTotal = $payments;
            $closingBalance = $openingBalance + $debitTotal - $creditTotal;

            $accounts[] = [
                'code'            => '1000',
                'sub_code'        => $bank->account_no ?? $bank->name,
                'name'            => $bank->name,
                'group'           => 'Assets',
                'sub_group'       => 'Cash & Cash Equivalents',
                'currency'        => $bank->currency?->code ?? 'USD',
                'opening_balance' => $openingBalance,
                'debit'           => $debitTotal,
                'credit'          => $creditTotal,
                'closing_balance' => $closingBalance,
                'type'            => 'debit',
            ];
        }

        // ── 2. Accounts Receivable ──
        $arTotal = (float) Invoice::where('type', 'AR')
            ->where('invoice_date', '<=', $periodTo)
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->sum('total_amount');

        $arPaymentsReceived = (float) AccountingPayment::where('type', 'RECEIVED')
            ->where('payment_date', '>=', $periodFrom)
            ->where('payment_date', '<=', $periodTo)
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->sum('amount');

        $netAR = $arTotal - $arPaymentsReceived;

        if (!$hideZero || abs($netAR) > 0.01) {
            $accounts[] = [
                'code'            => '1200',
                'sub_code'        => 'AR',
                'name'            => 'Accounts Receivable',
                'group'           => 'Assets',
                'sub_group'       => 'Current Assets',
                'currency'        => 'USD',
                'opening_balance' => $arTotal,
                'debit'           => 0,
                'credit'          => $arPaymentsReceived,
                'closing_balance' => $netAR,
                'type'            => 'debit',
            ];
        }

        // ── 3. Accounts Payable ──
        $apTotal = (float) Invoice::where('type', 'AP')
            ->where('invoice_date', '<=', $periodTo)
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->sum('total_amount');

        $apPaymentsMade = (float) AccountingPayment::where('type', 'MADE')
            ->where('payment_date', '>=', $periodFrom)
            ->where('payment_date', '<=', $periodTo)
            ->when($officeId, fn($q) => $q->where('office_id', $officeId))
            ->sum('amount');

        $netAP = $apTotal - $apPaymentsMade;

        if (!$hideZero || abs($netAP) > 0.01) {
            $accounts[] = [
                'code'            => '2000',
                'sub_code'        => 'AP',
                'name'            => 'Accounts Payable',
                'group'           => 'Liabilities',
                'sub_group'       => 'Current Liabilities',
                'currency'        => 'USD',
                'opening_balance' => $apTotal,
                'debit'           => $apPaymentsMade,
                'credit'          => 0,
                'closing_balance' => $netAP,
                'type'            => 'credit',
            ];
        }

        // ── 4. Revenue (from AR invoices) ──
        $totalRevenue = $arTotal;
        if (!$hideZero || abs($totalRevenue) > 0.01) {
            $accounts[] = [
                'code'            => '4000',
                'sub_code'        => 'REV',
                'name'            => 'Revenue',
                'group'           => 'Revenue',
                'sub_group'       => 'Operating Revenue',
                'currency'        => 'USD',
                'opening_balance' => 0,
                'debit'           => 0,
                'credit'          => $totalRevenue,
                'closing_balance' => $totalRevenue,
                'type'            => 'credit',
            ];
        }

        // ── 5. Expenses (from AP invoices) ──
        $totalExpenses = $apTotal;
        if (!$hideZero || abs($totalExpenses) > 0.01) {
            $accounts[] = [
                'code'            => '5000',
                'sub_code'        => 'EXP',
                'name'            => 'Expenses',
                'group'           => 'Expenses',
                'sub_group'       => 'Operating Expenses',
                'currency'        => 'USD',
                'opening_balance' => 0,
                'debit'           => $totalExpenses,
                'credit'          => 0,
                'closing_balance' => $totalExpenses,
                'type'            => 'debit',
            ];
        }

        // ── Group accounts if requested ──
        $groupedAccounts = [];
        if ($groupBySub) {
            $grouped = [];
            foreach ($accounts as $acc) {
                $key = $acc['group'] . '|' . $acc['sub_group'];
                if (!isset($grouped[$key])) {
                    $grouped[$key] = [
                        'group'     => $acc['group'],
                        'sub_group' => $acc['sub_group'],
                        'accounts'  => [],
                        'opening_balance' => 0,
                        'debit'     => 0,
                        'credit'    => 0,
                        'closing_balance' => 0,
                    ];
                }
                $grouped[$key]['accounts'][] = $acc;
                $grouped[$key]['opening_balance'] += $acc['opening_balance'];
                $grouped[$key]['debit'] += $acc['debit'];
                $grouped[$key]['credit'] += $acc['credit'];
                $grouped[$key]['closing_balance'] += $acc['closing_balance'];
            }
            $groupedAccounts = array_values($grouped);
        }

        // ── Calculate Totals ──
        $totalDebit  = array_sum(array_column($accounts, 'debit'));
        $totalCredit = array_sum(array_column($accounts, 'credit'));
        $totalOpeningBalance = array_sum(array_column($accounts, 'opening_balance'));
        $totalClosingBalance = array_sum(array_column($accounts, 'closing_balance'));

        return response()->json([
            'success'     => true,
            'period_from' => $periodFrom,
            'period_to'   => $periodTo,
            'office_id'   => $officeId,
            'format'      => $format,
            'group_by_sub' => $groupBySub,
            'accounts'    => $accounts,
            'grouped'     => $groupedAccounts,
            'summary'     => [
                'total_debit'          => $totalDebit,
                'total_credit'         => $totalCredit,
                'total_opening_balance' => $totalOpeningBalance,
                'total_closing_balance' => $totalClosingBalance,
                'is_balanced'          => abs($totalDebit - $totalCredit) < 0.01,
                'account_count'        => count($accounts),
            ],
        ]);
    }

    /**
     * Render a print-friendly version of the report.
     */
    public function printReport(Request $request)
    {
        $data = $this->view($request)->getData(true);

        return view('accounting.trial-balance-print', [
            'accounts'     => $data['accounts'] ?? [],
            'grouped'      => $data['grouped'] ?? [],
            'summary'      => $data['summary'] ?? [],
            'periodFrom'   => $request->period_from ?? date('Y-01-01'),
            'periodTo'     => $request->period_to ?? date('Y-m-d'),
            'format'       => $request->format ?? 'standard',
            'groupBySub'   => $request->group_by_sub ?? true,
        ]);
    }

    /**
     * Export report data to CSV.
     */
    public function exportExcel(Request $request)
    {
        $data = $this->view($request)->getData(true);
        $accounts = $data['accounts'] ?? [];
        $grouped  = $data['grouped'] ?? [];
        $summary  = $data['summary'] ?? [];
        $groupBySub = $data['group_by_sub'] ?? true;

        $filename = 'trial-balance-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($accounts, $grouped, $summary, $groupBySub) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF"); // UTF-8 BOM

            fputcsv($handle, ['Trial Balance Report']);
            fputcsv($handle, ['Period', request()->period_from ?? date('Y-01-01') . ' to ' . (request()->period_to ?? date('Y-m-d'))]);
            fputcsv($handle, []);

            // Header
            fputcsv($handle, ['Code', 'Account', 'Group', 'Opening Balance', 'Debit', 'Credit', 'Closing Balance']);

            if ($groupBySub && !empty($grouped)) {
                foreach ($grouped as $grp) {
                    fputcsv($handle, []);
                    fputcsv($handle, ['-- ' . strtoupper($grp['group']) . ' / ' . $grp['sub_group'] . ' --']);

                    foreach ($grp['accounts'] as $acc) {
                        fputcsv($handle, [
                            $acc['code'],
                            $acc['name'],
                            $acc['group'],
                            number_format($acc['opening_balance'], 2),
                            number_format($acc['debit'], 2),
                            number_format($acc['credit'], 2),
                            number_format($acc['closing_balance'], 2),
                        ]);
                    }

                    fputcsv($handle, [
                        '',
                        'Subtotal',
                        '',
                        number_format($grp['opening_balance'], 2),
                        number_format($grp['debit'], 2),
                        number_format($grp['credit'], 2),
                        number_format($grp['closing_balance'], 2),
                    ]);
                }
            } else {
                foreach ($accounts as $acc) {
                    fputcsv($handle, [
                        $acc['code'],
                        $acc['name'],
                        $acc['group'],
                        number_format($acc['opening_balance'], 2),
                        number_format($acc['debit'], 2),
                        number_format($acc['credit'], 2),
                        number_format($acc['closing_balance'], 2),
                    ]);
                }
            }

            // Grand Total
            fputcsv($handle, []);
            fputcsv($handle, [
                '',
                'GRAND TOTAL',
                '',
                number_format($summary['total_opening_balance'] ?? 0, 2),
                number_format($summary['total_debit'] ?? 0, 2),
                number_format($summary['total_credit'] ?? 0, 2),
                number_format($summary['total_closing_balance'] ?? 0, 2),
            ]);
            fputcsv($handle, ['', 'Balanced', '', '', '', '', ($summary['is_balanced'] ?? false) ? 'Yes' : 'No']);

            fclose($handle);
        }, 200, $headers);
    }
}
