<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Currency;
use App\Models\AccountingPayment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BankBookBalanceController extends Controller
{
    /**
     * Show the Bank Book Balance report page with filter inputs.
     */
    public function index()
    {
        $bankAccounts = BankAccount::orderBy('name')->get();
        $currencies = Currency::orderBy('code')->get();

        return view('accounting.bank-book-balance', compact('bankAccounts', 'currencies'));
    }

    /**
     * Convert amount based on currency mode.
     * Uses the passed exchange rate to avoid N+1 queries.
     */
    private function convertAmount(float $amount, ?float $exchangeRate, string $mode): float
    {
        if ($mode === 'bank_currency' || !$exchangeRate || $exchangeRate <= 0) {
            return $amount; // No conversion or no rate available
        }

        // Convert to main currency (USD) using the provided exchange rate
        return $amount / $exchangeRate;
    }

    /**
     * Generate the report data via AJAX.
     */
    public function view(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'period_from' => 'nullable|date',
            'period_to'   => 'nullable|date|after_or_equal:period_from',
            'bank_account_id' => 'nullable|exists:bank_accounts,id',
            'status'      => 'nullable|in:all,active,inactive',
            'type'        => 'nullable|in:Bank,Book',
            'report_type' => 'nullable|in:Summary,Detail',
            'hide_subtotal' => 'nullable|boolean',
            'currency'    => 'nullable|in:bank_currency,main_currency',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $periodFrom = $request->input('period_from');
        $periodTo   = $request->input('period_to');
        $bankId     = $request->input('bank_account_id');
        $status     = $request->input('status', 'all');
        $type       = $request->input('type', 'Bank');
        $reportType = $request->input('report_type', 'Summary');
        $hideSubtotal = filter_var($request->input('hide_subtotal', false), FILTER_VALIDATE_BOOLEAN);
        $currencyMode = $request->input('currency', 'bank_currency');

        // Build base query for bank accounts
        $query = BankAccount::with('currency');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($bankId) {
            $query->where('id', $bankId);
        }

        $bankAccounts = $query->orderBy('type')->orderBy('name')->get();

        // Calculate balances for each bank account
        $reportRows = [];
        $grandTotals = [
            'opening_balance' => 0,
            'receipts' => 0,
            'payments' => 0,
            'closing_balance' => 0,
            'book_balance' => 0,
            'difference' => 0,
        ];

        // Track subtotals by Type (Bank / Book)
        $subtotals = [
            'Bank' => ['opening_balance' => 0, 'receipts' => 0, 'payments' => 0, 'closing_balance' => 0, 'book_balance' => 0, 'difference' => 0, 'count' => 0],
            'Book' => ['opening_balance' => 0, 'receipts' => 0, 'payments' => 0, 'closing_balance' => 0, 'book_balance' => 0, 'difference' => 0, 'count' => 0],
        ];

        $previousType = null;

        foreach ($bankAccounts as $account) {
            $bankExchangeRate = $account->currency?->exchange_rate;
            $bankCurrencyCode = $account->currency?->code ?? 'USD';

            // Opening balance before period
            $openingBalance = (float) $account->opening_balance;

            // Receipts during period (payments of type RECEIVED)
            $receiptsQuery = AccountingPayment::where('type', 'RECEIVED');
            if ($periodFrom) {
                $receiptsQuery->where('payment_date', '>=', $periodFrom);
            }
            if ($periodTo) {
                $receiptsQuery->where('payment_date', '<=', $periodTo);
            }
            $receipts = (float) $receiptsQuery->sum('amount');

            // Payments during period (payments of type MADE)
            $paymentsQuery = AccountingPayment::where('type', 'MADE');
            if ($periodFrom) {
                $paymentsQuery->where('payment_date', '>=', $periodFrom);
            }
            if ($periodTo) {
                $paymentsQuery->where('payment_date', '<=', $periodTo);
            }
            $payments = (float) $paymentsQuery->sum('amount');

            // Currency conversion using eager-loaded exchange rate (avoids N+1)
            $openingBalanceConv = $this->convertAmount($openingBalance, $bankExchangeRate, $currencyMode);
            $receiptsConv = $this->convertAmount($receipts, $bankExchangeRate, $currencyMode);
            $paymentsConv = $this->convertAmount($payments, $bankExchangeRate, $currencyMode);

            // Closing balance
            $closingBalanceConv = $openingBalanceConv + $receiptsConv - $paymentsConv;

            // Book balance = net invoice position (AR - AP for this period)
            $arQuery = Invoice::where('type', 'AR');
            if ($periodFrom) {
                $arQuery->where('invoice_date', '>=', $periodFrom);
            }
            if ($periodTo) {
                $arQuery->where('invoice_date', '<=', $periodTo);
            }
            $arTotal = (float) $arQuery->sum('total_amount');

            $apQuery = Invoice::where('type', 'AP');
            if ($periodFrom) {
                $apQuery->where('invoice_date', '>=', $periodFrom);
            }
            if ($periodTo) {
                $apQuery->where('invoice_date', '<=', $periodTo);
            }
            $apTotal = (float) $apQuery->sum('total_amount');

            $bookBalanceConv = $this->convertAmount($arTotal - $apTotal, 1.0, $currencyMode);
            $differenceConv = $closingBalanceConv - $bookBalanceConv;

            // Check if we need a subtotal separator for the previous type group
            if (!$hideSubtotal && $previousType !== null && $previousType !== $account->type) {
                // Add subtotal row for the previous type
                $sub = $subtotals[$previousType];
                $reportRows[] = [
                    'is_subtotal' => true,
                    'subtotal_label' => $previousType . ' Accounts Subtotal',
                    'type' => $previousType,
                    'name' => '',
                    'account_no' => '',
                    'currency' => '',
                    'opening_balance' => $sub['opening_balance'],
                    'receipts' => $sub['receipts'],
                    'payments' => $sub['payments'],
                    'closing_balance' => $sub['closing_balance'],
                    'book_balance' => $sub['book_balance'],
                    'difference' => $sub['difference'],
                    'status' => '',
                ];
            }

            $previousType = $account->type;

            $row = [
                'is_subtotal' => false,
                'id' => $account->id,
                'name' => $account->name,
                'account_no' => $account->account_no ?? '--',
                'bank_name' => $account->bank_name ?? '--',
                'currency' => $bankCurrencyCode,
                'type' => $account->type,
                'opening_balance' => $openingBalanceConv,
                'receipts' => $receiptsConv,
                'payments' => $paymentsConv,
                'closing_balance' => $closingBalanceConv,
                'book_balance' => $bookBalanceConv,
                'difference' => $differenceConv,
                'status' => $account->status,
            ];

            // Accumulate subtotals
            $subtotals[$account->type]['opening_balance'] += $openingBalanceConv;
            $subtotals[$account->type]['receipts'] += $receiptsConv;
            $subtotals[$account->type]['payments'] += $paymentsConv;
            $subtotals[$account->type]['closing_balance'] += $closingBalanceConv;
            $subtotals[$account->type]['book_balance'] += $bookBalanceConv;
            $subtotals[$account->type]['difference'] += $differenceConv;
            $subtotals[$account->type]['count']++;

            // For Detail report, include individual transactions
            if ($reportType === 'Detail') {
                $transactions = [];

                $paymentsRec = AccountingPayment::where('type', 'RECEIVED')
                    ->when($periodFrom, fn($q) => $q->where('payment_date', '>=', $periodFrom))
                    ->when($periodTo, fn($q) => $q->where('payment_date', '<=', $periodTo))
                    ->with(['tradePartner', 'currency'])
                    ->latest('payment_date')
                    ->get()
                    ->map(fn($p) => [
                        'date' => $p->payment_date?->format('Y-m-d') ?? '--',
                        'description' => 'Receipt: ' . ($p->tradePartner?->name ?? 'N/A') . ' - ' . ($p->reference_no ?? ''),
                        'debit' => 0,
                        'credit' => $this->convertAmount((float) $p->amount, $p->currency?->exchange_rate, $currencyMode),
                        'balance' => 0,
                    ]);

                $paymentsMade = AccountingPayment::where('type', 'MADE')
                    ->when($periodFrom, fn($q) => $q->where('payment_date', '>=', $periodFrom))
                    ->when($periodTo, fn($q) => $q->where('payment_date', '<=', $periodTo))
                    ->with(['tradePartner', 'currency'])
                    ->latest('payment_date')
                    ->get()
                    ->map(fn($p) => [
                        'date' => $p->payment_date?->format('Y-m-d') ?? '--',
                        'description' => 'Payment: ' . ($p->tradePartner?->name ?? 'N/A') . ' - ' . ($p->reference_no ?? ''),
                        'debit' => $this->convertAmount((float) $p->amount, $p->currency?->exchange_rate, $currencyMode),
                        'credit' => 0,
                        'balance' => 0,
                    ]);

                $transactions = $paymentsRec->concat($paymentsMade)->sortBy('date')->values()->toArray();

                // Calculate running balance
                $runningBalance = $openingBalanceConv;
                foreach ($transactions as &$txn) {
                    $runningBalance += ($txn['credit'] - $txn['debit']);
                    $txn['balance'] = $runningBalance;
                }

                $row['transactions'] = $transactions;
            }

            $reportRows[] = $row;

            // Accumulate totals
            $grandTotals['opening_balance'] += $openingBalanceConv;
            $grandTotals['receipts'] += $receiptsConv;
            $grandTotals['payments'] += $paymentsConv;
            $grandTotals['closing_balance'] += $closingBalanceConv;
            $grandTotals['book_balance'] += $bookBalanceConv;
            $grandTotals['difference'] += $differenceConv;
        }

        // Add final subtotal row
        if (!$hideSubtotal && $previousType !== null) {
            $sub = $subtotals[$previousType];
            $reportRows[] = [
                'is_subtotal' => true,
                'subtotal_label' => $previousType . ' Accounts Subtotal',
                'type' => $previousType,
                'name' => '',
                'account_no' => '',
                'currency' => '',
                'opening_balance' => $sub['opening_balance'],
                'receipts' => $sub['receipts'],
                'payments' => $sub['payments'],
                'closing_balance' => $sub['closing_balance'],
                'book_balance' => $sub['book_balance'],
                'difference' => $sub['difference'],
                'status' => '',
            ];
        }

        return response()->json([
            'success' => true,
            'rows' => $reportRows,
            'totals' => $grandTotals,
            'hide_subtotal' => $hideSubtotal,
            'report_type' => $reportType,
            'currency_mode' => $currencyMode,
        ]);
    }

    /**
     * Render a print-friendly version of the report.
     */
    public function printReport(Request $request)
    {
        $data = $this->view($request)->getData(true);

        return view('accounting.bank-book-balance-print', [
            'rows' => $data['rows'] ?? [],
            'totals' => $data['totals'] ?? [],
            'hideSubtotal' => $data['hide_subtotal'] ?? false,
            'reportType' => $data['report_type'] ?? 'Summary',
            'periodFrom' => $request->period_from,
            'periodTo' => $request->period_to,
        ]);
    }

    /**
     * Export report data to CSV.
     */
    public function exportExcel(Request $request)
    {
        $data = $this->view($request)->getData(true);
        $rows = $data['rows'] ?? [];
        $totals = $data['totals'] ?? [];
        $reportType = $data['report_type'] ?? 'Summary';

        $filename = 'bank-book-balance-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($rows, $totals, $reportType) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF"); // UTF-8 BOM

            // Header row
            fputcsv($handle, [
                'Bank Name', 'Account No.', 'Currency', 'Opening Balance',
                'Receipts', 'Payments', 'Closing Balance',
                'Book Balance', 'Difference', 'Status'
            ]);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row['name'],
                    $row['account_no'],
                    $row['currency'],
                    number_format($row['opening_balance'], 2),
                    number_format($row['receipts'], 2),
                    number_format($row['payments'], 2),
                    number_format($row['closing_balance'], 2),
                    number_format($row['book_balance'], 2),
                    number_format($row['difference'], 2),
                    $row['status'],
                ]);

                // Include detail transactions if available
                if ($reportType === 'Detail' && !empty($row['transactions'])) {
                    foreach ($row['transactions'] as $txn) {
                        fputcsv($handle, [
                            '  ' . ($txn['date'] ?? ''),
                            '  ' . strip_tags($txn['description'] ?? ''),
                            '',
                            '',
                            $txn['debit'] > 0 ? number_format($txn['debit'], 2) : '',
                            $txn['credit'] > 0 ? number_format($txn['credit'], 2) : '',
                            number_format($txn['balance'], 2),
                            '', '', ''
                        ]);
                    }
                }
            }

            // Totals row
            fputcsv($handle, [
                'GRAND TOTAL', '', '',
                number_format($totals['opening_balance'] ?? 0, 2),
                number_format($totals['receipts'] ?? 0, 2),
                number_format($totals['payments'] ?? 0, 2),
                number_format($totals['closing_balance'] ?? 0, 2),
                number_format($totals['book_balance'] ?? 0, 2),
                number_format($totals['difference'] ?? 0, 2),
                '',
            ]);

            fclose($handle);
        }, 200, $headers);
    }
}
