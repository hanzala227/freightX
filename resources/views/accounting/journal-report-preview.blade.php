<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Report - {{ $startDate }} ~ {{ $endDate }}</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter','Open Sans',Arial,sans-serif;font-size:11px;color:#1e293b;padding:20px;background:#fff}
        .header{text-align:center;margin-bottom:16px;padding-bottom:14px;border-bottom:2px solid #1e293b}
        .header h1{font-size:18px;font-weight:700;color:#2563eb;margin-bottom:4px;letter-spacing:1px}
        .title{text-align:center;margin:14px 0 6px;font-size:15px;font-weight:700;color:#1e293b;text-transform:uppercase;letter-spacing:1.5px}
        .period{text-align:center;font-size:11px;color:#64748b;margin-bottom:16px}
        table{width:100%;border-collapse:collapse;margin-bottom:16px}
        th{background:#4a5568;color:#fff;font-weight:600;border:1px solid #2d3748;padding:5px 8px;text-align:left;font-size:10px;white-space:nowrap}
        td{padding:4px 8px;border:1px solid #e2e8f0;color:#334155;font-size:11px}
        td.num{text-align:right;font-family:'Courier New',monospace;white-space:nowrap}
        td.center{text-align:center}
        .total-row td{background:#1e293b;color:#fff;font-weight:700;font-size:11px;border:1px solid #0f172a}
        .record-count{font-size:10px;color:#64748b;margin-top:4px}
        .footer{margin-top:20px;border-top:1px solid #e2e8f0;padding-top:10px;font-size:9px;color:#94a3b8;text-align:center}
        @media print{body{padding:10px}}
    </style>
</head>
<body>
    <div class="header">
        <h1>GoFreight</h1>
    </div>

    <div class="title">Journal Report</div>
    <div class="period">Period: {{ $startDate }} ~ {{ $endDate }}</div>

    <table>
        <thead>
            <tr>
                <th style="width:8%;">Date</th>
                <th style="width:7%;">G/L No.</th>
                <th style="width:12%;">G/L Desc.</th>
                <th style="width:6%;">Source</th>
                <th style="width:10%;">Ref. No.</th>
                <th style="width:8%;">Office</th>
                <th style="width:10%;">Company</th>
                <th style="width:15%;">Description</th>
                <th style="width:7%;text-align:right;">Debit</th>
                <th style="width:7%;text-align:right;">Credit</th>
                <th style="width:7%;text-align:right;">Foreign Amount</th>
                <th style="width:3%;text-align:center;">Cur</th>
                <th style="width:5%;text-align:right;">Rate</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results as $row)
            <tr>
                <td>{{ $row['date'] }}</td>
                <td>{{ $row['gl_no'] }}</td>
                <td>{{ $row['gl_desc'] }}</td>
                <td class="center">{{ $row['source'] }}</td>
                <td>{{ $row['ref_no'] }}</td>
                <td>{{ $row['office'] }}</td>
                <td>{{ $row['company'] }}</td>
                <td>{{ $row['description'] }}</td>
                <td class="num">{{ $row['debit'] ? number_format($row['debit'], 2) : '' }}</td>
                <td class="num">{{ $row['credit'] ? number_format($row['credit'], 2) : '' }}</td>
                <td class="num">{{ $row['foreign_amount'] ? number_format($row['foreign_amount'], 2) : '' }}</td>
                <td class="center">{{ $row['cur'] }}</td>
                <td class="num">{{ $row['rate'] != 1 ? number_format($row['rate'], 6) : '' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="13" style="text-align:center;color:#94a3b8;padding:40px;">No records found.</td>
            </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="8" style="text-align:right;">TOTAL</td>
                <td class="num">{{ number_format($totalDebit, 2) }}</td>
                <td class="num">{{ number_format($totalCredit, 2) }}</td>
                <td class="num"></td>
                <td class="center"></td>
                <td class="num"></td>
            </tr>
            <tr>
                <td colspan="13" class="record-count">{{ count($results) }} Record(s)</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Journal Report &middot; Period: {{ $startDate }} ~ {{ $endDate }} &middot; Generated: {{ now()->format('Y-m-d H:i:s') }}
    </div>

    @if($printMode)<script>window.onload=function(){window.print()}</script>@endif
</body>
</html>
