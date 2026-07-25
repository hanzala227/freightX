<x-layout>
    @push('styles')
    <x-form-styles />
    @endpush

    <script>
        window.airExportModule = function() {
            return {
                showQuoteModal: false,
                quoteStep: 1,
                selectedQuote: null,
                selectQuote(data) {
                    this.selectedQuote = data;
                    this.quoteForm = data;
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
                matchFilters(quote) {
                    if (this.searchFilters.quote_no && !quote.quote_no.toLowerCase().includes(this.searchFilters.quote_no.toLowerCase())) return false;
                    if (this.searchFilters.customer && quote.customer_id != this.searchFilters.customer) return false;
                    if (this.searchFilters.pol && quote.pol_id != this.searchFilters.pol) return false;
                    if (this.searchFilters.pod && quote.pod_id != this.searchFilters.pod) return false;
                    if (this.searchFilters.status && quote.status.toUpperCase() !== this.searchFilters.status.toUpperCase()) return false;
                    if (this.searchFilters.sales && quote.sales_person_id != this.searchFilters.sales) return false;
                    if (this.searchFilters.op && quote.sales_person_id != this.searchFilters.op) return false;
                    return true;
                },
                quoteForm: {
                    mawb_no: '',
                    hawb_no: '',
                    eta: '',
                    etd: '',
                    customer: '',
                    sales: ''
                },
                quoteItems: {
                    @foreach($quotations as $q)
                    "{{ $q->quote_no }}": {!! json_encode($q->items->map(fn($i) => [
                        'id' => $i->id,
                        'charge_code' => $i->charge_code,
                        'charge_name' => $i->charge_name,
                        'qty' => (float)$i->qty,
                        'unit' => $i->unit,
                        'currency' => $i->currency ? ['code' => $i->currency->code] : ['code' => 'USD'],
                        'rate' => (float)$i->rate,
                        'amount' => (float)$i->amount,
                    ])->values()->toArray()) !!},
                    @endforeach
                },
                activeTab: 'basic',
                showMblSection: true,
                showMblMemo: false,
                isDirectMaster: false,
                showMore: false,
                showConnectingFlight: false,
                hawbs: [],
                form: {
                    file_no: '{{ isset($airExport) ? $airExport->file_no : "MAE-" . date("YmdHis") }}',
                    mawb_no: '{{ isset($airExport) ? $airExport->mawb_no : "" }}',
                    office: '{{ isset($airExport) ? $airExport->office_id : "" }}',
                    carrier: '{{ isset($airExport) ? $airExport->carrier_id : "" }}',
                    issuing_carrier: 'GOFREIGHT',
                    awb_type: 'NORMAL',
                    awb_date: '',
                    shipper: '',
                    consignee: '',
                    notify: '',
                    post_date: '{{ isset($airExport) && $airExport->post_date ? \Carbon\Carbon::parse($airExport->post_date)->format("Y-m-d") : date("Y-m-d") }}',
                    awb_acct_carrier: '{{ isset($airExport) ? $airExport->acct_carrier_id : "" }}',
                    co_loader: '{{ isset($airExport) ? $airExport->forwarding_agent_id : "" }}',
                    actual_shipper: '',
                    op: '{{ isset($airExport) ? $airExport->op_id : auth()->id() }}',
                    itn_no: '',
                    cers_no: '',
                    reference_no: '',
                    display_unit: 'BOTH',
                    departure: '{{ isset($airExport) ? $airExport->dep_port_id : "" }}',
                    destination: '{{ isset($airExport) ? $airExport->dst_port_id : "" }}',
                    flight_no: '{{ isset($airExport) ? $airExport->flight_no : "" }}',
                    cargo_ready_date: '',
                    etd: '{{ isset($airExport) && $airExport->etd ? \Carbon\Carbon::parse($airExport->etd)->format("Y-m-d") : "" }}',
                    atd: '{{ isset($airExport) && $airExport->atd ? \Carbon\Carbon::parse($airExport->atd)->format("Y-m-d") : "" }}',
                    eta: '{{ isset($airExport) && $airExport->eta ? \Carbon\Carbon::parse($airExport->eta)->format("Y-m-d") : "" }}',
                    ata: '{{ isset($airExport) && $airExport->ata ? \Carbon\Carbon::parse($airExport->ata)->format("Y-m-d") : "" }}',
                    dv_carriage: 'N.V.D.',
                    dv_customs: 'N.C.V.',
                    insurance: 'N.I.L.',
                    wt_val: 'P',
                    other_term: 'P',
                    pkg_qty: '{{ isset($airExport) ? $airExport->pkg_qty : "" }}',
                    pkg_unit_id: '{{ isset($airExport) ? $airExport->pkg_unit_id : "" }}',
                    gross_weight: '{{ isset($airExport) ? $airExport->gross_weight : "" }}',
                    chargeable_weight: '{{ isset($airExport) ? $airExport->chargeable_weight : "" }}',
                    volume: '{{ isset($airExport) ? $airExport->volume : "" }}',
                    buying_rate: '{{ isset($airExport) ? $airExport->buying_rate : "" }}',
                    selling_rate: '{{ isset($airExport) ? $airExport->selling_rate : "" }}',
                    sales_type: '{{ isset($airExport) ? $airExport->sales_type : "" }}',
                    internal_remark: '{{ isset($airExport) ? $airExport->internal_remark : "" }}',
                    other_charges: [],
                    accounting_info: [],
                    commodities: [],
                    memo: ''
                },
                addCharge() { this.form.other_charges.push({ charge_code: '', description: '', term: 'P', rate: '', amount: '' }); },
                removeCharge(idx) { this.form.other_charges.splice(idx, 1); },
                addAcctInfo() { this.form.accounting_info.push({ code: '', info: '' }); },
                removeAcctInfo(idx) { this.form.accounting_info.splice(idx, 1); },
                addCommodity() { this.form.commodities.push({ description: '', hts_no: '' }); },
                removeCommodity(idx) { this.form.commodities.splice(idx, 1); },
                addHawbCommodity(hawbIdx) { this.hawbs[hawbIdx].commodities.push({ description: '', hts_no: '', po_no: '' }); },
                removeHawbCommodity(hawbIdx, idx) { this.hawbs[hawbIdx].commodities.splice(idx, 1); },
                addHawb() {
                    this.hawbs.push({
                        show: true,
                        showMemo: false,
                        showMore: false,
                        hawb_no: '',
                        booking_no: '',
                        booking_date: '',
                        quotation_no: '',
                        shipper: '',
                        customer: '',
                        bill_to: '',
                        consignee: '',
                        notify: '',
                        oversea_agent: '',
                        issuing_carrier: 'GOFREIGHT',
                        trucker: '',
                        sales: '',
                        op: this.form.op,
                        itn_no: '',
                        display_unit: 'BOTH',
                        departure: '',
                        destination: '',
                        cargo_pickup: '',
                        delivery_to: '',
                        cargo_type: 'GENERAL CARGO',
                        sales_type: '',
                        ship_type: 'NORMAL',
                        feta: '',
                        dv_carriage: 'N.V.D.',
                        dv_customs: 'N.C.V.',
                        freight_term: 'P',
                        other_charge_term: 'P',
                        insurance: 'N.I.L.',
                        cargo_ready_date: '',
                        pkg_qty: '',
                        pkg_unit_id: '',
                        gross_weight: '',
                        chargeable_weight: '',
                        volume: '',
                        buying_rate: '',
                        selling_rate: '',
                        commodity: '',
                        incoterms_id: '',
                        hbl_remark: '',
                        packages: [],
                        commodities: [],
                        mark: '',
                        description: '',
                        remark: '',
                        memo: ''
                    });
                    this.showMblSection = false;
                },
                removeHawb(idx) {
                    if(confirm('Are you sure you want to delete this HAWB?')) {
                        this.hawbs.splice(idx, 1);
                        if(this.hawbs.length === 0) this.showMblSection = true;
                    }
                },
                saveShipment() {
                    document.getElementById('airExportForm').submit();
                },
                init() {
                    @if(isset($airExport) && $airExport->hbls->count() > 0)
                        this.hawbs = {!! json_encode($airExport->hbls->map(function($hbl) {
                            return [
                                'id' => $hbl->id,
                                'show' => true,
                                'showMore' => false,
                                'showMemo' => false,
                                'hawb_no' => $hbl->hawb_no,
                                'booking_no' => $hbl->booking_no ?? '',
                                'booking_date' => $hbl->booking_date ?? '',
                                'shipper' => $hbl->shipper_id,
                                'customer' => $hbl->customer_id,
                                'bill_to' => $hbl->bill_to ?? '',
                                'consignee' => $hbl->consignee_id,
                                'notify' => $hbl->notify_party_id,
                                'sales' => $hbl->sales_person_id,
                                'oversea_agent' => $hbl->oversea_agent_id ?? '',
                                'sales_type' => $hbl->sales_type ?? '',
                                'departure' => $hbl->departure ?? '',
                                'destination' => $hbl->destination ?? '',
                                'cargo_pickup' => $hbl->cargo_pickup ?? '',
                                'delivery_to' => $hbl->delivery_to ?? '',
                                'cargo_type' => $hbl->cargo_type ?? 'GENERAL CARGO',
                                'ship_type' => $hbl->ship_type ?? 'NORMAL',
                                'feta' => $hbl->feta ?? '',
                                'itn_no' => $hbl->itn_no ?? '',
                                'pkg_qty' => $hbl->pkg_qty,
                                'pkg_unit_id' => $hbl->pkg_unit_id,
                                'gross_weight' => $hbl->gross_weight,
                                'chargeable_weight' => $hbl->chargeable_weight,
                                'volume' => $hbl->volume,
                                'buying_rate' => $hbl->buying_rate ?? '',
                                'selling_rate' => $hbl->selling_rate ?? '',
                                'commodity' => $hbl->commodity ?? '',
                                'incoterms_id' => $hbl->incoterms_id ?? '',
                                'freight_term' => $hbl->freight_term ?? 'P',
                                'hbl_remark' => $hbl->hbl_remark ?? '',
                                'mark' => $hbl->mark ?? '',
                                'description' => $hbl->description ?? '',
                                'remark' => $hbl->remark ?? '',
                            ];
                        })) !!};
                    @else
                        if(this.hawbs.length === 0) this.addHawb();
                    @endif
                    if(window.location.search.includes('load_from_quotation=true')) {
                        this.showQuoteModal = true;
                    }
                },
                closeQuoteModal() {
                    if(window.location.search.includes('load_from_quotation=true')) {
                        window.location.href = '/air-export/create';
                    } else {
                        this.showQuoteModal = false;
                    }
                },
                confirmQuoteSelection() {
                    const q = this.quoteForm;
                    
                    // MAWB
                    this.form.mawb_no = q.mawb_no || '';
                    if (q.etd) this.form.etd = q.etd.length === 10 ? q.etd + 'T00:00' : q.etd;
                    if (q.eta) this.form.eta = q.eta.length === 10 ? q.eta + 'T00:00' : q.eta;
                    if (q.pol_id) this.form.departure = q.pol_id;
                    if (q.pod_id) this.form.destination = q.pod_id;
                    if (q.op_id) this.form.op = q.op_id;
                    if (q.gross_weight_kg) this.form.gross_weight = q.gross_weight_kg;
                    if (q.volume_cbm) this.form.volume = q.volume_cbm;
                    if (q.oversea_agent_id) this.form.co_loader = q.oversea_agent_id; // Using co-loader for forwarding agent
                    
                    // HAWB
                    if (this.hawbs.length === 0) this.addHawb();
                    this.hawbs[0].hawb_no = q.hawb_no || '';
                    if (q.customer_id) this.hawbs[0].customer = q.customer_id;
                    if (q.sales_person_id) this.hawbs[0].sales = q.sales_person_id;
                    if (q.incoterms_id) this.hawbs[0].incoterms_id = q.incoterms_id;
                    if (q.gross_weight_kg) this.hawbs[0].gross_weight = q.gross_weight_kg;
                    if (q.volume_cbm) this.hawbs[0].volume = q.volume_cbm;
                    if (q.commodity) this.hawbs[0].commodity = q.commodity;
                    if (q.quote_no) this.hawbs[0].quotation_no = q.quote_no;
                    
                    // Charges
                    if (this.selectedQuote && this.selectedQuote.items) {
                        const items = this.selectedQuote.items.filter(item => item.selected !== false);
                        items.forEach(item => {
                            this.form.other_charges.push({
                                charge_code: item.charge_code,
                                description: item.charge_name,
                                term: 'P',
                                rate: item.rate,
                                amount: item.qty
                            });
                        });
                    }

                    this.showQuoteModal = false;
                    if (typeof showToast === 'function') {
                        showToast('success', 'Quotation data loaded successfully');
                    }
                }
            };
        };
    </script>

    <div x-data="window.airExportModule()" x-init="init()" x-cloak>
        <div class="page-content">
        <form id="airExportForm" action="{{ isset($airExport) ? route('air-export.update', $airExport->id) : route('air-export.store') }}" method="POST">
            @csrf
            @if(isset($airExport)) @method('PUT') @endif
        <!-- Breadcrumbs -->
        <div style="font-size: 11px; color: #8e9eae; margin-bottom: 15px;">
            <a href="/" style="color: #8e9eae; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#337ab7';" onmouseout="this.style.color='#8e9eae';" target="_blank"><i class="fa fa-home"></i> Home</a> <i class="fa fa-angle-right" style="margin: 0 5px;"></i> 
            <a href="/air-export/list" style="color: #8e9eae; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#337ab7';" onmouseout="this.style.color='#8e9eae';">Air Export</a> <i class="fa fa-angle-right" style="margin: 0 5px;"></i> 
            <span style="color: #333; font-weight: 700;">{{ isset($airExport) ? 'Edit Shipment' : 'New Shipment' }}</span>
        </div>

        <!-- Toolbar -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
            <h1 class="caption-subject" style="font-size: 18px;">{{ isset($airExport) ? 'Edit Air Export Shipment' : 'Create Air Export Shipment' }}</h1>
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn-gofreight"><i class="fa fa-save"></i> SAVE SHIPMENT</button>
                <a href="/air-export/list" class="btn-default-gf">BACK TO LIST</a>
            </div>
        </div>

        <!-- Main Tabs -->
        <ul class="gf-tabs">
            <li :class="activeTab === 'basic' ? 'active' : ''" @click="activeTab = 'basic'"><a>Basic</a></li>
            <li :class="activeTab === 'accounting' ? 'active' : ''" @if(isset($airExport) && $airExport->id) @click="activeTab = 'accounting'" @else style="opacity: 0.5; cursor: not-allowed;" title="Save Basic tab first" @endif><a>Accounting</a></li>
            <li :class="activeTab === 'workorder' ? 'active' : ''" @if(isset($airExport) && $airExport->id) @click="activeTab = 'workorder'" @else style="opacity: 0.5; cursor: not-allowed;" title="Save Basic tab first" @endif><a>Work Order</a></li>
            <li :class="activeTab === 'status' ? 'active' : ''" @if(isset($airExport) && $airExport->id) @click="activeTab = 'status'" @else style="opacity: 0.5; cursor: not-allowed;" title="Save Basic tab first" @endif><a>Status</a></li>
        </ul>

        <div style="padding-bottom: 50px;">
            <!-- BASIC TAB -->
            <div x-show="activeTab === 'basic'" class="main-grid" x-cloak>
                <div class="portlet light">
                    <div @click="showMblSection = !showMblSection" class="portlet-title" style="cursor: pointer; background: #f9fafb;">
                        <span class="caption-subject"><i class="fa" :class="showMblSection ? 'fa-minus-square-o' : 'fa-plus-square-o'"></i> MAWB</span>
                        <div class="actions">
                            <i class="fa fa-angle-down transition-transform" :class="showMblSection ? 'rotate-180' : ''"></i>
                        </div>
                    </div>
                    <div class="portlet-body" x-show="showMblSection">
                        <!-- Reminder Section for MAWB -->
                        <div class="memo-section" style="margin-bottom: 10px;">
                            <div class="memo-header" @click="showMblMemo = !showMblMemo">
                                <span>Note</span>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <button class="btn-default-gf" @click.stop="">Document (0) <i class="fa fa-external-link"></i></button>
                                    <i class="fa" :class="showMblMemo ? 'fa-angle-up' : 'fa-angle-down'"></i>
                                </div>
                            </div>
                            <div class="memo-body" x-show="showMblMemo">
                                <div style="display: flex; gap: 10px;">
                                    <div style="flex: 2; border: 1px solid #dcdcdc; min-height: 50px; background: #fff; padding: 10px; color: #999; text-align: center;">No records found.</div>
                                    <div style="flex: 1;"><textarea class="form-control-gf" style="height: 80px; resize: none;" placeholder="Reminder content..."></textarea></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-grid-4">
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">File No.</label><div class="form-input-container"><input type="text" name="file_no" class="form-control-gf" value="{{ isset($airExport) ? $airExport->file_no : 'MAE-' . date('YmdHis') }}" required></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Carrier</label><div class="form-input-container"><x-inline-select name="carrier_id" :options="$agents" module="trade-partner" x-model="form.carrier" class="form-control-gf" /></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Issuing Carrier</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="form.issuing_carrier"><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">AWB Type</label><div class="form-input-container"><x-inline-select name="awb_type" :options="$agents" module="trade-partner" x-model="form.awb_type" class="form-control-gf" /></div></div>
                            </div>
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf" style="color:red;">*MAWB No.</label><div class="form-input-container"><input type="text" name="mawb_no" class="form-control-gf" x-model="form.mawb_no" required></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">AWB Date</label><div class="form-input-container"><input type="date" class="form-control-gf" x-model="form.awb_date"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Shipper</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="form.shipper"><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Consignee</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="form.consignee"><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                            </div>
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Notify</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="form.notify"><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Post Date</label><div class="form-input-container"><input type="date" name="post_date" class="form-control-gf" x-model="form.post_date"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf" style="color:red;">*Office</label><div class="form-input-container">
                                    <select name="office_id" class="form-control-gf" required x-model="form.office">
                                        <option value="">Select Office...</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}">{{ $office->name }}</option>
                                        @endforeach
                                    </select>
                                </div></div>
                                <div class="form-group-gf"><label class="form-label-gf">AWB Acct. Carrier</label><div class="form-input-container"><x-inline-select name="acct_carrier_id" :options="$agents" module="trade-partner" x-model="form.awb_acct_carrier" class="form-control-gf" /></div></div>
                            </div>
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Co-loader</label><div class="form-input-container"><x-inline-select name="forwarding_agent_id" :options="$agents" module="trade-partner" x-model="form.co_loader" class="form-control-gf" /></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Actual Shipper</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="form.actual_shipper"><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">OP</label><div class="form-input-container">
                                    <select name="op_id" class="form-control-gf" x-model="form.op">
                                        <option value="">Select Operator...</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ITN No.</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="form.itn_no"></div></div>
                            </div>
                        </div>

                        <div class="form-grid-4">
                            <div class="flex flex-col"><div class="form-group-gf"><label class="form-label-gf">CERS No.</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="form.cers_no"></div></div></div>
                            <div class="flex flex-col"><div class="form-group-gf"><label class="form-label-gf">Reference No.</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="form.reference_no"></div></div></div>
                            <div class="flex flex-col"><div class="form-group-gf"><label class="form-label-gf">Direct Master</label><div class="form-input-container" style="justify-content: flex-start;"><input type="checkbox" x-model="isDirectMaster"></div></div></div>
                        </div>

                        <div style="height: 15px;"></div>

                        <div class="form-grid-4">
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Departure</label><div class="form-input-container"><x-inline-select name="dep_port_id" :options="$ports" module="port" x-model="form.departure" class="form-control-gf" /></div></div>
                                <div class="form-group-gf"><label class="form-label-gf" style="color:red;">*ETD</label><div class="form-input-container"><input type="date" name="etd" class="form-control-gf" x-model="form.etd" required></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Cargo Ready Date</label><div class="form-input-container"><input type="date" class="form-control-gf" x-model="form.cargo_ready_date"></div></div>
                            </div>
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Destination</label><div class="form-input-container"><x-inline-select name="dst_port_id" :options="$ports" module="port" x-model="form.destination" class="form-control-gf" /></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ATD</label><div class="form-input-container"><input type="date" name="atd" class="form-control-gf" x-model="form.atd"></div></div>
                            </div>
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Flight No.</label><div class="form-input-container"><input type="text" name="flight_no" class="form-control-gf" x-model="form.flight_no"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ETA</label><div class="form-input-container"><input type="date" name="eta" class="form-control-gf" x-model="form.eta" style="background:#fff8e1;"></div></div>
                            </div>
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Connecting Flight</label><div class="form-input-container"><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="showConnectingFlight = !showConnectingFlight">Expand <i class="fa" :class="showConnectingFlight ? 'fa-minus-square-o' : 'fa-plus-square-o'"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ATA</label><div class="form-input-container"><input type="date" name="ata" class="form-control-gf" x-model="form.ata"></div></div>
                            </div>
                        </div>

                        <!-- Weight Row -->
                        <div style="height: 15px;"></div>
                        <div class="form-grid-4">
                            <div class="flex flex-col">
                                <div class="form-group-gf">
                                    <label class="form-label-gf">Package</label>
                                    <div class="form-input-container" style="gap:2px;">
                                        <input type="number" step="any" name="pkg_qty" class="form-control-gf" style="width:40%;" x-model="form.pkg_qty">
                                        <select name="pkg_unit_id" class="form-control-gf" style="width:60%;" x-model="form.pkg_unit_id">
                                            <option value="">Select...</option>
                                            @foreach($packageUnits as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group-gf"><label class="form-label-gf">Buying Rate</label><div class="form-input-container" style="gap:4px; align-items:center;"><input type="text" name="buying_rate" class="form-control-gf" style="flex:1;" x-model="form.buying_rate"> per <x-inline-select name="buying_rate_unit" :options="$agents" module="trade-partner" class="form-control-gf" /></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Selling Rate</label><div class="form-input-container" style="gap:4px; align-items:center;"><input type="text" name="selling_rate" class="form-control-gf" style="flex:1;" x-model="form.selling_rate"> per <x-inline-select name="selling_rate_unit" :options="$agents" module="trade-partner" class="form-control-gf" /></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Volume</label><div class="form-input-container" style="gap:4px; align-items:center;"><input type="number" step="any" name="volume" class="form-control-gf" style="flex:1;" x-model="form.volume"> <span style="font-size:10px;">CBM</span></div></div>
                            </div>
                            <div class="flex flex-col">
                                <div class="form-group-gf">
                                    <label class="form-label-gf">Gross Weight</label>
                                    <div class="form-input-container" style="gap:4px; align-items:center;">
                                        <input type="number" step="any" name="gross_weight" class="form-control-gf" style="flex:1;" x-model="form.gross_weight"> <span style="font-size:10px;">KG</span>
                                    </div>
                                </div>
                                <div class="form-group-gf"><label class="form-label-gf">AWB Gross Weight</label><div class="form-input-container" style="gap:4px; align-items:center;"><input type="text" class="form-control-gf" style="flex:1;"> <span style="font-size:10px;">KG</span> <input type="text" class="form-control-gf" style="flex:1;"> <span style="font-size:10px;">LB</span></div></div>
                                <div class="form-group-gf">
                                    <label class="form-label-gf">Chargeable Weight</label>
                                    <div class="form-input-container" style="gap:4px; align-items:center;">
                                        <input type="number" step="any" name="chargeable_weight" class="form-control-gf" style="flex:1;" x-model="form.chargeable_weight"> <span style="font-size:10px;">KG</span>
                                    </div>
                                </div>
                                <div class="form-group-gf"><label class="form-label-gf">AWB Chargeable Wt</label><div class="form-input-container" style="gap:4px; align-items:center;"><input type="text" class="form-control-gf" style="flex:1;"> <span style="font-size:10px;">KG</span> <input type="text" class="form-control-gf" style="flex:1;"> <span style="font-size:10px;">LB</span></div></div>
                            </div>
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Amount</label><div class="form-input-container"><input type="text" class="form-control-gf" style="text-align:right;"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Amount</label><div class="form-input-container"><input type="text" class="form-control-gf" style="text-align:right;"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Amount</label><div class="form-input-container"><input type="text" class="form-control-gf" style="text-align:right;"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Amount</label><div class="form-input-container"><input type="text" class="form-control-gf" style="text-align:right;"></div></div>
                            </div>
                            <div class="flex flex-col">
                                <button class="btn-default-gf" style="margin-bottom:5px;">Set Dimensions</button>
                                <button class="btn-default-gf">Sum Package & Weight</button>
                            </div>
                        </div>

                        <div style="height: 15px;"></div>

                        <div class="form-grid-4">
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">D.V. Carriage</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="form.dv_carriage"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Insurance</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="form.insurance"></div></div>
                            </div>
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">D.V. Customs</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="form.dv_customs"></div></div>
                            </div>
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">WT/VAL</label><div class="form-input-container" style="font-size:10px; gap:4px; justify-content: flex-start; align-items:center;"><input type="radio" value="P" x-model="form.wt_val"> PPD <input type="radio" value="C" x-model="form.wt_val"> COLL</div></div>
                            </div>
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Other</label><div class="form-input-container" style="font-size:10px; gap:4px; justify-content: flex-start; align-items:center;"><input type="radio" value="P" x-model="form.other_term"> PPD <input type="radio" value="C" x-model="form.other_term"> COLL</div></div>
                            </div>
                        </div>

                        <div style="height: 15px;"></div>

                        <div style="display: flex; justify-content: flex-end; margin-top: 15px; margin-bottom: 5px; align-items: center; gap: 10px;">
                            <label class="form-label-gf" style="width:auto; margin:0;">Display Unit</label>
                            <select class="form-control-gf" style="width: 150px;" x-model="form.display_unit"><option value="BOTH">Show Both</option><option value="KG">KG Only</option><option value="LB">LB Only</option></select>
                        </div>

                        <hr style="margin: 15px 0; border-top: 1px solid #eee;">

                        <!-- MAWB Grids -->
                        <div style="margin-bottom: 15px;">
                            <h4 style="font-size: 11px; font-weight: 600; color: #4b77be; margin: 0 0 5px 0;">Other Charges</h4>
                            <table class="table-custom" style="width:100%; border-collapse:collapse; font-size:10px; border:1px solid #ddd;">
                                <thead>
                                    <tr style="background:#f9f9f9;">
                                        <th style="width: 30px; text-align: center; border:1px solid #ddd;"><input type="checkbox"></th>
                                        <th style="border:1px solid #ddd; padding:4px;">Charge Code</th>
                                        <th style="border:1px solid #ddd; padding:4px;">Description</th>
                                        <th style="border:1px solid #ddd; padding:4px; width:60px;">Term</th>
                                        <th style="border:1px solid #ddd; padding:4px; width:80px;">Rate</th>
                                        <th style="border:1px solid #ddd; padding:4px; width:100px;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(charge, idx) in form.other_charges" :key="idx">
                                        <tr>
                                            <td style="text-align: center; border:1px solid #ddd;"><i class="fa fa-trash" style="color:red; cursor:pointer;" @click="removeCharge(idx)"></i></td>
                                            <td style="border:1px solid #ddd; padding:4px;"><input type="text" class="form-control-gf" x-model="charge.charge_code" :name="'charges['+idx+'][charge_code]'"></td>
                                            <td style="border:1px solid #ddd; padding:4px;"><input type="text" class="form-control-gf" x-model="charge.description" :name="'charges['+idx+'][description]'"></td>
                                            <td style="border:1px solid #ddd; padding:4px;"><select class="form-control-gf" x-model="charge.term" :name="'charges['+idx+'][term]'"><option value="P">PPD</option><option value="C">COLL</option></select></td>
                                            <td style="border:1px solid #ddd; padding:4px;"><input type="number" step="any" class="form-control-gf" x-model="charge.rate" :name="'charges['+idx+'][rate]'"></td>
                                            <td style="border:1px solid #ddd; padding:4px;"><input type="number" step="any" class="form-control-gf" x-model="charge.amount" :name="'charges['+idx+'][amount]'"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="form.other_charges.length === 0"><td colspan="6" style="text-align: center; color: #999; padding: 10px;">No charges added.</td></tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn-default-gf" style="margin-top: 5px;" @click="addCharge()"><i class="fa fa-plus"></i> Add Charge</button>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <h4 style="font-size: 11px; font-weight: 600; color: #4b77be; margin: 0 0 5px 0;">Accounting Information</h4>
                            <table class="table-custom" style="width:100%; border-collapse:collapse; font-size:10px; border:1px solid #ddd;">
                                <thead>
                                    <tr style="background:#f9f9f9;">
                                        <th style="width: 30px; text-align: center; border:1px solid #ddd;"><input type="checkbox"></th>
                                        <th style="border:1px solid #ddd; padding:4px;">Information Code</th>
                                        <th style="border:1px solid #ddd; padding:4px;">Accounting Information</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(info, idx) in form.accounting_info" :key="idx">
                                        <tr>
                                            <td style="text-align: center; border:1px solid #ddd;"><i class="fa fa-trash" style="color:red; cursor:pointer;" @click="removeAcctInfo(idx)"></i></td>
                                            <td style="border:1px solid #ddd; padding:4px;"><input type="text" class="form-control-gf" x-model="info.code" :name="'accounting_info['+idx+'][code]'"></td>
                                            <td style="border:1px solid #ddd; padding:4px;"><input type="text" class="form-control-gf" x-model="info.info" :name="'accounting_info['+idx+'][info]'"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="form.accounting_info.length === 0"><td colspan="3" style="text-align: center; color: #999; padding: 10px;">No information added.</td></tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn-default-gf" style="margin-top: 5px;" @click="addAcctInfo()"><i class="fa fa-plus"></i> Add Info</button>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <h4 style="font-size: 11px; font-weight: 600; color: #4b77be; margin: 0 0 5px 0;">Commodity</h4>
                            <table class="table-custom" style="width:100%; border-collapse:collapse; font-size:10px; border:1px solid #ddd;">
                                <thead>
                                    <tr style="background:#f9f9f9;">
                                        <th style="width: 30px; text-align: center; border:1px solid #ddd;"><input type="checkbox"></th>
                                        <th style="border:1px solid #ddd; padding:4px;">Description</th>
                                        <th style="border:1px solid #ddd; padding:4px; width:100px;">HTS No.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(commodity, idx) in form.commodities" :key="idx">
                                        <tr>
                                            <td style="text-align: center; border:1px solid #ddd;"><i class="fa fa-trash" style="color:red; cursor:pointer;" @click="removeCommodity(idx)"></i></td>
                                            <td style="border:1px solid #ddd; padding:4px;"><input type="text" class="form-control-gf" x-model="commodity.description" :name="'commodities['+idx+'][description]'"></td>
                                            <td style="border:1px solid #ddd; padding:4px;"><input type="text" class="form-control-gf" x-model="commodity.hts_no" :name="'commodities['+idx+'][hts_no]'"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="form.commodities.length === 0"><td colspan="3" style="text-align: center; color: #999; padding: 10px;">No commodities added.</td></tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn-default-gf" style="margin-top: 5px;" @click="addCommodity()"><i class="fa fa-plus"></i> Add Commodity</button>
                        </div>

                        <div style="margin-top: 15px;">
                            <h4 style="font-size: 11px; font-weight: 600; color: #333; margin: 0 0 5px 0;">Internal Memo</h4>
                            <textarea class="form-control-gf" style="height: 60px; padding: 5px; resize: vertical;" x-model="form.memo"></textarea>
                        </div>
                    </div>
                </div>

                <!-- House B/L (HAWB) Section -->
                <template x-for="(hawb, index) in hawbs" :key="index">
                    <div class="portlet light" style="margin-top: 5px;">
                        <div class="portlet-title" style="background: #f2bc00; color: #fff; cursor: pointer; min-height: 24px; padding: 2px 10px;" @click="hawb.show = !hawb.show">
                            <span class="caption-subject" style="color: #fff; font-size: 11px;"><i class="fa fa-user"></i> HAWB Information <small style="color:rgba(255,255,255,0.8); margin-left: 10px; font-weight: normal;" x-text="'OP: ' + hawb.op"></small></span>
                            <div class="actions" style="display: flex; gap: 10px; align-items: center;">
                                <i @click.stop="removeHawb(index)" class="fa fa-times" style="font-size: 12px; opacity: 0.8; cursor: pointer;"></i>
                                <i class="fa fa-angle-down transition-transform" :class="hawb.show ? 'rotate-180' : ''" style="font-size: 12px;"></i>
                            </div>
                        </div>
                        <div class="portlet-body" x-show="hawb.show" style="background: #f9f9f9; padding: 10px;">
                            
                            <div class="memo-section" style="margin-bottom: 15px;">
                                <div class="memo-header" @click="hawb.showMemo = !hawb.showMemo" style="background:#fff; border:1px solid #eef1f5; padding:4px 10px; display:flex; justify-content:space-between; align-items:center; cursor:pointer;">
                                    <span style="font-size:11px; font-weight:600; color:#666;">Note</span>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <button class="btn-default-gf" style="background:#eee; border:1px solid #ccc; font-size:10px; padding:1px 8px;" @click.stop="">Document (0) <i class="fa fa-external-link"></i></button>
                                        <i class="fa" :class="hawb.showMemo ? 'fa-angle-up' : 'fa-angle-down'"></i>
                                    </div>
                                </div>
                                <div class="memo-body" x-show="hawb.showMemo" style="border:1px solid #eef1f5; border-top:none; background:#fff; padding:10px;">
                                    <div style="display: flex; gap: 10px;">
                                        <div style="flex: 2; border: 1px solid #dcdcdc; min-height: 40px; background: #fff; padding: 5px; color: #999; text-align: center;">No records found.</div>
                                        <div style="flex: 1;"><textarea class="form-control-gf" style="height: 60px; resize: none;" placeholder="Reminder content..."></textarea></div>
                                    </div>
                                </div>
                            </div>

                            <div style="padding: 5px 0;">
                                <input type="hidden" :name="'hbls[' + index + '][id]'" :value="hawb.id">
                                <div class="form-grid-4">
                                    <!-- Column 1 -->
                                    <div class="flex flex-col">
                                        <div class="form-group-gf"><label class="form-label-gf" style="color:red;">*HAWB No.</label><div class="form-input-container"><input type="text" :name="'hbls[' + index + '][hawb_no]'" class="form-control-gf" x-model="hawb.hawb_no" required></div></div>
                                        <div class="form-group-gf"><label class="form-label-gf">Booking No.</label><div class="form-input-container"><input type="text" :name="'hbls[' + index + '][booking_no]'" class="form-control-gf" x-model="hawb.booking_no"></div></div>
                                        <div class="form-group-gf"><label class="form-label-gf">Quotation No.</label><div class="form-input-container"><x-inline-select name="quotation_no" :options="$agents" module="trade-partner" x-model="hawb.quotation_no" class="form-control-gf" /></div></div>
                                        <div class="form-group-gf"><label class="form-label-gf">ITN No.</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="hawb.itn_no"></div></div>
                                    </div>
                                    
                                    <!-- Column 2 -->
                                    <div class="flex flex-col">
                                        <div class="form-group-gf"><label class="form-label-gf">Actual Shipper</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls[' + index + '][shipper_id]'" :options="$agents" module="trade-partner" x-model="hawb.shipper" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                        <div class="form-group-gf"><label class="form-label-gf">Customer</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls[' + index + '][customer_id]'" :options="$agents" module="trade-partner" x-model="hawb.customer" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                        <div class="form-group-gf"><label class="form-label-gf">Sales</label><div class="form-input-container">
                                            <select :name="'hbls[' + index + '][sales_person_id]'" x-model="hawb.sales" class="form-control-gf">
                                                <option value="">Select...</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div></div>
                                        <div class="form-group-gf"><label class="form-label-gf">OP</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="hawb.op" disabled style="background:#eee;"></div></div>
                                    </div>

                                    <!-- Column 3 -->
                                    <div class="flex flex-col">
                                        <div class="form-group-gf"><label class="form-label-gf">Bill To</label><div class="form-input-container"><x-inline-select name="bill_to" :options="$agents" module="trade-partner" x-model="hawb.bill_to" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                        <div class="form-group-gf"><label class="form-label-gf">Consignee</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls[' + index + '][consignee_id]'" :options="$agents" module="trade-partner" x-model="hawb.consignee" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                        <div class="form-group-gf"><label class="form-label-gf">Booking Date</label><div class="form-input-container"><input type="date" class="form-control-gf" x-model="hawb.booking_date"></div></div>
                                    </div>

                                    <!-- Column 4 -->
                                    <div class="flex flex-col">
                                        <div class="form-group-gf"><label class="form-label-gf">Notify</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls[' + index + '][notify_party_id]'" :options="$agents" module="trade-partner" x-model="hawb.notify" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                        <div class="form-group-gf"><label class="form-label-gf">Oversea Agent</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls[' + index + '][oversea_agent_id]'" :options="$agents" module="trade-partner" x-model="hawb.oversea_agent" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                    </div>
                                </div>
                            </div>

                            <hr style="margin: 10px 0; border-top: 1px solid #ddd;">

                            <div class="form-grid-4">
                                <div class="flex flex-col">
                                    <div class="form-group-gf"><label class="form-label-gf">Departure</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="hawb.departure"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Cargo Pickup</label><div class="form-input-container"><x-inline-select name="cargo_pickup" :options="$agents" module="trade-partner" x-model="hawb.cargo_pickup" class="form-control-gf" /></div></div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="form-group-gf"><label class="form-label-gf">Destination</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="hawb.destination"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Delivery To/Pier</label><div class="form-input-container"><x-inline-select name="delivery_to" :options="$agents" module="trade-partner" x-model="hawb.delivery_to" class="form-control-gf" /></div></div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="form-group-gf"><label class="form-label-gf">Final ETA</label><div class="form-input-container"><input type="date" class="form-control-gf" x-model="hawb.feta"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Sales Type</label><div class="form-input-container"><x-inline-select name="sales_type" :options="$agents" module="trade-partner" x-model="hawb.sales_type" class="form-control-gf" /></div></div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="form-group-gf"><label class="form-label-gf">Cargo Type</label><div class="form-input-container"><select class="form-control-gf" x-model="hawb.cargo_type"><option value="GENERAL CARGO">GENERAL CARGO</option></select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Ship Type</label><div class="form-input-container"><select class="form-control-gf" x-model="hawb.ship_type"><option value="NORMAL">NORMAL</option></select></div></div>
                                </div>
                            </div>

                            <hr style="margin: 10px 0; border-top: 1px solid #ddd;">
                            
                            <div class="form-grid-4">
                                <div class="flex flex-col">
                                    <div class="form-group-gf">
                                        <label class="form-label-gf">Package</label>
                                        <div class="form-input-container" style="gap:2px;">
                                            <input type="number" step="any" :name="'hbls[' + index + '][pkg_qty]'" class="form-control-gf" style="width:40%;" x-model="hawb.pkg_qty">
                                            <select :name="'hbls[' + index + '][pkg_unit_id]'" class="form-control-gf" style="width:60%;" x-model="hawb.pkg_unit_id">
                                                <option value="">Select...</option>
                                                @foreach($packageUnits as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group-gf"><label class="form-label-gf">Buying Rate</label><div class="form-input-container" style="gap:4px; align-items:center;"><input type="text" :name="'hbls[' + index + '][buying_rate]'" class="form-control-gf" style="flex:1;" x-model="hawb.buying_rate"> per <select class="form-control-gf" style="width:50px;"><option>KG</option></select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Selling Rate</label><div class="form-input-container" style="gap:4px; align-items:center;"><input type="text" :name="'hbls[' + index + '][selling_rate]'" class="form-control-gf" style="flex:1;" x-model="hawb.selling_rate"> per <select class="form-control-gf" style="width:50px;"><option>KG</option></select></div></div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="form-group-gf">
                                        <label class="form-label-gf">Gross Weight (SHPR)</label>
                                        <div class="form-input-container" style="gap:4px; align-items:center;">
                                            <input type="number" step="any" :name="'hbls[' + index + '][gross_weight]'" class="form-control-gf" style="flex:1;" x-model="hawb.gross_weight"> <span style="font-size:10px;">KG</span>
                                        </div>
                                    </div>
                                    <div class="form-group-gf"><label class="form-label-gf">Gross Weight (CNEE)</label><div class="form-input-container" style="gap:4px; align-items:center;"><input type="text" class="form-control-gf" style="flex:1;"> <span style="font-size:10px;">KG</span> <input type="text" class="form-control-gf" style="flex:1;"> <span style="font-size:10px;">LB</span></div></div>
                                    <div class="form-group-gf">
                                        <label class="form-label-gf">Chargeable Weight</label>
                                        <div class="form-input-container" style="gap:4px; align-items:center;">
                                            <input type="number" step="any" :name="'hbls[' + index + '][chargeable_weight]'" class="form-control-gf" style="flex:1;" x-model="hawb.chargeable_weight"> <span style="font-size:10px;">KG</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="form-group-gf"><label class="form-label-gf">Amount</label><div class="form-input-container"><input type="text" class="form-control-gf" style="text-align:right;"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Amount</label><div class="form-input-container"><input type="text" class="form-control-gf" style="text-align:right;"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Amount</label><div class="form-input-container"><input type="text" class="form-control-gf" style="text-align:right;"></div></div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="form-group-gf"><label class="form-label-gf">Volume Weight</label><div class="form-input-container" style="gap:4px; align-items:center;"><input type="number" step="any" :name="'hbls[' + index + '][volume]'" class="form-control-gf" style="flex:1;" x-model="hawb.volume"> <span style="font-size:10px;">CBM</span></div></div>
                                    <button type="button" class="btn-default-gf" style="margin-bottom:5px;">Set Dimensions</button>
                                    <button type="button" class="btn-default-gf">Sum Package & Weight</button>
                                </div>
                            </div>

                            <hr style="margin: 10px 0; border-top: 1px solid #ddd;">

                            <div style="display: flex; justify-content: flex-end; margin-top: 10px; align-items: center; gap: 10px;">
                                <label class="form-label-gf" style="width:auto; margin:0;">Display Unit</label>
                                <select class="form-control-gf" style="width: 150px;" x-model="hawb.display_unit"><option value="BOTH">Show Both</option><option value="KG">KG Only</option><option value="LB">LB Only</option></select>
                            </div>

                            <hr style="margin: 10px 0; border-top: 1px solid #ddd;">

                            <!-- Grid Lists for HAWB -->
                            <div style="margin-bottom: 10px;">
                                <h4 style="font-size: 11px; font-weight: 600; color: #333; margin: 0 0 5px 0;">Commodity / HTS No.</h4>
                                <table class="table-custom" style="width:100%; border-collapse:collapse; font-size:10px; border:1px solid #ddd;">
                                    <thead>
                                        <tr style="background:#f9f9f9;">
                                            <th style="width: 30px; text-align: center; border:1px solid #ddd;"><input type="checkbox"></th>
                                            <th style="border:1px solid #ddd; padding:4px;">Commodity Description</th>
                                            <th style="border:1px solid #ddd; padding:4px;">HTS No.</th>
                                            <th style="border:1px solid #ddd; padding:4px; width:100px;">P.O. No.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(commodity, cidx) in hawb.commodities" :key="cidx">
                                            <tr>
                                                <td style="text-align: center; border:1px solid #ddd;"><i class="fa fa-trash" style="color:red; cursor:pointer;" @click="removeHawbCommodity(index, cidx)"></i></td>
                                                <td style="border:1px solid #ddd; padding:4px;"><input type="text" class="form-control-gf" x-model="commodity.description" :name="'hbls['+index+'][commodities]['+cidx+'][description]'"></td>
                                                <td style="border:1px solid #ddd; padding:4px;"><input type="text" class="form-control-gf" x-model="commodity.hts_no" :name="'hbls['+index+'][commodities]['+cidx+'][hts_no]'"></td>
                                                <td style="border:1px solid #ddd; padding:4px;"><input type="text" class="form-control-gf" x-model="commodity.po_no" :name="'hbls['+index+'][commodities]['+cidx+'][po_no]'"></td>
                                            </tr>
                                        </template>
                                        <tr x-show="hawb.commodities.length === 0"><td colspan="4" style="text-align: center; color: #999; padding: 10px;">No commodities added.</td></tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn-default-gf" style="margin-top: 5px;" @click="addHawbCommodity(index)"><i class="fa fa-plus"></i> Add Row</button>
                            </div>

                            <hr style="margin: 10px 0; border-top: 1px solid #ddd;">

                            <div class="form-grid-4" style="grid-template-columns: repeat(2, 1fr);">
                                <div class="flex flex-col">
                                    <h4 style="font-size: 11px; font-weight: 600; margin: 0 0 5px 0; color: #333;">Mark</h4>
                                    <textarea class="form-control-gf" style="height: 60px !important; resize: vertical; padding:5px;" x-model="hawb.mark"></textarea>
                                </div>
                                <div class="flex flex-col">
                                    <h4 style="font-size: 11px; font-weight: 600; margin: 0 0 5px 0; color: #333;">Description</h4>
                                    <textarea class="form-control-gf" style="height: 60px !important; resize: vertical; padding:5px;" x-model="hawb.description"></textarea>
                                </div>
                            </div>
                            <div style="margin-top: 10px;">
                                <h4 style="font-size: 11px; font-weight: 600; margin: 0 0 5px 0; color: #333;">Remark</h4>
                                <textarea class="form-control-gf" style="height: 50px !important; resize: vertical; padding:5px;" x-model="hawb.remark"></textarea>
                            </div>
                        </div>
                    </div>
                </template>

                <div class="flex justify-end" style="margin-top: 5px; margin-bottom: 20px;">
                    <button @click="addHawb" class="btn-gofreight" style="background:#f2bc00; padding: 4px 15px; font-size: 11px; border-radius: 2px;"><i class="fa fa-plus"></i> ADD HAWB</button>
                </div>
            </div>

            <!-- ACCOUNTING TAB -->
            <div x-show="activeTab === 'accounting'" class="main-grid" x-cloak style="flex-direction: row; gap: 10px;">
                <!-- Main Accounting Area (col-10) -->
                <div style="flex: 5;">
                    <!-- MAWB Section -->
                    <div class="portlet light">
                        <div class="portlet-title" style="background: #666; color: #fff;">
                            <div class="caption">
                                <span style="font-size: 11px; margin-right: 5px;">MAWB</span>
                                <span class="caption-subject" style="color: #fff;" x-text="form.mawb_no"></span>
                            </div>
                            <div class="actions" style="display: flex; gap: 5px; align-items: center;">
                                <button class="btn-default-gf dark"><i class="fa fa-info"></i></button>
                                <button class="btn-default-gf dark"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div style="background: #eef1f5; padding: 5px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                <div style="display: flex; gap: 5px;">
                                    <button class="btn-gofreight" style="background: #32c5d2;"><i class="fa fa-plus"></i> Origin Revenue (Invoice/AR) <i class="fa fa-angle-down"></i></button>
                                    <button class="btn-gofreight" style="background: #32c5d2;"><i class="fa fa-plus"></i> Destination Revenue/Cost (D/C Note) <i class="fa fa-angle-down"></i></button>
                                    <button class="btn-gofreight" style="background: #32c5d2;"><i class="fa fa-plus"></i> Origin Cost (AP) <i class="fa fa-angle-down"></i></button>
                                </div>
                                <div>
                                    <label style="font-size: 10px; display: flex; align-items: center; gap: 4px; color: #666; margin: 0;">
                                        <input type="checkbox" disabled> Include Draft Amount
                                    </label>
                                </div>
                            </div>
                            
                            <table class="table-custom" style="width: 100%; border-collapse: collapse; font-size: 10px;">
                                <thead>
                                    <tr>
                                        <th style="width: 20px;"></th>
                                        <th style="width: 20px;"></th>
                                        <th>Invoice No.</th>
                                        <th>Party</th>
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
                                    <tr>
                                        <td colspan="4" style="text-align: right; font-weight: 700;">Total</td>
                                        <td style="text-align: right; color: #32c5d2; font-weight: 700;">0.00</td>
                                        <td style="text-align: right; color: #32c5d2; font-weight: 700;">0.00</td>
                                        <td style="text-align: right; color: #32c5d2; font-weight: 700;">0.00</td>
                                        <td colspan="5"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="text-align: right; font-weight: 700;">Amount</td>
                                        <td colspan="2" style="text-align: right; color: #32c5d2; font-weight: 700;">0.00</td>
                                        <td colspan="6"></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <table class="table-custom" style="width: 100%; border-collapse: collapse; font-size: 10px; margin-top: 5px;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th style="text-align: right; width: 15%;">Amount</th>
                                        <th style="text-align: right; width: 15%;">Profit Percentage</th>
                                        <th style="text-align: right; width: 15%;">Profit Margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: right;">Total Profit</td>
                                        <td style="text-align: right; color: #32c5d2; font-weight: 700;">0.00</td>
                                        <td style="text-align: right; color: #32c5d2; font-weight: 700;">N/A</td>
                                        <td style="text-align: right; color: #32c5d2; font-weight: 700;">N/A</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Memo Section under Accounting -->
                            <div class="memo-section" style="margin-bottom: 10px;">
                                <div class="memo-header" style="background:#f1f3f6; border-bottom:1px solid #dcdcdc;">
                                    <span style="color: #333;">Memo</span>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <button class="btn-default-gf" style="background:#fff; border:1px solid #ccc; font-size:10px; padding:2px 8px;">Document (0) <i class="fa fa-external-link"></i></button>
                                        <i class="fa fa-angle-up"></i>
                                    </div>
                                </div>
                                <div class="memo-body" style="padding: 0;">
                                    <div style="display: flex;">
                                        <div style="flex: 2; min-height: 80px; padding: 0;">
                                            <table class="table-custom" style="width:100%; border:none; margin:0;">
                                                <thead>
                                                    <tr style="background:#a0a8b3;">
                                                        <th style="width: 30px; text-align: center; color:#fff; border:none; background:#a0a8b3;"><i class="fa fa-plus" style="background:#32c5d2; padding:3px; border-radius:2px; cursor:pointer;"></i></th>
                                                        <th style="color:#fff; border:none; background:#a0a8b3;"><i class="fa fa-bell"></i> Subject</th>
                                                        <th style="color:#fff; border:none; background:#a0a8b3;">Last Modified</th>
                                                        <th style="color:#fff; border:none; background:#a0a8b3;">Created</th>
                                                        <th style="color:#fff; border:none; background:#a0a8b3;">Action / TP</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr><td colspan="5" style="background:#fff; border:none; min-height: 50px;"></td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div style="flex: 1; padding: 5px; border-left: 1px solid #eef1f5;">
                                            <textarea class="form-control-gf" style="height: 100%; resize: none; background:#eee;" readonly></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- HBL Sidebar (col-2) -->
                <div style="flex: 1; display: flex; flex-direction: column;">
                    <button class="btn-default-gf" style="width: 100%; padding: 6px; font-weight: 600; font-size: 11px; margin-bottom: 10px; justify-content: center;" @click="activeTab='basic'; showMblSection=false; addHawb()">+ Add HAWB</button>
                    <hr style="margin: 0 0 10px 0; border-top: 1px solid #ddd;">
                    <div style="background: #fff; border: 1px solid #e7ecf1; border-radius: 4px; padding: 10px; display: flex; flex-direction: column; gap: 5px; flex: 1; height: 100%;">
                        <template x-for="(hawb, index) in hawbs" :key="index">
                            <div style="background: #f1f3f6; border: 1px solid #dcdcdc; border-left: 3px solid #f2bc00; padding: 8px; border-radius: 2px; cursor: pointer;">
                                <div style="font-weight: 700; color: #4b77be; font-size: 11px;">HAWB No.</div>
                                <div style="font-size: 10px; color: #666; margin-top: 2px;" x-text="hawb.hawb_no || 'TBD'"></div>
                            </div>
                        </template>
                        <div x-show="hawbs.length === 0" style="text-align: center; color: #999; font-size: 10px; padding: 10px;">No HAWB created.</div>
                    </div>
                </div>
            </div>

            <!-- DOC CENTER TAB (Placeholder) -->
            <div x-show="activeTab === 'doc'" class="main-grid" x-cloak>
                <div class="portlet light">
                    <div class="portlet-title"><span class="caption-subject">Doc Center</span></div>
                    <div class="portlet-body">
                        <div style="text-align: center; color: #999; padding: 40px;">Doc Center module will be loaded here...</div>
                    </div>
                </div>
            </div>

            <!-- WORK ORDER TAB -->
            <div x-show="activeTab === 'workorder'" class="main-grid" x-cloak style="flex-direction: row; gap: 10px;">
                <!-- Main Work Order Area (col-10) -->
                <div style="flex: 5;">
                    <div class="portlet light">
                        <div class="portlet-title" style="background: #666; color: #fff;">
                            <div class="caption">
                                <span style="font-size: 11px; margin-right: 5px;">MAWB</span>
                            </div>
                            <div class="actions" style="display: flex; gap: 5px; align-items: center;">
                                <button class="btn-default-gf dark"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                                <i class="fa fa-angle-down" style="cursor: pointer; padding: 0 5px;"></i>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 10px;">
                            <div style="background: #eef1f5; padding: 5px; margin-bottom: 5px; display: flex;">
                                <div class="btn-group" style="display: flex; gap: 2px;">
                                    <button class="btn-gofreight" style="background: #32c5d2; padding: 4px 8px; border-radius: 2px;"><i class="fa fa-plus"></i></button>
                                    <button class="btn-default-gf dark" style="background: #fff; border: 1px solid #ccc; padding: 4px 8px; border-radius: 2px; color: #999;" disabled><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                            
                            <table class="table-custom" style="width: 100%; border-collapse: collapse; font-size: 10px;">
                                <thead>
                                    <tr style="background: #a0a8b3; color: #fff;">
                                        <th style="width: 30px; text-align: center; border: 1px solid #e7ecf1; background: #a0a8b3; color: #fff;">
                                            <input type="checkbox" disabled>
                                        </th>
                                        <th style="text-align: center; border: 1px solid #e7ecf1; background: #a0a8b3; color: #fff;">No.</th>
                                        <th style="text-align: center; border: 1px solid #e7ecf1; background: #a0a8b3; color: #fff;">D/O Type</th>
                                        <th style="border: 1px solid #e7ecf1; background: #a0a8b3; color: #fff;">Freight Pickup</th>
                                        <th style="border: 1px solid #e7ecf1; background: #a0a8b3; color: #fff;">Delivery</th>
                                        <th style="border: 1px solid #e7ecf1; background: #a0a8b3; color: #fff;">Trucker</th>
                                        <th style="text-align: center; border: 1px solid #e7ecf1; background: #a0a8b3; color: #fff;">Last Modified</th>
                                        <th style="text-align: center; border: 1px solid #e7ecf1; background: #a0a8b3; color: #fff;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="8" style="text-align: center; color: #999; padding: 20px;">No records found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- HBL Sidebar (col-2) -->
                <div style="flex: 1; display: flex; flex-direction: column;">
                    <button class="btn-default-gf" style="width: 100%; padding: 6px; font-weight: 600; font-size: 11px; margin-bottom: 10px; justify-content: center;" @click="activeTab='basic'; showMblSection=false; addHawb()">+ Add HAWB</button>
                    <hr style="margin: 0 0 10px 0; border-top: 1px solid #ddd;">
                    <div style="background: #fff; border: 1px solid #e7ecf1; border-radius: 4px; padding: 10px; display: flex; flex-direction: column; gap: 5px; flex: 1; height: 100%;">
                        <template x-for="(hawb, index) in hawbs" :key="index">
                            <div style="background: #f1f3f6; border: 1px solid #dcdcdc; border-left: 3px solid #f2bc00; padding: 8px; border-radius: 2px; cursor: pointer;">
                                <div style="font-weight: 700; color: #4b77be; font-size: 11px;">HAWB No.</div>
                                <div style="font-size: 10px; color: #666; margin-top: 2px;" x-text="hawb.hawb_no || 'TBD'"></div>
                            </div>
                        </template>
                        <div x-show="hawbs.length === 0" style="text-align: center; color: #999; font-size: 10px; padding: 10px;">No HAWB created.</div>
                    </div>
                </div>
            </div>

            <!-- STATUS TAB -->
            <div x-show="activeTab === 'status'" class="main-grid" x-cloak style="flex-direction: row; gap: 10px;">
                <!-- Main Status Area (col-10) -->
                <div style="flex: 5;">
                    <div class="portlet light">
                        <div class="portlet-title" style="background: #666; color: #fff;">
                            <div class="caption">
                                <span style="font-size: 11px; margin-right: 5px;">MAWB</span>
                            </div>
                            <div class="actions" style="display: flex; gap: 5px; align-items: center;">
                                <button class="btn-default-gf dark"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                                <i class="fa fa-angle-down" style="cursor: pointer; padding: 0 5px;"></i>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 20px;">
                            <div style="display: flex; gap: 20px; margin-bottom: 30px;">
                                <div style="flex: 1;">
                                    <h4 style="font-size: 13px; font-weight: 700; color: #333; margin: 0 0 10px 0;">Role</h4>
                                    <div style="display: flex; align-items: center; gap: 10px; font-size: 11px; color: #333;">
                                        <span>OP :</span>
                                        <div style="width: 20px; height: 20px; border-radius: 50% !important; background: #3598dc; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600;">D</div>
                                        <select class="form-control-gf" style="width: 200px;">
                                            <option>DEMO_925 (DEMO_925)</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="flex: 2;">
                                    <h4 style="font-size: 13px; font-weight: 700; color: #333; margin: 0 0 10px 0;">Internal Message</h4>
                                    <textarea class="form-control-gf" style="width: 100%; height: 55px; resize: none;"></textarea>
                                </div>
                            </div>

                            <h4 style="font-size: 13px; font-weight: 700; color: #333; margin: 0 0 15px 0;">Change Log</h4>
                            <div style="position: relative; padding-left: 100px;">
                                <!-- Timeline Line -->
                                <div style="position: absolute; left: 135px; top: 0; bottom: 0; width: 4px; background: #e5e5e5;"></div>

                                <!-- Log Item 1 -->
                                <div style="position: relative; margin-bottom: 20px;">
                                    <div style="position: absolute; left: -100px; width: 80px; text-align: right; color: #999; font-size: 10px;">
                                        <div>05-15-2026</div>
                                        <div>03:01</div>
                                    </div>
                                    <div style="position: absolute; left: 21px; top: 0; width: 32px; height: 32px; border-radius: 50% !important; background: #88939b; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600; z-index: 2; border: 4px solid #fff;">
                                        D
                                    </div>
                                    <div style="margin-left: 70px; background: #f5f6fa; border-radius: 2px; padding: 10px; position: relative;">
                                        <div style="position: absolute; left: -6px; top: 12px; width: 0; height: 0; border-top: 6px solid transparent; border-bottom: 6px solid transparent; border-right: 6px solid #f5f6fa;"></div>
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                            <div>
                                                <div style="font-size: 12px; font-weight: 600; color: #333;">Other Charges Updated</div>
                                                <div style="font-size: 10px; color: #999; margin-top: 5px; font-style: italic;">DEMO_925 (DEMO_925)</div>
                                            </div>
                                            <button style="background: #3598dc; color: #fff; border: none; padding: 3px 8px; font-size: 10px; border-radius: 2px; cursor: pointer;">
                                                More Detail <i class="fa fa-arrow-circle-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Log Item 2 -->
                                <div style="position: relative; margin-bottom: 20px;">
                                    <div style="position: absolute; left: -100px; width: 80px; text-align: right; color: #999; font-size: 10px;">
                                        <div>05-15-2026</div>
                                        <div>03:01</div>
                                    </div>
                                    <div style="position: absolute; left: 21px; top: 0; width: 32px; height: 32px; border-radius: 50% !important; background: #88939b; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600; z-index: 2; border: 4px solid #fff;">
                                        D
                                    </div>
                                    <div style="margin-left: 70px; background: #f5f6fa; border-radius: 2px; padding: 10px; position: relative;">
                                        <div style="position: absolute; left: -6px; top: 12px; width: 0; height: 0; border-top: 6px solid transparent; border-bottom: 6px solid transparent; border-right: 6px solid #f5f6fa;"></div>
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                            <div>
                                                <div style="font-size: 12px; font-weight: 600; color: #333;">Other Charges Updated</div>
                                                <div style="font-size: 10px; color: #999; margin-top: 5px; font-style: italic;">DEMO_925 (DEMO_925)</div>
                                            </div>
                                            <button style="background: #3598dc; color: #fff; border: none; padding: 3px 8px; font-size: 10px; border-radius: 2px; cursor: pointer;">
                                                More Detail <i class="fa fa-arrow-circle-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Log Item 3 -->
                                <div style="position: relative; margin-bottom: 20px;">
                                    <div style="position: absolute; left: -100px; width: 80px; text-align: right; color: #999; font-size: 10px;">
                                        <div>05-15-2026</div>
                                        <div>03:01</div>
                                    </div>
                                    <div style="position: absolute; left: 21px; top: 0; width: 32px; height: 32px; border-radius: 50% !important; background: #88939b; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 600; z-index: 2; border: 4px solid #fff;">
                                        D
                                    </div>
                                    <div style="margin-left: 70px; background: #f5f6fa; border-radius: 2px; padding: 10px; position: relative;">
                                        <div style="position: absolute; left: -6px; top: 12px; width: 0; height: 0; border-top: 6px solid transparent; border-bottom: 6px solid transparent; border-right: 6px solid #f5f6fa;"></div>
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                            <div>
                                                <div style="font-size: 12px; font-weight: 600; color: #333;">Master B/L Created</div>
                                                <div style="font-size: 10px; color: #999; margin-top: 5px; font-style: italic;">DEMO_925 (DEMO_925)</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HBL Sidebar (col-2) -->
                <div style="flex: 1; display: flex; flex-direction: column;">
                    <button class="btn-default-gf" style="width: 100%; padding: 6px; font-weight: 600; font-size: 11px; margin-bottom: 10px; justify-content: center;" @click="activeTab='basic'; showMblSection=false; addHawb()">+ Add HAWB</button>
                    <hr style="margin: 0 0 10px 0; border-top: 1px solid #ddd;">
                    <div style="background: #fff; border: 1px solid #e7ecf1; border-radius: 4px; padding: 10px; display: flex; flex-direction: column; gap: 5px; flex: 1; height: 100%;">
                        <template x-for="(hawb, index) in hawbs" :key="index">
                            <div style="background: #f1f3f6; border: 1px solid #dcdcdc; border-left: 3px solid #f2bc00; padding: 8px; border-radius: 2px; cursor: pointer;">
                                <div style="font-weight: 700; color: #4b77be; font-size: 11px;">HAWB No.</div>
                                <div style="font-size: 10px; color: #666; margin-top: 2px;" x-text="hawb.hawb_no || 'TBD'"></div>
                            </div>
                        </template>
                        <div x-show="hawbs.length === 0" style="text-align: center; color: #999; font-size: 10px; padding: 10px;">No HAWB created.</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- QUOTE MODAL -->
        <template x-teleport="body">
            <div x-show="showQuoteModal" class="modal-overlay" style="z-index: 999999;" x-cloak>
                <div class="modal-container" style="width: 900px; max-width: 95vw;">
                    <div style="padding: 15px; border-bottom: 1px solid #e5e5e5; display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0; font-size: 18px; color: #333; font-weight: 500;">Load Quotation Data</h4>
                        <button type="button" @click="closeQuoteModal()" style="background: none; border: none; font-size: 21px; cursor: pointer; color: #000; opacity: .2;">&times;</button>
                    </div>
                
                <div class="modal-body" style="padding: 20px;">
                    <style>
                        .wizard-circle { width: 18px; height: 18px; min-width: 18px; min-height: 18px; flex-shrink: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 10px; font-weight: bold; }
                    </style>
                    <!-- Wizard Steps Header -->
                    <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 15px;">
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
                                <template x-if="quoteStep <= 2"><span>2</span></template>
                            </div>
                            <span :style="quoteStep >= 2 ? 'color: #1e293b; font-size: 10px; font-weight: 600;' : 'color: #94a3b8; font-size: 10px;'">Fill in shipment data</span>
                        </div>
                        <div style="height: 1px; width: 20px; background: #e2e8f0;"></div>

                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div class="wizard-circle" :style="quoteStep >= 3 ? 'background: #3b82f6;' : 'background: #cbd5e1;'">
                                <span>3</span>
                            </div>
                            <span :style="quoteStep >= 3 ? 'color: #1e293b; font-size: 10px; font-weight: 600;' : 'color: #94a3b8; font-size: 10px;'">Select invoice items</span>
                        </div>
                    </div>

                    <!-- Step 1 Content -->
                    <div x-show="quoteStep === 1">
                        <div class="row" style="display: flex; flex-wrap: wrap; margin: -5px;">
                            @php
                                $agents = \App\Models\TradePartner::orderBy('name')->get();
                                $ports = \App\Models\Port::orderBy('name')->get();
                                $users = \App\Models\User::orderBy('name')->get();
                            @endphp
                            <div style="width: 33.33%; padding: 5px;">
                                <div class="form-group-gf" style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                                    <label class="form-label-gf" style="text-align: left; width: auto;">Customer</label>
                                    <x-inline-select name="customer" :options="$agents" module="trade-partner" x-model="filters.customer" class="form-control-gf" />
                                </div>
                            </div>
                            <div style="width: 33.33%; padding: 5px;">
                                <div class="form-group-gf" style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                                    <label class="form-label-gf" style="text-align: left; width: auto;">Valid Date</label>
                                    <div style="display: flex; width: 100%;">
                                        <input type="date" class="form-control-gf" style="height: 24px;" x-model="filters.valid_date">
                                    </div>
                                </div>
                            </div>
                            <div style="width: 33.33%; padding: 5px;">
                                <div class="form-group-gf" style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                                    <label class="form-label-gf" style="text-align: left; width: auto;">Commodity</label>
                                    <input type="text" class="form-control-gf" style="height: 24px;" x-model="filters.commodity">
                                </div>
                            </div>
                            <div style="width: 33.33%; padding: 5px;">
                                <div class="form-group-gf" style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                                    <label class="form-label-gf" style="text-align: left; width: auto;">Departure</label>
                                    <x-inline-select name="pol" :options="$agents" module="trade-partner" x-model="filters.pol" class="form-control-gf" />
                                </div>
                            </div>
                            <div style="width: 33.33%; padding: 5px;">
                                <div class="form-group-gf" style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                                    <label class="form-label-gf" style="text-align: left; width: auto;">Destination</label>
                                    <select class="form-control-gf" style="height: 24px;" x-model="filters.pod">
                                        <option value="">Select...</option>
                                        @foreach($ports as $port)
                                            <option value="{{ $port->id }}">{{ $port->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div style="width: 33.33%; padding: 5px;">
                                <div class="form-group-gf" style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                                    <label class="form-label-gf" style="text-align: left; width: auto;">Sales</label>
                                    <select class="form-control-gf" style="height: 24px;" x-model="filters.sales">
                                        <option value="">Select...</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div style="width: 33.33%; padding: 5px;">
                                <div class="form-group-gf" style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                                    <label class="form-label-gf" style="text-align: left; width: auto;">Quote No.</label>
                                    <input type="text" class="form-control-gf" style="height: 24px;" x-model="filters.quote_no">
                                </div>
                            </div>
                            <div style="width: 33.33%; padding: 5px;">
                                <div class="form-group-gf" style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                                    <label class="form-label-gf" style="text-align: left; width: auto;">Status</label>
                                    <select class="form-control-gf" style="height: 24px;" x-model="filters.status">
                                        <option value="">Select...</option>
                                        <option value="Won">Won</option>
                                        <option value="Draft">Draft</option>
                                        <option value="Expired">Expired</option>
                                    </select>
                                </div>
                            </div>
                            <div style="width: 33.33%; padding: 5px;">
                                <div class="form-group-gf" style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                                    <label class="form-label-gf" style="text-align: left; width: auto;">OP</label>
                                    <select class="form-control-gf" style="height: 24px;" x-model="filters.op">
                                        <option value="">Select...</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div style="text-align: center; margin-top: 15px;">
                            <button type="button" class="btn-default-gf" @click="clearSearch()" style="padding: 6px 12px; font-size: 12px; border-radius: 4px;">Clear</button>
                            <button type="button" class="btn-gofreight" @click="applySearch()" style="padding: 6px 12px; font-size: 12px; border-radius: 4px;">Search</button>
                        </div>
                        
                        <hr style="margin: 20px 0; border-top: 1px solid #eee;">
                        
                        <div style="text-align: right; margin-bottom: 5px;">
                            <button type="button" class="btn-gofreight" style="background: #67809f; padding: 2px 8px; border-radius: 12px !important;"><i class="fa fa-cogs"></i> Config</button>
                        </div>
                        
                        <div style="border: 1px solid #e7ecf1; height: 310px; overflow-y: auto; display: flex; flex-direction: column;">
                            <table class="table-custom" style="margin: 0; border: none;">
                                <thead>
                                    <tr>
                                        <th style="width: 50px; text-align: center; background: #888; color: #fff;">Select</th>
                                        <th style="background: #888; color: #fff;">Quote No.</th>
                                        <th style="background: #888; color: #fff;">Valid Date</th>
                                        <th style="background: #888; color: #fff;">Status</th>
                                        <th style="background: #888; color: #fff;">Creation Date</th>
                                        <th style="background: #888; color: #fff;">Commodity</th>
                                        <th style="background: #888; color: #fff;">Departure</th>
                                        <th style="background: #888; color: #fff;">Destination</th>
                                        <th style="background: #888; color: #fff;">Carrier</th>
                                        <th style="background: #888; color: #fff;">Sales</th>
                                        <th style="background: #888; color: #fff;">OP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $quotations = \App\Models\Quotation::with(['customer', 'salesPerson', 'pol', 'pod'])->latest()->get();
                                    @endphp
                                    @foreach($quotations as $quote)
                                    <tr x-show="matchFilters({quote_no: '{{ $quote->quote_no }}', customer_id: '{{ $quote->customer_id }}', pol_id: '{{ $quote->pol_id }}', pod_id: '{{ $quote->pod_id }}', status: '{{ $quote->status }}', sales_person_id: '{{ $quote->sales_person_id }}'})" style="border-bottom: 1px solid #e7ecf1;">
                                        <td style="text-align: center; padding: 6px;"><input type="radio" name="quote_sel" :checked="selectedQuote && selectedQuote.quote_no === '{{ $quote->quote_no }}'" 
                                             @click="selectQuote({
    quote_no: '{{ $quote->quote_no }}',
    mawb_no: 'MAWB-{{ $quote->quote_no }}',
    hawb_no: 'HAWB-{{ $quote->quote_no }}',
    eta: '{{ $quote->expiry_date ? $quote->expiry_date->format('Y-m-d') : '' }}',
    etd: '{{ $quote->quote_date ? $quote->quote_date->format('Y-m-d') : '' }}',
    customer: '{{ addslashes($quote->customer->name ?? '') }}',
    customer_id: '{{ $quote->customer_id }}',
    sales: '{{ addslashes($quote->salesPerson->name ?? '') }}',
    sales_person_id: '{{ $quote->sales_person_id }}',
    op_id: '{{ $quote->op_id }}',
    pol_name: '{{ addslashes($quote->pol->name ?? '') }}',
    pod_name: '{{ addslashes($quote->pod->name ?? '') }}',
    pol_id: '{{ $quote->pol_id }}',
    pod_id: '{{ $quote->pod_id }}',
    carrier_name: '',
    oversea_agent_id: '{{ $quote->agent_id }}',
    service_term: '{{ addslashes($quote->service_term ?? '') }}',
    incoterms_id: '{{ $quote->incoterms_id }}',
    commodity: '{{ addslashes($quote->commodity ?? '') }}',
    gross_weight_kg: '{{ $quote->weight_kg ?? '' }}',
    gross_weight_lb: '{{ $quote->weight_lb ?? '' }}',
    volume_cbm: '{{ $quote->volume_cbm ?? '' }}',
    ship_mode: '{{ addslashes($quote->ship_mode ?? '') }}',
    items: (quoteItems && quoteItems['{{ $quote->quote_no }}']) ? quoteItems['{{ $quote->quote_no }}'].map(i => ({...i, selected: true})) : []
})"></td>
                                        <td style="padding: 6px;"><a href="#" style="color: #337ab7; text-decoration: none;">{{ $quote->quote_no }}</a></td>
                                        <td style="padding: 6px;">{{ $quote->quote_date ? $quote->quote_date->format('m-d-Y') : '' }} ~ {{ $quote->expiry_date ? $quote->expiry_date->format('m-d-Y') : '' }}</td>
                                        <td style="padding: 6px;"><span style="background: {{ $quote->status === 'ACCEPTED' ? '#26c281' : '#888' }}; color: #fff; padding: 2px 5px; border-radius: 2px; font-size: 10px;">{{ $quote->status }}</span></td>
                                        <td style="padding: 6px;">{{ $quote->created_at->format('Y-m-d') }}</td>
                                        <td style="padding: 6px;">{{ $quote->commodity ?? '' }}</td>
                                        <td style="padding: 6px;">{{ $quote->pol->name ?? '' }}</td>
                                        <td style="padding: 6px;">{{ $quote->pod->name ?? '' }}</td>
                                        <td style="padding: 6px;"></td>
                                        <td style="padding: 6px;">{{ $quote->salesPerson->name ?? '' }}</td>
                                        <td style="padding: 6px;">{{ $quote->op->name ?? '' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Step 2 Content -->
                    <div x-show="quoteStep === 2" x-cloak>
                        <h5 style="font-size: 13px; font-weight: 600; color: #333; margin: 0 0 10px 0; border-bottom: 1px solid #eee; padding-bottom: 5px;">Route Information</h5>
                        <table class="table-custom" style="margin-bottom: 20px;">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Select</th>
                                    <th>Departure</th>
                                    <th>Destination</th>
                                    <th>Final Destination</th>
                                    <th>Carrier</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background: #fffdf2;" x-show="selectedQuote" x-cloak>
                                    <td style="text-align: center;"><input type="radio" checked></td>
                                    <td x-text="selectedQuote ? selectedQuote.pol_name : ''"></td>
                                    <td x-text="selectedQuote ? selectedQuote.pod_name : ''"></td>
                                    <td x-text="selectedQuote ? selectedQuote.pod_name : ''"></td>
                                    <td x-text="selectedQuote ? selectedQuote.carrier_name : 'DEMO CARRIER'"></td>
                                </tr>
                                <tr x-show="!selectedQuote">
                                    <td colspan="5" style="text-align: center; color: #999; padding: 10px;">No route information in this quotation</td>
                                </tr>
                            </tbody>
                        </table>

                        <h5 style="font-size: 13px; font-weight: 600; color: #333; margin: 0 0 10px 0; border-bottom: 1px solid #eee; padding-bottom: 5px;">Fill in the Shipment Information</h5>
                        <table class="table-custom">
                            <tbody>
                                <tr>
                                    <td style="background: #f9fafb; font-weight: 600; width: 15%;">MAWB No.</td>
                                    <td style="width: 35%;">
                                        <div style="display: flex; gap: 5px;">
                                            <input type="text" class="form-control-gf" x-model="quoteForm.mawb_no" style="height: 24px; flex: 1;">
                                            <button class="btn-gofreight"><i class="fa fa-external-link-square"></i></button>
                                            <button class="btn-gofreight" disabled><i class="fa fa-magic"></i></button>
                                        </div>
                                    </td>
                                    <td style="background: #f9fafb; font-weight: 600; width: 15%;"><span style="color: red;">*</span>HAWB No.</td>
                                    <td style="width: 35%;">
                                        <div style="display: flex; align-items: center; border: 1px solid #ccc;">
                                            <div style="padding: 0 5px; border-right: 1px solid #ccc; background: #eee;"><input type="checkbox" checked></div>
                                            <input type="text" class="form-control-gf" x-model="quoteForm.hawb_no" style="border: none; height: 22px; width: 100%;">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background: #f9fafb; font-weight: 600;"><span style="color: red;">*</span>Departure Date/Time</td>
                                    <td>
                                        <div style="display: flex; width: 100%;">
                                            <input type="date" class="form-control-gf" x-model="quoteForm.etd" style="height: 24px; border-right: none;">
                                            <div style="background: #eee; border: 1px solid #ccc; padding: 0 8px; display: flex; align-items: center; color: #666;"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </td>
                                    <td style="background: #f9fafb; font-weight: 600;">Arrival Date/Time</td>
                                    <td>
                                        <div style="display: flex; width: 100%;">
                                            <input type="date" class="form-control-gf" x-model="quoteForm.eta" style="height: 24px; border-right: none;">
                                            <div style="background: #eee; border: 1px solid #ccc; padding: 0 8px; display: flex; align-items: center; color: #666;"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background: #f9fafb; font-weight: 600;"><span style="color: red;">*</span>Customer</td>
                                    <td><input type="text" class="form-control-gf" x-model="quoteForm.customer" style="height: 24px;" readonly style="background-color: #fff;"></td>
                                    <td style="background: #f9fafb; font-weight: 600;">Service Term</td>
                                    <td x-text="quoteForm.service_term || '-'"></td>
                                </tr>
                                <tr>
                                    <td style="background: #f9fafb; font-weight: 600;">Oversea Agent</td>
                                    <td x-text="quoteForm.oversea_agent_id ? 'Has Agent' : '-'"></td>
                                    <td style="background: #f9fafb; font-weight: 600;">Incoterms</td>
                                    <td x-text="quoteForm.incoterms_id ? 'Has Incoterm' : '-'"></td>
                                </tr>
                                <tr>
                                    <td style="background: #f9fafb; font-weight: 600;">Gross Weight</td>
                                    <td><span x-text="quoteForm.gross_weight_kg || '0.00'"></span> KG</td>
                                    <td style="background: #f9fafb; font-weight: 600;">Volume Weight</td>
                                    <td><span x-text="quoteForm.volume_cbm || '0.00'"></span> CBM</td>
                                </tr>
                                <tr>
                                    <td style="background: #f9fafb; font-weight: 600;">Chargeable Weight</td>
                                    <td>-</td>
                                    <td style="background: #f9fafb; font-weight: 600;">Sales</td>
                                    <td x-text="quoteForm.sales || '-'"></td>
                                </tr>
                                <tr>
                                    <td style="background: #f9fafb; font-weight: 600;">OP</td>
                                    <td colspan="3" x-text="quoteForm.op_id ? 'Has OP' : '-'"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Step 3 Content -->
                    <div x-show="quoteStep === 3" x-cloak>
                        <h5 style="font-size: 13px; font-weight: 600; color: #333; margin: 0 0 10px 0; border-bottom: 1px solid #eee; padding-bottom: 5px;">Select Freight Item(s)</h5>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                            <label style="display: flex; align-items: center; gap: 5px; font-size: 11px;">
                                <input type="checkbox"> Save as a draft invoice
                            </label>
                            <div style="display: flex; align-items: center; gap: 5px; font-size: 11px;">
                                <span>Applied Unit</span> <i class="fa fa-info-circle" style="color: #4b77be;"></i>
                                <select class="form-control-gf" style="width: 100px; height: 22px;"></select>
                            </div>
                        </div>

                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th style="width: 70px;"><input type="checkbox" disabled> Select</th>
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
                                <template x-if="selectedQuote && selectedQuote.items && selectedQuote.items.length > 0">
                                    <template x-for="(item, index) in selectedQuote.items" :key="index">
                                        <tr style="border-bottom: 1px solid #f1f3f6;">
                                            <td style="text-align: center; padding: 6px;"><input type="checkbox" x-model="item.selected"></td>
                                            <td style="padding: 6px;" x-text="item.charge_code || '-'"></td>
                                            <td style="padding: 6px;" x-text="item.charge_name || '-'"></td>
                                            <td style="padding: 6px;" x-text="item.unit || '-'"></td>
                                            <td style="padding: 6px;" x-text="item.currency ? item.currency.code : 'USD'"></td>
                                            <td style="padding: 6px;" x-text="item.qty || '1'"></td>
                                            <td style="padding: 6px; text-align: right;" x-text="Number(item.rate || 0).toFixed(2)"></td>
                                            <td style="padding: 6px; text-align: right; color: #4b77be;" x-text="Number(item.amount || 0).toFixed(2)"></td>
                                        </tr>
                                    </template>
                                </template>
                                <tr x-show="!selectedQuote || !selectedQuote.items || selectedQuote.items.length === 0">
                                    <td colspan="8" style="text-align: center; color: #999; padding: 10px;">No charge items in this quotation</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer" style="padding: 15px; border-top: 1px solid #e5e5e5; display: flex; justify-content: flex-end; gap: 10px; background: #f9fafb; border-radius: 0 0 4px 4px;">
                    <button type="button" class="btn-default-gf" style="padding: 6px 12px; font-size: 12px; border-radius: 4px;" @click="closeQuoteModal()">Cancel</button>
                    <button type="button" class="btn-gofreight" :disabled="(quoteStep === 1 && !selectedQuote) || (quoteStep === 2 && (!quoteForm.mawb_no || !quoteForm.hawb_no || !quoteForm.customer || !quoteForm.etd))" :style="((quoteStep === 1 && !selectedQuote) || (quoteStep === 2 && (!quoteForm.mawb_no || !quoteForm.hawb_no || !quoteForm.customer || !quoteForm.etd))) ? 'background: #ccc; border: none; color: #666; cursor: not-allowed; opacity: 0.7; padding: 6px 12px; font-size: 12px; border-radius: 4px;' : 'background: #1abc9c; padding: 6px 12px; font-size: 12px; border-radius: 4px;'" x-show="quoteStep < 3" @click="quoteStep++">Next</button>
                    <button type="button" class="btn-gofreight" style="background: #1abc9c; padding: 6px 12px; font-size: 12px; border-radius: 4px;" x-show="quoteStep === 3" x-cloak @click="confirmQuoteSelection()">Confirm</button>
                </div>
                </div>
            </div>
        </template>
    </div>
</x-layout>