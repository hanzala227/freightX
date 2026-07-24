<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Ledger Report - {{ $periodFrom }} to {{ $periodTo }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 11px; color: #1e293b; padding: 20px; background: #fff; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1e293b; padding-bottom: 10px; }
        .header h1 { font-size: 16px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
        .header .date { font-size: 11px; color: #475569; }
        .header .period { font-size: 10px; color: #64748b; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { text-align: left; border-bottom: 2px solid #1e293b; padding: 4px 8px; font-weight: 700; font-size: 10px; text-transform: uppercase; }
        th:nth-child(4), th:nth-child(5), th:nth-child(6) { text-align: right; }
        td { padding: 3px 8px; border-bottom: 1px solid #e2e8f0; }
        td:nth-child(4), td:nth-child(5), td:nth-child(6) { text-align: right; font-family: 'Courier New', monospace; }
        .account-header td { font-weight: 700; font-size: 12px; text-transform: uppercase; background: #f8fafc; border-bottom: 1px solid #cbd5e1; padding: 6px 8px; }
        .opening-row td { font-weight: 600; font-style: italic; color: #475569; background: #f0f9ff; border-bottom: 1px solid #bfdbfe; }
        .closing-row td { font-weight: 700; border-top: 2px solid #1e293b; background: #f0f9ff; color: #1e40af; }
        .total-row td { font-weight: 700; border-top: 2px solid #1e293b; font-size: 12px; background: #f8fafc; }
        .txn-row td:first-child { padding-left: 20px; }
        .no-data { text-align: center; color: #94a3b8; font-style: italic; padding: 8px; }
        .summary { margin-top: 20px; border-top: 2px solid #1e293b; padding-top: 10px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 4px; }
        .summary-label { font-weight: 600; }
        .summary-value { font-family: 'Courier New', monospace; }
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 8px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>General Ledger Report</h1>
        <div class="period">Period: {{ $periodFrom }} to {{ $periodTo }}</div>
        <div class="date">Report Type: {{ strtoupper($reportType) }}</div>
    </div>

    @foreach($accounts as $account)
        <table>
            <thead>
                <tr>
                    <th style="width:14%;">Date</th>
                    <th style="width:12%;">Reference</th>
                    <th style="width:30%;">Description</th>
                    <th style="width:14%;">Debit</th>
                    <th style="width:14%;">Credit</th>
                    <th style="width:16%;">Balance</th>
                </tr>
            </thead>
            <tbody>
                <tr class="account-header">
                    <td colspan="6">[{{ $account['code'] }}] {{ $account['name'] }}</td>
                </tr>
                <tr class="opening-row">
                    <td colspan="3">Opening Balance</td>
                    <td>{{ number_format($account['opening_balance'], 2) }}</td>
                    <td></td>
                    <td>{{ number_format($account['opening_balance'], 2) }}</td>
                </tr>

                @forelse($account['transactions'] as $txn)
                    <tr class="txn-row">
                        <td>{{ $txn['date'] }}</td>
                        <td>{{ $txn['reference'] }}</td>
                        <td>{{ $txn['description'] }}</td>
                        <td>{{ $txn['debit'] > 0 ? number_format($txn['debit'], 2) : '' }}</td>
                        <td>{{ $txn['credit'] > 0 ? number_format($txn['credit'], 2) : '' }}</td>
                        <td>{{ number_format($txn['running_balance'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="no-data">No transactions in this period</td></tr>
                @endforelse

                <tr class="closing-row">
                    <td colspan="3">Closing Balance</td>
                    <td>{{ number_format($account['total_debit'], 2) }}</td>
                    <td>{{ number_format($account['total_credit'], 2) }}</td>
                    <td>{{ number_format($account['closing_balance'], 2) }}</td>
                </tr>
            </tbody>
        </table>
        <div style="height:12px;"></div>
    @endforeach

    <table>
        <tbody>
            <tr class="total-row">
                <td colspan="3">GRAND TOTAL</td>
                <td>{{ number_format($summary['grand_total_debit'] ?? 0, 2) }}</td>
                <td>{{ number_format($summary['grand_total_credit'] ?? 0, 2) }}</td>
                <td>{{ number_format($summary['grand_closing'] ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span class="summary-label">Total Debit:</span>
            <span class="summary-value">{{ number_format($summary['grand_total_debit'] ?? 0, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Credit:</span>
            <span class="summary-value">{{ number_format($summary['grand_total_credit'] ?? 0, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Is Balanced:</span>
            <span class="summary-value">{{ ($summary['is_balanced'] ?? false) ? 'Yes' : 'No' }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Accounts:</span>
            <span class="summary-value">{{ $summary['account_count'] ?? 0 }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Transactions:</span>
            <span class="summary-value">{{ $summary['total_transactions'] ?? 0 }}</span>
        </div>
    </div>

    <div class="footer">
        <p>General Ledger Report &middot; Period: {{ $periodFrom }} to {{ $periodTo }} &middot; Generated {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>This is a computer-generated report. No signature required.</p>
    </div>

    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>
