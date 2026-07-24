<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet - {{ $asOfDate }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 11px; color: #1e293b; padding: 20px; background: #fff; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1e293b; padding-bottom: 10px; }
        .header h1 { font-size: 16px; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
        .header .date { font-size: 11px; color: #475569; }
        .header .currency { font-size: 10px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { text-align: left; border-bottom: 2px solid #1e293b; padding: 4px 8px; font-weight: 700; font-size: 10px; text-transform: uppercase; }
        th:last-child { text-align: right; }
        td { padding: 3px 8px; border-bottom: 1px solid #e2e8f0; }
        td:last-child { text-align: right; font-family: 'Courier New', monospace; }
        .section-header td { font-weight: 700; font-size: 12px; text-transform: uppercase; background: #f8fafc; border-bottom: 1px solid #cbd5e1; padding: 6px 8px; }
        .group-header td { font-weight: 600; font-style: italic; color: #475569; padding-left: 16px; }
        .group-total td { font-weight: 600; border-top: 1px solid #93c5fd; background: #f0f9ff; padding-left: 16px; }
        .total-row td { font-weight: 700; border-top: 2px solid #1e293b; font-size: 12px; }
        .indent td:first-child { padding-left: 24px; }
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
        <h1>Balance Sheet</h1>
        <div class="date">As of {{ $asOfDate }}</div>
        <div class="currency">Currency: {{ strtoupper($currency) }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sections as $section)
                <tr class="section-header">
                    <td colspan="2">{{ strtoupper($section['title']) }}</td>
                </tr>

                @foreach($section['groups'] as $group)
                    <tr class="group-header">
                        <td>{{ $group['name'] }}</td>
                        <td></td>
                    </tr>

                    @foreach($group['lines'] as $line)
                        <tr class="indent">
                            <td>{{ $line['label'] }}@if($line['detail']) <span style="color:#94a3b8;">({{ $line['detail'] }})</span>@endif</td>
                            <td>{{ number_format($line['amount'], 2) }}</td>
                        </tr>
                    @endforeach

                    <tr class="group-total">
                        <td>Total {{ $group['name'] }}</td>
                        <td>{{ number_format($group['total'], 2) }}</td>
                    </tr>
                @endforeach

                <tr class="total-row">
                    <td>Total {{ $section['title'] }}</td>
                    <td>{{ number_format($section['total'], 2) }}</td>
                </tr>
            @endforeach

            <tr style="background:#f0fdf4;">
                <td style="font-weight:700;font-size:12px;">TOTAL LIABILITIES & EQUITY</td>
                <td style="font-weight:700;font-size:12px;">{{ number_format($summary['total_liabilities_and_equity'] ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span class="summary-label">Total Assets:</span>
            <span class="summary-value">{{ number_format($summary['total_assets'] ?? 0, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Liabilities:</span>
            <span class="summary-value">{{ number_format($summary['total_liabilities'] ?? 0, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Equity:</span>
            <span class="summary-value">{{ number_format($summary['total_equity'] ?? 0, 2) }}</span>
        </div>
        <div class="summary-row" style="border-top:1px solid #1e293b;padding-top:4px;margin-top:4px;">
            <span class="summary-label">Total Liabilities & Equity:</span>
            <span class="summary-value">{{ number_format($summary['total_liabilities_and_equity'] ?? 0, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Balanced:</span>
            <span class="summary-value">{{ ($summary['is_balanced'] ?? false) ? 'Yes' : 'No' }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Balance Sheet Report &middot; Generated {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>This is a computer-generated report. No signature required.</p>
    </div>

    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>
