<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue / Cost Report - Print</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter','Open Sans',Arial,sans-serif;font-size:11px;color:#1e293b;padding:20px;background:#fff}
        .header{text-align:center;margin-bottom:20px;border-bottom:2px solid #1e293b;padding-bottom:12px}
        .header h1{font-size:18px;font-weight:700;color:#1e293b;margin-bottom:4px}
        .header .period{font-size:12px;color:#475569}
        .meta{display:flex;justify-content:space-between;margin-bottom:16px;font-size:10px;color:#64748b}
        table{width:100%;border-collapse:collapse;margin-bottom:16px}
        th{background:#4a5568;color:#fff;font-weight:600;border:1px solid #2d3748;padding:5px 8px;text-align:left;font-size:10px}
        td{padding:4px 8px;border:1px solid #e2e8f0;color:#334155}
        td.num{text-align:right;font-family:'Courier New',monospace}
        .grand-total td{background:#1e293b;color:#fff;font-weight:700;font-size:11px;border:1px solid #0f172a}
        .status-paid{color:#16a34a;font-weight:600}
        .status-not-paid{color:#dc2626;font-weight:600}
        .status-partial{color:#d97706;font-weight:600}
        .footer{margin-top:20px;border-top:1px solid #e2e8f0;padding-top:10px;font-size:9px;color:#94a3b8;text-align:center}
        @media print{body{padding:10px}}
    </style>
</head>
<body>
    <div class="header">
        <h1>Revenue / Cost Report</h1>
        <div class="period">Period: {{ $startDate }} ~ {{ $endDate }} &middot; {{ ucfirst($type) }}</div>
    </div>
    <div class="meta">
        <span>Generated: {{ now()->format('Y-m-d H:i:s') }}</span>
        <span>Report Type: {{ ucfirst($reportType) }}</span>
    </div>

    <table>
        <thead>
            <tr>
                @if($reportType === 'detail')
                    <th style="width:12%;">Invoice No</th>
                    <th style="width:9%;">Invoice Date</th>
                    <th style="width:9%;">Due Date</th>
                    <th style="width:14%;">Customer</th>
                    <th style="width:10%;">Sales Person</th>
                    <th style="width:10%;">Shipping Type</th>
                    <th style="width:8%;">Office</th>
                    <th style="width:6%;">Currency</th>
                    <th style="width:9%;text-align:right;">Total Amount</th>
                    <th style="width:9%;text-align:right;">Paid Amount</th>
                    <th style="width:9%;text-align:right;">Balance</th>
                    <th style="width:6%;">Status</th>
                @else
                    <th style="width:20%;">Shipping Type</th>
                    <th style="width:12%;">Currency</th>
                    <th style="width:12%;text-align:right;">Total Amount</th>
                    <th style="width:12%;text-align:right;">Paid Amount</th>
                    <th style="width:12%;text-align:right;">Balance</th>
                    <th style="width:10%;text-align:right;">Count</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($results as $row)
                <tr>
                    @if($reportType === 'detail')
                        <td>{{ $row['invoice_no'] ?? '' }}</td>
                        <td>{{ $row['invoice_date'] ?? '' }}</td>
                        <td>{{ $row['due_date'] ?? '' }}</td>
                        <td>{{ $row['partner_name'] ?? '' }}</td>
                        <td>{{ $row['sales_person'] ?? '' }}</td>
                        <td>{{ $row['shipping_type'] ?? '' }}</td>
                        <td>{{ $row['office'] ?? '' }}</td>
                        <td>{{ $row['currency'] ?? '' }}</td>
                        <td class="num">{{ number_format($row['total_amount'], 2) }}</td>
                        <td class="num">{{ number_format($row['paid_amount'], 2) }}</td>
                        <td class="num">{{ number_format($row['balance'], 2) }}</td>
                        <td class="{{ ($row['status'] ?? '') === 'Paid' ? 'status-paid' : (($row['status'] ?? '') === 'Not Paid' ? 'status-not-paid' : 'status-partial') }}">{{ $row['status'] ?? '' }}</td>
                    @else
                        <td>{{ $row['shipping_type'] ?? '' }}</td>
                        <td>{{ $row['currency'] ?? '' }}</td>
                        <td class="num">{{ number_format($row['total_amount'], 2) }}</td>
                        <td class="num">{{ number_format($row['paid_amount'], 2) }}</td>
                        <td class="num">{{ number_format($row['balance'], 2) }}</td>
                        <td class="num">{{ $row['count'] ?? 0 }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $reportType === 'detail' ? 12 : 6 }}" style="text-align:center;color:#94a3b8;">No records found.</td>
                </tr>
            @endforelse
            <tr class="grand-total">
                <td colspan="{{ $reportType === 'detail' ? 8 : 2 }}">TOTAL</td>
                <td class="num">{{ number_format($summary['total_amount'] ?? 0, 2) }}</td>
                <td class="num">{{ number_format($summary['total_paid'] ?? 0, 2) }}</td>
                <td class="num">{{ number_format($summary['total_balance'] ?? 0, 2) }}</td>
                <td class="num">{{ $summary['count'] ?? 0 }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Revenue / Cost Report &middot; Period: {{ $startDate }} ~ {{ $endDate }} &middot; Generated: {{ now()->format('Y-m-d H:i:s') }}
    </div>

    <script>window.onload=function(){window.print()}</script>
</body>
</html>
