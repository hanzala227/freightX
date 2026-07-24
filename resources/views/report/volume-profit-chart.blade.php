<x-layout>
    @push('styles')
    <x-form-styles />
    <style>
        .rpt-label { background: #f0f4f8; border: 1px solid #e2e8f0; border-right: none; padding: 4px 10px; font-size: 10px; font-weight: 700; color: #475569; text-transform: uppercase; display: flex; align-items: center; height: 28px; border-radius: 2px 0 0 2px; white-space: nowrap; letter-spacing: 0.3px; }
        .rpt-input-wrap { border: 1px solid #d1d5db; border-radius: 0 2px 2px 0; padding: 3px 6px; display: flex; align-items: center; min-height: 28px; background: #fff; gap: 8px; }
        .rpt-input-wrap .form-control-gf { border: none; box-shadow: none; padding: 0; height: 20px; background: transparent; }
        .rpt-input-wrap .form-control-gf:focus { box-shadow: none; border: none; outline: none; }
        .rpt-input-wrap select.form-control-gf { padding-right: 14px; }
        .rpt-row { display: flex; gap: 0; margin-bottom: 6px; }
        .rpt-row .rpt-label { min-width: 120px; max-width: 140px; flex-shrink: 0; }
        .rpt-row .rpt-input-wrap { flex: 1; min-width: 0; }
        .rpt-inline-label { font-size: 10px; color: #64748b; font-weight: 600; }
        .rpt-radio-group { display: flex; gap: 10px; align-items: center; }
        .rpt-radio-group label, .rpt-chk-group label { font-size: 10px; display: flex; align-items: center; gap: 3px; cursor: pointer; color: #334155; white-space: nowrap; }
        .rpt-radio-group input[type="radio"], .rpt-chk-group input[type="checkbox"] { width: 12px !important; height: 12px !important; accent-color: #3b82f6; }
        .rpt-chk-group { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        .rpt-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 6px 20px; }
        .rpt-filter-section { padding: 10px 14px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
        .rpt-view-btn { background: #3b82f6; color: #fff; border: none; padding: 6px 24px; font-size: 11px; font-weight: 700; border-radius: 3px; cursor: pointer; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 8px; transition: all 0.2s; }
        .rpt-view-btn:hover { background: #2563eb; }
        .rpt-view-btn:active { transform: translateY(1px); }
        .kpi-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 4px; padding: 12px 14px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 2px rgba(0,0,0,0.02); transition: box-shadow 0.2s; }
        .kpi-card:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.06); }
        .kpi-label { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.3px; }
        .kpi-value { font-size: 18px; font-weight: 700; color: #0f172a; margin-top: 2px; }
        .kpi-icon { font-size: 20px; opacity: 0.15; }
        .chart-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 4px; padding: 14px; }
        .chart-title { font-size: 11px; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
        .btn-chart { background: #64748b; color: #fff; border: none; padding: 3px 10px; font-size: 10px; border-radius: 2px; cursor: pointer; font-weight: 600; transition: all 0.2s; }
        .btn-chart:hover, .btn-chart.active { background: #3b82f6; }
        .lane-card { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: #fff; padding: 14px 16px; border-radius: 4px; }
        .lane-label { font-size: 10px; font-weight: 700; text-transform: uppercase; opacity: 0.85; }
        .lane-route { font-size: 13px; font-weight: 700; margin-top: 6px; display: flex; align-items: center; gap: 6px; }
        .lane-progress { margin-top: 10px; background: rgba(255,255,255,0.15); height: 4px; border-radius: 2px; }
        .lane-progress-bar { background: #fff; height: 100%; border-radius: 2px; }
        .lane-stats { display: flex; justify-content: space-between; font-size: 10px; font-weight: 700; margin-top: 5px; text-transform: uppercase; }
        .loading-overlay { position: absolute; inset: 0; background: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center; z-index: 10; border-radius: 4px; }
        .spinner { width: 28px; height: 28px; border: 3px solid #e2e8f0; border-top-color: #3b82f6; border-radius: 50%; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .filter-tag { display: inline-flex; align-items: center; gap: 4px; background: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe; padding: 2px 8px; border-radius: 2px; font-size: 10px; font-weight: 600; }
    </style>
    @endpush

    <div style="background: #eef1f5; min-height: 100vh; padding: 12px;">
        <div x-data="volumeProfitReport()" x-init="init()" style="position: relative;">
            <div x-show="loading" class="loading-overlay"><div class="spinner"></div></div>

            <div style="font-size: 11px; color: #64748b; margin-bottom: 10px;">
                <a href="/" style="color: #64748b; text-decoration: none;" target="_blank"><i class="fa fa-home"></i> Home</a>
                <i class="fa fa-angle-right" style="margin: 0 4px; opacity: 0.5;"></i>
                <a href="/report" style="color: #64748b; text-decoration: none;">Reports</a>
                <i class="fa fa-angle-right" style="margin: 0 4px; opacity: 0.5;"></i>
                <span style="color: #0f172a; font-weight: 700;">Volume & Profit Chart</span>
            </div>

            <div class="portlet light">
                <div class="portlet-title">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa fa-bar-chart" style="color: #3b82f6; font-size: 12px;"></i>
                        <span class="caption-subject">Volume & Profit Intelligence</span>
                    </div>
                    <div style="display: flex; gap: 4px;">
                        <button class="btn-gofreight" @click="printReport()"><i class="fa fa-print"></i> PRINT</button>
                        <button class="btn-gofreight" style="background: #10b981;" @click="exportExcel()"><i class="fa fa-file-excel-o"></i> EXPORT</button>
                    </div>
                </div>

                <div class="rpt-filter-section">
                    <div class="rpt-grid-2">
                        <div>
                            <div class="rpt-row">
                                <div class="rpt-label">Period</div>
                                <div class="rpt-input-wrap" style="flex-direction:column; align-items:stretch; gap:4px; padding:4px 6px;">
                                    <div class="rpt-radio-group">
                                        <label><input type="radio" name="period_type" value="post_date" x-model="filters.period_type"> Post Date</label>
                                        <label><input type="radio" name="period_type" value="etd" x-model="filters.period_type"> ETD</label>
                                        <label><input type="radio" name="period_type" value="eta" x-model="filters.period_type"> ETA</label>
                                    </div>
                                    <div style="display:flex; gap:4px; align-items:center;">
                                        <input type="date" x-model="filters.date_from" class="form-control-gf" style="flex:1; border:1px solid #d1d5db; border-radius:2px; height:22px; padding:0 4px; font-size:10px;">
                                        <span style="font-size:10px; color:#64748b;">~</span>
                                        <input type="date" x-model="filters.date_to" class="form-control-gf" style="flex:1; border:1px solid #d1d5db; border-radius:2px; height:22px; padding:0 4px; font-size:10px;">
                                    </div>
                                </div>
                            </div>
                            <div class="rpt-row">
                                <div class="rpt-label">Shipping Type</div>
                                <div class="rpt-input-wrap">
                                    <div class="rpt-chk-group">
                                        @foreach($shippingTypes as $st)
                                        <label><input type="checkbox" value="{{ $st }}" x-model="filters.shipping_types"> {{ $st }}</label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="rpt-row">
                                <div class="rpt-label">Volume Unit</div>
                                <div class="rpt-input-wrap">
                                    <select x-model="filters.volume_unit" class="form-control-gf" style="width:100%;">
                                        @foreach($volumeUnits as $vu)
                                        <option value="{{ $vu['value'] }}">{{ $vu['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="rpt-row">
                                <div class="rpt-label">Chart Type (X-axis)</div>
                                <div class="rpt-input-wrap">
                                    <select x-model="filters.chart_type" class="form-control-gf" style="width:100%;">
                                        @foreach($chartTypes as $ct)
                                        <option value="{{ $ct['value'] }}">{{ $ct['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="rpt-row">
                                <div class="rpt-label">Office</div>
                                <div class="rpt-input-wrap">
                                    <select x-model="filters.office_id" class="form-control-gf" style="width:100%;">
                                        <option value="">Select...</option>
                                        @foreach($offices as $o)
                                        <option value="{{ $o->id }}">{{ $o->code }} - {{ $o->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="rpt-row">
                                <div class="rpt-label">Sales</div>
                                <div class="rpt-input-wrap">
                                    <select x-model="filters.sales_person_id" class="form-control-gf" style="width:100%;">
                                        <option value="">All</option>
                                        @foreach($salesPersons as $sp)
                                        <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="rpt-row">
                                <div class="rpt-label">Bar Segment</div>
                                <div class="rpt-input-wrap">
                                    <select x-model="filters.bar_segment" class="form-control-gf" style="width:100%;">
                                        @foreach($barSegments as $bs)
                                        <option value="{{ $bs['value'] }}">{{ $bs['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="rpt-row">
                                <div class="rpt-label">Status</div>
                                <div class="rpt-input-wrap">
                                    <div class="rpt-radio-group">
                                        @foreach($statuses as $s)
                                        <label><input type="radio" name="status_filter" value="{{ strtolower($s) }}" x-model="filters.status_filter"> {{ $s }}</label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="text-align:center; margin-top:4px;">
                        <button class="rpt-view-btn" @click="applyFilters()"><i class="fa fa-search" style="margin-right:4px;"></i> View</button>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 14px;">
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Total Gross Profit</div>
                        <div class="kpi-value" x-text="'$' + formatNum(data.summary.gross_profit)"></div>
                    </div>
                    <i class="fa fa-money kpi-icon" style="color: #10b981;"></i>
                </div>
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label" x-text="'Total Volume (' + data.volume_unit + ')'"></div>
                        <div class="kpi-value" x-text="formatNum(data.summary.total_volume) + ' ' + data.volume_unit"></div>
                    </div>
                    <i class="fa fa-cube kpi-icon" style="color: #3b82f6;"></i>
                </div>
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label" x-text="'Avg Profit/' + data.volume_unit"></div>
                        <div class="kpi-value" x-text="'$' + formatNum(data.summary.profit_per_unit)"></div>
                    </div>
                    <i class="fa fa-calculator kpi-icon" style="color: #8b5cf6;"></i>
                </div>
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Active Quotations</div>
                        <div class="kpi-value" x-text="formatInt(data.summary.shipment_count)"></div>
                    </div>
                    <i class="fa fa-file-text-o kpi-icon" style="color: #f59e0b;"></i>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div class="chart-card">
                    <div class="chart-title">
                        <span><i class="fa fa-line-chart" style="margin-right: 4px;"></i> Monthly Revenue, Cost & Profit Trend</span>
                        <div style="display: flex; gap: 2px;">
                            <button class="btn-chart" :class="{ active: chartType === 'bar' }" @click="switchChartType('bar')">BAR</button>
                            <button class="btn-chart" :class="{ active: chartType === 'line' }" @click="switchChartType('line')">LINE</button>
                            <button class="btn-chart" :class="{ active: chartType === 'area' }" @click="switchChartType('area')">AREA</button>
                        </div>
                    </div>
                    <div id="trendChart" style="height: 320px;"></div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <div class="chart-card">
                        <div class="chart-title"><i class="fa fa-pie-chart" style="margin-right: 4px;"></i> Profit by Shipping Type</div>
                        <div id="typeChart" style="height: 220px;"></div>
                    </div>
                    <div class="chart-card" x-show="data.top_lanes.length > 0">
                        <div class="chart-title"><i class="fa fa-trophy" style="margin-right: 4px;"></i> Top Performing Lane</div>
                        <template x-if="data.top_lanes.length > 0">
                            <div>
                                <div class="lane-card">
                                    <div class="lane-label">Best Route</div>
                                    <div class="lane-route">
                                        <span x-text="data.top_lanes[0]?.label?.split('→')[0]?.trim()"></span>
                                        <i class="fa fa-arrow-right" style="font-size: 9px;"></i>
                                        <span x-text="data.top_lanes[0]?.label?.split('→')[1]?.trim()"></span>
                                    </div>
                                    <div class="lane-progress">
                                        <div class="lane-progress-bar" :style="'width:' + getLaneProgress(data.top_lanes[0]) + '%'"></div>
                                    </div>
                                    <div class="lane-stats">
                                        <span x-text="'Profit: $' + formatNum(data.top_lanes[0]?.profit || 0)"></span>
                                        <span x-text="data.top_lanes[0]?.count + ' quotes'"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div class="chart-card">
                    <div class="chart-title"><i class="fa fa-building" style="margin-right: 4px;"></i> Profit by Office</div>
                    <div id="officeChart" style="height: 280px;"></div>
                </div>
                <div class="chart-card">
                    <div class="chart-title"><i class="fa fa-flag" style="margin-right: 4px;"></i> Quotations by Status</div>
                    <div id="statusChart" style="height: 280px;"></div>
                </div>
            </div>

            <div class="chart-card" x-show="data.top_lanes.length > 1" style="margin-bottom: 14px;">
                <div class="chart-title"><i class="fa fa-route" style="margin-right: 4px;"></i> Top Lanes by Profit</div>
                <div id="lanesChart" style="height: 300px;"></div>
            </div>

            <div class="chart-card" style="margin-bottom: 14px;">
                <div class="chart-title"><i class="fa fa-table" style="margin-right: 4px;"></i> Monthly Breakdown</div>
                <div style="overflow-x: auto;">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th style="text-align:left;">Month</th>
                                <th style="text-align:right;">Revenue (A/R)</th>
                                <th style="text-align:right;">Cost (A/P)</th>
                                <th style="text-align:right;">Net Profit</th>
                                <th style="text-align:right;" x-text="'Volume (' + data.volume_unit + ')'"></th>
                                <th style="text-align:center;"># Quotes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(row, idx) in data.monthly" :key="idx">
                                <tr>
                                    <td style="font-weight:600;" x-text="row.month"></td>
                                    <td style="text-align:right;" x-text="'$' + formatNum(row.revenue)"></td>
                                    <td style="text-align:right;" x-text="'$' + formatNum(row.cost)"></td>
                                    <td style="text-align:right;font-weight:700;" :style="'color:' + (row.profit >= 0 ? '#10b981' : '#ef4444')" x-text="'$' + formatNum(row.profit)"></td>
                                    <td style="text-align:right;" x-text="formatNum(row.volume)"></td>
                                    <td style="text-align:center;font-weight:600;" x-text="row.count"></td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td class="total-label-cell" style="text-align:left;">TOTAL</td>
                                <td class="total-val-cell" x-text="'$' + formatNum(data.summary.total_revenue)"></td>
                                <td class="total-val-cell" x-text="'$' + formatNum(data.summary.total_cost)"></td>
                                <td class="total-val-cell" :style="'color:' + (data.summary.gross_profit >= 0 ? '#10b981' : '#ef4444')" x-text="'$' + formatNum(data.summary.gross_profit)"></td>
                                <td class="total-val-cell" x-text="formatNum(data.summary.total_volume)"></td>
                                <td class="total-val-cell" x-text="data.summary.shipment_count"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
    function volumeProfitReport() {
        return {
            loading: false,
            chartType: 'bar',
            trendChart: null,
            typeChart: null,
            officeChart: null,
            statusChart: null,
            lanesChart: null,
            filters: {
                date_from: '{{ now()->subMonths(6)->startOfMonth()->format("Y-m-d") }}',
                date_to: '{{ now()->endOfMonth()->format("Y-m-d") }}',
                period_type: 'post_date',
                shipping_types: [],
                office_id: '',
                sales_person_id: '',
                volume_unit: 'cbm',
                chart_type: 'month',
                bar_segment: 'shipping_type',
                status_filter: 'all',
            },
            offices: @json($offices),
            salesPersons: @json($salesPersons),
            data: {
                summary: { gross_profit: 0, total_volume: 0, profit_per_unit: 0, shipment_count: 0, total_revenue: 0, total_cost: 0, total_weight_kg: 0 },
                monthly: [],
                by_shipping_type: [],
                by_office: [],
                by_status: [],
                top_lanes: [],
                volume_unit: 'CBM',
            },

            init() {
                this.fetchData();
            },

            async fetchData() {
                this.loading = true;
                try {
                    const params = new URLSearchParams();
                    params.append('date_from', this.filters.date_from);
                    params.append('date_to', this.filters.date_to);
                    params.append('volume_unit', this.filters.volume_unit);
                    params.append('period_type', this.filters.period_type);
                    params.append('chart_type', this.filters.chart_type);
                    params.append('bar_segment', this.filters.bar_segment);
                    params.append('status_filter', this.filters.status_filter);
                    if (this.filters.office_id) params.append('office_id', this.filters.office_id);
                    if (this.filters.sales_person_id) params.append('sales_person_id', this.filters.sales_person_id);
                    this.filters.shipping_types.forEach(t => params.append('shipping_types[]', t));

                    const resp = await fetch('{{ route("report.volume-profit-chart.data") }}?' + params.toString());
                    const json = await resp.json();
                    this.data = json;
                    this.$nextTick(() => this.renderCharts());
                } catch (e) {
                    console.error('Fetch error:', e);
                } finally {
                    this.loading = false;
                }
            },

            applyFilters() { this.fetchData(); },

            renderCharts() {
                this.renderTrendChart();
                this.renderTypeChart();
                this.renderOfficeChart();
                this.renderStatusChart();
                this.renderLanesChart();
            },

            renderTrendChart() {
                const el = document.getElementById('trendChart');
                if (!el) return;
                if (this.trendChart) this.trendChart.destroy();

                const labels = this.data.monthly.map(m => m.month);
                const revenue = this.data.monthly.map(m => m.revenue);
                const cost = this.data.monthly.map(m => m.cost);
                const profit = this.data.monthly.map(m => m.profit);

                this.trendChart = new ApexCharts(el, {
                    series: [
                        { name: 'Revenue (A/R)', data: revenue },
                        { name: 'Cost (A/P)', data: cost },
                        { name: 'Net Profit', data: profit },
                    ],
                    chart: { height: 320, type: this.chartType, toolbar: { show: false }, animations: { enabled: true, easing: 'easeinout', speed: 600 } },
                    stroke: { width: this.chartType === 'bar' ? 0 : 2, curve: 'smooth' },
                    plotOptions: { bar: { columnWidth: '55%', borderRadius: 2 } },
                    xaxis: { categories: labels, labels: { style: { colors: '#64748b', fontSize: '10px', fontWeight: 600 } } },
                    yaxis: { labels: { style: { colors: '#64748b', fontSize: '10px', fontWeight: 600 }, formatter: v => '$' + this.formatNum(v) } },
                    colors: ['#3b82f6', '#ef4444', '#10b981'],
                    legend: { position: 'top', horizontalAlign: 'right', fontSize: '10px', fontWeight: 600 },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                    tooltip: { y: { formatter: v => '$' + this.formatNum(v) } },
                });
                this.trendChart.render();
            },

            renderTypeChart() {
                const el = document.getElementById('typeChart');
                if (!el) return;
                if (this.typeChart) this.typeChart.destroy();

                const labels = this.data.by_shipping_type.map(t => t.label);
                const profits = this.data.by_shipping_type.map(t => Math.abs(t.profit));

                this.typeChart = new ApexCharts(el, {
                    series: profits.length > 0 ? profits : [1],
                    chart: { height: 220, type: 'donut' },
                    labels: labels.length > 0 ? labels : ['No Data'],
                    colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                    plotOptions: { pie: { donut: { size: '55%' } } },
                    legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
                    dataLabels: { enabled: true, formatter: (val, opts) => '$' + this.formatNum(opts.w.config.series[opts.seriesIndex]) },
                });
                this.typeChart.render();
            },

            renderOfficeChart() {
                const el = document.getElementById('officeChart');
                if (!el) return;
                if (this.officeChart) this.officeChart.destroy();

                const labels = this.data.by_office.map(o => o.label);
                const profits = this.data.by_office.map(o => o.profit);

                this.officeChart = new ApexCharts(el, {
                    series: [{ name: 'Profit', data: profits }],
                    chart: { height: 280, type: 'bar', toolbar: { show: false } },
                    plotOptions: { bar: { columnWidth: '50%', borderRadius: 3, colors: { ranges: [{ from: -99999, to: 0, color: '#ef4444' }, { from: 0, to: 99999, color: '#3b82f6' }] } } },
                    xaxis: { categories: labels, labels: { style: { colors: '#64748b', fontSize: '10px', fontWeight: 600 } } },
                    yaxis: { labels: { style: { colors: '#64748b', fontSize: '10px', fontWeight: 600 }, formatter: v => '$' + this.formatNum(v) } },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                    tooltip: { y: { formatter: v => '$' + this.formatNum(v) } },
                });
                this.officeChart.render();
            },

            renderStatusChart() {
                const el = document.getElementById('statusChart');
                if (!el) return;
                if (this.statusChart) this.statusChart.destroy();

                const statusColors = { Draft: '#64748b', Sent: '#3b82f6', Pending: '#f59e0b', Won: '#10b981', Lost: '#ef4444', Expired: '#f87171', Cancelled: '#fb923c', Ghosted: '#a78bfa' };
                const labels = this.data.by_status.map(s => s.label);
                const counts = this.data.by_status.map(s => s.count);
                const colors = this.data.by_status.map(s => statusColors[s.label] || '#94a3b8');

                this.statusChart = new ApexCharts(el, {
                    series: counts.length > 0 ? counts : [1],
                    chart: { height: 280, type: 'donut' },
                    labels: labels.length > 0 ? labels : ['No Data'],
                    colors: colors.length > 0 ? colors : ['#94a3b8'],
                    plotOptions: { pie: { donut: { size: '50%' } } },
                    legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
                });
                this.statusChart.render();
            },

            renderLanesChart() {
                const el = document.getElementById('lanesChart');
                if (!el || this.data.top_lanes.length <= 1) return;
                if (this.lanesChart) this.lanesChart.destroy();

                const lanes = this.data.top_lanes.slice(0, 10);
                const labels = lanes.map(l => l.label);
                const profits = lanes.map(l => l.profit);

                this.lanesChart = new ApexCharts(el, {
                    series: [{ name: 'Profit', data: profits }],
                    chart: { height: 300, type: 'bar', toolbar: { show: false } },
                    plotOptions: { bar: { columnWidth: '60%', borderRadius: 3, horizontal: true } },
                    xaxis: { categories: labels, labels: { style: { colors: '#64748b', fontSize: '10px', fontWeight: 600 } } },
                    yaxis: { labels: { style: { colors: '#64748b', fontSize: '10px', fontWeight: 600 }, formatter: v => '$' + this.formatNum(v) } },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                    colors: ['#3b82f6'],
                    tooltip: { y: { formatter: v => '$' + this.formatNum(v) } },
                });
                this.lanesChart.render();
            },

            switchChartType(type) {
                this.chartType = type;
                this.renderTrendChart();
            },

            formatNum(n) {
                if (n === null || n === undefined) return '0.00';
                return parseFloat(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            formatInt(n) {
                if (n === null || n === undefined) return '0';
                return parseInt(n).toLocaleString('en-US');
            },

            getLaneProgress(lane) {
                if (!lane || this.data.top_lanes.length === 0) return 0;
                const maxProfit = Math.max(...this.data.top_lanes.map(l => Math.abs(l.profit)));
                return maxProfit > 0 ? Math.min((Math.abs(lane.profit) / maxProfit) * 100, 100) : 0;
            },

            printReport() { window.print(); },

            exportExcel() {
                let csv = 'Month,Revenue,Cost,Net Profit,Volume,Quotes\n';
                this.data.monthly.forEach(m => {
                    csv += `"${m.month}",${m.revenue},${m.cost},${m.profit},${m.volume},${m.count}\n`;
                });
                csv += `"TOTAL",${this.data.summary.total_revenue},${this.data.summary.total_cost},${this.data.summary.gross_profit},${this.data.summary.total_volume},${this.data.summary.shipment_count}\n`;
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url; a.download = 'volume-profit-report.csv'; a.click();
                URL.revokeObjectURL(url);
            },
        };
    }
    </script>
</x-layout>
