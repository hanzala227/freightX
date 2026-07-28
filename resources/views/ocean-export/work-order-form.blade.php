
Container & Item@php
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
        * { box-sizing: border-box; }
        body { background-color: #2b2b2b; font-family: 'Inter', Arial, sans-serif; margin: 0; padding: 0; color: #333; }
        
        /* Top Navigation Bar */
        .nav-bar {
            background-color: #1a1a1a;
            height: 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 2000;
            border-bottom: 1px solid #000;
        }

        .nav-actions { display: flex; align-items: center; gap: 12px; }
        .nav-btn {
            background: none; border: none; width: 32px; height: 32px; cursor: pointer;
            background-size: contain; background-repeat: no-repeat; opacity: 0.8; transition: 0.2s;
        }
        .nav-btn:hover { opacity: 1; transform: scale(1.05); }
        .nav-btn.pdf { background-image: url('https://img.icons8.com/color/48/pdf.png'); }
        .nav-btn.print { background-image: url('https://img.icons8.com/color/48/print.png'); }
        .nav-btn.mail { background-image: url('https://img.icons8.com/color/48/gmail-new.png'); }
        
        .select-lang { background: #fff; border: 1px solid #ccc; height: 28px; font-size: 12px; padding: 0 8px; border-radius: 3px; min-width: 120px; }
        .zoom-controls { display: flex; gap: 4px; }
        .zoom-btn { background: #333; color: #fff; border: 1px solid #444; width: 36px; height: 32px; cursor: pointer; font-size: 13px; border-radius: 3px; transition: 0.2s; }
        .zoom-btn:hover { background: #444; }

        /* Workspace */
        .workspace { padding: 20px; display: flex; flex-direction: column; align-items: center; min-height: calc(100vh - 48px); }
        .doc-wrap {
            background-color: #fff;
            width: 100%;
            max-width: 1000px;
            min-height: 1200px;
            padding: 40px;
            box-shadow: 0 0 40px rgba(0,0,0,0.5);
            transform-origin: top center;
            transition: transform 0.2s;
        }

        /* Topbar Block (Data Source) */
        .datasource-block {
            width: 100%;
            max-width: 1000px;
            margin-bottom: 15px;
            color: #ddd;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .ds-select { background: #fff; border: 1px solid #ccc; height: 32px; font-size: 12px; min-width: 200px; padding: 0 8px; border-radius: 3px; }

        /* Document Header */
        .doc-header { display: grid; grid-template-columns: 15% 45% 2% 38%; gap: 10px; margin-bottom: 25px; }
        .office-name { color: #1a4a7c; font-size: 26px; font-weight: 800; margin: 0 0 5px 0; }
        .office-des { font-size: 11px; line-height: 1.5; color: #555; }
        .title-box { border: 2px solid #1a4a7c; text-align: center; padding: 12px; border-radius: 3px; }
        .title-select { 
            border: 1px solid #ccc; 
            width: 100%; 
            height: 36px; 
            font-weight: 700; 
            font-size: 14px; 
            text-align: center; 
            border-radius: 3px;
            padding: 0 8px;
        }
        
        .issue-info { display: grid; grid-template-columns: 35% 15% 15% 15% 20%; font-size: 11px; margin-top: 15px; gap: 8px; padding: 12px; background: #f8f9fa; border-radius: 3px; }
        .issue-box { 
            border: 1px solid #ccc; 
            padding: 6px 8px; 
            height: 36px; 
            font-weight: 600; 
            background: #fff;
            border-radius: 3px;
            display: flex;
            align-items: center;
        }

        /* Content Grid */
        .main-content { display: grid; grid-template-columns: 48% 4% 48%; margin-top: 20px; }
        .section-block { border: 1px solid #ddd; margin-bottom: 20px; border-radius: 4px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .section-title { 
            background: #f5f7f9; 
            padding: 10px 12px; 
            font-size: 11px; 
            font-weight: 700; 
            color: #2c3e50;
            border-bottom: 1px solid #ddd; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            gap: 10px;
        }
        .partner-picker { 
            height: 32px !important; 
            font-size: 11px !important; 
            min-width: 200px !important; 
            max-width: 100% !important;
            border: 1px solid #ccc !important; 
            background: #fff !important;
            border-radius: 3px !important;
            padding: 0 8px !important;
        }
        .data-area { padding: 12px; background: #fff; }
        .textarea-gf { 
            width: 100%; 
            border: 1px solid #ddd; 
            font-size: 12px; 
            resize: vertical; 
            outline: none; 
            line-height: 1.5; 
            font-family: inherit;
            padding: 8px;
            border-radius: 3px;
            min-height: 80px;
        }
        .textarea-gf:focus { border-color: #4b77be; box-shadow: 0 0 0 2px rgba(75,119,190,0.1); }
        
        .field-row { display: flex; align-items: center; font-size: 11px; margin-bottom: 8px; gap: 8px; }
        .field-label { min-width: 80px; font-weight: 600; color: #555; }
        .field-input { 
            border: 1px solid #ddd; 
            height: 32px; 
            font-size: 12px; 
            padding: 0 8px; 
            flex: 1;
            border-radius: 3px;
        }
        .field-input:focus { border-color: #4b77be; outline: none; box-shadow: 0 0 0 2px rgba(75,119,190,0.1); }

        .info-grid { border: 1px solid #ddd; display: grid; grid-template-columns: 1fr 1fr; border-radius: 4px; overflow: hidden; }
        .info-cell { padding: 10px 12px; border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; }
        .info-cell:nth-child(2n) { border-right: none; }
        .cell-title { font-size: 10px; font-weight: 700; color: #7f8c8d; margin-bottom: 4px; text-transform: uppercase; }
        .cell-val { font-size: 12px; font-weight: 600; min-height: 18px; color: #2c3e50; }
        .highlight-cell { background: #f8f9fa; }

        /* Metrics Grid */
        .metrics-grid { display: grid; grid-template-columns: 1fr 1fr; border: 1px solid #ddd; margin-top: 15px; gap: 0; border-radius: 4px; overflow: hidden; }
        .metric-item { padding: 10px 12px; border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; }
        .metric-item:nth-child(2n) { border-right: none; }
        .metric-item:last-child, .metric-item:nth-last-child(2) { border-bottom: none; }

        /* Container Table */
        .ct-table { width: 100%; border-collapse: collapse; margin-top: 20px; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; }
        .ct-table th { background: #f5f7f9; border: 1px solid #ddd; font-size: 10px; font-weight: 700; padding: 10px 8px; text-align: left; color: #2c3e50; }
        .ct-table td { border: 1px solid #ddd; padding: 20px; text-align: center; color: #95a5a6; font-size: 11px; }

        /* Footer */
        .footer-note { border: 1px solid #ddd; display: grid; grid-template-columns: 1fr 1fr; margin-top: 20px; border-radius: 4px; overflow: hidden; }
        .note-left { padding: 15px; border-right: 1px solid #ddd; font-size: 12px; font-weight: 600; color: #1a4a7c; background: #f8f9fa; }
        .note-right { padding: 12px; background: #fff; }

        /* Floating Save */
        .float-save {
            position: fixed; bottom: 30px; left: 30px; z-index: 5000;
            background: linear-gradient(135deg, #26c281 0%, #22a66c 100%);
            color: #fff; border: none; padding: 16px 40px;
            font-weight: 700; border-radius: 6px; cursor: pointer; 
            box-shadow: 0 10px 25px rgba(38,194,129,0.3);
            display: flex; align-items: center; gap: 10px; font-size: 14px;
            transition: all 0.3s;
        }
        .float-save:hover { 
            background: linear-gradient(135deg, #22a66c 0%, #1e9160 100%); 
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(38,194,129,0.4);
        }
        .float-save i { font-size: 16px; }

        /* Toast - Enhanced with better visibility */
        .toast {
            position: fixed; 
            top: 80px; 
            right: 30px; 
            min-width: 300px;
            background: #27ae60; 
            color: #fff;
            padding: 16px 24px; 
            border-radius: 8px; 
            font-weight: 600; 
            z-index: 6000;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInRight 0.3s ease-out, fadeOut 0.3s ease-in 4.7s forwards;
        }
        .toast::before {
            content: '✓';
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            font-size: 16px;
            font-weight: bold;
        }
        .toast.error {
            background: #e74c3c;
        }
        .toast.error::before {
            content: '✕';
        }
        .toast.warning {
            background: #f39c12;
        }
        .toast.warning::before {
            content: '⚠';
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
                transform: translateX(400px);
            }
        }
        
        [x-cloak] { display: none !important; }

        /* Modal Styles */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.6); z-index: 9999; display: flex;
            justify-content: center; align-items: center;
        }
        .modal-container {
            background: #fff; width: 90%; max-width: 500px; border-radius: 6px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3); overflow: hidden;
            border-top: 4px solid #4b77be; font-family: 'Inter', sans-serif;
        }
        .modal-header {
            padding: 14px 20px; border-bottom: 1px solid #eee;
            display: flex; align-items: center; justify-content: space-between;
            font-weight: 600; font-size: 14px; color: #2c3e50;
        }
        .modal-body { padding: 20px; }
        .modal-footer {
            padding: 14px 20px; border-top: 1px solid #eee;
            text-align: right; background: #f8f9fa;
        }
        .form-group-gf { margin-bottom: 14px; }
        .form-group-gf label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; color: #2c3e50; }
        .form-control-gf {
            width: 100%; border: 1px solid #ddd; height: 36px;
            font-size: 12px; padding: 0 10px; border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }
        .form-control-gf:focus { border-color: #4b77be; outline: none; box-shadow: 0 0 0 2px rgba(75,119,190,0.1); }
        .btn-default-gf {
            background: #fff; border: 1px solid #ccc; padding: 8px 16px;
            font-size: 12px; cursor: pointer; border-radius: 4px;
            transition: all 0.2s;
        }
        .btn-default-gf:hover { background: #f8f9fa; border-color: #999; }
        .btn-gofreight {
            background: #4b77be; color: #fff; border: none; padding: 8px 16px;
            font-size: 12px; cursor: pointer; border-radius: 4px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-gofreight:hover { background: #3a5f97; }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .doc-wrap { padding: 30px 20px; }
            .main-content { grid-template-columns: 100%; }
            .doc-header { grid-template-columns: 1fr; }
            .issue-info { grid-template-columns: 1fr 1fr; }
            .float-save { bottom: 20px; left: 20px; padding: 14px 30px; font-size: 13px; }
        }
        
        @media (max-width: 768px) {
            .workspace { padding: 10px; }
            .doc-wrap { padding: 20px 15px; }
            .nav-bar { height: 44px; padding: 0 10px; }
            .nav-btn { width: 28px; height: 28px; }
            .datasource-block { flex-direction: column; align-items: flex-start; }
            .ds-select { width: 100%; }
            .info-grid { grid-template-columns: 1fr; }
            .metrics-grid { grid-template-columns: 1fr; }
            .partner-picker { min-width: 100px !important; font-size: 10px !important; }
            .section-title { flex-direction: column; align-items: flex-start; }
            .float-save { 
                bottom: 10px; left: 10px; right: 10px; 
                padding: 12px 20px; font-size: 12px;
                justify-content: center;
            }
        }
        
        @media print {
            .nav-bar, .float-save, .datasource-block { display: none !important; }
            .workspace { padding: 0; }
            .doc-wrap { box-shadow: none; transform: none !important; }
        }
    </style>
</head>
<body x-data="pdoForm()">

    <!-- Success / Error Toast Alerts -->
    @if(session('success'))
        <div class="toast" style="display:flex;">
            {{ session('success') }}
        </div>
        <script>
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                const toast = document.querySelector('.toast');
                if (toast) toast.style.display = 'none';
            }, 5000);
        </script>
    @endif
    @if(session('error'))
        <div class="toast error" style="display:flex;">
            {{ session('error') }}
        </div>
        <script>
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                const toast = document.querySelector('.toast');
                if (toast) toast.style.display = 'none';
            }, 5000);
        </script>
    @endif
    
    <!-- Alpine.js Toast Notification -->
    <div x-show="toast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform translate-x-full"
         :class="toastType === 'error' ? 'toast error' : (toastType === 'warning' ? 'toast warning' : 'toast')"
         style="display:flex;"
         x-cloak>
        <span x-text="toastMsg"></span>
    </div>

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

        <form id="workOrderForm" 
              action="{{ $isEdit ? route('ocean-export.work-order.update', $workOrder->id) : route('ocean-export.work-order.store') }}" 
              method="POST"
              data-turbo="false">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <input type="hidden" name="workable_type" value="{{ $workableType }}">
            <input type="hidden" name="workable_id" value="{{ $workableId }}">
            <input type="hidden" name="status" value="{{ $workOrder->status ?? 'PENDING' }}">
            
            <!-- Hidden fields for source redirect -->
            @if(isset($source) && $source)
                <input type="hidden" name="source" value="{{ $source }}">
            @endif
            @if(isset($sourceId) && $sourceId)
                <input type="hidden" name="source_id" value="{{ $sourceId }}">
            @endif

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
                                    <input type="date" name="empty_pickup_date" class="field-input" value="{{ $emptyPickupDate }}">
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
                                    <input type="date" name="freight_pickup_date" class="field-input" value="{{ $freightPickupDate }}">
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
                toastType: 'success',
                
                showToast(message, type = 'success') {
                    this.toastMsg = message;
                    this.toastType = type;
                    this.toast = true;
                    
                    // Auto-dismiss after 5 seconds
                    setTimeout(() => {
                        this.toast = false;
                    }, 5000);
                },
                
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
