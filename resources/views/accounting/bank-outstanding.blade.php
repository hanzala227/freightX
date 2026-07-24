<x-layout>
    @push('styles')
    <style>
        .page-content { padding: 8px 12px; background: #eef1f5; min-height: calc(100vh - 50px); font-family: 'Inter', 'Open Sans', sans-serif !important; }
        .portlet.light { background-color: #fff; border: 1px solid #cbd5e1; border-radius: 2px; margin-bottom: 10px !important; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .portlet-title { padding: 4px 10px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; min-height: 28px; background: #f8fafc; }
        .portlet-body { padding: 12px 14px; }
        .caption-subject { color: #1e293b; font-size: 12px; font-weight: 700; text-transform: uppercase; }

        .page-bar { background-color: #fff; padding: 8px 20px; margin-bottom: 15px; border: 1px solid #e9ebec; border-radius: 4px; }
        .page-breadcrumb { list-style: none; padding: 0; margin: 0; display: flex; align-items: center; }
        .page-breadcrumb li { font-size: 12px; color: #888; display: flex; align-items: center; }
        .page-breadcrumb li a { color: #337ab7; text-decoration: none; }
        .page-breadcrumb li i { margin: 0 8px; font-size: 10px; opacity: 0.5; }

        .form-label-box { background: #eef1f5; width: 130px; padding: 4px 8px; font-size: 11px; color: #333; min-height: 24px; display: flex; align-items: center; flex-shrink: 0; font-weight: 600; }
        .form-input-box { flex-grow: 1; padding-left: 10px; display: flex; align-items: center; flex-wrap: wrap; gap: 10px; }
        .form-group-row { display: flex; align-items: center; margin-bottom: 8px; }
        .form-control-gf { height: 24px; border: 1px solid #c2cad8; padding: 2px 6px; font-size: 11px; border-radius: 2px !important; width: 100%; max-width: 220px; color: #333; outline: none; background: #fff; }
        .form-control-gf:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,0.1); }
        select.form-control-gf { appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 4px center; background-size: 8px; padding-right: 16px; }

        .radio-item { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; color: #333; cursor: pointer; }
        .radio-item input[type="radio"], .radio-item input[type="checkbox"] { margin: 0; cursor: pointer; width: 12px; height: 12px; accent-color: #3b82f6; }

        .report-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px 40px; }
        @media (max-width: 900px) { .report-grid { grid-template-columns: 1fr; } }

        .button-row { display: flex; justify-content: center; gap: 8px; margin-top: 20px; }
        .btn-action-round { background: #64748b; color: #fff; border: 1px solid #475569; border-radius: 2px; padding: 0 8px; height: 20px; font-size: 10px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 4px; cursor: pointer; text-decoration: none; transition: all 0.15s; box-sizing: border-box; }
        .btn-action-round:hover { background: #475569; color: #fff; }
        .btn-action-round.primary { background: #3b82f6; border-color: #2563eb; }
        .btn-action-round.primary:hover { background: #2563eb; }
        .btn-action-round.green { background: #22c55e; border-color: #16a34a; }
        .btn-action-round.green:hover { background: #16a34a; }

        .text-danger { color: #e73d4a; }
        .fw-600 { font-weight: 600; }
        .num { text-align: right; font-family: 'Courier New', monospace; }

        .grid-container { width: 100%; overflow-x: auto; background: #fff; margin-top: 15px; }
        .grid-table { border-collapse: collapse; width: 100%; font-size: 11px; }
        .grid-table th { background: #f8fafc; color: #475569; font-weight: 600; border: 1px solid #e2e8f0; padding: 4px 6px; white-space: nowrap; text-align: left; }
        .grid-table td { padding: 3px 6px; border: 1px solid #e2e8f0; white-space: nowrap; color: #334155; }
        .grid-table td.num { text-align: right; font-family: 'Courier New', monospace; }
        .grid-table tbody tr:hover { background: #f1f5f9; }
        .grid-table .total-row { background: #f8fafc !important; font-weight: 700; }
        .grid-table .total-row td { border-top: 2px solid #475569; color: #0f172a; }

        .empty-state { text-align: center; padding: 40px 20px; color: #94a3b8; }
        .empty-state i { font-size: 36px; display: block; margin-bottom: 12px; }
        .loading-overlay { text-align: center; padding: 30px; color: #64748b; }
        .loading-overlay i { font-size: 20px; animation: spin 1s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        .toast-container { position: fixed; top: 56px; right: 16px; z-index: 9999; display: flex; flex-direction: column; gap: 6px; pointer-events: none; }
        .toast { background: #1e293b; color: #fff; padding: 8px 14px; border-radius: 4px; font-size: 11px; box-shadow: 0 4px 16px rgba(0,0,0,0.25); display: flex; align-items: center; gap: 8px; animation: toastIn 0.25s ease; pointer-events: all; }
        .toast.success { border-left: 3px solid #22c55e; }
        .toast.error   { border-left: 3px solid #ef4444; }
        .toast.info    { border-left: 3px solid #3b82f6; }
        @keyframes toastIn { from { transform: translateX(40px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    </style>
    @endpush

    <div class="toast-container" id="toast-container"></div>

    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a></li>
                <li><i class="fa fa-angle-right"></i>Accounting</li>
                <li><i class="fa fa-angle-right"></i>Bank</li>
                <li><i class="fa fa-angle-right"></i><span style="color: #333; font-weight: 700;">Bank Outstanding</span></li>
            </ul>
        </div>

        <div class="portlet light">
            <div class="portlet-title">
                <span class="caption-subject"><i class="fa fa-bank" style="color:#3b82f6;margin-right:6px;"></i>Bank Outstanding Report</span>
            </div>
            <div class="portlet-body">
                <div class="report-grid">
                    <div>
                        <div class="form-group-row">
                            <div class="form-label-box"><span class="text-danger">*</span>As of</div>
                            <div class="form-input-box">
                                <input type="date" id="as_of_date" class="form-control-gf" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-group-row">
                            <div class="form-label-box">Office</div>
                            <div class="form-input-box">
                                <select id="office_id" class="form-control-gf">
                                    <option value="">All</option>
                                    @foreach($offices as $office)
                                        <option value="{{ $office->id }}">{{ $office->code }}</option>
                                    @endforeach
                                </select>
                                <label class="radio-item" style="margin-left:8px;"><input type="checkbox" id="group_by_office"> Group by Office</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="button-row">
                    <button class="btn-action-round primary" onclick="viewReport()"><i class="fa fa-search"></i> View</button>
                    <button class="btn-action-round" onclick="printReport()"><i class="fa fa-print"></i> Print</button>
                    <button class="btn-action-round green" onclick="exportExcel()"><i class="fa fa-file-excel-o"></i> Download Excel</button>
                </div>

                <div id="report-results">
                    <div class="empty-state" id="empty-state">
                        <i class="fa fa-inbox"></i>
                        <div>Select report options and click <strong>View</strong> to generate the Bank Outstanding report.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function showToast(type, msg) {
        const icons = { success: 'check-circle', error: 'times-circle', info: 'info-circle' };
        const t = document.createElement('div');
        t.className = 'toast ' + type;
        t.innerHTML = '<i class="fa fa-' + (icons[type] || 'info-circle') + '"></i> ' + msg;
        document.getElementById('toast-container').appendChild(t);
        setTimeout(function() { t.remove(); }, 3000);
    }

    function fmt(val) {
        if (val === undefined || val === null) return '0.00';
        return parseFloat(val).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function escapeHtml(text) {
        if (!text) return '--';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function viewReport() {
        var asOfDate = document.getElementById('as_of_date').value;
        var officeId = document.getElementById('office_id').value;
        var groupByOffice = document.getElementById('group_by_office').checked;

        if (!asOfDate) {
            showToast('error', 'Please select an As of date.');
            return;
        }

        var resultsDiv = document.getElementById('report-results');
        resultsDiv.innerHTML = '<div class="loading-overlay"><i class="fa fa-spinner"></i><div style="margin-top:8px;">Generating report...</div></div>';

        var formData = new FormData();
        formData.append('as_of_date', asOfDate);
        formData.append('office_id', officeId);
        formData.append('group_by_office', groupByOffice ? '1' : '0');

        fetch('{{ route("accounting.bank.outstanding.view") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData,
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (!data.success) {
                resultsDiv.innerHTML = '<div class="empty-state"><i class="fa fa-exclamation-triangle" style="color:#ef4444;"></i><div>Failed to generate report.</div></div>';
                showToast('error', data.message || 'Failed to generate report');
                return;
            }
            renderReport(data);
        })
        .catch(function(err) {
            console.error(err);
            resultsDiv.innerHTML = '<div class="empty-state"><i class="fa fa-exclamation-triangle" style="color:#ef4444;"></i><div>An error occurred while generating the report.</div></div>';
            showToast('error', 'Failed to generate report');
        });
    }

    function renderReport(data) {
        var rows = data.rows || [];
        var totals = data.totals || {};
        var resultsDiv = document.getElementById('report-results');

        if (!rows.length) {
            resultsDiv.innerHTML = '<div class="empty-state"><i class="fa fa-inbox"></i><div>No outstanding items found for the selected criteria.</div></div>';
            return;
        }

        var html = '<div class="grid-container"><table class="grid-table">';
        html += '<thead><tr>';
        html += '<th>Bank Name</th>';
        if (data.group_by_office) {
            html += '<th>Office</th>';
        }
        html += '<th>Currency</th>';
        html += '<th class="num">Check Received</th>';
        html += '<th class="num">Check Paid</th>';
        html += '<th class="num">Total</th>';
        html += '</tr></thead><tbody>';

        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            html += '<tr>';
            html += '<td class="fw-600">' + escapeHtml(row.bank_name) + '</td>';
            if (data.group_by_office) {
                html += '<td>' + escapeHtml(row.office) + '</td>';
            }
            html += '<td>' + escapeHtml(row.currency) + '</td>';
            html += '<td class="num" style="color:#16a34a;">' + fmt(row.check_received) + '</td>';
            html += '<td class="num" style="color:#dc2626;">' + fmt(row.check_paid) + '</td>';
            var totalVal = parseFloat(row.total);
            html += '<td class="num fw-600" style="color:' + (totalVal < 0 ? '#dc2626' : totalVal > 0 ? '#16a34a' : '#475569') + ';">' + fmt(row.total) + '</td>';
            html += '</tr>';
        }

        html += '<tr class="total-row">';
        html += '<td colspan="' + (data.group_by_office ? 2 : 1) + '">GRAND TOTAL</td>';
        html += '<td></td>';
        html += '<td class="num">' + fmt(totals.check_received) + '</td>';
        html += '<td class="num">' + fmt(totals.check_paid) + '</td>';
        var grandTotal = parseFloat(totals.total);
        html += '<td class="num" style="color:' + (grandTotal < 0 ? '#dc2626' : grandTotal > 0 ? '#16a34a' : '#475569') + ';">' + fmt(totals.total) + '</td>';
        html += '</tr>';

        html += '</tbody></table></div>';
        html += '<div style="margin-top:8px;font-size:10px;color:#64748b;text-align:right;">';
        html += 'As of: ' + data.as_of_date + ' &middot; ' + rows.length + ' bank(s) &middot; Generated ' + new Date().toLocaleString();
        html += '</div>';

        resultsDiv.innerHTML = html;
        showToast('success', 'Report generated successfully');
    }

    function printReport() {
        var asOfDate = document.getElementById('as_of_date').value;
        var officeId = document.getElementById('office_id').value;
        var groupByOffice = document.getElementById('group_by_office').checked;

        var url = '{{ route("accounting.bank.outstanding.print") }}'
            + '?as_of_date=' + encodeURIComponent(asOfDate)
            + '&office_id=' + encodeURIComponent(officeId)
            + '&group_by_office=' + (groupByOffice ? '1' : '0');

        window.open(url, '_blank');
    }

    function exportExcel() {
        var asOfDate = document.getElementById('as_of_date').value;
        var officeId = document.getElementById('office_id').value;
        var groupByOffice = document.getElementById('group_by_office').checked;

        var url = '{{ route("accounting.bank.outstanding.export-excel") }}'
            + '?as_of_date=' + encodeURIComponent(asOfDate)
            + '&office_id=' + encodeURIComponent(officeId)
            + '&group_by_office=' + (groupByOffice ? '1' : '0');

        window.location.href = url;
    }
    </script>
    @endpush
</x-layout>
