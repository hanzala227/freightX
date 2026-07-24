<x-layout>
    @push('styles')
    <x-list-styles />
    @endpush

    {{-- ═══════ TOAST CONTAINER ═══════ --}}
    <div class="toast-container" id="toast-container"></div>

    {{-- ═══════ DELETE CONFIRM MODAL ═══════ --}}
    <div class="overlay" id="confirm-overlay" onclick="if(event.target===this) closeConfirm()">
        <div class="confirm-box">
            <div class="confirm-icon"><i class="fa fa-exclamation-triangle"></i></div>
            <h4>Delete Shipment(s)?</h4>
            <p id="confirm-msg">This action cannot be undone.</p>
            <div class="confirm-actions">
                <button class="btn-tool" style="padding:0 18px;height:26px;" onclick="closeConfirm()">Cancel</button>
                <button class="btn-tool danger" style="padding:0 18px;height:26px;" onclick="executeDelete()">
                    <i class="fa fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════ COLOR PICKER MODAL ═══════ --}}
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

    {{-- ═══════ MAIN PAGE ═══════ --}}
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
                <li>Truck <i class="fa fa-angle-right"></i></li>
                <li><span style="color:#333;font-weight:700;">Shipment List</span></li>
            </ul>
        </div>

        <div class="portlet light">
            {{-- ── PORTLET TITLE ── --}}
            <div class="portlet-title">
                <div class="caption" style="display:flex;align-items:center;gap:8px;">
                    <span class="caption-subject">SHIPMENT LIST</span>
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
                    <button class="btn-action-round" onclick="window.print()" title="Print this page">
                        <i class="fa fa-print"></i> Print
                    </button>
                    <a class="btn-action-round white" href="{{ route('truck.export-csv') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" title="Download as CSV/Excel" target="_blank">
                        <i class="fa fa-file-excel-o"></i> Excel <i class="fa fa-angle-down"></i>
                    </a>
                </div>
            </div>

            {{-- ── TOOLBAR ── --}}
            <div class="portlet-tool">
                <div style="display:flex;gap:10px;align-items:center;">
                    <div class="btn-group">
                        <a class="btn-tool green" href="{{ route('truck.create') }}" title="New Shipment" target="_blank">
                            <i class="fa fa-plus"></i>
                        </a>
                        <button class="btn-tool" id="btn-copy"   disabled title="Copy Selected" onclick="copySelected()">
                            <i class="fa fa-files-o"></i>
                        </button>
                        <button class="btn-tool" id="btn-delete" disabled title="Delete Selected" onclick="confirmDelete()">
                            <i class="fa fa-trash"></i>
                        </button>
                        <button class="btn-tool" title="Refresh" onclick="updateGrid(window.location.href)">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <i class="fa fa-search" style="font-size:10px;color:#94a3b8;"></i>
                    <input type="text" id="quick-search" class="input-inline" style="width:160px;"
                           placeholder="Quick search..." oninput="quickSearch(this.value)">
                </div>
            </div>

            {{-- ── TABLE ── --}}
            <form id="bulk-form" method="POST" action="{{ route('truck.index') }}" style="margin:0;">
                @csrf
                @method('DELETE')
                <div class="portlet-body">
                    <div class="grid-container">
                        <div class="grid-wrapper">
                            <table class="grid-table" id="main-grid">
                                <thead>
                                    <tr id="header-row">
                                        <th class="sticky-col sticky-col-header" data-col="check"   style="width:25px;text-align:center;">
                                            <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" title="Select All">
                                        </th>
                                        <th class="sticky-col sticky-col-header" data-col="lock"    style="width:25px;left:25px;text-align:center;"><i class="fa fa-lock"></i></th>
                                        <th class="sticky-col sticky-col-header" data-col="file_no" style="width:110px;left:50px;">File No.</th>
                                        <th class="sticky-col sticky-col-header" data-col="color"   style="width:35px;left:160px;text-align:center;">Color</th>
                                        <th data-col="post_date" data-sort="post_date" style="width:90px;cursor:pointer;" onclick="toggleSort('post_date')">
                                            Post Date <span id="sort-post_date" class="sort-icon"><i class="fa fa-sort"></i></span>
                                        </th>
                                        <th data-col="customer"  style="width:140px;">Customer</th>
                                        <th data-col="trucker"   style="width:140px;">Trucker</th>
                                        <th data-col="mbl_no"    data-sort="mbl_no"    style="width:120px;cursor:pointer;" onclick="toggleSort('mbl_no')">
                                            MB/L No. <span id="sort-mbl_no" class="sort-icon"><i class="fa fa-sort"></i></span>
                                        </th>
                                        <th data-col="hbl_no"    style="width:120px;">HB/L No.</th>
                                        <th data-col="pkg_qty"   data-sort="pkg_qty"   style="width:70px;text-align:right;cursor:pointer;" onclick="toggleSort('pkg_qty')">
                                            Package <span id="sort-pkg_qty" class="sort-icon"><i class="fa fa-sort"></i></span>
                                        </th>
                                        <th data-col="weight"    data-sort="weight_kg" style="width:80px;text-align:right;cursor:pointer;" onclick="toggleSort('weight_kg')">
                                            Weight <span id="sort-weight_kg" class="sort-icon"><i class="fa fa-sort"></i></span>
                                        </th>
                                        <th data-col="pod"       style="width:130px;">Port of Discharge</th>
                                        <th data-col="fdest"     style="width:140px;">Final Destination</th>
                                        <th data-col="ar_bal"    style="width:100px;text-align:right;">AR Balance</th>
                                        <th data-col="do"        style="width:50px;text-align:center;">D/O</th>
                                        <th data-col="action"    style="width:30px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="grid-body">
                                    @include('truck.partials.list-rows', ['shipments' => $shipments])
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>

            {{-- ── PAGINATION ── --}}
            <div class="portlet-tool bottom">
                <div style="display:flex;justify-content:space-between;width:100%;align-items:center;">
                    <div id="pagination-container">
                        @include('truck.partials.list-pagination', ['shipments' => $shipments])
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:10px;color:#64748b;">
                        <select class="input-inline" style="width:60px;" onchange="changePageSize(this.value)">
                            <option value="10" >10</option>
                            <option value="20" >20</option>
                            <option value="50" >50</option>
                            <option value="100">100</option>
                        </select>
                        <span>records</span>
                        Showing <span id="stat-first">{{ $shipments->firstItem() ?? 0 }}</span> – <span id="stat-last">{{ $shipments->lastItem() ?? 0 }}</span> of <span id="stat-total">{{ $shipments->total() }}</span> records
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    /* ================================================================
       TOOLBAR
    ================================================================ */
    function updateToolbar() {
        const checked  = [...document.querySelectorAll('.row-check:checked')];
        const all      = [...document.querySelectorAll('.row-check')];
        const n        = checked.length;
        const sa       = document.getElementById('select-all');
        sa.checked        = n === all.length && all.length > 0;
        sa.indeterminate  = n > 0 && n < all.length;

        document.getElementById('btn-delete').disabled  = n === 0;
        document.getElementById('btn-copy').disabled    = n !== 1;

        const badge = document.getElementById('sel-badge');
        badge.style.display = n > 0 ? 'inline' : 'none';
        badge.textContent   = n + ' selected';

        document.querySelectorAll('#grid-body tr[data-id]').forEach(row => {
            const cb = row.querySelector('.row-check');
            row.classList.toggle('row-selected', cb && cb.checked);
        });
    }

    function toggleSelectAll(el) {
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = el.checked);
        updateToolbar();
    }

    function rowClick(e, row) {
        const skip = ['A', 'INPUT', 'BUTTON', 'I'];
        if (skip.includes(e.target.tagName)) return;
        const cb = row.querySelector('.row-check');
        if (cb) { cb.checked = !cb.checked; updateToolbar(); }
    }

    /* ================================================================
       DELETE
    ================================================================ */
    function confirmDelete() {
        const n = document.querySelectorAll('.row-check:checked').length;
        if (!n) return;
        document.getElementById('confirm-msg').textContent =
            `You are about to permanently delete ${n} shipment(s). This cannot be undone.`;
        document.getElementById('confirm-overlay').classList.add('open');
    }
    function closeConfirm() {
        document.getElementById('confirm-overlay').classList.remove('open');
    }
    
    async function executeDelete() {
        const ids = getSelectedIds();
        closeConfirm();
        if (!ids.length) return;

        showToast('info', 'Deleting ' + ids.length + ' shipment(s)...');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        try {
            const res = await fetch('{{ route("truck.bulk-delete") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ ids }),
            });
            const data = await res.json();
            if (data.success) {
                showToast('success', data.message || 'Deleted successfully');
                setTimeout(() => updateGrid(window.location.href), 600);
            } else {
                showToast('error', data.message || 'Failed to delete');
            }
        } catch (e) {
            showToast('error', 'Delete failed: ' + e.message);
        }
    }

    function copySelected() {
        const checked = [...document.querySelectorAll('.row-check:checked')];
        if (checked.length !== 1) return;
        const row = checked[0].closest('tr');
        showToast('info', 'Copying shipment: ' + (row?.dataset?.file || ''));
        setTimeout(() => {
            window.location.href = '{{ route("truck.create") }}?copy=' + (row?.dataset?.id || '');
        }, 600);
    }

    function toggleLock(el) {
        const locked = el.classList.contains('fa-lock');
        el.classList.toggle('fa-lock', !locked);
        el.classList.toggle('fa-unlock', locked);
        el.style.color = locked ? '#22c55e' : '#94a3b8';
        el.title = locked ? 'Unlock' : 'Lock';
        showToast('info', locked ? 'Row unlocked.' : 'Row locked.');
    }

    function getSelectedIds() {
        return [...document.querySelectorAll('.row-check:checked')].map(cb => cb.value);
    }

    /* ================================================================
       QUICK SEARCH
    ================================================================ */
    var searchDebounce;
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

    /* ================================================================
       FILTER ROW
    ================================================================ */
    function toggleFilter() {
        var filterRow = document.getElementById('filter-row');
        if (!filterRow) {
            // Create filter row on first use
            createFilterRow();
            filterRow = document.getElementById('filter-row');
        }
        var isVisible = filterRow.style.display === 'table-row';
        filterRow.style.display = isVisible ? 'none' : 'table-row';
        document.getElementById('btn-filter').classList.toggle('active', !isVisible);

        if (isVisible) {
            document.querySelectorAll('.filter-input').forEach(i => { i.value = ''; });
            applyFilters();
        }
    }

    function createFilterRow() {
        const headerRow = document.getElementById('header-row');
        if (!headerRow) return;
        const filterRow = document.createElement('tr');
        filterRow.id = 'filter-row';
        filterRow.style.display = 'none';

        const cols = [
            { idx: 3, field: 'filter_file_no', placeholder: 'File No...' },
            { idx: 5, field: 'filter_customer', placeholder: 'Customer...' },
        ];

        const ths = headerRow.querySelectorAll('th');
        const totalCols = ths.length;

        for (let i = 0; i < totalCols; i++) {
            const td = document.createElement('td');
            const colAttr = ths[i]?.dataset?.col;
            if (colAttr === 'file_no') {
                const inp = document.createElement('input');
                inp.className = 'filter-input';
                inp.dataset.colIdx = '3';
                inp.placeholder = 'File No...';
                inp.oninput = () => applyFilters();
                td.appendChild(inp);
            } else if (colAttr === 'customer') {
                const inp = document.createElement('input');
                inp.className = 'filter-input';
                inp.dataset.colIdx = '5';
                inp.placeholder = 'Customer...';
                inp.oninput = () => applyFilters();
                td.appendChild(inp);
            }
            filterRow.appendChild(td);
        }
        headerRow.parentElement.insertBefore(filterRow, headerRow.nextSibling);
    }

    var filterDebounce;
    function applyFilters() {
        clearTimeout(filterDebounce);
        filterDebounce = setTimeout(() => {
            const inputs = [...document.querySelectorAll('#filter-row .filter-input')];
            var url = new URL(window.location.href);
            const search = url.searchParams.get('search') || '';
            url.search = '';
            if (search) url.searchParams.set('search', search);
            inputs.forEach(inp => {
                const v = inp.value.trim();
                if (!v) return;
                const idx = parseInt(inp.dataset.colIdx);
                if (idx === 3) url.searchParams.set('filter_file_no', v);
                else if (idx === 5) url.searchParams.set('filter_customer', v);
            });
            updateGrid(url.toString());
        }, 300);
    }

    /* ================================================================
       AJAX GRID UPDATE
    ================================================================ */
    async function updateGrid(url) {
        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();

            if (data.html) {
                document.getElementById('grid-body').innerHTML = data.html;
            }
            if (data.pagination) {
                document.getElementById('pagination-container').innerHTML = data.pagination;
            }
            if (data.total !== undefined) {
                document.getElementById('stat-total').textContent = data.total;
                document.getElementById('stat-first').textContent = data.from || 0;
                document.getElementById('stat-last').textContent = data.to || 0;
            }

            // Update sort icons from URL params
            document.querySelectorAll('[id^="sort-"]').forEach(el => {
                el.innerHTML = '<i class="fa fa-sort"></i>';
            });
            const urlObj = new URL(url, window.location.origin);
            const sortField = urlObj.searchParams.get('sort');
            const sortDir = urlObj.searchParams.get('dir');
            if (sortField) {
                const icon = document.getElementById('sort-' + sortField);
                if (icon) {
                    icon.innerHTML = '<i class="fa fa-sort-' + (sortDir === 'asc' ? 'asc' : 'desc') + '"></i>';
                }
            }

            // Push state to update address bar
            window.history.pushState({}, '', url);

            updateToolbar();
            loadColumnConfig();
        } catch (e) {
            console.error(e);
            showToast('error', 'Failed to update grid');
        }
    }

    // Wire pagination links to use AJAX
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            updateGrid(link.href);
        }
    });

    window.addEventListener('popstate', function() {
        updateGrid(window.location.href);
    });

    /* ================================================================
       SORT
    ================================================================ */
    function toggleSort(field) {
        const url = new URL(window.location.href);
        const currentSort = url.searchParams.get('sort');
        const currentDir  = url.searchParams.get('dir');

        let dir = 'asc';
        if (currentSort === field) {
            dir = currentDir === 'asc' ? 'desc' : 'asc';
        }

        url.searchParams.set('sort', field);
        url.searchParams.set('dir', dir);
        url.searchParams.delete('page');

        updateGrid(url.toString());
    }

    /* ================================================================
       CONFIG PANEL
    ================================================================ */
    var PINNED_COLS = ['check', 'lock', 'file_no', 'color'];

    function toggleConfig() {
        const panel = document.getElementById('config-panel');
        const open  = panel.style.display === 'none';
        panel.style.display = open ? 'block' : 'none';
        document.getElementById('btn-config').classList.toggle('active', open);
        if (open) buildConfigPanel();
    }

    function buildConfigPanel() {
        const container = document.getElementById('col-toggles');
        container.innerHTML = '';
        document.querySelectorAll('#header-row th[data-col]').forEach(th => {
            if (PINNED_COLS.includes(th.dataset.col)) return;
            const label = document.createElement('label');
            const cb    = document.createElement('input');
            cb.type    = 'checkbox';
            cb.checked = th.style.display !== 'none';
            cb.onchange = () => toggleColumn(th.dataset.col, cb.checked);
            label.appendChild(cb);
            label.append(' ' + (th.textContent.trim()));
            container.appendChild(label);
        });
    }

    function toggleColumn(colName, show) {
        const th  = document.querySelector(`#header-row th[data-col="${colName}"]`);
        if (!th) return;
        const idx = [...th.parentElement.children].indexOf(th);
        th.style.display = show ? '' : 'none';
        document.querySelectorAll('#grid-body tr, #filter-row').forEach(row => {
            if (!row) return;
            const cell = row.querySelectorAll('td, th')[idx];
            if (cell) cell.style.display = show ? '' : 'none';
        });
        saveColumnConfig();
    }

    const CONFIG_STORAGE_KEY = 'truck_shipment_column_config';

    function loadColumnConfig() {
        try {
            const saved = localStorage.getItem(CONFIG_STORAGE_KEY);
            if (!saved) return;
            const hidden = JSON.parse(saved);
            document.querySelectorAll('#header-row th[data-col]').forEach(th => {
                const col = th.dataset.col;
                if (PINNED_COLS.includes(col)) return;
                if (hidden.includes(col)) {
                    th.style.display = 'none';
                }
            });
            document.querySelectorAll('#grid-body tr, #filter-row').forEach(row => {
                if (!row) return;
                document.querySelectorAll('#header-row th[data-col]').forEach((th, i) => {
                    const col = th.dataset.col;
                    if (PINNED_COLS.includes(col)) return;
                    if (hidden.includes(col)) {
                        const cells = row.querySelectorAll('td, th');
                        if (cells[i]) cells[i].style.display = 'none';
                    }
                });
            });
        } catch (e) { /* ignore */ }
    }

    function saveColumnConfig() {
        try {
            const hidden = [];
            document.querySelectorAll('#header-row th[data-col]').forEach(th => {
                if (PINNED_COLS.includes(th.dataset.col)) return;
                if (th.style.display === 'none') hidden.push(th.dataset.col);
            });
            localStorage.setItem(CONFIG_STORAGE_KEY, JSON.stringify(hidden));
        } catch (e) { /* ignore */ }
    }

    document.addEventListener('click', e => {
        const panel = document.getElementById('config-panel');
        const btn   = document.getElementById('btn-config');
        if (panel?.style.display !== 'none' && panel && !panel.contains(e.target) && !btn?.contains(e.target)) {
            panel.style.display = 'none';
            btn?.classList.remove('active');
        }
    });

    // Load saved config on page load
    document.addEventListener('DOMContentLoaded', loadColumnConfig);

    /* ================================================================
       SORT ICON INIT on page load
    ================================================================ */
    (function initSortIcons() {
        const urlParams = new URLSearchParams(window.location.search);
        const sortField = urlParams.get('sort');
        const sortDir   = urlParams.get('dir');
        if (sortField) {
            const icon = document.getElementById('sort-' + sortField);
            if (icon) {
                icon.innerHTML = '<i class="fa fa-sort-' + (sortDir === 'asc' ? 'asc' : 'desc') + '"></i>';
            }
        }
    })();

    /* ================================================================
       COLOR PICKER
    ================================================================ */
    var COLOR_OPTIONS = [
        { label: 'Urgent', value: '#E08283' },
        { label: 'Ready to bill', value: '#F3C200' },
        { label: 'Ready to close', value: '#25A69A' },
        { label: 'Postpone', value: '#4B77BE' },
        { label: 'Freight Finalized', value: '#9B9B9B' },
    ];

    var _colorShipmentId = null;

    function openColorPicker(id, currentColor) {
        _colorShipmentId = id;
        const grid = document.getElementById('color-picker-grid');
        grid.innerHTML = COLOR_OPTIONS.map(o => {
            const active = o.value === currentColor;
            return `<div class="color-picker-opt ${active ? 'active' : ''}" onclick="selectColor('${o.value}', this)"><span class="swatch" style="background:${o.value}"></span><span>${o.label}</span><i class="fa fa-check"></i></div>`;
        }).join('');
        document.getElementById('color-picker-overlay').classList.add('open');
    }

    function selectColor(color, el) {
        document.querySelectorAll('.color-picker-opt').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        const id = _colorShipmentId;
        fetch('{{ route("truck.update-color", "ID") }}'.replace('ID', id), {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ color }),
        }).then(r => r.json()).then(data => {
            if (data.success) {
                const span = document.querySelector(`#shipment-row-${id} .color-mark`);
                if (span) span.style.background = color;
                showToast('success', 'Status color updated');
            }
        }).catch(() => showToast('error', 'Failed to update color'));
        closeColorPicker();
    }

    function closeColorPicker() {
        document.getElementById('color-picker-overlay').classList.remove('open');
        _colorShipmentId = null;
    }

    function clearColor() {
        const id = _colorShipmentId;
        if (!id) return;
        fetch('{{ route("truck.update-color", "ID") }}'.replace('ID', id), {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ color: '' }),
        }).then(r => r.json()).then(data => {
            if (data.success) {
                const span = document.querySelector(`#shipment-row-${id} .color-mark`);
                if (span) span.style.background = '#94a3b8';
                showToast('success', 'Status color cleared');
            }
        }).catch(() => showToast('error', 'Failed to clear color'));
        closeColorPicker();
    }

    /* ================================================================
       TOAST NOTIFICATIONS
    ================================================================ */
    
    /* Page size */
    function changePageSize(size) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', size);
        url.searchParams.delete('page');
        updateGrid(url.toString());
    }

    function showToast(type, msg) {
        const icons = { success: 'check-circle', error: 'times-circle', info: 'info-circle' };
        const t = document.createElement('div');
        t.className = `toast ${type}`;
        t.innerHTML = `<i class="fa fa-${icons[type] || 'info-circle'}"></i> ${msg}`;
        document.getElementById('toast-container').appendChild(t);
        setTimeout(() => t.remove(), 3000);
    }

    @if(session('success'))
        showToast('success', @json(session('success')));
    @endif
    @if(session('error'))
        showToast('error', @json(session('error')));
    @endif
    </script>
    @endpush
</x-layout>
