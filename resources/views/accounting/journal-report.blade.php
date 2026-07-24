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
        .form-table{width:100%;border-collapse:collapse}
        .form-table td{padding:3px 4px;vertical-align:middle;font-size:11px;border:none}
        .form-table .flabel{background:#eef1f5;width:130px;padding:4px 8px;font-weight:600;color:#333;white-space:nowrap}
        .form-table .finput{padding-left:8px}
        .form-table select,.gf-select{height:22px;border:1px solid #c2cad8;padding:1px 4px;font-size:11px;border-radius:2px;color:#333;background:#fff}
        .form-table input[type="date"]{height:22px;border:1px solid #c2cad8;padding:1px 4px;font-size:11px;border-radius:2px;color:#333;background:#fff}
        .btn-blue{background:#3b82f6;color:#fff;border:1px solid #2563eb;border-radius:2px;padding:0 14px;height:22px;font-size:11px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:4px;box-sizing:border-box}
        .btn-blue:hover{background:#2563eb;color:#fff}
        .btn-green{background:#22c55e;color:#fff;border:1px solid #16a34a;border-radius:2px;padding:0 14px;height:22px;font-size:11px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:4px;box-sizing:border-box}
        .btn-green:hover{background:#16a34a;color:#fff}
        .button-row{display:flex;gap:8px;align-items:center;margin-top:10px;padding-top:10px;border-top:1px solid #e2e8f0}
    </style>
    @endpush

    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a></li>
                <li><i class="fa fa-angle-right"></i>Accounting</li>
                <li><i class="fa fa-angle-right"></i>Report</li>
                <li><i class="fa fa-angle-right"></i><span style="color:#333;font-weight:700;">Journal Report</span></li>
            </ul>
        </div>

        <div class="portlet light">
            <div class="portlet-title">
                <span class="caption-subject">Journal Report</span>
            </div>
            <div class="portlet-body">
                <table class="form-table">
                    <tr>
                        <td class="flabel">Period</td>
                        <td class="finput" colspan="3">
                            <div style="display:flex;align-items:center;gap:4px;">
                                <input type="date" id="startDate" value="{{ date('Y-m-d') }}" style="width:140px;">
                                <span style="font-size:11px;color:#64748b;">~</span>
                                <input type="date" id="endDate" value="{{ date('Y-m-d') }}" style="width:140px;">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="flabel">Office</td>
                        <td class="finput">
                            <select id="officeId" class="gf-select" style="width:220px;">
                                <option value="">All</option>
                                @foreach($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                </table>

                <div class="button-row">
                    <button type="button" class="btn-blue" id="btnPreview"><i class="fa fa-search"></i> Preview</button>
                    <button type="button" class="btn-blue" id="btnPdf"><i class="fa fa-file-pdf-o"></i> Download PDF</button>
                    <button type="button" class="btn-green" id="btnExcel"><i class="fa fa-file-excel-o"></i> Download Excel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function params() {
            return 'start_date=' + encodeURIComponent(document.getElementById('startDate').value) +
                   '&end_date=' + encodeURIComponent(document.getElementById('endDate').value) +
                   (document.getElementById('officeId').value ? '&office_id=' + encodeURIComponent(document.getElementById('officeId').value) : '');
        }

        document.getElementById('btnPreview').addEventListener('click', function() {
            window.open('{{ route("accounting.report.journal-report.preview") }}?' + params(), '_blank');
        });

        document.getElementById('btnPdf').addEventListener('click', function() {
            window.open('{{ route("accounting.report.journal-report.print") }}?' + params(), '_blank');
        });

        document.getElementById('btnExcel').addEventListener('click', function() {
            window.location.href = '{{ route("accounting.report.journal-report.export-excel") }}?' + params();
        });
    });
    </script>
</x-layout>
