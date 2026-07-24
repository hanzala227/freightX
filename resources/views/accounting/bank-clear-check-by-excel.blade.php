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
        .portlet-title { padding: 4px 10px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; min-height: 28px; background: #f8fafc; cursor: pointer; user-select: none; }
        .portlet-title .caption-subject { display: flex; align-items: center; gap: 6px; }
        .portlet-body { padding: 12px 14px; }
        .caption-subject { color: #1e293b; font-size: 12px; font-weight: 700; text-transform: uppercase; }

        .section-label { font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
        .section-label i { color: #64748b; font-size: 10px; }

        .form-label-box { background: #eef1f5; width: 100px; padding: 4px 8px; font-size: 11px; color: #333; min-height: 24px; display: flex; align-items: center; flex-shrink: 0; font-weight: 600; }
        .form-input-box { flex-grow: 1; padding-left: 10px; display: flex; align-items: center; flex-wrap: wrap; gap: 10px; }
        .form-group-row { display: flex; align-items: center; margin-bottom: 8px; }
        .form-control-gf { height: 24px; border: 1px solid #c2cad8; padding: 2px 6px; font-size: 11px; border-radius: 2px !important; width: 100%; max-width: 220px; color: #333; outline: none; background: #fff; }
        .form-control-gf:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,0.1); }
        select.form-control-gf { appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 4px center; background-size: 8px; padding-right: 16px; }

        .button-row { display: flex; justify-content: center; gap: 8px; margin-top: 16px; }
        .btn-action-round { background: #64748b; color: #fff; border: 1px solid #475569; border-radius: 2px; padding: 0 8px; height: 20px; font-size: 10px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 4px; cursor: pointer; text-decoration: none; transition: all 0.15s; box-sizing: border-box; }
        .btn-action-round:hover { background: #475569; color: #fff; }
        .btn-action-round.primary { background: #3b82f6; border-color: #2563eb; }
        .btn-action-round.primary:hover { background: #2563eb; }
        .btn-action-round.green { background: #22c55e; border-color: #16a34a; }
        .btn-action-round.green:hover { background: #16a34a; }
        .btn-action-round:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn-add-files { background: #2ab4c0; color: #fff; border: 1px solid #2ab4c0; border-radius: 2px; padding: 0 12px; height: 24px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 4px; cursor: pointer; transition: all 0.15s; }
        .btn-add-files:hover { background: #239da8; }
        .btn-apply { background: #2ab4c0; color: #fff; border: 1px solid #2ab4c0; border-radius: 2px; padding: 6px 24px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 4px; cursor: pointer; transition: all 0.15s; }
        .btn-apply:hover { background: #239da8; }

        .radio-item { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; color: #333; cursor: pointer; }
        .radio-item input[type="radio"], .radio-item input[type="checkbox"] { margin: 0; cursor: pointer; width: 12px; height: 12px; accent-color: #3b82f6; }
        .text-danger { color: #e73d4a; }
        .fw-600 { font-weight: 600; }
        .num { text-align: right; font-family: 'Courier New', monospace; }
        .center { text-align: center; }

        .grid-container { width: 100%; overflow-x: auto; }
        .grid-table { border-collapse: collapse; width: 100%; font-size: 11px; }
        .grid-table th { background: #6b7280; color: #fff; font-weight: 600; border: 1px solid #9ca3af; padding: 4px 6px; white-space: nowrap; text-align: left; }
        .grid-table th.num { text-align: right; }
        .grid-table td { padding: 3px 6px; border: 1px solid #e2e8f0; white-space: nowrap; color: #334155; }
        .grid-table td.num { text-align: right; font-family: 'Courier New', monospace; }
        .grid-table td.center { text-align: center; }
        .grid-table tbody tr:hover { background: #f1f5f9; }
        .grid-table tbody tr.selected { background: #eff6ff; }
        .grid-table tbody tr.unmatched { background: #fef2f2; }
        .grid-table .total-row { background: #f8fafc !important; font-weight: 700; }
        .grid-table .total-row td { border-top: 2px solid #475569; color: #0f172a; }

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

        .upload-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; }
        .file-input-hidden { display: none; }
        .file-label { font-size: 11px; color: #64748b; }
        .file-label strong { color: #334155; }
    </style>
    @endpush

    <div class="toast-container" id="toast-container"></div>

    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a></li>
                <li><i class="fa fa-angle-right"></i>Accounting</li>
                <li><i class="fa fa-angle-right"></i>Bank</li>
                <li><i class="fa fa-angle-right"></i><span style="color: #333; font-weight: 700;">Clear Check by Excel</span></li>
            </ul>
        </div>

        <div x-data="clearCheckApp()" x-init="init()">
            {{-- Main Panel --}}
            <div class="portlet light">
                <div class="portlet-title" @click="panelOpen = !panelOpen">
                    <span class="caption-subject"><i class="fa fa-check-square-o" style="color:#3b82f6;"></i> Clear Check by Excel</span>
                    <i class="fa" :class="panelOpen ? 'fa-chevron-up' : 'fa-chevron-down'" style="color:#64748b;font-size:12px;"></i>
                </div>
                <div class="portlet-body" x-show="panelOpen" x-transition>

                    {{-- UPLOAD FILE --}}
                    <div style="margin-bottom: 16px;">
                        <div class="section-label"><i class="fa fa-cloud-upload"></i> UPLOAD FILE</div>
                        <div class="upload-row">
                            <div class="form-group-row" style="margin-bottom:0;">
                                <div class="form-label-box"><span class="text-danger">*</span>Bank</div>
                                <div class="form-input-box">
                                    <select class="form-control-gf" x-model="bankName" style="width:200px;max-width:200px;">
                                        <option value="">Select Bank</option>
                                        @foreach($bankNames as $name)
                                            <option value="{{ $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button class="btn-add-files" @click="$refs.fileInput.click()">
                                <i class="fa fa-plus"></i> Add files
                            </button>
                            <input type="file" accept=".csv,.txt" x-ref="fileInput" @change="handleFile($event)" class="file-input-hidden">
                            <span class="file-label" x-show="uploadedFile">
                                <strong x-text="uploadedFile"></strong>
                                <a href="#" @click.prevent="clearFile()" style="color:#ef4444;margin-left:4px;"><i class="fa fa-times"></i></a>
                            </span>
                        </div>
                    </div>

                    {{-- PROCESS RESULT --}}
                    <div x-show="results.length" x-cloak>
                        <div class="section-label" style="border-top:1px solid #e2e8f0;padding-top:12px;"><i class="fa fa-cog"></i> PROCESS RESULT</div>

                        <div style="margin-bottom:8px;">
                            <label class="radio-item"><input type="checkbox" x-model="compareAbsolute" @change="toggleAbsolute()"> Compare by absolute value</label>
                        </div>

                        <div class="grid-container">
                            <table class="grid-table">
                                <thead>
                                    <tr>
                                        <th style="width:30px;text-align:center;"><input type="checkbox" @change="toggleAll($event)" :checked="selectedIds.length === matchedCount && matchedCount > 0" style="accent-color:#3b82f6;"></th>
                                        <th>Clear Date</th>
                                        <th>Check No.</th>
                                        <th class="num">Bank Amount</th>
                                        <th>Post Date</th>
                                        <th>Paid to</th>
                                        <th class="num">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(r, idx) in results" :key="idx">
                                        <tr :class="r.matched ? (selectedIds.includes(r.id) ? 'selected' : '') : 'unmatched'">
                                            <td class="center">
                                                <input type="checkbox" x-show="r.matched" :checked="selectedIds.includes(r.id)" @change="toggleSelect(r.id)" style="accent-color:#3b82f6;">
                                            </td>
                                            <td x-text="clearDateDisplay"></td>
                                            <td class="fw-600" x-text="r.check_no"></td>
                                            <td class="num" x-text="r.bank_amount !== '--' ? fmt(r.bank_amount) : '--'"></td>
                                            <td x-text="r.payment_date"></td>
                                            <td x-text="r.trade_partner"></td>
                                            <td class="num" x-text="typeof r.amount === 'number' ? fmt(r.amount) : r.amount"></td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot>
                                    <tr class="total-row">
                                        <td></td>
                                        <td>Total Count</td>
                                        <td x-text="results.length"></td>
                                        <td></td>
                                        <td>Selected</td>
                                        <td class="fw-600" style="color:#3b82f6;" x-text="selectedIds.length"></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="button-row">
                            <button class="btn-apply" @click="processChecks()" :disabled="!selectedIds.length || processing">
                                <i class="fa" :class="processing ? 'fa-spinner fa-spin' : 'fa-check'"></i> Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LOG --}}
            <div class="portlet light">
                <div class="portlet-title">
                    <span class="caption-subject" style="color:#3b82f6;"><i class="fa fa-list-alt" style="margin-right:6px;"></i> LOG</span>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <select class="form-control-gf" style="width:50px;max-width:50px;" x-model="logPerPage" @change="loadHistory()">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                        </select>
                        <span style="font-size:10px;color:#64748b;">records</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="history-loading" class="loading-overlay" style="display:none;">
                        <i class="fa fa-spinner"></i>
                    </div>
                    <div class="grid-container">
                        <table class="grid-table">
                            <thead>
                                <tr>
                                    <th>Bank</th>
                                    <th>File Name</th>
                                    <th>Date</th>
                                    <th>Uploader</th>
                                    <th class="num">Payment Cleared Count</th>
                                    <th class="center">Detail</th>
                                </tr>
                            </thead>
                            <tbody id="history-tbody">
                                <tr><td colspan="6" class="empty-state" style="padding:20px;">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-bar" id="history-pagination"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div id="detail-modal" class="modal-backdrop" style="display:none;" onclick="if(event.target===this)closeDetailModal()">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Clear Check Detail</h3>
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

    function esc(text) {
        if (!text) return '--';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function clearCheckApp() {
        return {
            panelOpen: true,
            bankName: '',
            uploadedFile: null,
            uploadedFileObj: null,
            compareAbsolute: false,
            uploading: false,
            processing: false,
            results: [],
            selectedIds: [],
            logPerPage: 10,
            logPage: 1,

            get matchedCount() {
                return this.results.filter(function(r) { return r.matched; }).length;
            },

            get clearDateDisplay() {
                var today = new Date();
                var m = String(today.getMonth() + 1).padStart(2, '0');
                var d = String(today.getDate()).padStart(2, '0');
                var y = today.getFullYear();
                return m + '-' + d + '-' + y;
            },

            init() {
                this.loadHistory();
            },

            handleFile(event) {
                var file = event.target.files[0];
                if (!file) return;
                this.uploadedFile = file.name;
                this.uploadedFileObj = file;
                this.doUpload();
            },

            clearFile() {
                this.uploadedFile = null;
                this.uploadedFileObj = null;
                this.results = [];
                this.selectedIds = [];
            },

            toggleAbsolute() {
                if (this.compareAbsolute) {
                    this.results.forEach(function(r) {
                        if (typeof r.amount === 'number') r.amount = Math.abs(r.amount);
                        if (typeof r.bank_amount === 'number') r.bank_amount = Math.abs(r.bank_amount);
                    });
                }
            },

            doUpload() {
                if (!this.uploadedFileObj) { showToast('error', 'Please select a file.'); return; }
                if (!this.bankName) { showToast('error', 'Please select a bank.'); return; }

                this.uploading = true;
                this.results = [];
                this.selectedIds = [];

                var fd = new FormData();
                fd.append('file', this.uploadedFileObj);
                fd.append('bank_name', this.bankName);

                fetch('{{ route("accounting.bank.clear-check-excel.upload") }}', {
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
                    this.uploading = false;
                    if (!data.success) { showToast('error', data.message || 'Failed'); return; }
                    this.results = data.results;
                    this.selectedIds = data.results.filter(function(r) { return r.matched; }).map(function(r) { return r.id; });
                    showToast('success', data.selected_count + ' matched, ' + (data.results.length - data.selected_count) + ' unmatched');
                }.bind(this))
                .catch(function() {
                    this.uploading = false;
                    showToast('error', 'Failed to upload file');
                }.bind(this));
            },

            toggleAll(event) {
                if (event.target.checked) {
                    this.selectedIds = this.results.filter(function(r) { return r.matched; }).map(function(r) { return r.id; });
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

            processChecks() {
                if (!this.selectedIds.length) { showToast('error', 'Select checks to process.'); return; }
                if (!confirm('Clear ' + this.selectedIds.length + ' check(s)?')) return;

                this.processing = true;
                var fd = new FormData();
                fd.append('bank_name', this.bankName);
                fd.append('file_name', this.uploadedFile || 'manual');
                this.selectedIds.forEach(function(id) { fd.append('payment_ids[]', id); });

                fetch('{{ route("accounting.bank.clear-check-excel.process") }}', {
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
                    this.processing = false;
                    if (!data.success) { showToast('error', data.message || 'Failed'); return; }
                    showToast('success', data.message);
                    this.results = [];
                    this.selectedIds = [];
                    this.uploadedFile = null;
                    this.uploadedFileObj = null;
                    this.loadHistory();
                }.bind(this))
                .catch(function() {
                    this.processing = false;
                    showToast('error', 'Failed to process');
                }.bind(this));
            },

            loadHistory() {
                var el = document.getElementById('history-loading');
                if (el) el.style.display = 'block';

                var fd = new FormData();
                fd.append('page', this.logPage);
                fd.append('per_page', this.logPerPage);

                fetch('{{ route("accounting.bank.clear-check-excel.history") }}', {
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
                    this.renderHistory(data);
                }.bind(this))
                .catch(function() {
                    if (el) el.style.display = 'none';
                });
            },

            renderHistory(data) {
                var tbody = document.getElementById('history-tbody');
                var pag = document.getElementById('history-pagination');
                if (!data.logs.length) {
                    tbody.innerHTML = '<tr><td colspan="6" class="empty-state" style="padding:20px;">No records</td></tr>';
                    pag.innerHTML = '<div></div><div></div>';
                    return;
                }

                var html = '';
                data.logs.forEach(function(log) {
                    html += '<tr>';
                    html += '<td>' + esc(log.bank_name) + '</td>';
                    html += '<td class="fw-600">' + esc(log.file_name) + '</td>';
                    html += '<td>' + esc(log.date) + '</td>';
                    html += '<td>' + esc(log.uploader) + '</td>';
                    html += '<td class="num">' + log.matched_count + '</td>';
                    html += '<td class="center"><a href="#" onclick="showDetail(' + log.id + '); return false;" style="color:#3b82f6;font-size:14px;"><i class="fa fa-file-text-o"></i></a></td>';
                    html += '</tr>';
                });
                tbody.innerHTML = html;

                var from = ((data.page - 1) * data.per_page + 1);
                var to = Math.min(data.page * data.per_page, data.total);

                var paginationHtml = '';
                paginationHtml += '<div class="pagination">';
                paginationHtml += '<li' + (data.page <= 1 ? ' class="disabled"' : '') + '><a href="#" onclick="goHistoryPage(1); return false;">&laquo;</a></li>';
                paginationHtml += '<li' + (data.page <= 1 ? ' class="disabled"' : '') + '><a href="#" onclick="goHistoryPage(' + (data.page - 1) + '); return false;">&lsaquo;</a></li>';
                for (var i = 1; i <= data.total_pages; i++) {
                    paginationHtml += '<li' + (i === data.page ? ' class="active"' : '') + '><a href="#" onclick="goHistoryPage(' + i + '); return false;">' + i + '</a></li>';
                }
                paginationHtml += '<li' + (data.page >= data.total_pages ? ' class="disabled"' : '') + '><a href="#" onclick="goHistoryPage(' + (data.page + 1) + '); return false;">&rsaquo;</a></li>';
                paginationHtml += '<li' + (data.page >= data.total_pages ? ' class="disabled"' : '') + '><a href="#" onclick="goHistoryPage(' + data.total_pages + '); return false;">&raquo;</a></li>';
                paginationHtml += '</div>';
                paginationHtml += '<div>Showing ' + from + ' to ' + to + ' of ' + data.total + ' records</div>';

                pag.innerHTML = paginationHtml;
            }
        };
    }

    function goHistoryPage(page) {
        if (page < 1) return;
        var app = Alpine.$data(document.querySelector('[x-data]'));
        app.logPage = page;
        app.loadHistory();
    }

    function showDetail(logId) {
        var modal = document.getElementById('detail-modal');
        var body = document.getElementById('detail-body');
        modal.style.display = 'flex';
        body.innerHTML = '<div class="loading-overlay"><i class="fa fa-spinner"></i><div style="margin-top:8px;">Loading...</div></div>';

        var fd = new FormData();
        fd.append('log_id', logId);

        fetch('{{ route("accounting.bank.clear-check-excel.log-detail") }}', {
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
            html += '<div><strong>Bank:</strong> ' + esc(log.bank_name || 'All') + '</div>';
            html += '<div><strong>File:</strong> ' + esc(log.file_name) + '</div>';
            html += '<div><strong>Uploader:</strong> ' + esc(log.operator) + '</div>';
            html += '<div><strong>Payment Cleared:</strong> ' + log.matched_count + '</div>';
            html += '<div><strong>Date:</strong> ' + esc(log.created_at) + '</div>';
            html += '</div>';

            if (payments.length) {
                html += '<div class="grid-container"><table class="grid-table"><thead><tr>';
                html += '<th>Check No.</th><th>Payment No.</th><th>Paid to</th><th>Currency</th><th class="num">Amount</th><th>Bank</th><th>Office</th>';
                html += '</tr></thead><tbody>';
                payments.forEach(function(p) {
                    html += '<tr>';
                    html += '<td class="fw-600">' + esc(p.check_no) + '</td>';
                    html += '<td>' + esc(p.payment_no) + '</td>';
                    html += '<td>' + esc(p.trade_partner) + '</td>';
                    html += '<td>' + esc(p.currency) + '</td>';
                    html += '<td class="num">' + fmt(p.amount) + '</td>';
                    html += '<td>' + esc(p.bank_name) + '</td>';
                    html += '<td class="center">' + esc(p.office) + '</td>';
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
    </script>
    @endpush
</x-layout>
