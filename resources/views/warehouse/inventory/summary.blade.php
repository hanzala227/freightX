<x-layout>
    @push('styles')
    <x-list-styles />
    <style>
        .summary-cards { display: flex; gap: 8px; margin-bottom: 10px; flex-wrap: wrap; }
        .summary-card { background: #fff; border: 1px solid #cbd5e1; border-radius: 3px; padding: 10px 16px; flex: 1; min-width: 140px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
        .summary-card .card-label { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; }
        .summary-card .card-value { font-size: 18px; font-weight: 700; color: #1e293b; margin-top: 2px; line-height: 1.2; }
        .summary-card .card-icon { float: right; font-size: 22px; color: #cbd5e1; margin-top: 4px; }

        .table-wrapper { overflow: auto; max-height: calc(100vh - 300px); position: relative; }
        .table-grid { border-collapse: separate; border-spacing: 0; width: 1860px; table-layout: fixed; font-size: 10px; }
        .table-grid thead tr:first-child th { position: sticky; top: 0; z-index: 20; background: #f8fafc; color: #475569; font-weight: 600; border-bottom: 1px solid #cbd5e1; border-right: 1px solid #e2e8f0; padding: 3px 6px; white-space: nowrap; height: 26px; text-align: left; user-select: none; box-sizing: border-box; }
        .table-grid thead tr:first-child th.sortable { cursor: pointer; }
        .table-grid thead tr:first-child th.sortable:hover { background: #e2e8f0; }
        .table-grid th.pin-0, .table-grid td.pin-0 { position: sticky; left: 0; z-index: 12; }
        .table-grid th.pin-1, .table-grid td.pin-1 { position: sticky; left: 130px; z-index: 12; }
        .table-grid th.pin-2, .table-grid td.pin-2 { position: sticky; left: 271px; z-index: 12; }
        .table-grid thead tr:first-child th.pin-0, .table-grid thead tr:first-child th.pin-1, .table-grid thead tr:first-child th.pin-2 { background: #f8fafc; z-index: 22; }
        .table-grid tbody td.pin-0, .table-grid tbody td.pin-1, .table-grid tbody td.pin-2 { background: #fff; z-index: 11; }
        .table-grid tbody tr:nth-of-type(even) td.pin-0, .table-grid tbody tr:nth-of-type(even) td.pin-1, .table-grid tbody tr:nth-of-type(even) td.pin-2 { background-color: #fafbfc; }
        .table-grid tbody tr:hover td.pin-0, .table-grid tbody tr:hover td.pin-1, .table-grid tbody tr:hover td.pin-2 { background-color: #f1f5f9 !important; }
        .table-grid td.pin-2 { border-right: 2px solid #cbd5e1 !important; }
        .table-grid th.pin-2 { border-right: 2px solid #cbd5e1 !important; }
        .table-grid tbody td { padding: 3px 6px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #f1f5f9; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; height: 22px; color: #334155; font-size: 10px; }
        .table-grid tbody tr:hover td { background-color: #f1f5f9 !important; }
        .table-grid tbody tr:nth-of-type(even) td { background-color: #fafbfc; }
        .text-right { text-align: right !important; }
        .col-link { color: #3b82f6; font-weight: 600; text-decoration: none; }
        .col-link:hover { text-decoration: underline; }

        .filter-row td { background: #eff6ff !important; padding: 2px 3px; border-bottom: 1px solid #bfdbfe; }
        .filter-row td input.filter-input { width: 100%; height: 18px; border: 1px solid #93c5fd; font-size: 9px; border-radius: 2px; padding: 0 3px; box-sizing: border-box; outline: none; background: #fff; }
        .filter-row td input.filter-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 1px rgba(59,130,246,0.2); }

        #grid-loading { display: none; position: absolute; inset: 0; background: rgba(255,255,255,0.7); z-index: 50; align-items: center; justify-content: center; }
        #grid-loading.show { display: flex; }
        #grid-loading .spinner { width: 24px; height: 24px; border: 3px solid #e2e8f0; border-top-color: #3b82f6; border-radius: 50%; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        @media print {
            .portlet-tool:not(.bottom), .actions, .page-bar, .toast-container, .summary-cards { display: none !important; }
            .portlet.light { border: none; box-shadow: none; }
            .table-grid th, .table-grid td { font-size: 8px; padding: 1px 3px; }
            body { background: #fff; }
            .page-content { background: #fff; padding: 0; }
            .table-wrapper { max-height: none; overflow: visible; }
        }
    </style>
    @endpush

    <div class="toast-container" id="toast-container"></div>

    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
                <li>Warehouse <i class="fa fa-angle-right"></i></li>
                <li>Inventory <i class="fa fa-angle-right"></i></li>
                <li><span style="color:#333;font-weight:700;">Summary</span></li>
            </ul>
        </div>

        <div class="summary-cards">
            <div class="summary-card">
                <span class="card-icon"><i class="fa fa-cubes"></i></span>
                <div class="card-label">Total Items</div>
                <div class="card-value" id="stat-total-items">{{ number_format($stats['total_items']) }}</div>
            </div>
            <div class="summary-card">
                <span class="card-icon"><i class="fa fa-cube"></i></span>
                <div class="card-label">On Hand Qty</div>
                <div class="card-value" id="stat-on-hand">{{ number_format($stats['total_on_hand'], 2) }}</div>
            </div>
            <div class="summary-card">
                <span class="card-icon"><i class="fa fa-check-circle"></i></span>
                <div class="card-label">Available Qty</div>
                <div class="card-value" id="stat-available">{{ number_format($stats['total_available'], 2) }}</div>
            </div>
            <div class="summary-card">
                <span class="card-icon"><i class="fa fa-balance-scale"></i></span>
                <div class="card-label">Total Weight</div>
                <div class="card-value" id="stat-weight">{{ number_format($stats['total_weight'], 2) }} KG</div>
            </div>
            <div class="summary-card">
                <span class="card-icon"><i class="fa fa-arrows"></i></span>
                <div class="card-label">Total Volume</div>
                <div class="card-value" id="stat-volume">{{ number_format($stats['total_volume'], 2) }} CBM</div>
            </div>
        </div>

        <div class="portlet light" style="position:relative;">
            <div id="grid-loading"><div class="spinner"></div></div>

            <div class="portlet-title">
                <div class="caption" style="display:flex;align-items:center;gap:8px;">
                    <span class="caption-subject">Inventory Summary</span>
                    <span id="result-count" style="font-size:10px;color:#64748b;font-weight:400;">({{ $items->total() }} records)</span>
                </div>
                <div class="actions" style="display:flex;gap:4px;position:relative;align-items:center;">
                    <button class="btn-action-round" id="btn-filter" onclick="toggleFilter()" title="Toggle filter row">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                    <div style="position:relative;display:inline-flex;align-items:center;">
                        <button class="btn-action-round" id="btn-config" onclick="toggleConfig()" title="Column visibility">
                            <i class="fa fa-cogs"></i> Config
                        </button>
                        <div class="config-panel" id="config-panel" style="display:none;">
                            <div class="config-panel-title">Column Visibility</div>
                            <div id="col-toggles"></div>
                        </div>
                    </div>
                    <button class="btn-action-round" onclick="refreshGrid()" title="Refresh"><i class="fa fa-refresh"></i></button>
                    <button class="btn-action-round" onclick="window.print()" title="Print"><i class="fa fa-print"></i></button>
                    <a class="btn-action-round white" id="btn-excel" href="{{ route('inventory.summary.export-csv') }}" title="Download as CSV" target="_blank"><i class="fa fa-file-excel-o"></i> Excel</a>
                </div>
            </div>

            <div class="portlet-tool">
                <div style="display:flex;gap:10px;align-items:center;">&nbsp;</div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <i class="fa fa-search" style="font-size:10px;color:#94a3b8;"></i>
                    <input type="text" id="quick-search" class="input-inline" style="width:180px;" placeholder="Search SKU, product, UPC..." value="{{ request('search') }}" oninput="quickSearch(this.value)">
                    <button id="clear-search" class="btn-tool" style="display:none;padding:0 6px;height:18px;font-size:9px;" onclick="clearSearch()" title="Clear search"><i class="fa fa-times-circle"></i></button>
                </div>
            </div>

            <div class="portlet-body">
                <div class="table-wrapper">
                    <table class="table-grid" id="grid-table">
                        <thead>
                            <tr id="header-row">
                                <th class="pin-0" data-col="customer" style="width:130px;">Customer</th>
                                <th class="pin-1" data-col="warehouse" style="width:141px;">
                                    <a href="javascript:;" onclick="toggleSort('warehouse_id')" style="color:inherit;text-decoration:none;">Warehouse <i class="fa fa-sort" id="sort-warehouse_id"></i></a>
                                </th>
                                <th class="pin-2" data-col="sku" style="width:95px;">
                                    <a href="javascript:;" onclick="toggleSort('sku')" style="color:inherit;text-decoration:none;">SKU No. <i class="fa fa-sort" id="sort-sku"></i></a>
                                </th>
                                <th data-col="po" style="width:105px;">Customer P.O.</th>
                                <th data-col="item_name" style="width:180px;">
                                    <a href="javascript:;" onclick="toggleSort('item_name')" style="color:inherit;text-decoration:none;">Product Description <i class="fa fa-sort" id="sort-item_name"></i></a>
                                </th>
                                <th data-col="bl_no" style="width:100px;">B/L No.</th>
                                <th data-col="office" style="width:60px;">Office</th>
                                <th data-col="upc" style="width:70px;">UPC/EAN</th>
                                <th data-col="on_hand_qty" style="width:100px;text-align:right;">
                                    <a href="javascript:;" onclick="toggleSort('on_hand_qty')" style="color:inherit;text-decoration:none;">On Hand Qty <i class="fa fa-sort" id="sort-on_hand_qty"></i></a>
                                </th>
                                <th data-col="allocated" style="width:100px;text-align:right;">Allocated Qty</th>
                                <th data-col="available_qty" style="width:100px;text-align:right;">
                                    <a href="javascript:;" onclick="toggleSort('available_qty')" style="color:inherit;text-decoration:none;">Available Qty <i class="fa fa-sort" id="sort-available_qty"></i></a>
                                </th>
                                <th data-col="unit" style="width:70px;">Qty Unit</th>
                                <th data-col="weight_kg" style="width:100px;text-align:right;">
                                    <a href="javascript:;" onclick="toggleSort('weight_kg')" style="color:inherit;text-decoration:none;">Weight <i class="fa fa-sort" id="sort-weight_kg"></i></a>
                                </th>
                                <th data-col="volume_cbm" style="width:100px;text-align:right;">
                                    <a href="javascript:;" onclick="toggleSort('volume_cbm')" style="color:inherit;text-decoration:none;">Measurement <i class="fa fa-sort" id="sort-volume_cbm"></i></a>
                                </th>
                                <th data-col="inner_pack" style="width:80px;text-align:right;">Inner Pack</th>
                                <th data-col="on_hand_pcs" style="width:100px;text-align:right;">On Hand Pcs</th>
                                <th data-col="alloc_pcs" style="width:100px;text-align:right;">Allocated Pcs</th>
                                <th data-col="avail_pcs" style="width:100px;text-align:right;">Available Pcs</th>
                            </tr>
                            <tr id="filter-row" class="filter-row" style="display:none;">
                                <td class="pin-0" data-col="customer"><input class="filter-input" data-col-idx="0" placeholder="Customer..." oninput="applyFilters()"></td>
                                <td class="pin-1" data-col="warehouse"><input class="filter-input" data-col-idx="1" placeholder="Warehouse..." oninput="applyFilters()"></td>
                                <td class="pin-2" data-col="sku"><input class="filter-input" data-col-idx="2" placeholder="SKU..." oninput="applyFilters()"></td>
                                <td data-col="po"></td>
                                <td data-col="item_name"><input class="filter-input" data-col-idx="3" placeholder="Product..." oninput="applyFilters()"></td>
                                <td data-col="bl_no"></td>
                                <td data-col="office"></td>
                                <td data-col="upc"></td>
                                <td data-col="on_hand_qty"></td>
                                <td data-col="allocated"></td>
                                <td data-col="available_qty"></td>
                                <td data-col="unit"></td>
                                <td data-col="weight_kg"></td>
                                <td data-col="volume_cbm"></td>
                                <td data-col="inner_pack"></td>
                                <td data-col="on_hand_pcs"></td>
                                <td data-col="alloc_pcs"></td>
                                <td data-col="avail_pcs"></td>
                            </tr>
                        </thead>
                        <tbody id="grid-body">
                            @forelse($items as $item)
                            <tr data-id="{{ $item->id }}">
                                <td class="pin-0" style="width:130px;overflow:hidden;text-overflow:ellipsis;">{{ $item->customer?->name ?? $item->latestReceivingItem?->receiving?->customer?->name ?? '' }}</td>
                                <td class="pin-1" style="width:141px;overflow:hidden;text-overflow:ellipsis;">{{ $item->warehouse->name ?? '' }}</td>
                                <td class="pin-2" style="width:95px;"><span class="col-link">{{ $item->sku }}</span></td>
                                <td style="width:105px;">{{ $item->latestReceivingItem?->customer_po ?? '' }}</td>
                                <td style="width:180px;overflow:hidden;text-overflow:ellipsis;">{{ $item->item_name }}</td>
                                <td style="width:100px;">{{ $item->latestReceivingItem?->receiving?->bl_no ?? '' }}</td>
                                <td style="width:60px;">{{ $item->latestReceivingItem?->receiving?->office?->code ?? '' }}</td>
                                <td style="width:70px;">{{ $item->upc_ean ?? $item->latestReceivingItem?->sku_no ?? '' }}</td>
                                <td style="width:100px;text-align:right;">{{ number_format($item->on_hand_qty ?? 0, 2) }}</td>
                                <td style="width:100px;text-align:right;">0.00</td>
                                <td style="width:100px;text-align:right;">{{ number_format($item->available_qty ?? 0, 2) }}</td>
                                <td style="width:70px;">{{ $item->unit->name ?? '' }}</td>
                                <td style="width:100px;text-align:right;">{{ number_format($item->weight_kg ?? 0, 2) }} KG</td>
                                <td style="width:100px;text-align:right;">{{ number_format($item->volume_cbm ?? 0, 2) }} CBM</td>
                                <td style="width:80px;text-align:right;">0.00</td>
                                <td style="width:100px;text-align:right;">{{ number_format($item->on_hand_qty ?? 0, 2) }}</td>
                                <td style="width:100px;text-align:right;">0.00</td>
                                <td style="width:100px;text-align:right;">{{ number_format($item->available_qty ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr id="empty-row">
                                <td colspan="18" style="text-align:center;padding:30px;color:#94a3b8;">
                                    <i class="fa fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                                    No inventory records found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="portlet-tool bottom">
                <div style="display:flex;justify-content:space-between;width:100%;align-items:center;">
                    <div id="pagination-container">{{ $items->links() }}</div>
                    <div style="font-size:10px;color:#64748b;">
                        Showing <span id="stat-first">{{ $items->firstItem() ?? 0 }}</span> – <span id="stat-last">{{ $items->lastItem() ?? 0 }}</span> of <span id="stat-total">{{ $items->total() }}</span> records
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    const FILTER_MAP = { 0: 'filter_customer', 1: 'filter_warehouse', 2: 'filter_sku', 3: 'filter_item_name' };

    function showLoading() { const el = document.getElementById('grid-loading'); if (el) el.classList.add('show'); }
    function hideLoading() { const el = document.getElementById('grid-loading'); if (el) el.classList.remove('show'); }

    function updateExcelLink() {
        const btn = document.getElementById('btn-excel');
        if (!btn) return;
        const url = new URL(window.location.href);
        btn.href = url.pathname + '?' + url.searchParams.toString();
    }

    function updateClearSearch() {
        const btn = document.getElementById('clear-search');
        const input = document.getElementById('quick-search');
        if (btn && input) { btn.style.display = input.value.trim() ? 'inline-flex' : 'none'; }
    }
    function clearSearch() {
        const input = document.getElementById('quick-search');
        if (input) { input.value = ''; quickSearch(''); }
    }

    /* SEARCH */
    let searchDebounce;
    function quickSearch(val) {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => {
            const q = val.trim();
            const url = new URL(window.location.href);
            if (!q) url.searchParams.delete('search');
            else url.searchParams.set('search', q);
            url.searchParams.delete('page');
            updateClearSearch();
            updateGrid(url.toString());
        }, 300);
    }

    /* FILTER */
    function toggleFilter() {
        const row = document.getElementById('filter-row');
        const isVisible = row.style.display === 'table-row';
        row.style.display = isVisible ? 'none' : 'table-row';
        document.getElementById('btn-filter').classList.toggle('active', !isVisible);

        if (!isVisible) {
            const urlParams = new URLSearchParams(window.location.search);
            document.querySelectorAll('#filter-row .filter-input').forEach(inp => {
                const param = FILTER_MAP[inp.dataset.colIdx];
                if (param) inp.value = urlParams.get(param) || '';
            });
            document.querySelector('#filter-row .filter-input')?.focus();
        } else {
            document.querySelectorAll('#filter-row .filter-input').forEach(i => { i.value = ''; });
            applyFilters();
        }
    }

    let filterDebounce;
    function applyFilters() {
        clearTimeout(filterDebounce);
        filterDebounce = setTimeout(() => {
            const url = new URL(window.location.href);
            const search = url.searchParams.get('search') || '';
            const sort = url.searchParams.get('sort') || '';
            const dir = url.searchParams.get('dir') || '';
            url.search = '';
            if (search) url.searchParams.set('search', search);
            if (sort) url.searchParams.set('sort', sort);
            if (dir) url.searchParams.set('dir', dir);

            document.querySelectorAll('#filter-row .filter-input').forEach(inp => {
                const v = inp.value.trim();
                if (!v) return;
                const param = FILTER_MAP[inp.dataset.colIdx];
                if (param) url.searchParams.set(param, v);
            });
            updateGrid(url.toString());
        }, 300);
    }

    /* SORT */
    function toggleSort(field) {
        const params = new URLSearchParams(window.location.search);
        const currentSort = params.get('sort') || 'created_at';
        const currentDir = params.get('dir') || 'desc';
        let newDir = 'asc';
        if (currentSort === field) { newDir = currentDir === 'asc' ? 'desc' : 'asc'; }
        const url = new URL(window.location.href);
        url.searchParams.set('sort', field);
        url.searchParams.set('dir', newDir);
        url.searchParams.delete('page');
        updateGrid(url.toString());
    }

    /* AJAX GRID */
    async function updateGrid(url) {
        showLoading();
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network error');
            const html = await response.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');

            const newBody = doc.getElementById('grid-body');
            const newPagination = doc.getElementById('pagination-container');
            if (newBody) document.getElementById('grid-body').innerHTML = newBody.innerHTML;
            if (newPagination) document.getElementById('pagination-container').innerHTML = newPagination.innerHTML;

            ['stat-total-items', 'stat-on-hand', 'stat-available', 'stat-weight', 'stat-volume'].forEach(id => {
                const newVal = doc.getElementById(id);
                const oldVal = document.getElementById(id);
                if (newVal && oldVal) oldVal.textContent = newVal.textContent;
            });

            const newCount = doc.getElementById('result-count');
            const oldCount = document.getElementById('result-count');
            if (newCount && oldCount) oldCount.textContent = newCount.textContent;

            const stats = doc.querySelector('.portlet-tool.bottom div:last-child');
            if (stats) {
                const nums = stats.textContent.match(/[\d,]+/g);
                if (nums && nums.length >= 3) {
                    document.getElementById('stat-first').textContent = nums[0];
                    document.getElementById('stat-last').textContent = nums[1];
                    document.getElementById('stat-total').textContent = nums[2];
                }
            }

            const params = new URLSearchParams(new URL(url).search);
            const sort = params.get('sort') || 'created_at';
            const dir = params.get('dir') || 'desc';
            document.querySelectorAll('[id^="sort-"]').forEach(i => i.className = 'fa fa-sort');
            const sortIcon = document.getElementById('sort-' + sort);
            if (sortIcon) sortIcon.className = 'fa ' + (dir === 'asc' ? 'fa-sort-asc' : 'fa-sort-desc');

            window.history.pushState({}, '', url);
            updateExcelLink();
            updateClearSearch();
            loadColumnConfig();
        } catch (e) {
            console.error(e);
            showToast('error', 'Failed to update grid');
        } finally {
            hideLoading();
        }
    }

    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) { e.preventDefault(); updateGrid(link.href); }
    });

    function refreshGrid() { updateGrid(window.location.href); }

    /* CONFIG */
    const PINNED = ['customer', 'warehouse', 'sku'];
    const STORAGE_KEY = 'inventory_summary_column_config';
    function loadColumnConfig() {
        try {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (!saved) return;
            const hidden = JSON.parse(saved);
            document.querySelectorAll('#header-row th[data-col]').forEach(th => {
                if (PINNED.includes(th.dataset.col)) return;
                if (hidden.includes(th.dataset.col)) th.style.display = 'none';
            });
            document.querySelectorAll('#grid-body tr').forEach(row => {
                const cells = row.querySelectorAll('td');
                document.querySelectorAll('#header-row th[data-col]').forEach((th, i) => {
                    if (PINNED.includes(th.dataset.col)) return;
                    if (hidden.includes(th.dataset.col) && cells[i]) cells[i].style.display = 'none';
                });
            });
            document.querySelectorAll('#filter-row td[data-col]').forEach(td => {
                if (hidden.includes(td.dataset.col)) td.style.display = 'none';
            });
        } catch(e) {}
    }
    function toggleConfig() {
        const panel = document.getElementById('config-panel');
        const open = panel.style.display === 'none';
        panel.style.display = open ? 'block' : 'none';
        document.getElementById('btn-config').classList.toggle('active', open);
        if (open) buildConfigPanel();
    }
    function buildConfigPanel() {
        const container = document.getElementById('col-toggles');
        container.innerHTML = '';
        const hidden = getHiddenColumns();
        document.querySelectorAll('#header-row th[data-col]').forEach(th => {
            if (PINNED.includes(th.dataset.col)) return;
            const label = document.createElement('label');
            const cb = document.createElement('input');
            cb.type = 'checkbox';
            cb.checked = !hidden.includes(th.dataset.col);
            cb.onchange = () => toggleColumn(th.dataset.col, cb.checked);
            label.appendChild(cb);
            label.append(' ' + th.textContent.trim().replace(/[0-9]/g, '').trim());
            container.appendChild(label);
        });
    }
    function getHiddenColumns() {
        const hidden = [];
        document.querySelectorAll('#header-row th[data-col]').forEach(th => {
            if (PINNED.includes(th.dataset.col)) return;
            if (th.style.display === 'none') hidden.push(th.dataset.col);
        });
        return hidden;
    }
    function toggleColumn(colName, show) {
        const th = document.querySelector(`#header-row th[data-col="${colName}"]`);
        if (!th) return;
        const idx = [...th.parentElement.children].indexOf(th);
        th.style.display = show ? '' : 'none';
        document.querySelectorAll('#grid-body tr').forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells[idx]) cells[idx].style.display = show ? '' : 'none';
        });
        const filterTd = document.querySelector(`#filter-row td[data-col="${colName}"]`);
        if (filterTd) filterTd.style.display = show ? '' : 'none';
        saveColumnConfig();
    }
    function saveColumnConfig() { try { localStorage.setItem(STORAGE_KEY, JSON.stringify(getHiddenColumns())); } catch(e) {} }
    document.addEventListener('click', e => {
        const panel = document.getElementById('config-panel');
        const btn = document.getElementById('btn-config');
        if (panel.style.display !== 'none' && !panel.contains(e.target) && !btn.contains(e.target)) {
            panel.style.display = 'none';
            btn.classList.remove('active');
        }
    });

    /* INIT */
    (function() {
        const params = new URLSearchParams(window.location.search);
        const sort = params.get('sort') || 'created_at';
        const dir = params.get('dir') || 'desc';
        const icon = document.getElementById('sort-' + sort);
        if (icon) icon.className = 'fa ' + (dir === 'asc' ? 'fa-sort-asc' : 'fa-sort-desc');
        loadColumnConfig();
        updateExcelLink();
        updateClearSearch();
    })();

    window.addEventListener('popstate', function() { updateGrid(window.location.href); });

    /* TOAST */
    function showToast(type, msg) {
        const icons = { success: 'check-circle', error: 'times-circle', info: 'info-circle' };
        const t = document.createElement('div');
        t.className = `toast ${type}`;
        t.innerHTML = `<i class="fa fa-${icons[type] || 'info-circle'}"></i> <span>${msg}</span>`;
        document.getElementById('toast-container').appendChild(t);
        setTimeout(() => t.remove(), 4000);
    }

    @if(session('success')) showToast('success', @json(session('success'))); @endif
    @if(session('error')) showToast('error', @json(session('error'))); @endif
    </script>
    @endpush
</x-layout>
