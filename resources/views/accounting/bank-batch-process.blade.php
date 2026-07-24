<x-layout>
    @push('styles')
    <style>
        .page-content { padding: 8px 12px; background: #eef1f5; min-height: calc(100vh - 50px); font-family: 'Inter', 'Open Sans', sans-serif !important; }
        .page-bar { background-color: #fff; padding: 8px 20px; margin-bottom: 15px; border: 1px solid #e9ebec; border-radius: 4px; }
        .page-breadcrumb { list-style: none; padding: 0; margin: 0; display: flex; align-items: center; }
        .page-breadcrumb li { font-size: 12px; color: #888; display: flex; align-items: center; }
        .page-breadcrumb li a { color: #337ab7; text-decoration: none; }
        .page-breadcrumb li i { margin: 0 8px; font-size: 10px; opacity: 0.5; }

        .portlet.light { background-color: #fff; border: 1px solid #cbd5e1; border-radius: 2px; margin-bottom: 10px !important; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .portlet-title { padding: 4px 10px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; min-height: 28px; background: #f8fafc; }
        .portlet-body { padding: 12px 14px; }
        .caption-subject { color: #1e293b; font-size: 12px; font-weight: 700; text-transform: uppercase; }

        .form-label-box { background: #eef1f5; width: 120px; padding: 4px 8px; font-size: 11px; color: #333; min-height: 24px; display: flex; align-items: center; flex-shrink: 0; font-weight: 600; }
        .form-input-box { flex-grow: 1; padding-left: 10px; display: flex; align-items: center; flex-wrap: wrap; gap: 10px; }
        .form-group-row { display: flex; align-items: center; margin-bottom: 8px; }
        .form-control-gf { height: 24px; border: 1px solid #c2cad8; padding: 2px 6px; font-size: 11px; border-radius: 2px !important; width: 100%; max-width: 220px; color: #333; outline: none; background: #fff; }
        .form-control-gf:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,0.1); }
        select.form-control-gf { appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 4px center; background-size: 8px; padding-right: 16px; }

        .report-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 40px; }
        @media (max-width: 900px) { .report-grid { grid-template-columns: 1fr; } }

        .button-row { display: flex; justify-content: center; gap: 8px; margin-top: 16px; }
        .btn-action-round { background: #64748b; color: #fff; border: 1px solid #475569; border-radius: 2px; padding: 0 8px; height: 20px; font-size: 10px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 4px; cursor: pointer; text-decoration: none; transition: all 0.15s; box-sizing: border-box; }
        .btn-action-round:hover { background: #475569; color: #fff; }
        .btn-action-round.primary { background: #3b82f6; border-color: #2563eb; }
        .btn-action-round.primary:hover { background: #2563eb; }
        .btn-action-round.green { background: #22c55e; border-color: #16a34a; }
        .btn-action-round.green:hover { background: #16a34a; }
        .btn-action-round.red { background: #ef4444; border-color: #dc2626; }
        .btn-action-round.red:hover { background: #dc2626; }
        .btn-action-round:disabled { opacity: 0.5; cursor: not-allowed; }

        .radio-item { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; color: #333; cursor: pointer; }
        .radio-item input[type="radio"], .radio-item input[type="checkbox"] { margin: 0; cursor: pointer; width: 12px; height: 12px; accent-color: #3b82f6; }
        .text-danger { color: #e73d4a; }
        .fw-600 { font-weight: 600; }
        .num { text-align: right; font-family: 'Courier New', monospace; }
        .center { text-align: center; }

        .grid-container { width: 100%; overflow-x: auto; }
        .grid-table { border-collapse: collapse; width: 100%; font-size: 11px; }
        .grid-table th { background: #f8fafc; color: #475569; font-weight: 600; border: 1px solid #e2e8f0; padding: 4px 6px; white-space: nowrap; text-align: left; }
        .grid-table th.num { text-align: right; }
        .grid-table td { padding: 3px 6px; border: 1px solid #e2e8f0; white-space: nowrap; color: #334155; }
        .grid-table td.num { text-align: right; font-family: 'Courier New', monospace; }
        .grid-table td.center { text-align: center; }
        .grid-table tbody tr:hover { background: #f1f5f9; }
        .grid-table .total-row { background: #f8fafc !important; font-weight: 700; }
        .grid-table .total-row td { border-top: 2px solid #475569; color: #0f172a; }
        .grid-table tbody tr.selected { background: #eff6ff; }

        .pagination-bar { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; font-size: 11px; color: #64748b; }
        .pagination { display: flex; list-style: none; padding: 0; margin: 0; border: 1px solid #e2e8f0; border-radius: 2px; }
        .pagination li { border-right: 1px solid #e2e8f0; }
        .pagination li:last-child { border-right: none; }
        .pagination li a { display: block; padding: 3px 8px; text-decoration: none; color: #3b82f6; font-size: 11px; }
        .pagination li.active a { background: #f1f5f9; color: #64748b; cursor: default; }
        .pagination li.disabled a { color: #cbd5e1; cursor: not-allowed; }

        .empty-state { text-align: center; padding: 40px 20px; color: #94a3b8; }
        .empty-state i { font-size: 36px; display: block; margin-bottom: 12px; }
        .loading-overlay { text-align: center; padding: 30px; color: #64748b; }
        .loading-overlay i { font-size: 20px; animation: spin 1s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        .toast-container { position: fixed; top: 56px; right: 16px; z-index: 9999; display: flex; flex-direction: column; gap: 6px; pointer-events: none; }
        .toast { background: #1e293b; color: #fff; padding: 8px 14px; border-radius: 4px; font-size: 11px; box-shadow: 0 4px 16px rgba(0,0,0,0.25); display: flex; align-items: center; gap: 8px; animation: toastIn 0.25s ease; pointer-events: all; }
        .toast.success { border-left: 3px solid #22c55e; }
        .toast.error { border-left: 3px solid #ef4444; }
        .toast.info { border-left: 3px solid #3b82f6; }
        @keyframes toastIn { from { transform: translateX(40px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        .modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9998; display: flex; align-items: center; justify-content: center; }
        .modal-box { background: #fff; border: 1px solid #cbd5e1; border-radius: 4px; width: 90%; max-width: 900px; max-height: 80vh; overflow-y: auto; box-shadow: 0 8px 30px rgba(0,0,0,0.2); }
        .modal-header { padding: 8px 12px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; }
        .modal-header h3 { font-size: 12px; font-weight: 700; text-transform: uppercase; color: #1e293b; }
        .modal-close { background: none; border: none; font-size: 16px; color: #64748b; cursor: pointer; padding: 4px; }
        .modal-close:hover { color: #1e293b; }
        .modal-body { padding: 12px; }
        .modal-footer { padding: 8px 12px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 8px; background: #f8fafc; }
    </style>
    @endpush

    <div class="toast-container" id="toast-container"></div>

    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a></li>
                <li><i class="fa fa-angle-right"></i>Accounting</li>
                <li><i class="fa fa-angle-right"></i>Bank</li>
                <li><i class="fa fa-angle-right"></i><span style="color: #333; font-weight: 700;">Batch Process</span></li>
            </ul>
        </div>

        <div class="portlet light">
            <div class="portlet-title">
                <span class="caption-subject"><i class="fa fa-cogs" style="color:#3b82f6;margin-right:6px;"></i>Bank Batch Process</span>
                <button class="btn-action-round" onclick="exportExcel()"><i class="fa fa-file-excel-o"></i> Excel</button>
            </div>
            <div class="portlet-body">
                <div x-data="batchApp()" x-init="init()">
                    {{-- Operation Type --}}
                    <div class="form-group-row" style="margin-bottom: 12px;">
                        <div class="form-input-box" style="padding-left: 0; gap: 16px;">
                            <label class="radio-item"><input type="radio" value="Deposit" x-model="operationType" @change="resetSearch()"> Deposit</label>
                            <label class="radio-item"><input type="radio" value="Clear Check" x-model="operationType" @change="resetSearch()"> Clear Check</label>
                            <label class="radio-item"><input type="radio" value="Cancel Deposit" x-model="operationType" @change="resetSearch()"> Cancel Deposit</label>
                            <label class="radio-item"><input type="radio" value="Cancel Clear" x-model="operationType" @change="resetSearch()"> Cancel Clear</label>
                        </div>
                    </div>

                    <div class="report-grid">
                        {{-- Post Date --}}
                        <div>
                            <div class="form-group-row">
                                <div class="form-label-box"><span class="text-danger">*</span>Post Date</div>
                                <div class="form-input-box">
                                    <input type="date" class="form-control-gf" style="width: 150px;" x-model="postDate" :disabled="postDateType === 'today'">
                                    <label class="radio-item" style="margin-left: 8px;"><input type="radio" value="today" x-model="postDateType" @change="setToday()"> As of Today</label>
                                </div>
                            </div>
                        </div>
                        <div></div>

                        {{-- Cancel Date (conditional) --}}
                        <div x-show="operationType === 'Cancel Deposit' || operationType === 'Cancel Clear'" x-cloak>
                            <div class="form-group-row">
                                <div class="form-label-box" x-text="operationType === 'Cancel Clear' ? 'Clear Date' : 'Deposit Date'"></div>
                                <div class="form-input-box">
                                    <input type="date" class="form-control-gf" style="width: 150px;" x-model="actionDate">
                                </div>
                            </div>
                        </div>
                        <div x-show="operationType === 'Cancel Deposit' || operationType === 'Cancel Clear'" x-cloak></div>

                        {{-- Office --}}
                        <div>
                            <div class="form-group-row">
                                <div class="form-label-box">Office</div>
                                <div class="form-input-box">
                                    <select class="form-control-gf" x-model="officeId">
                                        <option value="">All</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}">{{ $office->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Bank --}}
                        <div>
                            <div class="form-group-row">
                                <div class="form-label-box">Bank</div>
                                <div class="form-input-box">
                                    <select class="form-control-gf" x-model="bankName">
                                        <option value="">All</option>
                                        @foreach($bankNames as $name)
                                            <option value="{{ $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="button-row">
                        <button class="btn-action-round primary" @click="searchPayments()" :disabled="searching">
                            <i class="fa" :class="searching ? 'fa-spinner fa-spin' : 'fa-search'"></i> Search
                        </button>
                        <button class="btn-action-round green" @click="executeBatch()" :disabled="!payments.length || executing || !selectedIds.length">
                            <i class="fa" :class="executing ? 'fa-spinner fa-spin' : 'fa-play'"></i> Process (<span x-text="selectedIds.length"></span>)
                        </button>
                    </div>

                    {{-- Search Results --}}
                    <div x-show="payments.length || searched" style="margin-top: 12px;">
                        <div style="font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;margin-bottom:8px;letter-spacing:0.3px;">
                            <span x-text="operationType"></span> — <span x-text="payments.length"></span> payment(s) found, Total: <span x-text="fmt(totalAmount)"></span>
                        </div>
                        <div class="grid-container" x-show="payments.length">
                            <table class="grid-table">
                                <thead>
                                    <tr>
                                        <th style="width:30px;text-align:center;"><input type="checkbox" @change="toggleAll($event)" :checked="selectedIds.length === payments.length && payments.length > 0" style="accent-color:#3b82f6;"></th>
                                        <th>Payment No.</th>
                                        <th>Payment Date</th>
                                        <th>Partner</th>
                                        <th>Currency</th>
                                        <th class="num">Amount</th>
                                        <th class="center">Office</th>
                                        <th>Bank</th>
                                        <th>Check No.</th>
                                        <th x-show="operationType.startsWith('Cancel')">Clear Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="p in payments" :key="p.id">
                                        <tr :class="selectedIds.includes(p.id) ? 'selected' : ''">
                                            <td class="center"><input type="checkbox" :value="p.id" :checked="selectedIds.includes(p.id)" @change="toggleSelect(p.id)" style="accent-color:#3b82f6;"></td>
                                            <td class="fw-600" x-text="p.payment_no"></td>
                                            <td x-text="p.payment_date"></td>
                                            <td x-text="p.trade_partner"></td>
                                            <td x-text="p.currency"></td>
                                            <td class="num" x-text="fmt(p.amount)"></td>
                                            <td class="center" x-text="p.office"></td>
                                            <td x-text="p.bank_name"></td>
                                            <td x-text="p.check_no"></td>
                                            <td x-show="operationType.startsWith('Cancel')" x-text="p.clear_date"></td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot>
                                    <tr class="total-row">
                                        <td></td>
                                        <td x-text="'Total (' + payments.length + ')'"></td>
                                        <td colspan="3"></td>
                                        <td class="num" x-text="fmt(totalAmount)"></td>
                                        <td colspan="4"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div x-show="searched && !payments.length" class="empty-state" style="padding:20px;">
                            <i class="fa fa-inbox"></i>
                            <div>No payments found matching the criteria.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- LOG SECTION --}}
        <div class="portlet light">
            <div class="portlet-title">
                <span class="caption-subject"><i class="fa fa-list-alt" style="color:#22c55e;margin-right:6px;"></i>Log</span>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:10px;color:#64748b;">Show</span>
                    <select class="form-control-gf" style="width:50px;max-width:50px;" x-model="logPerPage" @change="loadLog()">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
            <div class="portlet-body">
                <div id="log-loading" class="loading-overlay" style="display:none;">
                    <i class="fa fa-spinner"></i>
                    <div style="margin-top:8px;">Loading log...</div>
                </div>
                <div class="grid-container">
                    <table class="grid-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Operation</th>
                                <th>Action</th>
                                <th>Post Date</th>
                                <th>Office</th>
                                <th>Bank</th>
                                <th>Deposit/Clear Date</th>
                                <th class="num">Amount</th>
                                <th class="center">Detail</th>
                            </tr>
                        </thead>
                        <tbody id="log-tbody">
                            <tr><td colspan="9" class="empty-state" style="padding:20px;">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="pagination-bar" id="log-pagination"></div>
            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div id="detail-modal" class="modal-backdrop" style="display:none;" onclick="if(event.target===this)closeDetailModal()">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Batch Detail</h3>
                <button class="modal-close" onclick="closeDetailModal()"><i class="fa fa-times"></i></button>
            </div>
            <div class="modal-body" id="detail-body">
                <div class="loading-overlay"><i class="fa fa-spinner"></i></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function showToast(type, msg) {
        var icons = { success: 'check-circle', error: 'times-circle', info: 'info-circle' };
        var t = document.createElement('div');
        t.className = 'toast ' + type;
        t.innerHTML = '<i class="fa fa-' + (icons[type] || 'info-circle') + '"></i> ' + msg;
        document.getElementById('toast-container').appendChild(t);
        setTimeout(function() { t.remove(); }, 3000);
    }

    function fmt(val) {
        if (val === undefined || val === null) return '0.00';
        return parseFloat(val).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function batchApp() {
        return {
            operationType: 'Deposit',
            postDateType: 'specific',
            postDate: '{{ date("Y-m-d") }}',
            actionDate: '',
            officeId: '',
            bankName: '',
            searching: false,
            executing: false,
            searched: false,
            payments: [],
            selectedIds: [],
            totalAmount: 0,
            logPerPage: 10,
            logPage: 1,

            init() {
                this.loadLog();
            },

            setToday() {
                this.postDate = '{{ date("Y-m-d") }}';
            },

            resetSearch() {
                this.payments = [];
                this.selectedIds = [];
                this.totalAmount = 0;
                this.searched = false;
            },

            searchPayments() {
                if (!this.postDate) { showToast('error', 'Please select a post date.'); return; }
                if ((this.operationType === 'Cancel Deposit' || this.operationType === 'Cancel Clear') && !this.actionDate) {
                    showToast('error', 'Please select an action date.'); return;
                }
                this.searching = true;
                this.selectedIds = [];

                var fd = new FormData();
                fd.append('operation_type', this.operationType);
                fd.append('post_date', this.postDate);
                if (this.actionDate) fd.append('action_date', this.actionDate);
                if (this.officeId) fd.append('office_id', this.officeId);
                if (this.bankName) fd.append('bank_name', this.bankName);

                fetch('{{ route("accounting.bank.batch-process.search") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: fd,
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    this.searching = false;
                    if (!data.success) { showToast('error', data.message || 'Failed'); return; }
                    this.payments = data.payments;
                    this.totalAmount = data.total_amount;
                    this.searched = true;
                }.bind(this))
                .catch(function() {
                    this.searching = false;
                    showToast('error', 'Failed to search payments');
                }.bind(this));
            },

            toggleAll(event) {
                if (event.target.checked) {
                    this.selectedIds = this.payments.map(function(p) { return p.id; });
                } else {
                    this.selectedIds = [];
                }
            },

            toggleSelect(id) {
                var idx = this.selectedIds.indexOf(id);
                if (idx > -1) {
                    this.selectedIds = this.selectedIds.filter(function(i) { return i !== id; });
                } else {
                    this.selectedIds = this.selectedIds.concat([id]);
                }
            },

            executeBatch() {
                if (!this.selectedIds.length) { showToast('error', 'Select payments to process.'); return; }
                if (!this.postDate) { showToast('error', 'Post date required.'); return; }
                if ((this.operationType === 'Cancel Deposit' || this.operationType === 'Cancel Clear') && !this.actionDate) {
                    showToast('error', 'Action date required.'); return;
                }
                if (!confirm('Process ' + this.selectedIds.length + ' payment(s) as ' + this.operationType + '?')) return;

                this.executing = true;
                var fd = new FormData();
                fd.append('operation_type', this.operationType);
                fd.append('post_date', this.postDate);
                if (this.actionDate) fd.append('action_date', this.actionDate);
                if (this.officeId) {
                    var sel = document.querySelector('select[x-model="officeId"]');
                    fd.append('office', sel.options[sel.selectedIndex].text);
                }
                if (this.bankName) fd.append('bank_name', this.bankName);
                this.selectedIds.forEach(function(id) { fd.append('payment_ids[]', id); });

                fetch('{{ route("accounting.bank.batch-process.execute") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: fd,
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    this.executing = false;
                    if (!data.success) { showToast('error', data.message || 'Failed'); return; }
                    showToast('success', data.message);
                    this.payments = [];
                    this.selectedIds = [];
                    this.totalAmount = 0;
                    this.searched = false;
                    this.loadLog();
                }.bind(this))
                .catch(function() {
                    this.executing = false;
                    showToast('error', 'Failed to execute batch');
                }.bind(this));
            },

            loadLog() {
                var el = document.getElementById('log-loading');
                if (el) el.style.display = 'block';

                var fd = new FormData();
                fd.append('page', this.logPage);
                fd.append('per_page', this.logPerPage);

                fetch('{{ route("accounting.bank.batch-process.log") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: fd,
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (el) el.style.display = 'none';
                    if (!data.success) return;
                    this.renderLog(data);
                }.bind(this))
                .catch(function() {
                    if (el) el.style.display = 'none';
                });
            },

            renderLog(data) {
                var tbody = document.getElementById('log-tbody');
                var pag = document.getElementById('log-pagination');
                if (!data.logs.length) {
                    tbody.innerHTML = '<tr><td colspan="9" class="empty-state" style="padding:20px;"><i class="fa fa-inbox"></i><div>No batch logs yet.</div></td></tr>';
                    pag.innerHTML = '';
                    return;
                }

                var html = '';
                data.logs.forEach(function(log) {
                    html += '<tr>';
                    html += '<td>' + esc(log.date) + '</td>';
                    html += '<td class="fw-600">' + esc(log.operation) + '</td>';
                    html += '<td>' + esc(log.action) + '</td>';
                    html += '<td>' + esc(log.post_date) + '</td>';
                    html += '<td>' + esc(log.office) + '</td>';
                    html += '<td>' + esc(log.bank_name) + '</td>';
                    html += '<td>' + esc(log.action_date) + '</td>';
                    html += '<td class="num">' + fmt(log.amount) + '</td>';
                    html += '<td class="center"><a href="#" onclick="showDetail(' + log.id + '); return false;" style="color:#3b82f6;font-size:14px;"><i class="fa fa-file-text-o"></i></a></td>';
                    html += '</tr>';
                });
                tbody.innerHTML = html;

                var paginationHtml = '<div>Showing ' + ((data.page - 1) * data.per_page + 1) + ' to ' + Math.min(data.page * data.per_page, data.total) + ' of ' + data.total + ' records</div>';
                paginationHtml += '<ul class="pagination">';
                paginationHtml += '<li' + (data.page <= 1 ? ' class="disabled"' : '') + '><a href="#" onclick="goLogPage(' + (data.page - 1) + '); return false;">&laquo;</a></li>';
                for (var i = 1; i <= data.total_pages; i++) {
                    paginationHtml += '<li' + (i === data.page ? ' class="active"' : '') + '><a href="#" onclick="goLogPage(' + i + '); return false;">' + i + '</a></li>';
                }
                paginationHtml += '<li' + (data.page >= data.total_pages ? ' class="disabled"' : '') + '><a href="#" onclick="goLogPage(' + (data.page + 1) + '); return false;">&rsaquo;</a></li>';
                paginationHtml += '</ul>';
                pag.innerHTML = paginationHtml;
            }
        };
    }

    function esc(text) {
        if (!text) return '--';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function goLogPage(page) {
        var app = Alpine.$data(document.querySelector('[x-data]'));
        if (page < 1) return;
        app.logPage = page;
        app.loadLog();
    }

    function showDetail(logId) {
        var modal = document.getElementById('detail-modal');
        var body = document.getElementById('detail-body');
        modal.style.display = 'flex';
        body.innerHTML = '<div class="loading-overlay"><i class="fa fa-spinner"></i><div style="margin-top:8px;">Loading...</div></div>';

        var fd = new FormData();
        fd.append('log_id', logId);

        fetch('{{ route("accounting.bank.batch-process.log-detail") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: fd,
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (!data.success) { body.innerHTML = '<div class="empty-state">Failed to load detail.</div>'; return; }
            var log = data.log;
            var payments = data.payments;

            var html = '<div style="margin-bottom:12px;display:grid;grid-template-columns:1fr 1fr;gap:6px 20px;font-size:11px;">';
            html += '<div><strong>Action:</strong> ' + esc(log.operation_type) + '</div>';
            html += '<div><strong>Operator:</strong> ' + esc(log.user) + '</div>';
            html += '<div><strong>Post Date:</strong> ' + esc(log.post_date) + '</div>';
            html += '<div><strong>Action Date:</strong> ' + esc(log.action_date || '--') + '</div>';
            html += '<div><strong>Office:</strong> ' + esc(log.office) + '</div>';
            html += '<div><strong>Bank:</strong> ' + esc(log.bank_name || 'All') + '</div>';
            html += '<div><strong>Processed:</strong> ' + log.payment_count + ' payment(s)</div>';
            html += '<div><strong>Total:</strong> ' + fmt(log.total_amount) + '</div>';
            html += '<div><strong>Date:</strong> ' + esc(log.created_at) + '</div>';
            html += '</div>';

            if (payments.length) {
                html += '<div class="grid-container"><table class="grid-table"><thead><tr>';
                html += '<th>Payment No.</th><th>Date</th><th>Partner</th><th>Currency</th><th class="num">Amount</th><th class="center">Office</th><th>Bank</th><th>Check No.</th><th>Clear Date</th>';
                html += '</tr></thead><tbody>';
                payments.forEach(function(p) {
                    html += '<tr>';
                    html += '<td class="fw-600">' + esc(p.payment_no) + '</td>';
                    html += '<td>' + esc(p.payment_date) + '</td>';
                    html += '<td>' + esc(p.trade_partner) + '</td>';
                    html += '<td>' + esc(p.currency) + '</td>';
                    html += '<td class="num">' + fmt(p.amount) + '</td>';
                    html += '<td class="center">' + esc(p.office) + '</td>';
                    html += '<td>' + esc(p.bank_name) + '</td>';
                    html += '<td>' + esc(p.check_no) + '</td>';
                    html += '<td>' + esc(p.clear_date) + '</td>';
                    html += '</tr>';
                });
                html += '</tbody></table></div>';
            } else {
                html += '<div class="empty-state" style="padding:16px;">No payment details available.</div>';
            }

            body.innerHTML = html;
        })
        .catch(function() {
            body.innerHTML = '<div class="empty-state">Failed to load detail.</div>';
        });
    }

    function closeDetailModal() {
        document.getElementById('detail-modal').style.display = 'none';
    }

    function exportExcel() {
        window.location.href = '{{ route("accounting.bank.batch-process.export-excel") }}';
    }
    </script>
    @endpush
</x-layout>
