<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1099 Report - {{ $fiscalYear }}</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter','Open Sans',Arial,sans-serif;font-size:11px;color:#1e293b;padding:20px;background:#fff}
        .company{text-align:center;margin-bottom:16px;padding-bottom:14px;border-bottom:2px solid #1e293b}
        .company h1{font-size:18px;font-weight:700;color:#2563eb;margin-bottom:4px;letter-spacing:1px}
        .company .addr{font-size:10px;color:#475569;line-height:1.5}
        .title{text-align:center;margin:14px 0;font-size:15px;font-weight:700;color:#1e293b;text-transform:uppercase;letter-spacing:1.5px}
        .subtitle{text-align:center;font-size:11px;color:#64748b;margin-bottom:16px}
        .meta{display:flex;gap:30px;margin-bottom:14px;font-size:11px;color:#475569}
        .meta span{font-weight:700;color:#1e293b}
        .vendor-block{margin-bottom:16px;border:1px solid #e2e8f0;border-radius:2px;page-break-inside:avoid}
        .vendor-header{background:#f8fafc;padding:8px 12px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center}
        .vendor-header .name{font-size:12px;font-weight:700;color:#1e293b}
        .vendor-header .tax{font-size:10px;color:#64748b}
        .vendor-header .tot{font-size:11px;font-weight:600;color:#334155}
        table{width:100%;border-collapse:collapse}
        th{background:#4a5568;color:#fff;font-weight:600;border:1px solid #2d3748;padding:5px 8px;text-align:left;font-size:10px}
        td{padding:4px 8px;border:1px solid #e2e8f0;color:#334155;font-size:11px}
        td.num{text-align:right;font-family:'Courier New',monospace}
        .grand-total td{background:#1e293b;color:#fff;font-weight:700;font-size:11px;border:1px solid #0f172a}
        .summary{margin-bottom:14px;display:flex;gap:20px;flex-wrap:wrap}
        .summary .box{flex:1;min-width:120px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:4px;padding:8px 12px}
        .summary .box .lbl{font-size:9px;color:#64748b;text-transform:uppercase;font-weight:600}
        .summary .box .val{font-size:14px;font-weight:700;color:#0f172a;font-family:'Courier New',monospace}
        .footer{margin-top:20px;border-top:1px solid #e2e8f0;padding-top:10px;font-size:9px;color:#94a3b8;text-align:center}
        @media print{body{padding:10px}}
    </style>
</head>
<body>
    <div class="company">
        <h1>GoFreight</h1>
        <div class="addr">12F, No.186, Sec. 1, Fu-Xing S. Rd., Da-An District,<br>Taipei City 106, Taiwan R.O.C.<br>TEL: 886-2-2708-5068 &nbsp; FAX: 886-2-2708-5067</div>
    </div>

    <div class="title">1099 REPORT</div>
    <div class="subtitle">Tax Year {{ $fiscalYear }}</div>

    <div class="summary">
        <div class="box">
            <div class="lbl">Total Vendors</div>
            <div class="val">{{ $summary['total_vendors'] ?? 0 }}</div>
        </div>
        <div class="box">
            <div class="lbl">Total Payments</div>
            <div class="val">{{ $summary['total_payments'] ?? 0 }}</div>
        </div>
        <div class="box">
            <div class="lbl">Total Amount Paid</div>
            <div class="val">$ {{ number_format($summary['total_amount'] ?? 0, 2) }}</div>
        </div>
    </div>

    @forelse($results as $vendor)
    <div class="vendor-block">
        <div class="vendor-header">
            <div>
                <span class="name">{{ $vendor['name'] }}</span>
                @if($vendor['tax_id'])<span class="tax"> &mdash; TIN: {{ $vendor['tax_id'] }}</span>@endif
            </div>
            <span class="tot">$ {{ number_format($vendor['total_amount'], 2) }}</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width:12%;">Date</th>
                    <th style="width:18%;">Payment No.</th>
                    <th style="width:12%;">Check No.</th>
                    <th style="width:10%;">Type</th>
                    <th style="width:30%;">Reference</th>
                    <th style="width:18%;text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendor['payments'] as $pmt)
                <tr>
                    <td>{{ $pmt['payment_date'] }}</td>
                    <td>{{ $pmt['payment_no'] }}</td>
                    <td>{{ $pmt['check_no'] }}</td>
                    <td>{{ $pmt['type'] }}</td>
                    <td>{{ $pmt['reference_no'] }}</td>
                    <td class="num">{{ number_format($pmt['amount'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="grand-total">
                    <td colspan="5" style="text-align:right;">Subtotal</td>
                    <td class="num">{{ number_format($vendor['total_amount'], 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @empty
    <div style="text-align:center;color:#94a3b8;padding:40px;font-size:12px;">No 1099-tracked payments found for this period.</div>
    @endforelse

    <div class="footer">
        1099 Report &middot; Tax Year {{ $fiscalYear }} &middot; Generated: {{ now()->format('Y-m-d H:i:s') }}
    </div>

    <script>window.onload=function(){window.print()}</script>
</body>
</html>
