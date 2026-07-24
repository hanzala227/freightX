<x-layout>
    @push('styles')
    <x-list-styles />
    <style>
        @media print {
            .page-bar, .portlet-title, .portlet-tool, .portlet-tool.bottom, #filter-row,
            .sticky-col, .overlay, .toast-container { display: none !important; }
            .portlet-body { padding: 0 !important; }
            .grid-container { overflow: visible !important; }
            .grid-wrapper { overflow: visible !important; }
            .grid-table { width: 100% !important; font-size: 9px; }
            .grid-table th, .grid-table td { padding: 3px 5px !important; border: 1px solid #ccc !important; }
            body { background: #fff !important; }
        }
    </style>
    @endpush

    <div class="toast-container" id="toast-container"></div>

    {{-- LOADING SPINNER --}}
    <div id="grid-loading" style="display:none;position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(255,255,255,0.7);z-index:100;align-items:center;justify-content:center;">
        <i class="fa fa-spinner fa-spin" style="font-size:24px;color:#3b82f6;"></i>
    </div>

    {{-- DELETE CONFIRM MODAL --}}
    <div class="overlay" id="confirm-overlay" onclick="if(event.target===this) closeConfirm()">
        <div class="confirm-box">
            <div class="confirm-icon"><i class="fa fa-exclamation-triangle"></i></div>
            <h4>Delete Record(s)?</h4>
            <p id="confirm-msg">This action cannot be undone.</p>
            <div class="confirm-actions">
                <button class="btn-tool" style="padding:0 18px;height:26px;" onclick="closeConfirm()">Cancel</button>
                <button class="btn-tool danger" style="padding:0 18px;height:26px;" onclick="executeDelete()">
                    <i class="fa fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    {{-- COLOR PICKER MODAL --}}
    <div class="overlay color-picker-overlay" id="color-picker-overlay" onclick="if(event.target===this) closeColorPicker()">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-header-title"><i class="fa fa-paint-brush" style="color:#3b82f6;"></i> Status Color</div>
                <button class="modal-close" onclick="closeColorPicker()"><i class="fa fa-times"></i></button>
            </div>
            <div class="modal-body" style="text-align:center;">
                <div class="color-picker-grid" id="color-picker-grid"></div>
                <div class="color-clear-btn" onclick="clearColor()">Clear / No Color</div>
            </div>
        </div>
    </div>

    {{-- MAIN PAGE --}}
    <div class="page-content">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
                <li>Warehouse <i class="fa fa-angle-right"></i></li>
                <li><span style="color: #333; font-weight: 700;">Receiving List</span></li>
            </ul>
        </div>

        <div class="portlet light">

            {{-- PORTLET TITLE --}}
            <div class="portlet-title">
                <div class="caption" style="display:flex;align-items:center;gap:8px;">
                    <span class="caption-subject">Warehouse Receiving List</span>
                    <span id="sel-badge" style="display:none;font-size:10px;color:#3b82f6;font-weight:600;background:#eff6ff;padding:1px 6px;border-radius:10px;border:1px solid #bfdbfe;"></span>
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
                    <button class="btn-action-round" onclick="window.print()" title="Print this page">
                        <i class="fa fa-print"></i> Print
                    </button>
                    <a class="btn-action-round white" href="{{ route('receiving.export-csv') }}" id="btn-excel" title="Download as CSV/Excel" target="_blank">
                        <i class="fa fa-file-excel-o"></i> Excel <i class="fa fa-angle-down"></i>
                    </a>
                </div>
            </div>

            {{-- TOOLBAR --}}
            <div class="portlet-tool">
                <div style="display:flex;gap:10px;align-items:center;">
                    <div class="btn-group">
                        <a class="btn-tool green" href="{{ route('receiving.create') }}" title="New Receiving" target="_blank">
                            <i class="fa fa-plus"></i>
                        </a>
                        <button class="btn-tool" id="btn-delete" disabled title="Delete Selected" onclick="confirmDelete()">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <i class="fa fa-search" style="font-size:10px;color:#94a3b8;"></i>
                    <input type="text" id="quick-search" class="input-inline" style="width:160px;"
                           placeholder="Quick search..." value="{{ request('search') }}"
                           oninput="quickSearch(this.value)">
                    <a href="javascript:;" id="clear-search-btn" onclick="clearSearch()" style="display:{{ request()->has('search') && request('search') ? 'inline' : 'none' }};font-size:10px;color:#3b82f6;text-decoration:none;cursor:pointer;" title="Clear search"><i class="fa fa-times-circle"></i></a>
                </div>
            </div>

            {{-- BULK-ACTION FORM + TABLE --}}
            <form id="bulk-form" method="POST" action="{{ route('receiving.bulk-delete') }}" style="margin:0;">
                @csrf
                @method('DELETE')
                <div class="portlet-body" style="position:relative;">
                    <div class="grid-container">
                        <div class="grid-wrapper">
                            <table class="grid-table" id="main-grid">
                                <thead>
                                    {{-- HEADER ROW --}}
                                    <tr id="header-row">
                                        <th class="sticky-col sticky-col-header" data-col="check" style="width:25px;text-align:center;">
                                            <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" title="Select All">
                                        </th>
                                        <th class="sticky-col sticky-col-header" data-col="color" style="width:30px;left:25px;text-align:center;">Color</th>
                                        <th class="sticky-col sticky-col-header" data-col="receipt_no" style="width:130px;left:55px;cursor:pointer;" onclick="toggleSort('receipt_no')">
                                            Receipt No. <i class="fa fa-sort" id="sort-receipt_no"></i>
                                        </th>
                                        <th class="sticky-col sticky-col-header" data-col="customer" style="width:150px;left:185px;">Customer</th>
                                        <th data-col="bl_no" style="width:120px;cursor:pointer;" onclick="toggleSort('bl_no')">
                                            B/L No. <i class="fa fa-sort" id="sort-bl_no"></i>
                                        </th>
                                        <th data-col="container_no" style="width:120px;cursor:pointer;" onclick="toggleSort('container_no')">
                                            Container No. <i class="fa fa-sort" id="sort-container_no"></i>
                                        </th>
                                        <th data-col="ship_from" style="width:150px;">Ship From</th>
                                        <th data-col="receiving_date" style="width:90px;cursor:pointer;" onclick="toggleSort('receiving_date')">
                                            In Date <i class="fa fa-sort" id="sort-receiving_date"></i>
                                        </th>
                                        <th data-col="post_date" style="width:90px;cursor:pointer;" onclick="toggleSort('post_date')">
                                            Post Date <i class="fa fa-sort" id="sort-post_date"></i>
                                        </th>
                                        <th data-col="order_date" style="width:90px;cursor:pointer;" onclick="toggleSort('order_date')">
                                            Order Date <i class="fa fa-sort" id="sort-order_date"></i>
                                        </th>
                                        <th data-col="status" style="width:100px;cursor:pointer;" onclick="toggleSort('status')">
                                            Status <i class="fa fa-sort" id="sort-status"></i>
                                        </th>
                                        <th data-col="pallet" style="width:100px;cursor:pointer;" onclick="toggleSort('pallet')">
                                            Pallet <i class="fa fa-sort" id="sort-pallet"></i>
                                        </th>
                                        <th data-col="office" style="width:80px;">Office</th>
                                        <th data-col="trucker" style="width:130px;">Trucker</th>
                                        <th data-col="operator" style="width:100px;">OP</th>
                                        <th data-col="quotation_no" style="width:100px;">Quotation No.</th>
                                        <th data-col="created_at" style="width:90px;cursor:pointer;" onclick="toggleSort('created_at')">
                                            Created <i class="fa fa-sort" id="sort-created_at"></i>
                                        </th>
                                    </tr>

                                    {{-- FILTER ROW (hidden by default) --}}
                                    <tr id="filter-row" style="display:none;">
                                        <td data-col="check" class="sticky-col" style="left:0;"></td>
                                        <td data-col="color" class="sticky-col" style="left:25px;"></td>
                                        <td data-col="receipt_no" class="sticky-col" style="left:55px;"><input class="filter-input" data-col-idx="2" placeholder="Receipt No..." oninput="applyFilters()"></td>
                                        <td data-col="customer" class="sticky-col" style="left:185px;"><input class="filter-input" data-col-idx="3" placeholder="Customer..." oninput="applyFilters()"></td>
                                        <td data-col="bl_no"><input class="filter-input" data-col-idx="4" placeholder="B/L No..." oninput="applyFilters()"></td>
                                        <td data-col="container_no"><input class="filter-input" data-col-idx="5" placeholder="Container..." oninput="applyFilters()"></td>
                                        <td data-col="ship_from"><input class="filter-input" data-col-idx="6" placeholder="Ship From..." oninput="applyFilters()"></td>
                                        <td data-col="receiving_date"><input class="filter-input" data-col-idx="7" placeholder="In Date..." oninput="applyFilters()"></td>
                                        <td data-col="post_date"><input class="filter-input" data-col-idx="8" placeholder="Post Date..." oninput="applyFilters()"></td>
                                        <td data-col="order_date"><input class="filter-input" data-col-idx="9" placeholder="Order Date..." oninput="applyFilters()"></td>
                                        <td data-col="status"><input class="filter-input" data-col-idx="10" placeholder="Status..." oninput="applyFilters()"></td>
                                        <td data-col="pallet"><input class="filter-input" data-col-idx="11" placeholder="Pallet..." oninput="applyFilters()"></td>
                                        <td data-col="office"><input class="filter-input" data-col-idx="12" placeholder="Office..." oninput="applyFilters()"></td>
                                        <td data-col="trucker"><input class="filter-input" data-col-idx="13" placeholder="Trucker..." oninput="applyFilters()"></td>
                                        <td data-col="operator" colspan="3"></td>
                                    </tr>
                                </thead>

                                <tbody id="grid-body">
                                @forelse($receivings as $receiving)
                                    <tr id="row-{{ $receiving->id }}" data-id="{{ $receiving->id }}" onclick="rowClick(event, this)">
                                        <td class="sticky-col" style="width:25px;text-align:center;" onclick="event.stopPropagation()">
                                            <input type="checkbox" name="ids[]" value="{{ $receiving->id }}" class="row-check" onchange="updateToolbar()">
                                        </td>
                                        <td class="sticky-col" style="width:30px;left:25px;text-align:center;">
                                            <span class="color-mark" style="background:{{ $receiving->color ?? '#94a3b8' }}" title="Click to change status color" onclick="event.stopPropagation();openColorPicker({{ $receiving->id }}, '{{ $receiving->color ?? '' }}')"></span>
                                        </td>
                                        <td class="sticky-col" style="width:130px;left:55px;" onclick="event.stopPropagation()">
                                            <a href="{{ route('receiving.edit', $receiving->id) }}" class="col-link">{{ $receiving->receipt ? $receiving->receipt->receipt_no : 'WR-' . $receiving->id }}</a>
                                        </td>
                                        <td class="sticky-col" style="width:150px;left:185px;">
                                            {{ $receiving->customer->name ?? ($receiving->receipt->customer->name ?? '') }}
                                        </td>
                                        <td>{{ $receiving->bl_no }}</td>
                                        <td>{{ $receiving->container_no }}</td>
                                        <td>{{ $receiving->shipFrom->name ?? ($receiving->receipt->shipper->name ?? '') }}</td>
                                        <td>{{ $receiving->receiving_date ? $receiving->receiving_date->format('m-d-Y') : '' }}</td>
                                        <td>{{ $receiving->post_date ? $receiving->post_date->format('m-d-Y') : '' }}</td>
                                        <td>{{ $receiving->order_date ? $receiving->order_date->format('m-d-Y') : '' }}</td>
                                        <td>
                                            @if($receiving->status)
                                                @php
                                                    $statusClass = match($receiving->status) {
                                                        'Complete' => 'bg-green',
                                                        'Receiving' => 'bg-blue',
                                                        'Pre-Receiving' => 'bg-orange',
                                                        default => 'bg-blue',
                                                    };
                                                @endphp
                                                <span class="badge-status {{ $statusClass }}">{{ $receiving->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $receiving->pallet }}</td>
                                        <td>{{ $receiving->office->code ?? '' }}</td>
                                        <td>{{ $receiving->trucker->name ?? ($receiving->receipt->carrier_name ?? '') }}</td>
                                        <td>{{ $receiving->operator->name ?? '' }}</td>
                                        <td>{{ $receiving->quotation_no }}</td>
                                        <td>{{ $receiving->created_at->format('m-d-Y') }}</td>
                                    </tr>
                                @empty
                                    <tr id="empty-row">
                                        <td colspan="50" style="text-align:center;padding:30px 10px;color:#94a3b8;">
                                            <i class="fa fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                                            No receiving records found.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>

            {{-- PAGINATION --}}
            <div class="portlet-tool bottom">
                <div style="display:flex;justify-content:space-between;width:100%;align-items:center;">
                    <div id="pagination-container">{{ $receivings->links() }}</div>
                    <div style="font-size:10px;color:#64748b;">
                        Showing <span id="stat-first">{{ $receivings->firstItem() ?? 0 }}</span> – <span id="stat-last">{{ $receivings->lastItem() ?? 0 }}</span> of <span id="stat-total">{{ $receivings->total() }}</span> records
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    /* TOOLBAR */
    function updateToolbar() {
        const checked = [...document.querySelectorAll('.row-check:checked')];
        const all = [...document.querySelectorAll('.row-check')];
        const n = checked.length;
        const sa = document.getElementById('select-all');
        sa.checked = n === all.length && all.length > 0;
        sa.indeterminate = n > 0 && n < all.length;
        document.getElementById('btn-delete').disabled = n === 0;
        const badge = document.getElementById('sel-badge');
        badge.style.display = n > 0 ? 'inline' : 'none';
        badge.textContent = n + ' selected';
        document.querySelectorAll('#grid-body tr[data-id]').forEach(row => {
            const cb = row.querySelector('.row-check');
            row.classList.toggle('row-selected', cb && cb.checked);
        });
    }
    function toggleSelectAll(el) { document.querySelectorAll('.row-check').forEach(cb => cb.checked = el.checked); updateToolbar(); }
    function rowClick(e, row) { const skip = ['A', 'INPUT', 'BUTTON', 'I']; if (skip.includes(e.target.tagName)) return; const cb = row.querySelector('.row-check'); if (cb) { cb.checked = !cb.checked; updateToolbar(); } }

    /* DELETE */
    function confirmDelete() { const n = document.querySelectorAll('.row-check:checked').length; if (!n) return; document.getElementById('confirm-msg').textContent = `Delete ${n} record(s)? This cannot be undone.`; document.getElementById('confirm-overlay').classList.add('open'); }
    function closeConfirm() { document.getElementById('confirm-overlay').classList.remove('open'); }
    function executeDelete() {
        closeConfirm();
        const ids = getSelectedIds();
        if (!ids.length) return;
        showToast('info', 'Deleting...');
        fetch('{{ route("receiving.bulk-delete") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
            body: JSON.stringify({ ids }),
        }).then(r => r.json()).then(data => {
            if (data.success) { showToast('success', data.message || 'Deleted successfully'); updateGrid(window.location.href); }
            else showToast('error', data.message || 'Failed to delete');
        }).catch(() => showToast('error', 'Failed to delete'));
    }

    /* FILTER ROW */
    let filterOpen = false;
    const FILTER_MAP = { 2: 'filter_receipt_no', 3: 'filter_customer', 4: 'filter_bl_no', 5: 'filter_container_no', 6: 'filter_ship_from', 7: 'filter_receiving_date', 8: 'filter_post_date', 9: 'filter_order_date', 10: 'filter_status', 11: 'filter_pallet', 12: 'filter_office', 13: 'filter_trucker' };

    function toggleFilter() {
        filterOpen = !filterOpen;
        const row = document.getElementById('filter-row');
        if (row) { row.style.display = filterOpen ? 'table-row' : 'none'; }
        document.getElementById('btn-filter').classList.toggle('active', filterOpen);

        if (filterOpen) {
            restoreFilterRow(new URLSearchParams(window.location.search));
            document.querySelector('#filter-row .filter-input')?.focus();
        } else {
            document.querySelectorAll('#filter-row .filter-input').forEach(i => { i.value = ''; });
            applyFilters();
        }
    }

    function restoreFilterRow(params) {
        document.querySelectorAll('#filter-row .filter-input').forEach(inp => {
            const param = FILTER_MAP[inp.dataset.colIdx];
            inp.value = param ? (params.get(param) || '') : '';
        });
    }

    function autoOpenFilterIfNeeded() {
        const params = new URLSearchParams(window.location.search);
        const hasFilter = Object.values(FILTER_MAP).some(k => params.has(k) && params.get(k));
        if (hasFilter) {
            filterOpen = true;
            document.getElementById('filter-row').style.display = 'table-row';
            document.getElementById('btn-filter').classList.add('active');
            restoreFilterRow(params);
        }
    }

    /* LOADING */
    function showLoading() { const el = document.getElementById('grid-loading'); if (el) el.style.display = 'flex'; }
    function hideLoading() { const el = document.getElementById('grid-loading'); if (el) el.style.display = 'none'; }

    /* EXCEL LINK CARRY FILTERS */
    function updateExcelLink() {
        const url = new URL(window.location.href);
        const btn = document.getElementById('btn-excel');
        if (btn) btn.href = url.pathname + '?' + url.searchParams.toString();
    }

    /* CLEAR SEARCH */
    function clearSearch() {
        document.getElementById('quick-search').value = '';
        document.getElementById('clear-search-btn').style.display = 'none';
        const url = new URL(window.location.href);
        url.searchParams.delete('search');
        updateGrid(url.toString());
    }

    /* AJAX GRID */
    async function updateGrid(url) {
        showLoading();
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('Network error');
            const html = await response.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const newBody = doc.getElementById('grid-body');
            const newPagination = doc.getElementById('pagination-container');
            if (newBody) document.getElementById('grid-body').innerHTML = newBody.innerHTML;
            if (newPagination) document.getElementById('pagination-container').innerHTML = newPagination.innerHTML;
            const stats = doc.querySelector('.portlet-tool.bottom div:last-child');
            if (stats) { const m = stats.textContent.match(/\d+/g); if (m && m.length >= 3) { document.getElementById('stat-first').textContent = m[0]; document.getElementById('stat-last').textContent = m[1]; document.getElementById('stat-total').textContent = m[2]; } }
            /* Update sort icons from URL */
            const urlObj = new URL(url);
            const newSort = urlObj.searchParams.get('sort') || 'created_at';
            const newDir = urlObj.searchParams.get('dir') || 'desc';
            document.querySelectorAll('[id^="sort-"]').forEach(el => { el.className = 'fa fa-sort'; });
            const sortIcon = document.getElementById('sort-' + newSort);
            if (sortIcon) { sortIcon.className = 'fa fa-sort-' + (newDir === 'asc' ? 'asc' : 'desc'); }
            /* Update clear-search visibility */
            const searchVal = urlObj.searchParams.get('search') || '';
            document.getElementById('clear-search-btn').style.display = searchVal ? 'inline' : 'none';
            /* Update Excel link */
            updateExcelLink();
            /* Restore filter row values if open */
            if (filterOpen) restoreFilterRow(urlObj.searchParams);
            window.history.pushState({}, '', url);
            updateToolbar();
            loadColumnConfig();
        } catch (e) { showToast('error', 'Failed to update grid'); }
        finally { hideLoading(); }
    }
    document.addEventListener('click', function(e) { const link = e.target.closest('.pagination a'); if (link) { e.preventDefault(); updateGrid(link.href); } });
    function getSelectedIds() { return [...document.querySelectorAll('.row-check:checked')].map(cb => cb.value); }
    function refreshGrid() { updateGrid(window.location.href); }

    /* POPSTATE */
    window.addEventListener('popstate', function() { updateGrid(window.location.href); });

    /* SEARCH */
    let searchDebounce;
    function quickSearch(val) {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => {
            const q = val.trim();
            const url = new URL(window.location.href);
            if (!q) url.searchParams.delete('search'); else url.searchParams.set('search', q);
            url.searchParams.delete('page');
            updateGrid(url.toString());
        }, 300);
    }

    /* FILTER APPLY */
    let filterDebounce;
    function applyFilters() {
        clearTimeout(filterDebounce);
        filterDebounce = setTimeout(() => {
            const url = new URL(window.location.href);
            const search = url.searchParams.get('search') || '';
            const sort = url.searchParams.get('sort') || 'created_at';
            const dir = url.searchParams.get('dir') || 'desc';
            url.search = '';
            if (search) url.searchParams.set('search', search);
            url.searchParams.set('sort', sort);
            url.searchParams.set('dir', dir);
            document.querySelectorAll('#filter-row .filter-input').forEach(inp => {
                const v = inp.value.trim();
                if (!v) return;
                const param = FILTER_MAP[inp.dataset.colIdx];
                if (param) url.searchParams.set(param, v);
            });
            updateGrid(url.toString());
        }, 300);
    }

    /* SORTING */
    function toggleSort(field) {
        const url = new URL(window.location.href);
        let curSort = url.searchParams.get('sort') || 'created_at';
        let curDir = url.searchParams.get('dir') || 'desc';
        if (curSort === field) { curDir = curDir === 'asc' ? 'desc' : 'asc'; }
        else { curSort = field; curDir = 'asc'; }
        url.searchParams.set('sort', curSort);
        url.searchParams.set('dir', curDir);
        url.searchParams.delete('page');
        updateGrid(url.toString());
    }

    /* CONFIG */
    const PINNED_COLS = ['check', 'color', 'receipt_no', 'customer'];
    const CONFIG_STORAGE_KEY = 'warehouse_receiving_column_config';

    function loadColumnConfig() {
        try {
            const saved = localStorage.getItem(CONFIG_STORAGE_KEY);
            if (!saved) return;
            const hidden = JSON.parse(saved);
            document.querySelectorAll('#header-row th[data-col]').forEach(th => {
                const col = th.dataset.col;
                if (PINNED_COLS.includes(col)) return;
                if (hidden.includes(col)) th.style.display = 'none';
            });
            document.querySelectorAll('#grid-body tr').forEach(row => {
                document.querySelectorAll('#header-row th[data-col]').forEach((th, i) => {
                    const col = th.dataset.col;
                    if (PINNED_COLS.includes(col)) return;
                    if (hidden.includes(col)) {
                        const cells = row.querySelectorAll('td, th');
                        if (cells[i]) cells[i].style.display = 'none';
                    }
                });
            });
            document.querySelectorAll('#filter-row td[data-col]').forEach(td => {
                const col = td.dataset.col;
                if (PINNED_COLS.includes(col)) return;
                if (hidden.includes(col)) td.style.display = 'none';
            });
        } catch (e) {}
    }

    function toggleConfig() { const panel = document.getElementById('config-panel'); const open = panel.style.display === 'none'; panel.style.display = open ? 'block' : 'none'; document.getElementById('btn-config').classList.toggle('active', open); if (open) buildConfigPanel(); }

    function buildConfigPanel() { const container = document.getElementById('col-toggles'); container.innerHTML = ''; const hidden = getHiddenColumns(); document.querySelectorAll('#header-row th[data-col]').forEach(th => { if (PINNED_COLS.includes(th.dataset.col)) return; const label = document.createElement('label'); const cb = document.createElement('input'); cb.type = 'checkbox'; cb.checked = !hidden.includes(th.dataset.col); cb.onchange = () => toggleColumn(th.dataset.col, cb.checked); label.appendChild(cb); label.append(' ' + th.textContent.trim()); container.appendChild(label); }); }

    function getHiddenColumns() { const hidden = []; document.querySelectorAll('#header-row th[data-col]').forEach(th => { if (PINNED_COLS.includes(th.dataset.col)) return; if (th.style.display === 'none') hidden.push(th.dataset.col); }); return hidden; }
    function saveColumnConfig() { try { localStorage.setItem(CONFIG_STORAGE_KEY, JSON.stringify(getHiddenColumns())); } catch (e) {} }

    function toggleColumn(colName, show) {
        const th = document.querySelector('#header-row th[data-col="' + colName + '"]');
        const idx = [...th.parentElement.children].indexOf(th);
        th.style.display = show ? '' : 'none';
        document.querySelectorAll('#grid-body tr').forEach(row => {
            const cell = row.querySelectorAll('td, th')[idx];
            if (cell) cell.style.display = show ? '' : 'none';
        });
        const filterTd = document.querySelector('#filter-row td[data-col="' + colName + '"]');
        if (filterTd) filterTd.style.display = show ? '' : 'none';
        saveColumnConfig();
    }

    document.addEventListener('click', e => { const panel = document.getElementById('config-panel'); const btn = document.getElementById('btn-config'); if (panel.style.display !== 'none' && !panel.contains(e.target) && !btn.contains(e.target)) { panel.style.display = 'none'; btn.classList.remove('active'); } });

    /* COLOR PICKER */
    const COLOR_OPTIONS = [
        { label: 'Urgent', value: '#E08283' },
        { label: 'Ready to bill', value: '#F3C200' },
        { label: 'Ready to close', value: '#25A69A' },
        { label: 'Postpone', value: '#4B77BE' },
        { label: 'Freight Finalized', value: '#9B9B9B' },
    ];
    let _colorReceivingId = null;
    function openColorPicker(id, currentColor) { _colorReceivingId = id; const grid = document.getElementById('color-picker-grid'); grid.innerHTML = COLOR_OPTIONS.map(o => { const active = o.value === currentColor; return '<div class="color-picker-opt ' + (active ? 'active' : '') + '" onclick="selectColor(\'' + o.value + '\', this)"><span class="swatch" style="background:' + o.value + '"></span><span>' + o.label + '</span><i class="fa fa-check"></i></div>'; }).join(''); document.getElementById('color-picker-overlay').classList.add('open'); }
    function selectColor(color, el) { document.querySelectorAll('.color-picker-opt').forEach(c => c.classList.remove('active')); el.classList.add('active'); const id = _colorReceivingId; fetch('{{ route("receiving.update-color", "ID") }}'.replace('ID', id), { method: 'PATCH', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ color }) }).then(r => r.json()).then(data => { if (data.success) { const span = document.querySelector('#row-' + id + ' .color-mark'); if (span) span.style.background = color; showToast('success', 'Status color updated'); } }).catch(() => showToast('error', 'Failed to update color')); closeColorPicker(); }
    function closeColorPicker() { document.getElementById('color-picker-overlay').classList.remove('open'); _colorReceivingId = null; }
    function clearColor() { const id = _colorReceivingId; fetch('{{ route("receiving.update-color", "ID") }}'.replace('ID', id), { method: 'PATCH', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ color: '' }) }).then(r => r.json()).then(data => { if (data.success) { const span = document.querySelector('#row-' + id + ' .color-mark'); if (span) span.style.background = '#94a3b8'; showToast('success', 'Status color cleared'); } }).catch(() => showToast('error', 'Failed to clear color')); closeColorPicker(); }

    /* TOAST */
    function showToast(type, msg) { const icons = { success: 'check-circle', error: 'times-circle', info: 'info-circle' }; const t = document.createElement('div'); t.className = 'toast ' + type; t.innerHTML = '<i class="fa fa-' + (icons[type] || 'info-circle') + '"></i> <span>' + msg + '</span>'; document.getElementById('toast-container').appendChild(t); setTimeout(() => t.remove(), 4000); }

    /* INIT */
    (function() {
        const params = new URLSearchParams(window.location.search);
        const sort = params.get('sort') || 'created_at';
        const dir = params.get('dir') || 'desc';
        const icon = document.getElementById('sort-' + sort);
        if (icon) { icon.className = 'fa fa-sort-' + (dir === 'asc' ? 'asc' : 'desc'); }
        loadColumnConfig();
        updateExcelLink();
        autoOpenFilterIfNeeded();
    })();

    @if(session('success')) showToast('success', @json(session('success'))); @endif
    @if(session('error')) showToast('error', @json(session('error'))); @endif
    </script>
    @endpush
</x-layout>
