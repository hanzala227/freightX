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
        .rpt-row .rpt-label { min-width: 120px; max-width: 160px; flex-shrink: 0; }
        .rpt-row .rpt-input-wrap { flex: 1; min-width: 0; }
        .rpt-chk-group { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        .rpt-chk-group label { font-size: 10px; display: flex; align-items: center; gap: 3px; cursor: pointer; color: #334155; white-space: nowrap; }
        .rpt-chk-group input[type="checkbox"] { width: 12px !important; height: 12px !important; accent-color: #3b82f6; }
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
        .loading-overlay { position: absolute; inset: 0; background: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center; z-index: 10; border-radius: 4px; }
        .spinner { width: 28px; height: 28px; border: 3px solid #e2e8f0; border-top-color: #3b82f6; border-radius: 50%; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .tab-btn { background: none; border: none; padding: 8px 16px; font-size: 11px; font-weight: 600; color: #64748b; cursor: pointer; border-bottom: 2px solid transparent; transition: all 0.2s; }
        .tab-btn:hover { color: #0f172a; background: #f1f5f9; }
        .tab-btn.active { color: #3b82f6; border-bottom-color: #3b82f6; }
        .margin-bar { display: inline-flex; align-items: center; gap: 5px; }
        .margin-bar-track { width: 40px; height: 4px; background: #f0f3f8; border-radius: 2px; }
        .margin-bar-fill { height: 100%; border-radius: 2px; }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }
    </style>
    @endpush

    <div style="background: #eef1f5; min-height: 100vh; padding: 12px;">
        <div x-data="advancedReport()" x-init="init()" style="position: relative;">
            <div x-show="loading" class="loading-overlay"><div class="spinner"></div></div>

            <div style="font-size: 11px; color: #64748b; margin-bottom: 10px;">
                <a href="/" style="color: #64748b; text-decoration: none;" target="_blank"><i class="fa fa-home"></i> Home</a>
                <i class="fa fa-angle-right" style="margin: 0 4px; opacity: 0.5;"></i>
                <a href="/report" style="color: #64748b; text-decoration: none;">Reports</a>
                <i class="fa fa-angle-right" style="margin: 0 4px; opacity: 0.5;"></i>
                <span style="color: #0f172a; font-weight: 700;">Advanced Report</span>
            </div>

            <div class="portlet light">
                <div class="portlet-title">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa fa-gears" style="color: #3b82f6; font-size: 12px;"></i>
                        <span class="caption-subject">Advanced Analytical Report</span>
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
                                <div class="rpt-label">Shipment Type</div>
                                <div class="rpt-input-wrap">
                                    <div class="rpt-chk-group">
                                        @foreach($shippingTypes as $st)
                                        <label><input type="checkbox" value="{{ $st }}" x-model="filters.shipping_types"> {{ $st }}</label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="rpt-row">
                                <div class="rpt-label">Analysis Period</div>
                                <div class="rpt-input-wrap" style="flex-direction:column; align-items:stretch; gap:4px; padding:4px 6px;">
                                    <div style="display:flex; gap:4px; align-items:center;">
                                        <input type="date" x-model="filters.date_from" class="form-control-gf" style="flex:1; border:1px solid #d1d5db; border-radius:2px; height:22px; padding:0 4px; font-size:10px;">
                                        <span style="font-size:10px; color:#64748b;">~</span>
                                        <input type="date" x-model="filters.date_to" class="form-control-gf" style="flex:1; border:1px solid #d1d5db; border-radius:2px; height:22px; padding:0 4px; font-size:10px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="rpt-row">
                                <div class="rpt-label">Office</div>
                                <div class="rpt-input-wrap">
                                    <select x-model="filters.office_id" class="form-control-gf" style="width:100%;">
                                        <option value="">All Offices</option>
                                        @foreach($offices as $o)
                                        <option value="{{ $o->id }}">{{ $o->code }} - {{ $o->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="rpt-row">
                                <div class="rpt-label">Currency</div>
                                <div class="rpt-input-wrap">
                                    <select x-model="filters.currency_id" class="form-control-gf" style="width:100%;">
                                        <option value="">Report Currency: USD</option>
                                        @foreach($currencies as $c)
                                        <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="rpt-row" style="align-items:center;">
                                <div class="rpt-label">&nbsp;</div>
                                <div class="rpt-input-wrap">
                                    <label style="font-size:10px; display:flex; align-items:center; gap:4px; cursor:pointer; color:#334155;">
                                        <input type="checkbox" x-model="filters.include_internal" style="width:12px !important; height:12px !important;"> Include Internal Profit
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="text-align:center; margin-top:4px;">
                        <button class="rpt-view-btn" @click="applyFilters()"><i class="fa fa-search" style="margin-right:4px;"></i> Execute Analysis</button>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 14px;">
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Total Revenue</div>
                        <div class="kpi-value" x-text="'$' + fmt(data.summary.total_revenue)"></div>
                    </div>
                    <i class="fa fa-line-chart kpi-icon" style="color: #3b82f6;"></i>
                </div>
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Total Cost</div>
                        <div class="kpi-value" x-text="'$' + fmt(data.summary.total_cost)"></div>
                    </div>
                    <i class="fa fa-money kpi-icon" style="color: #ef4444;"></i>
                </div>
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Net Profit</div>
                        <div class="kpi-value" :style="'color:' + (data.summary.gross_profit >= 0 ? '#10b981' : '#ef4444')" x-text="'$' + fmt(data.summary.gross_profit)"></div>
                    </div>
                    <i class="fa fa-balance-scale kpi-icon" style="color: #10b981;"></i>
                </div>
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Total Shipments</div>
                        <div class="kpi-value" x-text="fmtInt(data.summary.total_count)"></div>
                    </div>
                    <i class="fa fa-ship kpi-icon" style="color: #f59e0b;"></i>
                </div>
            </div>

            <div class="chart-card" style="margin-bottom: 14px;">
                <div style="display: flex; border-bottom: 1px solid #e2e8f0;">
                    <button class="tab-btn" :class="{ active: activeTab === 'shipping' }" @click="switchTab('shipping')">By Shipping Type</button>
                    <button class="tab-btn" :class="{ active: activeTab === 'partner' }" @click="switchTab('partner')">By Trade Partner</button>
                    <button class="tab-btn" :class="{ active: activeTab === 'office' }" @click="switchTab('office')">By Office</button>
                    <button class="tab-btn" :class="{ active: activeTab === 'sales' }" @click="switchTab('sales')">By Sales Person</button>
                </div>

                <div class="tab-panel" :class="{ active: activeTab === 'shipping' }">
                    <div style="overflow-x: auto;">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th style="text-align:left;">Shipping Type</th>
                                    <th style="text-align:right;">Revenue</th>
                                    <th style="text-align:right;">Cost</th>
                                    <th style="text-align:right;">Profit</th>
                                    <th style="text-align:center;">Margin</th>
                                    <th style="text-align:right;">Volume (CBM)</th>
                                    <th style="text-align:center;">Shipments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(row, idx) in data.by_shipping_type" :key="idx">
                                    <tr>
                                        <td><span style="background:#eff6ff;color:#3b82f6;padding:2px 6px;border-radius:2px;font-weight:700;font-size:10px;" x-text="row.shipping_type"></span></td>
                                        <td style="text-align:right;" x-text="'$' + fmt(row.revenue)"></td>
                                        <td style="text-align:right;" x-text="'$' + fmt(row.cost)"></td>
                                        <td style="text-align:right;font-weight:700;" :style="'color:' + (row.profit >= 0 ? '#10b981' : '#ef4444')" x-text="'$' + fmt(row.profit)"></td>
                                        <td style="text-align:center;">
                                            <div class="margin-bar">
                                                <div class="margin-bar-track"><div class="margin-bar-fill" :style="'width:' + Math.min(Math.abs(row.margin), 100) + '%; background:' + (row.margin >= 0 ? '#10b981' : '#ef4444')"></div></div>
                                                <span style="font-size:10px;font-weight:700;" x-text="row.margin + '%'"></span>
                                            </div>
                                        </td>
                                        <td style="text-align:right;" x-text="fmt(row.volume)"></td>
                                        <td style="text-align:center;font-weight:600;" x-text="row.count"></td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td class="total-label-cell">TOTAL</td>
                                    <td class="total-val-cell" x-text="'$' + fmt(data.summary.total_revenue)"></td>
                                    <td class="total-val-cell" x-text="'$' + fmt(data.summary.total_cost)"></td>
                                    <td class="total-val-cell" :style="'color:' + (data.summary.gross_profit >= 0 ? '#10b981' : '#ef4444')" x-text="'$' + fmt(data.summary.gross_profit)"></td>
                                    <td class="total-val-cell" x-text="data.summary.margin + '%'"></td>
                                    <td class="total-val-cell" x-text="fmt(data.summary.total_volume)"></td>
                                    <td class="total-val-cell" x-text="data.summary.total_count"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="tab-panel" :class="{ active: activeTab === 'partner' }">
                    <div style="overflow-x: auto;">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th style="text-align:left;">Trade Partner</th>
                                    <th style="text-align:right;">Revenue</th>
                                    <th style="text-align:right;">Cost</th>
                                    <th style="text-align:right;">Profit</th>
                                    <th style="text-align:center;">Margin</th>
                                    <th style="text-align:right;">Volume (CBM)</th>
                                    <th style="text-align:center;">Shipments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(row, idx) in data.by_partner" :key="idx">
                                    <tr>
                                        <td style="font-weight:700;color:#3b82f6;" x-text="row.partner"></td>
                                        <td style="text-align:right;" x-text="'$' + fmt(row.revenue)"></td>
                                        <td style="text-align:right;" x-text="'$' + fmt(row.cost)"></td>
                                        <td style="text-align:right;font-weight:700;" :style="'color:' + (row.profit >= 0 ? '#10b981' : '#ef4444')" x-text="'$' + fmt(row.profit)"></td>
                                        <td style="text-align:center;">
                                            <div class="margin-bar">
                                                <div class="margin-bar-track"><div class="margin-bar-fill" :style="'width:' + Math.min(Math.abs(row.margin), 100) + '%; background:' + (row.margin >= 0 ? '#10b981' : '#ef4444')"></div></div>
                                                <span style="font-size:10px;font-weight:700;" x-text="row.margin + '%'"></span>
                                            </div>
                                        </td>
                                        <td style="text-align:right;" x-text="fmt(row.volume)"></td>
                                        <td style="text-align:center;font-weight:600;" x-text="row.count"></td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td class="total-label-cell">TOTAL</td>
                                    <td class="total-val-cell" x-text="'$' + fmt(data.summary.total_revenue)"></td>
                                    <td class="total-val-cell" x-text="'$' + fmt(data.summary.total_cost)"></td>
                                    <td class="total-val-cell" :style="'color:' + (data.summary.gross_profit >= 0 ? '#10b981' : '#ef4444')" x-text="'$' + fmt(data.summary.gross_profit)"></td>
                                    <td class="total-val-cell" x-text="data.summary.margin + '%'"></td>
                                    <td class="total-val-cell" x-text="fmt(data.summary.total_volume)"></td>
                                    <td class="total-val-cell" x-text="data.summary.total_count"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="tab-panel" :class="{ active: activeTab === 'office' }">
                    <div style="overflow-x: auto;">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th style="text-align:left;">Office</th>
                                    <th style="text-align:right;">Revenue</th>
                                    <th style="text-align:right;">Cost</th>
                                    <th style="text-align:right;">Profit</th>
                                    <th style="text-align:right;">Volume (CBM)</th>
                                    <th style="text-align:center;">Shipments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(row, idx) in data.by_office" :key="idx">
                                    <tr>
                                        <td style="font-weight:700;color:#3b82f6;" x-text="row.office"></td>
                                        <td style="text-align:right;" x-text="'$' + fmt(row.revenue)"></td>
                                        <td style="text-align:right;" x-text="'$' + fmt(row.cost)"></td>
                                        <td style="text-align:right;font-weight:700;" :style="'color:' + (row.profit >= 0 ? '#10b981' : '#ef4444')" x-text="'$' + fmt(row.profit)"></td>
                                        <td style="text-align:right;" x-text="fmt(row.volume)"></td>
                                        <td style="text-align:center;font-weight:600;" x-text="row.count"></td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td class="total-label-cell">TOTAL</td>
                                    <td class="total-val-cell" x-text="'$' + fmt(data.summary.total_revenue)"></td>
                                    <td class="total-val-cell" x-text="'$' + fmt(data.summary.total_cost)"></td>
                                    <td class="total-val-cell" :style="'color:' + (data.summary.gross_profit >= 0 ? '#10b981' : '#ef4444')" x-text="'$' + fmt(data.summary.gross_profit)"></td>
                                    <td class="total-val-cell" x-text="fmt(data.summary.total_volume)"></td>
                                    <td class="total-val-cell" x-text="data.summary.total_count"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="tab-panel" :class="{ active: activeTab === 'sales' }">
                    <div style="overflow-x: auto;">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th style="text-align:left;">Sales Person</th>
                                    <th style="text-align:right;">Revenue</th>
                                    <th style="text-align:right;">Cost</th>
                                    <th style="text-align:right;">Profit</th>
                                    <th style="text-align:center;">Shipments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(row, idx) in data.by_sales_person" :key="idx">
                                    <tr>
                                        <td style="font-weight:700;color:#3b82f6;" x-text="row.sales"></td>
                                        <td style="text-align:right;" x-text="'$' + fmt(row.revenue)"></td>
                                        <td style="text-align:right;" x-text="'$' + fmt(row.cost)"></td>
                                        <td style="text-align:right;font-weight:700;" :style="'color:' + (row.profit >= 0 ? '#10b981' : '#ef4444')" x-text="'$' + fmt(row.profit)"></td>
                                        <td style="text-align:center;font-weight:600;" x-text="row.count"></td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td class="total-label-cell">TOTAL</td>
                                    <td class="total-val-cell" x-text="'$' + fmt(data.summary.total_revenue)"></td>
                                    <td class="total-val-cell" x-text="'$' + fmt(data.summary.total_cost)"></td>
                                    <td class="total-val-cell" :style="'color:' + (data.summary.gross_profit >= 0 ? '#10b981' : '#ef4444')" x-text="'$' + fmt(data.summary.gross_profit)"></td>
                                    <td class="total-val-cell" x-text="data.summary.total_count"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function advancedReport() {
        return {
            loading: false,
            activeTab: 'shipping',
            filters: {
                date_from: '{{ now()->subMonths(6)->startOfMonth()->format("Y-m-d") }}',
                date_to: '{{ now()->endOfMonth()->format("Y-m-d") }}',
                shipping_types: [],
                office_id: '',
                currency_id: '',
                include_internal: false,
            },
            offices: @json($offices),
            currencies: @json($currencies),
            data: {
                summary: { total_revenue: 0, total_cost: 0, gross_profit: 0, margin: 0, total_volume: 0, total_count: 0 },
                by_shipping_type: [],
                by_office: [],
                by_partner: [],
                by_sales_person: [],
            },

            init() { this.fetchData(); },

            async fetchData() {
                this.loading = true;
                try {
                    const p = new URLSearchParams();
                    p.append('date_from', this.filters.date_from);
                    p.append('date_to', this.filters.date_to);
                    p.append('currency_id', this.filters.currency_id);
                    p.append('include_internal', this.filters.include_internal ? '1' : '');
                    if (this.filters.office_id) p.append('office_id', this.filters.office_id);
                    this.filters.shipping_types.forEach(t => p.append('shipping_types[]', t));

                    const resp = await fetch('{{ route("report.advanced.data") }}?' + p.toString());
                    this.data = await resp.json();
                } catch (e) {
                    console.error('Fetch error:', e);
                } finally {
                    this.loading = false;
                }
            },

            applyFilters() { this.fetchData(); },

            switchTab(tab) { this.activeTab = tab; },

            fmt(v) {
                if (v === null || v === undefined) return '0.00';
                return parseFloat(v).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            fmtInt(v) {
                if (v === null || v === undefined) return '0';
                return parseInt(v).toLocaleString('en-US');
            },

            printReport() { window.print(); },

            exportExcel() {
                let csv = 'Shipping Type,Revenue,Cost,Profit,Margin,Volume,Shipments\n';
                this.data.by_shipping_type.forEach(r => {
                    csv += `"${r.shipping_type}",${r.revenue},${r.cost},${r.profit},${r.margin},${r.volume},${r.count}\n`;
                });
                csv += `"TOTAL",${this.data.summary.total_revenue},${this.data.summary.total_cost},${this.data.summary.gross_profit},${this.data.summary.margin},${this.data.summary.total_volume},${this.data.summary.total_count}\n`;
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url; a.download = 'advanced-report.csv'; a.click();
                URL.revokeObjectURL(url);
            },
        };
    }
    </script>
</x-layout>
