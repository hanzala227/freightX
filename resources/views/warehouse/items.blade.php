<x-layout>
    @push('styles')
    <x-list-styles />
    <style>
        .form-input-gf { width: 100%; height: 20px; border: 1px solid #cbd5e1; padding: 0 4px; font-size: 10px; border-radius: 2px; background: #fff; color: #1e293b; box-sizing: border-box; }
        .form-input-gf:focus { border-color: #3b82f6; outline: none; box-shadow: 0 0 0 2px rgba(59,130,246,0.1); }
        .modal-form-row { display: flex; flex-wrap: wrap; margin: 0 -6px; }
        .modal-form-col { padding: 0 6px; margin-bottom: 8px; box-sizing: border-box; }
        .modal-label { font-size: 10px; font-weight: 700; color: #475569; margin-bottom: 3px; display: block; }
        .modal-label .text-danger { color: #ef4444; }
        .modal-box { min-width: 400px; max-width: 800px; }
        .modal-body { max-height: 70vh; overflow-y: auto; }
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
                <button class="btn-tool danger" style="padding:0 18px;height:26px;" onclick="executeDelete()"><i class="fa fa-trash"></i> Delete</button>
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

    {{-- ITEM CREATE/EDIT MODAL --}}
    <div class="overlay" id="item-modal-overlay" onclick="if(event.target===this) closeItemModal()">
        <div class="modal-box" style="min-width:700px;">
            <div class="modal-header">
                <div class="modal-header-title" id="item-modal-title"><i class="fa fa-cube" style="color:#3b82f6;"></i> <span id="item-modal-title-text">Create Item</span></div>
                <button class="modal-close" onclick="closeItemModal()"><i class="fa fa-times"></i></button>
            </div>
            <form id="item-form" method="POST" enctype="multipart/form-data" style="margin:0;">
                @csrf
                <input type="hidden" name="_method" id="item-form-method" value="POST">
                <div class="modal-body">
                    <div class="modal-form-row">
                        <div class="modal-form-col" style="width:50%;">
                            <label class="modal-label"><span class="text-danger">*</span> Customer</label>
                            <select name="customer_id" id="item-customer_id" class="form-input-gf" required>
                                <option value="">Select...</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-form-col" style="width:50%;">
                            <label class="modal-label"><span class="text-danger">*</span> SKU No.</label>
                            <input type="text" name="sku" id="item-sku" class="form-input-gf" required>
                        </div>
                        <div class="modal-form-col" style="width:50%;">
                            <label class="modal-label">Vendor</label>
                            <select name="vendor_id" id="item-vendor_id" class="form-input-gf">
                                <option value="">Select...</option>
                                @foreach($vendors as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-form-col" style="width:50%;">
                            <label class="modal-label"><span class="text-danger">*</span> Warehouse</label>
                            <select name="warehouse_id" id="item-warehouse_id" class="form-input-gf" required>
                                <option value="">Select...</option>
                                @foreach($warehouses as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-form-col" style="width:100%;">
                            <label class="modal-label"><span class="text-danger">*</span> Product Name</label>
                            <input type="text" name="item_name" id="item-item_name" class="form-input-gf" required>
                        </div>
                        <div class="modal-form-col" style="width:33%;">
                            <label class="modal-label">UPC/EAN</label>
                            <input type="text" name="upc_ean" id="item-upc_ean" class="form-input-gf">
                        </div>
                        <div class="modal-form-col" style="width:33%;">
                            <label class="modal-label">MPN</label>
                            <input type="text" name="mpn" id="item-mpn" class="form-input-gf">
                        </div>
                        <div class="modal-form-col" style="width:33%;">
                            <label class="modal-label">HTS Code</label>
                            <input type="text" name="hts_code" id="item-hts_code" class="form-input-gf">
                        </div>
                        <div class="modal-form-col" style="width:100%;">
                            <label class="modal-label">Product Description</label>
                            <textarea name="description" id="item-description" class="form-input-gf" style="height:40px;resize:vertical;"></textarea>
                        </div>
                        <div class="modal-form-col" style="width:25%;">
                            <label class="modal-label">Inner Pack</label>
                            <input type="number" step="0.01" name="inner_pack" id="item-inner_pack" class="form-input-gf" value="0">
                        </div>
                        <div class="modal-form-col" style="width:25%;">
                            <label class="modal-label">Qty Unit</label>
                            <select name="unit_id" id="item-unit_id" class="form-input-gf">
                                <option value="">Select...</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-form-col" style="width:25%;">
                            <label class="modal-label">On Hand Qty</label>
                            <input type="number" step="0.01" name="on_hand_qty" id="item-on_hand_qty" class="form-input-gf" value="0">
                        </div>
                        <div class="modal-form-col" style="width:25%;">
                            <label class="modal-label">Available Qty</label>
                            <input type="number" step="0.01" name="available_qty" id="item-available_qty" class="form-input-gf" value="0">
                        </div>
                        <div class="modal-form-col" style="width:50%;">
                            <label class="modal-label">Weight (KG)</label>
                            <input type="number" step="0.001" name="weight_kg" id="item-weight_kg" class="form-input-gf" value="0">
                        </div>
                        <div class="modal-form-col" style="width:50%;">
                            <label class="modal-label">Measurement (CBM)</label>
                            <input type="number" step="0.001" name="volume_cbm" id="item-volume_cbm" class="form-input-gf" value="0">
                        </div>
                        <div class="modal-form-col" style="width:100%;">
                            <label class="modal-label">Dimension</label>
                            <div style="display:flex;gap:6px;align-items:center;">
                                <input type="number" step="0.01" name="dimension_length" id="item-dimension_length" class="form-input-gf" style="width:80px;" placeholder="Length">
                                <span style="font-size:10px;color:#64748b;">x</span>
                                <input type="number" step="0.01" name="dimension_width" id="item-dimension_width" class="form-input-gf" style="width:80px;" placeholder="Width">
                                <span style="font-size:10px;color:#64748b;">x</span>
                                <input type="number" step="0.01" name="dimension_height" id="item-dimension_height" class="form-input-gf" style="width:80px;" placeholder="Height">
                                <div style="display:flex;gap:2px;margin-left:4px;">
                                    <label style="font-size:9px;display:flex;align-items:center;gap:2px;cursor:pointer;padding:2px 6px;border:1px solid #cbd5e1;border-radius:2px;background:#fff;">
                                        <input type="radio" name="dimension_unit" value="cm" checked style="margin:0;"> CM
                                    </label>
                                    <label style="font-size:9px;display:flex;align-items:center;gap:2px;cursor:pointer;padding:2px 6px;border:1px solid #cbd5e1;border-radius:2px;background:#fff;">
                                        <input type="radio" name="dimension_unit" value="inch" style="margin:0;"> Inch
                                    </label>
                                    <label style="font-size:9px;display:flex;align-items:center;gap:2px;cursor:pointer;padding:2px 6px;border:1px solid #cbd5e1;border-radius:2px;background:#fff;">
                                        <input type="radio" name="dimension_unit" value="feet" style="margin:0;"> Feet
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-form-col" style="width:100%;">
                            <label class="modal-label">Remark</label>
                            <textarea name="remark" id="item-remark" class="form-input-gf" style="height:40px;resize:vertical;"></textarea>
                        </div>
                        <div class="modal-form-col" style="width:33%;">
                            <label class="modal-label">Create Date</label>
                            <input type="date" name="create_date" id="item-create_date" class="form-input-gf">
                        </div>
                        <div class="modal-form-col" style="width:33%;">
                            <label class="modal-label">Status</label>
                            <select name="status" id="item-status" class="form-input-gf">
                                <option value="enable">Enable</option>
                                <option value="disable">Disable</option>
                            </select>
                        </div>
                        <div class="modal-form-col" style="width:33%;">
                            <label class="modal-label">Product Photo</label>
                            <input type="file" name="product_photo" id="item-product_photo" class="form-input-gf" style="padding:1px 4px;height:auto;" accept="image/jpeg,image/png,image/gif">
                        </div>
                    </div>
                </div>
                <div class="modal-header" style="border-top:1px solid #e2e8f0;border-bottom:none;justify-content:flex-end;gap:6px;">
                    <button type="button" class="btn-tool" onclick="closeItemModal()">Cancel</button>
                    <button type="submit" class="btn-tool green">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MAIN PAGE --}}
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
                <li>Warehouse <i class="fa fa-angle-right"></i></li>
                <li><span style="color:#333;font-weight:700;">Items List</span></li>
            </ul>
        </div>

        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption" style="display:flex;align-items:center;gap:8px;">
                    <span class="caption-subject">Warehouse Items</span>
                    <span id="sel-badge" style="display:none;font-size:10px;color:#3b82f6;font-weight:600;background:#eff6ff;padding:1px 6px;border-radius:10px;border:1px solid #bfdbfe;"></span>
                </div>
                <div class="actions" style="display:flex;gap:4px;position:relative;align-items:center;">
                    <button class="btn-action-round" id="btn-filter" onclick="toggleFilter()" title="Toggle filter row"><i class="fa fa-filter"></i> Filter</button>
                    <div style="position:relative;display:inline-flex;align-items:center;">
                        <button class="btn-action-round" id="btn-config" onclick="toggleConfig()" title="Column visibility"><i class="fa fa-cogs"></i> Config</button>
                        <div class="config-panel" id="config-panel" style="display:none;">
                            <div class="config-panel-title">Column Visibility</div>
                            <div id="col-toggles"></div>
                        </div>
                    </div>
                    <button class="btn-action-round" onclick="refreshGrid()" title="Refresh"><i class="fa fa-refresh"></i></button>
                    <button class="btn-action-round" onclick="window.print()" title="Print"><i class="fa fa-print"></i></button>
                    <a class="btn-action-round white" href="{{ route('items.export-csv') }}" id="btn-excel" title="Download as CSV" target="_blank"><i class="fa fa-file-excel-o"></i> Excel</a>
                </div>
            </div>

            <div class="portlet-tool">
                <div style="display:flex;gap:10px;align-items:center;">
                    <div class="btn-group">
                        <button class="btn-tool green" onclick="openCreateModal()" title="New Item"><i class="fa fa-plus"></i></button>
                        <button class="btn-tool" id="btn-delete" disabled title="Delete Selected" onclick="confirmDelete()"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <i class="fa fa-search" style="font-size:10px;color:#94a3b8;"></i>
                    <input type="text" id="quick-search" class="input-inline" style="width:160px;" placeholder="Quick search..." value="{{ request('search') }}" oninput="quickSearch(this.value)">
                    <a href="javascript:;" id="clear-search-btn" onclick="clearSearch()" style="display:{{ request()->has('search') ? 'inline' : 'none' }};font-size:10px;color:#3b82f6;text-decoration:none;cursor:pointer;" title="Clear search"><i class="fa fa-times-circle"></i></a>
                </div>
            </div>

            <form id="bulk-form" method="POST" action="{{ route('items.bulk-delete') }}" style="margin:0;">
                @csrf
                @method('DELETE')
                <div class="portlet-body" style="position:relative;">
                    <div class="grid-container">
                        <div class="grid-wrapper">
                            <table class="grid-table" id="main-grid">
                                <thead>
                                    <tr id="header-row">
                                        <th class="sticky-col sticky-col-header" data-col="check" style="width:25px;text-align:center;"><input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" title="Select All"></th>
                                        <th class="sticky-col sticky-col-header" data-col="color" style="width:30px;left:25px;text-align:center;">Color</th>
                                        <th class="sticky-col sticky-col-header" data-col="warehouse" style="width:130px;left:55px;">
                                            <a href="javascript:;" onclick="toggleSort('warehouse_id')" style="color:inherit;text-decoration:none;">Warehouse <i class="fa fa-sort" id="sort-warehouse_id"></i></a>
                                        </th>
                                        <th data-col="sku" style="width:100px;">
                                            <a href="javascript:;" onclick="toggleSort('sku')" style="color:inherit;text-decoration:none;">SKU No. <i class="fa fa-sort" id="sort-sku"></i></a>
                                        </th>
                                        <th data-col="item_name" style="width:180px;">
                                            <a href="javascript:;" onclick="toggleSort('item_name')" style="color:inherit;text-decoration:none;">Product Name <i class="fa fa-sort" id="sort-item_name"></i></a>
                                        </th>
                                        <th data-col="description" style="width:150px;">Description</th>
                                        <th data-col="unit" style="width:60px;">Unit</th>
                                        <th data-col="on_hand_qty" style="width:80px;text-align:right;">On Hand</th>
                                        <th data-col="available_qty" style="width:80px;text-align:right;">Available</th>
                                        <th data-col="weight_kg" style="width:80px;text-align:right;">
                                            <a href="javascript:;" onclick="toggleSort('weight_kg')" style="color:inherit;text-decoration:none;">Weight <i class="fa fa-sort" id="sort-weight_kg"></i></a>
                                        </th>
                                        <th data-col="volume_cbm" style="width:80px;text-align:right;">
                                            <a href="javascript:;" onclick="toggleSort('volume_cbm')" style="color:inherit;text-decoration:none;">Volume <i class="fa fa-sort" id="sort-volume_cbm"></i></a>
                                        </th>
                                        <th data-col="created_at" style="width:80px;">
                                            <a href="javascript:;" onclick="toggleSort('created_at')" style="color:inherit;text-decoration:none;">Created <i class="fa fa-sort" id="sort-created_at"></i></a>
                                        </th>
                                    </tr>
                                    <tr id="filter-row" style="display:none;">
                                        <td data-col="check" class="sticky-col" style="left:0;"></td>
                                        <td data-col="color" class="sticky-col" style="left:25px;"></td>
                                        <td data-col="warehouse" class="sticky-col" style="left:55px;"><input class="filter-input" data-col-idx="2" placeholder="Warehouse..." oninput="applyFilters()"></td>
                                        <td data-col="sku"><input class="filter-input" data-col-idx="3" placeholder="SKU..." oninput="applyFilters()"></td>
                                        <td data-col="item_name"><input class="filter-input" data-col-idx="4" placeholder="Product Name..." oninput="applyFilters()"></td>
                                        <td data-col="description"><input class="filter-input" data-col-idx="5" placeholder="Description..." oninput="applyFilters()"></td>
                                        <td data-col="unit"><input class="filter-input" data-col-idx="6" placeholder="Unit..." oninput="applyFilters()"></td>
                                        <td data-col="on_hand_qty" colspan="5"></td>
                                    </tr>
                                </thead>
                                <tbody id="grid-body">
                                @forelse($items as $item)
                                    <tr id="row-{{ $item->id }}" data-id="{{ $item->id }}"
                                        data-customer-id="{{ $item->customer_id }}" data-vendor-id="{{ $item->vendor_id }}"
                                        data-warehouse-id="{{ $item->warehouse_id }}" data-sku="{{ addslashes($item->sku) }}"
                                        data-item-name="{{ addslashes($item->item_name) }}" data-description="{{ addslashes($item->description ?? '') }}"
                                        data-upc-ean="{{ addslashes($item->upc_ean ?? '') }}" data-mpn="{{ addslashes($item->mpn ?? '') }}"
                                        data-hts-code="{{ addslashes($item->hts_code ?? '') }}" data-unit-id="{{ $item->unit_id }}"
                                        data-on-hand-qty="{{ $item->on_hand_qty }}" data-available-qty="{{ $item->available_qty }}"
                                        data-weight-kg="{{ $item->weight_kg }}" data-volume-cbm="{{ $item->volume_cbm }}"
                                        data-inner-pack="{{ $item->inner_pack }}"
                                        data-dimension-length="{{ $item->dimension_length }}" data-dimension-width="{{ $item->dimension_width }}"
                                        data-dimension-height="{{ $item->dimension_height }}" data-dimension-unit="{{ $item->dimension_unit ?? 'cm' }}"
                                        data-remark="{{ addslashes($item->remark ?? '') }}"
                                        data-create-date="{{ $item->create_date ? $item->create_date->format('Y-m-d') : '' }}"
                                        data-status="{{ $item->status ?? 'enable' }}"
                                        onclick="rowClick(event, this)">
                                        <td class="sticky-col" style="width:25px;text-align:center;" onclick="event.stopPropagation()">
                                            <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="row-check" onchange="updateToolbar()">
                                        </td>
                                        <td class="sticky-col" style="width:30px;left:25px;text-align:center;">
                                            <span class="color-mark" style="background:{{ $item->color ?? '#94a3b8' }}" title="Click to change color" onclick="event.stopPropagation();openColorPicker({{ $item->id }}, '{{ $item->color ?? '' }}')"></span>
                                        </td>
                                        <td class="sticky-col" style="width:130px;left:55px;">{{ $item->warehouse->name ?? '' }}</td>
                                        <td><a href="javascript:;" class="col-link" data-id="{{ $item->id }}" onclick="event.stopPropagation();openEditModal(this)">{{ $item->sku }}</a></td>
                                        <td>{{ $item->item_name }}</td>
                                        <td style="font-size:9px;color:#64748b;">{{ Str::limit($item->description, 60) }}</td>
                                        <td>{{ $item->unit->name ?? '' }}</td>
                                        <td style="text-align:right;">{{ number_format($item->on_hand_qty, 2) }}</td>
                                        <td style="text-align:right;">{{ number_format($item->available_qty, 2) }}</td>
                                        <td style="text-align:right;">{{ number_format($item->weight_kg, 2) }}</td>
                                        <td style="text-align:right;">{{ number_format($item->volume_cbm, 2) }}</td>
                                        <td>{{ $item->created_at->format('m-d-Y') }}</td>
                                    </tr>
                                @empty
                                    <tr id="empty-row">
                                        <td colspan="50" style="text-align:center;padding:30px 10px;color:#94a3b8;">
                                            <i class="fa fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                                            No inventory items found.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>

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
    function confirmDelete() { const n = document.querySelectorAll('.row-check:checked').length; if (!n) return; document.getElementById('confirm-msg').textContent = `Delete ${n} item(s)? This cannot be undone.`; document.getElementById('confirm-overlay').classList.add('open'); }
    function closeConfirm() { document.getElementById('confirm-overlay').classList.remove('open'); }
    function executeDelete() {
        closeConfirm();
        const ids = [...document.querySelectorAll('.row-check:checked')].map(cb => cb.value);
        if (!ids.length) return;
        showToast('info', 'Deleting...');
        fetch('{{ route("items.bulk-delete") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
            body: JSON.stringify({ ids })
        }).then(r => r.json()).then(d => {
            if (d.success) {
                showToast('success', d.message);
                updateGrid(window.location.href);
            } else showToast('error', d.message || 'Failed');
        }).catch(() => showToast('error', 'Failed to delete'));
    }

    /* FILTER */
    let filterOpen = false;
    function toggleFilter() {
        filterOpen = !filterOpen;
        const row = document.getElementById('filter-row');
        if (row) { row.style.display = filterOpen ? 'table-row' : 'none'; }
        document.getElementById('btn-filter').classList.toggle('active', filterOpen);

        if (filterOpen) {
            const urlParams = new URLSearchParams(window.location.search);
            document.querySelectorAll('#filter-row .filter-input').forEach(inp => {
                const idx = parseInt(inp.dataset.colIdx);
                if (idx === 2) inp.value = urlParams.get('filter_warehouse') || '';
                else if (idx === 3) inp.value = urlParams.get('filter_sku') || '';
                else if (idx === 4) inp.value = urlParams.get('filter_item_name') || '';
                else if (idx === 5) inp.value = urlParams.get('filter_description') || '';
                else if (idx === 6) inp.value = urlParams.get('filter_unit') || '';
            });
            document.querySelector('.filter-input')?.focus();
        } else {
            document.querySelectorAll('#filter-row .filter-input').forEach(i => { i.value = ''; });
            applyFilters();
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
            document.querySelectorAll('[id^="sort-"]').forEach(i => i.className = 'fa fa-sort');
            const sortIcon = document.getElementById('sort-' + newSort);
            if (sortIcon) { sortIcon.className = 'fa ' + (newDir === 'asc' ? 'fa-sort-asc' : 'fa-sort-desc'); }
            /* Update clear-search visibility */
            const searchVal = urlObj.searchParams.get('search') || '';
            document.getElementById('clear-search-btn').style.display = searchVal ? 'inline' : 'none';
            /* Update Excel link */
            updateExcelLink();
            /* Restore filter row from URL */
            restoreFilterRow(urlObj);
            window.history.pushState({}, '', url);
            updateToolbar(); loadColumnConfig();
        } catch (e) { showToast('error', 'Failed to update grid'); }
        finally { hideLoading(); }
    }
    document.addEventListener('click', function(e) { const link = e.target.closest('.pagination a'); if (link) { e.preventDefault(); updateGrid(link.href); } });
    function getSelectedIds() { return [...document.querySelectorAll('.row-check:checked')].map(cb => cb.value); }

    /* POPSTATE */
    window.addEventListener('popstate', function() { updateGrid(window.location.href); });

    /* SORT */
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
    function refreshGrid() { updateGrid(window.location.href); }

    /* SEARCH */
    let searchDebounce;
    function quickSearch(val) { clearTimeout(searchDebounce); searchDebounce = setTimeout(() => { const q = val.trim(); const url = new URL(window.location.href); if (!q) url.searchParams.delete('search'); else url.searchParams.set('search', q); url.searchParams.delete('page'); updateGrid(url.toString()); }, 300); }

    /* FILTER ROW */
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
            const filterMap = { 2: 'filter_warehouse', 3: 'filter_sku', 4: 'filter_item_name', 5: 'filter_description', 6: 'filter_unit' };
            document.querySelectorAll('#filter-row .filter-input').forEach(inp => {
                const v = inp.value.trim();
                if (!v) return;
                const param = filterMap[inp.dataset.colIdx];
                if (param) url.searchParams.set(param, v);
            });
            updateGrid(url.toString());
        }, 300);
    }
    function restoreFilterRow(urlObj) {
        if (!filterOpen) return;
        document.querySelectorAll('#filter-row .filter-input').forEach(inp => {
            const idx = parseInt(inp.dataset.colIdx);
            const map = { 2: 'filter_warehouse', 3: 'filter_sku', 4: 'filter_item_name', 5: 'filter_description', 6: 'filter_unit' };
            const param = map[idx];
            inp.value = param ? (urlObj.searchParams.get(param) || '') : '';
        });
    }

    /* CONFIG */
    const PINNED_COLS = ['check', 'color', 'warehouse'];
    const CONFIG_STORAGE_KEY = 'warehouse_items_column_config';
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
        } catch(e){}
    }
    function toggleConfig() { const panel = document.getElementById('config-panel'); const open = panel.style.display === 'none'; panel.style.display = open ? 'block' : 'none'; document.getElementById('btn-config').classList.toggle('active', open); if (open) buildConfigPanel(); }
    function buildConfigPanel() { const container = document.getElementById('col-toggles'); container.innerHTML = ''; const hidden = getHiddenColumns(); document.querySelectorAll('#header-row th[data-col]').forEach(th => { if (PINNED_COLS.includes(th.dataset.col)) return; const label = document.createElement('label'); const cb = document.createElement('input'); cb.type = 'checkbox'; cb.checked = !hidden.includes(th.dataset.col); cb.onchange = () => toggleColumn(th.dataset.col, cb.checked); label.appendChild(cb); label.append(' ' + th.textContent.trim()); container.appendChild(label); }); }
    function getHiddenColumns() { const hidden = []; document.querySelectorAll('#header-row th[data-col]').forEach(th => { if (PINNED_COLS.includes(th.dataset.col)) return; if (th.style.display === 'none') hidden.push(th.dataset.col); }); return hidden; }
    function saveColumnConfig() { try { localStorage.setItem(CONFIG_STORAGE_KEY, JSON.stringify(getHiddenColumns())); } catch(e){} }
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
    const COLOR_OPTIONS = [ { label: 'Urgent', value: '#E08283' }, { label: 'Ready to bill', value: '#F3C200' }, { label: 'Ready to close', value: '#25A69A' }, { label: 'Postpone', value: '#4B77BE' }, { label: 'Freight Finalized', value: '#9B9B9B' } ];
    let _colorItemId = null;
    function openColorPicker(id, currentColor) { _colorItemId = id; const grid = document.getElementById('color-picker-grid'); grid.innerHTML = COLOR_OPTIONS.map(o => { const active = o.value === currentColor; return '<div class="color-picker-opt ' + (active ? 'active' : '') + '" onclick="selectColor(\'' + o.value + '\', this)"><span class="swatch" style="background:' + o.value + '"></span><span>' + o.label + '</span><i class="fa fa-check"></i></div>'; }).join(''); document.getElementById('color-picker-overlay').classList.add('open'); }
    function selectColor(color, el) { document.querySelectorAll('.color-picker-opt').forEach(c => c.classList.remove('active')); el.classList.add('active'); const id = _colorItemId; fetch('{{ route("items.update-color", "ID") }}'.replace('ID', id), { method: 'PATCH', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ color }) }).then(r => r.json()).then(d => { if (d.success) { const span = document.querySelector('#row-' + id + ' .color-mark'); if (span) span.style.background = color; showToast('success', 'Color updated'); } }).catch(() => showToast('error', 'Failed')); closeColorPicker(); }
    function closeColorPicker() { document.getElementById('color-picker-overlay').classList.remove('open'); _colorItemId = null; }
    function clearColor() { const id = _colorItemId; fetch('{{ route("items.update-color", "ID") }}'.replace('ID', id), { method: 'PATCH', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ color: '' }) }).then(r => r.json()).then(d => { if (d.success) { const span = document.querySelector('#row-' + id + ' .color-mark'); if (span) span.style.background = '#94a3b8'; showToast('success', 'Color cleared'); } }).catch(() => showToast('error', 'Failed')); closeColorPicker(); }

    /* ITEM MODAL CRUD — data-attribute approach */
    function openCreateModal() {
        document.getElementById('item-modal-title-text').textContent = 'Create Item';
        document.getElementById('item-form-method').value = 'POST';
        document.getElementById('item-form').action = '{{ route("items.store") }}';
        const fields = ['customer_id', 'vendor_id', 'warehouse_id', 'sku', 'item_name', 'upc_ean', 'mpn', 'hts_code',
            'description', 'inner_pack', 'unit_id', 'on_hand_qty', 'available_qty', 'weight_kg', 'volume_cbm',
            'dimension_length', 'dimension_width', 'dimension_height', 'remark', 'create_date', 'status'];
        fields.forEach(id => { const el = document.getElementById('item-' + id); if (el) el.value = ''; });
        ['inner_pack', 'on_hand_qty', 'available_qty', 'weight_kg', 'volume_cbm'].forEach(id => {
            const el = document.getElementById('item-' + id);
            if (el) el.value = '0';
        });
        const cmRadio = document.querySelector('input[name="dimension_unit"][value="cm"]');
        if (cmRadio) cmRadio.checked = true;
        const photoInput = document.getElementById('item-product_photo');
        if (photoInput) photoInput.value = '';
        document.getElementById('item-modal-overlay').classList.add('open');
    }
    function openEditModal(link) {
        const tr = link.closest('tr');
        if (!tr) return;
        const d = tr.dataset;
        document.getElementById('item-modal-title-text').textContent = 'Edit Item';
        document.getElementById('item-form-method').value = 'PUT';
        document.getElementById('item-form').action = '/warehouse/items/' + d.id;
        document.getElementById('item-customer_id').value = d.customerId || '';
        document.getElementById('item-vendor_id').value = d.vendorId || '';
        document.getElementById('item-warehouse_id').value = d.warehouseId || '';
        document.getElementById('item-sku').value = d.sku || '';
        document.getElementById('item-item_name').value = d.itemName || '';
        document.getElementById('item-upc_ean').value = d.upcEan || '';
        document.getElementById('item-mpn').value = d.mpn || '';
        document.getElementById('item-hts_code').value = d.htsCode || '';
        document.getElementById('item-description').value = d.description || '';
        document.getElementById('item-inner_pack').value = d.innerPack || '0';
        document.getElementById('item-unit_id').value = d.unitId || '';
        document.getElementById('item-on_hand_qty').value = d.onHandQty || '0';
        document.getElementById('item-available_qty').value = d.availableQty || '0';
        document.getElementById('item-weight_kg').value = d.weightKg || '0';
        document.getElementById('item-volume_cbm').value = d.volumeCbm || '0';
        document.getElementById('item-dimension_length').value = d.dimensionLength || '';
        document.getElementById('item-dimension_width').value = d.dimensionWidth || '';
        document.getElementById('item-dimension_height').value = d.dimensionHeight || '';
        document.getElementById('item-remark').value = d.remark || '';
        document.getElementById('item-create_date').value = d.createDate || '';
        document.getElementById('item-status').value = d.status || 'enable';
        const dimRadio = document.querySelector('input[name="dimension_unit"][value="' + (d.dimensionUnit || 'cm') + '"]');
        if (dimRadio) dimRadio.checked = true;
        const photoInput = document.getElementById('item-product_photo');
        if (photoInput) photoInput.value = '';
        document.getElementById('item-modal-overlay').classList.add('open');
    }
    function closeItemModal() { document.getElementById('item-modal-overlay').classList.remove('open'); }

    /* TOAST */
    function showToast(type, msg) { const icons = { success: 'check-circle', error: 'times-circle', info: 'info-circle' }; const t = document.createElement('div'); t.className = 'toast ' + type; t.innerHTML = '<i class="fa fa-' + (icons[type] || 'info-circle') + '"></i> <span>' + msg + '</span>'; document.getElementById('toast-container').appendChild(t); setTimeout(() => t.remove(), 4000); }

    /* INIT */
    (function() {
        const params = new URLSearchParams(window.location.search);
        const sort = params.get('sort') || 'created_at';
        const dir = params.get('dir') || 'desc';
        const icon = document.getElementById('sort-' + sort);
        if (icon) { icon.className = 'fa ' + (dir === 'asc' ? 'fa-sort-asc' : 'fa-sort-desc'); }
        loadColumnConfig();
        updateExcelLink();
        /* Open filter row if any filter is active */
        const hasFilter = ['search', 'filter_warehouse', 'filter_sku', 'filter_item_name', 'filter_description', 'filter_unit'].some(k => params.has(k) && params.get(k));
        if (hasFilter) { filterOpen = true; document.getElementById('filter-row').style.display = 'table-row'; document.getElementById('btn-filter').classList.add('active'); restoreFilterRow(params); }
    })();

    @if(session('success')) showToast('success', @json(session('success'))); @endif
    @if(session('error')) showToast('error', @json(session('error'))); @endif
    </script>
    @endpush
</x-layout>
