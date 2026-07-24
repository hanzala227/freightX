<x-layout>
    @push('styles')
    <style>
        .page-content{padding:8px 12px;background:#eef1f5;min-height:calc(100vh - 50px);font-family:'Inter','Open Sans',sans-serif!important}
        .portlet.light{background-color:#fff;border:1px solid #cbd5e1;border-radius:2px;margin-bottom:10px!important;box-shadow:0 1px 2px rgba(0,0,0,.05)}
        .portlet-title{padding:4px 10px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;min-height:28px;background:#f8fafc}
        .portlet-body{padding:12px 14px}
        .caption-subject{color:#1e293b;font-size:12px;font-weight:700;text-transform:uppercase}
        .page-bar{background-color:#fff;padding:8px 20px;margin-bottom:15px;border:1px solid #e9ebec;border-radius:4px}
        .page-breadcrumb{list-style:none;padding:0;margin:0;display:flex;align-items:center}
        .page-breadcrumb li{font-size:12px;color:#888;display:flex;align-items:center}
        .page-breadcrumb li a{color:#337ab7;text-decoration:none}
        .page-breadcrumb li i{margin:0 8px;font-size:10px;opacity:.5}

        .fi{height:22px;border:1px solid #c2cad8;padding:0 6px;font-size:11px;border-radius:2px;color:#333;background:#fff;outline:none;box-sizing:border-box}
        .fi:focus{border-color:#3b82f6}
        .fi-select{min-width:100px}
        .fi-date{width:120px}
        .filter-row{display:flex;align-items:center;gap:12px;margin-bottom:6px}
        .filter-row label{font-size:11px;color:#334155;white-space:nowrap;cursor:pointer}
        .filter-row input[type="radio"]{accent-color:#3b82f6;margin-right:2px;vertical-align:middle}
        .radio-group{display:flex;align-items:center;gap:12px}
        .radio-group label{font-size:11px;color:#334155;cursor:pointer;white-space:nowrap;display:inline-flex;align-items:center}
        .filter-group{display:flex;align-items:center;gap:6px}
        .filter-group .fg-label{font-size:11px;color:#64748b;white-space:nowrap;min-width:80px}

        .btn-search-gf{background:#3b82f6;color:#fff;border:1px solid #2563eb;border-radius:2px;padding:0 14px;height:22px;font-size:11px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:4px;box-sizing:border-box}
        .btn-search-gf:hover{background:#2563eb;color:#fff}

        .grid-container{width:100%;overflow:hidden;background:#fff}
        .grid-wrapper{width:100%;overflow-x:auto}
        .grid-table{border-collapse:separate;border-spacing:0;width:100%;font-size:10px;table-layout:auto}
        .grid-table th{background:#f8fafc;color:#475569;font-weight:600;border-bottom:1px solid #cbd5e1;border-right:1px solid #e2e8f0;border-top:1px solid #cbd5e1;padding:3px 6px;white-space:nowrap;height:24px;position:sticky;top:0;z-index:10;text-align:left;user-select:none}
        .grid-table td{padding:3px 6px;border-bottom:1px solid #e2e8f0;border-right:1px solid #e2e8f0;white-space:nowrap;height:24px;overflow:hidden;text-overflow:ellipsis;vertical-align:middle;color:#334155}
        .grid-table tr:hover td{background-color:#f1f5f9!important}
        .lock-icon{font-size:12px}
        .lock-icon.locked{color:#e74c3c}
        .lock-icon.unlocked{color:#22c55e}
        .block-type{font-weight:700;font-size:10px;color:#1e293b}

        .pagination-bar{display:flex;align-items:center;justify-content:space-between;padding:8px 14px;font-size:11px;color:#64748b;border-top:1px solid #e2e8f0}
        .pagination{display:flex;list-style:none;padding:0;margin:0;gap:2px;align-items:center;font-size:10px}
        .pagination li a,.pagination li span{padding:2px 8px;border:1px solid #cbd5e1;color:#334155;text-decoration:none;border-radius:2px;background:#fff;cursor:pointer}
        .pagination li.active span{background:#3b82f6;color:#fff;border-color:#2563eb;font-weight:600}
        .pagination li.disabled span{opacity:.4;cursor:not-allowed}
        .per-page-select{display:flex;align-items:center;gap:4px}
        .per-page-select label{font-size:10px;color:#64748b}
        .per-page-select select{height:20px;border:1px solid #c2cad8;padding:0 4px;font-size:10px;border-radius:2px;color:#333;background:#fff}

        .toast-container{position:fixed;top:16px;right:16px;z-index:10000;display:flex;flex-direction:column;gap:8px}
        .toast{padding:10px 16px;border-radius:4px;font-size:11px;font-weight:600;color:#fff;box-shadow:0 4px 12px rgba(0,0,0,.15);animation:slideIn .3s ease}
        .toast.success{background:#22c55e}
        .toast.error{background:#e73d4a}
        @keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}
        .loading-overlay{display:none;position:fixed;inset:0;background:rgba(255,255,255,.7);z-index:9999;justify-content:center;align-items:center}
        .loading-overlay.active{display:flex}
        .loading-spinner{width:36px;height:36px;border:3px solid #e2e8f0;border-top-color:#3b82f6;border-radius:50%;animation:spin .8s linear infinite}
        @keyframes spin{to{transform:rotate(360deg)}}
    </style>
    @endpush

    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a></li>
                <li><i class="fa fa-angle-right"></i>Accounting</li>
                <li><i class="fa fa-angle-right"></i>Journal</li>
                <li><i class="fa fa-angle-right"></i><span style="color:#333;font-weight:700;">Accounting Block History</span></li>
            </ul>
        </div>

        <div class="portlet light">
            <div class="portlet-title">
                <span class="caption-subject">Accounting Block History</span>
            </div>
            <div class="portlet-body">
                <div class="filter-row">
                    <div class="filter-group">
                        <span class="fg-label">Office</span>
                        <select id="officeId" class="fi fi-select">
                            <option value="">All</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-group">
                        <span class="fg-label">Execute Date</span>
                        <input type="date" id="executeDate" class="fi fi-date">
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-group">
                        <span class="fg-label">Program</span>
                        <div class="radio-group">
                            <label><input type="radio" name="program" value="all" checked> All</label>
                            <label><input type="radio" name="program" value="Accounting Block"> Accounting Block</label>
                            <label><input type="radio" name="program" value="Block Maintenance"> Block Maintenance</label>
                        </div>
                    </div>
                </div>
                <div style="text-align:center;margin-top:10px;">
                    <button type="button" class="btn-search-gf" id="btnSearch"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
        </div>

        <div class="portlet light">
            <div class="portlet-body" style="padding:0;">
                <div class="grid-container">
                    <div class="grid-wrapper">
                        <table class="grid-table" id="resultsTable">
                            <thead>
                                <tr>
                                    <th>Program</th>
                                    <th style="width:30px;text-align:center;"><i class="fa fa-lock"></i></th>
                                    <th>Block Type/Date</th>
                                    <th>Ref No.</th>
                                    <th>Office</th>
                                    <th>Execute by</th>
                                    <th>Date Execute</th>
                                </tr>
                            </thead>
                            <tbody id="resultsBody">
                                <tr>
                                    <td colspan="7" style="text-align:center;color:#94a3b8;padding:30px;">Click Search to load results.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="pagination-bar" id="paginationBar">
                    <span id="showingText">Showing 0 records</span>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div class="per-page-select">
                            <select id="perPage" onchange="doSearch()">
                                <option value="25" selected>25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <label>records</label>
                        </div>
                        <ul class="pagination" id="paginationLinks"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>
    <div class="loading-overlay" id="loadingOverlay"><div class="loading-spinner"></div></div>

    <script>
    var CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var currentPage = 1;

    function $(sel) { return document.querySelector(sel); }
    function $$(sel) { return document.querySelectorAll(sel); }

    function toast(type, msg) {
        var c = document.getElementById('toastContainer');
        var t = document.createElement('div');
        t.className = 'toast ' + type;
        t.textContent = msg;
        c.appendChild(t);
        setTimeout(function(){ t.remove(); }, 4000);
    }

    function showLoading() { document.getElementById('loadingOverlay').classList.add('active'); }
    function hideLoading() { document.getElementById('loadingOverlay').classList.remove('active'); }

    function doSearch(page) {
        currentPage = page || 1;
        var officeId = document.getElementById('officeId').value;
        var executeDate = document.getElementById('executeDate').value;
        var program = document.querySelector('input[name="program"]:checked').value;
        var perPage = document.getElementById('perPage').value;

        var params = new URLSearchParams();
        if (officeId) params.set('office_id', officeId);
        if (executeDate) params.set('execute_date', executeDate);
        params.set('program', program);
        params.set('page', currentPage);
        params.set('per_page', perPage);

        showLoading();
        fetch('{{ route("accounting.block-history.search") }}?' + params.toString(), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function(r) { return r.json(); })
        .then(function(resp) {
            hideLoading();
            if (!resp.success) { toast('error', 'Search failed.'); return; }
            renderGrid(resp.data);
            renderPagination(resp);
            document.getElementById('showingText').textContent = 'Showing ' + resp.from + ' to ' + resp.to + ' of ' + resp.total + ' records';
        })
        .catch(function() { hideLoading(); toast('error', 'Network error.'); });
    }

    function renderGrid(data) {
        var tbody = document.getElementById('resultsBody');
        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:#94a3b8;padding:30px;">No records found.</td></tr>';
            return;
        }
        var html = '';
        for (var i = 0; i < data.length; i++) {
            var r = data[i];
            html += '<tr>';
            html += '<td>' + esc(r.program) + '</td>';
            html += '<td style="text-align:center;"><i class="fa fa-lock lock-icon ' + (r.is_blocked ? 'locked' : 'unlocked') + '"></i></td>';
            html += '<td>';
            if (r.block_type) { html += '<span class="block-type">' + esc(r.block_type) + '</span><br>'; }
            html += esc(r.block_date);
            html += '</td>';
            html += '<td>' + esc(r.ref_no) + '</td>';
            html += '<td>' + esc(r.office) + '</td>';
            html += '<td>' + esc(r.execute_by) + '</td>';
            html += '<td>' + esc(r.executed_at) + '</td>';
            html += '</tr>';
        }
        tbody.innerHTML = html;
    }

    function renderPagination(resp) {
        var links = document.getElementById('paginationLinks');
        if (resp.last_page <= 1) { links.innerHTML = ''; return; }
        var html = '';
        html += '<li class="' + (resp.current_page <= 1 ? 'disabled' : '') + '"><span onclick="doSearch(' + (resp.current_page - 1) + ')">&laquo;</span></li>';
        var start = Math.max(1, resp.current_page - 2);
        var end = Math.min(resp.last_page, resp.current_page + 2);
        for (var p = start; p <= end; p++) {
            html += '<li class="' + (p === resp.current_page ? 'active' : '') + '"><span onclick="doSearch(' + p + ')">' + p + '</span></li>';
        }
        html += '<li class="' + (resp.current_page >= resp.last_page ? 'disabled' : '') + '"><span onclick="doSearch(' + (resp.current_page + 1) + ')">&raquo;</span></li>';
        links.innerHTML = html;
    }

    function esc(str) {
        if (!str) return '';
        var d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    document.getElementById('btnSearch').addEventListener('click', function() { doSearch(1); });

    document.querySelectorAll('input, select').forEach(function(el) {
        el.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') doSearch(1);
        });
    });
    </script>
</x-layout>
