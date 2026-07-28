<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Log Report</title>
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
            max-width: 1400px;
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
            padding: 8px 4px;
            text-align: left;
            font-size: 9px;
            font-weight: 600;
            border: 1px solid #333;
        }
        
        .report-table td {
            padding: 6px 4px;
            border: 1px solid #ddd;
            font-size: 9px;
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
        
        .summary-row {
            background: #e8e8e8 !important;
            font-weight: 700;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
        
        .active-duration {
            color: #10b981;
            font-weight: 700;
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
            <div class="report-title">User Log In/Out Report</div>
        </div>
        
        <div class="report-info">
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Period:</span>
                    <span>{{ $dateFrom }} to {{ $dateTo }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">User:</span>
                    <span>{{ $userName }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Total Records:</span>
                    <span>{{ $totalRecords }}</span>
                </div>
            </div>
        </div>
        
        @if(count($rows) > 0)
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width:30px;" class="text-center">#</th>
                    <th style="width:90px;">User ID</th>
                    <th style="width:90px;">First Name</th>
                    <th style="width:90px;">Last Name</th>
                    <th style="width:80px;">Office</th>
                    <th style="width:110px;">Login</th>
                    <th style="width:110px;">Logout</th>
                    <th style="width:80px;">Duration</th>
                    <th style="width:80px;">Active</th>
                    <th style="width:80px;">Inactive</th>
                    <th style="width:100px;" class="text-center">Active Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $idx => $row)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td style="font-weight:600;">{{ $row['user_code'] ?? '' }}</td>
                    <td>{{ $row['first_name'] ?? '' }}</td>
                    <td>{{ $row['last_name'] ?? '--' }}</td>
                    <td>{{ $row['office'] ?? '--' }}</td>
                    <td>{{ $row['login'] ?? '' }}</td>
                    <td>{{ $row['logout'] ?? '--' }}</td>
                    <td>{{ $row['duration'] ?? '' }}</td>
                    <td>{{ $row['active'] ?? '' }}</td>
                    <td>{{ $row['inactive'] ?? '' }}</td>
                    <td class="text-center active-duration">{{ $row['active_duration'] ?? '' }}</td>
                </tr>
                @endforeach
                
                <tr class="summary-row">
                    <td colspan="11" class="text-right">Total: {{ count($rows) }} Record(s)</td>
                </tr>
            </tbody>
        </table>
        @else
        <div class="no-data">
            <i class="fa fa-key" style="font-size: 48px; margin-bottom: 15px; display: block; opacity: 0.3;"></i>
            No log entries found for the selected criteria.
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
