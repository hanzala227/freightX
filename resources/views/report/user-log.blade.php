<x-layout>
    @push('styles')
    <x-list-styles />
    <style>
        .active-dur { color: #10b981; font-weight: 700; cursor: pointer; }
        .active-dur i { font-size: 10px; opacity: 0.7; margin-left: 3px; }
        .loading-overlay { position: absolute; inset: 0; background: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center; z-index: 10; border-radius: 4px; }
        .spinner { width: 28px; height: 28px; border: 3px solid #e2e8f0; border-top-color: #3b82f6; border-radius: 50%; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .quick-toggle { height: 22px; padding: 0 12px; font-size: 10px; border: 1px solid #cbd5e1; background: #fff; color: #334155; border-radius: 2px; cursor: pointer; font-weight: 600; transition: all 0.15s; }
        .quick-toggle:hover { background: #f1f5f9; border-color: #94a3b8; }
        .quick-toggle.active { background: #3b82f6; color: #fff; border-color: #2563eb; }
        .filter-panel { display: none; background: #eff6ff; border-bottom: 1px solid #e2e8f0; padding: 6px 8px; }
        .filter-panel.open { display: flex; flex-wrap: wrap; gap: 8px; align-items: flex-end; }
        .filter-group { display: flex; flex-direction: column; gap: 2px; }
        .filter-group label { font-size: 9px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.3px; }
        .filter-group input, .filter-group select { height: 22px; padding: 0 6px; border: 1px solid #93c5fd; font-size: 10px; border-radius: 2px; background: #fff; outline: none; }
        .filter-group input:focus, .filter-group select:focus { border-color: #3b82f6; box-shadow: 0 0 0 1px rgba(59,130,246,0.2); }
        
        /* Toast Notification Styles */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #fff;
            padding: 12px 18px;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 500;
            z-index: 9999;
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .toast-notification.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .toast-notification i {
            font-size: 16px;
        }
        
        .toast-notification.toast-success {
            border-left: 4px solid #10b981;
            color: #065f46;
        }
        
        .toast-notification.toast-success i {
            color: #10b981;
        }
        
        .toast-notification.toast-error {
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }
        
        .toast-notification.toast-error i {
            color: #ef4444;
        }
        
        .toast-notification.toast-info {
            border-left: 4px solid #3b82f6;
            color: #1e40af;
        }
        
        .toast-notification.toast-info i {
            color: #3b82f6;
        }
        
        @media print {
            .no-print { display: none !important; }
            .loading-overlay { display: none !important; }
            .page-bar { display: none !important; }
            .filter-panel { display: none !important; }
            .grid-table th, .grid-table td { font-size: 9px; padding: 1px 3px; }
        }
    </style>
    @endpush

    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
                <li>Reports <i class="fa fa-angle-right"></i></li>
                <li><span style="color: #333; font-weight: 700;">User Log In/Out Report</span></li>
            </ul>
        </div>

        <div x-data="userLogReport()" x-init="init()" style="position: relative;">
            <div x-show="loading" class="loading-overlay"><div class="spinner"></div></div>

            <div class="portlet light">

                {{-- ── PORTLET TITLE ── --}}
                <div class="portlet-title">
                    <div class="caption" style="display:flex;align-items:center;gap:8px;">
                        <span class="caption-subject">User Log In/Out Report</span>
                        <span x-show="pagination.total > 0" style="font-size:10px;color:#3b82f6;font-weight:600;background:#eff6ff;padding:1px 6px;border-radius:10px;border:1px solid #bfdbfe;" x-text="pagination.total + ' records'"></span>
                    </div>
                    <div class="actions" style="display:flex;gap:4px;align-items:center;">
                        <button class="btn-action-round no-print" :class="{ 'active-filter': showFilter }" @click="showFilter = !showFilter" title="Toggle filter row">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        <button class="btn-action-round no-print" @click="exportExcel()" title="Download as CSV/Excel">
                            <i class="fa fa-file-excel-o"></i> Excel
                        </button>
                        <button class="btn-action-round no-print" @click="printReport()" title="Print report">
                            <i class="fa fa-print"></i> Print
                        </button>
                    </div>
                </div>

                {{-- ── TOOLBAR ── --}}
                <div class="portlet-tool no-print">
                    <div style="display:flex;gap:6px;align-items:center;">
                        <div class="btn-group">
                            <button class="quick-toggle" :class="{ active: quickRange === 'all' }" @click="setQuickRange('all')">Full</button>
                            <button class="quick-toggle" :class="{ active: quickRange === 'today' }" @click="setQuickRange('today')">Today</button>
                            <button class="quick-toggle" :class="{ active: quickRange === 'week' }" @click="setQuickRange('week')">Week</button>
                            <button class="quick-toggle" :class="{ active: quickRange === 'month' }" @click="setQuickRange('month')">Month</button>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;">
                        <i class="fa fa-search" style="font-size:10px;color:#94a3b8;"></i>
                        <select class="select-tool" x-model="filters.user_id" @change="filters.page = 1; fetchData()">
                            <option value="">All Users</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- ── FILTER PANEL ── --}}
                <div class="filter-panel no-print" :class="{ open: showFilter }">
                    <div class="filter-group">
                        <label>Date From</label>
                        <input type="date" x-model="filters.date_from" @change="filters.page = 1; fetchData()">
                    </div>
                    <div class="filter-group">
                        <label>Date To</label>
                        <input type="date" x-model="filters.date_to" @change="filters.page = 1; fetchData()">
                    </div>
                    <div class="filter-group">
                        <label>User</label>
                        <select x-model="filters.user_id" @change="filters.page = 1; fetchData()" style="height:22px;padding:0 6px;border:1px solid #93c5fd;font-size:10px;border-radius:2px;background:#fff;">
                            <option value="">All Users</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn-tool green" @click="filters.page = 1; fetchData()" style="height:22px;padding:0 14px;">Apply</button>
                    <button class="btn-tool" @click="resetFilters()" style="height:22px;">Clear</button>
                </div>

                {{-- ── TABLE ── --}}
                <div class="portlet-body">
                    <div class="grid-container">
                        <div class="grid-wrapper">
                            <table class="grid-table">
                                <thead>
                                    <tr>
                                        <th style="width:35px;text-align:center;">#</th>
                                        <th style="width:110px;cursor:pointer;" @click="sort('user_code')">
                                            User ID <i class="fa" :class="getSortIcon('user_code')"></i>
                                        </th>
                                        <th style="width:110px;cursor:pointer;" @click="sort('first_name')">
                                            First Name <i class="fa" :class="getSortIcon('first_name')"></i>
                                        </th>
                                        <th style="width:110px;cursor:pointer;" @click="sort('last_name')">
                                            Last Name <i class="fa" :class="getSortIcon('last_name')"></i>
                                        </th>
                                        <th style="width:90px;cursor:pointer;" @click="sort('office')">
                                            Office <i class="fa" :class="getSortIcon('office')"></i>
                                        </th>
                                        <th style="width:130px;cursor:pointer;" @click="sort('login')">
                                            Login <i class="fa" :class="getSortIcon('login')"></i>
                                        </th>
                                        <th style="width:130px;cursor:pointer;" @click="sort('logout')">
                                            Logout <i class="fa" :class="getSortIcon('logout')"></i>
                                        </th>
                                        <th style="width:80px;cursor:pointer;" @click="sort('duration')">
                                            Duration <i class="fa" :class="getSortIcon('duration')"></i>
                                        </th>
                                        <th style="width:80px;cursor:pointer;" @click="sort('active')">
                                            Active <i class="fa" :class="getSortIcon('active')"></i>
                                        </th>
                                        <th style="width:80px;cursor:pointer;" @click="sort('inactive')">
                                            Inactive <i class="fa" :class="getSortIcon('inactive')"></i>
                                        </th>
                                        <th style="width:110px;text-align:center;cursor:pointer;" @click="sort('active_duration')">
                                            Active Duration <i class="fa" :class="getSortIcon('active_duration')"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-if="rows.length === 0">
                                        <tr>
                                            <td colspan="11" style="text-align:center;padding:30px;color:#94a3b8;">
                                                <i class="fa fa-key" style="font-size:24px;margin-bottom:8px;display:block;opacity:0.3;"></i>
                                                No log entries found.
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="(row, idx) in rows" :key="idx">
                                        <tr>
                                            <td style="text-align:center;color:#94a3b8;" x-text="((pagination.current_page - 1) * pagination.per_page) + idx + 1"></td>
                                            <td style="font-weight:600;color:#0f172a;" x-text="row.user_code"></td>
                                            <td x-text="row.first_name"></td>
                                            <td x-text="row.last_name || '--'"></td>
                                            <td x-text="row.office"></td>
                                            <td x-text="row.login"></td>
                                            <td x-text="row.logout || '--'"></td>
                                            <td x-text="row.duration"></td>
                                            <td x-text="row.active"></td>
                                            <td x-text="row.inactive"></td>
                                            <td style="text-align:center;">
                                                <span class="active-dur" x-text="row.active_duration"></span>
                                                <i class="fa fa-info-circle active-dur"></i>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ── PAGINATION ── --}}
                <div class="portlet-tool bottom no-print">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <ul class="pagination">
                            <li :class="{ disabled: pagination.current_page <= 1 }">
                                <a href="#" @click.prevent="goToPage(1)"><i class="fa fa-angle-double-left"></i></a>
                            </li>
                            <li :class="{ disabled: pagination.current_page <= 1 }">
                                <a href="#" @click.prevent="goToPage(pagination.current_page - 1)"><i class="fa fa-angle-left"></i></a>
                            </li>
                            <template x-for="p in getPages()" :key="p">
                                <li :class="{ active: p === pagination.current_page }">
                                    <a href="#" @click.prevent="goToPage(p)" x-text="p"></a>
                                </li>
                            </template>
                            <li :class="{ disabled: pagination.current_page >= pagination.last_page }">
                                <a href="#" @click.prevent="goToPage(pagination.current_page + 1)"><i class="fa fa-angle-right"></i></a>
                            </li>
                            <li :class="{ disabled: pagination.current_page >= pagination.last_page }">
                                <a href="#" @click.prevent="goToPage(pagination.last_page)"><i class="fa fa-angle-double-right"></i></a>
                            </li>
                        </ul>
                        <select class="select-tool" x-model="filters.per_page" @change="filters.page = 1; fetchData()">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div style="font-size:10px;color:#64748b;">
                        <span x-text="getShowingText()"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function userLogReport() {
        return {
            loading: false,
            showFilter: false,
            quickRange: 'all',
            filters: {
                date_from: '2026-01-01',
                date_to: '{{ now()->format("Y-m-d") }}',
                user_id: '',
                sort_by: 'login',
                sort_dir: 'desc',
                page: 1,
                per_page: 10,
            },
            rows: [],
            pagination: { current_page: 1, per_page: 10, total: 0, last_page: 1 },

            init() {
                this.fetchData();
            },

            async fetchData() {
                this.loading = true;
                try {
                    const params = new URLSearchParams();
                    params.append('date_from', this.filters.date_from);
                    params.append('date_to', this.filters.date_to);
                    params.append('page', this.filters.page);
                    params.append('per_page', this.filters.per_page);
                    params.append('sort_by', this.filters.sort_by);
                    params.append('sort_dir', this.filters.sort_dir);
                    if (this.filters.user_id) params.append('user_id', this.filters.user_id);

                    const resp = await fetch('{{ route("report.user-log.data") }}?' + params.toString());
                    if (!resp.ok) throw new Error('Failed to fetch data');
                    
                    const json = await resp.json();
                    this.rows = json.rows;
                    this.pagination = json.pagination;
                } catch (e) {
                    console.error('Fetch error:', e);
                    this.showToast('Failed to load data. Please try again.', 'error');
                } finally {
                    this.loading = false;
                }
            },

            toLocalDateStr(d) {
                const y = d.getFullYear();
                const m = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return y + '-' + m + '-' + day;
            },

            setQuickRange(range) {
                this.quickRange = range;
                const today = new Date();
                let from;
                switch (range) {
                    case 'all':
                        from = new Date(2026, 0, 1);
                        break;
                    case 'today':
                        from = new Date(today.getFullYear(), today.getMonth(), today.getDate());
                        break;
                    case 'week':
                        from = new Date(today.getFullYear(), today.getMonth(), today.getDate() - today.getDay());
                        break;
                    case 'month':
                        from = new Date(today.getFullYear(), today.getMonth(), 1);
                        break;
                }
                this.filters.date_from = this.toLocalDateStr(from);
                this.filters.date_to = this.toLocalDateStr(today);
                this.filters.page = 1;
                this.fetchData();
                this.showToast('Date range updated to ' + range, 'info');
            },

            resetFilters() {
                const today = new Date();
                const from = new Date(2026, 0, 1);
                this.filters.date_from = this.toLocalDateStr(from);
                this.filters.date_to = this.toLocalDateStr(today);
                this.filters.user_id = '';
                this.quickRange = 'all';
                this.filters.page = 1;
                this.fetchData();
                this.showToast('Filters cleared', 'info');
            },

            sort(field) {
                if (this.filters.sort_by === field) {
                    this.filters.sort_dir = this.filters.sort_dir === 'asc' ? 'desc' : 'asc';
                } else {
                    this.filters.sort_by = field;
                    this.filters.sort_dir = 'desc';
                }
                this.filters.page = 1;
                this.fetchData();
            },

            getSortIcon(field) {
                if (this.filters.sort_by !== field) return 'fa-sort sort-icon';
                return this.filters.sort_dir === 'asc' ? 'fa-sort-asc sort-icon sort-active' : 'fa-sort-desc sort-icon sort-active';
            },

            goToPage(page) {
                if (page < 1 || page > this.pagination.last_page) return;
                this.filters.page = page;
                this.fetchData();
            },

            getPages() {
                const pages = [];
                const total = this.pagination.last_page;
                const current = this.pagination.current_page;
                let start = Math.max(1, current - 2);
                let end = Math.min(total, start + 4);
                if (end - start < 4) start = Math.max(1, end - 4);
                for (let i = start; i <= end; i++) pages.push(i);
                return pages;
            },

            getShowingText() {
                const total = this.pagination.total;
                const per = this.pagination.per_page;
                const page = this.pagination.current_page;
                if (total === 0) return 'Showing 0 to 0 of 0 records';
                const from = ((page - 1) * per) + 1;
                const to = Math.min(page * per, total);
                return 'Showing ' + from + ' to ' + to + ' of ' + total + ' records';
            },

            async printReport() { 
                this.showToast('Generating print report...', 'info');
                
                try {
                    // Fetch all data (no pagination) for print
                    const params = new URLSearchParams();
                    params.append('date_from', this.filters.date_from);
                    params.append('date_to', this.filters.date_to);
                    params.append('page', 1);
                    params.append('per_page', 10000);
                    params.append('sort_by', this.filters.sort_by);
                    params.append('sort_dir', this.filters.sort_dir);
                    if (this.filters.user_id) params.append('user_id', this.filters.user_id);

                    // Open print view in new window
                    const printUrl = '{{ route("report.user-log.print") }}?' + params.toString();
                    window.open(printUrl, '_blank');
                    
                    this.showToast('Print report opened in new window', 'success');
                } catch (e) {
                    console.error('Print error:', e);
                    this.showToast('Failed to generate print report', 'error');
                }
            },

            async exportExcel() {
                this.showToast('Generating Excel file...', 'info');
                
                try {
                    // Fetch all data (no pagination) for export
                    const params = new URLSearchParams();
                    params.append('date_from', this.filters.date_from);
                    params.append('date_to', this.filters.date_to);
                    params.append('page', 1);
                    params.append('per_page', 10000);
                    params.append('sort_by', this.filters.sort_by);
                    params.append('sort_dir', this.filters.sort_dir);
                    if (this.filters.user_id) params.append('user_id', this.filters.user_id);

                    const resp = await fetch('{{ route("report.user-log.data") }}?' + params.toString());
                    if (!resp.ok) throw new Error('Failed to fetch data');
                    
                    const json = await resp.json();
                    const allRows = json.rows;
                    
                    // Generate CSV
                    let csv = '#,User ID,First Name,Last Name,Office,Login,Logout,Duration,Active,Inactive,Active Duration\n';
                    allRows.forEach((r, i) => {
                        csv += `${i + 1},"${r.user_code || ''}","${r.first_name || ''}","${r.last_name || ''}","${r.office || ''}","${r.login || ''}","${r.logout || ''}","${r.duration || ''}","${r.active || ''}","${r.inactive || ''}","${r.active_duration || ''}"\n`;
                    });
                    
                    // Download
                    const blob = new Blob([csv], { type: 'text/csv' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'user-log-report-' + new Date().toISOString().split('T')[0] + '.csv';
                    a.style.display = 'none';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                    
                    this.showToast('Excel file downloaded successfully! (' + allRows.length + ' records)', 'success');
                } catch (e) {
                    console.error('Export error:', e);
                    this.showToast('Failed to export data. Please try again.', 'error');
                }
            },

            showToast(message, type = 'info') {
                // Remove existing toasts
                const existingToast = document.querySelector('.toast-notification');
                if (existingToast) existingToast.remove();

                // Create toast element
                const toast = document.createElement('div');
                toast.className = 'toast-notification toast-' + type;
                toast.innerHTML = `
                    <i class="fa fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                    <span>${message}</span>
                `;
                document.body.appendChild(toast);

                // Show toast with animation
                setTimeout(() => toast.classList.add('show'), 10);

                // Auto hide after 3 seconds
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            },
        };
    }
    </script>
</x-layout>
