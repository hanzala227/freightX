<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Container Storage Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            padding: 20px;
            background: #fff;
        }
        
        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
        }
        
        .report-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        
        .report-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 10px;
        }
        
        .info-row {
            display: flex;
            gap: 20px;
        }
        
        .info-item {
            display: flex;
            gap: 5px;
        }
        
        .info-label {
            font-weight: 700;
        }
        
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .report-table th {
            background: #4b4b4b;
            color: #fff;
            padding: 8px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            border: 1px solid #333;
        }
        
        .report-table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        
        .report-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .report-table tbody tr:hover {
            background: #f0f0f0;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .report-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }
        
        .total-row {
            background: #e8e8e8 !important;
            font-weight: 700;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
        
        @media print {
            body {
                padding: 10px;
            }
            
            .no-print {
                display: none;
            }
            
            .report-table {
                page-break-inside: auto;
            }
            
            .report-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
        
        .print-button {
            background: #5b9bd1;
            color: #fff;
            border: none;
            padding: 8px 24px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 3px;
            cursor: pointer;
            margin-bottom: 15px;
        }
        
        .print-button:hover {
            background: #4a8bc2;
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px;">
        <button class="print-button" onclick="window.print()">
            <i class="fa fa-print"></i> Print Report
        </button>
        <button class="print-button" onclick="window.close()" style="background: #95a5a6;">
            <i class="fa fa-times"></i> Close
        </button>
    </div>

    <div class="report-container">
        <div class="report-header">
            <div class="report-title">Container Storage Report</div>
        </div>
        
        <div class="report-info">
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Period:</span>
                    <span>{{ request('date_from') }} - {{ request('date_to') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Customer:</span>
                    <span>{{ $partyName }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Office:</span>
                    <span>{{ $officeName }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Department:</span>
                    <span>{{ $departmentTypes }}</span>
                </div>
            </div>
        </div>
        
        @if(count($containers) > 0)
        <table class="report-table">
            <thead>
                <tr>
                    <th>Container #</th>
                    <th>TP/SZ</th>
                    <th>File #</th>
                    <th>MBL #</th>
                    <th>HBL #</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th class="text-right">Days</th>
                </tr>
            </thead>
            <tbody>
                @foreach($containers as $container)
                <tr>
                    <td>{{ $container['container_no'] ?? 'N/A' }}</td>
                    <td>{{ $container['tp_sz'] ?? '-' }}</td>
                    <td>{{ $container['file_no'] ?? '-' }}</td>
                    <td>{{ $container['mbl_no'] ?? '-' }}</td>
                    <td>{{ $container['hbl_no'] ?? '-' }}</td>
                    <td>{{ $container['start_date'] ? \Carbon\Carbon::parse($container['start_date'])->format('m-d-Y') : '-' }}</td>
                    <td>{{ $container['end_date'] ? \Carbon\Carbon::parse($container['end_date'])->format('m-d-Y') : '-' }}</td>
                    <td class="text-right">{{ $container['storage_days'] ?? '0' }}</td>
                </tr>
                @endforeach
                
                <tr class="total-row">
                    <td colspan="7" class="text-right">Total: {{ count($containers) }} Container(s)</td>
                    <td class="text-right">{{ $totalDays }} Day(s)</td>
                </tr>
            </tbody>
        </table>
        @else
        <div class="no-data">
            <i class="fa fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
            No containers found matching the selected criteria.
        </div>
        @endif
        
        <div class="report-footer">
            <div>
                <strong>Generated:</strong> {{ now()->format('m-d-Y H:i:s') }}
            </div>
            <div>
                <strong>Generated by:</strong> {{ auth()->user()->name ?? 'System' }}
            </div>
        </div>
    </div>
</body>
</html>
