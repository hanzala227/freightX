<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check/Deposit Report</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', 'Open Sans', Arial, sans-serif; font-size: 11px; color: #333; margin: 20px; }
        .report-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1e293b; padding-bottom: 12px; }
        .report-header h1 { font-size: 16px; font-weight: 700; color: #0f172a; margin: 0 0 4px 0; }
        .report-header .subtitle { font-size: 12px; color: #64748b; }
        .report-header .period { font-size: 11px; color: #475569; margin-top: 4px; }

        .section-title { font-size: 12px; font-weight: 700; color: #475569; padding: 6px 0 4px 0; border-bottom: 1px solid #e2e8f0; margin: 16px 0 8px 0; }

        table { border-collapse: collapse; width: 100%; margin-bottom: 8px; }
        th { background: #f1f5f9; color: #475569; font-weight: 600; border: 1px solid #cbd5e1; padding: 4px 6px; white-space: nowrap; text-align: left; font-size: 10px; }
        th.num { text-align: right; }
        td { padding: 3px 6px; border: 1px solid #e2e8f0; white-space: nowrap; color: #334155; font-size: 10px; }
        td.num { text-align: right; font-family: 'Courier New', monospace; }
        .total-row { background: #f1f5f9 !important; font-weight: 700; }
        .total-row td { border-top: 2px solid #475569; color: #0f172a; }

        .status-badge { display: inline-block; padding: 1px 4px; border-radius: 2px; font-size: 8px; font-weight: 600; text-transform: uppercase; }
        .status-cleared { background: #dcfce7; color: #166534; }
        .status-outstanding { background: #fef9c3; color: #854d0e; }
        .status-void { background: #fee2e2; color: #991b1b; }

        .summary-boxes { display: flex; gap: 16px; margin-bottom: 12px; }
        .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px 14px; min-width: 140px; }
        .summary-box .label { font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: 600; }
        .summary-box .value { font-size: 14px; font-weight: 700; color: #1e293b; margin-top: 2px; }
        .summary-box .value.green { color: #16a34a; }
        .summary-box .value.red { color: #dc2626; }
        .summary-box .value.blue { color: #3b82f6; }

        .print-footer { text-align: right; font-size: 9px; color: #94a3b8; margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 8px; }
        @media print { body { margin: 10mm; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align:right;margin-bottom:10px;">
        <button onclick="window.print()" style="background:#3b82f6;color:#fff;border:none;padding:6px 14px;border-radius:4px;cursor:pointer;font-size:11px;font-weight:600;"><i class="fa fa-print"></i> Print</button>
        <button onclick="window.close()" style="background:#64748b;color:#fff;border:none;padding:6px 14px;border-radius:4px;cursor:pointer;font-size:11px;font-weight:600;margin-left:6px;"><i class="fa fa-times"></i> Close</button>
    </div>

    <div class="report-header">
        <h1>CHECK / DEPOSIT REPORT</h1>
        <div class="subtitle">Summary by Bank</div>
        <div class="period">As of: {{ $effective_date }} &middot; Sort by: {{ ucfirst($summary_sort) }}</div>
    </div>

    <div class="summary-boxes">
        <div class="summary-box">
            <div class="label">Total Deposits</div>
            <div class="value green">${{ number_format($grand_total['deposit'], 2) }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Total Check Paid</div>
            <div class="value red">${{ number_format($grand_total['check_paid'], 2) }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Net Amount</div>
            <div class="value {{ $grand_total['total'] < 0 ? 'red' : ($grand_total['total'] > 0 ? 'green' : 'blue') }}">${{ number_format($grand_total['total'], 2) }}</div>
        </div>
    </div>

    <div class="section-title">SUMMARY BY BANK</div>
    <table>
        <thead>
            <tr>
                <th>Bank Name</th>
                <th class="num">Record(s)</th>
                <th class="num">Deposit</th>
                <th class="num">Check Paid</th>
                <th class="num">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary_rows as $row)
                <tr>
                    <td style="font-weight:600;">{{ $row['bank_name'] }}</td>
                    <td class="num">{{ $row['record_count'] }} Record(s).</td>
                    <td class="num" style="color:#16a34a;">${{ number_format($row['deposit'], 2) }}</td>
                    <td class="num" style="color:#dc2626;">${{ number_format($row['check_paid'], 2) }}</td>
                    <td class="num fw-600" style="color:{{ $row['total'] < 0 ? '#dc2626' : ($row['total'] > 0 ? '#16a34a' : '#475569') }};">${{ number_format($row['total'], 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>Grand Total</td>
                <td class="num">{{ $grand_total['record_count'] }} Record(s).</td>
                <td class="num">${{ number_format($grand_total['deposit'], 2) }}</td>
                <td class="num">${{ number_format($grand_total['check_paid'], 2) }}</td>
                <td class="num" style="color:{{ $grand_total['total'] < 0 ? '#dc2626' : ($grand_total['total'] > 0 ? '#16a34a' : '#475569') }};">${{ number_format($grand_total['total'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if(!empty($detail_rows))
        <div class="section-title" style="margin-top:20px;">DETAIL</div>
        <table>
            <thead>
                <tr>
                    <th>Payment No.</th>
                    <th>Date</th>
                    <th>Bank</th>
                    <th>Type</th>
                    <th>Check No.</th>
                    <th>Party</th>
                    <th>Cur</th>
                    <th class="num">Amount</th>
                    <th>Office</th>
                    <th>Clear Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail_rows as $row)
                    <tr>
                        <td>{{ $row['payment_no'] }}</td>
                        <td>{{ $row['payment_date'] }}</td>
                        <td>{{ $row['bank_name'] }}</td>
                        <td>{{ $row['type'] === 'RECEIVED' ? 'DEPOSIT' : 'CHECK' }}</td>
                        <td>{{ $row['check_no'] }}</td>
                        <td>{{ $row['party_name'] }}</td>
                        <td>{{ $row['currency'] }}</td>
                        <td class="num">{{ number_format($row['amount'], 2) }}</td>
                        <td>{{ $row['office'] }}</td>
                        <td>{{ $row['clear_date'] }}</td>
                        <td><span class="status-badge status-{{ strtolower($row['status']) }}">{{ $row['status'] }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="print-footer">
        Generated {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>
