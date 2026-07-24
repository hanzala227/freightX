<x-layout>
    @push('styles')
    <x-list-styles />
    <style>
        .grid-table th.sticky-col, .grid-table td.sticky-col { z-index: 2; }
        .grid-table th.sticky-col-header { z-index: 3; }
        .status-badge-grid {
            display: inline-flex; align-items: center; gap: 3px;
            padding: 0 8px 0 6px; border-radius: 10px;
            font-size: 10px; font-weight: 600; color: #fff;
            line-height: 16px;
            white-space: nowrap;
        }
        .col-link { color: #3b82f6; text-decoration: none; font-weight: 500; }
        .col-link:hover { text-decoration: underline; }
        .grid-table td { padding: 2px 6px; font-size: 10px; }
        .rate-cell { display: flex; gap: 2px; align-items: center; }
        .rate-cell .rate-val { font-weight: 600; color: #0f172a; }
        .rate-cell .rate-type { color: #94a3b8; font-size: 9px; }
        .filter-select { width: 100%; height: 18px; border: 1px solid #93c5fd; font-size: 9px; border-radius: 2px; padding: 0 2px; box-sizing: border-box; background: #fff; outline: none; }
        .filter-select:focus { border-color: #3b82f6; box-shadow: 0 0 0 1px rgba(59,130,246,0.2); }

        /* Status dropdown (toolbar button) */
        .status-dropdown-wrap { position: relative; display: inline-flex; }
        .status-dropdown-menu {
            display: none; position: absolute; top: 100%; left: 0; z-index: 9999;
            background: #fff; border: 1px solid #cbd5e1; border-radius: 4px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15); min-width: 150px;
            padding: 4px 0; margin-top: 2px;
        }
        .status-dropdown-menu.open { display: block; }
        .status-dropdown-item {
            display: flex; align-items: center; gap: 8px;
            padding: 5px 12px; font-size: 11px; color: #334155; cursor: pointer;
            transition: background 0.1s;
        }
        .status-dropdown-item:hover { background: #f1f5f9; }
        .status-dropdown-item .dot {
            width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
        }
        th.freeze-divider { border-left: 2px solid #3b82f6 !important; }
        td.freeze-divider { border-left: 2px solid #3b82f6 !important; }
    </style>
    @endpush

    {{-- ═══════════════════════ TOAST CONTAINER ═══════════════════════ --}}
    <div class="toast-container" id="toast-container"></div>

    {{-- ═══════════════════════ DELETE CONFIRM MODAL ═══════════════════════ --}}
    <div class="overlay" id="confirm-overlay" onclick="if(event.target===this) closeConfirm()">
        <div class="confirm-box">
            <div class="confirm-icon"><i class="fa fa-exclamation-triangle"></i></div>
            <h4>Delete Quotation(s)?</h4>
            <p id="confirm-msg">This action cannot be undone.</p>
            <div class="confirm-actions">
                <button class="btn-tool" style="padding:0 18px;height:26px;" onclick="closeConfirm()">Cancel</button>
                <button class="btn-tool danger" style="padding:0 18px;height:26px;" onclick="executeDelete()">
                    <i class="fa fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════ MAIN PAGE ═══════════════════════ --}}
    <div class="page-content">

        {{-- Breadcrumb --}}
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
                <li>Sales <i class="fa fa-angle-right"></i></li>
                <li><span style="color: #333; font-weight: 700;">Quotation List</span></li>
            </ul>
        </div>

        <div class="portlet light">
            {{-- ── PORTLET TITLE ── --}}
            <div class="portlet-title">
                <div class="caption" style="display:flex;align-items:center;gap:8px;">
                    <span class="caption-subject">Quotation List</span>
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
                    <a class="btn-action-round white" href="{{ route('sales.quotations.list', array_merge(request()->query(), ['export' => 'csv'])) }}" title="Download as CSV/Excel" target="_blank">
                        <i class="fa fa-file-excel-o"></i> Excel <i class="fa fa-angle-down"></i>
                    </a>
                </div>
            </div>

            {{-- ── TOOLBAR ── --}}
            <div class="portlet-tool">
                <div style="display:flex;gap:10px;align-items:center;">
                    <div class="btn-group">
                        <a class="btn-tool green" href="{{ route('sales.quotations.create') }}" title="New Quotation" target="_blank">
                            <i class="fa fa-plus"></i>
                        </a>
                        <button class="btn-tool" id="btn-copy"   disabled title="Copy Selected (select 1 row)" onclick="copySelected()">
                            <i class="fa fa-files-o"></i>
                        </button>
                        <button class="btn-tool" id="btn-delete" disabled title="Delete Selected" onclick="confirmDelete()">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="btn-group">
                        <div class="status-dropdown-wrap" id="status-dropdown-wrap">
                            <button class="btn-tool" id="btn-status" disabled style="padding:0 12px;" onclick="toggleStatusDropdown(event)">
                                Change Status <i class="fa fa-angle-down"></i>
                            </button>
                            <div class="status-dropdown-menu" id="status-dropdown-menu"></div>
                        </div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <i class="fa fa-search" style="font-size:10px;color:#94a3b8;"></i>
                    <input type="text" id="quick-search" class="input-inline" style="width:160px;"
                           placeholder="Quick search..." oninput="quickSearch(this.value)" onkeyup="if(event.key === 'Enter') this.blur()">
                </div>
            </div>

            {{-- ── BULK-ACTION FORM + TABLE ── --}}
            <form id="bulk-form" method="POST" action="#" style="margin:0;">
                @csrf
                @method('DELETE')
                <div class="portlet-body">
                    <div class="grid-container">
                        <div class="grid-wrapper">
                            <table class="grid-table" id="main-grid">
                                <thead>
                                    {{-- ── HEADER ROW ── --}}
                                    <tr id="header-row" class="grid-header-row">
                                        <th class="sticky-col sticky-col-header" data-col="check"    style="width:25px;left:0;text-align:center;">
                                            <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" title="Select All">
                                        </th>
                                        <th class="sticky-col sticky-col-header" data-col="quote_no"  style="width:120px;left:25px;">Quote No.</th>
                                        <th class="sticky-col sticky-col-header" data-col="date"      style="width:85px;left:145px;">Create Date</th>
                                        <th class="sticky-col sticky-col-header" data-col="pub_status" style="width:85px;left:230px;">Publication Status</th>
                                        <th class="sticky-col sticky-col-header" data-col="status"     style="width:80px;left:315px;">Status</th>
                                        <th class="freeze-divider" data-col="office"      style="width:100px;">Office</th>
                                        <th data-col="customer"    style="width:140px;">Customer</th>
                                        <th data-col="agent"       style="width:100px;">Agent</th>
                                        <th data-col="term"        style="width:80px;">Service Term</th>
                                        <th data-col="type"        style="width:100px;">Shipping Type</th>
                                        <th data-col="pol"         style="width:120px;">Port of Loading</th>
                                        <th data-col="pod"         style="width:120px;">Port of Discharge</th>
                                        <th data-col="rate1"       style="width:100px;">Rate / Type</th>
                                        <th data-col="rate2"       style="width:100px;">Rate2 / Type2</th>
                                        <th data-col="rate3"       style="width:100px;">Rate3 / Type3</th>
                                        <th data-col="rate4"       style="width:100px;">Rate4 / Type4</th>
                                        <th data-col="rate5"       style="width:100px;">Rate5 / Type5</th>
                                        <th data-col="rate6"       style="width:100px;">Rate6 / Type6</th>
                                        <th data-col="rate7"       style="width:100px;">Rate7 / Type7</th>
                                        <th data-col="qremark"     style="width:120px;">Quotation Remark</th>
                                        <th data-col="valid_date"  style="width:85px;">Valid Date</th>
                                        <th data-col="departure"   style="width:100px;">Departure</th>
                                        <th data-col="dest"        style="width:100px;">Destination</th>
                                        <th data-col="carrier"     style="width:110px;">Carrier</th>
                                        <th data-col="via"         style="width:80px;">Via</th>
                                        <th data-col="tt"          style="width:60px;">T/T</th>
                                        <th data-col="commodity"   style="width:120px;">Commodity</th>
                                        <th data-col="created_by"  style="width:100px;">Created By</th>
                                        <th data-col="sales"       style="width:100px;">Sales</th>
                                        <th data-col="op"          style="width:100px;">OP</th>
                                        <th data-col="remark"      style="width:120px;">Remark</th>
                                        <th data-col="liner_code"  style="width:80px;">Liner Code</th>
                                        <th data-col="fdest"       style="width:100px;">Final Destination</th>
                                        <th data-col="por"         style="width:100px;">Place of Receipt</th>
                                        <th data-col="podl"        style="width:100px;">Place of Delivery</th>
                                        <th data-col="schedule"    style="width:80px;">Schedule</th>
                                        <th data-col="cost1"       style="width:90px;">Rate 1 Cost</th>
                                        <th data-col="cost2"       style="width:90px;">Rate 2 Cost</th>
                                        <th data-col="cost3"       style="width:90px;">Rate 3 Cost</th>
                                        <th data-col="cost4"       style="width:90px;">Rate 4 Cost</th>
                                        <th data-col="cost5"       style="width:90px;">Rate 5 Cost</th>
                                        <th data-col="cost6"       style="width:90px;">Rate 6 Cost</th>
                                        <th data-col="cost7"       style="width:90px;">Rate 7 Cost</th>
                                        <th data-col="ship_mode"   style="width:70px;">Ship Mode</th>
                                    </tr>

                                    {{-- ── FILTER ROW (hidden by default) ── --}}
                                    <tr id="filter-row" style="display:none;">
                                        <td class="sticky-col" style="left:0;"></td>
                                        <td class="sticky-col" style="left:25px;"><input class="filter-input" data-col-idx="1" placeholder="Quote..." oninput="filterDebounce()"></td>
                                        <td class="sticky-col" style="left:145px;"><input class="filter-input" data-col-idx="2" type="date" oninput="filterDebounce()"></td>
                                        <td class="sticky-col" style="left:230px;">
                                            <select class="filter-input filter-select" data-col-idx="3" onchange="applyFilters()">
                                                <option value="">All</option>
                                                <option value="Draft">Draft</option>
                                                <option value="Sent">Sent</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Won">Won</option>
                                                <option value="Lost">Lost</option>
                                                <option value="Expired">Expired</option>
                                                <option value="Cancelled">Cancelled</option>
                                                <option value="Ghosted">Ghosted</option>
                                            </select>
                                        </td>
                                        <td class="sticky-col" style="left:315px;">
                                            <select class="filter-input filter-select" data-col-idx="4" onchange="applyFilters()">
                                                <option value="">All</option>
                                                <option value="Draft">Draft</option>
                                                <option value="Sent">Sent</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Won">Won</option>
                                                <option value="Lost">Lost</option>
                                                <option value="Expired">Expired</option>
                                                <option value="Cancelled">Cancelled</option>
                                                <option value="Ghosted">Ghosted</option>
                                            </select>
                                        </td>
                                        <td><input class="filter-input" data-col-idx="5"  placeholder="Office..." oninput="filterDebounce()"></td>
                                        <td><input class="filter-input" data-col-idx="6"  placeholder="Customer..." oninput="filterDebounce()"></td>
                                        <td><input class="filter-input" data-col-idx="7"  placeholder="Agent..."    oninput="filterDebounce()"></td>
                                        <td><input class="filter-input" data-col-idx="8"  placeholder="Term..."      oninput="filterDebounce()"></td>
                                        <td>
                                            <select class="filter-input filter-select" data-col-idx="9" onchange="applyFilters()">
                                                <option value="">All</option>
                                                <option value="Ocean Import">Ocean Import</option>
                                                <option value="Ocean Export">Ocean Export</option>
                                                <option value="Air Import">Air Import</option>
                                                <option value="Air Export">Air Export</option>
                                                <option value="Truck">Truck</option>
                                            </select>
                                        </td>
                                        <td><input class="filter-input" data-col-idx="10" placeholder="POL..." oninput="filterDebounce()"></td>
                                        <td><input class="filter-input" data-col-idx="11" placeholder="POD..." oninput="filterDebounce()"></td>
                                        <td colspan="26"></td>
                                    </tr>
                                </thead>

                                <tbody id="grid-body">
                                @forelse($quotations as $q)
                                    @php
                                        $arItems = $q->items->where('type', 'AR')->values();
                                        $dcItems = $q->items->where('type', 'DC_NOTE')->values();
                                        $stParts = explode('~', $q->service_term ?? '');
                                        $stDisplay = trim(($stParts[0] ?? '') . ' / ' . ($stParts[1] ?? ''), ' /');
                                    @endphp
                                    <tr id="quote-row-{{ $q->id }}"
                                        data-id="{{ $q->id }}"
                                        data-quote="{{ $q->quote_no }}"
                                        data-customer="{{ $q->customer?->name ?? '' }}"
                                        data-status="{{ $q->status }}"
                                        onclick="rowClick(event, this)"
                                    >
                                        <td class="sticky-col" style="width:25px;text-align:center;left:0;" onclick="event.stopPropagation()">
                                            <input type="checkbox" name="ids[]" value="{{ $q->id }}" class="row-check" onchange="updateToolbar()">
                                        </td>
                                        <td class="sticky-col" style="width:120px;left:25px;" onclick="event.stopPropagation()">
                                            <a href="{{ route('sales.quotations.edit', $q->id) }}" class="col-link">{{ $q->quote_no }}</a>
                                        </td>
                                        <td class="sticky-col" style="width:85px;left:145px;">{{ ($q->quote_date ?? $q->created_at)?->format('Y-m-d') ?? '--' }}</td>
                                        <td class="sticky-col" style="width:85px;left:230px;text-align:center;">
                                            <div class="status-badge-grid" style="background:{{ $statusColors[$q->status] ?? '#888' }}">
                                                <span>{{ $q->status }}</span>
                                            </div>
                                        </td>
                                        <td class="sticky-col" style="width:80px;left:315px;text-align:center;">
                                            <span style="font-size:9px;color:#64748b;">{{ $q->status }}</span>
                                        </td>
                                        <td class="freeze-divider">{{ $q->office?->name ?? '--' }}</td>
                                        <td>{{ $q->customer?->name ?? '--' }}</td>
                                        <td>{{ $q->agent?->name ?? '--' }}</td>
                                        <td>{{ $stDisplay ?: '--' }}</td>
                                        <td>{{ $q->transport_mode ?? '--' }}</td>
                                        <td>{{ $q->pol?->name ?? '--' }}</td>
                                        <td>{{ $q->pod?->name ?? '--' }}</td>
                                        @for($i = 0; $i < 7; $i++)
                                        <td>
                                            <div class="rate-cell">
                                                @isset($arItems[$i])<span class="rate-val">{{ number_format($arItems[$i]->rate, 2) }}</span> <span class="rate-type">{{ $arItems[$i]->currency->code ?? '' }} {{ $arItems[$i]->unit }}</span>@else<span class="rate-val" style="color:#ccc;">--</span>@endisset
                                            </div>
                                        </td>
                                        @endfor
                                        <td title="{{ $q->quotation_remark ?? '' }}">{{ Str::limit($q->quotation_remark ?? '', 30) ?: '--' }}</td>
                                        <td>{{ $q->expiry_date?->format('Y-m-d') ?? '--' }}</td>
                                        <td>{{ $q->departure ?? '--' }}</td>
                                        <td>{{ $q->destination ?? '--' }}</td>
                                        <td>{{ $q->carrier?->name ?? '--' }}</td>
                                        <td>{{ $q->via ?? '--' }}</td>
                                        <td>{{ $q->tt ?? '--' }}</td>
                                        <td title="{{ $q->commodity ?? '' }}">{{ Str::limit($q->commodity ?? '', 20) ?: '--' }}</td>
                                        <td>{{ $q->createdBy?->name ?? '--' }}</td>
                                        <td>{{ $q->salesPerson?->name ?? '--' }}</td>
                                        <td>{{ $q->op?->name ?? '--' }}</td>
                                        <td title="{{ $q->internal_remark ?? '' }}">{{ Str::limit($q->internal_remark ?? '', 25) ?: '--' }}</td>
                                        <td>{{ $q->liner_code ?? '--' }}</td>
                                        <td>{{ $q->final_destination ?? '--' }}</td>
                                        <td>{{ $q->place_of_receipt ?? '--' }}</td>
                                        <td>{{ $q->place_of_delivery ?? '--' }}</td>
                                        <td>{{ $q->schedule?->schedule_no ?? '--' }}</td>
                                        @for($i = 0; $i < 7; $i++)
                                        <td>
                                            <div class="rate-cell">
                                                @isset($dcItems[$i])<span class="rate-val">{{ number_format($dcItems[$i]->rate, 2) }}</span> <span class="rate-type">{{ $dcItems[$i]->currency->code ?? '' }}</span>@else<span class="rate-val" style="color:#ccc;">--</span>@endisset
                                            </div>
                                        </td>
                                        @endfor
                                        <td>{{ $q->ship_mode ?? '--' }}</td>
                                    </tr>
                                @empty
                                    <tr id="empty-row">
                                        <td colspan="50" style="text-align:center;padding:30px 10px;color:#94a3b8;">
                                            <i class="fa fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                                            No quotations found.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>

            {{-- ── PAGINATION ── --}}
            <div class="portlet-tool bottom">
                <div style="display:flex;justify-content:space-between;width:100%;align-items:center;">
                    <div id="pagination-container">{{ $quotations->withQueryString()->links() }}</div>
                    <div style="font-size:10px;color:#64748b;">
                        Showing <span id="stat-first">{{ $quotations->firstItem() ?? 0 }}</span> – <span id="stat-last">{{ $quotations->lastItem() ?? 0 }}</span> of <span id="stat-total">{{ $quotations->total() }}</span> records
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    /* ================================================================
       STATUS COLOR MAP
    ================================================================ */
    var STATUS_COLORS = @json($statusColors);

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
        document.getElementById('btn-copy').disabled    = n !== 1;
        document.getElementById('btn-status').disabled  = n === 0;

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

    function getSelectedIds() {
        return [...document.querySelectorAll('.row-check:checked')].map(cb => cb.value);
    }

    /* ================================================================
       DELETE — AJAX + remove row from DOM
    ================================================================ */
    function confirmDelete() {
        const n = document.querySelectorAll('.row-check:checked').length;
        if (!n) return;
        document.getElementById('confirm-msg').textContent =
            'You are about to permanently delete ' + n + ' quotation(s). This cannot be undone.';
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
        fetch('{{ route("quotations.bulk-delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: ids }),
        }).then(function(r) { return r.json(); }).then(function(data) {
            if (data.success) {
                ids.forEach(function(id) {
                    var row = document.getElementById('quote-row-' + id);
                    if (row) row.remove();
                });
                showToast('success', data.message || 'Deleted successfully');
                updateToolbar();
                var total = document.getElementById('stat-total');
                var last  = document.getElementById('stat-last');
                if (total) total.textContent = Math.max(0, parseInt(total.textContent) - ids.length);
                if (last)  last.textContent = document.querySelectorAll('#grid-body tr[data-id]').length;
            } else {
                showToast('error', data.message || 'Failed to delete');
            }
        }).catch(function() { showToast('error', 'Failed to delete'); });
    }

    /* ================================================================
       COPY — navigate to create page with ?copy=id
    ================================================================ */
    function copySelected() {
        const checked = [...document.querySelectorAll('.row-check:checked')];
        if (checked.length !== 1) return;
        const row = checked[0].closest('tr');
        showToast('info', 'Copying quotation: ' + (row.dataset.quote || '') + ' ...');
        setTimeout(function() {
            window.location.href = '/sales/quotation/create?copy=' + row.dataset.id;
        }, 600);
    }

    /* ================================================================
       CHANGE STATUS — dropdown below the button
    ================================================================ */
    function buildStatusDropdown() {
        var menu = document.getElementById('status-dropdown-menu');
        if (menu.children.length > 0) return;
        var labels = Object.keys(STATUS_COLORS);
        labels.forEach(function(label) {
            var item = document.createElement('div');
            item.className = 'status-dropdown-item';
            item.innerHTML = '<span class="dot" style="background:' + (STATUS_COLORS[label] || '#888') + '"></span>' + label;
            item.setAttribute('data-status', label);
            item.onclick = function() {
                changeStatusTo(label);
                closeStatusDropdown();
            };
            menu.appendChild(item);
        });
    }

    function toggleStatusDropdown(e) {
        e.stopPropagation();
        var menu = document.getElementById('status-dropdown-menu');
        if (menu.classList.contains('open')) {
            closeStatusDropdown();
        } else {
            buildStatusDropdown();
            menu.classList.add('open');
        }
    }

    function closeStatusDropdown() {
        document.getElementById('status-dropdown-menu').classList.remove('open');
    }

    document.addEventListener('click', function(e) {
        var wrap = document.getElementById('status-dropdown-wrap');
        if (wrap && !wrap.contains(e.target)) closeStatusDropdown();
    });

    function changeStatusTo(newStatus) {
        var ids = getSelectedIds();
        if (!ids.length) return;
        var color = STATUS_COLORS[newStatus] || '#888';
        showToast('info', 'Updating status to ' + newStatus + '...');
        fetch('{{ route("quotations.bulk-status") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: ids, status: newStatus }),
        }).then(function(r) { return r.json(); }).then(function(data) {
            if (data.success) {
                ids.forEach(function(qid) {
                    var row = document.getElementById('quote-row-' + qid);
                    if (row) {
                        row.dataset.status = newStatus;
                        var badges = row.querySelectorAll('.status-badge-grid');
                        badges.forEach(function(badge) {
                            badge.style.background = color;
                            badge.querySelector('span').textContent = newStatus;
                        });
                    }
                });
                showToast('success', data.message || 'Status updated');
            } else {
                showToast('error', data.message || 'Failed to update status');
            }
        }).catch(function() { showToast('error', 'Failed to update status'); });
    }

    /* ================================================================
       FILTER — AJAX-based, no full page reload
    ================================================================ */
    function toggleFilter() {
        var filterRow = document.getElementById('filter-row');
        var isVisible = filterRow.style.display === 'table-row';
        filterRow.style.display = isVisible ? 'none' : 'table-row';
        document.getElementById('btn-filter').classList.toggle('active', !isVisible);
        if (!isVisible) {
            filterRow.querySelectorAll('.filter-input').forEach(function(inp) { inp.value = ''; });
            var urlParams = new URLSearchParams(window.location.search);
            document.querySelectorAll('.filter-input').forEach(function(inp) {
                var idx = parseInt(inp.dataset.colIdx);
                var paramMap = { 1: 'quote_no', 2: 'date', 3: 'status', 4: 'status', 6: 'customer', 7: 'agent', 8: 'term', 9: 'type', 10: 'pol', 11: 'pod' };
                if (paramMap[idx]) inp.value = urlParams.get(paramMap[idx]) || '';
            });
            var first = document.querySelector('.filter-input');
            if (first) first.focus();
        } else {
            document.querySelectorAll('.filter-input').forEach(function(i) { i.value = ''; });
            applyFilters();
        }
    }

    function filterDebounce() {
        clearTimeout(window._filterTimer);
        window._filterTimer = setTimeout(function() { applyFilters(); }, 350);
    }

    function applyFilters() {
        var inputs = [...document.querySelectorAll('#filter-row .filter-input')];
        var url = new URL(window.location.pathname, window.location.origin);
        var existingSearch = new URLSearchParams(window.location.search).get('search');
        if (existingSearch) url.searchParams.set('search', existingSearch);
        var filterMap = {
            1: 'quote_no', 2: 'date', 3: 'status', 4: 'status', 6: 'customer',
            7: 'agent', 8: 'term', 9: 'type', 10: 'pol', 11: 'pod'
        };
        inputs.forEach(function(inp) {
            var v = inp.value.trim();
            if (!v) return;
            var param = filterMap[inp.dataset.colIdx];
            if (param) url.searchParams.set(param, v);
        });
        updateGrid(url.toString());
    }

    /* ================================================================
       QUICK SEARCH — AJAX, no full page reload
    ================================================================ */
    var searchDebounce;
    function quickSearch(val) {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(function() {
            var q = val.trim();
            var url = new URL(window.location.pathname, window.location.origin);
            if (q) url.searchParams.set('search', q);
            var params = new URLSearchParams(window.location.search);
            params.forEach(function(v, k) {
                if (k !== 'search' && k !== 'export') url.searchParams.set(k, v);
            });
            updateGrid(url.toString());
        }, 300);
    }

    /* ================================================================
       CONFIG PANEL — column visibility
    ================================================================ */
    var PINNED_COLS = ['check', 'quote_no', 'date', 'pub_status', 'status'];

    function toggleConfig() {
        var panel = document.getElementById('config-panel');
        var open  = panel.style.display === 'none';
        panel.style.display = open ? 'block' : 'none';
        document.getElementById('btn-config').classList.toggle('active', open);
        if (open) buildConfigPanel();
    }

    function buildConfigPanel() {
        var container = document.getElementById('col-toggles');
        container.innerHTML = '';
        document.querySelectorAll('#header-row th[data-col]').forEach(function(th) {
            if (PINNED_COLS.includes(th.dataset.col)) return;
            var label = document.createElement('label');
            var cb    = document.createElement('input');
            cb.type    = 'checkbox';
            cb.checked = th.style.display !== 'none';
            cb.onchange = function() { toggleColumn(th.dataset.col, cb.checked); };
            label.appendChild(cb);
            label.append(' ' + th.textContent.trim());
            container.appendChild(label);
        });
    }

    function toggleColumn(colName, show) {
        var th  = document.querySelector('#header-row th[data-col="' + colName + '"]');
        var idx = [...th.parentElement.children].indexOf(th);
        th.style.display = show ? '' : 'none';
        document.querySelectorAll('#grid-body tr, #filter-row').forEach(function(row) {
            var cell = row.querySelectorAll('td, th')[idx];
            if (cell) cell.style.display = show ? '' : 'none';
        });
    }

    document.addEventListener('click', function(e) {
        var panel = document.getElementById('config-panel');
        var btn   = document.getElementById('btn-config');
        if (panel && panel.style.display !== 'none' && !panel.contains(e.target) && !btn.contains(e.target)) {
            panel.style.display = 'none';
            btn.classList.remove('active');
        }
    });

    /* ================================================================
       AJAX GRID UPDATE (pagination, filter, search)
    ================================================================ */
    function updateGrid(url) {
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function(response) {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        }).then(function(html) {
            var parser = new DOMParser();
            var doc = parser.parseFromString(html, 'text/html');
            window.history.pushState({}, '', url);

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
            updateToolbar();
        }).catch(function(e) {
            console.error(e);
            showToast('error', 'Failed to update grid');
        });
    }

    document.addEventListener('click', function(e) {
        var link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            updateGrid(link.href);
        }
    });

    /* ================================================================
       TOAST NOTIFICATIONS
    ================================================================ */
    function showToast(type, msg) {
        var icons = { success: 'check-circle', error: 'times-circle', info: 'info-circle' };
        var t = document.createElement('div');
        t.className = 'toast ' + type;
        t.innerHTML = '<i class="fa fa-' + (icons[type] || 'info-circle') + '"></i> ' + msg;
        document.getElementById('toast-container').appendChild(t);
        setTimeout(function() { t.remove(); }, 3000);
    }

    /* ================================================================
       FLASH MESSAGES
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
