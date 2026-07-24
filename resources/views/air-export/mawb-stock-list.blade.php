<x-layout>
    @push('styles')
    <x-list-styles />
    @endpush

    {{-- ═══════════════════════ TOAST CONTAINER ═══════════════════════ --}}
    <div class="toast-container" id="toast-container"></div>

    {{-- ═══════════════════════ DELETE CONFIRM MODAL ═══════════════════════ --}}
    <div class="overlay" id="confirm-overlay" onclick="if(event.target===this) closeConfirm()">
        <div class="confirm-box">
            <div class="confirm-icon"><i class="fa fa-exclamation-triangle"></i></div>
            <h4>Delete MAWB Stock?</h4>
            <p id="confirm-msg">This action cannot be undone.</p>
            <div class="confirm-actions">
                <button class="btn-tool" style="padding:0 18px;height:26px;" onclick="closeConfirm()">Cancel</button>
                <button class="btn-tool danger" style="padding:0 18px;height:26px;" onclick="executeDelete()">
                    <i class="fa fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════ COLOR PICKER MODAL ═══════════════════════ --}}
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

    {{-- ═══════════════════════ MAIN PAGE ═══════════════════════ --}}
    <div class="page-content">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
                <li>Air Export <i class="fa fa-angle-right"></i></li>
                <li><span style="color: #333; font-weight: 700;">MAWB Stock List</span></li>
            </ul>
        </div>

        <div class="portlet light">

            {{-- ── PORTLET TITLE ── --}}
            <div class="portlet-title">
                <div class="caption" style="display:flex;align-items:center;gap:8px;">
                    <span class="caption-subject">MAWB Stock List</span>
                    <span id="sel-badge" style="display:none;font-size:10px;color:#3b82f6;font-weight:600;background:#eff6ff;padding:1px 6px;border-radius:10px;border:1px solid #bfdbfe;"></span>
                </div>
                <div class="actions" style="display:flex;gap:4px;position:relative;align-items:center;">
                    <button class="btn-action-round" id="btn-filter" onclick="toggleFilter()" title="Toggle filter row">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                    <div style="position:relative;">
                        <button class="btn-action-round" id="btn-config" onclick="toggleConfig()" title="Column visibility">
                            <i class="fa fa-cogs"></i> Config
                        </button>
                        <div class="config-panel" id="config-panel" style="display:none;">
                            <div class="config-panel-title">Column Visibility</div>
                            <div id="col-toggles"></div>
                        </div>
                    </div>
                    <a class="btn-action-round white" href="/air-export/export-csv" title="Download as CSV/Excel" target="_blank">
                        <i class="fa fa-file-excel-o"></i> Excel
                    </a>
                </div>
            </div>

            {{-- ── TOOLBAR ── --}}
            <div class="portlet-tool">
                <div style="display:flex;gap:10px;align-items:center;">
                    <div class="btn-group">
                        <a class="btn-tool green" href="/air-export/create" title="New MAWB" target="_blank">
                            <i class="fa fa-plus"></i>
                        </a>
                        <button class="btn-tool" id="btn-delete" disabled title="Delete Selected" onclick="confirmDelete()">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="btn-group">
                        <button class="btn-tool" id="btn-block"   disabled style="padding:0 12px;" onclick="blockSelected()">Block</button>
                        <button class="btn-tool" id="btn-unblock" disabled style="padding:0 12px;" onclick="unblockSelected()">Unblock</button>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <i class="fa fa-search" style="font-size:10px;color:#94a3b8;"></i>
                    <input type="text" id="quick-search" class="input-inline" style="width:160px;"
                           placeholder="Quick search..." value="{{ request('search') }}"
                           oninput="quickSearch(this.value)">
                </div>
            </div>

            {{-- ── TABLE ── --}}
            <div class="portlet-body">
                <div class="grid-container">
                    <div class="grid-wrapper">
                        <table class="grid-table" id="main-grid">
                            <thead>
                                {{-- ── HEADER ROW ── --}}
                                <tr id="header-row">
                                    <th class="sticky-col sticky-col-header" data-col="check"   style="width:25px;text-align:center;">
                                        <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" title="Select All">
                                    </th>
                                    <th class="sticky-col sticky-col-header" data-col="color"   style="width:35px;left:25px;text-align:center;">Color</th>
                                    <th class="sticky-col sticky-col-header" data-col="status"  style="width:80px;left:60px;">Status</th>
                                    <th class="sticky-col sticky-col-header" data-col="prefix"  style="width:50px;left:140px;">Prefix</th>
                                    <th class="sticky-col sticky-col-header" data-col="waybill" style="width:110px;left:190px;">Waybill No.</th>
                                    <th data-col="carrier" style="width:180px;">Carrier</th>
                                    <th data-col="file"    style="width:130px;">File No.</th>
                                    <th data-col="office"  style="width:90px;">Office</th>
                                    <th data-col="date"    style="width:130px;">Created Date</th>
                                    <th data-col="actions" style="width:60px;">Actions</th>
                                </tr>

                                {{-- ── FILTER ROW (hidden by default) ── --}}
                                <tr id="filter-row" style="display:none;" class="filter-row">
                                    <td class="sticky-col" style="left:0;"></td>
                                    <td class="sticky-col" style="left:25px;"></td>
                                    <td class="sticky-col" style="left:60px;">
                                        <select class="filter-select" data-col-idx="2" onchange="applyFilters()">
                                            <option value="">All</option>
                                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                            <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                                        </select>
                                    </td>
                                    <td class="sticky-col" style="left:140px;"></td>
                                    <td class="sticky-col" style="left:190px;">
                                        <input class="filter-input" data-col-idx="4" placeholder="Waybill..." onkeyup="if(event.key==='Enter') applyFilters()">
                                    </td>
                                    <td>
                                        <select class="filter-select" data-col-idx="5" onchange="applyFilters()">
                                            <option value="">All Carriers</option>
                                            @foreach($carriers as $c)
                                                <option value="{{ $c->id }}" {{ request('carrier_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input class="filter-input" data-col-idx="6" placeholder="File No..." onkeyup="if(event.key==='Enter') applyFilters()"></td>
                                    <td>
                                        <select class="filter-select" data-col-idx="7" onchange="applyFilters()">
                                            <option value="">All Offices</option>
                                            @foreach($offices as $o)
                                                <option value="{{ $o->id }}" {{ request('office_id') == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td colspan="2">
                                        <button class="btn-tool green" onclick="applyFilters()" style="height:18px;padding:0 6px;font-size:9px;">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <a href="{{ url()->current() }}" class="btn-tool" style="height:18px;padding:0 6px;font-size:9px;" target="_blank">
                                            <i class="fa fa-undo"></i>
                                        </a>
                                    </td>
                                </tr>
                            </thead>

                            <tbody id="grid-body">
                                @forelse($stocks as $stock)
                                @php
                                    $isBlocked = $stock->is_blocked ?? false;
                                    $isAssigned = !$isBlocked && !empty($stock->file_no);
                                    $isAvailable = !$isBlocked && empty($stock->file_no);
                                    if ($isBlocked) { $badgeClass = 'bg-red'; $statusLabel = 'BLOCKED'; }
                                    elseif ($isAssigned) { $badgeClass = 'bg-orange'; $statusLabel = 'ASSIGNED'; }
                                    else { $badgeClass = 'bg-green'; $statusLabel = 'AVAILABLE'; }
                                @endphp
                                <tr id="stock-row-{{ $stock->id }}"
                                    data-id="{{ $stock->id }}"
                                    data-file="{{ $stock->file_no ?? '' }}"
                                    onclick="rowClick(event, this)">
                                    <td class="sticky-col" style="width:25px;text-align:center;" onclick="event.stopPropagation()">
                                        <input type="checkbox" name="ids[]" value="{{ $stock->id }}" class="row-check" onchange="updateToolbar()">
                                    </td>
                                    <td class="sticky-col" style="width:35px;left:25px;text-align:center;">
                                        <span class="color-mark" style="background:{{ $stock->color ?? '#94a3b8' }}" title="Click to change color" onclick="event.stopPropagation();openColorPicker({{ $stock->id }}, '{{ $stock->color ?? '' }}')"></span>
                                    </td>
                                    <td class="sticky-col" style="width:80px;left:60px;">
                                        <span class="badge-status {{ $badgeClass }}">{{ $statusLabel }}</span>
                                    </td>
                                    <td class="sticky-col" style="width:50px;left:140px;">{{ $stock->mawb_no ? substr($stock->mawb_no, 0, 3) : '---' }}</td>
                                    <td class="sticky-col" style="width:110px;left:190px;font-weight:600;">
                                        <a href="/air-export/{{ $stock->id }}/edit" class="col-link">{{ $stock->mawb_no ? substr($stock->mawb_no, 4) : '--' }}</a>
                                    </td>
                                    <td>{{ $stock->carrier->name ?? '--' }}</td>
                                    <td><a href="/air-export/{{ $stock->id }}/edit" class="col-link">{{ $stock->file_no ?? '--' }}</a></td>
                                    <td>{{ $stock->office->name ?? '-' }}</td>
                                    <td>{{ $stock->created_at ? $stock->created_at->format('m-d-Y H:i') : '--' }}</td>
                                    <td onclick="event.stopPropagation()">
                                        <i class="fa fa-copy action-icon" title="Copy" onclick="copyRow({{ $stock->id }})"></i>
                                        <i class="fa fa-trash-o action-icon danger" title="Delete" onclick="deleteRow({{ $stock->id }})"></i>
                                    </td>
                                </tr>
                                @empty
                                <tr id="empty-row">
                                    <td colspan="10" style="text-align:center;padding:30px 10px;color:#94a3b8;">
                                        <i class="fa fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                                        No MAWB stocks found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ── PAGINATION ── --}}
            <div class="portlet-tool bottom">
                <div style="display:flex;justify-content:space-between;width:100%;align-items:center;">
                    <div id="pagination-container">{{ $stocks->links() }}</div>
                    <div style="font-size:10px;color:#64748b;">
                        Showing <span id="stat-first">{{ $stocks->firstItem() ?? 0 }}</span> – <span id="stat-last">{{ $stocks->lastItem() ?? 0 }}</span> of <span id="stat-total">{{ $stocks->total() }}</span> records
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    var CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    var searchDebounce;
    var filterDebounce;
    var _deleteId = null;
    var _colorId = null;

    /* ---------- AJAX Grid ---------- */
    async function updateGrid(url) {
        try {
            var resp = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!resp.ok) { showToast('error', 'Failed to load data'); return; }
            var html = await resp.text();
            var parser = new DOMParser();
            var doc = parser.parseFromString(html, 'text/html');
            var newBody = doc.getElementById('grid-body');
            var newPagination = doc.getElementById('pagination-container');
            if (newBody) document.getElementById('grid-body').innerHTML = newBody.innerHTML;
            if (newPagination) document.getElementById('pagination-container').innerHTML = newPagination.innerHTML;
            var stats = doc.querySelector('.portlet-tool.bottom div:last-child');
            if (stats) {
                var text = stats.textContent;
                var matches = text.match(/\d+/g);
                if (matches && matches.length >= 3) {
                    document.getElementById('stat-first').textContent = matches[0];
                    document.getElementById('stat-last').textContent = matches[1];
                    document.getElementById('stat-total').textContent = matches[2];
                }
            }
            bindPaginationClicks();
            updateToolbar();
        } catch(e) {
            showToast('error', 'Search failed: ' + e.message);
        }
    }

    function bindPaginationClicks() {
        document.querySelectorAll('.pagination a').forEach(function(link) {
            link.removeEventListener('click', paginationHandler);
            link.addEventListener('click', paginationHandler);
        });
    }

    function paginationHandler(e) {
        e.preventDefault();
        updateGrid(this.href);
        var qs = document.getElementById('quick-search');
        if (qs) {
            var url = new URL(this.href);
            if (qs.value) url.searchParams.set('search', qs.value);
            window.history.replaceState({}, '', url.toString());
        }
    }

    function quickSearch(val) {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(function() {
            var url = new URL(window.location.href);
            url.searchParams.set('search', val);
            url.searchParams.delete('page');
            window.history.replaceState({}, '', url.toString());
            updateGrid(url.toString());
        }, 300);
    }

    bindPaginationClicks();

    /* ---------- Toast ---------- */
    function showToast(type, msg) {
        var icons = { success: 'check-circle', error: 'times-circle', info: 'info-circle' };
        var t = document.createElement('div');
        t.className = 'toast ' + type;
        t.innerHTML = '<i class="fa fa-' + (icons[type] || 'info-circle') + '"></i> <span>' + msg + '</span>';
        document.getElementById('toast-container').appendChild(t);
        setTimeout(function() { t.remove(); }, 3000);
    }

    /* ---------- Checkbox / Bulk Toolbar ---------- */
    function updateToolbar() {
        var checked = document.querySelectorAll('.row-check:checked');
        var all = document.querySelectorAll('.row-check');
        var n = checked.length;
        var sa = document.getElementById('select-all');
        if (sa) { sa.checked = n === all.length && all.length > 0; sa.indeterminate = n > 0 && n < all.length; }
        document.getElementById('btn-delete').disabled = n === 0;
        document.getElementById('btn-block').disabled = n === 0;
        document.getElementById('btn-unblock').disabled = n === 0;
        var badge = document.getElementById('sel-badge');
        badge.style.display = n > 0 ? 'inline' : 'none';
        badge.textContent = n + ' selected';
        document.querySelectorAll('#grid-body tr[data-id]').forEach(function(row) {
            var cb = row.querySelector('.row-check');
            row.classList.toggle('row-selected', cb && cb.checked);
        });
    }

    function toggleSelectAll(el) {
        document.querySelectorAll('.row-check').forEach(function(cb) { cb.checked = el.checked; });
        updateToolbar();
    }

    function rowClick(e, row) {
        var skip = ['A', 'INPUT', 'BUTTON', 'I', 'SELECT'];
        if (skip.includes(e.target.tagName)) return;
        var cb = row.querySelector('.row-check');
        if (cb) { cb.checked = !cb.checked; updateToolbar(); }
    }

    function getSelectedIds() {
        return Array.from(document.querySelectorAll('.row-check:checked')).map(function(cb) { return cb.value; });
    }

    /* ---------- Filter ---------- */
    function toggleFilter() {
        var row = document.getElementById('filter-row');
        var btn = document.getElementById('btn-filter');
        var isVisible = row.style.display === 'table-row';
        row.style.display = isVisible ? 'none' : 'table-row';
        btn.classList.toggle('active', !isVisible);
        if (!isVisible) {
            // Load current request params into filter inputs
            var params = new URLSearchParams(window.location.search);
            var selects = row.querySelectorAll('select.filter-select');
            selects.forEach(function(sel) {
                var idx = parseInt(sel.dataset.colIdx);
                if (idx === 2) sel.value = params.get('status') || '';
                else if (idx === 5) sel.value = params.get('carrier_id') || '';
                else if (idx === 7) sel.value = params.get('office_id') || '';
            });
            var inputs = row.querySelectorAll('input.filter-input');
            inputs.forEach(function(inp) {
                var idx = parseInt(inp.dataset.colIdx);
                if (idx === 4) inp.value = params.get('search') || '';
                else if (idx === 6) inp.value = params.get('search') || '';
            });
        } else {
            document.querySelectorAll('#filter-row input').forEach(function(i) { i.value = ''; });
            document.querySelectorAll('#filter-row select').forEach(function(s) { s.value = ''; });
            applyFilters();
        }
    }

    function applyFilters() {
        clearTimeout(filterDebounce);
        filterDebounce = setTimeout(function() {
            var url = new URL(window.location.href);
            url.search = '';
            // Collect filter values
            var search = document.getElementById('quick-search')?.value || '';
            if (search) url.searchParams.set('search', search);
            var status = document.querySelector('#filter-row select[data-col-idx="2"]')?.value || '';
            if (status) url.searchParams.set('status', status);
            var carrier = document.querySelector('#filter-row select[data-col-idx="5"]')?.value || '';
            if (carrier) url.searchParams.set('carrier_id', carrier);
            var office = document.querySelector('#filter-row select[data-col-idx="7"]')?.value || '';
            if (office) url.searchParams.set('office_id', office);
            window.history.replaceState({}, '', url.toString());
            updateGrid(url.toString());
        }, 300);
    }

    /* ---------- Config ---------- */
    var PINNED_COLS = ['check', 'color', 'status', 'prefix', 'waybill'];

    function toggleConfig() {
        var panel = document.getElementById('config-panel');
        var btn = document.getElementById('btn-config');
        var open = panel.style.display !== 'none';
        panel.style.display = open ? 'none' : 'block';
        btn.classList.toggle('active', !open);
        if (!open) buildConfigPanel();
    }

    function buildConfigPanel() {
        var container = document.getElementById('col-toggles');
        container.innerHTML = '';
        document.querySelectorAll('#header-row th[data-col]').forEach(function(th) {
            if (PINNED_COLS.includes(th.dataset.col)) return;
            var label = document.createElement('label');
            var cb = document.createElement('input');
            cb.type = 'checkbox';
            cb.checked = th.style.display !== 'none';
            cb.onchange = function() { toggleColumn(th.dataset.col, cb.checked); };
            label.appendChild(cb);
            label.append(' ' + th.textContent.trim());
            container.appendChild(label);
        });
    }

    function toggleColumn(colName, show) {
        var th = document.querySelector('#header-row th[data-col="' + colName + '"]');
        if (!th) return;
        var idx = Array.from(th.parentElement.children).indexOf(th);
        th.style.display = show ? '' : 'none';
        document.querySelectorAll('#grid-body tr, #filter-row').forEach(function(row) {
            var cell = row.querySelectorAll('td, th')[idx];
            if (cell) cell.style.display = show ? '' : 'none';
        });
    }

    // Close config on outside click
    document.addEventListener('click', function(e) {
        var panel = document.getElementById('config-panel');
        var btn = document.getElementById('btn-config');
        if (panel.style.display !== 'none' && !panel.contains(e.target) && !btn.contains(e.target)) {
            panel.style.display = 'none';
            btn.classList.remove('active');
        }
    });

    /* ---------- Color Picker ---------- */
    var COLOR_OPTIONS = [
        { label: 'Urgent', value: '#E08283' },
        { label: 'Ready to bill', value: '#F3C200' },
        { label: 'Ready to close', value: '#25A69A' },
        { label: 'Postpone', value: '#4B77BE' },
        { label: 'Freight Finalized', value: '#9B9B9B' }
    ];

    function openColorPicker(id, cur) {
        _colorId = id;
        var grid = document.getElementById('color-picker-grid');
        grid.innerHTML = COLOR_OPTIONS.map(function(o) {
            var active = o.value === cur;
            return '<div class="color-picker-opt ' + (active ? 'active' : '') + '" onclick="selectColor(\'' + o.value + '\',this)"><span class="swatch" style="background:' + o.value + '"></span><span>' + o.label + '</span><i class="fa fa-check"></i></div>';
        }).join('');
        document.getElementById('color-picker-overlay').classList.add('open');
    }

    function selectColor(color, el) {
        document.querySelectorAll('.color-picker-opt').forEach(function(c) { c.classList.remove('active'); });
        el.classList.add('active');
        var id = _colorId;
        fetch('/air-export/' + id + '/color', {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ color: color })
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.success) {
                showToast('success', 'Color updated');
                var dot = document.querySelector('#stock-row-' + id + ' .color-mark');
                if (dot) dot.style.background = color;
            }
        })
        .catch(function() { showToast('error', 'Color update failed'); });
        closeColorPicker();
    }

    function closeColorPicker() {
        document.getElementById('color-picker-overlay').classList.remove('open');
        _colorId = null;
    }

    function clearColor() {
        if (!_colorId) return;
        var id = _colorId;
        fetch('/air-export/' + id + '/color', {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ color: '' })
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.success) {
                showToast('success', 'Color cleared');
                var dot = document.querySelector('#stock-row-' + id + ' .color-mark');
                if (dot) dot.style.background = '#94a3b8';
            }
        })
        .catch(function() { showToast('error', 'Failed to clear'); });
        closeColorPicker();
    }

    /* ---------- Delete ---------- */
    function deleteRow(id) {
        _deleteId = id;
        document.getElementById('confirm-msg').textContent = 'Delete MAWB stock? This action cannot be undone.';
        document.getElementById('confirm-overlay').classList.add('open');
    }

    function closeConfirm() {
        document.getElementById('confirm-overlay').classList.remove('open');
        _deleteId = null;
    }

    function executeDelete() {
        var id = _deleteId;
        var ids = getSelectedIds();
        closeConfirm();
        // Single row delete
        if (id) {
            fetch('/air-export/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF }
            })
            .then(function(r) {
                if (r.redirected) { window.location.href = r.url; return; }
                return r.json();
            })
            .then(function(d) {
                if (!d || d.success !== false) {
                    showToast('success', 'MAWB stock deleted.');
                    var row = document.getElementById('stock-row-' + id);
                    if (row) row.remove();
                    updateToolbar();
                }
            })
            .catch(function() { window.location.reload(); });
        // Bulk delete
        } else if (ids.length > 0) {
            fetch('/air-export/bulk-delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ ids: ids })
            })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (d.success) {
                    showToast('success', d.message || 'Items deleted.');
                    updateGrid(window.location.href);
                } else {
                    showToast('error', d.message || 'Delete failed.');
                }
            })
            .catch(function() { showToast('error', 'Delete request failed.'); });
        }
    }

    function confirmDelete() {
        var n = document.querySelectorAll('.row-check:checked').length;
        if (!n) return;
        document.getElementById('confirm-msg').textContent = 'You are about to permanently delete ' + n + ' MAWB stock(s). This cannot be undone.';
        document.getElementById('confirm-overlay').classList.add('open');
    }

    /* ---------- Bulk Delete / Block / Unblock ---------- */
    function bulkAction(url, successMsg) {
        var ids = getSelectedIds();
        if (ids.length === 0) { showToast('error', 'No items selected'); return; }
        if (!confirm('Are you sure you want to perform this action on ' + ids.length + ' item(s)?')) return;
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ ids: ids })
        })
        .then(function(r) {
            if (!r.ok) { throw new Error('Server returned ' + r.status); }
            return r.json();
        })
        .then(function(d) {
            if (d.success) {
                showToast('success', successMsg || d.message || 'Action completed.');
                updateGrid(window.location.href);
            } else {
                showToast('error', d.message || 'Action failed.');
            }
        })
        .catch(function(e) { showToast('error', 'Request failed: ' + e.message); });
    }

    function blockSelected() { bulkAction('/air-export/bulk-block', 'Items blocked.'); }
    function unblockSelected() { bulkAction('/air-export/bulk-unblock', 'Items unblocked.'); }

    /* ---------- Copy Row ---------- */
    function copyRow(id) {
        showToast('info', 'Copying to new shipment...');
        setTimeout(function() {
            window.location.href = '/air-export/create?copy=' + id;
        }, 400);
    }

    /* ---------- Session flash toasts ---------- */
    @if(session('success')) showToast('success', @json(session('success'))); @endif
    @if(session('error')) showToast('error', @json(session('error'))); @endif
    </script>
    @endpush
</x-layout>
