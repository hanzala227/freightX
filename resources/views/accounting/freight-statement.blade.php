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
        .text-danger{color:#e73d4a}
        .form-table{width:100%;border-collapse:collapse}
        .form-table td{padding:3px 4px;vertical-align:middle;font-size:11px;border:none}
        .form-table .flabel{background:#eef1f5;width:130px;padding:4px 8px;font-weight:600;color:#333;white-space:nowrap}
        .form-table .finput{padding-left:8px}
        .form-table input[type="radio"],.form-table input[type="checkbox"]{accent-color:#3b82f6;margin-right:3px}
        .form-table label{font-size:11px;color:#334155;cursor:pointer;white-space:nowrap}
        .form-table select,.gf-select{height:22px;border:1px solid #c2cad8;padding:1px 4px;font-size:11px;border-radius:2px;color:#333;background:#fff}
        .form-table input[type="date"]{height:22px;border:1px solid #c2cad8;padding:1px 4px;font-size:11px;border-radius:2px;color:#333;background:#fff}
        .radio-row{display:flex;gap:12px;flex-wrap:wrap;align-items:center}
        .check-row{display:flex;gap:10px;flex-wrap:wrap;align-items:center}
        .btn-search-gf{background:#4CAF50;color:#fff;border:1px solid #388E3C;border-radius:2px;padding:0 16px;height:26px;font-size:11px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:4px}
        .btn-search-gf:hover{background:#388E3C;color:#fff}
        .btn-action-round{background:#3b82f6;color:#fff;border:1px solid #2563eb;border-radius:2px;padding:0 12px;height:26px;font-size:11px;font-weight:600;display:inline-flex;align-items:center;justify-content:center;gap:4px;cursor:pointer;text-decoration:none;transition:all .15s;box-sizing:border-box}
        .btn-action-round:hover{background:#2563eb;color:#fff}
        .btn-action-round.secondary{background:#64748b;border-color:#475569}
        .btn-action-round.secondary:hover{background:#475569}
        .btn-action-round.green{background:#22c55e;border-color:#16a34a}
        .btn-action-round.green:hover{background:#16a34a}
        .loading-overlay{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(255,255,255,.7);z-index:9999;justify-content:center;align-items:center}
        .toast-container{position:fixed;top:16px;right:16px;z-index:10000;display:flex;flex-direction:column;gap:8px}
        .toast{padding:10px 16px;border-radius:4px;font-size:11px;font-weight:600;color:#fff;box-shadow:0 4px 12px rgba(0,0,0,.15);animation:slideIn .3s ease}
        .toast.success{background:#22c55e}
        .toast.error{background:#e73d4a}
        @keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}
        .info-box{margin-top:12px;padding:8px 12px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:4px;font-size:10px;color:#1e40af;line-height:1.5}
    </style>
    @endpush

    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a></li>
                <li><i class="fa fa-angle-right"></i>Accounting</li>
                <li><i class="fa fa-angle-right"></i>Report</li>
                <li><i class="fa fa-angle-right"></i><span style="color:#333;font-weight:700;">Freight Statement</span></li>
            </ul>
        </div>

        <div class="portlet light">
            <div class="portlet-title">
                <span class="caption-subject">Freight Statement</span>
            </div>
            <div class="portlet-body">
                <table class="form-table">
                    <tr>
                        <td class="flabel"><span class="text-danger">*</span>Partner</td>
                        <td class="finput" colspan="2">
                            <div class="radio-row" style="margin-bottom:3px;">
                                <label><input type="radio" name="partner_type" value="agent_customer" checked id="partnerTypeAC"> Agent/Customer</label>
                                <label><input type="radio" name="partner_type" value="account_group" id="partnerTypeAG"> Account Group</label>
                            </div>
                            <select id="billToId" class="gf-select" style="width:220px;">
                                <option value="">Select...</option>
                                @foreach($tradePartners as $tp)<option value="{{ $tp->id }}">{{ $tp->name }}</option>@endforeach
                            </select>
                            <select id="accountGroupId" class="gf-select" style="width:220px;display:none;">
                                <option value="">Select...</option>
                                @foreach($accountGroups as $ag)<option value="{{ $ag }}">{{ $ag }}</option>@endforeach
                            </select>
                        </td>
                        <td class="flabel">Report by</td>
                        <td class="finput" colspan="2">
                            <div class="check-row">
                                <label><input type="checkbox" id="transTypeAll" checked> All</label>
                                <label><input type="checkbox" class="trans-type-check" value="debit" checked> Debit</label>
                                <label><input type="checkbox" class="trans-type-check" value="credit" checked> Credit</label>
                                <label><input type="checkbox" class="trans-type-check" value="ar" checked> A/R</label>
                                <label><input type="checkbox" class="trans-type-check" value="ap" checked> A/P</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="flabel"><span class="text-danger">*</span>Period</td>
                        <td class="finput" colspan="2">
                            <div class="radio-row">
                                <select id="periodType" class="gf-select" style="width:100px;">
                                    <option value="post_date">Post Date</option>
                                </select>
                                <input type="date" id="asOfDate" value="{{ date('Y-m-d') }}" style="width:150px;height:22px;border:1px solid #c2cad8;padding:1px 4px;font-size:11px;border-radius:2px;">
                                <label><input type="radio" name="period_mode" value="as_of_today" checked> As of Today</label>
                            </div>
                        </td>
                        <td class="flabel">Office</td>
                        <td class="finput" colspan="2">
                            <select id="officeId" class="gf-select" style="width:220px;">
                                <option value="">Select...</option>
                                @foreach($offices as $office)<option value="{{ $office->id }}">{{ $office->name }}</option>@endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="flabel">Filter by</td>
                        <td class="finput" colspan="2">
                            <div class="radio-row">
                                <label><input type="radio" name="payment_status" value="all" checked> All</label>
                                <label><input type="radio" name="payment_status" value="open"> Open</label>
                                <label><input type="radio" name="payment_status" value="paid"> Paid</label>
                            </div>
                        </td>
                        <td class="flabel">More Options</td>
                        <td class="finput" colspan="2">
                            <div class="check-row" style="flex-wrap:wrap;">
                                <label><input type="checkbox" id="hideOverpaid"> Do Not Show Overpaid Invoice(s)</label>
                                <label><input type="checkbox" id="invoiceLocalReceived"> Invoice(Local A/R) Received Only</label>
                                <label><input type="checkbox" id="showBookingNumber"> Show Booking Number</label>
                            </div>
                        </td>
                    </tr>
                </table>
                <div style="text-align:center;margin-top:12px;padding-top:10px;border-top:1px solid #e2e8f0;">
                    <button type="button" class="btn-search-gf" id="btnSearch"><i class="fa fa-search"></i> View</button>
                </div>
            </div>
        </div>


    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        var partnerTypeAC = document.getElementById('partnerTypeAC');
        var partnerTypeAG = document.getElementById('partnerTypeAG');
        var billToIdSel = document.getElementById('billToId');
        var accountGroupIdSel = document.getElementById('accountGroupId');

        function togglePartnerType() {
            if (partnerTypeAG.checked) {
                billToIdSel.style.display = 'none';
                billToIdSel.value = '';
                accountGroupIdSel.style.display = '';
            } else {
                accountGroupIdSel.style.display = 'none';
                accountGroupIdSel.value = '';
                billToIdSel.style.display = '';
            }
        }
        partnerTypeAC.addEventListener('change', togglePartnerType);
        partnerTypeAG.addEventListener('change', togglePartnerType);

        var transTypeAll = document.getElementById('transTypeAll');
        var transChecks = document.querySelectorAll('.trans-type-check');
        transTypeAll.addEventListener('change', function() {
            for (var i = 0; i < transChecks.length; i++) transChecks[i].checked = this.checked;
        });
        for (var i = 0; i < transChecks.length; i++) {
            transChecks[i].addEventListener('change', function() {
                var allChecked = true;
                for (var j = 0; j < transChecks.length; j++) { if (!transChecks[j].checked) { allChecked = false; break; } }
                transTypeAll.checked = allChecked;
            });
        }

        var periodToday = document.querySelector('input[name="period_mode"][value="as_of_today"]');
        if (periodToday) {
            periodToday.addEventListener('change', function() {
                document.getElementById('asOfDate').value = '{{ date("Y-m-d") }}';
            });
        }

        function showToast(type, msg) {
            var container = document.getElementById('toastContainer');
            var toast = document.createElement('div');
            toast.className = 'toast ' + type;
            toast.textContent = msg;
            container.appendChild(toast);
            setTimeout(function() { toast.remove(); }, 4000);
        }

        function buildParams() {
            var params = new URLSearchParams();
            params.set('as_of_date', document.getElementById('asOfDate').value);
            var pt = partnerTypeAG.checked ? 'account_group' : 'agent_customer';
            params.set('partner_type', pt);
            if (pt === 'account_group') { var ag = accountGroupIdSel.value; if (ag) params.set('account_group', ag); }
            else { var bt = billToIdSel.value; if (bt) params.set('bill_to_id', bt); }
            var ps = document.querySelector('input[name="payment_status"]:checked');
            params.set('payment_status', ps ? ps.value : 'all');
            params.set('hide_overpaid', document.getElementById('hideOverpaid').checked ? '1' : '0');
            params.set('invoice_local_received', document.getElementById('invoiceLocalReceived').checked ? '1' : '0');
            params.set('show_booking_number', document.getElementById('showBookingNumber').checked ? '1' : '0');
            var oe = document.getElementById('officeId');
            if (oe.value) params.set('office_id', oe.value);
            var st = [];
            for (var i = 0; i < transChecks.length; i++) { if (transChecks[i].checked) st.push(transChecks[i].value); }
            params.set('trans_type', (st.length > 0 && !transTypeAll.checked) ? st.join(',') : 'all');
            return params;
        }

        document.getElementById('btnSearch').addEventListener('click', function() {
            var billTo = billToIdSel.value;
            var accGroup = accountGroupIdSel.value;
            if (!billTo && !accGroup) {
                showToast('error', 'Please select a Partner or Account Group.');
                return;
            }
            window.open('{{ route("accounting.report.freight-statement.print") }}?' + buildParams().toString(), '_blank');
        });

        document.getElementById('btnExport').addEventListener('click', function() {
            window.location = '{{ route("accounting.report.freight-statement.export-excel") }}?' + buildParams().toString();
        });
    });
    </script>
</x-layout>
