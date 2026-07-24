<x-layout>
    @push('styles')
    <x-list-styles />
    @endpush

    {{-- TOAST CONTAINER --}}
    <div class="toast-container" id="toast-container"></div>

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
                <li><span style="color: #333; font-weight: 700;">Shipping List</span></li>
            </ul>
        </div>

        <div class="portlet light">
            {{-- PORTLET TITLE --}}
            <div class="portlet-title">
                <div class="caption" style="display:flex;align-items:center;gap:8px;">
                    <span class="caption-subject">Warehouse Shipping List</span>
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
                    <button class="btn-action-round" onclick="window.print()" title="Print"><i class="fa fa-print"></i></button>
                    <a class="btn-action-round white" href="{{ route('shipping.export-csv') }}" title="Download as CSV/Excel" target="_blank">
                        <i class="fa fa-file-excel-o"></i> Excel <i class="fa fa-angle-down"></i>
                    </a>
                </div>
            </div>

            {{-- TOOLBAR --}}
            <div class="portlet-tool">
                <div style="display:flex;gap:10px;align-items:center;">
                    <div class="btn-group">
                        <a class="btn-tool green" href="{{ route('shipping.create') }}" title="New Shipping" target="_blank">
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
                    @if(request()->has('search'))
                        <a href="{{ route('shipping.index') }}" style="font-size:10px;color:#3b82f6;text-decoration:none;" target="_blank">
                            <i class="fa fa-times-circle"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- TABLE --}}
            <form id="bulk-form" method="POST" action="{{ route('shipping.bulk-delete') }}" style="margin:0;">
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
                                        <th class="sticky-col sticky-col-header" data-col="color"   style="width:30px;left:25px;text-align:center;">Color</th>
                                        <th class="sticky-col sticky-col-header" data-col="file_no" style="width:130px;left:55px;">
                                            <a href="javascript:;" onclick="toggleSort('shipping_no')" style="color:inherit;text-decoration:none;">Shipping No. <i class="fa fa-sort" id="sort-shipping_no"></i></a>
                                        </th>
                                        <th class="sticky-col sticky-col-header" data-col="customer" style="width:150px;left:185px;">Customer</th>
                                        <th data-col="office"       style="width:80px;">Office</th>
                                        <th data-col="shipping_date" style="width:90px;">
                                            <a href="javascript:;" onclick="toggleSort('shipping_date')" style="color:inherit;text-decoration:none;">Post Date <i class="fa fa-sort" id="sort-shipping_date"></i></a>
                                        </th>
                                        <th data-col="out_date"     style="width:90px;">
                                            <a href="javascript:;" onclick="toggleSort('out_date')" style="color:inherit;text-decoration:none;">Out Date <i class="fa fa-sort" id="sort-out_date"></i></a>
                                        </th>
                                        <th data-col="order_date"   style="width:90px;">
                                            <a href="javascript:;" onclick="toggleSort('order_date')" style="color:inherit;text-decoration:none;">Order Date <i class="fa fa-sort" id="sort-order_date"></i></a>
                                        </th>
                                        <th data-col="order_no"     style="width:100px;">Order No.</th>
                                        <th data-col="truck_bl_no"  style="width:120px;">Truck B/L</th>
                                        <th data-col="warehouse"    style="width:150px;">Warehouse</th>
                                        <th data-col="ship_to"      style="width:150px;">Ship To</th>
                                        <th data-col="trucker"      style="width:130px;">Trucker</th>
                                        <th data-col="pallet"       style="width:100px;">Pallet</th>
                                        <th data-col="status"       style="width:100px;">
                                            <a href="javascript:;" onclick="toggleSort('status')" style="color:inherit;text-decoration:none;">Status <i class="fa fa-sort" id="sort-status"></i></a>
                                        </th>
                                        <th data-col="operator"     style="width:100px;">OP</th>
                                        <th data-col="created_at"   style="width:90px;">
                                            <a href="javascript:;" onclick="toggleSort('created_at')" style="color:inherit;text-decoration:none;">Created <i class="fa fa-sort" id="sort-created_at"></i></a>
                                        </th>
                                    </tr>

                                    {{-- FILTER ROW --}}
                                    <tr id="filter-row" style="display:none;">
                                        <td class="sticky-col" style="left:0;"></td>
                                        <td class="sticky-col" style="left:25px;"></td>
                                        <td class="sticky-col" style="left:55px;"><input class="filter-input" data-col-idx="2" placeholder="Shipping No..." oninput="applyFilters()"></td>
                                        <td class="sticky-col" style="left:185px;"><input class="filter-input" data-col-idx="3" placeholder="Customer..." oninput="applyFilters()"></td>
                                        <td><input class="filter-input" data-col-idx="4" placeholder="Office..." oninput="applyFilters()"></td>
                                        <td><input class="filter-input" data-col-idx="5" placeholder="Post Date..." oninput="applyFilters()"></td>
                                        <td><input class="filter-input" data-col-idx="6" placeholder="Out Date..." oninput="applyFilters()"></td>
                                        <td><input class="filter-input" data-col-idx="7" placeholder="Order Date..." oninput="applyFilters()"></td>
                                        <td><input class="filter-input" data-col-idx="8" placeholder="Order No..." oninput="applyFilters()"></td>
                                        <td><input class="filter-input" data-col-idx="9" placeholder="Truck B/L..." oninput="applyFilters()"></td>
                                        <td><input class="filter-input" data-col-idx="10" placeholder="Warehouse..." oninput="applyFilters()"></td>
                                        <td><input class="filter-input" data-col-idx="11" placeholder="Ship To..." oninput="applyFilters()"></td>
                                        <td><input class="filter-input" data-col-idx="12" placeholder="Trucker..." oninput="applyFilters()"></td>
                                        <td><input class="filter-input" data-col-idx="13" placeholder="Pallet..." oninput="applyFilters()"></td>
                                        <td><input class="filter-input" data-col-idx="14" placeholder="Status..." oninput="applyFilters()"></td>
                                        <td colspan="2"></td>
                                    </tr>
                                </thead>

                                <tbody id="grid-body">
                                @forelse($shippings as $shipping)
                                    <tr
                                        id="row-{{ $shipping->id }}"
                                        data-id="{{ $shipping->id }}"
                                        onclick="rowClick(event, this)"
                                    >
                                        {{-- Checkbox --}}
                                        <td class="sticky-col" style="width:25px;text-align:center;" onclick="event.stopPropagation()">
                                            <input type="checkbox" name="ids[]" value="{{ $shipping->id }}" class="row-check" onchange="updateToolbar()">
                                        </td>
                                        {{-- Color --}}
                                        <td class="sticky-col" style="width:30px;left:25px;text-align:center;">
                                            <span class="color-mark" style="background:{{ $shipping->color ?? '#94a3b8' }}" title="Click to change status color" onclick="event.stopPropagation();openColorPicker({{ $shipping->id }}, '{{ $shipping->color ?? '' }}')"></span>
                                        </td>
                                        {{-- Shipping No. --}}
                                        <td class="sticky-col" style="width:130px;left:55px;" onclick="event.stopPropagation()">
                                            <a href="{{ route('shipping.edit', $shipping->id) }}" class="col-link">{{ $shipping->shipping_no }}</a>
                                        </td>
                                        {{-- Customer --}}
                                        <td class="sticky-col" style="width:150px;left:185px;">
                                            {{ $shipping->customer->name ?? '' }}
                                        </td>
                                        {{-- Office --}}
                                        <td>{{ $shipping->office->code ?? '' }}</td>
                                        {{-- Post Date --}}
                                        <td>{{ $shipping->shipping_date ? $shipping->shipping_date->format('m-d-Y') : '' }}</td>
                                        {{-- Out Date --}}
                                        <td>{{ $shipping->out_date ? $shipping->out_date->format('m-d-Y') : '' }}</td>
                                        {{-- Order Date --}}
                                        <td>{{ $shipping->order_date ? $shipping->order_date->format('m-d-Y') : '' }}</td>
                                        {{-- Order No. --}}
                                        <td>{{ $shipping->order_no }}</td>
                                        {{-- Truck B/L --}}
                                        <td>{{ $shipping->truck_bl_no }}</td>
                                        {{-- Warehouse --}}
                                        <td>{{ $shipping->warehouse->name ?? '' }}</td>
                                        {{-- Ship To --}}
                                        <td>{{ $shipping->shipTo->name ?? '' }}</td>
                                        {{-- Trucker --}}
                                        <td>{{ $shipping->trucker->name ?? '' }}</td>
                                        {{-- Pallet --}}
                                        <td>{{ $shipping->pallet }}</td>
                                        {{-- Status --}}
                                        <td>
                                            @if($shipping->status)
                                                <span class="badge-status {{ $shipping->status === 'Shipped' ? 'bg-green' : ($shipping->status === 'Delivered' ? 'bg-green' : 'bg-blue') }}">{{ $shipping->status }}</span>
                                            @endif
                                        </td>
                                        {{-- Operator --}}
                                        <td>{{ $shipping->operator->name ?? '' }}</td>
                                        {{-- Created At --}}
                                        <td>{{ $shipping->created_at->format('m-d-Y') }}</td>
                                    </tr>
                                @empty
                                    <tr id="empty-row">
                                        <td colspan="50" style="text-align:center;padding:30px 10px;color:#94a3b8;">
                                            <i class="fa fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                                            No shipping records found.
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
                    <div id="pagination-container">{{ $shippings->links() }}</div>
                    <div style="font-size:10px;color:#64748b;">
                        Showing <span id="stat-first">{{ $shippings->firstItem() ?? 0 }}</span> – <span id="stat-last">{{ $shippings->lastItem() ?? 0 }}</span> of <span id="stat-total">{{ $shippings->total() }}</span> records
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    /* ================================================================
       TOOLBAR — checkbox management
    ================================================================ */
    function updateToolbar() {
        const checked  = [...document.querySelectorAll('.row-check:checked')];
        const all      = [...document.querySelectorAll('.row-check')];
        const n        = checked.length;
        const sa       = document.getElementById('select-all');
        sa.checked        = n === all.length && all.length > 0;
        sa.indeterminate  = n > 0 && n < all.length;

        document.getElementById('btn-delete').disabled  = n === 0;

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
            `You are about to permanently delete ${n} record(s). This cannot be undone.`;
        document.getElementById('confirm-overlay').classList.add('open');
    }
    function closeConfirm() {
        document.getElementById('confirm-overlay').classList.remove('open');
    }
    function executeDelete() {
        closeConfirm();
        const ids = getSelectedIds();
        if (!ids.length) return;

        showToast('info', 'Deleting...');
        fetch('{{ route("shipping.bulk-delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids }),
        }).then(r => r.json()).then(data => {
            if (data.success) {
                showToast('success', data.message || 'Deleted successfully');
                updateGrid(window.location.href);
            } else {
                showToast('error', data.message || 'Failed to delete');
            }
        }).catch(() => showToast('error', 'Failed to delete'));
    }

    /* ================================================================
       FILTER ROW TOGGLE
    ================================================================ */
    let filterOpen = false;
    function toggleFilter() {
        filterOpen = !filterOpen;
        const row = document.getElementById('filter-row');
        if (row) { row.style.display = filterOpen ? 'table-row' : 'none'; }
        document.getElementById('btn-filter').classList.toggle('active', filterOpen);

        if (filterOpen) {
            // Load filters from URL params
            const urlParams = new URLSearchParams(window.location.search);
            document.querySelectorAll('#filter-row .filter-input').forEach(inp => {
                const idx = parseInt(inp.dataset.colIdx);
                const filterMap = {
                    2: 'filter_shipping_no', 3: 'filter_customer', 4: 'filter_office',
                    5: 'filter_shipping_date', 6: 'filter_out_date', 7: 'filter_order_date',
                    8: 'filter_order_no', 9: 'filter_truck_bl_no', 10: 'filter_warehouse',
                    11: 'filter_ship_to', 12: 'filter_trucker', 13: 'filter_pallet',
                    14: 'filter_status'
                };
                inp.value = urlParams.get(filterMap[idx]) || '';
            });
            document.querySelector('.filter-input')?.focus();
        } else {
            // Clear filters when closing
            document.querySelectorAll('#filter-row .filter-input').forEach(i => { i.value = ''; });
            applyFilters();
        }
    }

    /* ================================================================
        AJAX GRID UPDATE
    ================================================================ */
    async function updateGrid(url) {
        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) throw new Error('Network response was not ok');
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newBody = doc.getElementById('grid-body');
            const newPagination = doc.getElementById('pagination-container');

            if (newBody) document.getElementById('grid-body').innerHTML = newBody.innerHTML;
            if (newPagination) document.getElementById('pagination-container').innerHTML = newPagination.innerHTML;

            const stats = doc.querySelector('.portlet-tool.bottom div:last-child');
            if (stats) {
                const text = stats.textContent;
                const matches = text.match(/\d+/g);
                if (matches && matches.length >= 3) {
                    document.getElementById('stat-first').textContent = matches[0];
                    document.getElementById('stat-last').textContent = matches[1];
                    document.getElementById('stat-total').textContent = matches[2];
                }
            }

            // Update sort icons
            document.querySelectorAll('[id^="sort-"]').forEach(i => i.className = 'fa fa-sort');
            const sortIcon = document.getElementById('sort-' + currentSort);
            if (sortIcon) {
                sortIcon.className = 'fa ' + (currentDir === 'asc' ? 'fa-sort-asc' : 'fa-sort-desc');
            }

            // Update URL in address bar
            window.history.pushState({}, '', url);

            updateToolbar();
            loadColumnConfig();
        } catch (e) {
            console.error(e);
            showToast('error', 'Failed to update grid');
        }
    }

    // Wire pagination links to use AJAX instead of full page loads
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            updateGrid(link.href);
        }
    });

    function getSelectedIds() {
        return [...document.querySelectorAll('.row-check:checked')].map(cb => cb.value);
    }

    let currentSort = '{{ request('sort', 'created_at') }}';
    let currentDir = '{{ request('dir', 'desc') }}';

    function toggleSort(field) {
        if (currentSort === field) {
            currentDir = currentDir === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort = field;
            currentDir = 'asc';
        }
        const url = new URL(window.location.href);
        url.searchParams.set('sort', currentSort);
        url.searchParams.set('dir', currentDir);
        url.searchParams.delete('page');
        updateGrid(url.toString());
    }

    function refreshGrid() {
        updateGrid(window.location.href);
    }

    let searchDebounce;
    function quickSearch(val) {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => {
            const q = val.trim();
            const url = new URL(window.location.href);
            if (!q) url.searchParams.delete('search'); else url.searchParams.set('search', q);
            updateGrid(url.toString());
        }, 300);
    }

    let filterDebounce;
    function applyFilters() {
        clearTimeout(filterDebounce);
        filterDebounce = setTimeout(() => {
            const inputs = [...document.querySelectorAll('#filter-row .filter-input')];
            const url = new URL(window.location.href);

            // Preserve search param
            const search = url.searchParams.get('search') || '';
            url.search = '';
            if (search) url.searchParams.set('search', search);

            const filterMap = {
                2: 'filter_shipping_no', 3: 'filter_customer', 4: 'filter_office',
                5: 'filter_shipping_date', 6: 'filter_out_date', 7: 'filter_order_date',
                8: 'filter_order_no', 9: 'filter_truck_bl_no', 10: 'filter_warehouse',
                11: 'filter_ship_to', 12: 'filter_trucker', 13: 'filter_pallet',
                14: 'filter_status'
            };

            inputs.forEach(inp => {
                const v = inp.value.trim();
                if (!v) return;
                const param = filterMap[inp.dataset.colIdx];
                if (param) url.searchParams.set(param, v);
            });

            updateGrid(url.toString());
        }, 300);
    }

    /* ================================================================
       CONFIG PANEL — column visibility
    ================================================================ */
    const PINNED_COLS = ['check', 'color', 'file_no', 'customer'];
    const CONFIG_STORAGE_KEY = 'warehouse_shipping_column_config';

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
        const hidden = getHiddenColumns();
        document.querySelectorAll('#header-row th[data-col]').forEach(th => {
            if (PINNED_COLS.includes(th.dataset.col)) return;
            const label = document.createElement('label');
            const cb    = document.createElement('input');
            cb.type    = 'checkbox';
            cb.checked = !hidden.includes(th.dataset.col);
            cb.onchange = () => toggleColumn(th.dataset.col, cb.checked);
            label.appendChild(cb);
            label.append(' ' + th.textContent.trim());
            container.appendChild(label);
        });
    }

    function getHiddenColumns() {
        const hidden = [];
        document.querySelectorAll('#header-row th[data-col]').forEach(th => {
            if (PINNED_COLS.includes(th.dataset.col)) return;
            if (th.style.display === 'none') hidden.push(th.dataset.col);
        });
        return hidden;
    }

    function saveColumnConfig() {
        try {
            localStorage.setItem(CONFIG_STORAGE_KEY, JSON.stringify(getHiddenColumns()));
        } catch (e) { /* ignore */ }
    }

    function toggleColumn(colName, show) {
        const th  = document.querySelector(`#header-row th[data-col="${colName}"]`);
        const idx = [...th.parentElement.children].indexOf(th);
        th.style.display = show ? '' : 'none';
        document.querySelectorAll('#grid-body tr, #filter-row').forEach(row => {
            const cell = row.querySelectorAll('td, th')[idx];
            if (cell) cell.style.display = show ? '' : 'none';
        });
        saveColumnConfig();
    }

    // Close config on outside click
    document.addEventListener('click', e => {
        const panel = document.getElementById('config-panel');
        const btn   = document.getElementById('btn-config');
        if (panel.style.display !== 'none' && !panel.contains(e.target) && !btn.contains(e.target)) {
            panel.style.display = 'none';
            btn.classList.remove('active');
        }
    });

    // Init sort icons + column config on page load
    (function() {
        const params = new URLSearchParams(window.location.search);
        const sort = params.get('sort') || 'created_at';
        const dir = params.get('dir') || 'desc';
        currentSort = sort;
        currentDir = dir;
        const icon = document.getElementById('sort-' + sort);
        if (icon) { icon.className = 'fa ' + (dir === 'asc' ? 'fa-sort-asc' : 'fa-sort-desc'); }
        loadColumnConfig();
    })();

    /* ================================================================
       COLOR PICKER
    ================================================================ */
    const COLOR_OPTIONS = [
        { label: 'Urgent', value: '#E08283' },
        { label: 'Ready to bill', value: '#F3C200' },
        { label: 'Ready to close', value: '#25A69A' },
        { label: 'Postpone', value: '#4B77BE' },
        { label: 'Freight Finalized', value: '#9B9B9B' },
    ];

    let _colorShippingId = null;

    function openColorPicker(id, currentColor) {
        _colorShippingId = id;
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
        const id = _colorShippingId;
        fetch('{{ route("shipping.update-color", "ID") }}'.replace('ID', id), {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify({ color }),
        }).then(r => r.json()).then(data => {
            if (data.success) {
                const span = document.querySelector(`#row-${id} .color-mark`);
                if (span) span.style.background = color;
                showToast('success', 'Status color updated');
            }
        }).catch(() => showToast('error', 'Failed to update color'));
        closeColorPicker();
    }

    function closeColorPicker() {
        document.getElementById('color-picker-overlay').classList.remove('open');
        _colorShippingId = null;
    }

    function clearColor() {
        const id = _colorShippingId;
        fetch('{{ route("shipping.update-color", "ID") }}'.replace('ID', id), {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify({ color: '' }),
        }).then(r => r.json()).then(data => {
            if (data.success) {
                const span = document.querySelector(`#row-${id} .color-mark`);
                if (span) span.style.background = '#94a3b8';
                showToast('success', 'Status color cleared');
            }
        }).catch(() => showToast('error', 'Failed to clear color'));
        closeColorPicker();
    }

    /* ================================================================
       TOAST NOTIFICATIONS
    ================================================================ */
    function showToast(type, msg) {
        const icons = { success: 'check-circle', error: 'times-circle', info: 'info-circle' };
        const t = document.createElement('div');
        t.className = `toast ${type}`;
        t.innerHTML = `<i class="fa fa-${icons[type] || 'info-circle'}"></i> <span>${msg}</span>`;
        document.getElementById('toast-container').appendChild(t);
        setTimeout(() => t.remove(), 4000);
    }

    /* ================================================================
       FLASH MESSAGE FROM SERVER
    ================================================================ */
    @if(session('success'))
        showToast('success', @json(session('success')));
    @endif
    @if(session('error'))
        showToast('error', @json(session('error')));
    @endif
    </script>
    @endpush
</x-layout>
