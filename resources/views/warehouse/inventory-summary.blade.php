<x-layout>
    @push('styles')
    <x-list-styles />
    <style>
        .freeze-table-group { display: flex; overflow: hidden; border: 1px solid #e2e8f0; border-radius: 4px; }
        .freeze-table-main { flex: 0 0 auto; background: #fff; z-index: 10; border-right: 2px solid #cbd5e1; }
        .freeze-table-scroll { flex: 1; overflow-x: auto; background: #fff; }
        .table-gf { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .table-gf thead th { background: #f8fafc; border-bottom: 1px solid #cbd5e1; border-right: 1px solid #e2e8f0; padding: 4px 8px; font-size: 10px; color: #475569; font-weight: 700; text-align: left; height: 24px; white-space: nowrap; }
        .table-gf tbody td { padding: 4px 8px; font-size: 10px; color: #334155; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; height: 24px; }
        .table-gf tbody tr:hover td { background-color: #f1f5f9 !important; }
        .text-right { text-align: right !important; }
    </style>
    @endpush

    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
                <li>Warehouse <i class="fa fa-angle-right"></i></li>
                <li><span style="color: #333; font-weight: 700;">Inventory Summary List</span></li>
            </ul>
        </div>

        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject">Inventory Summary List</span>
                </div>
                <div class="actions" style="display:flex;gap:4px;position:relative;align-items:center;">
                    <div class="btn-group">
                        <button class="btn-action-round"><i class="fa fa-filter"></i> Filter</button>
                        <button class="btn-action-round white"><i class="fa fa-file-excel-o"></i> Excel</button>
                    </div>
                </div>
            </div>

            <div class="portlet-body">
                <div class="freeze-table-group">
                    <!-- Fixed Area (Left Part) -->
                    <div class="freeze-table-main">
                        <table class="table-gf table-fixed table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 141px;">Customer</th>
                                    <th style="width: 95px;">SKU No.</th>
                                    <th style="width: 105px;">Customer P.O.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>SAFR</td><td><a href="javascript:void(0)" class="col-link">CAIU9960453</a></td><td></td></tr>
                                <tr><td>SAFR</td><td><a href="javascript:void(0)" class="col-link">CAIU9960454</a></td><td></td></tr>
                                <tr><td>SAFR</td><td><a href="javascript:void(0)" class="col-link">CAIU9960452</a></td><td></td></tr>
                                <tr><td>LORAMA GROUP INC</td><td><a href="javascript:void(0)" class="col-link">2111111004</a></td><td></td></tr>
                                <tr><td>JAX IMPORT (CANADA) LTD.</td><td><a href="javascript:void(0)" class="col-link">JAXSKU001</a></td><td></td></tr>
                                <tr><td>ABBOTT INC</td><td><a href="javascript:void(0)" class="col-link">DYC5000</a></td><td></td></tr>
                                <tr><td>ABBOTT INC</td><td><a href="javascript:void(0)" class="col-link">DYC6000</a></td><td></td></tr>
                                <tr><td>3M COMPANY</td><td><a href="javascript:void(0)" class="col-link">12536477</a></td><td></td></tr>
                                <tr><td>3M COMPANY</td><td><a href="javascript:void(0)" class="col-link">OFFICE CHAIR</a></td><td></td></tr>
                                <tr><td>3M COMPANY</td><td><a href="javascript:void(0)" class="col-link">OFFICE CHAIR</a></td><td></td></tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Scrollable Area (Right Part) -->
                    <div class="freeze-table-scroll">
                        <table class="table-gf table-fixed table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 200px;">Product Description</th>
                                    <th style="width: 100px;">B/L No.</th>
                                    <th style="width: 60px;">Office</th>
                                    <th style="width: 70px;">UPC/EAN</th>
                                    <th style="width: 100px;">On Hand Qty</th>
                                    <th style="width: 100px;">Allocated Qty</th>
                                    <th style="width: 100px;">Available Qty</th>
                                    <th style="width: 100px;">Qty Unit</th>
                                    <th style="width: 100px;">Weight</th>
                                    <th style="width: 100px;">Measurement</th>
                                    <th style="width: 100px;">Inner Pack</th>
                                    <th style="width: 100px;">On Hand Pcs</th>
                                    <th style="width: 100px;">Allocated Pcs</th>
                                    <th style="width: 100px;">Available Pcs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data Row 1 -->
                                <tr>
                                    <td>AUTO PARTS</td><td>BLTEST01</td><td>LAX</td><td></td>
                                    <td class="text-right">1.00</td><td class="text-right">0.00</td><td class="text-right">1.00</td>
                                    <td>CONTAINER</td><td class="text-right">10,000.00 KG</td><td class="text-right">51.00 CBM</td>
                                    <td class="text-right"></td><td class="text-right">0.00</td><td class="text-right">0.00</td><td class="text-right">0.00</td>
                                </tr>
                                <!-- Data Row 2 -->
                                <tr>
                                    <td></td><td></td><td>LAX</td><td></td>
                                    <td class="text-right">700.00</td><td class="text-right">0.00</td><td class="text-right">700.00</td>
                                    <td>CARTON(S)</td><td class="text-right"></td><td class="text-right"></td>
                                    <td class="text-right"></td><td class="text-right">0.00</td><td class="text-right">0.00</td><td class="text-right">0.00</td>
                                </tr>
                                <!-- Data Row 3 -->
                                <tr>
                                    <td></td><td></td><td>LAX</td><td></td>
                                    <td class="text-right">26.00</td><td class="text-right">0.00</td><td class="text-right">26.00</td>
                                    <td>PALLET(S)</td><td class="text-right"></td><td class="text-right"></td>
                                    <td class="text-right"></td><td class="text-right">0.00</td><td class="text-right">0.00</td><td class="text-right">0.00</td>
                                </tr>
                                <!-- Data Row 4 -->
                                <tr>
                                    <td>RHEOFAL 301</td><td></td><td>LAX</td><td></td>
                                    <td class="text-right">2.00</td><td class="text-right">0.00</td><td class="text-right">2.00</td>
                                    <td></td><td class="text-right">1,300.00 KG</td><td class="text-right">2.05 CBM</td>
                                    <td class="text-right"></td><td class="text-right">2.00</td><td class="text-right">0.00</td><td class="text-right">2.00</td>
                                </tr>
                                <!-- Data Row 5 -->
                                <tr>
                                    <td>IPHONE 13 PRO MAX CASE</td><td></td><td>STI</td><td></td>
                                    <td class="text-right">200.00</td><td class="text-right">0.00</td><td class="text-right">200.00</td>
                                    <td></td><td class="text-right"></td><td class="text-right"></td>
                                    <td class="text-right"></td><td class="text-right">0.00</td><td class="text-right">0.00</td><td class="text-right">0.00</td>
                                </tr>
                                <!-- Data Row 6 -->
                                <tr>
                                    <td>HOOVER TYPE 1</td><td></td><td>LAX</td><td></td>
                                    <td class="text-right">-5.00</td><td class="text-right">10.00</td><td class="text-right">5.00</td>
                                    <td>CARTON(S)</td><td class="text-right">15.00 KG</td><td class="text-right"></td>
                                    <td class="text-right"></td><td class="text-right">-5.00</td><td class="text-right">10.00</td><td class="text-right">5.00</td>
                                </tr>
                                <!-- Data Row 7 -->
                                <tr>
                                    <td>HOOVER TYPE 2</td><td></td><td>LAX</td><td></td>
                                    <td class="text-right">-8.00</td><td class="text-right">10.00</td><td class="text-right">2.00</td>
                                    <td>CARTON(S)</td><td class="text-right">20.00 KG</td><td class="text-right"></td>
                                    <td class="text-right"></td><td class="text-right">-8.00</td><td class="text-right">10.00</td><td class="text-right">2.00</td>
                                </tr>
                                <!-- Data Row 8 -->
                                <tr>
                                    <td>SOYBEAN</td><td></td><td>STI</td><td></td>
                                    <td class="text-right">0.00</td><td class="text-right">200.00</td><td class="text-right">200.00</td>
                                    <td></td><td class="text-right"></td><td class="text-right"></td>
                                    <td class="text-right"></td><td class="text-right">0.00</td><td class="text-right">200.00</td><td class="text-right">200.00</td>
                                </tr>
                                <!-- Data Row 9 -->
                                <tr>
                                    <td></td><td></td><td>LAX</td><td></td>
                                    <td class="text-right">-200.00</td><td class="text-right">280.00</td><td class="text-right">80.00</td>
                                    <td></td><td class="text-right">22.00 KG</td><td class="text-right"></td>
                                    <td class="text-right"></td><td class="text-right">-200.00</td><td class="text-right">280.00</td><td class="text-right">80.00</td>
                                </tr>
                                <!-- Data Row 10 -->
                                <tr>
                                    <td></td><td></td><td>STI</td><td></td>
                                    <td class="text-right">20.00</td><td class="text-right">0.00</td><td class="text-right">20.00</td>
                                    <td></td><td class="text-right">22.00 KG</td><td class="text-right"></td>
                                    <td class="text-right"></td><td class="text-right">20.00</td><td class="text-right">0.00</td><td class="text-right">20.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="portlet-tool bottom" style="background: #f8fafc !important; padding: 4px 8px !important; border-top: 1px solid #cbd5e1 !important; border-bottom: none !important;">
                <div style="display:flex;justify-content:space-between;width:100%;align-items:center;">
                    <div id="pagination-container">
                        <ul class="pagination">
                            <li class="disabled"><span><i class="fa fa-angle-double-left"></i></span></li>
                            <li class="disabled"><span><i class="fa fa-angle-left"></i></span></li>
                            <li class="active"><span>1</span></li>
                            <li><span>2</span></li>
                            <li><span><i class="fa fa-angle-right"></i></span></li>
                            <li><span><i class="fa fa-angle-double-right"></i></span></li>
                        </ul>
                    </div>
                    <div style="font-size:10px;color:#64748b;">Showing 1 to 10 of 11 records</div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
