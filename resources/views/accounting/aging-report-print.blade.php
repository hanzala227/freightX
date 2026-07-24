<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aging Report - {{ $asOfDate }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 11px; color: #1e293b; padding: 20px; background: #fff; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1e293b; padding-bottom: 10px; }
        .header h1 { font-size: 16px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
        .header .date { font-size: 11px; color: #475569; }
        .header .type { font-size: 10px; color: #64748b; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { text-align: left; border-bottom: 2px solid #1e293b; padding: 4px 8px; font-weight: 700; font-size: 10px; text-transform: uppercase; }
        th:nth-child(2), th:nth-child(3), th:nth-child(4), th:nth-child(5), th:nth-child(6), th:nth-child(7) { text-align: right; }
        td { padding: 3px 8px; border-bottom: 1px solid #e2e8f0; }
        td:nth-child(2), td:nth-child(3), td:nth-child(4), td:nth-child(5), td:nth-child(6), td:nth-child(7) { text-align: right; font-family: 'Courier New', monospace; }
        .group-header td { font-weight: 700; font-size: 12px; text-transform: uppercase; background: #f8fafc; border-bottom: 1px solid #cbd5e1; padding: 6px 8px; }
        .detail-row td:first-child { padding-left: 20px; }
        .total-row td { font-weight: 700; border-top: 2px solid #1e293b; font-size: 12px; background: #f8fafc; }
        .aging-current { color: #16a34a; }
        .aging-1-30 { color: #2563eb; }
        .aging-31-60 { color: #d97706; }
        .aging-61-90 { color: #ea580c; }
        .aging-90 { color: #dc2626; font-weight: 700; }
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
        <h1>Aging Report</h1>
        <div class="date">As of {{ $asOfDate }}</div>
        <div class="type">Type: {{ strtoupper(str_replace(',', ' / ', $agingType)) }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:22%;">Name</th>
                <th style="width:10%;">Current Balance</th>
                <th style="width:10%;">Over 1-30 Days</th>
                <th style="width:10%;">Over 31-60 Days</th>
                <th style="width:10%;">Over 61-90 Days</th>
                <th style="width:10%;">Over 90 Days</th>
                <th style="width:12%;">Total Balance</th>
                <th style="width:8%;">Currency</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results as $row)
                <tr class="group-header">
                    <td>{{ $row['partner_name'] }}</td>
                    <td class="aging-current">{{ number_format($row['current'], 2) }}</td>
                    <td class="aging-1-30">{{ number_format($row['over1_30'], 2) }}</td>
                    <td class="aging-31-60">{{ number_format($row['over31_60'], 2) }}</td>
                    <td class="aging-61-90">{{ number_format($row['over61_90'], 2) }}</td>
                    <td class="aging-90">{{ number_format($row['over90'], 2) }}</td>
                    <td>{{ number_format($row['total'], 2) }}</td>
                    <td>{{ $row['currency'] }}</td>
                </tr>

                @if(!empty($row['invoices']))
                    @foreach($row['invoices'] as $inv)
                        <tr class="detail-row">
                            <td>{{ $inv['invoice_no'] }} ({{ $inv['due_date'] }})</td>
                            <td>{{ $inv['current'] > 0 ? number_format($inv['current'], 2) : '' }}</td>
                            <td>{{ $inv['over1_30'] > 0 ? number_format($inv['over1_30'], 2) : '' }}</td>
                            <td>{{ $inv['over31_60'] > 0 ? number_format($inv['over31_60'], 2) : '' }}</td>
                            <td>{{ $inv['over61_90'] > 0 ? number_format($inv['over61_90'], 2) : '' }}</td>
                            <td>{{ $inv['over90'] > 0 ? number_format($inv['over90'], 2) : '' }}</td>
                            <td>{{ number_format($inv['balance'], 2) }}</td>
                            <td>{{ $inv['currency'] }}</td>
                        </tr>
                    @endforeach
                @endif
            @empty
                <tr><td colspan="8" style="text-align:center;color:#94a3b8;font-style:italic;">No outstanding balances found</td></tr>
            @endforelse

            <tr class="total-row">
                <td>TOTAL</td>
                <td class="aging-current">{{ number_format($summary['total_current'] ?? 0, 2) }}</td>
                <td class="aging-1-30">{{ number_format($summary['total_over1_30'] ?? 0, 2) }}</td>
                <td class="aging-31-60">{{ number_format($summary['total_over31_60'] ?? 0, 2) }}</td>
                <td class="aging-61-90">{{ number_format($summary['total_over61_90'] ?? 0, 2) }}</td>
                <td class="aging-90">{{ number_format($summary['total_over90'] ?? 0, 2) }}</td>
                <td>{{ number_format($summary['grand_total'] ?? 0, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span class="summary-label">Total Current:</span>
            <span class="summary-value">{{ number_format($summary['total_current'] ?? 0, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Over 1-30 Days:</span>
            <span class="summary-value">{{ number_format($summary['total_over1_30'] ?? 0, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Over 31-60 Days:</span>
            <span class="summary-value">{{ number_format($summary['total_over31_60'] ?? 0, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Over 61-90 Days:</span>
            <span class="summary-value">{{ number_format($summary['total_over61_90'] ?? 0, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Over 90 Days:</span>
            <span class="summary-value">{{ number_format($summary['total_over90'] ?? 0, 2) }}</span>
        </div>
        <div class="summary-row" style="border-top:1px solid #1e293b;padding-top:4px;margin-top:4px;">
            <span class="summary-label">Grand Total:</span>
            <span class="summary-value">{{ number_format($summary['grand_total'] ?? 0, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Partners:</span>
            <span class="summary-value">{{ $summary['partner_count'] ?? 0 }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Aging Report &middot; As of {{ $asOfDate }} &middot; Generated {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>This is a computer-generated report. No signature required.</p>
    </div>

    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>
