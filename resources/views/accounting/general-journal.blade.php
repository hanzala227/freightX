<x-layout>
    @push('styles')
    <x-list-styles />
    @endpush

    <div class="toast-container" id="toast-container"></div>

    <div class="overlay" id="confirm-overlay" onclick="if(event.target===this) closeConfirm()">
        <div class="confirm-box">
            <div class="confirm-icon"><i class="fa fa-exclamation-triangle"></i></div>
            <h4>Delete Journal Entry(ies)?</h4>
            <p id="confirm-msg">This action cannot be undone.</p>
            <div class="confirm-actions">
                <button class="btn-tool" style="padding:0 18px;height:26px;" onclick="closeConfirm()">Cancel</button>
                <button class="btn-tool danger" style="padding:0 18px;height:26px;" onclick="executeDelete()">
                    <i class="fa fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
                <li>Accounting <i class="fa fa-angle-right"></i></li>
                <li>Journal <i class="fa fa-angle-right"></i></li>
                <li><span style="color: #333; font-weight: 700;">General Journal</span></li>
            </ul>
        </div>

        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption" style="display:flex;align-items:center;gap:8px;">
                    <span class="caption-subject">General Journal</span>
                    <span id="sel-badge" style="display:none;font-size:10px;color:#3b82f6;font-weight:600;background:#eff6ff;padding:1px 6px;border-radius:10px;border:1px solid #bfdbfe;"></span>
                </div>
                <div class="actions" style="display:flex;gap:4px;position:relative;align-items:center;">
                    <button class="btn-action-round" id="btn-filter" onclick="toggleFilter()" title="Toggle filter panel">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </div>

            <div class="portlet-tool">
                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                    <div class="btn-group">
                        <a class="btn-tool green" href="{{ route('accounting.journal.entry') }}" title="New Journal Entry" target="_blank">
                            <i class="fa fa-plus"></i>
                        </a>
                        <button class="btn-tool" id="btn-delete" disabled title="Delete Selected" onclick="confirmDelete()">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="btn-group">
                        <button class="btn-tool" style="padding:0 10px;" onclick="importJournal()">
                            <i class="fa fa-upload"></i> Import Journal
                        </button>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <i class="fa fa-search" style="font-size:10px;color:#94a3b8;"></i>
                    <input type="text" id="quick-search" class="input-inline" style="width:160px;"
                           placeholder="Quick search..." value="{{ request('search') }}"
                           oninput="quickSearch(this.value)">
                </div>
            </div>

            <div id="advanced-filter" style="display:none;background:#f0f4ff;padding:6px 8px;border-bottom:1px solid #bfdbfe;">
                <div id="filter-form" style="display:flex;flex-wrap:wrap;gap:6px;align-items:end;margin:0;">
                    <div style="display:flex;flex-direction:column;gap:1px;">
                        <label style="font-size:9px;color:#475569;font-weight:600;">From Date</label>
                        <input type="date" id="from_date" name="from_date" class="input-inline" style="width:130px;height:20px;font-size:9px;" value="{{ request('from_date') }}" onchange="applyFilters()">
                    </div>
                    <div style="display:flex;flex-direction:column;gap:1px;">
                        <label style="font-size:9px;color:#475569;font-weight:600;">To Date</label>
                        <input type="date" id="to_date" name="to_date" class="input-inline" style="width:130px;height:20px;font-size:9px;" value="{{ request('to_date') }}" onchange="applyFilters()">
                    </div>
                    <div style="display:flex;flex-direction:column;gap:1px;">
                        <label style="font-size:9px;color:#475569;font-weight:600;">Office</label>
                        <select id="office_id" name="office_id" class="input-inline" style="width:100px;height:20px;font-size:9px;" onchange="applyFilters()">
                            <option value="">All</option>
                            @foreach(\App\Models\Office::where('is_active', true)->orderBy('name')->get() as $office)
                                <option value="{{ $office->id }}" {{ request('office_id') == $office->id ? 'selected' : '' }}>{{ $office->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:1px;">
                        <label style="font-size:9px;color:#475569;font-weight:600;">Status</label>
                        <select id="status" name="status" class="input-inline" style="width:90px;height:20px;font-size:9px;" onchange="applyFilters()">
                            <option value="">All</option>
                            <option value="POSTED" {{ request('status') == 'POSTED' ? 'selected' : '' }}>Posted</option>
                            <option value="DRAFT" {{ request('status') == 'DRAFT' ? 'selected' : '' }}>Draft</option>
                            <option value="VOIDED" {{ request('status') == 'VOIDED' ? 'selected' : '' }}>Voided</option>
                        </select>
                    </div>
                    <div style="display:flex;gap:4px;">
                        <button type="button" class="btn-tool" style="padding:0 12px;" onclick="clearFilters()"><i class="fa fa-times"></i> Clear</button>
                    </div>
                </div>
            </div>

            <div style="width:100%;overflow-x:auto;background:#fff;">
                <table class="grid-table" id="main-grid" style="min-width:900px;">
                    <thead>
                        <tr id="header-row">
                            <th style="width:28px;text-align:center;background:#f8fafc;"><input type="checkbox" id="select-all" onclick="toggleAll(this)"></th>
                            <th style="width:28px;text-align:center;background:#f8fafc;" title="Lock Status"><i class="fa fa-lock" style="font-size:9px;color:#94a3b8;"></i></th>
                            <th style="width:110px;background:#f8fafc;">Post Date</th>
                            <th style="width:50px;background:#f8fafc;">Seq</th>
                            <th style="background:#f8fafc;">Remark</th>
                            <th style="width:90px;background:#f8fafc;text-align:right;">Debit</th>
                            <th style="width:90px;background:#f8fafc;text-align:right;">Credit</th>
                            <th style="width:70px;background:#f8fafc;">Type</th>
                            <th style="width:100px;background:#f8fafc;">Issued By</th>
                            <th style="width:80px;background:#f8fafc;">Office</th>
                        </tr>
                    </thead>
                    <tbody id="grid-body">
                        @forelse($entries as $entry)
                            @php
                                $totalDebit = $entry->lines->sum('local_debit');
                                $totalCredit = $entry->lines->sum('local_credit');
                            @endphp
                            <tr data-id="{{ $entry->id }}" style="cursor:pointer;" onclick="rowClick(event, this)">
                                <td style="text-align:center;"><input type="checkbox" class="row-check" value="{{ $entry->id }}" onclick="event.stopPropagation(); updateToolbar()"></td>
                                <td style="text-align:center;">
                                    @if($entry->status === 'POSTED')
                                        <span style="color:#22c55e;font-weight:700;font-size:11px;">Y</span>
                                    @else
                                        <span style="color:#ef4444;font-weight:700;font-size:11px;">N</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('accounting.journal.show', $entry->id) }}" onclick="event.stopPropagation()" class="col-link">
                                        {{ $entry->entry_date?->format('Y-m-d') }}
                                    </a>
                                </td>
                                <td style="text-align:center;">{{ $entry->id }}</td>
                                <td style="overflow:hidden;text-overflow:ellipsis;">{{ $entry->remark ?? $entry->description ?? '' }}</td>
                                <td style="text-align:right;font-family:'Courier New',monospace;">{{ number_format($totalDebit, 2) }}</td>
                                <td style="text-align:right;font-family:'Courier New',monospace;">{{ number_format($totalCredit, 2) }}</td>
                                <td>Entry</td>
                                <td>{{ $entry->creator?->name ?? '' }}</td>
                                <td>{{ $entry->office?->code ?? '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align:center;padding:24px;color:#94a3b8;">No journal entries found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="portlet-tool bottom">
                <div style="display:flex;justify-content:space-between;width:100%;align-items:center;">
                    <div id="pagination-container">{{ $entries->appends(request()->query())->links() }}</div>
                    <div style="font-size:10px;color:#64748b;">
                        Showing {{ $entries->firstItem() ?? 0 }} – {{ $entries->lastItem() ?? 0 }} of {{ $entries->total() }} records
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function getGridUrl() {
        var base = '{{ route("accounting.general-journal") }}';
        var params = new URLSearchParams();
        var q = document.getElementById('quick-search').value.trim();
        if (q) params.set('search', q);
        var fd = document.getElementById('from_date');
        var td = document.getElementById('to_date');
        var oi = document.getElementById('office_id');
        var st = document.getElementById('status');
        if (fd && fd.value) params.set('from_date', fd.value);
        if (td && td.value) params.set('to_date', td.value);
        if (oi && oi.value) params.set('office_id', oi.value);
        if (st && st.value) params.set('status', st.value);
        return base + '?' + params.toString();
    }

    async function updateGrid(url) {
        if (!url) url = getGridUrl();
        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) throw new Error('Network error');
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            var newBody = doc.getElementById('grid-body');
            var newPagination = doc.getElementById('pagination-container');
            var newStats = doc.querySelector('.portlet-tool.bottom div:last-child');

            if (newBody) document.getElementById('grid-body').innerHTML = newBody.innerHTML;
            if (newPagination) document.getElementById('pagination-container').innerHTML = newPagination.innerHTML;
            if (newStats) {
                var statsDiv = document.querySelector('.portlet-tool.bottom div:last-child');
                if (statsDiv) statsDiv.innerHTML = newStats.innerHTML;
            }

            // Re-bind pagination links
            document.querySelectorAll('#pagination-container a').forEach(function(a) {
                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    updateGrid(this.href);
                });
            });
        } catch (e) {
            showToast('error', 'Failed to update grid');
        }
    }

    function updateToolbar() {
        const checked = [...document.querySelectorAll('.row-check:checked')];
        const all = [...document.querySelectorAll('.row-check')];
        const n = checked.length;
        const sa = document.getElementById('select-all');
        const badge = document.getElementById('sel-badge');
        const delBtn = document.getElementById('btn-delete');

        if (sa) sa.checked = all.length > 0 && n === all.length;
        if (delBtn) delBtn.disabled = n === 0;

        if (n > 0) {
            badge.style.display = 'inline';
            badge.textContent = n + ' selected';
        } else {
            badge.style.display = 'none';
        }
    }

    function toggleAll(el) {
        const c = el.checked;
        document.querySelectorAll('.row-check').forEach(function(cb) {
            cb.checked = c;
            cb.closest('tr').classList.toggle('row-selected', c);
        });
        updateToolbar();
    }

    function rowClick(e, tr) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'A' || e.target.tagName === 'BUTTON') return;
        const cb = tr.querySelector('.row-check');
        if (cb) {
            cb.checked = !cb.checked;
            tr.classList.toggle('row-selected', cb.checked);
            updateToolbar();
        }
    }

    function showToast(type, msg) {
        var c = document.getElementById('toast-container');
        var t = document.createElement('div');
        t.className = 'toast ' + type;
        t.textContent = msg;
        c.appendChild(t);
        setTimeout(function(){ t.remove(); }, 4000);
    }

    let filterOpen = false;
    function toggleFilter() {
        filterOpen = !filterOpen;
        var panel = document.getElementById('advanced-filter');
        panel.style.display = filterOpen ? 'block' : 'none';
        document.getElementById('btn-filter').classList.toggle('active-filter', filterOpen);
    }

    let filterDebounce;
    function applyFilters() {
        clearTimeout(filterDebounce);
        filterDebounce = setTimeout(function() {
            updateGrid(getGridUrl());
        }, 300);
    }

    let searchDebounce;
    function quickSearch(val) {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(function() {
            updateGrid(getGridUrl());
        }, 300);
    }

    function clearFilters() {
        document.querySelectorAll('#filter-form input, #filter-form select').forEach(function(el) {
            if (el.type === 'date' || el.tagName === 'SELECT') el.value = '';
        });
        document.getElementById('quick-search').value = '';
        updateGrid(getGridUrl());
    }

    let deleteIds = [];
    function confirmDelete() {
        deleteIds = [...document.querySelectorAll('.row-check:checked')].map(function(cb) { return cb.value; });
        if (!deleteIds.length) { showToast('error', 'Please select row(s) to delete.'); return; }
        document.getElementById('confirm-msg').textContent = 'Delete ' + deleteIds.length + ' entry(ies)?';
        document.getElementById('confirm-overlay').classList.add('open');
    }
    function closeConfirm() {
        document.getElementById('confirm-overlay').classList.remove('open');
    }
    function executeDelete() {
        fetch('{{ route("accounting.general-journal.delete") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ ids: deleteIds })
        })
        .then(function(r) { return r.json(); })
        .then(function(resp) {
            closeConfirm();
            if (!resp.success) { showToast('error', resp.message || 'Delete failed.'); return; }
            showToast('success', resp.message || 'Deleted!');
            updateGrid(getGridUrl());
        })
        .catch(function() { closeConfirm(); showToast('error', 'Network error.'); });
    }

    function importJournal() {
        showToast('info', 'Import Journal coming soon.');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Bind pagination links
        document.querySelectorAll('#pagination-container a').forEach(function(a) {
            a.addEventListener('click', function(e) {
                e.preventDefault();
                updateGrid(this.href);
            });
        });
    });
    </script>
    @endpush
</x-layout>
