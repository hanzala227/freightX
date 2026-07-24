<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bank Reconciliation - {{ $bank_name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Open Sans', Arial, sans-serif; font-size: 11px; color: #333; padding: 15px; background: #fff; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #1e293b; padding-bottom: 8px; margin-bottom: 15px; }
        .header h1 { font-size: 14px; font-weight: 700; margin-bottom: 4px; }
        .header p { font-size: 10px; color: #64748b; }
        .header-right { text-align: right; font-size: 10px; color: #64748b; }

        .summary-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 16px; }
        .summary-col h3 { font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 10px; color: #475569; letter-spacing: 0.3px; }
        .srow { display: flex; justify-content: space-between; padding: 3px 0; font-size: 10px; border-bottom: 1px dotted #e2e8f0; }
        .srow .lbl { color: #64748b; }
        .srow .val { font-family: 'Courier New', monospace; font-weight: 600; color: #334155; }
        .srow.total { border-top: 1px solid #475569; border-bottom: none; padding-top: 6px; }
        .srow.total .lbl { font-weight: 700; color: #1e293b; }
        .srow.total .val { color: #2563eb; }
        .diff-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px; font-size: 12px; font-weight: 700; text-align: center; margin-bottom: 20px; color: #475569; }

        .section-title { font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.3px; margin: 16px 0 8px; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 14px; border: 1px solid #e2e8f0; }
        th { background: #f1f5f9; color: #475569; padding: 4px 8px; font-weight: 600; text-align: left; border: 1px solid #e2e8f0; }
        td { padding: 3px 8px; border: 1px solid #e2e8f0; text-align: center; color: #334155; }
        .num { text-align: right; font-family: 'Courier New', monospace; }
        .total-row { background: #f8fafc !important; font-weight: 700; }
        .total-row td { border-top: 2px solid #475569; color: #0f172a; }

        .footer { margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 8px; font-size: 9px; color: #94a3b8; display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1><i class="fa fa-bank"></i> GO FREIGHT</h1>
            <p>Bank Reconciliation Report</p>
        </div>
        <div class="header-right">
            <div>Bank: <strong>{{ $bank_name }}</strong></div>
            <div>Currency: <strong>{{ $bank_currency }}</strong></div>
            <div>As of: <strong>{{ $period_date }}</strong></div>
            <div>Generated: {{ now()->format('Y-m-d H:i') }}</div>
        </div>
    </div>

    {{-- SUMMARY --}}
    <div class="summary-grid">
        <div class="summary-col">
            <h3>STATEMENT BALANCE</h3>
            <div class="srow"><span class="lbl">Beginning Balance</span><span class="val">{{ number_format($summary['statement']['beginning_balance'], 2) }}</span></div>
            <div class="srow"><span class="lbl">Deposit and Credit</span><span class="val">{{ number_format($summary['statement']['deposit_credit'], 2) }}</span></div>
            <div class="srow"><span class="lbl">Checks and Debit</span><span class="val">{{ number_format($summary['statement']['checks_debit'], 2) }}</span></div>
            <div class="srow total"><span class="lbl">Ending Balance</span><span class="val">{{ number_format($summary['statement']['ending_balance'], 2) }}</span></div>
        </div>
        <div class="summary-col">
            <h3>OUTSTANDING</h3>
            <div class="srow"><span class="lbl">Deposit and Credit</span><span class="val">{{ number_format($summary['outstanding']['deposit_credit'], 2) }}</span></div>
            <div class="srow"><span class="lbl">Checks and Debit</span><span class="val">{{ number_format($summary['outstanding']['checks_debit'], 2) }}</span></div>
            <div class="srow total"><span class="lbl">Actual Ending</span><span class="val">{{ number_format($summary['outstanding']['actual_ending'], 2) }}</span></div>
        </div>
        <div class="summary-col">
            <h3>BOOK BALANCE</h3>
            <div class="srow"><span class="lbl">Beginning Balance</span><span class="val">{{ number_format($summary['book']['beginning_balance'], 2) }}</span></div>
            <div class="srow"><span class="lbl">Deposit and Credit</span><span class="val">{{ number_format($summary['book']['deposit_credit'], 2) }}</span></div>
            <div class="srow"><span class="lbl">Checks and Debit</span><span class="val">{{ number_format($summary['book']['checks_debit'], 2) }}</span></div>
            <div class="srow total"><span class="lbl">Ending Balance</span><span class="val">{{ number_format($summary['book']['ending_balance'], 2) }}</span></div>
        </div>
    </div>

    @php $diff = $summary['bank_book_diff']; @endphp
    <div class="diff-box">BANK & BOOK DIFFERENCE: <span style="color:{{ $diff < 0 ? '#e73d4a' : '#3598dc' }};">{{ number_format($diff, 2) }}</span></div>

    {{-- DEPOSIT & CREDIT --}}
    <div class="section-title">Deposit & Credit</div>
    <table>
        <thead>
            <tr>
                <th style="width:80px;">Post Date</th>
                <th style="width:80px;">Check No.</th>
                <th>Received From</th>
                <th style="text-align:center;">Currency</th>
                <th style="text-align:right;">Amount</th>
                <th style="text-align:center;">Office</th>
                <th style="text-align:center;">Deposit</th>
                <th style="text-align:center;">Deposit date</th>
                <th style="text-align:center;">Void</th>
                <th style="text-align:center;">Void Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deposit_rows as $row)
                <tr>
                    <td>{{ $row['post_date'] }}</td>
                    <td>{{ $row['check_no'] }}</td>
                    <td>{{ $row['received_from'] }}</td>
                    <td style="text-align:center;">{{ $row['currency'] }}</td>
                    <td class="num">{{ number_format($row['amount'], 2) }}</td>
                    <td style="text-align:center;">{{ $row['office'] }}</td>
                    <td style="text-align:center;">{{ $row['deposit'] }}</td>
                    <td style="text-align:center;">{{ $row['deposit_date'] }}</td>
                    <td style="text-align:center;">{{ $row['void'] }}</td>
                    <td style="text-align:center;">{{ $row['void_date'] }}</td>
                </tr>
            @empty
                <tr><td colspan="10" style="padding:15px;color:#999;">No Data Available</td></tr>
            @endforelse
            @if(count($deposit_rows))
                <tr class="total-row">
                    <td colspan="4">Total ({{ count($deposit_rows) }})</td>
                    <td class="num">{{ number_format(collect($deposit_rows)->sum('amount'), 2) }}</td>
                    <td colspan="5"></td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- CHECKS & DEBIT --}}
    <div class="section-title">Checks & Debit</div>
    <table>
        <thead>
            <tr>
                <th style="width:80px;">Post Date</th>
                <th style="width:80px;">Check No.</th>
                <th>Pay To</th>
                <th style="text-align:center;">Currency</th>
                <th style="text-align:right;">Amount</th>
                <th style="text-align:center;">Office</th>
                <th style="text-align:center;">Clear</th>
                <th style="text-align:center;">Clear date</th>
                <th style="text-align:center;">Void</th>
                <th style="text-align:center;">Void Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($check_rows as $row)
                <tr>
                    <td>{{ $row['post_date'] }}</td>
                    <td>{{ $row['check_no'] }}</td>
                    <td>{{ $row['pay_to'] }}</td>
                    <td style="text-align:center;">{{ $row['currency'] }}</td>
                    <td class="num">{{ number_format($row['amount'], 2) }}</td>
                    <td style="text-align:center;">{{ $row['office'] }}</td>
                    <td style="text-align:center;">{{ $row['clear'] }}</td>
                    <td style="text-align:center;">{{ $row['clear_date'] }}</td>
                    <td style="text-align:center;">{{ $row['void'] }}</td>
                    <td style="text-align:center;">{{ $row['void_date'] }}</td>
                </tr>
            @empty
                <tr><td colspan="10" style="padding:15px;color:#999;">No Data Available</td></tr>
            @endforelse
            @if(count($check_rows))
                <tr class="total-row">
                    <td colspan="4">Total ({{ count($check_rows) }})</td>
                    <td class="num">{{ number_format(collect($check_rows)->sum('amount'), 2) }}</td>
                    <td colspan="5"></td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <span>Bank Reconciliation — {{ $bank_name }} — As of {{ $period_date }}</span>
        <span>Page 1 of 1</span>
    </div>

    <script>window.onload = function() { window.print(); };</script>
</body>
</html>
