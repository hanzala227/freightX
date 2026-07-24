<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>General Journal | GoFreight</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { font-family: 'Open Sans', Arial, sans-serif; margin: 0; padding: 20px; color: #333; font-size: 11px; background: #fff; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { font-size: 18px; font-weight: 800; color: #1a4a7c; margin: 0; }
        .header .meta { text-align: right; font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10px; }
        th { background: #f0f4f8; color: #475569; font-weight: 600; border: 1px solid #cbd5e1; padding: 4px 6px; text-align: left; font-size: 9px; text-transform: uppercase; }
        td { border: 1px solid #e2e8f0; padding: 3px 6px; }
        .num { text-align: right; font-family: 'Courier New', monospace; }
        .total-row td { background: #f8fafc; font-weight: 700; border-top: 2px solid #475569; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 8px; }
        @media print { body { padding: 10px; } }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>GENERAL JOURNAL</h1>
            <div style="font-size:10px;color:#64748b;margin-top:4px;">
                GoFreight ERP — Accounting Module
            </div>
        </div>
        <div class="meta">
            <div>Generated: {{ now()->format('Y-m-d H:i:s') }}</div>
            <div>Records: {{ $entries->count() }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:30px;">#</th>
                <th style="width:90px;">Post Date</th>
                <th style="width:50px;">Seq</th>
                <th>Remark</th>
                <th style="width:80px;text-align:right;">Debit</th>
                <th style="width:80px;text-align:right;">Credit</th>
                <th style="width:60px;">Type</th>
                <th style="width:90px;">Issued By</th>
                <th style="width:60px;">Office</th>
            </tr>
        </thead>
        <tbody>
            @php $gDr = 0; $gCr = 0; @endphp
            @foreach($entries as $i => $entry)
                @php
                    $dr = $entry->lines->sum('local_debit');
                    $cr = $entry->lines->sum('local_credit');
                    $gDr += $dr;
                    $gCr += $cr;
                @endphp
                <tr>
                    <td style="text-align:center;">{{ $i + 1 }}</td>
                    <td>{{ $entry->entry_date?->format('Y-m-d') }}</td>
                    <td style="text-align:center;">{{ $entry->id }}</td>
                    <td>{{ $entry->remark ?? $entry->description ?? '' }}</td>
                    <td class="num">{{ number_format($dr, 2) }}</td>
                    <td class="num">{{ number_format($cr, 2) }}</td>
                    <td>Entry</td>
                    <td>{{ $entry->creator?->name ?? '' }}</td>
                    <td>{{ $entry->office?->code ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align:right;">Grand Total</td>
                <td class="num">{{ number_format($gDr, 2) }}</td>
                <td class="num">{{ number_format($gCr, 2) }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        General Journal Report — GoFreight ERP — Printed {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>
