<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Outstanding Report - As of {{ $asOfDate }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Open Sans', Arial, sans-serif; font-size: 11px; color: #333; padding: 15px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 15px; }
        .header-left { flex: 1; }
        .header-right { text-align: right; font-size: 10px; color: #666; }
        .header-left h1 { font-size: 14px; font-weight: 700; margin-bottom: 4px; }
        .header-left p { font-size: 10px; color: #666; }
        .report-title { text-align: center; font-size: 12px; font-weight: 700; margin: 12px 0; text-decoration: underline; }

        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th { background: #f0f0f0; color: #333; font-weight: 700; border: 1px solid #999; padding: 5px 6px; text-align: left; }
        td { padding: 4px 6px; border: 1px solid #ccc; color: #333; }
        .num { text-align: right; font-family: 'Courier New', monospace; }
        .total-row { background: #e8e8e8 !important; font-weight: 700; }
        .total-row td { border-top: 2px solid #333; font-weight: 700; }
        .received { color: #16a34a; }
        .paid { color: #dc2626; }
        .fw-600 { font-weight: 600; }

        .footer { margin-top: 15px; border-top: 1px solid #ccc; padding-top: 8px; font-size: 9px; color: #999; display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <h1><i class="fa fa-bank"></i> GO FREIGHT</h1>
            <p>Bank Outstanding Report</p>
        </div>
        <div class="header-right">
            <div>As of: <strong>{{ $asOfDate }}</strong></div>
            <div>Generated: {{ now()->format('Y-m-d H:i') }}</div>
        </div>
    </div>

    @if(!$rows || empty($rows))
        <div style="text-align:center;padding:30px;color:#999;">No outstanding items found.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Bank Name</th>
                    @if($groupByOffice)
                        <th>Office</th>
                    @endif
                    <th>Currency</th>
                    <th class="num">Check Received</th>
                    <th class="num">Check Paid</th>
                    <th class="num">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td class="fw-600">{{ $row['bank_name'] }}</td>
                        @if($groupByOffice)
                            <td>{{ $row['office'] ?? '' }}</td>
                        @endif
                        <td>{{ $row['currency'] }}</td>
                        <td class="num received">{{ number_format($row['check_received'], 2) }}</td>
                        <td class="num paid">{{ number_format($row['check_paid'], 2) }}</td>
                        <td class="num fw-600" style="color: {{ $row['total'] < 0 ? '#dc2626' : ($row['total'] > 0 ? '#16a34a' : '#333') }};">{{ number_format($row['total'], 2) }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="{{ $groupByOffice ? 2 : 1 }}">GRAND TOTAL</td>
                    <td></td>
                    <td class="num">{{ number_format($totals['check_received'] ?? 0, 2) }}</td>
                    <td class="num">{{ number_format($totals['check_paid'] ?? 0, 2) }}</td>
                    @php $gt = $totals['total'] ?? 0; @endphp
                    <td class="num" style="color: {{ $gt < 0 ? '#dc2626' : ($gt > 0 ? '#16a34a' : '#333') }};">{{ number_format($gt, 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    <div class="footer">
        <span>Bank Outstanding Report &mdash; As of {{ $asOfDate }}</span>
        <span>Page 1 of 1</span>
    </div>

    <script>
        window.onload = function() { window.print(); };
    </script>
</body>
</html>
