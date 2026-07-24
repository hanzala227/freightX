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

        .form-label-box { background: #eef1f5; width: 130px; padding: 4px 8px; font-size: 11px; color: #333; min-height: 24px; display: flex; align-items: center; flex-shrink: 0; font-weight: 600; }
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

        .text-danger { color: #e73d4a; }
        .text-blue { color: #3b82f6 !important; }
        .fw-600 { font-weight: 600; }
        .num { text-align: right; font-family: 'Courier New', monospace; }

        /* Summary Section inside report */
        .summary-section { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0; }
        @media (max-width: 900px) { .summary-section { grid-template-columns: 1fr; } }
        .summary-col { padding: 14px 16px; border-right: 1px solid #e2e8f0; }
        .summary-col:last-child { border-right: none; }
        .summary-title { font-weight: 700; font-size: 11px; text-transform: uppercase; margin-bottom: 12px; color: #475569; letter-spacing: 0.3px; }
        .summary-row { display: flex; align-items: center; margin-bottom: 8px; justify-content: flex-end; }
        .summary-label { font-size: 11px; color: #64748b; margin-right: 12px; text-align: right; width: 130px; }
        .summary-val-box { background: #f1f5f9; width: 140px; height: 22px; display: flex; align-items: center; justify-content: flex-end; padding: 0 6px; font-size: 11px; font-family: 'Courier New', monospace; color: #334155; border: 1px solid #e2e8f0; }
        .summary-val-highlight { background: #eff6ff; border-color: #93c5fd; color: #2563eb; font-weight: 700; }
        .diff-footer { padding: 10px 16px; border-top: 1px solid #e2e8f0; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; background: #f8fafc; }

        /* Detail Tables — Ocean Module style */
        .detail-title { font-size: 11px; font-weight: 700; color: #475569; margin: 16px 0 8px 0; text-transform: uppercase; letter-spacing: 0.3px; padding-left: 2px; }
        .detail-title:first-child { margin-top: 0; }
        .grid-container { width: 100%; overflow-x: auto; margin-bottom: 16px; }
        .grid-table { border-collapse: collapse; width: 100%; font-size: 11px; }
        .grid-table th { background: #f8fafc; color: #475569; font-weight: 600; border: 1px solid #e2e8f0; padding: 4px 6px; white-space: nowrap; text-align: left; }
        .grid-table th.num { text-align: right; }
        .grid-table td { padding: 3px 6px; border: 1px solid #e2e8f0; white-space: nowrap; color: #334155; }
        .grid-table td.num { text-align: right; font-family: 'Courier New', monospace; }
        .grid-table td.center { text-align: center; }
        .grid-table tbody tr:hover { background: #f1f5f9; }
        .grid-table tbody tr.reconciled { background: #f0fdf4; }
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
        .toast.error { border-left: 3px solid #ef4444; }
        .toast.info { border-left: 3px solid #3b82f6; }
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
                <li><i class="fa fa-angle-right"></i><span style="color: #333; font-weight: 700;">Bank Reconciliation</span></li>
            </ul>
        </div>

        <div class="portlet light">
            <div class="portlet-title">
                <span class="caption-subject"><i class="fa fa-balance-scale" style="color:#3b82f6;margin-right:6px;"></i>Bank Reconciliation</span>
            </div>
            <div class="portlet-body">
                <div class="report-grid">
                    <div>
                        <div class="form-group-row">
                            <div class="form-label-box"><span class="text-danger">*</span>Bank</div>
                            <div class="form-input-box">
                                <select id="bank_name" class="form-control-gf">
                                    <option value="">Select Bank...</option>
                                    @foreach($bankNames as $name)
                                        <option value="{{ $name }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-group-row">
                            <div class="form-label-box"><span class="text-danger">*</span>Period</div>
                            <div class="form-input-box">
                                <input type="date" id="period_date" class="form-control-gf" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="button-row">
                    <button class="btn-action-round primary" onclick="viewReport()"><i class="fa fa-calculator"></i> Calculate</button>
                    <button class="btn-action-round" onclick="printReport()"><i class="fa fa-print"></i> Print</button>
                    <button class="btn-action-round green" onclick="exportExcel()"><i class="fa fa-file-excel-o"></i> Excel</button>
                </div>

                <div id="report-content">
                    <div class="empty-state" id="empty-state">
                        <i class="fa fa-inbox"></i>
                        <div>Select a bank and period, then click <strong>Calculate</strong>.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    var _lastData = null;

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

    function viewReport() {
        var bankName = document.getElementById('bank_name').value;
        var periodDate = document.getElementById('period_date').value;

        if (!bankName) { showToast('error', 'Please select a bank.'); return; }
        if (!periodDate) { showToast('error', 'Please select a period date.'); return; }

        var contentDiv = document.getElementById('report-content');
        contentDiv.innerHTML = '<div class="loading-overlay"><i class="fa fa-spinner"></i><div style="margin-top:8px;">Calculating report...</div></div>';

        var fd = new FormData();
        fd.append('bank_name', bankName);
        fd.append('period_date', periodDate);

        fetch('{{ route("accounting.bank.reconciliation.view") }}', {
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
            if (!data.success) {
                contentDiv.innerHTML = '<div class="empty-state"><i class="fa fa-exclamation-triangle" style="color:#ef4444;"></i><div>' + esc(data.message || 'Failed.') + '</div></div>';
                showToast('error', data.message || 'Failed');
                return;
            }
            _lastData = data;
            renderReport(data);
        })
        .catch(function(err) {
            console.error(err);
            contentDiv.innerHTML = '<div class="empty-state"><i class="fa fa-exclamation-triangle" style="color:#ef4444;"></i><div>An error occurred while generating the report.</div></div>';
            showToast('error', 'Failed to generate report');
        });
    }

    function renderReport(data) {
        var s = data.summary;
        var html = '';

        // === BALANCE SUMMARY PANEL ===
        html += '<div class="portlet light">';
        html += '<div class="portlet-title"><span class="caption-subject"><i class="fa fa-balance-scale" style="color:#3b82f6;margin-right:6px;"></i>Balance Summary</span><span style="font-size:10px;color:#64748b;">CURRENCY: ' + esc(data.bank_currency) + '</span></div>';
        html += '<div class="portlet-body">';

        html += '<div class="summary-section">';

        // Statement Balance
        html += '<div class="summary-col">';
        html += '<div class="summary-title">Statement Balance</div>';
        html += summaryRow('Beginning Balance', s.statement.beginning_balance);
        html += summaryRow('Deposit and Credit', s.statement.deposit_credit);
        html += summaryRow('Checks and Debit', s.statement.checks_debit);
        html += summaryRow('Ending Balance', s.statement.ending_balance, true);
        html += '</div>';

        // Outstanding
        html += '<div class="summary-col">';
        html += '<div class="summary-title">Outstanding</div>';
        html += summaryRow('Deposit and Credit', s.outstanding.deposit_credit);
        html += summaryRow('Checks and Debit', s.outstanding.checks_debit);
        html += summaryRow('Actual Ending', s.outstanding.actual_ending, true);
        html += '</div>';

        // Book Balance
        html += '<div class="summary-col">';
        html += summaryRow('Beginning Balance', s.book.beginning_balance);
        html += summaryRow('Deposit and Credit', s.book.deposit_credit);
        html += summaryRow('Checks and Debit', s.book.checks_debit);
        html += summaryRow('Ending Balance', s.book.ending_balance, true);
        html += '</div>';

        html += '</div>'; // summary-section

        var diff = s.bank_book_diff;
        var diffColor = diff < 0 ? '#dc2626' : '#2563eb';
        html += '<div class="diff-footer">Bank & Book Difference: <span style="color:' + diffColor + ';font-size:12px;">' + fmt(diff) + '</span></div>';
        html += '</div>'; // portlet-body
        html += '</div>'; // portlet

        // === OUTSTANDING DETAIL ===
        html += '<div class="portlet light">';
        html += '<div class="portlet-title"><span class="caption-subject"><i class="fa fa-list" style="color:#22c55e;margin-right:6px;"></i>Outstanding Detail</span></div>';
        html += '<div class="portlet-body">';

        // Deposit & Credit table
        html += '<div class="detail-title">Deposit & Credit</div>';
        if (data.deposit_rows.length) {
            html += buildReconTable(data.deposit_rows, 'deposit');
        } else {
            html += '<div style="padding:12px;color:#94a3b8;font-size:11px;">No outstanding deposits.</div>';
        }

        // Checks & Debit table
        html += '<div class="detail-title">Checks & Debit</div>';
        if (data.check_rows.length) {
            html += buildReconTable(data.check_rows, 'check');
        } else {
            html += '<div style="padding:12px;color:#94a3b8;font-size:11px;">No outstanding checks.</div>';
        }

        html += '</div>'; // portlet-body
        html += '</div>'; // portlet

        document.getElementById('report-content').innerHTML = html;
        showToast('success', 'Report calculated');
    }

    function summaryRow(label, val, highlight) {
        var cls = highlight ? ' summary-val-box summary-val-highlight' : ' summary-val-box';
        return '<div class="summary-row"><div class="summary-label">' + label + '</div><div class="' + cls + '">' + fmt(val) + '</div></div>';
    }

    function buildReconTable(rows, type) {
        var html = '<div class="grid-container"><table class="grid-table"><thead><tr>';
        html += '<th>Post Date</th>';
        html += '<th>Check No.</th>';
        html += '<th>' + (type === 'deposit' ? 'Received From' : 'Pay To') + '</th>';
        html += '<th style="text-align:center;">Currency</th>';
        html += '<th class="num">Amount</th>';
        html += '<th style="text-align:center;">Office</th>';
        html += '<th style="text-align:center;">' + (type === 'deposit' ? 'Deposit' : 'Clear') + '</th>';
        html += '<th style="text-align:center;">' + (type === 'deposit' ? 'Deposit Date' : 'Clear Date') + '</th>';
        html += '<th style="text-align:center;">Void</th>';
        html += '<th style="text-align:center;">Void Date</th>';
        html += '</tr></thead><tbody>';

        var total = 0;
        if (!rows.length) {
            html += '<tr><td colspan="10" style="padding:15px;color:#94a3b8;text-align:center;">No Data Available</td></tr>';
        }

        for (var i = 0; i < rows.length; i++) {
            var r = rows[i];
            total += r.amount;
            var isReconciled = (type === 'deposit' ? r.deposit : r.clear) === 'Y';
            html += '<tr' + (isReconciled ? ' class="reconciled"' : '') + '>';
            html += '<td>' + esc(r.post_date) + '</td>';
            html += '<td class="fw-600">' + esc(r.check_no) + '</td>';
            html += '<td>' + esc(type === 'deposit' ? r.received_from : r.pay_to) + '</td>';
            html += '<td class="center">' + esc(r.currency) + '</td>';
            html += '<td class="num">' + fmt(r.amount) + '</td>';
            html += '<td class="center">' + esc(r.office) + '</td>';
            html += '<td class="center">' + esc(type === 'deposit' ? r.deposit : r.clear) + '</td>';
            html += '<td class="center">' + esc(type === 'deposit' ? r.deposit_date : r.clear_date) + '</td>';
            html += '<td class="center">' + esc(r.void) + '</td>';
            html += '<td class="center">' + esc(r.void_date) + '</td>';
            html += '</tr>';
        }

        html += '<tr class="total-row">';
        html += '<td colspan="4">Total (' + rows.length + ')</td>';
        html += '<td class="num">' + fmt(total) + '</td>';
        html += '<td colspan="5"></td>';
        html += '</tr>';
        html += '</tbody></table></div>';
        return html;
    }

    function printReport() {
        var bankName = document.getElementById('bank_name').value;
        var periodDate = document.getElementById('period_date').value;
        if (!bankName) { showToast('error', 'Calculate a report first.'); return; }
        window.open('{{ route("accounting.bank.reconciliation.print") }}?bank_name=' + encodeURIComponent(bankName) + '&period_date=' + encodeURIComponent(periodDate), '_blank');
    }

    function exportExcel() {
        var bankName = document.getElementById('bank_name').value;
        var periodDate = document.getElementById('period_date').value;
        if (!bankName) { showToast('error', 'Calculate a report first.'); return; }
        window.location.href = '{{ route("accounting.bank.reconciliation.export-excel") }}?bank_name=' + encodeURIComponent(bankName) + '&period_date=' + encodeURIComponent(periodDate);
    }
    </script>
    @endpush
</x-layout>
