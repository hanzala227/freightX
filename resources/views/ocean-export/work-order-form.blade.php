@php
    $isEdit = isset($workOrder);
    
    // Primary fields
    $woNo = $isEdit ? $workOrder->work_order_no : ($prefilledData['booking_no'] ?? $workOrderNo);
    $subject = old('subject', $isEdit ? $workOrder->subject : 'PICKUP & DELIVERY ORDER');
    $issueDate = old('issue_date', $isEdit ? ($workOrder->issue_date ? $workOrder->issue_date->format('Y-m-d') : '') : date('Y-m-d'));
    $dueDate = old('due_date', $isEdit ? ($workOrder->due_date ? $workOrder->due_date->format('Y-m-d') : '') : '');
    $vendorId = old('vendor_id', $isEdit ? $workOrder->vendor_id : '');
    $truckerAddr = old('extra_data.trucker_address', $isEdit ? ($workOrder->extra_data['trucker_address'] ?? '') : '');
    
    // Pickup locations
    $emptyPickupLocationId = old('empty_pickup_location_id', $isEdit ? $workOrder->empty_pickup_location_id : '');
    $emptyPickupAddress = old('empty_pickup_address', $isEdit ? $workOrder->empty_pickup_address : '');
    $emptyPickupRef = old('empty_pickup_ref', $isEdit ? $workOrder->empty_pickup_ref : '');
    $emptyPickupDate = old('empty_pickup_date', $isEdit ? $workOrder->empty_pickup_date : '');
    
    $freightPickupLocationId = old('freight_pickup_location_id', $isEdit ? $workOrder->freight_pickup_location_id : '');
    $freightPickupAddress = old('freight_pickup_address', $isEdit ? $workOrder->freight_pickup_address : '');
    $freightPickupRef = old('freight_pickup_ref', $isEdit ? $workOrder->freight_pickup_ref : '');
    $freightPickupDate = old('freight_pickup_date', $isEdit ? $workOrder->freight_pickup_date : '');
    
    // Booking info
    $carrierId = old('carrier_id', $isEdit ? $workOrder->carrier_id : ($prefilledData['carrier_id'] ?? ''));
    $carrierBkgNo = old('carrier_bkg_no', $isEdit ? $workOrder->carrier_bkg_no : ($prefilledData['carrier_bkg_no'] ?? ''));
    $vesselInfo = old('vessel_info', $isEdit ? $workOrder->vessel_info : ($prefilledData['vessel_info'] ?? ''));
    $placeOfReceipt = old('place_of_receipt', $isEdit ? $workOrder->place_of_receipt : ($prefilledData['place_of_receipt'] ?? ''));
    $etd = old('etd', $isEdit ? $workOrder->etd : ($prefilledData['etd'] ?? ''));
    
    // Metrics
    $totalPackages = old('total_packages', $isEdit ? $workOrder->total_packages : 0);
    $packageUnit = old('package_unit', $isEdit ? $workOrder->package_unit : 'CARTON(S)');
    $containerQty = old('container_qty', $isEdit ? $workOrder->container_qty : '');
    $grossWeightKgs = old('gross_weight_kgs', $isEdit ? $workOrder->gross_weight_kgs : '0.00');
    $grossWeightLbs = old('gross_weight_lbs', $isEdit ? $workOrder->gross_weight_lbs : '0.00');
    
    // Bill To
    $showBillTo = old('show_bill_to', $isEdit ? $workOrder->show_bill_to : true);
    $billToId = old('bill_to_id', $isEdit ? $workOrder->bill_to_id : '');
    $billToAddress = old('bill_to_address', $isEdit ? $workOrder->bill_to_address : '');
    $billToRef = old('bill_to_ref', $isEdit ? $workOrder->bill_to_ref : '');
    
    // Instructions & Footer
    $doNotBreakDownPallet = old('do_not_break_down_pallet', $isEdit ? $workOrder->do_not_break_down_pallet : false);
    $instructions = old('instructions', $isEdit ? $workOrder->instructions : '');
    
    $mblNo = $prefilledData['mbl_no'] ?? '';
    $hblNo = $prefilledData['hbl_no'] ?? '';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pickup & Delivery Order | GoFreight</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { background-color: #2b2b2b; font-family: 'Inter', Arial, sans-serif; margin: 0; padding: 0; color: #333; }
        
        /* Top Navigation Bar */
        .nav-bar {
            background-color: #1a1a1a;
            height: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 15px;
            position: sticky;
            top: 0;
            z-index: 2000;
            border-bottom: 1px solid #000;
        }

        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .nav-btn {
            background: none; border: none; width: 28px; height: 28px; cursor: pointer;
            background-size: contain; background-repeat: no-repeat; opacity: 0.8; transition: 0.2s;
        }
        .nav-btn:hover { opacity: 1; transform: scale(1.1); }
        .nav-btn.pdf { background-image: url('https://img.icons8.com/color/48/pdf.png'); }
        .nav-btn.print { background-image: url('https://img.icons8.com/color/48/print.png'); }
        .nav-btn.mail { background-image: url('https://img.icons8.com/color/48/gmail-new.png'); }
        
        .select-lang { background: #fff; border: 1px solid #ccc; height: 22px; font-size: 11px; padding: 0 5px; border-radius: 2px; }
        .zoom-controls { display: flex; gap: 2px; }
        .zoom-btn { background: #333; color: #fff; border: 1px solid #444; width: 30px; height: 28px; cursor: pointer; font-size: 12px; }
        .zoom-btn:hover { background: #444; }

        /* Workspace */
        .workspace { padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .doc-wrap {
            background-color: #fff;
            width: 950px;
            min-height: 1200px;
            padding: 40px;
            box-shadow: 0 0 40px rgba(0,0,0,0.5);
            transform-origin: top center;
            transition: transform 0.2s;
        }

        /* Topbar Block (Data Source) */
        .datasource-block {
            width: 950px;
            margin-bottom: 10px;
            color: #ddd;
            font-size: 11px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .ds-select { background: #fff; border: 1px solid #ccc; height: 24px; font-size: 11px; width: 180px; }

        /* Document Header */
        .doc-header { display: grid; grid-template-columns: 15% 45% 2% 38%; margin-bottom: 20px; }
        .office-name { color: #1a4a7c; font-size: 24px; font-weight: 800; margin: 0; }
        .office-des { font-size: 10px; line-height: 1.4; color: #444; }
        .title-box { border: 2px solid #1a4a7c; text-align: center; padding: 10px; }
        .title-select { border: 1px solid #ccc; width: 100%; height: 30px; font-weight: 800; font-size: 14px; text-align: center; }
        
        .issue-info { display: grid; grid-template-columns: 52% 9% 10% 11% 18%; font-size: 10px; margin-top: 10px; border-top: 1px solid #eee; padding-top: 5px; }
        .issue-box { border: 1px solid #ccc; padding: 2px 5px; height: 20px; font-weight: bold; }

        /* Content Grid */
        .main-content { display: grid; grid-template-columns: 48% 4% 48%; margin-top: 10px; }
        .section-block { border: 1px solid #ccc; margin-bottom: 15px; }
        .section-title { background: #fff; padding: 4px 8px; font-size: 10px; font-weight: 800; border-bottom: 1px solid #ccc; display: flex; justify-content: space-between; align-items: center; }
        .partner-picker { height: 22px; font-size: 10px; width: 150px; border: 1px solid #ccc; background: #fff; }
        .data-area { padding: 5px; }
        .textarea-gf { width: 100%; border: none; font-size: 11px; resize: none; outline: none; line-height: 1.4; font-family: inherit; }
        
        .field-row { display: flex; align-items: center; font-size: 10px; margin-bottom: 3px; }
        .field-label { width: 60px; font-weight: bold; }
        .field-input { border: 1px solid #ccc; height: 20px; font-size: 10px; padding: 0 5px; flex: 1; }

        .info-grid { border: 1px solid #ccc; display: grid; grid-template-columns: 1fr 1fr; }
        .info-cell { padding: 4px 8px; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc; }
        .info-cell:nth-child(2n) { border-right: none; }
        .cell-title { font-size: 9px; font-weight: 800; color: #555; margin-bottom: 2px; }
        .cell-val { font-size: 11px; font-weight: bold; min-height: 14px; }
        .highlight-cell { background: #f0f4f7; }

        /* Metrics Grid */
        .metrics-grid { display: grid; grid-template-columns: 1fr 1fr; border: 1px solid #ccc; margin-top: 10px; }
        .metric-item { padding: 4px 8px; border-right: 1px solid #ccc; }
        .metric-item:last-child { border-right: none; }

        /* Container Table */
        .ct-table { width: 100%; border-collapse: collapse; margin-top: 15px; border: 1px solid #ccc; }
        .ct-table th { background: #f5f5f5; border: 1px solid #ccc; font-size: 9px; font-weight: 800; padding: 6px; text-align: left; }
        .ct-table td { border: 1px solid #ccc; padding: 15px; text-align: center; color: #999; font-size: 10px; }

        /* Footer */
        .footer-note { border: 1px solid #ccc; display: grid; grid-template-columns: 1fr 1fr; margin-top: 15px; }
        .note-left { padding: 10px; border-right: 1px solid #ccc; font-size: 11px; font-weight: bold; color: #1a4a7c; }
        .note-right { padding: 5px; }

        /* Floating Save */
        .float-save {
            position: fixed; bottom: 30px; left: 30px; z-index: 5000;
            background: #26c281; color: #fff; border: none; padding: 15px 40px;
            font-weight: 800; border-radius: 4px; cursor: pointer; box-shadow: 0 10px 20px rgba(0,0,0,0.4);
            display: flex; align-items: center; gap: 10px; font-size: 14px;
        }
        .float-save:hover { background: #21a36d; }

        /* Toast */
        .toast {
            position: fixed; top: 60px; right: 20px; background: #32c5d2; color: #fff;
            padding: 12px 25px; border-radius: 4px; font-weight: bold; z-index: 6000;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        [x-cloak] { display: none !important; }

        /* Modal Styles */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5); z-index: 9999; display: flex;
            justify-content: center; align-items: center;
        }
        .modal-container {
            background: #fff; width: 450px; border-radius: 4px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25); overflow: hidden;
            border-top: 4px solid #4b77be; font-family: 'Inter', sans-serif;
        }
        .modal-header {
            padding: 10px 15px; border-bottom: 1px solid #eee;
            display: flex; align-items: center; justify-content: space-between;
            font-weight: bold; font-size: 13px; color: #333;
        }
        .modal-body { padding: 15px; }
        .modal-footer {
            padding: 10px 15px; border-top: 1px solid #eee;
            text-align: right; background: #f9f9f9;
        }
        .form-group-gf { margin-bottom: 10px; }
        .form-group-gf label { display: block; font-size: 11px; font-weight: 600; margin-bottom: 4px; color: #555; }
        .form-control-gf {
            width: 100%; border: 1px solid #ccc; height: 24px;
            font-size: 11px; padding: 0 5px; border-radius: 2px;
            box-sizing: border-box;
        }
        .btn-default-gf {
            background: #fff; border: 1px solid #ccc; padding: 4px 12px;
            font-size: 11px; cursor: pointer; border-radius: 2px;
        }
        .btn-gofreight {
            background: #4b77be; color: #fff; border: none; padding: 4px 12px;
            font-size: 11px; cursor: pointer; border-radius: 2px;
        }
        .btn-gofreight:hover { background: #3a5f97; }
    </style>
</head>
<body x-data="pdoForm()">

    <!-- Success / Error Toast Alerts -->
    @if(session('success'))
        <div class="toast" style="display:block;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="toast" style="display:block; background:#f0ad4e;">{{ session('error') }}</div>
    @endif

    <!-- Navigation Bar -->
    <div class="nav-bar">
        <div class="nav-actions">
            <button class="nav-btn pdf" title="Download PDF & Save" type="submit" form="workOrderForm"></button>
            <button class="nav-btn print" title="Print & Save" @click="window.print(); document.getElementById('workOrderForm').submit()"></button>
            <button class="nav-btn mail" title="Send Email" type="button" @click="alert('Email module triggered')"></button>
            <select class="select-lang ml-2">
                <option value="en">English</option>
                <option value="zh-hans">简体中文</option>
                <option value="zh-hant">繁體中文</option>
            </select>
        </div>
        <div class="zoom-controls">
            <button class="zoom-btn" title="Zoom Out" @click="zoomOut()"><i class="fa fa-search-minus"></i></button>
            <button class="zoom-btn" title="Zoom In" @click="zoomIn()"><i class="fa fa-search-plus"></i></button>
        </div>
    </div>

    <div class="workspace">
        <!-- Data Source Block -->
        <div class="datasource-block">
            Data Source :
            <select class="ds-select">
                <option selected>Shipment</option>
                <option>Last Modified</option>
                <option>Load empty fields from last modified</option>
            </select>
        </div>

        <form id="workOrderForm" action="{{ $isEdit ? route('ocean-export.work-order.update', $workOrder->id) : route('ocean-export.work-order.store') }}" method="POST">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <input type="hidden" name="workable_type" value="{{ $workableType }}">
            <input type="hidden" name="workable_id" value="{{ $workableId }}">
            <input type="hidden" name="status" value="{{ $workOrder->status ?? 'PENDING' }}">

            <div class="doc-wrap" :style="`transform: scale(${zoom})`">
                <!-- Header -->
                <div class="doc-header">
                    <div class="logo-area"></div>
                    <div class="office-area">
                        <h1 class="office-name">GOFREIGHT</h1>
                        <div class="office-des">
                            9149 WILKERSON MEWS SUITE 546<br>
                            NEW VALERIEVIEW, VI 34553-1977<br>
                            TEL: 045-085-5813x845 FAX: 045-085-5813x845<br>
                            EMAIL: shawon@silk-container.com<br>
                            <strong>Prepared by {{ auth()->user()->name ?? 'DEMO_925' }} {{ date('m-d-Y H:i') }} (PDT)</strong>
                        </div>
                    </div>
                    <div></div>
                    <div class="title-box">
                        <select class="title-select" name="subject">
                            <option value="PICKUP & DELIVERY ORDER" {{ $subject === 'PICKUP & DELIVERY ORDER' ? 'selected' : '' }}>PICKUP & DELIVERY ORDER</option>
                            <option value="REQUEST FOR TRANSPORT" {{ $subject === 'REQUEST FOR TRANSPORT' ? 'selected' : '' }}>REQUEST FOR TRANSPORT</option>
                        </select>
                    </div>
                </div>

                <!-- Issue Line -->
                <div class="issue-info" style="grid-template-columns: 35% 15% 15% 15% 20%;">
                    <div style="font-weight: 800; display: flex; align-items: center; gap: 5px;">
                        WO NO: 
                        <input type="text" name="work_order_no" class="field-input" style="width: 140px; font-weight: bold; background: #fdfdfd; border: 1px solid #ccc;" value="{{ $woNo }}" readonly>
                    </div>
                    <div style="font-weight: 800; text-align: right; padding-right: 5px; display: flex; align-items: center; justify-content: flex-end;">ISSUED AT :</div>
                    <div class="issue-box" style="display: flex; align-items: center;">
                        <input type="date" name="issue_date" style="border:none; width: 100%; font-size:10px; background: transparent;" value="{{ $issueDate }}">
                    </div>
                    <div style="font-weight: 800; text-align: right; padding-right: 5px; display: flex; align-items: center; justify-content: flex-end;">DUE DATE :</div>
                    <div class="issue-box" style="display: flex; align-items: center;">
                        <input type="date" name="due_date" style="border:none; width: 100%; font-size:10px; background: transparent;" value="{{ $dueDate }}">
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="main-content">
                    <!-- Left Column -->
                    <div class="left-pane">
                        <div class="section-block">
                            <div class="section-title">
                                TRUCKER
                                <x-inline-select 
                                    name="vendor_id" 
                                    :options="$tradePartners" 
                                    x-model="trucker" 
                                    module="trade-partner" 
                                    class="partner-picker"
                                    @change="loadTrucker()"
                                    placeholder="Select Trucker..."
                                />
                            </div>
                            <div class="data-area">
                                <textarea name="extra_data[trucker_address]" class="textarea-gf" rows="5" x-model="truckerAddr"></textarea>
                            </div>
                        </div>

                        <div class="section-block">
                            <div class="section-title">
                                <span>
                                    <input type="checkbox" name="extra_data[empty_pickup_checked]" value="1" {{ (old('extra_data.empty_pickup_checked', $workOrder->extra_data['empty_pickup_checked'] ?? '1') == '1') ? 'checked' : '' }}> 
                                    EMPTY PICK UP LOCATION
                                </span>
                                <x-inline-select 
                                    name="empty_pickup_location_id" 
                                    :options="$tradePartners" 
                                    x-model="emptyPickupLocationId" 
                                    module="trade-partner" 
                                    class="partner-picker"
                                    @change="loadEmptyPickup()"
                                    placeholder="Select Location..."
                                />
                            </div>
                            <div class="data-area">
                                <textarea name="empty_pickup_address" class="textarea-gf" rows="5" placeholder="Empty Pickup Address..." x-model="emptyPickupAddress"></textarea>
                                <div class="field-row">
                                    <div class="field-label">REF. NO. :</div>
                                    <input type="text" name="empty_pickup_ref" class="field-input" value="{{ $emptyPickupRef }}">
                                </div>
                                <div class="field-row">
                                    <div class="field-label">DATE:</div>
                                    <input type="text" name="empty_pickup_date" class="field-input" value="{{ $emptyPickupDate }}">
                                </div>
                            </div>
                        </div>

                        <div class="section-block">
                            <div class="section-title">
                                <span>FREIGHT PICK UP LOCATION</span>
                                <x-inline-select 
                                    name="freight_pickup_location_id" 
                                    :options="$tradePartners" 
                                    x-model="freightPickupLocationId" 
                                    module="trade-partner" 
                                    class="partner-picker"
                                    @change="loadFreightPickup()"
                                    placeholder="Select Location..."
                                />
                            </div>
                            <div class="data-area">
                                <textarea name="freight_pickup_address" class="textarea-gf" rows="5" placeholder="Freight Pickup Address..." x-model="freightPickupAddress"></textarea>
                                <div class="field-row">
                                    <div class="field-label">REF. NO. :</div>
                                    <input type="text" name="freight_pickup_ref" class="field-input" value="{{ $freightPickupRef }}">
                                </div>
                                <div class="field-row">
                                    <div class="field-label">DATE:</div>
                                    <input type="text" name="freight_pickup_date" class="field-input" value="{{ $freightPickupDate }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div></div>

                    <!-- Right Column -->
                    <div class="right-pane">
                        <div class="info-grid">
                            <div class="info-cell highlight-cell">
                                <div class="cell-title">MB/L NO.</div>
                                <div class="cell-val">{{ $mblNo }}</div>
                            </div>
                            <div class="info-cell highlight-cell">
                                <div class="cell-title">HB/L NO.</div>
                                <div class="cell-val">{{ $hblNo }}</div>
                            </div>
                            <div class="info-cell">
                                <div class="cell-title">BOOKING NO.</div>
                                <div class="cell-val">{{ $carrierBkgNo }}</div>
                            </div>
                            <div class="info-cell">
                                <div class="cell-title">CARRIER BKG NO.</div>
                                <input type="text" name="carrier_bkg_no" class="field-input" style="border:none;" value="{{ $carrierBkgNo }}">
                            </div>
                            <div class="info-cell" style="grid-column: span 2;">
                                <div class="cell-title">CARRIER</div>
                                <div style="display:flex; gap:5px; align-items: center;">
                                    <x-inline-select 
                                        name="carrier_id" 
                                        :options="$tradePartners" 
                                        x-model="carrierId" 
                                        module="trade-partner" 
                                        class="partner-picker"
                                        style="width: 100%;"
                                        placeholder="Select Carrier..."
                                    />
                                </div>
                            </div>
                            <div class="info-cell" style="grid-column: span 2;">
                                <div class="cell-title">VESSEL INFO.</div>
                                <input type="text" name="vessel_info" class="field-input" style="border:none; width: 100%;" value="{{ $vesselInfo }}">
                            </div>
                            <div class="info-cell">
                                <div class="cell-title">PLACE OF RECEIPT</div>
                                <input type="text" name="place_of_receipt" class="field-input" style="border:none;" value="{{ $placeOfReceipt }}">
                            </div>
                            <div class="info-cell">
                                <div class="cell-title">ETD</div>
                                <input type="text" name="etd" class="field-input" style="border:none;" value="{{ $etd }}">
                            </div>
                        </div>

                        <!-- Metrics -->
                        <div class="metrics-grid">
                            <div class="metric-item">
                                <div class="cell-title">TOTAL PACKAGES</div>
                                <div style="display:flex; gap:5px; align-items: center;">
                                    <input type="number" name="total_packages" class="field-input" style="width:60px;" value="{{ $totalPackages }}"> 
                                    <select class="ds-select" name="package_unit" style="width: 100px;">
                                        <option value="CARTON(S)" {{ $packageUnit === 'CARTON(S)' ? 'selected' : '' }}>CARTON(S)</option>
                                        <option value="PALLET(S)" {{ $packageUnit === 'PALLET(S)' ? 'selected' : '' }}>PALLET(S)</option>
                                        <option value="BOX(ES)" {{ $packageUnit === 'BOX(ES)' ? 'selected' : '' }}>BOX(ES)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="metric-item">
                                <div class="cell-title">CONTAINER/QTY.</div>
                                <input type="text" name="container_qty" class="field-input" style="width:100%;" value="{{ $containerQty }}">
                            </div>
                        </div>

                        <div class="metrics-grid" style="border-top:none;">
                            <div class="metric-item" style="grid-column: span 2;">
                                <div class="cell-title">GROSS WEIGHT</div>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <input type="text" name="gross_weight_kgs" class="field-input" style="text-align:right;" value="{{ $grossWeightKgs }}"> <span>KGS</span>
                                    <input type="text" name="gross_weight_lbs" class="field-input" style="text-align:right;" value="{{ $grossWeightLbs }}"> <span>LBS</span>
                                </div>
                            </div>
                        </div>

                        <!-- Bill To -->
                        <div class="section-block" style="margin-top: 15px;">
                            <div class="section-title">
                                <span><input type="checkbox" name="show_bill_to" value="1" x-model="showBillTo"> Show Bill To Party</span>
                                <div x-show="showBillTo" style="display: inline-block;">
                                    <x-inline-select 
                                        name="bill_to_id" 
                                        :options="$tradePartners" 
                                        x-model="billToId" 
                                        module="trade-partner" 
                                        class="partner-picker"
                                        @change="loadBillTo()"
                                        placeholder="Select Bill To..."
                                    />
                                </div>
                            </div>
                            <div class="data-area" x-show="showBillTo" x-cloak>
                                <textarea name="bill_to_address" class="textarea-gf" rows="4" placeholder="Bill To Address..." x-model="billToAddress"></textarea>
                                <div class="field-row">
                                    <div class="field-label">REF. NO. :</div>
                                    <input type="text" name="bill_to_ref" class="field-input" value="{{ $billToRef }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <table class="ct-table">
                    <thead>
                        <tr>
                            <th style="width:30px;"><input type="checkbox"></th>
                            <th>CONTAINER NO.</th><th>TYPE</th><th>SEAL NO.</th><th>PACKAGE</th><th>WEIGHT</th><th>PICKUP NO.</th><th>L.F.D</th>
                        </tr>
                    </thead>
                    <tbody><tr><td colspan="8">No container data available for this shipment.</td></tr></tbody>
                </table>

                <!-- Footer Remark -->
                <div class="footer-note">
                    <div class="note-left">
                        P.O.D REQUIRED WITH BILLING INVOICE<br>
                        PLEASE FAX PROOF OF DELIVERY TO 999-000-5555
                    </div>
                    <div class="note-right">
                        <div class="cell-title">DESCRIPTION / INSTRUCTION</div>
                        <textarea name="instructions" class="textarea-gf" rows="6" placeholder="Add custom instructions here...">{{ $instructions }}</textarea>
                    </div>
                </div>

                <!-- Bottom Line -->
                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px; font-size:11px; font-weight:800;">
                    <div>
                        <input type="checkbox" name="do_not_break_down_pallet" value="1" {{ $doNotBreakDownPallet ? 'checked' : '' }}> 
                        DO NOT BREAK DOWN PALLET
                    </div>
                    <div>You are requested to inform us immediately of any occurrence. Thank You!</div>
                </div>
            </div>
        </form>
    </div>

    <!-- Floating Save -->
    <button type="submit" form="workOrderForm" class="float-save">
        <i class="fa fa-save"></i> SAVE & SYNC WORK ORDER
    </button>

    <!-- Global Modal Component for Inline Creation of Partners/Ports -->
    <x-add-new-modal />

    <script>
        window.dynamicOptions = window.dynamicOptions || {};
        
        function pdoForm() {
            return {
                zoom: 1.0,
                showBillTo: @json($showBillTo),
                trucker: @json($vendorId),
                truckerAddr: @json($truckerAddr),
                
                emptyPickupLocationId: @json($emptyPickupLocationId),
                emptyPickupAddress: @json($emptyPickupAddress),
                
                freightPickupLocationId: @json($freightPickupLocationId),
                freightPickupAddress: @json($freightPickupAddress),
                
                carrierId: @json($carrierId),
                billToId: @json($billToId),
                billToAddress: @json($billToAddress),
                
                toast: false,
                toastMsg: '',
                
                init() {
                    // Pre-populate dynamic options for inline-selects
                    window.dynamicOptions['trade-partner'] = @json($tradePartners->map(fn($p) => ['id' => $p->id, 'name' => $p->name]));
                    
                    // Add listener to auto-bind dynamic partner selects
                    window.addEventListener('new-record-created', (e) => {
                        const { module, model, id, name } = e.detail;
                        if (model && this.hasOwnProperty(model)) {
                            this[model] = id;
                            if (model === 'trucker') {
                                setTimeout(() => this.loadTrucker(), 100);
                            } else if (model === 'emptyPickupLocationId') {
                                setTimeout(() => this.loadEmptyPickup(), 100);
                            } else if (model === 'freightPickupLocationId') {
                                setTimeout(() => this.loadFreightPickup(), 100);
                            } else if (model === 'billToId') {
                                setTimeout(() => this.loadBillTo(), 100);
                            }
                        }
                    });
                },
                
                zoomIn() { if(this.zoom < 1.5) this.zoom += 0.1 },
                zoomOut() { if(this.zoom > 0.5) this.zoom -= 0.1 },
                
                loadTrucker() {
                    const id = this.trucker;
                    const partners = @json($tradePartners);
                    const partner = partners.find(p => p.id == id);
                    if (partner) {
                        this.truckerAddr = partner.name + '\n' + (partner.billing_address || partner.local_address || '');
                    } else {
                        this.truckerAddr = '';
                    }
                },
                
                loadEmptyPickup() {
                    const id = this.emptyPickupLocationId;
                    const partners = @json($tradePartners);
                    const partner = partners.find(p => p.id == id);
                    if (partner) {
                        this.emptyPickupAddress = partner.name + '\n' + (partner.billing_address || partner.local_address || '');
                    } else {
                        this.emptyPickupAddress = '';
                    }
                },
                
                loadFreightPickup() {
                    const id = this.freightPickupLocationId;
                    const partners = @json($tradePartners);
                    const partner = partners.find(p => p.id == id);
                    if (partner) {
                        this.freightPickupAddress = partner.name + '\n' + (partner.billing_address || partner.local_address || '');
                    } else {
                        this.freightPickupAddress = '';
                    }
                },
                
                loadBillTo() {
                    const id = this.billToId;
                    const partners = @json($tradePartners);
                    const partner = partners.find(p => p.id == id);
                    if (partner) {
                        this.billToAddress = partner.name + '\n' + (partner.billing_address || partner.local_address || '');
                    } else {
                        this.billToAddress = '';
                    }
                }
            }
        }
    </script>
</body>
</html>
