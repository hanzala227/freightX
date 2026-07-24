<x-layout>
    @push('styles')
    <x-list-styles />
    <style>
        .grid-table tbody tr:nth-of-type(even) td { background-color: #fafbfc; }
        .text-right { text-align: right !important; }
        .totals-row td { background: #f1f5f9 !important; font-weight: 700; border-top: 2px solid #cbd5e1; color: #1e293b; }
        #grid-loading { display: none; position: absolute; inset: 0; background: rgba(255,255,255,0.7); z-index: 50; align-items: center; justify-content: center; }
        #grid-loading.show { display: flex; }
        #grid-loading .spinner { width: 24px; height: 24px; border: 3px solid #e2e8f0; border-top-color: #3b82f6; border-radius: 50%; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .filter-row td { background: #eff6ff !important; padding: 2px 3px; }
        .filter-row td input.filter-input { width: 100%; }
        .badge-status { padding: 1px 4px; border-radius: 2px; font-size: 9px; font-weight: 600; }
        .status-pre-receiving { background: #fefce8; color: #ca8a04; border: 1px solid #fef08a; }
        .status-received { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .status-shipped { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        .status-default { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }
        @media print {
            .portlet-tool:not(.bottom), .actions, .page-bar, .toast-container { display: none !important; }
            .portlet.light { border: none; box-shadow: none; }
            .grid-table th, .grid-table td { font-size: 9px; padding: 2px 4px; }
            body { background: #fff; }
            .page-content { background: #fff; padding: 0; }
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
                <li><span style="color:#333;font-weight:700;">Detail</span></li>
            </ul>
        </div>

        <div class="portlet light" style="position:relative;">
            <div id="grid-loading"><div class="spinner"></div></div>

            <div class="portlet-title">
                <div class="caption" style="display:flex;align-items:center;gap:8px;">
                    <span class="caption-subject">Inventory Detail</span>
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
                    <a class="btn-action-round white" id="btn-excel" href="{{ route('inventory.detail.export-csv') }}" title="Download as CSV" target="_blank"><i class="fa fa-file-excel-o"></i> Excel</a>
                </div>
            </div>

            <div class="portlet-tool">
                <div style="display:flex;gap:10px;align-items:center;">&nbsp;</div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <i class="fa fa-search" style="font-size:10px;color:#94a3b8;"></i>
                    <input type="text" id="quick-search" class="input-inline" style="width:180px;" placeholder="Search SKU, product, PO..." value="{{ request('search') }}" oninput="quickSearch(this.value)">
                    <button id="clear-search" class="btn-tool" style="display:none;padding:0 6px;height:18px;font-size:9px;" onclick="clearSearch()" title="Clear search"><i class="fa fa-times-circle"></i></button>
                </div>
            </div>

            <div class="portlet-body" style="overflow-x:auto;">
                <table class="grid-table" id="main-grid">
                    <thead>
                        <tr id="header-row">
                            <th data-col="date" style="width:65px;">
                                <a href="javascript:;" onclick="toggleSort('created_at')" style="color:inherit;text-decoration:none;">Date <i class="fa fa-sort" id="sort-created_at"></i></a>
                            </th>
                            <th data-col="customer" style="width:110px;">Customer</th>
                            <th data-col="file_no" style="width:80px;">File No.</th>
                            <th data-col="office" style="width:55px;">Office</th>
                            <th data-col="trucker" style="width:80px;">Trucker</th>
                            <th data-col="from_to" style="width:80px;">From / To</th>
                            <th data-col="sku" style="width:85px;">
                                <a href="javascript:;" onclick="toggleSort('sku_no')" style="color:inherit;text-decoration:none;">SKU No. <i class="fa fa-sort" id="sort-sku_no"></i></a>
                            </th>
                            <th data-col="po" style="width:80px;">Customer P.O.</th>
                            <th data-col="product" style="width:150px;">
                                <a href="javascript:;" onclick="toggleSort('description')" style="color:inherit;text-decoration:none;">Description <i class="fa fa-sort" id="sort-description"></i></a>
                            </th>
                            <th data-col="order_po" style="width:80px;">Order P.O.</th>
                            <th data-col="qty" style="width:70px;text-align:right;">
                                <a href="javascript:;" onclick="toggleSort('qty')" style="color:inherit;text-decoration:none;">Qty <i class="fa fa-sort" id="sort-qty"></i></a>
                            </th>
                            <th data-col="pcs" style="width:60px;text-align:right;">PCS</th>
                            <th data-col="weight" style="width:90px;text-align:right;">
                                <a href="javascript:;" onclick="toggleSort('weight_kg')" style="color:inherit;text-decoration:none;">Weight <i class="fa fa-sort" id="sort-weight_kg"></i></a>
                            </th>
                            <th data-col="measurement" style="width:90px;text-align:right;">
                                <a href="javascript:;" onclick="toggleSort('measure_cbm')" style="color:inherit;text-decoration:none;">Measurement <i class="fa fa-sort" id="sort-measure_cbm"></i></a>
                            </th>
                            <th data-col="status" style="width:70px;">Status</th>
                        </tr>
                        <tr id="filter-row" class="filter-row" style="display:none;">
                            <td data-col="date"><input class="filter-input" data-col-idx="0" placeholder="Date..." oninput="applyFilters()"></td>
                            <td data-col="customer"><input class="filter-input" data-col-idx="1" placeholder="Customer..." oninput="applyFilters()"></td>
                            <td data-col="file_no"></td>
                            <td data-col="office"><input class="filter-input" data-col-idx="3" placeholder="Office..." oninput="applyFilters()"></td>
                            <td data-col="trucker"></td>
                            <td data-col="from_to"></td>
                            <td data-col="sku"><input class="filter-input" data-col-idx="6" placeholder="SKU..." oninput="applyFilters()"></td>
                            <td data-col="po"></td>
                            <td data-col="product"><input class="filter-input" data-col-idx="8" placeholder="Description..." oninput="applyFilters()"></td>
                            <td data-col="order_po"></td>
                            <td data-col="qty"></td>
                            <td data-col="pcs"></td>
                            <td data-col="weight"></td>
                            <td data-col="measurement"></td>
                            <td data-col="status"></td>
                        </tr>
                    </thead>
                    <tbody id="grid-body">
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->receiving?->receiving_date?->format('m-d-Y') ?? $item->created_at?->format('m-d-Y') ?? '' }}</td>
                            <td>{{ $item->receiving?->customer?->name ?? $item->receiving?->receipt?->customer?->name ?? '' }}</td>
                            <td>{{ $item->receiving?->bl_no ?? '' }}</td>
                            <td>{{ $item->receiving?->office?->code ?? '' }}</td>
                            <td>{{ $item->receiving?->trucker?->name ?? '' }}</td>
                            <td>{{ $item->receiving?->shipFrom?->name ?? '' }}</td>
                            <td><span class="col-link">{{ $item->sku_no }}</span></td>
                            <td>{{ $item->customer_po ?? '' }}</td>
                            <td style="overflow:hidden;text-overflow:ellipsis;">{{ $item->description }}</td>
                            <td>{{ $item->order_po_no ?? '' }}</td>
                            <td style="text-align:right;">{{ number_format($item->qty ?? 0, 2) }}</td>
                            <td style="text-align:right;">{{ number_format($item->pack ?? 0, 2) }}</td>
                            <td style="text-align:right;">{{ number_format($item->weight_kg ?? 0, 2) }} KG</td>
                            <td style="text-align:right;">{{ number_format($item->measure_cbm ?? 0, 2) }} CBM</td>
                            <td>
                                @php $status = $item->receiving?->status ?? ''; @endphp
                                @if($status)
                                    <span class="badge-status {{ $status === 'Pre-Receiving' ? 'status-pre-receiving' : ($status === 'Received' ? 'status-received' : ($status === 'Shipped' ? 'status-shipped' : 'status-default')) }}">{{ $status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-row">
                            <td colspan="15" style="text-align:center;padding:30px 10px;color:#94a3b8;">
                                <i class="fa fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                                No inventory records found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                    @if($items->count() > 0)
                    <tfoot>
                        <tr class="totals-row">
                            <td colspan="10" style="text-align:right;">TOTAL</td>
                            <td style="text-align:right;">{{ number_format($totals['qty'] ?? 0, 2) }}</td>
                            <td style="text-align:right;">&mdash;</td>
                            <td style="text-align:right;">{{ number_format($totals['weight'] ?? 0, 2) }} KG</td>
                            <td style="text-align:right;">{{ number_format($totals['measure'] ?? 0, 2) }} CBM</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
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
    const FILTER_MAP = { 0: 'filter_date', 1: 'filter_customer', 3: 'filter_office', 6: 'filter_sku', 8: 'filter_item_name' };

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
            const newTfoot = doc.querySelector('#main-grid tfoot');
            const oldTfoot = document.querySelector('#main-grid tfoot');

            if (newBody) document.getElementById('grid-body').innerHTML = newBody.innerHTML;
            if (newPagination) document.getElementById('pagination-container').innerHTML = newPagination.innerHTML;

            if (newTfoot) {
                if (oldTfoot) oldTfoot.innerHTML = newTfoot.innerHTML;
                else document.querySelector('#main-grid').appendChild(newTfoot.cloneNode(true));
            } else if (oldTfoot) {
                oldTfoot.remove();
            }

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

            document.querySelectorAll('[id^="sort-"]').forEach(i => i.className = 'fa fa-sort');
            const params = new URLSearchParams(new URL(url).search);
            const sort = params.get('sort') || 'created_at';
            const dir = params.get('dir') || 'desc';
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
    const PINNED = [];
    const STORAGE_KEY = 'inventory_detail_column_config';
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
