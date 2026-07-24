<x-layout>
    @push('styles')
    <style>
        /* ========== DASHBOARD CUSTOM STYLES (MATCHING PICTURE 2) ========== */
        .page-content {
            padding: 20px;
            background: #eef1f5;
            min-height: 100vh;
        }

        .portlet.light {
            background-color: #fff;
            border: 1px solid #e7ecf1;
            border-radius: 6px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .portlet-title {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f3f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            background-color: #fafbfc;
            border-radius: 6px 6px 0 0;
            color: #2d3748;
            font-weight: 800;
        }

        .dashboard-stat2 {
            background: #fff;
            border: 1px solid #e7ecf1;
            border-top: 3px solid #4b77be;
            padding: 20px 15px;
            border-radius: 6px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .dashboard-stat2:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* Responsive KPI Row */
        .kpi-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        @media (max-width: 1200px) {
            .kpi-row { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) {
            .kpi-row { grid-template-columns: 1fr; }
        }

        /* Side by Side Layout */
        .side-by-side-row {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            align-items: stretch;
        }

        .side-navigation {
            flex: 0 0 450px;
            display: flex;
            flex-direction: column;
        }

        .side-table {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .side-table .portlet.light {
            flex: 1;
            margin-bottom: 0;
            display: flex;
            flex-direction: column;
        }

        .side-table .table-responsive {
            flex: 1;
        }

        @media (max-width: 1300px) {
            .side-by-side-row { flex-direction: column; }
            .side-navigation { flex: 1; width: 100%; }
            .side-table .portlet.light { flex: none; }
        }

        /* Charts Row */
        .charts-row {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            align-items: stretch;
        }

        .charts-row .portlet.light {
            flex: 1;
            margin-bottom: 0;
        }

        @media (max-width: 992px) {
            .charts-row { flex-direction: column; }
        }

        /* Table Styles */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            background: #fff;
            min-width: 600px;
        }

        .table-custom thead th {
            text-align: left;
            padding: 10px 15px;
            background: #fafbfc;
            color: #4b77be;
            font-weight: 800;
            text-transform: uppercase;
            border-bottom: 2px solid #eef1f5;
            letter-spacing: 0.3px;
        }

        .table-custom tbody tr:hover {
            background-color: #f9fbfd !important;
        }

        .table-custom tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f3f6;
            vertical-align: middle;
            color: #4a5568;
        }

        /* Status Badges */
        .badge-status {
            padding: 2px 8px;
            border-radius: 12px;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            display: inline-block;
        }

        .bg-pre-alert { background: #00827f; }
        .bg-arrival { background: #4b77be; }
        .bg-invoice { background: #d05454; }

        .section-header {
            font-size: 11px;
            font-weight: 900;
            color: #1a202c;
            text-transform: uppercase;
            margin: 25px 0 15px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: 0.8px;
        }

        .section-header i {
            color: #4b77be;
            background: rgba(75, 119, 190, 0.1);
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            font-size: 12px;
        }

        .btn-gofreight {
            background: #4b77be;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 800;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(75, 119, 190, 0.1);
        }

        .btn-gofreight:hover {
            background: #3a62a4;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(75, 119, 190, 0.2);
        }

        /* Chart stats row */
        .chart-stats-row { display: flex; justify-content: center; gap: 40px; padding: 15px; border-bottom: 1px solid #f1f3f6; margin-bottom: 15px; background: #fcfdfe; }
        .chart-stat-item { text-align: center; }
        .chart-stat-item h4 { margin: 0; font-size: 18px; font-weight: 700; color: #2d3748; }
        .chart-stat-item h4.text-primary { color: #0ab39c; }
        .chart-stat-item span { font-size: 11px; color: #718096; text-transform: uppercase; font-weight: 600; display: block; margin-top: 2px; }

        /* Badge styling */
        .badge-subtle-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px; display: inline-block; }
        .badge-subtle-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px; display: inline-block; }
        .badge-subtle-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px; display: inline-block; }
        .badge-subtle-info { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px; display: inline-block; }
        
        .tasks-list { list-style: none; padding: 0; margin: 0; }
        .task-item { display: flex; align-items: flex-start; padding: 12px 15px; border-bottom: 1px dashed #e2e8f0; }
        .task-item:last-child { border-bottom: none; }
        .task-item .task-checkbox { margin-top: 3px; margin-right: 12px; cursor: pointer; width: 14px !important; height: 14px !important; }
        .task-item .task-label { font-size: 12px; color: #2d3748; flex: 1; cursor: pointer; font-weight: 500; text-align: left; }
        .task-item .task-date { font-size: 11px; color: #a0aec0; margin-left: 10px; white-space: nowrap; font-weight: 500; }
    </style>
    @endpush

    <div class="page-content">
        <!-- ═══ 1. KPI ROW ═══ -->
        <div class="kpi-row">
            @php
                $kpiConfig = [
                    ['label' => 'TOTAL PROFIT', 'val' => '$ ' . number_format($kpis['totalProfit'], 0), 'color' => '#4b77be', 'change' => $kpis['profitChangePercent']],
                    ['label' => 'TOTAL VOLUME', 'val' => number_format($kpis['totalVolume']), 'color' => '#00827f', 'change' => $kpis['volumeChangePercent']],
                    ['label' => 'NO. OF ACTIVE CUSTOMERS', 'val' => number_format($kpis['activeCustomers']), 'color' => '#d05454', 'change' => null],
                    ['label' => 'NO. OF LOST CUSTOMERS', 'val' => number_format($kpis['lostCustomers']), 'color' => '#f2bc00', 'change' => null],
                ];
            @endphp
            @foreach($kpiConfig as $kpi)
            <div class="dashboard-stat2">
                <div style="text-align: center;">
                    <small style="color: #8e9eae; font-weight: 700; font-size: 10px;">{{ $kpi['label'] }}</small>
                    <h3 style="color: {{ $kpi['color'] }}; font-size: 24px; font-weight: 700; margin: 5px 0;">{{ $kpi['val'] }}</h3>
                    <div style="font-size: 11px; color: #8e9eae;">
                        @if($kpi['change'] !== null)
                            {{ $kpi['change'] >= 0 ? '+' : '' }}{{ $kpi['change'] }}%
                        @else
                            --
                        @endif
                        <br> <small>Prev. : {{ number_format($kpis['prevProfit']) }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- ═══ 2. TO DO LIST ═══ -->
        <div class="section-header"><i class="fa fa-list"></i> TO DO LIST</div>
        <div class="portlet light" style="padding:0; border-top: 3px solid #4b77be;">
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th style="width:120px;">TASK</th>
                            <th>FILE NO.</th>
                            <th>ETA</th>
                            <th>MB/L</th>
                            <th>HB/L</th>
                            <th>POD</th>
                            <th>CONSIGNEE</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todos as $todo)
                        <tr>
                            <td><span class="badge-status {{ $todo['badgeClass'] }}">{{ $todo['task'] }}</span></td>
                            <td><a href="/{{ strtolower(str_replace('Import', '-import', str_replace('Export', '-export', $todo['shipment_type']))) }}/list" style="color:#4b77be;">{{ $todo['file_no'] }}</a></td>
                            <td>{{ $todo['eta'] }}</td>
                            <td>{{ $todo['mbl'] }}</td>
                            <td>{{ $todo['hbl'] }}</td>
                            <td>{{ $todo['pod'] }}</td>
                            <td>{{ $todo['consignee'] }}</td>
                            <td style="color:#8e9eae; font-size:10px;">{{ $todo['status'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:20px; color:#94a3b8;">
                                <i class="fa fa-inbox" style="font-size:20px;display:block;margin-bottom:6px;"></i>
                                No pending tasks.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ═══ 3. COMBINED SIDE SECTION ═══ -->


        <!-- ═══ 4. INTERACTIVE CHARTS ═══ -->
        <div class="charts-row" style="margin-bottom: 25px;">
            <!-- Balance Overview -->
            <div class="portlet light" style="flex: 2; border-top: 3px solid #3b82f6; display: flex; flex-direction: column;">
                <div class="portlet-title">
                    <span style="font-size:12px; font-weight:700;"><i class="fa fa-line-chart"></i> BALANCE OVERVIEW</span>
                    <div style="font-size:11px; color:#718096; font-weight:normal;">
                        Sort by:
                        <select id="balance-period" onchange="reloadBalanceChart(this.value)" style="font-weight:700; color:#2d3748; background:transparent; border:1px solid #e2e8f0; border-radius:4px; padding:2px 6px; font-size:11px; cursor:pointer;">
                            <option value="3">Last 3 Months</option>
                            <option value="6" selected>Last 6 Months</option>
                            <option value="12">Last 12 Months</option>
                            <option value="24">Last 24 Months</option>
                        </select>
                    </div>
                </div>
                <div class="chart-stats-row" id="balance-stats-row">
                    <div class="chart-stat-item">
                        <h4 class="text-primary">${{ round($balanceChart['totalRevenue'] / 1000, 1) }}k</h4>
                        <span>Revenue</span>
                    </div>
                    <div class="chart-stat-item" style="border-left: 1px solid #edf2f7; padding-left: 30px;">
                        <h4>${{ round($balanceChart['totalExpenses'] / 1000, 1) }}k</h4>
                        <span>Expenses</span>
                    </div>
                    <div class="chart-stat-item" style="border-left: 1px solid #edf2f7; padding-left: 30px;">
                        <h4 style="color:#f59e0b;">{{ $balanceChart['profitRatio'] }}%</h4>
                        <span>Profit Ratio</span>
                    </div>
                </div>
                <div style="padding: 10px 15px; flex: 1;">
                    <div id="balance-overview-chart" style="min-height: 290px;"></div>
                </div>
            </div>

            <!-- Sales Forecast -->
            <div class="portlet light" style="flex: 1; border-top: 3px solid #8b5cf6; display: flex; flex-direction: column;">
                <div class="portlet-title">
                    <span style="font-size:12px; font-weight:700;"><i class="fa fa-bar-chart"></i> SALES FORECAST</span>
                    <div style="font-size:11px; color:#718096; font-weight:normal;">
                        Sort by:
                        <select id="forecast-period" onchange="reloadForecastChart(this.value)" style="font-weight:700; color:#2d3748; background:transparent; border:1px solid #e2e8f0; border-radius:4px; padding:2px 6px; font-size:11px; cursor:pointer;">
                            <option value="3">Last 3 Months</option>
                            <option value="6">Last 6 Months</option>
                            <option value="12" selected>Last 12 Months</option>
                            <option value="24">Last 24 Months</option>
                        </select>
                    </div>
                </div>
                <div style="padding: 20px 15px; flex: 1;">
                    <div id="sales-forecast-chart" style="min-height: 320px;"></div>
                </div>
            </div>
        </div>

        <!-- ═══ 5. TASKS STATUS TABLE ═══ -->
        <div class="section-header"><i class="fa fa-check-circle"></i> TASKS STATUS</div>
        <div class="portlet light" style="padding:0; border-top: 3px solid #10b981; margin-bottom: 30px;">
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Last Contacted</th>
                            <th>Sales Representative</th>
                            <th>Status</th>
                            <th>Deal Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                        <tr>
                            <td style="font-weight: 600; color:#2d3748;">{{ $task['name'] }}</td>
                            <td>{{ $task['last_contacted'] }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($task['sales_rep_name']) }}&background=4f46e5&color=fff&size=24" alt="{{ $task['sales_rep_name'] }}" style="width:24px; height:24px; border-radius:50%;">
                                    <span style="font-weight:500;">{{ $task['sales_rep_name'] }}</span>
                                </div>
                            </td>
                            <td><span class="{{ $task['badgeClass'] }}">{{ $task['status'] }}</span></td>
                            <td style="font-weight: 700; color: {{ $task['dealColor'] }};">
                                ${{ number_format($task['deal_value'] / 1000, 1) }}K
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px 10px; color:#94a3b8;">
                                <i class="fa fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                                No leads found. Create a lead to see tasks here.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Balance Overview Area Chart
            var balanceOptions = {
                series: [{
                    name: 'Revenue',
                    data: [{{ implode(',', $balanceChart['revenueData']) }}]
                }, {
                    name: 'Expenses',
                    data: [{{ implode(',', $balanceChart['expenseData']) }}]
                }],
                chart: {
                    id: 'balance-chart',
                    height: 290,
                    type: 'area',
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif',
                    animations: { dynamicAnimation: { speed: 300 } }
                },
                colors: ['#0ab39c', '#f06548'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.15, opacityTo: 0.02, stops: [0, 90, 100] }
                },
                xaxis: {
                    categories: ['{{ implode("','", $balanceChart['months']) }}'],
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: { formatter: function (v) { return "$" + v.toFixed(1) + "k"; } }
                },
                grid: { borderColor: '#f1f3f6', strokeDashArray: 3 },
                tooltip: { y: { formatter: function (v) { return "$" + v.toFixed(2); } } }
            };

            var balanceChart = new ApexCharts(document.querySelector("#balance-overview-chart"), balanceOptions);
            balanceChart.render();

            // 2. Sales Forecast Bar/Column Chart
            var forecastOptions = {
                series: [{
                    name: 'Goal',
                    data: [{{ $forecastChart['goal'] }}]
                }, {
                    name: 'Pending Forecast',
                    data: [{{ $forecastChart['pendingForecast'] }}]
                }, {
                    name: 'Revenue',
                    data: [{{ $forecastChart['revenue'] }}]
                }],
                chart: {
                    id: 'forecast-chart',
                    height: 320,
                    type: 'bar',
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif',
                    animations: { dynamicAnimation: { speed: 300 } }
                },
                colors: ['#405189', '#0ab39c', '#f7b84b'],
                plotOptions: {
                    bar: { horizontal: false, columnWidth: '40%', endingShape: 'rounded' }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (v) { return "$" + (v / 1000).toFixed(1) + "k"; },
                    style: { fontSize: '11px', colors: ['#fff'] }
                },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                xaxis: {
                    categories: ['{{ $forecastChart['categories'][0] }}'],
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    title: { text: 'Value ($)' },
                    labels: { formatter: function (v) { return "$" + (v / 1000).toFixed(0) + "k"; } }
                },
                fill: { opacity: 1 },
                grid: { borderColor: '#f1f3f6', strokeDashArray: 3 },
                legend: { position: 'bottom', horizontalAlign: 'center' }
            };

            var forecastChart = new ApexCharts(document.querySelector("#sales-forecast-chart"), forecastOptions);
            forecastChart.render();
        });

        function reloadBalanceChart(period) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/dashboard/chart-data?type=balance&period=' + period, true);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    ApexCharts.exec('balance-chart', 'updateOptions', {
                        series: [
                            { name: 'Revenue', data: data.revenueData },
                            { name: 'Expenses', data: data.expenseData }
                        ],
                        xaxis: { categories: data.months }
                    });
                    // Update stats row
                    var statsRow = document.getElementById('balance-stats-row');
                    if (statsRow) {
                        statsRow.innerHTML = '<div class="chart-stat-item"><h4 class="text-primary">$' + (data.totalRevenue / 1000).toFixed(1) + 'k</h4><span>Revenue</span></div><div class="chart-stat-item" style="border-left:1px solid #edf2f7;padding-left:30px;"><h4>$' + (data.totalExpenses / 1000).toFixed(1) + 'k</h4><span>Expenses</span></div><div class="chart-stat-item" style="border-left:1px solid #edf2f7;padding-left:30px;"><h4 style="color:#f59e0b;">' + data.profitRatio + '%</h4><span>Profit Ratio</span></div>';
                    }
                }
            };
            xhr.send();
        }

        function reloadForecastChart(period) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/dashboard/chart-data?type=forecast&period=' + period, true);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    ApexCharts.exec('forecast-chart', 'updateOptions', {
                        series: [
                            { name: 'Goal', data: [data.goal] },
                            { name: 'Pending Forecast', data: [data.pendingForecast] },
                            { name: 'Revenue', data: [data.revenue] }
                        ]
                    });
                }
            };
            xhr.send();
        }
    </script>
    @endpush
</x-layout>
