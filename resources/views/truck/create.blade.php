<x-layout>
    @push('styles')
    <style>
        [x-cloak] { display: none !important; }
        .page-content { padding: 8px; background: #eef1f5; min-height: calc(100vh - 50px); font-family: 'Open Sans', sans-serif !important; }
        
        .action-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .page-title { font-size: 16px; font-weight: 600; color: #4b77be; text-transform: uppercase; }
        
        .page-bar { background-color: #fff; padding: 8px 20px; margin-bottom: 15px; border: 1px solid #e9ebec; border-radius: 4px; }
        .page-breadcrumb { list-style: none; padding: 0; margin: 0; display: flex; align-items: center; }
        .page-breadcrumb li { font-size: 12px; color: #888; display: flex; align-items: center; }
        .page-breadcrumb li a { color: #337ab7; text-decoration: none; }
        .page-breadcrumb li i { margin: 0 8px; font-size: 10px; opacity: 0.5; }
        
        .gf-tabs { display: flex; border-bottom: 1px solid #ddd; list-style: none; padding: 0; margin: 0 0 15px 0; background: #fff; border-radius: 4px 4px 0 0; overflow-x: auto; white-space: nowrap; }
        .gf-tabs li { margin-bottom: -1px; }
        .gf-tabs li a { padding: 10px 20px; display: block; color: #555; text-decoration: none; border: 1px solid transparent; cursor: pointer; font-size: 12px; font-weight: 600; }
        .gf-tabs li.active a { background: #fff; border: 1px solid #ddd; border-bottom-color: #fff; border-top: 3px solid #32c5d2; color: #333; }
        .gf-tabs li.disabled-tab a { color: #bbb !important; cursor: not-allowed !important; background: #f5f5f5 !important; opacity: 0.6; pointer-events: none; border-color: transparent !important; }
        
        .portlet { background-color: #fff; border: 1px solid #e7ecf1; border-radius: 4px; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .portlet-title { padding: 8px 10px; border-bottom: 1px solid #eef1f5; display: flex; align-items: center; justify-content: space-between; background: #f9fafb; min-height: 35px; }
        .portlet-title .caption { font-size: 13px; font-weight: 700; text-transform: uppercase; display: flex; align-items: center; gap: 5px; color: #333; }
        .portlet-body { padding: 15px; background: #fdfdfd; }
        
        .btn-gofreight { background: #32c5d2; color: #fff; border: none; padding: 4px 12px; font-size: 12px; font-weight: 600; cursor: pointer; border-radius: 2px; display: inline-flex; align-items: center; gap: 5px; }
        .btn-gofreight:hover { background: #26a1ab; }
        .btn-gf-inline { background: #32c5d2; color: #fff !important; border: none; padding: 2px 8px; border-radius: 2px; font-size: 10px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; }
        .btn-gf-inline:hover { background: #26a1ab; }
        .btn-default-gf { background: #fff; border: 1px solid #ccc; color: #666; padding: 4px 12px; font-size: 11px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; text-decoration: none; border-radius: 2px; }
        .btn-default-gf:hover { background: #f5f5f5; }
        .btn-gf-tool { padding: 0 5px; height: 19px; font-size: 10px; background: #fff; border: 1px solid #ccc; color: #666; cursor: pointer; border-radius: 2px; }
        
        .form-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px 15px; margin-bottom: 10px; }
        
        .form-group-gf { display: flex; align-items: center; min-height: 22px; }
        .form-label-gf { font-size: 11px; font-weight: 500; color: #666; width: 110px; text-align: right; margin-right: 8px; flex-shrink: 0; }
        .form-label-gf.required { color: #d05454; }
        .color-remark-tag { display: inline-block; width: 14px; height: 18px; border: 1px solid #ddd; margin-right: 5px; vertical-align: middle; background: #fff; }
        .form-input-container { flex: 1; display: flex; align-items: center; gap: 4px; }
        .form-control-gf { width: 100%; height: 22px; border: 1px solid #ccc; padding: 0 4px; font-size: 10px; border-radius: 2px; background: #fff; box-sizing: border-box; }
        .form-control-gf[disabled] { background: #eee; }
        .form-control-gf.date-picker { background: #fff url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="%23999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>') no-repeat right 4px center; padding-right: 20px; }
        .form-control-gf.error { border-color: #d05454; }
        .form-error { font-size: 9px; color: #d05454; margin-top: 2px; display: block; }
        
        input[type="checkbox"], input[type="radio"] { width: 12px; height: 12px; margin: 0; }
        
        hr { border: 0; border-top: 1px solid #e0e0e0; margin: 10px 0; }
        
        .memo-table { width: 100%; border-collapse: collapse; font-size: 11px; background: #fff; }
        .memo-table th { background: #eef1f5; color: #333; padding: 6px; text-align: left; font-weight: 600; border: 1px solid #ddd; }
        .memo-table td { padding: 6px; border: 1px solid #ddd; }
        .memo-header-container { display: flex; justify-content: space-between; align-items: center; background: #eef1f5; padding: 5px 10px; border: 1px solid #ccc; border-bottom: none; }
        
        .btn-add-memo { background: #32c5d2; border: none; color: white; padding: 2px 8px; border-radius: 3px; font-size: 10px; cursor: pointer; }
        .btn-add-memo:hover { background: #26a1ab; }
        
        .memo-table-dark th { background: #888; color: #fff; border: 1px solid #ccc; }
        
        .modal-backdrop { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1040; }
        .modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1050; display: flex; justify-content: center; align-items: flex-start; padding-top: 30px; overflow-y: auto; }
        .modal-dialog { width: 900px; max-width: 95%; background: #fff; border-radius: 4px; box-shadow: 0 5px 15px rgba(0,0,0,.5); }
        .modal-header { padding: 15px; border-bottom: 1px solid #e5e5e5; display: flex; justify-content: space-between; align-items: center; }
        .modal-title { margin: 0; font-size: 18px; font-weight: 400; color: #333; }
        .close-btn { background: none; border: none; font-size: 21px; font-weight: 700; color: #000; text-shadow: 0 1px 0 #fff; opacity: .2; cursor: pointer; }
        .close-btn:hover { opacity: .5; }
        .modal-body { padding: 15px; }
        .modal-footer { padding: 15px; text-align: right; border-top: 1px solid #e5e5e5; }
        
        .step-container { display: flex; align-items: center; justify-content: center; margin-bottom: 20px; }
        .step { display: flex; align-items: center; gap: 8px; flex-direction: column; }
        .step-id { width: 24px; height: 24px; border-radius: 50%; background: #ccc; color: #fff; display: flex; justify-content: center; align-items: center; font-size: 12px; font-weight: bold; }
        .step-id.active { background: #36c6d3; }
        .step-title { font-size: 12px; color: #666; }
        .step-divider { flex: 1; height: 1px; background: #e5e5e5; margin: 0 15px; align-self: flex-start; margin-top: 12px; max-width: 150px; }

        .alert-success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px 15px; border-radius: 4px; margin-bottom: 15px; font-size: 12px; }
        .alert-danger { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 10px 15px; border-radius: 4px; margin-bottom: 15px; font-size: 12px; }

        .file-upload-zone { border: 2px dashed #ccc; border-radius: 4px; padding: 20px; text-align: center; cursor: pointer; background: #fff; transition: all 0.2s; }
        .file-upload-zone:hover { border-color: #32c5d2; background: #f0fafb; }

        .table-gf { width: 100%; border-collapse: collapse; font-size: 11px; }
        .table-gf th { background: #f8f9fa; border: 1px solid #ddd; padding: 5px; font-weight: 600; color: #555; text-align: left; }
        .table-gf td { border: 1px solid #ddd; padding: 4px; vertical-align: middle; }
        .well { background: #f9fafb; border: 1px solid #ebedf2; padding: 10px; margin-bottom: 10px; border-radius: 4px; }
        .caption-subject { font-size: 13px; font-weight: 700; color: #333; text-transform: uppercase; }
        /* Quote Modal Styles (Ocean Import compat) */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1040; overflow-y: auto; display: flex; justify-content: center; align-items: flex-start; padding-top: 30px; }
        .modal-container { background: #fff; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); width: 950px; max-width: 95%; max-height: 90vh; display: flex; flex-direction: column; animation: modalFadeIn 0.2s ease; }
        @keyframes modalFadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .modal-container .modal-header { padding: 12px 16px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc; border-radius: 8px 8px 0 0; font-size: 14px; font-weight: 600; color: #1e293b; }
        .modal-container .modal-header span { display: flex; align-items: center; gap: 8px; }
        .modal-container .modal-body { padding: 16px; overflow-y: auto; flex: 1; }
        .modal-container .modal-footer { padding: 12px 16px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 8px; background: #f8fafc; border-radius: 0 0 8px 8px; }
        .wizard-circle { width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: #fff; transition: all 0.3s; }
        .hbl-header { font-size: 11px; font-weight: 700; color: #334155; text-transform: uppercase; background: #f1f5f9; padding: 6px 10px; border: 1px solid #e2e8f0; border-bottom: none; border-radius: 4px 4px 0 0; margin-top: 8px; }
        .table-custom { width: 100%; border-collapse: collapse; font-size: 11px; }
        .table-custom th { background: #f1f5f9; color: #475569; padding: 6px 8px; text-align: left; font-weight: 600; border: 1px solid #e2e8f0; font-size: 10px; }
        .table-custom td { padding: 6px 8px; border: 1px solid #e2e8f0; color: #334155; }
        .table-custom tbody tr:hover { background: #f8fafc; }
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .text-blue-500 { color: #3b82f6; }
        .text-gray-500 { color: #64748b; }
        .hover\:text-gray-700:hover { color: #334155; }
        .cursor-pointer { cursor: pointer; }
        .opacity-50 { opacity: 0.5; }
        .cursor-not-allowed { cursor: not-allowed; }
        .main-grid { display: flex; flex-direction: column; gap: 4px; }
        .btn-gofreight.opacity-50 { pointer-events: none; }
        
        /* Dropdown Item Styles */
        .dropdown-item { display: flex; align-items: center; padding: 8px 14px; font-size: 10px; font-weight: 600; color: #334155; text-decoration: none; cursor: pointer; transition: all 0.2s; }
        .dropdown-item:hover { background: #f8fafc; color: #3b82f6; }
        .dropdown-item i { color: inherit; }
    </style>
    @endpush

    <div class="page-content" x-data="truckCreateApp()" x-cloak>
        <form id="truckShipmentForm" action="{{ isset($truckShipment) ? route('truck.update', $truckShipment->id) : route('truck.store') }}" method="POST" enctype="multipart/form-data" @submit.prevent="validateAndSubmit">
            @csrf
            @if(isset($truckShipment)) @method('PUT') @endif

            @if(session('success'))
                <div class="alert-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert-danger">
                    <strong><i class="fa fa-exclamation-circle"></i> Validation Error</strong>
                    <ul style="margin:5px 0 0 15px;padding:0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        <!-- Breadcrumb -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
                <li><a href="/truck/list">Trucking</a> <i class="fa fa-angle-right"></i></li>
                <li>{{ isset($truckShipment) ? 'Edit Shipment' : 'New Shipment' }}</li>
            </ul>
        </div>

        <!-- Load Quotation Data Modal -->
        <div x-show="showQuoteModal" class="modal-overlay" style="display:none;" x-cloak>
            <div class="modal-container" style="max-width: 950px; display: flex; flex-direction: column;">
                <div class="modal-header">
                    <span><i class="fa fa-file-text-o text-blue-500"></i> Load Quotation Data</span>
                    <i class="fa fa-times cursor-pointer text-gray-500 hover:text-gray-700" @click="closeQuoteModal()"></i>
                </div>

                <div class="modal-body hide-scrollbar">
                    <style>
                        .hide-scrollbar::-webkit-scrollbar { display: none; }
                        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
                    </style>

                    <!-- Steps -->
                    <div style="display: flex; justify-content: center; align-items: center; gap: 8px; margin-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div class="wizard-circle" :style="quoteStep >= 1 ? 'background: #3b82f6;' : 'background: #cbd5e1;'">
                                <template x-if="quoteStep > 1"><i class="fa fa-check"></i></template>
                                <template x-if="quoteStep === 1"><span>1</span></template>
                            </div>
                            <span :style="quoteStep >= 1 ? 'color: #1e293b; font-size: 10px; font-weight: 600;' : 'color: #94a3b8; font-size: 10px;'">Select Quotation</span>
                        </div>
                        <div style="height: 1px; width: 20px; background: #e2e8f0;"></div>
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div class="wizard-circle" :style="quoteStep >= 2 ? 'background: #3b82f6;' : 'background: #cbd5e1;'">
                                <template x-if="quoteStep > 2"><i class="fa fa-check"></i></template>
                                <template x-if="quoteStep === 2"><span>2</span></template>
                            </div>
                            <span :style="quoteStep >= 2 ? 'color: #1e293b; font-size: 10px; font-weight: 600;' : 'color: #94a3b8; font-size: 10px;'">Fill in shipment data</span>
                        </div>
                        <div style="height: 1px; width: 20px; background: #e2e8f0;"></div>
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div class="wizard-circle" :style="quoteStep >= 3 ? 'background: #3b82f6;' : 'background: #cbd5e1;'">
                                <template x-if="quoteStep > 3"><i class="fa fa-check"></i></template>
                                <template x-if="quoteStep === 3"><span>3</span></template>
                            </div>
                            <span :style="quoteStep >= 3 ? 'color: #1e293b; font-size: 10px; font-weight: 600;' : 'color: #94a3b8; font-size: 10px;'">Select invoice items</span>
                        </div>
                    </div>

                    <!-- ===== STEP 1: Select Quotation ===== -->
                    <div x-show="quoteStep === 1">
                        <div class="form-grid-4" style="grid-template-columns: repeat(3, 1fr);">
                            <div class="main-grid">
                                <div class="form-group-gf"><label class="form-label-gf">Customer</label><div class="form-input-container">
                                    <select name="customer" x-model="filters.customer" class="form-control-gf">
                                        <option value="">Select...</option>
                                        <template x-for="agent in agents" :key="agent.id">
                                            <option :value="agent.id" x-text="agent.company_name || agent.name"></option>
                                        </template>
                                    </select>
                                </div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Port of Loading</label><div class="form-input-container">
                                    <select name="pol" x-model="filters.pol" class="form-control-gf">
                                        <option value="">Select...</option>
                                        <template x-for="port in ports" :key="port.id">
                                            <option :value="port.id" x-text="port.name"></option>
                                        </template>
                                    </select>
                                </div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Quote No.</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="filters.quote_no"></div></div>
                            </div>
                            <div class="main-grid">
                                <div class="form-group-gf"><label class="form-label-gf">Valid Date</label><div class="form-input-container"><input type="text" class="form-control-gf datepicker" x-model="filters.valid_date" placeholder="Start Date - End Date"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Port of Discharge</label><div class="form-input-container">
                                    <select name="pod" x-model="filters.pod" class="form-control-gf">
                                        <option value="">Select...</option>
                                        <template x-for="port in ports" :key="port.id">
                                            <option :value="port.id" x-text="port.name"></option>
                                        </template>
                                    </select>
                                </div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Status</label><div class="form-input-container">
                                    <select class="form-control-gf" x-model="filters.status">
                                        <option value="">Select...</option>
                                        <option value="ACTIVE">Active</option>
                                        <option value="EXPIRED">Expired</option>
                                        <option value="CANCELLED">Cancelled</option>
                                        <option value="CONFIRMED">Confirmed</option>
                                    </select>
                                </div></div>
                            </div>
                            <div class="main-grid">
                                <div class="form-group-gf"><label class="form-label-gf">Commodity</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="filters.commodity"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Sales</label><div class="form-input-container">
                                    <select class="form-control-gf" x-model="filters.sales">
                                        <option value="">Select...</option>
                                        <template x-for="user in users" :key="user.id">
                                            <option :value="user.id" x-text="user.name"></option>
                                        </template>
                                    </select>
                                </div></div>
                                <div class="form-group-gf"><label class="form-label-gf">OP</label><div class="form-input-container">
                                    <select class="form-control-gf" x-model="filters.op">
                                        <option value="">Select...</option>
                                        <template x-for="user in users" :key="user.id">
                                            <option :value="user.id" x-text="user.name"></option>
                                        </template>
                                    </select>
                                </div></div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: center; gap: 8px; margin: 10px 0;">
                            <button type="button" class="btn-default-gf" @click="clearSearch()">Clear</button>
                            <button type="button" class="btn-gofreight" @click="searchQuotes()"><i class="fa fa-search"></i> Search</button>
                        </div>

                        <div style="text-align: right; margin-bottom: 4px;">
                            <button type="button" class="btn-default-gf" @click="showQuoteConfig = !showQuoteConfig"><i class="fa fa-cogs"></i> Config</button>
                        </div>

                        <div x-show="showQuoteConfig" style="margin-bottom: 8px; padding: 8px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px;">
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                <label style="font-size: 10px; display: flex; align-items: center; gap: 4px; cursor: pointer;"><input type="checkbox" x-model="colVisibility.quote_no" style="width: 12px; height: 12px;"> Quote No.</label>
                                <label style="font-size: 10px; display: flex; align-items: center; gap: 4px; cursor: pointer;"><input type="checkbox" x-model="colVisibility.valid_date" style="width: 12px; height: 12px;"> Valid Date</label>
                                <label style="font-size: 10px; display: flex; align-items: center; gap: 4px; cursor: pointer;"><input type="checkbox" x-model="colVisibility.status" style="width: 12px; height: 12px;"> Status</label>
                                <label style="font-size: 10px; display: flex; align-items: center; gap: 4px; cursor: pointer;"><input type="checkbox" x-model="colVisibility.commodity" style="width: 12px; height: 12px;"> Commodity</label>
                                <label style="font-size: 10px; display: flex; align-items: center; gap: 4px; cursor: pointer;"><input type="checkbox" x-model="colVisibility.pol" style="width: 12px; height: 12px;"> POL</label>
                                <label style="font-size: 10px; display: flex; align-items: center; gap: 4px; cursor: pointer;"><input type="checkbox" x-model="colVisibility.pod" style="width: 12px; height: 12px;"> POD</label>
                                <label style="font-size: 10px; display: flex; align-items: center; gap: 4px; cursor: pointer;"><input type="checkbox" x-model="colVisibility.sales" style="width: 12px; height: 12px;"> Sales</label>
                                <label style="font-size: 10px; display: flex; align-items: center; gap: 4px; cursor: pointer;"><input type="checkbox" x-model="colVisibility.op" style="width: 12px; height: 12px;"> OP</label>
                            </div>
                        </div>

                        <div class="table-responsive" style="margin-bottom: 10px;">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Select</th>
                                    <th x-show="colVisibility.quote_no">Quote No.</th>
                                    <th x-show="colVisibility.valid_date">Valid Date</th>
                                    <th x-show="colVisibility.status">Status</th>
                                    <th>Creation Date</th>
                                    <th x-show="colVisibility.commodity">Commodity</th>
                                    <th x-show="colVisibility.pol">POL</th>
                                    <th x-show="colVisibility.pod">POD</th>
                                    <th x-show="colVisibility.sales">Sales</th>
                                    <th x-show="colVisibility.op">OP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(quote, idx) in filteredQuotes" :key="quote.id">
                                    <tr @click="selectedQuote = quote; quoteStep = 2" style="cursor: pointer;">
                                        <td style="text-align: center;"><input type="radio" name="selected_quote" :value="quote.id" @click.stop x-model="quoteSearch.selected_id"></td>
                                        <td x-show="colVisibility.quote_no" x-text="quote.quote_no"></td>
                                        <td x-show="colVisibility.valid_date" x-text="quote.expiry_date"></td>
                                        <td x-show="colVisibility.status"><span x-text="quote.status"></span></td>
                                        <td x-text="quote.created_at ? quote.created_at.substring(0,10) : ''"></td>
                                        <td x-show="colVisibility.commodity" x-text="quote.commodity || 'N/A'"></td>
                                        <td x-show="colVisibility.pol" x-text="quote.pol_name || 'N/A'"></td>
                                        <td x-show="colVisibility.pod" x-text="quote.pod_name || 'N/A'"></td>
                                        <td x-show="colVisibility.sales" x-text="quote.sales_person_name || 'N/A'"></td>
                                        <td x-show="colVisibility.op" x-text="quote.op_name || 'N/A'"></td>
                                    </tr>
                                </template>
                                <template x-if="filteredQuotes.length === 0">
                                    <tr>
                                        <td colspan="10" style="text-align: center; color: #94a3b8; font-size: 11px; padding: 20px;">No quotations found. Use the search filters above.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        </div>
                    </div>

                    <!-- ===== STEP 2: Fill in shipment data ===== -->
                    <div x-show="quoteStep === 2">
                        <div class="hbl-header">Select a Route</div>
                        <div class="table-responsive" style="margin-bottom: 10px;">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Select</th>
                                    <th>Place of Receipt</th>
                                    <th>Port of Loading</th>
                                    <th>Port of Discharge</th>
                                    <th>Place of Delivery</th>
                                    <th>Final Destination</th>
                                    <th>Carrier</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: center;"><input type="radio" checked></td>
                                    <td x-text="selectedQuote ? selectedQuote.pol_name : '-'"></td>
                                    <td x-text="selectedQuote ? selectedQuote.pol_name : '-'"></td>
                                    <td x-text="selectedQuote ? selectedQuote.pod_name : '-'"></td>
                                    <td x-text="selectedQuote ? selectedQuote.pod_name : '-'"></td>
                                    <td x-text="selectedQuote ? selectedQuote.pod_name : '-'"></td>
                                    <td><span x-text="selectedQuote && selectedQuote.carrier_name ? selectedQuote.carrier_name : '-'"></span></td>
                                </tr>
                            </tbody>
                        </table>
                        </div>

                        <div class="hbl-header">Fill in the Shipment Information</div>
                        <div class="form-grid-4" style="grid-template-columns: repeat(2, 1fr);">
                            <div class="main-grid">
                                <div class="form-group-gf"><label class="form-label-gf" style="color: #ef4444;">*MB/L No.</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="quoteForm.mbl_no"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">HB/L No.</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="quoteForm.hbl_no"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ETD</label><div class="form-input-container"><input type="date" class="form-control-gf" x-model="quoteForm.etd"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf" style="color: #ef4444;">*Customer</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="quoteForm.customer"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Sales</label><div class="form-input-container"><span x-text="quoteForm.sales" style="font-size: 10px; color: #334155;"></span></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">OP</label><div class="form-input-container"><span x-text="quoteForm.op" style="font-size: 10px; color: #334155;"></span></div></div>
                            </div>
                            <div class="main-grid">
                                <div class="form-group-gf"><label class="form-label-gf">Vessel/Flight No.</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="quoteForm.vessel_flight_no"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf" style="color: #ef4444;">*ETA</label><div class="form-input-container"><input type="date" class="form-control-gf" x-model="quoteForm.eta"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Carrier Bkg. No.</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="quoteForm.carrier_bkg_no"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Shipper</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="quoteForm.shipper"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Consignee</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="quoteForm.consignee"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Trucker</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="quoteForm.trucker"></div></div>
                            </div>
                        </div>
                        <div class="main-grid" style="margin-top: 4px;">
                            <div class="form-group-gf"><label class="form-label-gf">Detail</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="quoteForm.detail"></div></div>
                        </div>
                    </div>

                    <!-- ===== STEP 3: Select invoice items ===== -->
                    <div x-show="quoteStep === 3">
                        <div class="hbl-header">Select Freight Item(s)</div>
                        <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 4px;">
                            <input type="checkbox" x-model="saveAsDraftInvoice" style="margin: 0; width: 12px; height: 12px; cursor: pointer; accent-color: #3b82f6;">
                            <span style="font-size: 10px; color: #475569; font-weight: 600;">Save as a draft invoice</span>
                        </div>

                        <div class="table-responsive" style="margin-bottom: 10px;">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Select</th>
                                    <th>Freight Code</th>
                                    <th>Freight Description</th>
                                    <th>Unit</th>
                                    <th>Currency</th>
                                    <th>Volume</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, idx) in (selectedQuote?.items || [])" :key="idx">
                                <tr>
                                    <td style="text-align: center;"><input type="checkbox" x-model="item.selected"></td>
                                    <td x-text="item.charge_code"></td>
                                    <td x-text="item.charge_name"></td>
                                    <td x-text="item.unit"></td>
                                    <td x-text="item.currency"></td>
                                    <td x-text="item.qty"></td>
                                    <td x-text="item.rate"></td>
                                    <td x-text="item.amount ? item.amount.toLocaleString() : '0.00'"></td>
                                </tr>
                                </template>
                                <tr x-show="!selectedQuote?.items || selectedQuote.items.length === 0">
                                    <td colspan="8" style="text-align: center; color: #94a3b8; font-size: 11px; padding: 20px;">No charge items available for this quotation. Items can be added after shipment creation.</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-default-gf" @click="closeQuoteModal()">Cancel</button>
                    <button type="button" x-show="quoteStep > 1" class="btn-default-gf" @click="quoteStep--">Back</button>

                    <button type="button" x-show="quoteStep < 3"
                            :class="((quoteStep === 1 && !selectedQuote) || (quoteStep === 2 && (!quoteForm.mbl_no || !quoteForm.eta || !quoteForm.customer))) ? 'btn-gofreight opacity-50 cursor-not-allowed' : 'btn-gofreight'"
                            :disabled="(quoteStep === 1 && !selectedQuote) || (quoteStep === 2 && (!quoteForm.mbl_no || !quoteForm.eta || !quoteForm.customer))"
                            @click="quoteStep === 1 ? selectQuote(selectedQuote) : quoteStep < 3 ? quoteStep++ : null">
                        Next
                    </button>

                    <button type="button" x-show="quoteStep === 3" class="btn-gofreight" @click="confirmQuoteSelection()">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h1 style="font-size: 18px; margin: 0; font-weight: 400; color: #444;">{{ isset($truckShipment) ? 'Edit Truck Shipment' : 'New Truck Shipment' }}</h1>
            <div style="display: flex; gap: 8px;">
                <button type="submit" form="truckShipmentForm" class="btn-gofreight" style="padding:6px 24px;"><i class="fa fa-save"></i> {{ isset($truckShipment) ? 'UPDATE' : 'SAVE' }}</button>
                <a href="/truck/list" class="btn-default-gf" style="padding:6px 20px;text-decoration:none;" target="_blank"><i class="fa fa-arrow-left"></i> BACK TO LIST</a>
            </div>
        </div>
        <ul class="gf-tabs">
            <li :class="activeTab === 'basic' ? 'active' : ''" @click="activeTab = 'basic'"><a>Basic</a></li>
            <li :class="(activeTab === 'container' ? 'active' : '') + (saved ? '' : ' disabled-tab')" @click="saved ? activeTab = 'container' : null"><a>Container & Item</a></li>
            <li :class="(activeTab === 'accounting' ? 'active' : '') + (saved ? '' : ' disabled-tab')" @click="saved ? activeTab = 'accounting' : null"><a>Accounting <i class="fa fa-sliders" style="margin-left: 4px; color: #888;"></i></a></li>
            <li :class="(activeTab === 'doc' ? 'active' : '') + (saved ? '' : ' disabled-tab')" @click="saved ? activeTab = 'doc' : null"><a>Doc Center</a></li>
            <li :class="(activeTab === 'workorder' ? 'active' : '') + (saved ? '' : ' disabled-tab')" @click="saved ? activeTab = 'workorder' : null"><a>Work Order</a></li>
            <li :class="(activeTab === 'status' ? 'active' : '') + (saved ? '' : ' disabled-tab')" @click="saved ? activeTab = 'status' : null"><a>Status</a></li>
        </ul>

        <!-- ==================== BASIC TAB ==================== -->
        <div x-show="activeTab === 'basic'" x-cloak>
            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption caption-subject">
                        <svg width="12" height="16" viewBox="0 0 12 16" fill="none" style="margin-right: 4px;">
                            <path d="M0 0H12V11L6 16L0 11V0Z" fill="#fff"/>
                        </svg>
                        MB/L Information
                    </div>
                    <button type="button" class="btn-default-gf" @click="loadFromQuotation = !loadFromQuotation"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                </div>
                
                <div class="portlet-body">
                    <div class="well">
                    <div class="form-grid-4">
                        <div class="form-group-gf">
                            <span class="form-label-gf required">File No.</span>
                            <div class="form-input-container">
                                <input type="text" name="file_no" class="form-control-gf" x-model="form.file_no" readonly>
                                <span class="form-error" x-show="errors.file_no" x-text="errors.file_no"></span>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf required">*Post Date</span>
                            <div class="form-input-container">
                                <input type="date" name="post_date" class="form-control-gf" x-model="form.post_date" required>
                                <span class="form-error" x-show="errors.post_date" x-text="errors.post_date"></span>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf required">*Office</span>
                            <div class="form-input-container">
                                <select name="office_id" class="form-control-gf" x-model="form.office_id" required>
                                    <option value="">Select Office...</option>
                                    @foreach($offices as $office)
                                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                                    @endforeach
                                </select>
                                <span class="form-error" x-show="errors.office_id" x-text="errors.office_id"></span>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Type</span>
                            <div class="form-input-container">
                                <select name="ship_type" class="form-control-gf" x-model="form.ship_type">
                                    <option value="Trucking">Trucker</option>
                                    <option value="Ocean">Ocean</option>
                                    <option value="Air">Air</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group-gf">
                            <span class="form-label-gf">MB/L No.</span>
                            <div class="form-input-container">
                                <input type="text" name="mbl_no" class="form-control-gf" x-model="form.mbl_no">
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">HB/L No.</span>
                            <div class="form-input-container">
                                <input type="text" name="hbl_no" class="form-control-gf" x-model="form.hbl_no">
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Vessel/Flight No.</span>
                            <div class="form-input-container">
                                <input type="text" name="vessel_flight_no" class="form-control-gf" x-model="form.vessel_flight_no">
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Carrier Bkg. No.</span>
                            <div class="form-input-container">
                                <input type="text" name="carrier_bkg_no" class="form-control-gf" x-model="form.carrier_bkg_no">
                            </div>
                        </div>
                        
                        <div class="form-group-gf">
                            <span class="form-label-gf">Quotation No.</span>
                            <div class="form-input-container">
                                <select name="quotation_id" class="form-control-gf" x-model="form.quotation_id">
                                    <option value="">Select...</option>
                                    @foreach($quotations as $quote)
                                        <option value="{{ $quote->id }}">{{ $quote->quote_no }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-default-gf dark" style="padding:0 6px;height:22px;" @click="showQuoteModal = true"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Customer</span>
                            <div class="form-input-container">
                                <x-inline-select name="customer_id" :options="$agents" module="trade-partner" x-model="form.customer_id" class="form-control-gf" />
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Customer Ref No.</span>
                            <div class="form-input-container">
                                <input type="text" name="customer_ref_no" class="form-control-gf" x-model="form.customer_ref_no">
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Shipper</span>
                            <div class="form-input-container">
                                <x-inline-select name="shipper_id" :options="$agents" module="trade-partner" x-model="form.shipper_id" class="form-control-gf" />
                            </div>
                        </div>

                        <div class="form-group-gf">
                            <span class="form-label-gf">Consignee</span>
                            <div class="form-input-container">
                                <x-inline-select name="consignee_id" :options="$agents" module="trade-partner" x-model="form.consignee_id" class="form-control-gf" />
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Trucker</span>
                            <div class="form-input-container">
                                <select name="trucker_id" class="form-control-gf" x-model="form.trucker_id">
                                    <option value="">Select Trucker...</option>
                                    @foreach($truckers as $tp)
                                        <option value="{{ $tp->id }}">{{ $tp->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-default-gf dark" style="padding:0 6px;height:22px;" onclick="window.open('/trade-partner/create','_blank')"><i class="fa fa-external-link"></i></button>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Bill To</span>
                            <div class="form-input-container">
                                <select name="bill_to_id" class="form-control-gf" x-model="form.bill_to_id">
                                    <option value="">Select...</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-default-gf dark" style="padding:0 6px;height:22px;" onclick="window.open('/trade-partner/create','_blank')"><i class="fa fa-external-link"></i></button>
                                <button type="button" class="btn-default-gf dark" style="padding:0 6px;height:22px;background:#3b73af;border-color:#3b73af;color:#fff;"><i class="fa fa-share"></i></button>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Sales</span>
                            <div class="form-input-container">
                                <select name="sales_id" class="form-control-gf" x-model="form.sales_id">
                                    <option value="">Select Sales...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group-gf">
                            <span class="form-label-gf">OP</span>
                            <div class="form-input-container">
                                <select name="op_id" class="form-control-gf" x-model="form.op_id">
                                    <option value="">Select Operator...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <hr>

                    <div class="form-grid-4">
                        <div class="form-group-gf">
                            <span class="form-label-gf">Port of Loading</span>
                            <div class="form-input-container">
                                <x-inline-select name="pol_id" :options="$ports" module="port" x-model="form.pol_id" class="form-control-gf" />
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">ETD</span>
                            <div class="form-input-container">
                                <input type="date" name="etd" class="form-control-gf" x-model="form.etd">
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Port of Discharge</span>
                            <div class="form-input-container">
                                <x-inline-select name="pod_id" :options="$ports" module="port" x-model="form.pod_id" class="form-control-gf" />
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">ETA</span>
                            <div class="form-input-container">
                                <input type="date" name="eta" class="form-control-gf" x-model="form.eta">
                            </div>
                        </div>
                    </div>

                    <div class="form-grid-4">
                        <div class="form-group-gf">
                            <span class="form-label-gf">Final Destination</span>
                            <div class="form-input-container">
                                <select name="final_destination_id" class="form-control-gf" x-model="form.final_destination_id">
                                    <option value="">Select...</option>
                                    @foreach($ports as $port)
                                        <option value="{{ $port->id }}">{{ $port->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Final ETA</span>
                            <div class="form-input-container">
                                <input type="date" name="feta" class="form-control-gf" x-model="form.feta">
                            </div>
                        </div>
                    </div>
                    
                    <hr>

                    <div class="form-grid-4">
                        <div class="form-group-gf">
                            <span class="form-label-gf">Empty Pickup</span>
                            <div class="form-input-container">
                                <select name="empty_pickup_location_id" class="form-control-gf" x-model="form.empty_pickup_location_id">
                                    <option value="">Select...</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-default-gf dark" style="padding:0 6px;height:22px;" onclick="window.open('/trade-partner/create','_blank')"><i class="fa fa-edit"></i></button>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Freight Pickup</span>
                            <div class="form-input-container">
                                <select name="freight_pickup_location_id" class="form-control-gf" x-model="form.freight_pickup_location_id">
                                    <option value="">Select...</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-default-gf dark" style="padding:0 6px;height:22px;" onclick="window.open('/trade-partner/create','_blank')"><i class="fa fa-edit"></i></button>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Delivery To</span>
                            <div class="form-input-container">
                                <select name="delivery_to_location_id" class="form-control-gf" x-model="form.delivery_to_location_id">
                                    <option value="">Select...</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-default-gf dark" style="padding:0 6px;height:22px;" onclick="window.open('/trade-partner/create','_blank')"><i class="fa fa-edit"></i></button>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Empty Return</span>
                            <div class="form-input-container">
                                <select name="empty_return_location_id" class="form-control-gf" x-model="form.empty_return_location_id">
                                    <option value="">Select...</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-default-gf dark" style="padding:0 6px;height:22px;" onclick="window.open('/trade-partner/create','_blank')"><i class="fa fa-edit"></i></button>
                            </div>
                        </div>

                        <div class="form-group-gf">
                            <span class="form-label-gf">Package</span>
                            <div class="form-input-container">
                                <input type="number" step="any" name="pkg_qty" class="form-control-gf" style="width: 30%; text-align: right;" x-model="form.pkg_qty">
                                <select name="pkg_unit_id" class="form-control-gf" style="width: 70%;" x-model="form.pkg_unit_id">
                                    <option value="">Select...</option>
                                    @foreach($packageUnits as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Weight</span>
                            <div class="form-input-container">
                                <input type="number" step="any" name="weight_kg" class="form-control-gf" style="text-align: right;" x-model="form.weight_kg"> <span style="font-size: 10px; color: #555;">KG</span>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Measurement</span>
                            <div class="form-input-container">
                                <input type="number" step="any" name="volume_cbm" class="form-control-gf" style="text-align: right;" x-model="form.volume_cbm"> <span style="font-size: 10px; color: #555;">CBM</span>
                                <input type="number" step="any" name="measure_cft" class="form-control-gf" style="text-align: right;" x-model="form.measure_cft"> <span style="font-size: 10px; color: #555;">CFT</span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-grid-4">
                        <div class="form-group-gf">
                            <span class="form-label-gf">Estimated Delivery Date</span>
                            <div class="form-input-container">
                                <input type="date" name="est_delivery_date" class="form-control-gf" x-model="form.est_delivery_date">
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf">Delivered</span>
                            <div class="form-input-container">
                                <input type="checkbox" name="is_delivered" x-model="form.is_delivered" value="1">
                                <input type="date" name="delivered_date" class="form-control-gf" x-model="form.delivered_date" :disabled="!form.is_delivered">
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <span class="form-label-gf" style="color: #32c5d2; text-align: left; margin-left: 10px; cursor: pointer;" @click="showMore = !showMore">
                                More <i class="fa" :class="showMore ? 'fa-minus-square-o' : 'fa-plus-square-o'"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div x-show="showMore" x-cloak>
                        <hr>
                        <div class="form-grid-4">
                            <div class="form-group-gf">
                                <span class="form-label-gf">E-Commerce</span>
                                <div class="form-input-container">
                                    <input type="checkbox" name="is_ecommerce" x-model="form.is_ecommerce" value="1">
                                </div>
                            </div>
                            <div class="form-group-gf">
                                <span class="form-label-gf">Truck No.</span>
                                <div class="form-input-container">
                                    <input type="text" name="truck_no" class="form-control-gf" x-model="form.truck_no">
                                </div>
                            </div>
                            <div class="form-group-gf">
                                <span class="form-label-gf">Driver Name</span>
                                <div class="form-input-container">
                                    <input type="text" name="driver_name" class="form-control-gf" x-model="form.driver_name">
                                </div>
                            </div>
                            <div class="form-group-gf">
                                <span class="form-label-gf">Driver Phone</span>
                                <div class="form-input-container">
                                    <input type="text" name="driver_phone" class="form-control-gf" x-model="form.driver_phone">
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <!-- Memo Section -->
            <div class="memo-section">
                <div class="memo-header-container">
                    <span style="font-size: 12px; font-weight: 600; color: #555;">Memo</span>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <button type="button" class="btn-default-gf dark" @click="openMemoModal()"><i class="fa fa-plus"></i> Add Memo</button>
                        <button type="button" class="btn-default-gf dark"><i class="fa fa-external-link-square"></i> Documents (<span x-text="documents.length"></span>)</button>
                    </div>
                </div>
                <div style="display: flex;">
                    <div style="flex: 7;">
                        <table class="memo-table memo-table-dark">
                            <thead>
                                <tr>
                                    <th style="width: 30px; text-align: center;">#</th>
                                    <th style="width: 30px; text-align: center;"><i class="fa fa-bell" style="color: #fff;"></i></th>
                                    <th>Subject</th>
                                    <th>Last Modified</th>
                                    <th>Created</th>
                                    <th style="width:80px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-if="memos.length === 0">
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: #888; height: 100px;">No memos found. Click "Add Memo" to create one.</td>
                                    </tr>
                                </template>
                                <template x-for="(memo, idx) in memos" :key="memo.id || idx">
                                    <tr>
                                        <td style="text-align:center;" x-text="idx + 1"></td>
                                        <td style="text-align:center;"><input type="checkbox" x-model="memo.has_alert"></td>
                                        <td><a href="#" @click.prevent="viewMemo(memo)" style="color:#337ab7;text-decoration:none;" x-text="memo.subject"></a></td>
                                        <td x-text="memo.updated_at || memo.created_at"></td>
                                        <td x-text="memo.created_at"></td>
                                        <td style="text-align:center;">
                                            <button type="button" class="btn-default-gf dark" style="padding:1px 5px;font-size:9px;" @click="editMemo(memo)"><i class="fa fa-pencil"></i></button>
                                            <button type="button" class="btn-default-gf dark" style="padding:1px 5px;font-size:9px;color:#d05454;" @click="deleteMemo(idx)"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div style="flex: 3; background: #eef1f5; padding: 10px; border: 1px solid #ddd; border-left: none;">
                        <textarea class="form-control-gf" style="height: 100px; width: 100%; border: 1px solid #ccc; resize: none; background: #fff;" x-model="selectedMemoContent" :placeholder="selectedMemoContent ? '' : 'Select a memo to view content...'" :disabled="!selectedMemoContent"></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ==================== CONTAINER & ITEM TAB ==================== -->
        <div x-show="activeTab === 'container'" x-cloak>
            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption caption-subject">
                        <svg width="12" height="16" viewBox="0 0 12 16" fill="none" style="margin-right: 4px;">
                            <path d="M0 0H12V11L6 16L0 11V0Z" fill="#fff"/>
                        </svg>
                        MB/L INFORMATION
                    </div>
                    <div style="display: flex; gap: 5px;">
                        <button type="button" class="btn-default-gf" @click="saveAllContainers()" :disabled="!saved">
                            <i class="fa fa-save"></i> SAVE ALL CONTAINERS
                        </button>
                        <button type="button" class="btn-default-gf" @click="saveAllCommodities()" :disabled="!saved">
                            <i class="fa fa-save"></i> SAVE ALL COMMODITIES
                        </button>
                    </div>
                </div>
                
                <div class="portlet-body">
                    <div class="well">
                    <!-- PO No -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                        <h4 style="font-size: 13px; font-weight: 700; margin: 0; color: #32c5d2;">P.O. No. <span style="color: #888; font-weight: normal; font-size: 10px; margin-left: 5px;">Please list down P.O. No. for this MB/L</span></h4>
                        <div style="display: flex; align-items: center; gap: 10px; font-size: 10px;">
                            <span style="font-weight: 600;">P.O. Mapping</span>
                            <label style="display: flex; align-items: center; gap: 4px;"><input type="radio" x-model="po_mapping" value="C"> Container based</label>
                            <label style="display: flex; align-items: center; gap: 4px;"><input type="radio" x-model="po_mapping" value="I"> Item based</label>
                        </div>
                    </div>
                    <div style="display:flex;gap:4px;margin-bottom:15px;">
                        <input type="text" class="form-control-gf" placeholder="Add P.O. here..." x-model="newPoNo" style="flex:1;">
                        <button type="button" class="btn-gf-inline" @click="addPoNo()" style="margin-top:0;"><i class="fa fa-plus"></i> Add</button>
                    </div>
                    <template x-if="poNos.length > 0">
                        <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:10px;">
                            <template x-for="(po, idx) in poNos" :key="idx">
                                <span style="display:inline-flex;align-items:center;gap:3px;background:#e8f0fe;border:1px solid #c4d7f5;border-radius:3px;padding:1px 6px;font-size:10px;color:#333;">
                                    <span x-text="po"></span>
                                    <button type="button" @click="poNos.splice(idx,1)" style="border:none;background:none;cursor:pointer;padding:0;color:#999;font-size:12px;line-height:1;">&times;</button>
                                </span>
                            </template>
                        </div>
                    </template>
                    
                    <hr>
                    
                    <!-- Container List -->
                    <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 8px;flex-wrap:wrap;">
                        <h4 style="font-size: 13px; font-weight: 700; margin: 0; color: #32c5d2; margin-right: 5px;">Container List</h4>
                        <button type="button" class="btn-gf-inline" @click="addContainer()"><i class="fa fa-plus"></i> Add</button>
                        <button type="button" class="btn-gf-inline" @click="addContainers(5)" style="background:#67809f;">+5</button>
                        <button type="button" class="btn-default-gf dark" @click="duplicateContainer()"><i class="fa fa-clone"></i></button>
                        <button type="button" class="btn-default-gf dark" @click="deleteSelectedContainers()" :disabled="selectedContainers.length === 0"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn-gf-inline" style="background:#3b73af;">Create Pier Pass A/P</button>
                    </div>
                    
                    <table class="memo-table" style="margin-bottom: 15px; text-align: center;">
                        <thead>
                            <tr>
                                <th style="width: 25px; text-align: center;"><input type="checkbox" @change="toggleAllContainers($event.target.checked)"></th>
                                <th style="width: 25px; text-align: center;">#</th>
                                <th style="text-align: center;">Pier Pass A/P</th>
                                <th style="text-align: center;">Container No.</th>
                                <th>TP/SZ</th>
                                <th>Seal No.</th>
                                <th>Pick Up No.</th>
                                <th>PKG</th>
                                <th>Weight</th>
                                <th>Measurement</th>
                                <th>LFD</th>
                                <th>Appt.</th>
                                <th>Pick Up</th>
                                <th>Empty Return</th>
                                <th x-show="po_mapping === 'C'">P.O. No.</th>
                                <th style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="containers.length === 0">
                                <tr>
                                    <td :colspan="po_mapping === 'C' ? 16 : 15" style="text-align: center; color: #888; height: 35px;">
                                        No Data Available. Please click <span style="color: #32c5d2; cursor:pointer;" @click="addContainer()">here</span> to add a new row.
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(cont, idx) in containers" :key="cont.id || idx">
                                <tr :style="cont._unsaved ? 'background: #fffbeb;' : ''">
                                    <td style="text-align:center;"><input type="checkbox" :value="idx" x-model="selectedContainers"></td>
                                    <td style="text-align:center;" x-text="idx + 1"></td>
                                    <td><input type="text" class="form-control-gf" x-model="cont.pier_pass" style="width:70px;" @input="cont._unsaved = true"></td>
                                    <td><input type="text" class="form-control-gf" x-model="cont.container_no" placeholder="Container No." style="width:120px;" @input="cont._unsaved = true"></td>
                                    <td>
                                        <select class="form-control-gf" x-model="cont.container_type_id" style="width:80px;" @change="cont._unsaved = true">
                                            <option value="">Select...</option>
                                            @foreach($containerTypes as $ct)<option value="{{ $ct->id }}">{{ $ct->code }}</option>@endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control-gf" x-model="cont.seal_no" style="width:80px;" @input="cont._unsaved = true"></td>
                                    <td><input type="text" class="form-control-gf" x-model="cont.pickup_no" style="width:80px;" @input="cont._unsaved = true"></td>
                                    <td><input type="number" step="any" class="form-control-gf" x-model="cont.pkg" style="width:60px;text-align:right;" @input="cont._unsaved = true"></td>
                                    <td><input type="number" step="any" class="form-control-gf" x-model="cont.weight" style="width:70px;text-align:right;" @input="cont._unsaved = true"></td>
                                    <td><input type="number" step="any" class="form-control-gf" x-model="cont.measurement" style="width:70px;text-align:right;" @input="cont._unsaved = true"></td>
                                    <td><input type="date" class="form-control-gf" x-model="cont.lfd" style="width:90px;" @change="cont._unsaved = true"></td>
                                    <td><input type="date" class="form-control-gf" x-model="cont.appointment" style="width:90px;" @change="cont._unsaved = true"></td>
                                    <td><input type="date" class="form-control-gf" x-model="cont.pickup_date" style="width:90px;" @change="cont._unsaved = true"></td>
                                    <td><input type="date" class="form-control-gf" x-model="cont.empty_return_date" style="width:90px;" @change="cont._unsaved = true"></td>
                                    <td x-show="po_mapping === 'C'"><input type="text" class="form-control-gf" x-model="cont.po_no" style="width:100px;" @input="cont._unsaved = true"></td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn-default-gf dark" style="padding:2px 6px;font-size:9px;" @click="saveContainer(idx)" :disabled="!cont._unsaved" title="Save">
                                            <i class="fa fa-save"></i>
                                        </button>
                                        <button type="button" class="btn-default-gf dark" style="padding:2px 6px;font-size:9px;color:#d05454;" @click="deleteContainer(idx)" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <tr style="background: #f9f9f9;">
                                <td colspan="4"></td>
                                <td style="text-align: center;"><input type="radio" name="total_source" value="container" x-model="totalSource"></td>
                                <td colspan="2" style="text-align: left;">Container Total</td>
                                <td style="text-align: right; color: #32c5d2;" x-text="containerTotals.pkg"></td>
                                <td style="text-align: right; color: #32c5d2;" x-text="containerTotals.weight"></td>
                                <td style="text-align: right; color: #32c5d2;" x-text="containerTotals.measurement"></td>
                                <td :colspan="po_mapping === 'C' ? 5 : 4"></td>
                            </tr>
                            <tr style="background: #f9f9f9;">
                                <td colspan="4"></td>
                                <td style="text-align: center;"><input type="radio" name="total_source" value="manual" x-model="totalSource"></td>
                                <td colspan="2" style="text-align: left;">Manual Input Total</td>
                                <td><input type="text" class="form-control-gf" style="text-align: right; background: #eef1f5;" x-model="manualTotal.pkg" :disabled="totalSource !== 'manual'"></td>
                                <td style="text-align: right;">
                                    <input type="text" class="form-control-gf" style="width: 60px; display: inline-block; text-align: right; background: #eef1f5;" x-model="manualTotal.weight" :disabled="totalSource !== 'manual'"> KG
                                </td>
                                <td style="text-align: right;">
                                    <input type="text" class="form-control-gf" style="width: 60px; display: inline-block; text-align: right; background: #eef1f5;" x-model="manualTotal.measurement" :disabled="totalSource !== 'manual'"> CBM
                                </td>
                                <td :colspan="po_mapping === 'C' ? 5 : 4"></td>
                            </tr>
                            <tr style="background: #f9f9f9;">
                                <td colspan="4"></td>
                                <td style="text-align: center;"><input type="radio" name="total_source" value="receiving" x-model="totalSource" disabled></td>
                                <td colspan="2" style="text-align: left;">Receiving Total</td>
                                <td :colspan="po_mapping === 'C' ? 8 : 7" style="text-align: left;">
                                    <button type="button" class="btn-gf-inline" style="background: #3b73af;" @click="openWarehouseLoadModal()"><i class="fa fa-external-link-square"></i> Load from Warehouse</button>
                                    <button type="button" class="btn-gf-inline" style="background: #3b73af;" @click="openCreateReceiptModal()"><i class="fa fa-external-link-square"></i> Create Receipt and Link</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <hr>
                    
                    <!-- Commodity -->
                    <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 8px;">
                        <h4 style="font-size: 13px; font-weight: 700; margin: 0; color: #32c5d2; margin-right: 5px;">Commodity</h4>
                        <button type="button" class="btn-gf-inline" @click="addCommodity()"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn-default-gf dark" @click="deleteSelectedCommodities()" :disabled="selectedCommodities.length === 0"><i class="fa fa-trash"></i></button>
                    </div>
                    
                    <table class="memo-table" style="margin-bottom: 15px;">
                        <thead>
                            <tr>
                                <th style="width: 25px; text-align: center;"><input type="checkbox" @change="toggleAllCommodities($event.target.checked)"></th>
                                <th><span style="color: #d05454;">*</span>Commodity Description</th>
                                <th>HTS Code</th>
                                <th>Container</th>
                                <th x-show="po_mapping === 'I'">P.O. No.</th>
                                <th style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="commodities.length === 0">
                                <tr>
                                    <td :colspan="po_mapping === 'I' ? 6 : 5" style="text-align: center; color: #888; height: 35px;">
                                        No Data Available. Please click <span style="color: #32c5d2; cursor:pointer;" @click="addCommodity()">here</span> to add a new row.
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(comm, idx) in commodities" :key="comm.id || idx">
                                <tr :style="comm._unsaved ? 'background: #fffbeb;' : ''">
                                    <td style="text-align:center;"><input type="checkbox" :value="idx" x-model="selectedCommodities"></td>
                                    <td><input type="text" class="form-control-gf" x-model="comm.description" placeholder="Commodity description" @input="comm._unsaved = true"></td>
                                    <td><input type="text" class="form-control-gf" x-model="comm.hts_code" placeholder="HTS Code" style="width:120px;" @input="comm._unsaved = true"></td>
                                    <td>
                                        <select class="form-control-gf" x-model="comm.container_idx" style="width:120px;" @change="comm._unsaved = true">
                                            <option value="">Select...</option>
                                            <template x-for="(c, ci) in containers" :key="ci">
                                                <option :value="ci" x-text="c.container_no || 'Container ' + (ci+1)"></option>
                                            </template>
                                        </select>
                                    </td>
                                    <td x-show="po_mapping === 'I'"><input type="text" class="form-control-gf" x-model="comm.po_no" style="width:100px;" @input="comm._unsaved = true"></td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn-default-gf dark" style="padding:2px 6px;font-size:9px;" @click="saveCommodity(idx)" :disabled="!comm._unsaved" title="Save">
                                            <i class="fa fa-save"></i>
                                        </button>
                                        <button type="button" class="btn-default-gf dark" style="padding:2px 6px;font-size:9px;color:#d05454;" @click="deleteCommodity(idx)" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    
                    <hr>
                    
                    <!-- Warehouse Receipt List -->
                    <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 15px;">
                        <h4 style="font-size: 13px; font-weight: 700; margin: 0; color: #32c5d2; margin-right: 5px;">Warehouse Receipt List</h4>
                        <button type="button" class="btn-default-gf dark" disabled><i class="fa fa-trash"></i></button>
                    </div>
                    
                    <table class="memo-table" style="margin-bottom:15px;">
                        <thead>
                            <tr><th style="text-align:center;color:#888;padding:10px;">No warehouse receipts linked.</th></tr>
                        </thead>
                    </table>
                    
                    <hr>
                    
                    <!-- Instruction & Description -->
                    <div style="display: flex; gap: 20px;">
                        <div style="flex: 1;">
                            <h4 style="font-size: 12px; font-weight: 600; margin: 0 0 5px 0; color: #333;">Instruction</h4>
                            <textarea name="instruction_text" class="form-control-gf" style="height: 75px; resize: none;" x-model="instructionText"></textarea>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 5px;">
                                <h4 style="font-size: 13px; font-weight: 700; margin: 0; color: #32c5d2;">Description</h4>
                                <div style="font-size: 10px;">
                                    <span style="color: #888; margin-right: 5px;">Copy:</span>
                                    <button type="button" class="btn-default-gf dark" style="padding: 2px 6px;" disabled>P.O.</button>
                                    <button type="button" class="btn-default-gf dark" style="padding: 2px 6px;" @click="copyCommoditiesToDescription()">Commodity</button>
                                    <button type="button" class="btn-default-gf dark" style="padding: 2px 6px;" disabled>Commodity & HTS</button>
                                </div>
                            </div>
                            <textarea name="description" class="form-control-gf" style="height: 75px; resize: none;" x-model="form.description"></textarea>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== WAREHOUSE LOAD MODAL ==================== -->
        <div x-show="showWarehouseLoadModal" class="modal-overlay" style="display:none;" x-cloak @click.self="closeWarehouseLoadModal()">
            <div class="modal-container" style="max-width: 1100px;">
                <div class="modal-header">
                    <span><i class="fa fa-warehouse text-blue-500"></i> Load from Warehouse</span>
                    <i class="fa fa-times cursor-pointer text-gray-500 hover:text-gray-700" @click="closeWarehouseLoadModal()"></i>
                </div>

                <div class="modal-body">
                    <!-- Search Filters -->
                    <div class="form-grid-4" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 15px;">
                        <div class="form-group-gf">
                            <label class="form-label-gf">Warehouse</label>
                            <div class="form-input-container">
                                <select class="form-control-gf" x-model="warehouseFilters.warehouse_id">
                                    <option value="">All Warehouses</option>
                                    @foreach($warehouses ?? [] as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <label class="form-label-gf">Receipt No.</label>
                            <div class="form-input-container">
                                <input type="text" class="form-control-gf" x-model="warehouseFilters.receipt_no" placeholder="Search...">
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <label class="form-label-gf">Customer</label>
                            <div class="form-input-container">
                                <select class="form-control-gf" x-model="warehouseFilters.customer_id">
                                    <option value="">All Customers</option>
                                    @foreach($agents ?? [] as $agent)
                                        @if($agent->is_customer)
                                            <option value="{{ $agent->id }}">{{ $agent->company_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <label class="form-label-gf">Status</label>
                            <div class="form-input-container">
                                <select class="form-control-gf" x-model="warehouseFilters.status">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="received">Received</option>
                                    <option value="linked">Linked</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 15px;">
                        <button type="button" class="btn-default-gf" @click="clearWarehouseFilters()">Clear</button>
                        <button type="button" class="btn-gofreight" @click="searchWarehouseReceipts()"><i class="fa fa-search"></i> Search</button>
                    </div>

                    <!-- Warehouse Receipts Table -->
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th style="width: 30px; text-align: center;"><input type="checkbox" @change="toggleAllWarehouseReceipts($event.target.checked)"></th>
                                    <th>Receipt No.</th>
                                    <th>Warehouse</th>
                                    <th>Customer</th>
                                    <th>Receive Date</th>
                                    <th style="text-align: right;">PKG</th>
                                    <th style="text-align: right;">Weight (KG)</th>
                                    <th style="text-align: right;">CBM</th>
                                    <th>Status</th>
                                    <th>Commodity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-if="warehouseReceipts.length === 0">
                                    <tr>
                                        <td colspan="10" style="text-align: center; color: #94a3b8; font-size: 11px; padding: 20px;">
                                            No warehouse receipts found. Use the search filters above.
                                        </td>
                                    </tr>
                                </template>
                                <template x-for="receipt in warehouseReceipts" :key="receipt.id">
                                    <tr style="cursor: pointer;" :class="selectedWarehouseReceipts.includes(receipt.id) ? 'bg-blue-50' : ''" @click="toggleWarehouseReceipt(receipt.id)">
                                        <td style="text-align: center;" @click.stop>
                                            <input type="checkbox" :checked="selectedWarehouseReceipts.includes(receipt.id)" @change="toggleWarehouseReceipt(receipt.id)">
                                        </td>
                                        <td x-text="receipt.receipt_no"></td>
                                        <td x-text="receipt.warehouse_name"></td>
                                        <td x-text="receipt.customer_name"></td>
                                        <td x-text="receipt.receive_date"></td>
                                        <td style="text-align: right;" x-text="receipt.pkg"></td>
                                        <td style="text-align: right;" x-text="receipt.weight"></td>
                                        <td style="text-align: right;" x-text="receipt.cbm"></td>
                                        <td>
                                            <span :style="'padding: 2px 8px; border-radius: 3px; font-size: 9px; font-weight: 600; background: ' + (receipt.status === 'received' ? '#d4edda' : receipt.status === 'linked' ? '#cce5ff' : '#fff3cd') + '; color: ' + (receipt.status === 'received' ? '#155724' : receipt.status === 'linked' ? '#004085' : '#856404')" x-text="receipt.status.toUpperCase()"></span>
                                        </td>
                                        <td x-text="receipt.commodity" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 10px; padding: 8px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 10px;">
                        <strong>Selected:</strong> <span x-text="selectedWarehouseReceipts.length"></span> receipt(s)
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-default-gf" @click="closeWarehouseLoadModal()">Cancel</button>
                    <button type="button" class="btn-gofreight" @click="loadSelectedWarehouseReceipts()" :disabled="selectedWarehouseReceipts.length === 0">
                        <i class="fa fa-check"></i> Load Selected (<span x-text="selectedWarehouseReceipts.length"></span>)
                    </button>
                </div>
            </div>
        </div>

        <!-- ==================== CREATE RECEIPT MODAL ==================== -->
        <div x-show="showCreateReceiptModal" class="modal-overlay" style="display:none;" x-cloak @click.self="closeCreateReceiptModal()">
            <div class="modal-container" style="max-width: 800px;">
                <div class="modal-header">
                    <span><i class="fa fa-plus-circle text-blue-500"></i> Create Warehouse Receipt and Link</span>
                    <i class="fa fa-times cursor-pointer text-gray-500 hover:text-gray-700" @click="closeCreateReceiptModal()"></i>
                </div>

                <div class="modal-body">
                    <div class="form-grid-4" style="grid-template-columns: repeat(2, 1fr);">
                        <div class="form-group-gf">
                            <label class="form-label-gf required">Warehouse</label>
                            <div class="form-input-container">
                                <select class="form-control-gf" x-model="receiptForm.warehouse_id" required>
                                    <option value="">Select Warehouse...</option>
                                    @foreach($warehouses ?? [] as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <label class="form-label-gf required">Receipt No.</label>
                            <div class="form-input-container">
                                <input type="text" class="form-control-gf" x-model="receiptForm.receipt_no" placeholder="Auto-generated" required>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <label class="form-label-gf">Customer</label>
                            <div class="form-input-container">
                                <select class="form-control-gf" x-model="receiptForm.customer_id">
                                    <option value="">Select Customer...</option>
                                    @foreach($agents ?? [] as $agent)
                                        @if($agent->is_customer)
                                            <option value="{{ $agent->id }}">{{ $agent->company_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <label class="form-label-gf required">Receive Date</label>
                            <div class="form-input-container">
                                <input type="date" class="form-control-gf" x-model="receiptForm.receive_date" required>
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <label class="form-label-gf">PKG</label>
                            <div class="form-input-container">
                                <input type="number" step="any" class="form-control-gf" x-model="receiptForm.pkg" style="text-align: right;">
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <label class="form-label-gf">Weight (KG)</label>
                            <div class="form-input-container">
                                <input type="number" step="any" class="form-control-gf" x-model="receiptForm.weight" style="text-align: right;">
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <label class="form-label-gf">CBM</label>
                            <div class="form-input-container">
                                <input type="number" step="any" class="form-control-gf" x-model="receiptForm.cbm" style="text-align: right;">
                            </div>
                        </div>
                        <div class="form-group-gf">
                            <label class="form-label-gf">Status</label>
                            <div class="form-input-container">
                                <select class="form-control-gf" x-model="receiptForm.status">
                                    <option value="pending">Pending</option>
                                    <option value="received">Received</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-gf" style="margin-top: 10px;">
                        <label class="form-label-gf">Commodity</label>
                        <div class="form-input-container" style="flex: 1;">
                            <textarea class="form-control-gf" x-model="receiptForm.commodity" rows="3" placeholder="Enter commodity description..."></textarea>
                        </div>
                    </div>

                    <div class="form-group-gf" style="margin-top: 10px;">
                        <label class="form-label-gf">Remark</label>
                        <div class="form-input-container" style="flex: 1;">
                            <textarea class="form-control-gf" x-model="receiptForm.remark" rows="2" placeholder="Optional remarks..."></textarea>
                        </div>
                    </div>

                    <div style="margin-top: 15px; padding: 10px; background: #e8f4fd; border: 1px solid #bee5eb; border-radius: 4px; font-size: 10px;">
                        <i class="fa fa-info-circle" style="color: #0c5460;"></i> 
                        <strong>Note:</strong> This receipt will be automatically linked to this truck shipment after creation.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-default-gf" @click="closeCreateReceiptModal()">Cancel</button>
                    <button type="button" class="btn-gofreight" @click="createAndLinkReceipt()">
                        <i class="fa fa-save"></i> Create and Link
                    </button>
                </div>
            </div>
        </div>

        <!-- ==================== ACCOUNTING TAB ==================== -->
        <div x-show="activeTab === 'accounting'" x-cloak>
            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption caption-subject">
                        <span style="display: inline-block; width: 14px; height: 18px; background: #fff; clip-path: polygon(100% 0, 100% 66%, 50% 100%, 0 66%, 0 0); margin-right: 5px;"></span>
                        TRUCKING ACCOUNTING
                    </div>
                    <div style="display: flex; gap: 5px; position: relative;">
                        <button type="button" class="btn-default-gf" style="height: 22px; padding: 0 8px; font-size: 10px;" @click="toolsOpen = !toolsOpen">
                            <i class="fa fa-cogs"></i> TOOLS <i class="fa fa-angle-down"></i>
                        </button>
                        <div x-show="toolsOpen" @click.away="toolsOpen = false" style="position: absolute; top: 100%; right: 0; background: white; border: 1px solid #ddd; z-index: 100; min-width: 220px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); border-radius: 2px;">
                            <a href="#" @click.prevent="toolsOpen = false; toggleBlock()" class="dropdown-item">
                                <i class="fa fa-ban" style="width: 16px; margin-right: 8px;"></i> 
                                <span x-text="form.is_blocked ? 'UNBLOCK' : 'BLOCK'"></span>
                            </a>
                            <a href="#" @click.prevent="toolsOpen = false; generatePickupDeliveryOrder()" class="dropdown-item">
                                <i class="fa fa-truck" style="width: 16px; margin-right: 8px;"></i> PICKUP / DELIVERY ORDER
                            </a>
                            <a href="#" @click.prevent="toolsOpen = false; printBOL()" class="dropdown-item">
                                <i class="fa fa-file-pdf-o" style="width: 16px; margin-right: 8px;"></i> BOL PRINT
                            </a>
                            <div style="height: 1px; background: #eee; margin: 4px 0;"></div>
                            <a href="#" @click.prevent="toolsOpen = false; generateProfitReportSummary()" class="dropdown-item">
                                <i class="fa fa-chart-bar" style="width: 16px; margin-right: 8px;"></i> PROFIT REPORT - SUMMARY
                            </a>
                            <a href="#" @click.prevent="toolsOpen = false; generateProfitReportDetail()" class="dropdown-item">
                                <i class="fa fa-chart-line" style="width: 16px; margin-right: 8px;"></i> PROFIT REPORT - DETAIL
                            </a>
                            <div style="height: 1px; background: #eee; margin: 4px 0;"></div>
                            <a href="#" @click.prevent="toolsOpen = false; viewCargoManifestStatus()" class="dropdown-item">
                                <i class="fa fa-list-alt" style="width: 16px; margin-right: 8px;"></i> CARGO MANIFEST STATUS
                            </a>
                            <a href="#" @click.prevent="toolsOpen = false; openInTrackTrace()" class="dropdown-item">
                                <i class="fa fa-map-marker-alt" style="width: 16px; margin-right: 8px;"></i> OPEN IN TRACK-TRACE
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="portlet-body">
                    <!-- Accounting Navigation Buttons -->
                    <div style="display: flex; gap: 6px; margin-bottom: 10px; flex-wrap: wrap;">
                        <button type="button" @click.prevent="createInvoice('AR')" class="btn-gofreight" style="background: #32c5d2; border: none; color: white; padding: 6px 12px; border-radius: 3px; font-size: 11px; cursor: pointer; transition: all 0.2s;">
                            <i class="fa fa-plus"></i> ORIGIN REVENUE (INVOICE/AR)
                        </button>
                        <button type="button" @click.prevent="createInvoice('DC')" class="btn-gofreight" style="background: #32c5d2; border: none; color: white; padding: 6px 12px; border-radius: 3px; font-size: 11px; cursor: pointer; transition: all 0.2s;">
                            <i class="fa fa-plus"></i> DESTINATION REVENUE/COST (D/C NOTE)
                        </button>
                        <button type="button" @click.prevent="createInvoice('AP')" class="btn-gofreight" style="background: #32c5d2; border: none; color: white; padding: 6px 12px; border-radius: 3px; font-size: 11px; cursor: pointer; transition: all 0.2s;">
                            <i class="fa fa-plus"></i> ORIGIN COST (AP)
                        </button>
                    </div>

                    <table class="memo-table" style="text-align: center; margin-bottom: 15px;">
                        <thead>
                            <tr>
                                <th style="width: 25px;"></th>
                                <th style="width: 25px;"></th>
                                <th style="text-align: left;">Invoice No.</th>
                                <th style="text-align: left;">Party</th>
                                <th style="text-align: right;">Revenue</th>
                                <th style="text-align: right;">Cost</th>
                                <th style="text-align: right;">Balance</th>
                                <th style="text-align: center;">Status</th>
                                <th style="text-align: right;">Post Date</th>
                                <th style="text-align: right;">Invoice Date</th>
                                <th style="text-align: center;">Email</th>
                                <th style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="charges.length === 0">
                                <tr>
                                    <td colspan="12" style="text-align: center; padding: 25px; color: #999; font-style: italic;">
                                        <i class="fa fa-file-text-o" style="font-size: 20px; display: block; margin-bottom: 5px;"></i>
                                        No charges added yet. Use the buttons above to create charges.
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(charge, idx) in charges" :key="charge.id || idx">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:left;" x-text="charge.invoice_no || 'Draft'"></td>
                                    <td style="text-align:left;" x-text="charge.party_name || 'N/A'"></td>
                                    <td style="text-align:right;" x-text="charge.type === 'AR' ? charge.amount : '0.00'"></td>
                                    <td style="text-align:right;" x-text="charge.type === 'AP' ? charge.amount : '0.00'"></td>
                                    <td style="text-align:right;" x-text="charge.balance || charge.amount"></td>
                                    <td style="text-align:center;"><span style="font-size:9px;padding:2px 6px;border-radius:2px;background:#e8e8e8;" x-text="charge.is_invoiced ? 'Invoiced' : 'Draft'"></span></td>
                                    <td style="text-align:right;" x-text="charge.post_date || '-'"></td>
                                    <td style="text-align:right;" x-text="charge.invoice_date || '-'"></td>
                                    <td style="text-align:center;">-</td>
                                    <td style="text-align:center;">
                                        <button type="button" class="btn-default-gf dark" style="padding:1px 5px;font-size:9px;" @click="editCharge(charge)"><i class="fa fa-pencil"></i></button>
                                        <button type="button" class="btn-default-gf dark" style="padding:1px 5px;font-size:9px;color:#d05454;" @click="deleteCharge(idx)"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </template>
                            <tr>
                                <td colspan="4" style="text-align: right;"><strong style="font-size: 11px;">Total</strong></td>
                                <td style="text-align: right; color: #3b73af;"><strong style="font-size: 11px;" x-text="totals.revenue.toFixed(2)"></strong></td>
                                <td style="text-align: right; color: #3b73af;"><strong style="font-size: 11px;" x-text="totals.cost.toFixed(2)"></strong></td>
                                <td style="text-align: right; color: #3b73af;"><strong style="font-size: 11px;" x-text="totals.balance.toFixed(2)"></strong></td>
                                <td colspan="5"></td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right;"><strong style="font-size: 11px;">Amount</strong></td>
                                <td colspan="2" style="text-align: right; color: #3b73af;"><strong style="font-size: 11px;" x-text="totals.profit.toFixed(2)"></strong></td>
                                <td colspan="6"></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <table class="memo-table" style="margin-bottom: 15px;">
                        <colgroup>
                            <col style="width: 55%;">
                            <col style="width: 15%;">
                            <col style="width: 15%;">
                            <col style="width: 15%;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th></th>
                                <th style="text-align: right; font-size: 11px; color: #888; font-weight: normal;">Amount</th>
                                <th style="text-align: right; font-size: 11px; color: #888; font-weight: normal;">Profit Percentage</th>
                                <th style="text-align: right; font-size: 11px; color: #888; font-weight: normal;">Profit Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: right; font-size: 11px;">Total Profit</td>
                                <td style="text-align: right; color: #3b73af; font-weight: 600;" x-text="totals.profit.toFixed(2)"></td>
                                <td style="text-align: right; color: #3b73af; font-weight: 600;" x-text="totals.profitPercentage"></td>
                                <td style="text-align: right; color: #3b73af; font-weight: 600;" x-text="totals.profitMargin"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==================== DOC CENTER TAB ==================== -->
        <div x-show="activeTab === 'doc'" x-cloak>
            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption caption-subject">
                        <span style="display: inline-block; width: 14px; height: 18px; background: #fff; clip-path: polygon(100% 0, 100% 66%, 50% 100%, 0 66%, 0 0); margin-right: 5px;"></span>
                        Documents
                    </div>
                    <div>
                        <button type="button" class="btn-default-gf"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                    </div>
                </div>
                
                <div class="portlet-body">
                    <div style="margin-bottom: 15px; display: flex; gap: 8px; flex-wrap: wrap;">
                        <label class="btn-gf-inline" style="background:#4b77be;margin:0;cursor:pointer;">
                            <i class="fa fa-upload"></i> Upload Document
                            <input type="file" name="document" x-ref="documentInput" style="display:none;" @change="uploadDocument($event)">
                        </label>
                        <button type="button" class="btn-default-gf dark"><i class="fa fa-download"></i> Batch Download</button>
                        <button type="button" class="btn-default-gf dark"><i class="fa fa-envelope-o"></i> Email</button>
                    </div>

                    <table class="memo-table" style="margin-bottom: 15px;">
                        <thead>
                            <tr>
                                <th style="width: 25px; text-align: center;"><input type="checkbox" @change="toggleAllDocuments($event.target.checked)"></th>
                                <th style="width: 30px;"></th>
                                <th>Document Name</th>
                                <th>Category</th>
                                <th>Remark</th>
                                <th>File Name</th>
                                <th style="text-align: right;">Size</th>
                                <th style="text-align: right;">Upload Date</th>
                                <th>Uploader</th>
                                <th style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="documents.length === 0">
                                <tr>
                                    <td colspan="10" style="text-align: center; padding: 50px; color: #999; font-style: italic;">
                                        <i class="fa fa-folder-open-o" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                                        No documents uploaded yet. Click "Upload Document" to add files.
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(doc, idx) in documents" :key="doc.id || idx">
                                <tr>
                                    <td style="text-align:center;"><input type="checkbox" :value="idx" x-model="selectedDocuments"></td>
                                    <td style="text-align:center;"><i class="fa fa-file-pdf-o" style="color:#d05454;" x-show="doc.file_extension === 'pdf'"></i><i class="fa fa-file-image-o" style="color:#4b77be;" x-show="['jpg','jpeg','png','gif'].includes(doc.file_extension)"></i><i class="fa fa-file-text-o" style="color:#888;" x-show="!['pdf','jpg','jpeg','png','gif'].includes(doc.file_extension)"></i></td>
                                    <td x-text="doc.file_name || doc.original_name"></td>
                                    <td x-text="doc.document_type || 'General'"></td>
                                    <td x-text="doc.description || '-'"></td>
                                    <td x-text="doc.file_name"></td>
                                    <td style="text-align:right;" x-text="doc.file_size ? (doc.file_size / 1024).toFixed(1) + ' KB' : '-'"></td>
                                    <td style="text-align:right;" x-text="doc.created_at"></td>
                                    <td x-text="doc.uploader_name || 'N/A'"></td>
                                    <td style="text-align:center;">
                                        <a :href="doc.download_url || '#'" class="btn-default-gf dark" style="padding:1px 5px;font-size:9px;text-decoration:none;" :download="doc.file_name" target="_blank"><i class="fa fa-download"></i></a>
                                        <button type="button" class="btn-default-gf dark" style="padding:1px 5px;font-size:9px;color:#d05454;" @click="deleteDocument(idx)"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    
                    <div class="file-upload-zone" @click="$refs.documentInput.click()">
                        <i class="fa fa-cloud-upload" style="font-size: 24px; color: #32c5d2; display: block; margin-bottom: 5px;"></i>
                        <span style="font-size: 12px; color: #888;">Drag & drop files here or click to browse</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== WORK ORDER TAB ==================== -->
        <div x-show="activeTab === 'workorder'" x-cloak>
            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption caption-subject">
                        <span style="display: inline-block; width: 14px; height: 18px; background: #fff; clip-path: polygon(100% 0, 100% 66%, 50% 100%, 0 66%, 0 0); margin-right: 5px;"></span>
                        Work Orders
                    </div>
                    <div>
                        <button type="button" class="btn-default-gf"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                    </div>
                </div>
                
                <div class="portlet-body" style="padding: 0;">
                    <div style="padding: 10px; background: #eef1f5; display: flex; gap: 4px;">
                        <button type="button" class="btn-gf-inline" style="margin: 0; background: #32c5d2;" @click="createWorkOrder()"><i class="fa fa-plus"></i></button>
                        <button type="button" class="btn-default-gf dark" style="margin: 0; padding: 4px 10px; border-radius: 3px; border: 1px solid #ccc; color: #555;" @click="deleteSelectedWorkOrders()" :disabled="selectedWorkOrders.length === 0"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn-default-gf dark" style="margin-left:auto;" @click="syncWorkOrders()"><i class="fa fa-refresh"></i> Refresh</button>
                    </div>
                    <table class="memo-table memo-table-dark" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th style="width: 30px; text-align: center;"><input type="checkbox" @change="toggleAllWorkOrders($event.target.checked)"></th>
                                <th style="text-align: center; width: 50px;">No.</th>
                                <th style="text-align: center;">D/O Type</th>
                                <th>Freight Pickup</th>
                                <th>Delivery</th>
                                <th>Trucker</th>
                                <th style="text-align: center;">Status</th>
                                <th style="text-align: center;">Last Modified</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="workOrders.length === 0">
                                <tr>
                                    <td colspan="9" style="background: #f9f9f9; text-align: center; color: #888; padding: 20px;">
                                        <i class="fa fa-truck" style="font-size:18px;display:block;margin-bottom:5px;"></i>
                                        No work orders yet. Click the "+" button to create one.
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(wo, idx) in workOrders" :key="wo.id">
                                <tr>
                                    <td style="text-align:center;"><input type="checkbox" :value="wo.id" x-model="selectedWorkOrders"></td>
                                    <td style="text-align:center;" x-text="wo.work_order_no || wo.no"></td>
                                    <td style="text-align:center;" x-text="wo.type || 'Delivery'"></td>
                                    <td x-text="wo.freight_pickup_name || '-'"></td>
                                    <td x-text="wo.delivery_name || '-'"></td>
                                    <td x-text="wo.trucker || wo.vendor_name || '-'"></td>
                                    <td style="text-align:center;">
                                        <span :class="'status-badge status-' + (wo.status || 'PENDING').toLowerCase()" 
                                              style="font-size:9px;padding:2px 6px;border-radius:2px;"
                                              :style="wo.status === 'COMPLETED' ? 'background:#26c281;color:#fff;' : wo.status === 'IN_PROGRESS' ? 'background:#578ebe;color:#fff;' : wo.status === 'CANCELLED' ? 'background:#d05454;color:#fff;' : 'background:#e8e8e8;color:#555;'"
                                              x-text="wo.status || 'PENDING'"></span>
                                    </td>
                                    <td style="text-align:center;" x-text="wo.updated_at || wo.date || '-'"></td>
                                    <td style="text-align:center;">
                                        <div style="display:flex;gap:4px;justify-content:center;">
                                            <button type="button" class="btn-default-gf dark" style="padding:1px 5px;font-size:9px;" @click="editWorkOrder(wo.id)"><i class="fa fa-pencil"></i></button>
                                            <button type="button" class="btn-default-gf dark" style="padding:1px 5px;font-size:9px;color:#d05454;" @click="deleteWorkOrder(wo.id)"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==================== STATUS TAB ==================== -->
        <div x-show="activeTab === 'status'" x-cloak>
            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption caption-subject">
                        <span style="display: inline-block; width: 14px; height: 18px; background: #fff; clip-path: polygon(100% 0, 100% 66%, 50% 100%, 0 66%, 0 0); margin-right: 5px;"></span>
                        Status & History
                    </div>
                    <div>
                        <button type="button" class="btn-default-gf"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                    </div>
                </div>
                
                <div class="portlet-body">
                    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                        <div style="flex: 1;">
                            <h4 style="font-size: 13px; font-weight: 600; color: #333; margin: 0 0 10px 0;">Role</h4>
                            <div style="display: flex; flex-direction: column; gap: 15px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="font-size: 11px; font-weight: 600; width: 40px;">OP :</span>
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background: #4b77be; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600;" x-text="form.op_id ? (users.find(u => u.id == form.op_id)?.name?.charAt(0) || '?') : '?'"></div>
                                    <select name="op_id" class="form-control-gf" style="width: 200px; height: 26px;" x-model="form.op_id">
                                        <option value="">Select...</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="font-size: 11px; font-weight: 600; width: 40px;">SALES:</span>
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background: #4b77be; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600;" x-text="form.sales_id ? (users.find(u => u.id == form.sales_id)?.name?.charAt(0) || '?') : '?'"></div>
                                    <select name="sales_id" class="form-control-gf" style="width: 200px; height: 26px;" x-model="form.sales_id">
                                        <option value="">Select...</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div style="flex: 2;">
                            <h4 style="font-size: 13px; font-weight: 600; color: #333; margin: 0 0 10px 0;">Internal Message</h4>
                            <textarea name="internal_remark" class="form-control-gf" style="width: 100%; height: 65px; resize: none; border: 1px solid #ccc;" x-model="form.internal_remark"></textarea>
                        </div>
                    </div>

                    <h4 style="font-size: 13px; font-weight: 600; color: #333; margin: 0 0 15px 0;">Change Log</h4>
                    
                    <template x-if="statusLogs.length === 0">
                        <div style="text-align:center;padding:20px;color:#888;">
                            <i class="fa fa-history" style="font-size:20px;display:block;margin-bottom:5px;"></i>
                            No status history yet. Changes will be logged automatically as the shipment is updated.
                        </div>
                    </template>
                    
                    <template x-for="(log, idx) in statusLogs" :key="log.id || idx">
                        <div style="display: flex; gap: 20px;">
                            <div style="text-align: right; width: 100px;">
                                <div style="font-size: 11px; color: #888;" x-text="log.event_time || log.created_at"></div>
                            </div>
                            <div style="position: relative; display: flex; flex-direction: column; align-items: center; width: 40px;">
                                <div style="width: 30px; height: 30px; border-radius: 50%; border: 3px solid #4b77be; display: flex; align-items: center; justify-content: center; background: #fff; z-index: 2;">
                                    <div style="width: 20px; height: 20px; border-radius: 50%; background: #4b77be; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;" x-text="log.user_name ? log.user_name.charAt(0).toUpperCase() : '?'"></div>
                                </div>
                                <div style="width: 4px; background: #ddd; position: absolute; top: 30px; bottom: -20px; z-index: 1;"></div>
                            </div>
                            <div style="flex: 1; padding-bottom: 20px;">
                                <div style="position: relative; background: #f9f9f9; padding: 10px; border: 1px solid #eef1f5;">
                                    <div style="position: absolute; left: -6px; top: 10px; width: 0; height: 0; border-top: 6px solid transparent; border-bottom: 6px solid transparent; border-right: 6px solid #f9f9f9;"></div>
                                    <h4 style="font-size: 12px; font-weight: 600; color: #333; margin: 0 0 5px 0;" x-text="log.status_name || log.action"></h4>
                                    <div style="font-size: 11px; color: #888;" x-text="log.user_name || 'System'"></div>
                                    <div style="font-size: 11px; color: #666; margin-top: 5px;" x-text="log.details || ''"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Toolbar -->
      

        <!-- Serialized containers & memos for form submission -->
        <input type="hidden" name="containers" id="containers_input" value="">
        <input type="hidden" name="memos" id="memos_input" value="">
        </form>

    <!-- Memo Add/Edit Modal -->
    <div class="modal-backdrop" x-show="memoModalOpen" style="display: none;"></div>
    <div class="modal" x-show="memoModalOpen" style="display: none;">
        <div class="modal-dialog" style="width:500px;" @click.stop>
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" x-text="memoEditIndex === -1 ? 'Add Memo' : 'Edit Memo'"></h4>
                    <button type="button" class="close-btn" @click="memoModalOpen = false">&times;</button>
                </div>
                <form @submit.prevent="saveMemo()">
                    <div class="modal-body">
                        <div style="margin-bottom:10px;">
                            <label style="display:block;font-size:11px;font-weight:600;margin-bottom:4px;">Subject</label>
                            <input type="text" class="form-control-gf" style="height:24px;font-size:11px;" x-model="memoForm.subject" required>
                        </div>
                        <div style="margin-bottom:10px;">
                            <label style="display:block;font-size:11px;font-weight:600;margin-bottom:4px;">Content</label>
                            <textarea class="form-control-gf" style="height:120px;resize:none;font-size:11px;" x-model="memoForm.content"></textarea>
                        </div>
                        <div>
                            <label style="display:flex;align-items:center;gap:5px;font-size:11px;">
                                <input type="checkbox" x-model="memoForm.has_alert"> Enable pop-up alert
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-default-gf dark" style="padding:6px 12px;font-size:12px;" @click="memoModalOpen = false">Cancel</button>
                        <button type="submit" class="btn-gf" style="margin:0;padding:6px 20px;">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Charge Add/Edit Modal (Steps/Wizard) -->
    <div class="modal-backdrop" x-show="chargeModalOpen" style="display: none;"></div>
    <div class="modal" x-show="chargeModalOpen" style="display: none;">
        <div class="modal-dialog" style="width:650px;" @click.stop>
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" x-text="chargeForm.is_dc_note ? 'Create D/C Note' : (chargeForm.type === 'AR' ? 'Create Invoice' : 'Create Cost')"></h4>
                    <button type="button" class="close-btn" @click="chargeModalOpen = false">&times;</button>
                </div>
                <form @submit.prevent="saveCharge()">
                    <div class="modal-body">
                        <!-- Steps Indicator -->
                        <div class="step-container" style="margin-bottom: 20px;">
                            <div class="step">
                                <div class="step-id" :class="chargeStep >= 1 ? 'active' : ''"><span>1</span></div>
                                <div class="step-title">Charge Info</div>
                            </div>
                            <div class="step-divider" :class="chargeStep > 1 ? 'active' : ''"></div>
                            <div class="step">
                                <div class="step-id" :class="chargeStep >= 2 ? 'active' : ''"><span>2</span></div>
                                <div class="step-title">Pricing</div>
                            </div>
                            <div class="step-divider" :class="chargeStep > 2 ? 'active' : ''"></div>
                            <div class="step">
                                <div class="step-id" :class="chargeStep >= 3 ? 'active' : ''"><span>3</span></div>
                                <div class="step-title">Parties & Notes</div>
                            </div>
                        </div>

                        <!-- Step 1: Charge Info -->
                        <div x-show="chargeStep === 1">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                                <div>
                                    <label style="display:block;font-size:11px;font-weight:600;margin-bottom:4px;">Type</label>
                                    <select class="form-control-gf" style="height:24px;font-size:11px;" x-model="chargeForm.type" :disabled="chargeForm.is_dc_note">
                                        <option value="AR">AR - Revenue</option>
                                        <option value="AP">AP - Cost</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="display:block;font-size:11px;font-weight:600;margin-bottom:4px;">Charge Code</label>
                                    <input type="text" class="form-control-gf" style="height:24px;font-size:11px;" x-model="chargeForm.charge_code" placeholder="e.g. FREIGHT">
                                </div>
                                <div style="grid-column: span 2;">
                                    <label style="display:block;font-size:11px;font-weight:600;margin-bottom:4px;">Charge Name</label>
                                    <input type="text" class="form-control-gf" style="height:24px;font-size:11px;" x-model="chargeForm.charge_name" placeholder="Description of charge">
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Pricing -->
                        <div x-show="chargeStep === 2">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                                <div>
                                    <label style="display:block;font-size:11px;font-weight:600;margin-bottom:4px;">Currency</label>
                                    <select class="form-control-gf" style="height:24px;font-size:11px;" x-model="chargeForm.currency_id">
                                        <option value="">Select...</option>
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}">{{ $currency->code }} - {{ $currency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label style="display:block;font-size:11px;font-weight:600;margin-bottom:4px;">Qty</label>
                                    <input type="number" step="any" class="form-control-gf" style="height:24px;font-size:11px;" x-model="chargeForm.qty">
                                </div>
                                <div>
                                    <label style="display:block;font-size:11px;font-weight:600;margin-bottom:4px;">Unit</label>
                                    <input type="text" class="form-control-gf" style="height:24px;font-size:11px;" x-model="chargeForm.unit" placeholder="e.g. KG, CTN">
                                </div>
                                <div>
                                    <label style="display:block;font-size:11px;font-weight:600;margin-bottom:4px;">Rate</label>
                                    <input type="number" step="any" class="form-control-gf" style="height:24px;font-size:11px;" x-model="chargeForm.rate" @input="chargeForm.amount = parseFloat(chargeForm.rate || 0) * parseFloat(chargeForm.qty || 1)">
                                </div>
                                <div style="grid-column: span 2;">
                                    <label style="display:block;font-size:11px;font-weight:600;margin-bottom:4px;">Amount</label>
                                    <input type="number" step="any" class="form-control-gf" style="height:24px;font-size:11px;background:#eee;" x-model="chargeForm.amount" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Parties & Notes -->
                        <div x-show="chargeStep === 3">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                                <div>
                                    <label style="display:block;font-size:11px;font-weight:600;margin-bottom:4px;">Bill To</label>
                                    <select class="form-control-gf" style="height:24px;font-size:11px;" x-model="chargeForm.bill_to_id">
                                        <option value="">Select...</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label style="display:block;font-size:11px;font-weight:600;margin-bottom:4px;">Vendor</label>
                                    <select class="form-control-gf" style="height:24px;font-size:11px;" x-model="chargeForm.vendor_id">
                                        <option value="">Select...</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="grid-column: span 2;">
                                    <label style="display:block;font-size:11px;font-weight:600;margin-bottom:4px;">Remark</label>
                                    <textarea class="form-control-gf" style="height:50px;resize:none;font-size:11px;" x-model="chargeForm.remark"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <button type="button" class="btn-default-gf dark" style="padding:6px 12px;font-size:12px;" @click="chargeStep > 1 ? chargeStep-- : (chargeModalOpen = false)"><i class="fa fa-chevron-left"></i> <span x-text="chargeStep === 1 ? 'Cancel' : 'Previous'"></span></button>
                        </div>
                        <div style="font-size:11px;color:#888;">
                            Step <span x-text="chargeStep"></span> of 3
                        </div>
                        <div>
                            <template x-if="chargeStep < 3">
                                <button type="button" class="btn-gf" style="margin:0;padding:6px 20px;" @click="chargeStep++">Next <i class="fa fa-chevron-right"></i></button>
                            </template>
                            <template x-if="chargeStep === 3">
                                <button type="submit" class="btn-gf" style="margin:0;padding:6px 20px;"><i class="fa fa-check"></i> Save</button>
                            </template>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <script>
        function truckCreateApp() {
            return {
                activeTab: 'basic',
                saved: typeof truckShipmentSaved !== "undefined" ? truckShipmentSaved : @json(isset($truckShipment) ? true : false),
                errors: {},
                chargeStep: 1,
                toolsOpen: false,
                showMore: false,
                loadFromQuotation: false,
                
                // Warehouse Modal States
                showWarehouseLoadModal: false,
                showCreateReceiptModal: false,
                warehouseReceipts: [],
                selectedWarehouseReceipts: [],
                warehouseFilters: {
                    warehouse_id: '',
                    receipt_no: '',
                    customer_id: '',
                    status: ''
                },
                receiptForm: {
                    warehouse_id: '',
                    receipt_no: '',
                    customer_id: '',
                    receive_date: new Date().toISOString().split('T')[0],
                    pkg: 0,
                    weight: 0,
                    cbm: 0,
                    status: 'received',
                    commodity: '',
                    remark: ''
                },
                
                // ===== Users data for dynamic rendering =====
                users: @json($users),
                
                // ===== Dropdown Options (Dynamic) =====
                agents: [],           // Trade partners (customers, shippers, consignees, etc.)
                ports: [],            // Ports
                offices: [],          // Offices
                quotations: [],       // Quotations
                truckers: [],         // Truckers
                locations: [],        // Locations
                packageUnits: [],     // Package units
                containerTypes: [],   // Container types
                warehouses: [],       // Warehouses
                currencies: [],       // Currencies
                vendors: [],          // Vendors for charges
                
                // ===== Quote Modal =====
                showQuoteModal: new URLSearchParams(window.location.search).get('load_from_quotation') === 'true' || {{ isset($page) && $page === 'create-quote' ? 'true' : 'false' }},
                showQuoteConfig: false,
                quoteStep: 1,
                selectedQuote: null,
                saveAsDraftInvoice: false,
                quoteSearch: {
                    selected_id: '',
                    results: []
                },
                colVisibility: {
                    quote_no: true,
                    valid_date: true,
                    status: true,
                    commodity: true,
                    pol: true,
                    pod: true,
                    sales: true,
                    op: true
                },
                filters: {
                    customer: '',
                    pol: '',
                    quote_no: '',
                    valid_date: '',
                    pod: '',
                    status: '',
                    commodity: '',
                    sales: '',
                    op: ''
                },
                searchFilters: {
                    customer: '',
                    pol: '',
                    quote_no: '',
                    valid_date: '',
                    pod: '',
                    status: '',
                    commodity: '',
                    sales: '',
                    op: ''
                },
                get filteredQuotes() {
                    return this.quoteSearch.results.filter(q => {
                        if (this.searchFilters.quote_no && !q.quote_no.toLowerCase().includes(this.searchFilters.quote_no.toLowerCase())) return false;
                        if (this.searchFilters.customer && q.customer_id != this.searchFilters.customer) return false;
                        if (this.searchFilters.pol && q.pol_id != this.searchFilters.pol) return false;
                        if (this.searchFilters.pod && q.pod_id != this.searchFilters.pod) return false;
                        if (this.searchFilters.status && q.status?.toUpperCase() !== this.searchFilters.status.toUpperCase()) return false;
                        if (this.searchFilters.sales && q.sales_person_id != this.searchFilters.sales) return false;
                        if (this.searchFilters.op && q.op != this.searchFilters.op) return false;
                        return true;
                    });
                },
                applySearch() {
                    this.searchFilters = { ...this.filters };
                },
                clearSearch() {
                    this.filters = {
                        customer: '',
                        pol: '',
                        quote_no: '',
                        valid_date: '',
                        pod: '',
                        status: '',
                        commodity: '',
                        sales: '',
                        op: ''
                    };
                    this.searchFilters = { ...this.filters };
                },
                quoteForm: {
                    quote_no: '',
                    mbl_no: '',
                    hbl_no: '',
                    etd: '',
                    eta: '',
                    customer: '',
                    customer_id: '',
                    sales: '',
                    sales_person_id: '',
                    pol_id: '',
                    pod_id: '',
                    pol_name: '',
                    pod_name: '',
                    vessel_flight_no: '',
                    carrier_bkg_no: '',
                    shipper: '',
                    consignee: '',
                    trucker: '',
                    op: '',
                    detail: ''
                },
                selectQuote(data) {
                    this.selectedQuote = { ...data };
                    this.quoteForm.quote_no = data.quote_no || '';
                    this.quoteForm.mbl_no = data.mbl_no || '';
                    this.quoteForm.hbl_no = data.hbl_no || '';
                    this.quoteForm.eta = data.eta || '';
                    this.quoteForm.etd = data.etd || '';
                    this.quoteForm.customer = data.customer_name || '';
                    this.quoteForm.customer_id = data.customer_id || '';
                    this.quoteForm.sales = data.sales_person_name || '';
                    this.quoteForm.sales_person_id = data.sales_person_id || '';
                    this.quoteForm.pol_id = data.pol_id || '';
                    this.quoteForm.pod_id = data.pod_id || '';
                    this.quoteForm.pol_name = data.pol_name || '';
                    this.quoteForm.pod_name = data.pod_name || '';
                    this.quoteForm.op = data.op_name || '';
                    this.quoteStep = 2;
                },
                confirmQuoteSelection() {
                    if (this.selectedQuote && this.selectedQuote.id) this.form.quotation_id = this.selectedQuote.id;
                    
                    if (this.quoteForm.mbl_no) this.form.mbl_no = this.quoteForm.mbl_no;
                    if (this.quoteForm.hbl_no) this.form.hbl_no = this.quoteForm.hbl_no;
                    if (this.quoteForm.eta) this.form.eta = this.quoteForm.eta;
                    if (this.quoteForm.etd) this.form.etd = this.quoteForm.etd;
                    if (this.quoteForm.customer_id) this.form.customer_id = this.quoteForm.customer_id;
                    if (this.quoteForm.sales_person_id) this.form.sales_id = this.quoteForm.sales_person_id;
                    if (this.quoteForm.pol_id) this.form.pol_id = this.quoteForm.pol_id;
                    if (this.quoteForm.pod_id) this.form.pod_id = this.quoteForm.pod_id;
                    if (this.selectedQuote && this.selectedQuote.items) {
                        const items = this.selectedQuote.items.filter(item => item.selected !== false);
                        items.forEach(item => {
                            this.charges.push({
                                id: null,
                                selected: false,
                                party: 'Custom',
                                party_name_id: '',
                                sal: 'Sea',
                                pr: 'Rec',
                                ppc: 'Colle',
                                chrg_code: item.charge_code,
                                charge_name: item.charge_name,
                                currency: item.currency || 'USD',
                                rate: item.rate,
                                qty: item.qty,
                                qty_type: item.unit || 'UNIT',
                                roe: 1.0,
                                vat: 0,
                                inv_no: '',
                                financial_date: new Date().toISOString().split('T')[0],
                                eq_bl_no: '',
                                remark: false,
                                mbl_no: ''
                            });
                        });
                    }
                    this.showQuoteModal = false;
                },
                async searchQuotes() {
                    try {
                        const params = new URLSearchParams();
                        if (this.filters.customer) params.append('customer_id', this.filters.customer);
                        if (this.filters.commodity) params.append('commodity', this.filters.commodity);
                        if (this.filters.sales) params.append('sales_person_id', this.filters.sales);
                        if (this.filters.pol) params.append('pol_id', this.filters.pol);
                        if (this.filters.pod) params.append('pod_id', this.filters.pod);
                        if (this.filters.quote_no) params.append('quote_no', this.filters.quote_no);
                        if (this.filters.status) params.append('status', this.filters.status);
                        if (this.filters.op) params.append('op', this.filters.op);
                        
                        const response = await fetch(`/api/quotations?transport_mode=TRUCK&${params.toString()}`);
                        if (response.ok) {
                            const data = await response.json();
                            this.quoteSearch.results = data.data || data || [];
                            this.searchFilters = { ...this.filters };
                        }
                    } catch (e) {
                        console.error('Quote search failed:', e);
                        this.quoteSearch.results = [];
                    }
                },
                closeQuoteModal() {
                    this.showQuoteModal = false;
                    this.quoteStep = 1;
                    if (window.location.pathname.includes('create-quote')) {
                        window.location.href = '/truck/create';
                    } else if (new URLSearchParams(window.location.search).has('load_from_quotation')) {
                        window.location.href = '/truck/create';
                    }
                },
                
                // ===== Tab State =====
                po_mapping: 'C',
                totalSource: 'container',
                
                instructionText: '',
                
                // ===== Form Data =====
                form: {
                    file_no: '{{ isset($truckShipment) ? $truckShipment->file_no : "MTR-" . date("YmdHis") }}',
                    post_date: '{{ isset($truckShipment) && $truckShipment->post_date ? \Carbon\Carbon::parse($truckShipment->post_date)->format("Y-m-d") : date("Y-m-d") }}',
                    office_id: '{{ isset($truckShipment) ? $truckShipment->office_id : (isset($copyShipment) ? $copyShipment->office_id : "") }}',
                    ship_type: '{{ isset($truckShipment) ? $truckShipment->ship_type : (isset($copyShipment) ? $copyShipment->ship_type : "Trucking") }}',
                    mbl_no: '{{ isset($truckShipment) ? $truckShipment->mbl_no : (isset($copyShipment) ? $copyShipment->mbl_no : "") }}',
                    hbl_no: '{{ isset($truckShipment) ? $truckShipment->hbl_no : (isset($copyShipment) ? $copyShipment->hbl_no : "") }}',
                    vessel_flight_no: '{{ isset($truckShipment) ? $truckShipment->vessel_flight_no : (isset($copyShipment) ? $copyShipment->vessel_flight_no : "") }}',
                    carrier_bkg_no: '{{ isset($truckShipment) ? $truckShipment->carrier_bkg_no : (isset($copyShipment) ? $copyShipment->carrier_bkg_no : "") }}',
                    quotation_id: '{{ isset($truckShipment) ? $truckShipment->quotation_id : (isset($copyShipment) ? $copyShipment->quotation_id : "") }}',
                    customer_id: '{{ isset($truckShipment) ? $truckShipment->customer_id : (isset($copyShipment) ? $copyShipment->customer_id : "") }}',
                    customer_ref_no: '{{ isset($truckShipment) ? $truckShipment->customer_ref_no : (isset($copyShipment) ? $copyShipment->customer_ref_no : "") }}',
                    shipper_id: '{{ isset($truckShipment) ? $truckShipment->shipper_id : (isset($copyShipment) ? $copyShipment->shipper_id : "") }}',
                    consignee_id: '{{ isset($truckShipment) ? $truckShipment->consignee_id : (isset($copyShipment) ? $copyShipment->consignee_id : "") }}',
                    trucker_id: '{{ isset($truckShipment) ? $truckShipment->trucker_id : (isset($copyShipment) ? $copyShipment->trucker_id : "") }}',
                    bill_to_id: '{{ isset($truckShipment) ? $truckShipment->bill_to_id : (isset($copyShipment) ? $copyShipment->bill_to_id : "") }}',
                    sales_id: '{{ isset($truckShipment) ? $truckShipment->sales_id : (isset($copyShipment) ? $copyShipment->sales_id : "") }}',
                    op_id: '{{ isset($truckShipment) ? $truckShipment->op_id : (isset($copyShipment) ? $copyShipment->op_id : "") }}',
                    
                    pol_id: '{{ isset($truckShipment) ? $truckShipment->pol_id : (isset($copyShipment) ? $copyShipment->pol_id : "") }}',
                    pod_id: '{{ isset($truckShipment) ? $truckShipment->pod_id : (isset($copyShipment) ? $copyShipment->pod_id : "") }}',
                    final_destination_id: '{{ isset($truckShipment) ? $truckShipment->final_destination_id : (isset($copyShipment) ? $copyShipment->final_destination_id : "") }}',
                    etd: '{{ isset($truckShipment) && $truckShipment->etd ? \Carbon\Carbon::parse($truckShipment->etd)->format("Y-m-d") : (isset($copyShipment) && $copyShipment->etd ? \Carbon\Carbon::parse($copyShipment->etd)->format("Y-m-d") : "") }}',
                    eta: '{{ isset($truckShipment) && $truckShipment->eta ? \Carbon\Carbon::parse($truckShipment->eta)->format("Y-m-d") : (isset($copyShipment) && $copyShipment->eta ? \Carbon\Carbon::parse($copyShipment->eta)->format("Y-m-d") : "") }}',
                    feta: '{{ isset($truckShipment) && $truckShipment->feta ? \Carbon\Carbon::parse($truckShipment->feta)->format("Y-m-d") : (isset($copyShipment) && $copyShipment->feta ? \Carbon\Carbon::parse($copyShipment->feta)->format("Y-m-d") : "") }}',
                    
                    empty_pickup_location_id: '{{ isset($truckShipment) ? $truckShipment->empty_pickup_location_id : (isset($copyShipment) ? $copyShipment->empty_pickup_location_id : "") }}',
                    freight_pickup_location_id: '{{ isset($truckShipment) ? $truckShipment->freight_pickup_location_id : (isset($copyShipment) ? $copyShipment->freight_pickup_location_id : "") }}',
                    delivery_to_location_id: '{{ isset($truckShipment) ? $truckShipment->delivery_to_location_id : (isset($copyShipment) ? $copyShipment->delivery_to_location_id : "") }}',
                    empty_return_location_id: '{{ isset($truckShipment) ? $truckShipment->empty_return_location_id : (isset($copyShipment) ? $copyShipment->empty_return_location_id : "") }}',
                    
                    pkg_qty: '{{ isset($truckShipment) ? $truckShipment->pkg_qty : (isset($copyShipment) ? $copyShipment->pkg_qty : "") }}',
                    pkg_unit_id: '{{ isset($truckShipment) ? $truckShipment->pkg_unit_id : (isset($copyShipment) ? $copyShipment->pkg_unit_id : "") }}',
                    weight_kg: '{{ isset($truckShipment) ? $truckShipment->weight_kg : (isset($copyShipment) ? $copyShipment->weight_kg : "") }}',
                    volume_cbm: '{{ isset($truckShipment) ? $truckShipment->volume_cbm : (isset($copyShipment) ? $copyShipment->volume_cbm : "") }}',
                    measure_cft: '{{ isset($truckShipment) ? $truckShipment->measure_cft : (isset($copyShipment) ? $copyShipment->measure_cft : "") }}',
                    
                    est_delivery_date: '{{ isset($truckShipment) && $truckShipment->est_delivery_date ? \Carbon\Carbon::parse($truckShipment->est_delivery_date)->format("Y-m-d") : (isset($copyShipment) && $copyShipment->est_delivery_date ? \Carbon\Carbon::parse($copyShipment->est_delivery_date)->format("Y-m-d") : "") }}',
                    is_delivered: {{ isset($truckShipment) && $truckShipment->is_delivered ? 'true' : (isset($copyShipment) && $copyShipment->is_delivered ? 'true' : 'false') }},
                    delivered_date: '{{ isset($truckShipment) && $truckShipment->delivered_date ? \Carbon\Carbon::parse($truckShipment->delivered_date)->format("Y-m-d") : (isset($copyShipment) && $copyShipment->delivered_date ? \Carbon\Carbon::parse($copyShipment->delivered_date)->format("Y-m-d") : "") }}',
                    is_ecommerce: {{ isset($truckShipment) && $truckShipment->is_ecommerce ? 'true' : (isset($copyShipment) && $copyShipment->is_ecommerce ? 'true' : 'false') }},
                    is_blocked: {{ isset($truckShipment) && $truckShipment->is_blocked ? 'true' : (isset($copyShipment) && $copyShipment->is_blocked ? 'true' : 'false') }},
                    
                    truck_no: '{{ isset($truckShipment) ? $truckShipment->truck_no : (isset($copyShipment) ? $copyShipment->truck_no : "") }}',
                    driver_name: '{{ isset($truckShipment) ? $truckShipment->driver_name : (isset($copyShipment) ? $copyShipment->driver_name : "") }}',
                    driver_phone: '{{ isset($truckShipment) ? $truckShipment->driver_phone : (isset($copyShipment) ? $copyShipment->driver_phone : "") }}',
                    internal_remark: '{{ isset($truckShipment) ? $truckShipment->internal_remark : (isset($copyShipment) ? $copyShipment->internal_remark : "") }}',
                    description: '{{ isset($truckShipment) ? $truckShipment->description : (isset($copyShipment) ? $copyShipment->description : "") }}',
                },
                
                // ===== Container Management =====
                containers: [],
                selectedContainers: [],
                poNos: [],
                newPoNo: '',
                addPoNo() {
                    if (this.newPoNo.trim()) {
                        this.poNos.push(this.newPoNo.trim());
                        this.newPoNo = '';
                    }
                },
                addContainer() {
                    this.containers.push({
                        id: null,
                        container_no: '', 
                        tp_sz: '', 
                        container_type_id: '', 
                        seal_no: '', 
                        pickup_no: '',
                        pkg: 0, 
                        weight: 0, 
                        measurement: 0,
                        lfd: '', 
                        appointment: '', 
                        pickup_date: '', 
                        empty_return_date: '',
                        pier_pass: '', 
                        po_no: '',
                        _unsaved: true
                    });
                },
                addContainers(n) {
                    for (let i = 0; i < n; i++) this.addContainer();
                },
                duplicateContainer() {
                    if (this.selectedContainers.length > 0) {
                        this.selectedContainers.forEach(idx => {
                            const original = this.containers[idx];
                            if (original) {
                                const duplicate = { ...original, id: null, _unsaved: true };
                                this.containers.push(duplicate);
                            }
                        });
                        if (typeof showToast === 'function') {
                            showToast('success', `Duplicated ${this.selectedContainers.length} container(s)`);
                        }
                    } else if (this.containers.length > 0) {
                        const original = this.containers[this.containers.length - 1];
                        const duplicate = { ...original, id: null, _unsaved: true };
                        this.containers.push(duplicate);
                        if (typeof showToast === 'function') {
                            showToast('success', 'Container duplicated');
                        }
                    }
                },
                async saveContainer(idx) {
                    const container = this.containers[idx];
                    if (!container) return;
                    
                    @if(isset($truckShipment))
                    const shipmentId = {{ $truckShipment->id }};
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    
                    try {
                        const payload = { ...container };
                        delete payload._unsaved;
                        payload.truck_shipment_id = shipmentId;
                        
                        let response;
                        if (container.id) {
                            // Update existing
                            response = await fetch(`/api/truck-shipments/${shipmentId}/containers/${container.id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken || '',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(payload)
                            });
                        } else {
                            // Create new
                            response = await fetch(`/api/truck-shipments/${shipmentId}/containers`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken || '',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(payload)
                            });
                        }
                        
                        if (response.ok) {
                            const data = await response.json();
                            if (data.container) {
                                Object.assign(container, data.container);
                                container._unsaved = false;
                            }
                            if (typeof showToast === 'function') {
                                showToast('success', 'Container saved successfully');
                            }
                        } else {
                            const error = await response.json();
                            if (typeof showToast === 'function') {
                                showToast('error', error.message || 'Failed to save container');
                            }
                        }
                    } catch (e) {
                        console.error('Container save failed:', e);
                        if (typeof showToast === 'function') {
                            showToast('error', 'Failed to save container');
                        }
                    }
                    @else
                    // Mark as saved locally
                    container._unsaved = false;
                    if (typeof showToast === 'function') {
                        showToast('warning', 'Please save the shipment first, then save containers');
                    }
                    @endif
                },
                async deleteContainer(idx) {
                    if (!confirm('Delete this container?')) return;
                    
                    const container = this.containers[idx];
                    
                    @if(isset($truckShipment))
                    if (container.id) {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        const shipmentId = {{ $truckShipment->id }};
                        
                        try {
                            const response = await fetch(`/api/truck-shipments/${shipmentId}/containers/${container.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken || '',
                                    'Accept': 'application/json'
                                }
                            });
                            
                            if (response.ok) {
                                this.containers.splice(idx, 1);
                                if (typeof showToast === 'function') {
                                    showToast('success', 'Container deleted successfully');
                                }
                            } else {
                                if (typeof showToast === 'function') {
                                    showToast('error', 'Failed to delete container');
                                }
                            }
                        } catch (e) {
                            console.error('Container delete failed:', e);
                            if (typeof showToast === 'function') {
                                showToast('error', 'Failed to delete container');
                            }
                        }
                    } else {
                        this.containers.splice(idx, 1);
                    }
                    @else
                    this.containers.splice(idx, 1);
                    @endif
                },
                deleteSelectedContainers() {
                    if (this.selectedContainers.length === 0) return;
                    if (!confirm(`Delete ${this.selectedContainers.length} selected container(s)?`)) return;
                    
                    const sorted = [...this.selectedContainers].sort((a,b) => b - a);
                    sorted.forEach(idx => { 
                        this.deleteContainer(idx);
                    });
                    this.selectedContainers = [];
                },
                async saveAllContainers() {
                    @if(isset($truckShipment))
                    let savedCount = 0;
                    let errorCount = 0;
                    
                    for (let i = 0; i < this.containers.length; i++) {
                        if (this.containers[i]._unsaved) {
                            try {
                                await this.saveContainer(i);
                                savedCount++;
                            } catch (e) {
                                errorCount++;
                            }
                        }
                    }
                    
                    if (savedCount > 0) {
                        if (typeof showToast === 'function') {
                            showToast('success', `Saved ${savedCount} container(s)`);
                        }
                    }
                    if (errorCount > 0) {
                        if (typeof showToast === 'function') {
                            showToast('error', `Failed to save ${errorCount} container(s)`);
                        }
                    }
                    @else
                    if (typeof showToast === 'function') {
                        showToast('warning', 'Please save the shipment first');
                    }
                    @endif
                },
                toggleAllContainers(checked) {
                    if (checked) {
                        this.selectedContainers = this.containers.map((_, idx) => idx);
                    } else {
                        this.selectedContainers = [];
                    }
                },
                get containerTotals() {
                    return {
                        pkg: this.containers.reduce((s, c) => s + (parseFloat(c.pkg) || 0), 0),
                        weight: this.containers.reduce((s, c) => s + (parseFloat(c.weight) || 0), 0),
                        measurement: this.containers.reduce((s, c) => s + (parseFloat(c.measurement) || 0), 0)
                    };
                },
                manualTotal: { pkg: 0, weight: 0, measurement: 0 },
                
                // ===== Commodity Management =====
                commodities: [],
                selectedCommodities: [],
                addCommodity() {
                    this.commodities.push({ 
                        id: null,
                        description: '', 
                        hts_code: '', 
                        container_idx: '', 
                        po_no: '',
                        _unsaved: true
                    });
                },
                async saveCommodity(idx) {
                    const commodity = this.commodities[idx];
                    if (!commodity) return;
                    
                    @if(isset($truckShipment))
                    const shipmentId = {{ $truckShipment->id }};
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    
                    try {
                        const payload = { ...commodity };
                        delete payload._unsaved;
                        payload.truck_shipment_id = shipmentId;
                        
                        // Map container_idx to container_id
                        if (payload.container_idx !== '' && this.containers[payload.container_idx]) {
                            payload.container_id = this.containers[payload.container_idx].id;
                        }
                        delete payload.container_idx;
                        
                        let response;
                        if (commodity.id) {
                            // Update existing
                            response = await fetch(`/api/truck-shipments/${shipmentId}/commodities/${commodity.id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken || '',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(payload)
                            });
                        } else {
                            // Create new
                            response = await fetch(`/api/truck-shipments/${shipmentId}/commodities`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken || '',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(payload)
                            });
                        }
                        
                        if (response.ok) {
                            const data = await response.json();
                            if (data.commodity) {
                                Object.assign(commodity, data.commodity);
                                commodity._unsaved = false;
                            }
                            if (typeof showToast === 'function') {
                                showToast('success', 'Commodity saved successfully');
                            }
                        } else {
                            const error = await response.json();
                            if (typeof showToast === 'function') {
                                showToast('error', error.message || 'Failed to save commodity');
                            }
                        }
                    } catch (e) {
                        console.error('Commodity save failed:', e);
                        if (typeof showToast === 'function') {
                            showToast('error', 'Failed to save commodity');
                        }
                    }
                    @else
                    // Mark as saved locally
                    commodity._unsaved = false;
                    if (typeof showToast === 'function') {
                        showToast('warning', 'Please save the shipment first, then save commodities');
                    }
                    @endif
                },
                async deleteCommodity(idx) {
                    if (!confirm('Delete this commodity?')) return;
                    
                    const commodity = this.commodities[idx];
                    
                    @if(isset($truckShipment))
                    if (commodity.id) {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        const shipmentId = {{ $truckShipment->id }};
                        
                        try {
                            const response = await fetch(`/api/truck-shipments/${shipmentId}/commodities/${commodity.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken || '',
                                    'Accept': 'application/json'
                                }
                            });
                            
                            if (response.ok) {
                                this.commodities.splice(idx, 1);
                                if (typeof showToast === 'function') {
                                    showToast('success', 'Commodity deleted successfully');
                                }
                            } else {
                                if (typeof showToast === 'function') {
                                    showToast('error', 'Failed to delete commodity');
                                }
                            }
                        } catch (e) {
                            console.error('Commodity delete failed:', e);
                            if (typeof showToast === 'function') {
                                showToast('error', 'Failed to delete commodity');
                            }
                        }
                    } else {
                        this.commodities.splice(idx, 1);
                    }
                    @else
                    this.commodities.splice(idx, 1);
                    @endif
                },
                deleteSelectedCommodities() {
                    if (this.selectedCommodities.length === 0) return;
                    if (!confirm(`Delete ${this.selectedCommodities.length} selected commodity(s)?`)) return;
                    
                    const sorted = [...this.selectedCommodities].sort((a,b) => b - a);
                    sorted.forEach(idx => { 
                        this.deleteCommodity(idx);
                    });
                    this.selectedCommodities = [];
                },
                async saveAllCommodities() {
                    @if(isset($truckShipment))
                    let savedCount = 0;
                    let errorCount = 0;
                    
                    for (let i = 0; i < this.commodities.length; i++) {
                        if (this.commodities[i]._unsaved) {
                            try {
                                await this.saveCommodity(i);
                                savedCount++;
                            } catch (e) {
                                errorCount++;
                            }
                        }
                    }
                    
                    if (savedCount > 0) {
                        if (typeof showToast === 'function') {
                            showToast('success', `Saved ${savedCount} commodity(s)`);
                        }
                    }
                    if (errorCount > 0) {
                        if (typeof showToast === 'function') {
                            showToast('error', `Failed to save ${errorCount} commodity(s)`);
                        }
                    }
                    @else
                    if (typeof showToast === 'function') {
                        showToast('warning', 'Please save the shipment first');
                    }
                    @endif
                },
                toggleAllCommodities(checked) {
                    if (checked) {
                        this.selectedCommodities = this.commodities.map((_, idx) => idx);
                    } else {
                        this.selectedCommodities = [];
                    }
                },
                copyCommoditiesToDescription() {
                    this.form.description = this.commodities.map(c => c.description).filter(Boolean).join(', ');
                },
                
                // ===== Warehouse Load Modal Methods =====
                openWarehouseLoadModal() {
                    this.showWarehouseLoadModal = true;
                    this.searchWarehouseReceipts();
                },
                closeWarehouseLoadModal() {
                    this.showWarehouseLoadModal = false;
                    this.selectedWarehouseReceipts = [];
                },
                clearWarehouseFilters() {
                    this.warehouseFilters = {
                        warehouse_id: '',
                        receipt_no: '',
                        customer_id: '',
                        status: ''
                    };
                },
                async searchWarehouseReceipts() {
                    try {
                        const params = new URLSearchParams();
                        if (this.warehouseFilters.warehouse_id) params.append('warehouse_id', this.warehouseFilters.warehouse_id);
                        if (this.warehouseFilters.receipt_no) params.append('receipt_no', this.warehouseFilters.receipt_no);
                        if (this.warehouseFilters.customer_id) params.append('customer_id', this.warehouseFilters.customer_id);
                        if (this.warehouseFilters.status) params.append('status', this.warehouseFilters.status);
                        
                        // Only show unlinked or available receipts
                        params.append('available', 'true');
                        
                        const response = await fetch(`/api/warehouse-receipts?${params.toString()}`);
                        if (response.ok) {
                            const data = await response.json();
                            this.warehouseReceipts = Array.isArray(data) ? data : (data.data || []);
                        } else {
                            this.warehouseReceipts = [];
                        }
                    } catch (e) {
                        console.error('Failed to search warehouse receipts:', e);
                        this.warehouseReceipts = [];
                        if (typeof showToast === 'function') {
                            showToast('error', 'Failed to load warehouse receipts');
                        }
                    }
                },
                toggleWarehouseReceipt(id) {
                    const index = this.selectedWarehouseReceipts.indexOf(id);
                    if (index >= 0) {
                        this.selectedWarehouseReceipts.splice(index, 1);
                    } else {
                        this.selectedWarehouseReceipts.push(id);
                    }
                },
                toggleAllWarehouseReceipts(checked) {
                    if (checked) {
                        this.selectedWarehouseReceipts = this.warehouseReceipts.map(r => r.id);
                    } else {
                        this.selectedWarehouseReceipts = [];
                    }
                },
                async loadSelectedWarehouseReceipts() {
                    if (this.selectedWarehouseReceipts.length === 0) {
                        if (typeof showToast === 'function') {
                            showToast('warning', 'Please select at least one receipt');
                        }
                        return;
                    }
                    
                    @if(isset($truckShipment))
                    const shipmentId = {{ $truckShipment->id }};
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    
                    try {
                        const response = await fetch(`/api/truck-shipments/${shipmentId}/link-warehouse-receipts`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken || '',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                receipt_ids: this.selectedWarehouseReceipts
                            })
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            if (typeof showToast === 'function') {
                                showToast('success', `Linked ${this.selectedWarehouseReceipts.length} warehouse receipt(s) successfully`);
                            }
                            
                            // Update totals if provided
                            if (data.totals) {
                                this.manualTotal.pkg = data.totals.pkg || 0;
                                this.manualTotal.weight = data.totals.weight || 0;
                                this.manualTotal.measurement = data.totals.cbm || 0;
                                this.totalSource = 'receiving';
                            }
                            
                            // Close modal
                            this.closeWarehouseLoadModal();
                            
                            // Reload page to show linked receipts
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            const error = await response.json();
                            if (typeof showToast === 'function') {
                                showToast('error', error.message || 'Failed to link warehouse receipts');
                            }
                        }
                    } catch (e) {
                        console.error('Failed to link warehouse receipts:', e);
                        if (typeof showToast === 'function') {
                            showToast('error', 'Failed to link warehouse receipts');
                        }
                    }
                    @else
                    if (typeof showToast === 'function') {
                        showToast('warning', 'Please save the shipment first');
                    }
                    @endif
                },
                
                // ===== Create Receipt Modal Methods =====
                openCreateReceiptModal() {
                    this.showCreateReceiptModal = true;
                    // Reset form
                    this.receiptForm = {
                        warehouse_id: '',
                        receipt_no: '',
                        customer_id: this.form.customer_id || '',
                        receive_date: new Date().toISOString().split('T')[0],
                        pkg: 0,
                        weight: 0,
                        cbm: 0,
                        status: 'received',
                        commodity: '',
                        remark: ''
                    };
                },
                closeCreateReceiptModal() {
                    this.showCreateReceiptModal = false;
                },
                async createAndLinkReceipt() {
                    // Validate required fields
                    if (!this.receiptForm.warehouse_id) {
                        if (typeof showToast === 'function') {
                            showToast('error', 'Please select a warehouse');
                        }
                        return;
                    }
                    if (!this.receiptForm.receive_date) {
                        if (typeof showToast === 'function') {
                            showToast('error', 'Please select receive date');
                        }
                        return;
                    }
                    
                    @if(isset($truckShipment))
                    const shipmentId = {{ $truckShipment->id }};
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    
                    try {
                        const response = await fetch(`/api/truck-shipments/${shipmentId}/create-and-link-receipt`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken || '',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.receiptForm)
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            if (typeof showToast === 'function') {
                                showToast('success', 'Warehouse receipt created and linked successfully');
                            }
                            
                            // Update totals if provided
                            if (data.totals) {
                                this.manualTotal.pkg = data.totals.pkg || 0;
                                this.manualTotal.weight = data.totals.weight || 0;
                                this.manualTotal.measurement = data.totals.cbm || 0;
                                this.totalSource = 'receiving';
                            }
                            
                            // Close modal
                            this.closeCreateReceiptModal();
                            
                            // Reload page to show linked receipt
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            const error = await response.json();
                            if (typeof showToast === 'function') {
                                showToast('error', error.message || 'Failed to create warehouse receipt');
                            }
                        }
                    } catch (e) {
                        console.error('Failed to create warehouse receipt:', e);
                        if (typeof showToast === 'function') {
                            showToast('error', 'Failed to create warehouse receipt');
                        }
                    }
                    @else
                    if (typeof showToast === 'function') {
                        showToast('warning', 'Please save the shipment first');
                    }
                    @endif
                },
                
                // ===== Accounting / Charges =====
                charges: [],
                get totals() {
                    const revenue = this.charges.filter(c => c.type === 'AR').reduce((s, c) => s + (parseFloat(c.amount) || 0), 0);
                    const cost = this.charges.filter(c => c.type === 'AP').reduce((s, c) => s + (parseFloat(c.amount) || 0), 0);
                    const balance = revenue - cost;
                    const profit = revenue - cost;
                    const profitPercentage = revenue > 0 ? ((profit / revenue) * 100).toFixed(2) + '%' : 'N/A';
                    const profitMargin = revenue > 0 ? ((profit / revenue) * 100).toFixed(2) + '%' : 'N/A';
                    return { revenue, cost, balance, profit, profitPercentage, profitMargin };
                },
                
                // ===== Document Management =====
                documents: [],
                selectedDocuments: [],
                toggleAllDocuments(checked) {
                    if (checked) {
                        this.selectedDocuments = this.documents.map((_, idx) => idx);
                    } else {
                        this.selectedDocuments = [];
                    }
                },
                async uploadDocument(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    
                    const formData = new FormData();
                    formData.append('document', file);
                    formData.append('file_name', file.name);
                    
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        @if(isset($truckShipment))
                        const response = await fetch('/api/truck-shipments/{{ $truckShipment->id }}/documents', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken || '', 'Accept': 'application/json' },
                            body: formData
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.documents.push(data.data || data);
                        }
                        @else
                        alert('Please save the shipment first before uploading documents.');
                        @endif
                    } catch (e) {
                        console.error('Upload failed:', e);
                        alert('Failed to upload document.');
                    }
                    event.target.value = '';
                },
                deleteDocument(idx) {
                    const doc = this.documents[idx];
                    if (!doc) return;
                    if (!confirm('Delete this document?')) return;
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    fetch(`/api/truck-shipments/documents/${doc.id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken || '', 'Accept': 'application/json' }
                    }).then(r => {
                        if (r.ok) this.documents.splice(idx, 1);
                    }).catch(e => console.error('Delete failed:', e));
                },
                
                // ===== Work Order Management =====
                workOrders: [],
                selectedWorkOrders: [],
                toggleAllWorkOrders(checked) {
                    if (checked) {
                        this.selectedWorkOrders = this.workOrders.map(wo => wo.id);
                    } else {
                        this.selectedWorkOrders = [];
                    }
                },
                createWorkOrder() {
                    @if(isset($truckShipment))
                        window.open('/ocean-export/work-order/create?workable_type=App%5CModels%5CTruckShipment&workable_id={{ $truckShipment->id }}', '_blank');
                    @else
                        alert('Please save the shipment first before creating a work order.');
                    @endif
                },
                editWorkOrder(id) {
                    window.open(`/ocean-export/work-order/${id}/edit`, '_blank');
                },
                async deleteWorkOrder(id) {
                    if (!confirm('Delete this work order?')) return;
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        const response = await fetch(`/ocean-export/work-order/${id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': csrfToken || '', 'Accept': 'application/json' }
                        });
                        if (response.ok) {
                            this.syncWorkOrders();
                        }
                    } catch (e) {
                        console.error('Failed to delete work order:', e);
                    }
                },
                deleteSelectedWorkOrders() {
                    if (this.selectedWorkOrders.length === 0) return;
                    if (!confirm(`Delete ${this.selectedWorkOrders.length} work order(s)?`)) return;
                    this.selectedWorkOrders.forEach(id => this.deleteWorkOrder(id));
                },
                async syncWorkOrders() {
                    @if(isset($truckShipment))
                    try {
                        const response = await fetch(`/api/work-orders?workable_type=App%5CModels%5CTruckShipment&workable_id={{ $truckShipment->id }}`);
                        if (response.ok) {
                            const data = await response.json();
                            this.workOrders = Array.isArray(data) ? data : (data.data || []);
                        }
                    } catch (e) {
                        console.error('Failed to sync work orders:', e);
                    }
                    @else
                    this.workOrders = [];
                    @endif
                },
                
                // ===== Status Logs =====
                statusLogs: [],
                
                // ===== Memo Management =====
                memos: [],
                selectedMemoContent: '',
                memoModalOpen: false,
                memoEditIndex: -1,
                memoForm: { subject: '', content: '', has_alert: false },
                
                
                // ===== Charge Management =====
                chargeModalOpen: false,
                chargeEditIndex: -1,
                chargeForm: {
                    type: 'AR',
                    charge_code: '',
                    charge_name: '',
                    bill_to_id: '',
                    vendor_id: '',
                    currency_id: '',
                    qty: 1,
                    unit: '',
                    rate: 0,
                    amount: 0,
                    remark: '',
                    is_dc_note: false,
                },
                openChargeModal(type) {
                    this.chargeEditIndex = -1;
                    this.chargeForm = { type: type, charge_code: '', charge_name: '', bill_to_id: '', vendor_id: '', currency_id: '', qty: 1, unit: '', rate: 0, amount: 0, remark: '', is_dc_note: false };
                    this.chargeModalOpen = true;
                },
                openDCNoteModal() {
                    this.chargeEditIndex = -1;
                    this.chargeForm = { type: 'AR', charge_code: 'DC_NOTE', charge_name: 'Debit/Credit Note', bill_to_id: '', vendor_id: '', currency_id: '', qty: 1, unit: '', rate: 0, amount: 0, remark: '', is_dc_note: true };
                    this.chargeModalOpen = true;
                },
                editCharge(charge) {
                    this.chargeEditIndex = this.charges.indexOf(charge);
                    this.chargeForm = {
                        type: charge.type || 'AR',
                        charge_code: charge.charge_code || '',
                        charge_name: charge.charge_name || '',
                        bill_to_id: charge.bill_to_id || '',
                        vendor_id: charge.vendor_id || '',
                        currency_id: charge.currency_id || '',
                        qty: charge.qty || 1,
                        unit: charge.unit || '',
                        rate: charge.rate || 0,
                        amount: charge.amount || 0,
                        remark: charge.remark || '',
                        is_dc_note: false,
                    };
                    this.chargeModalOpen = true;
                },
                async saveCharge() {
                    if (!this.chargeForm.charge_name && !this.chargeForm.amount) return;
                    
                    @if(isset($truckShipment))
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        const payload = { ...this.chargeForm };
                        delete payload.is_dc_note;
                        
                        if (this.chargeEditIndex >= 0) {
                            // Update existing
                            const charge = this.charges[this.chargeEditIndex];
                            const response = await fetch(`/api/truck-shipments/{{ $truckShipment->id }}/charges/${charge.id}`, {
                                method: 'PUT',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '', 'Accept': 'application/json' },
                                body: JSON.stringify(payload)
                            });
                            if (response.ok) {
                                const data = await response.json();
                                if (data.charge) Object.assign(charge, data.charge);
                            }
                        } else {
                            // Create new
                            const response = await fetch('/api/truck-shipments/{{ $truckShipment->id }}/charges', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '', 'Accept': 'application/json' },
                                body: JSON.stringify(payload)
                            });
                            if (response.ok) {
                                const data = await response.json();
                                if (data.charge) this.charges.push(data.charge);
                            }
                        }
                    } catch (e) {
                        console.error('Charge save failed:', e);
                    }
                    @else
                    // Offline: push to local array
                    const chargeData = { ...this.chargeForm };
                    delete chargeData.is_dc_note;
                    chargeData.id = Date.now();
                    chargeData.is_invoiced = false;
                    if (this.chargeEditIndex >= 0) {
                        Object.assign(this.charges[this.chargeEditIndex], chargeData);
                    } else {
                        this.charges.push(chargeData);
                    }
                    @endif
                    
                    this.chargeModalOpen = false;
                },
                deleteCharge(idx) {
                    const charge = this.charges[idx];
                    if (!charge) return;
                    if (!confirm('Delete this charge?')) return;
                    
                    @if(isset($truckShipment))
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    fetch(`/api/truck-shipments/{{ $truckShipment->id }}/charges/${charge.id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken || '', 'Accept': 'application/json' }
                    }).then(r => {
                        if (r.ok) this.charges.splice(idx, 1);
                    }).catch(e => console.error('Delete failed:', e));
                    @else
                    this.charges.splice(idx, 1);
                    @endif
                },
                async createInvoiceFromCharges() {
                    @if(isset($truckShipment))
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        const response = await fetch('/api/truck-shipments/{{ $truckShipment->id }}/create-invoice', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken || '', 'Accept': 'application/json' }
                        });
                        const data = await response.json();
                        if (data.success) {
                            alert(data.message || 'Invoice created successfully.');
                            // Reload charges
                            location.reload();
                        } else {
                            alert(data.message || 'Failed to create invoice.');
                        }
                    } catch (e) {
                        console.error('Invoice creation failed:', e);
                    }
                    @else
                    alert('Please save the shipment first before creating an invoice.');
                    @endif
                },
                
                // ===== Accounting Navigation Methods =====
                createInvoice(type) {
                    // Check if shipment is saved
                    @if(isset($truckShipment))
                        const shipmentId = {{ $truckShipment->id }};
                    @else
                        const shipmentId = null;
                    @endif
                    
                    if (!shipmentId) {
                        if (typeof showToast === 'function') {
                            showToast('error', 'Please save the shipment first before creating invoices');
                        } else {
                            alert('Please save the shipment first before creating invoices');
                        }
                        return;
                    }
                    
                    // Define routes for each invoice type
                    const routes = {
                        'AR': `/accounting/invoice/create?type=AR&shipment_type=truck_shipment&shipment_id=${shipmentId}`,
                        'DC': `/accounting/invoice/create?type=DC&shipment_type=truck_shipment&shipment_id=${shipmentId}`,
                        'AP': `/accounting/invoice/create?type=AP&shipment_type=truck_shipment&shipment_id=${shipmentId}`
                    };

                    // Open invoice creation page in new tab
                    if (routes[type]) {
                        window.open(routes[type], '_blank');
                        if (typeof showToast === 'function') {
                            showToast('success', `Opening ${type} invoice creation page...`);
                        }
                    } else {
                        if (typeof showToast === 'function') {
                            showToast('info', `${type} invoice creation - Coming soon`);
                        } else {
                            alert(`${type} invoice creation - Coming soon`);
                        }
                    }
                },
                
                // ===== Tools Dropdown Methods =====
                toggleBlock() {
                    @if(isset($truckShipment))
                    const shipmentId = {{ $truckShipment->id }};
                    const currentStatus = this.form.is_blocked || false;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    
                    fetch(`/truck/${shipmentId}/toggle-block`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ is_blocked: !currentStatus })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            this.form.is_blocked = data.is_blocked;
                            if (typeof showToast === 'function') {
                                showToast('success', data.is_blocked ? 'Shipment blocked successfully' : 'Shipment unblocked successfully');
                            } else {
                                alert(data.is_blocked ? 'Shipment blocked' : 'Shipment unblocked');
                            }
                        }
                    })
                    .catch(e => {
                        console.error('Toggle block failed:', e);
                        if (typeof showToast === 'function') {
                            showToast('error', 'Failed to update block status');
                        }
                    });
                    @else
                    alert('Please save the shipment first');
                    @endif
                },
                
                generatePickupDeliveryOrder() {
                    @if(isset($truckShipment))
                    const shipmentId = {{ $truckShipment->id }};
                    window.open(`/truck/${shipmentId}/pickup-delivery-order`, '_blank');
                    if (typeof showToast === 'function') {
                        showToast('info', 'Opening Pickup/Delivery Order...');
                    }
                    @else
                    alert('Please save the shipment first before generating documents');
                    @endif
                },
                
                printBOL() {
                    @if(isset($truckShipment))
                    const shipmentId = {{ $truckShipment->id }};
                    window.open(`/truck/${shipmentId}/bol-print`, '_blank');
                    if (typeof showToast === 'function') {
                        showToast('info', 'Opening BOL Print...');
                    }
                    @else
                    alert('Please save the shipment first before printing documents');
                    @endif
                },
                
                generateProfitReportSummary() {
                    @if(isset($truckShipment))
                    const shipmentId = {{ $truckShipment->id }};
                    window.open(`/truck/${shipmentId}/profit-report-summary`, '_blank');
                    if (typeof showToast === 'function') {
                        showToast('info', 'Generating Profit Report - Summary...');
                    }
                    @else
                    alert('Please save the shipment first before generating reports');
                    @endif
                },
                
                generateProfitReportDetail() {
                    @if(isset($truckShipment))
                    const shipmentId = {{ $truckShipment->id }};
                    window.open(`/truck/${shipmentId}/profit-report-detail`, '_blank');
                    if (typeof showToast === 'function') {
                        showToast('info', 'Generating Profit Report - Detail...');
                    }
                    @else
                    alert('Please save the shipment first before generating reports');
                    @endif
                },
                
                viewCargoManifestStatus() {
                    @if(isset($truckShipment))
                    const shipmentId = {{ $truckShipment->id }};
                    window.open(`/truck/${shipmentId}/cargo-manifest-status`, '_blank');
                    if (typeof showToast === 'function') {
                        showToast('info', 'Opening Cargo Manifest Status...');
                    }
                    @else
                    alert('Please save the shipment first');
                    @endif
                },
                
                openInTrackTrace() {
                    @if(isset($truckShipment))
                    const fileNo = '{{ $truckShipment->file_no ?? '' }}';
                    if (fileNo) {
                        window.open(`/track-trace?file_no=${encodeURIComponent(fileNo)}`, '_blank');
                        if (typeof showToast === 'function') {
                            showToast('info', 'Opening in Track-Trace...');
                        }
                    } else {
                        alert('File number not available');
                    }
                    @else
                    alert('Please save the shipment first');
                    @endif
                },
                
openMemoModal() {
                    this.memoEditIndex = -1;
                    this.memoForm = { subject: '', content: '', has_alert: false };
                    this.memoModalOpen = true;
                },
                editMemo(memo) {
                    this.memoEditIndex = this.memos.indexOf(memo);
                    this.memoForm = { 
                        subject: memo.subject, 
                        content: memo.content || '', 
                        has_alert: memo.has_alert || false 
                    };
                    this.memoModalOpen = true;
                },
                viewMemo(memo) {
                    this.selectedMemoContent = memo.content || 'No content.';
                },
                async saveMemo() {
                    if (!this.memoForm.subject.trim()) return;
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    
                    @if(isset($truckShipment))
                    try {
                        if (this.memoEditIndex === -1) {
                            // Create new memo
                            const response = await fetch('/api/truck-shipments/{{ $truckShipment->id }}/memos', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '', 'Accept': 'application/json' },
                                body: JSON.stringify(this.memoForm)
                            });
                            if (response.ok) {
                                const data = await response.json();
                                this.memos.push(data.data || data);
                            }
                        } else {
                            // Update existing memo
                            const memo = this.memos[this.memoEditIndex];
                            const response = await fetch(`/api/truck-shipments/{{ $truckShipment->id }}/memos/${memo.id}`, {
                                method: 'PUT',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '', 'Accept': 'application/json' },
                                body: JSON.stringify(this.memoForm)
                            });
                            if (response.ok) {
                                Object.assign(memo, this.memoForm);
                            }
                        }
                        this.memoModalOpen = false;
                    } catch (e) {
                        console.error('Memo save failed:', e);
                    }
                    @else
                    // Local-only mode before save
                    if (this.memoEditIndex === -1) {
                        this.memos.push({
                            id: Date.now(),
                            subject: this.memoForm.subject,
                            content: this.memoForm.content,
                            has_alert: this.memoForm.has_alert,
                            created_at: new Date().toISOString().split('T')[0],
                            updated_at: new Date().toISOString().split('T')[0]
                        });
                    } else {
                        Object.assign(this.memos[this.memoEditIndex], this.memoForm);
                    }
                    this.memoModalOpen = false;
                    @endif
                },
                deleteMemo(idx) {
                    if (!confirm('Delete this memo?')) return;
                    
                    @if(isset($truckShipment))
                    const memo = this.memos[idx];
                    if (memo.id) {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        fetch(`/api/truck-shipments/{{ $truckShipment->id }}/memos/${memo.id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': csrfToken || '', 'Accept': 'application/json' }
                        }).catch(e => console.error('Delete failed:', e));
                    }
                    @endif
                    this.memos.splice(idx, 1);
                },
                
                init() {
                    // Load dropdown options first
                    this.loadDropdownOptions();
                    
                    // Serialize containers & memos on form submit
                    this.$nextTick(() => {
                        const form = document.getElementById('truckShipmentForm');
                        if (form) {
                            form.addEventListener('submit', (e) => {
                                const cInput = document.getElementById('containers_input');
                                const mInput = document.getElementById('memos_input');
                                if (cInput) cInput.value = JSON.stringify(this.containers);
                                if (mInput) mInput.value = JSON.stringify(this.memos);
                            });
                        }
                    });
                    
                    // Load initial data
                    @if(isset($truckShipment))
                        this.memos = @json($truckShipment->memos ?? []);
                        this.statusLogs = @json($truckShipment->statusLogs ?? []);
                        this.instructionText = @json($truckShipment->instruction_text ?? '');
                        
                        // Load containers from database
                        this.loadContainers();
                        
                        // Load commodities from database
                        this.loadCommodities();
                        
                        // Load charges
                        this.charges = @json($truckShipment->charges ?? []);
                        
                        // Load documents
                        this.documents = @json($truckShipment->documents ?? []);
                        
                        // Load work orders
                        this.syncWorkOrders();
                        
                        // Poll for work orders
                        setInterval(() => { this.syncWorkOrders(); }, 5000);
                    @elseif(isset($copyShipment))
                        this.memos = @json($copyShipment->memos ?? []);
                        this.containers = @json($copyShipment->containers ?? []);
                        this.commodities = @json($copyShipment->commodities ?? []);
                        this.charges = @json($copyShipment->charges ?? []);
                        
                        // Mark all as unsaved for copy mode
                        this.containers.forEach(c => { c.id = null; c._unsaved = true; });
                        this.commodities.forEach(c => { c.id = null; c._unsaved = true; });
                    @endif
                },
                
                // ===== Load Dropdown Options =====
                async loadDropdownOptions() {
                    try {
                        const [agents, ports, offices, quotations, truckers, locations, packageUnits, containerTypes, warehouses, currencies, vendors] = await Promise.all([
                            fetch('/api/dropdown-options/agents').then(r => r.json()).catch(() => ({ data: [] })),
                            fetch('/api/dropdown-options/ports').then(r => r.json()).catch(() => ({ data: [] })),
                            fetch('/api/dropdown-options/offices').then(r => r.json()).catch(() => ({ data: [] })),
                            fetch('/api/dropdown-options/quotations').then(r => r.json()).catch(() => ({ data: [] })),
                            fetch('/api/dropdown-options/truckers').then(r => r.json()).catch(() => ({ data: [] })),
                            fetch('/api/dropdown-options/locations').then(r => r.json()).catch(() => ({ data: [] })),
                            fetch('/api/dropdown-options/package-units').then(r => r.json()).catch(() => ({ data: [] })),
                            fetch('/api/dropdown-options/container-types').then(r => r.json()).catch(() => ({ data: [] })),
                            fetch('/api/dropdown-options/warehouses').then(r => r.json()).catch(() => ({ data: [] })),
                            fetch('/api/dropdown-options/currencies').then(r => r.json()).catch(() => ({ data: [] })),
                            fetch('/api/dropdown-options/vendors').then(r => r.json()).catch(() => ({ data: [] }))
                        ]);
                        
                        this.agents = agents.data || agents || [];
                        this.ports = ports.data || ports || [];
                        this.offices = offices.data || offices || [];
                        this.quotations = quotations.data || quotations || [];
                        this.truckers = truckers.data || truckers || [];
                        this.locations = locations.data || locations || [];
                        this.packageUnits = packageUnits.data || packageUnits || [];
                        this.containerTypes = containerTypes.data || containerTypes || [];
                        this.warehouses = warehouses.data || warehouses || [];
                        this.currencies = currencies.data || currencies || [];
                        this.vendors = vendors.data || vendors || [];
                    } catch (e) {
                        console.error('Failed to load dropdown options:', e);
                        if (typeof showToast === 'function') {
                            showToast('error', 'Failed to load dropdown options');
                        }
                    }
                },
                
                async loadContainers() {
                    @if(isset($truckShipment))
                    try {
                        const response = await fetch('/api/truck-shipments/{{ $truckShipment->id }}/containers');
                        if (response.ok) {
                            const data = await response.json();
                            this.containers = Array.isArray(data) ? data : (data.data || []);
                            // Mark all as saved
                            this.containers.forEach(c => { c._unsaved = false; });
                        }
                    } catch (e) {
                        console.error('Failed to load containers:', e);
                    }
                    @endif
                },
                
                async loadCommodities() {
                    @if(isset($truckShipment))
                    try {
                        const response = await fetch('/api/truck-shipments/{{ $truckShipment->id }}/commodities');
                        if (response.ok) {
                            const data = await response.json();
                            this.commodities = Array.isArray(data) ? data : (data.data || []);
                            // Mark all as saved
                            this.commodities.forEach(c => { c._unsaved = false; });
                            
                            // Map container_id to container_idx for display
                            this.commodities.forEach(comm => {
                                if (comm.container_id) {
                                    const containerIdx = this.containers.findIndex(c => c.id === comm.container_id);
                                    comm.container_idx = containerIdx >= 0 ? containerIdx : '';
                                }
                            });
                        }
                    } catch (e) {
                        console.error('Failed to load commodities:', e);
                    }
                    @endif
                },

                validateAndSubmit() {
                    this.errors = {};
                    let hasError = false;
                    const required = [
                        { name: "file_no", label: "File No." },
                        { name: "post_date", label: "Post Date" },
                        { name: "office_id", label: "Office" }
                    ];
                    for (let field of required) {
                        const el = document.querySelector(`[name="${field.name}"]`);
                        if (!el || !el.value.trim()) {
                            this.errors[field.name] = `${field.label} is required`;
                            hasError = true;
                        }
                    }
                    if (hasError) {
                        const firstError = Object.keys(this.errors)[0];
                        document.querySelector(`[name="${firstError}"]`)?.focus();
                        return;
                    }
                    // Warn if significant numeric fields are zero (user may have forgotten)
                    const importantNumeric = ['pkg_qty'];
                    importantNumeric.forEach(key => {
                        if (this.form[key] === 0 || this.form[key] === '0') {
                            // Silently set to 0 - user can enter value on edit
                        }
                    });
                    // Ensure numeric fields default to 0
                    ['pkg_qty', 'weight_kg', 'volume_cbm', 'measure_cft'].forEach(key => {
                        if (this.form[key] === null || this.form[key] === '' || this.form[key] === undefined) {
                            this.form[key] = 0;
                        }
                    });
                    document.getElementById("containers_input").value = JSON.stringify(this.containers);
                    document.getElementById("memos_input").value = JSON.stringify(this.memos);
                    document.getElementById('truckShipmentForm').submit();
                }
            }
        }
    </script>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="toast-container"></div>

    <!-- Toast Notification System -->
    <script>
        function showToast(type, msg) {
            const icons = { 
                success: 'check-circle', 
                error: 'times-circle', 
                info: 'info-circle', 
                warning: 'exclamation-triangle' 
            };
            
            const container = document.getElementById('toast-container') || (() => {
                const c = document.createElement('div');
                c.id = 'toast-container';
                c.className = 'toast-container';
                document.body.appendChild(c);
                return c;
            })();
            
            const t = document.createElement('div');
            t.className = 'toast ' + type;
            t.innerHTML = '<i class="fa fa-' + (icons[type] || 'info-circle') + '"></i> ' + msg;
            container.appendChild(t);
            setTimeout(() => t.remove(), 7000);
        }

        // Show Laravel session messages as toasts
        @if(session('success'))
            showToast('success', '{{ session('success') }}');
        @endif
        @if(session('error'))
            showToast('error', '{!! addslashes(session('error')) !!}');
        @endif
        @if(session('warning'))
            showToast('warning', '{{ session('warning') }}');
        @endif
        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast('error', '{!! addslashes($error) !!}');
            @endforeach
        @endif
    </script>

    <!-- Toast Notification Styles -->
    <style>
        .toast-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }
        .toast {
            min-width: 280px;
            padding: 14px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease-out, fadeOut 0.5s ease-in 6.5s forwards;
            pointer-events: all;
        }
        .toast i { font-size: 16px; }
        .toast.success { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); }
        .toast.error { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); }
        .toast.warning { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }
        .toast.info { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }
        
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; transform: translateX(400px); }
        }
    </style>
</x-layout>
