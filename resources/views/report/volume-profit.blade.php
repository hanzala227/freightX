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
        .table-custom { width: 100%; border-collapse: collapse; font-size: 11px; }
        .table-custom thead th { background: #f1f5f9; padding: 8px 10px; text-align: left; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 10px; border-bottom: 2px solid #e2e8f0; white-space: nowrap; cursor: pointer; user-select: none; }
        .table-custom thead th:hover { background: #e2e8f0; }
        .table-custom thead th.text-right { text-align: right; }
        .table-custom thead th.text-center { text-align: center; }
        .table-custom tbody td { padding: 8px 10px; border-bottom: 1px solid #f1f5f9; color: #334155; }
        .table-custom tbody td.text-right { text-align: right; }
        .table-custom tbody td.text-center { text-align: center; }
        .table-custom tbody tr:hover { background: #f8fafc; }
        .total-row td { background: #f1f5f9; font-weight: 700; color: #0f172a; border-top: 2px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; }
        .type-badge { display: inline-block; padding: 2px 8px; border-radius: 2px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .sort-icon { margin-left: 3px; font-size: 9px; opacity: 0.4; }
        .sort-active { color: #3b82f6; opacity: 1 !important; }
        .pagination { display: flex; gap: 4px; align-items: center; }
        .pagination button { width: 28px; height: 28px; border: 1px solid #d1d5db; background: #fff; color: #334155; border-radius: 3px; font-size: 11px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.15s; }
        .pagination button:hover { background: #f1f5f9; border-color: #94a3b8; }
        .pagination button.active { background: #3b82f6; color: #fff; border-color: #3b82f6; }
        .pagination button:disabled { opacity: 0.4; cursor: not-allowed; }
        .search-box { border: 1px solid #d1d5db; border-radius: 3px; padding: 4px 8px; font-size: 11px; height: 28px; width: 200px; background: #fff; }
        .search-box:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,0.1); }
        .loading-overlay { position: absolute; inset: 0; background: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center; z-index: 10; border-radius: 4px; }
        .spinner { width: 28px; height: 28px; border: 3px solid #e2e8f0; border-top-color: #3b82f6; border-radius: 50%; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
    @endpush

    <div style="background: #eef1f5; min-height: 100vh; padding: 12px;">
        <div x-data="volumeProfitTable()" x-init="init()" style="position: relative;">
            <div x-show="loading" class="loading-overlay"><div class="spinner"></div></div>

            <div style="font-size: 11px; color: #64748b; margin-bottom: 10px;">
                <a href="/" style="color: #64748b; text-decoration: none;" target="_blank"><i class="fa fa-home"></i> Home</a>
                <i class="fa fa-angle-right" style="margin: 0 4px; opacity: 0.5;"></i>
                <a href="/report" style="color: #64748b; text-decoration: none;">Reports</a>
                <i class="fa fa-angle-right" style="margin: 0 4px; opacity: 0.5;"></i>
                <span style="color: #0f172a; font-weight: 700;">Volume & Profit Report</span>
            </div>

            <div class="portlet light">
                <div class="portlet-title">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa fa-table" style="color: #3b82f6; font-size: 12px;"></i>
                        <span class="caption-subject">Volume & Profit Analysis</span>
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
                                <div class="rpt-label">Profit Filter</div>
                                <div class="rpt-input-wrap">
                                    <div class="rpt-radio-group">
                                        <label><input type="radio" name="profit_filter" value="all" x-model="filters.profit_filter"> All</label>
                                        <label><input type="radio" name="profit_filter" value="profit" x-model="filters.profit_filter"> Profit Only</label>
                                        <label><input type="radio" name="profit_filter" value="loss" x-model="filters.profit_filter"> Loss Only</label>
                                        <label><input type="radio" name="profit_filter" value="breakeven" x-model="filters.profit_filter"> Breakeven</label>
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
                                <div class="rpt-label">Sales Person</div>
                                <div class="rpt-input-wrap">
                                    <select x-model="filters.sales_person_id" class="form-control-gf" style="width:100%;">
                                        <option value="">All Sales Persons</option>
                                        @foreach($salesPersons as $sp)
                                        <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="rpt-row">
                                <div class="rpt-label">Per Page</div>
                                <div class="rpt-input-wrap">
                                    <select x-model="filters.per_page" class="form-control-gf" style="width:100%;">
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="text-align:center; margin-top:4px;">
                        <button class="rpt-view-btn" @click="applyFilters()"><i class="fa fa-search" style="margin-right:4px;"></i> Generate Report</button>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 14px;">
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Total Revenue</div>
                        <div class="kpi-value" x-text="'$' + formatNum(data.summary.total_revenue)"></div>
                    </div>
                    <i class="fa fa-arrow-up kpi-icon" style="color: #10b981;"></i>
                </div>
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Total Cost</div>
                        <div class="kpi-value" x-text="'$' + formatNum(data.summary.total_cost)"></div>
                    </div>
                    <i class="fa fa-arrow-down kpi-icon" style="color: #ef4444;"></i>
                </div>
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Net Profit</div>
                        <div class="kpi-value" :style="'color:' + (data.summary.gross_profit >= 0 ? '#10b981' : '#ef4444')" x-text="'$' + formatNum(data.summary.gross_profit)"></div>
                    </div>
                    <i class="fa fa-money kpi-icon" style="color: #8b5cf6;"></i>
                </div>
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Total Volume (CBM)</div>
                        <div class="kpi-value" x-text="formatNum(data.summary.total_volume)"></div>
                    </div>
                    <i class="fa fa-cube kpi-icon" style="color: #3b82f6;"></i>
                </div>
                <div class="kpi-card">
                    <div>
                        <div class="kpi-label">Shipments</div>
                        <div class="kpi-value" x-text="formatInt(data.summary.shipment_count)"></div>
                    </div>
                    <i class="fa fa-ship kpi-icon" style="color: #f59e0b;"></i>
                </div>
            </div>

            <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 4px; overflow: hidden; margin-bottom: 14px;">
                <div style="padding: 8px 14px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;">
                            <i class="fa fa-list" style="margin-right: 4px;"></i> Data Table
                        </span>
                        <span style="font-size: 10px; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 2px;" x-text="data.summary.row_count + ' rows'"></span>
                    </div>
                    <input type="text" class="search-box" placeholder="Search shipping type, partner..." x-model="filters.search" @input.debounce.300ms="fetchData()">
                </div>

                <div style="overflow-x: auto;">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th @click="sort('shipping_type')" style="width: 140px;">
                                    Shipping Type
                                    <i class="fa" :class="getSortIcon('shipping_type')"></i>
                                </th>
                                <th @click="sort('partner')">
                                    Partner / Customer
                                    <i class="fa" :class="getSortIcon('partner')"></i>
                                </th>
                                <th @click="sort('revenue')" class="text-right">
                                    Revenue (A/R)
                                    <i class="fa" :class="getSortIcon('revenue')"></i>
                                </th>
                                <th @click="sort('cost')" class="text-right">
                                    Cost (A/P)
                                    <i class="fa" :class="getSortIcon('cost')"></i>
                                </th>
                                <th @click="sort('profit')" class="text-right">
                                    Net Profit
                                    <i class="fa" :class="getSortIcon('profit')"></i>
                                </th>
                                <th @click="sort('volume')" class="text-right">
                                    Volume (CBM)
                                    <i class="fa" :class="getSortIcon('volume')"></i>
                                </th>
                                <th @click="sort('count')" class="text-center">
                                    # Quotes
                                    <i class="fa" :class="getSortIcon('count')"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="data.rows.length === 0">
                                <tr><td colspan="7" style="text-align:center; padding:40px; color:#94a3b8; font-size:12px;">No data found for selected filters.</td></tr>
                            </template>
                            <template x-for="(row, idx) in data.rows" :key="idx">
                                <tr>
                                    <td>
                                        <span class="type-badge" :style="'background:' + getTypeColor(row.shipping_type) + '; color: #fff;'" x-text="row.shipping_type"></span>
                                    </td>
                                    <td style="font-weight: 600; color: #3b82f6;" x-text="row.partner"></td>
                                    <td class="text-right" x-text="'$' + formatNum(row.revenue)"></td>
                                    <td class="text-right" x-text="'$' + formatNum(row.cost)"></td>
                                    <td class="text-right" style="font-weight: 700;" :style="'color:' + (row.profit >= 0 ? '#10b981' : '#ef4444')" x-text="'$' + formatNum(row.profit)"></td>
                                    <td class="text-right" x-text="formatNum(row.volume_cbm)"></td>
                                    <td class="text-center" style="font-weight: 600;" x-text="row.count"></td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot x-show="data.rows.length > 0">
                            <tr class="total-row">
                                <td style="text-transform: uppercase; letter-spacing: 0.5px;">TOTAL</td>
                                <td></td>
                                <td class="text-right" x-text="'$' + formatNum(data.summary.total_revenue)"></td>
                                <td class="text-right" x-text="'$' + formatNum(data.summary.total_cost)"></td>
                                <td class="text-right" :style="'color:' + (data.summary.gross_profit >= 0 ? '#10b981' : '#ef4444')" x-text="'$' + formatNum(data.summary.gross_profit)"></td>
                                <td class="text-right" x-text="formatNum(data.summary.total_volume)"></td>
                                <td class="text-center" x-text="data.summary.shipment_count"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div style="padding: 8px 14px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
                    <div style="font-size: 10px; color: #64748b;">
                        <span x-text="'Showing ' + getShowingText()"></span>
                    </div>
                    <div class="pagination">
                        <button @click="goToPage(1)" :disabled="pagination.current_page <= 1"><i class="fa fa-angle-double-left"></i></button>
                        <button @click="goToPage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1"><i class="fa fa-angle-left"></i></button>
                        <template x-for="p in getPages()" :key="p">
                            <button :class="{ active: p === pagination.current_page }" @click="goToPage(p)" x-text="p"></button>
                        </template>
                        <button @click="goToPage(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page"><i class="fa fa-angle-right"></i></button>
                        <button @click="goToPage(pagination.last_page)" :disabled="pagination.current_page >= pagination.last_page"><i class="fa fa-angle-double-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function volumeProfitTable() {
        return {
            loading: false,
            filters: {
                date_from: '{{ now()->subMonths(6)->startOfMonth()->format("Y-m-d") }}',
                date_to: '{{ now()->endOfMonth()->format("Y-m-d") }}',
                period_type: 'post_date',
                shipping_types: [],
                office_id: '',
                sales_person_id: '',
                profit_filter: 'all',
                search: '',
                sort_by: 'revenue',
                sort_dir: 'desc',
                page: 1,
                per_page: 25,
            },
            data: {
                summary: { total_revenue: 0, total_cost: 0, gross_profit: 0, margin: 0, total_volume: 0, total_weight_kg: 0, shipment_count: 0, row_count: 0 },
                rows: [],
                pagination: { current_page: 1, per_page: 25, total: 0, last_page: 1 },
            },
            pagination: { current_page: 1, per_page: 25, total: 0, last_page: 1 },

            init() {
                this.fetchData();
            },

            async fetchData() {
                this.loading = true;
                try {
                    const params = new URLSearchParams();
                    params.append('date_from', this.filters.date_from);
                    params.append('date_to', this.filters.date_to);
                    params.append('period_type', this.filters.period_type);
                    params.append('profit_filter', this.filters.profit_filter);
                    params.append('search', this.filters.search);
                    params.append('sort_by', this.filters.sort_by);
                    params.append('sort_dir', this.filters.sort_dir);
                    params.append('page', this.filters.page);
                    params.append('per_page', this.filters.per_page);
                    if (this.filters.office_id) params.append('office_id', this.filters.office_id);
                    if (this.filters.sales_person_id) params.append('sales_person_id', this.filters.sales_person_id);
                    this.filters.shipping_types.forEach(t => params.append('shipping_types[]', t));

                    const resp = await fetch('{{ route("report.volume-profit.data") }}?' + params.toString());
                    const json = await resp.json();
                    this.data = json;
                    this.pagination = json.pagination;
                } catch (e) {
                    console.error('Fetch error:', e);
                } finally {
                    this.loading = false;
                }
            },

            applyFilters() {
                this.filters.page = 1;
                this.fetchData();
            },

            sort(field) {
                if (this.filters.sort_by === field) {
                    this.filters.sort_dir = this.filters.sort_dir === 'asc' ? 'desc' : 'asc';
                } else {
                    this.filters.sort_by = field;
                    this.filters.sort_dir = 'desc';
                }
                this.filters.page = 1;
                this.fetchData();
            },

            getSortIcon(field) {
                if (this.filters.sort_by !== field) return 'fa-sort sort-icon';
                return this.filters.sort_dir === 'asc' ? 'fa-sort-asc sort-icon sort-active' : 'fa-sort-desc sort-icon sort-active';
            },

            goToPage(page) {
                if (page < 1 || page > this.pagination.last_page) return;
                this.filters.page = page;
                this.fetchData();
            },

            getPages() {
                const pages = [];
                const total = this.pagination.last_page;
                const current = this.pagination.current_page;
                let start = Math.max(1, current - 2);
                let end = Math.min(total, start + 4);
                if (end - start < 4) start = Math.max(1, end - 4);
                for (let i = start; i <= end; i++) pages.push(i);
                return pages;
            },

            getShowingText() {
                const total = this.pagination.total;
                const per = this.pagination.per_page;
                const page = this.pagination.current_page;
                if (total === 0) return '0 results';
                const from = ((page - 1) * per) + 1;
                const to = Math.min(page * per, total);
                return from + '-' + to + ' of ' + total + ' results';
            },

            getTypeColor(type) {
                const colors = {
                    'Ocean Export': '#3b82f6',
                    'Ocean Import': '#0ea5e9',
                    'Air Export': '#8b5cf6',
                    'Air Import': '#a855f7',
                    'Trucking': '#f59e0b',
                    'Warehouse': '#10b981',
                    'Misc': '#64748b',
                };
                return colors[type] || '#94a3b8';
            },

            formatNum(n) {
                if (n === null || n === undefined) return '0.00';
                return parseFloat(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            formatInt(n) {
                if (n === null || n === undefined) return '0';
                return parseInt(n).toLocaleString('en-US');
            },

            printReport() { window.print(); },

            exportExcel() {
                let csv = 'Shipping Type,Partner / Customer,Revenue (A/R),Cost (A/P),Net Profit,Volume (CBM),Count\n';
                this.data.rows.forEach(r => {
                    csv += `"${r.shipping_type}","${r.partner}",${r.revenue},${r.cost},${r.profit},${r.volume_cbm},${r.count}\n`;
                });
                csv += `"TOTAL","",${this.data.summary.total_revenue},${this.data.summary.total_cost},${this.data.summary.gross_profit},${this.data.summary.total_volume},${this.data.summary.shipment_count}\n`;
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
