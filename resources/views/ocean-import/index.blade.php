<x-layout>
    @push('styles')
    <x-form-styles />
    @endpush

    <form action="{{ isset($oceanImport) ? route('ocean-import.update', $oceanImport->id) : route('ocean-import.store') }}" method="POST">
        @csrf
        @if(isset($oceanImport)) @method('PUT') @endif

        @if(session('success'))
            <div class="alert alert-success" style="background:#e8f5e9;border:1px solid #66bb6a;color:#2e7d32;padding:10px 15px;border-radius:4px;margin-bottom:15px;display:flex;align-items:center;gap:8px;">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" style="background:#fce4e4;border:1px solid #e57373;color:#c62828;padding:10px 15px;border-radius:4px;margin-bottom:15px;display:flex;align-items:center;gap:8px;">
                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" style="background:#fce4e4;border:1px solid #e57373;color:#c62828;padding:10px 15px;border-radius:4px;margin-bottom:15px;">
                <strong>Validation Error</strong>
                <ul style="margin:5px 0 0 15px;padding:0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    <script>
        function oceanImportModule() {
            return {
                saved: @json(isset($oceanImport) ? true : false),
                isSaving: false,
                saveError: '',
                activeTab: sessionStorage.getItem('oceanImportActiveTab') || 'basic',
                activeChargeFilter: 'All',
                showMblSection: true,
                showMblMemo: false,
                isDirectMaster: {{ (isset($oceanImport) && $oceanImport->is_direct_master) ? 'true' : 'false' }},
                showMore: false,
                showClipboardModal: false,
                showQuoteModal: '{{ $page ?? "" }}' === 'create-quote',
                showQuoteConfig: false,
                colVisibility: {
                    select: true,
                    quote_no: true,
                    valid_date: true,
                    status: true,
                    creation_date: true,
                    commodity: true,
                    pol: true,
                    pod: true,
                    carrier: true,
                    sales: true
                },
                inputTotalMode: false,
                saveAsDraftInvoice: false,
                showDocumentModal: false,
                showWrModal: false,
                activeHblForReceipts: null,
                wrSearchQuery: '',
                wrSearchResults: [],
                quoteStep: 1,
                selectedQuote: null,
                selectedMemoIndex: null,
                selectQuote(data) {
                    this.selectedQuote = { ...data, items: this.quoteItems[data.quote_no] || [] };
                    this.quoteForm.quote_no = data.quote_no || '';
                    this.quoteForm.mbl_no = data.mbl_no;
                    this.quoteForm.hbl_no = data.hbl_no;
                    this.quoteForm.eta = data.eta;
                    this.quoteForm.etd = data.etd;
                    this.quoteForm.customer = data.customer;
                    this.quoteForm.customer_id = data.customer_id || '';
                    this.quoteForm.sales = data.sales;
                    this.quoteForm.sales_person_id = data.sales_person_id || '';
                    this.quoteForm.pol_id = data.pol_id || '';
                    this.quoteForm.pod_id = data.pod_id || '';
                    this.quoteForm.pol_name = data.pol_name || '';
                    this.quoteForm.pod_name = data.pod_name || '';
                    this.quoteForm.oversea_agent = data.oversea_agent || '';
                    this.quoteForm.service_term = data.service_term || '';
                    this.quoteForm.op = data.op || '';
                    this.quoteForm.incoterms = data.incoterms || '';
                    this.quoteForm.incoterms_id = data.incoterms_id || '';
                    this.quoteForm.carrier_name = data.carrier_name || '';
                    this.quoteForm.detail = data.detail || '';
                    this.quoteForm.ship_mode = data.ship_mode || 'FCL';
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
                    if (this.searchFilters.op && quote.op != this.searchFilters.op) return false;
                    return true;
                },
                openAddNewModal(module, selectName) {
                    if (module === 'trade-partner') {
                        window.open('/trade-partner/create', '_blank');
                    }
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
                    ship_mode: 'FCL',
                    oversea_agent: '',
                    service_term: '',
                    op: '',
                    incoterms: '',
                    incoterms_id: '',
                    carrier_name: '',
                    detail: ''
                },
                hbls: @json(isset($oceanImport) && $oceanImport->hbls->count() ? $oceanImport->hbls : []),
                documents: @json(isset($oceanImport) && $oceanImport->documents ? $oceanImport->documents : []),
                form: {
                    id: @json(isset($oceanImport) ? $oceanImport->id : null),
                    file_no: @json(isset($oceanImport) ? $oceanImport->file_no : 'MOI-' . date('ymdHis')),
                    mbl_no: @json(isset($oceanImport) ? $oceanImport->mbl_no : ''),
                    office_id: @json(isset($oceanImport) ? $oceanImport->office_id : ''),
                    post_date: @json(isset($oceanImport) && $oceanImport->post_date ? $oceanImport->post_date->format('Y-m-d') : date('Y-m-d')),
                    voyage: @json(isset($oceanImport) ? $oceanImport->voyage : ''),
                    etd: @json(isset($oceanImport) && $oceanImport->etd ? $oceanImport->etd->format('Y-m-d') : ''),
                    eta: @json(isset($oceanImport) && $oceanImport->eta ? $oceanImport->eta->format('Y-m-d') : ''),
                    forwarding_agent_id: @json(isset($oceanImport) ? $oceanImport->forwarding_agent_id : ''),
                    op_id: @json(isset($oceanImport) ? $oceanImport->op_id : ''),
                    dm_customer_id: @json(isset($oceanImport) ? $oceanImport->dm_customer_id : ''),
                    dm_shipper_id: @json(isset($oceanImport) ? $oceanImport->dm_shipper_id : ''),
                    dm_consignee_id: @json(isset($oceanImport) ? $oceanImport->dm_consignee_id : ''),
                    dm_notify_id: @json(isset($oceanImport) ? $oceanImport->dm_notify_id : ''),
                    dm_bill_to_id: @json(isset($oceanImport) ? $oceanImport->dm_bill_to_id : ''),
                    dm_sales_person_id: @json(isset($oceanImport) ? $oceanImport->dm_sales_person_id : ''),
                    agent_ref_no: @json(isset($oceanImport) ? $oceanImport->agent_ref_no : ''),
                    oversea_agent_id: @json(isset($oceanImport) ? $oceanImport->oversea_agent_id : ''),
                    co_loader_id: @json(isset($oceanImport) ? $oceanImport->co_loader_id : ''),
                    contract_no: @json(isset($oceanImport) ? $oceanImport->contract_no : ''),
                    carrier_id: @json(isset($oceanImport) ? $oceanImport->carrier_id : ''),
                    bl_type: @json(isset($oceanImport) ? $oceanImport->bl_type : 'NORMAL'),
                    acct_carrier_id: @json(isset($oceanImport) ? $oceanImport->acct_carrier_id : ''),
                    sub_bl_no: @json(isset($oceanImport) ? $oceanImport->sub_bl_no : ''),
                    cargo_type: @json(isset($oceanImport) ? $oceanImport->cargo_type : 'GENERAL CARGO'),
                    vessel_id: @json(isset($oceanImport) ? $oceanImport->vessel_id : ''),
                    pol_id: @json(isset($oceanImport) ? $oceanImport->pol_id : ''),
                    del_id: @json(isset($oceanImport) ? $oceanImport->del_id : ''),
                    atd: @json(isset($oceanImport) && $oceanImport->atd ? $oceanImport->atd->format('Y-m-d') : ''),
                    cy_location_id: @json(isset($oceanImport) ? $oceanImport->cy_location_id : ''),
                    pod_id: @json(isset($oceanImport) ? $oceanImport->pod_id : ''),
                    fdest_id: @json(isset($oceanImport) ? $oceanImport->fdest_id : ''),
                    ata: @json(isset($oceanImport) && $oceanImport->ata ? $oceanImport->ata->format('Y-m-d') : ''),
                    cfs_location_id: @json(isset($oceanImport) ? $oceanImport->cfs_location_id : ''),
                    final_eta: @json(isset($oceanImport) && $oceanImport->final_eta ? $oceanImport->final_eta->format('Y-m-d') : ''),
                    etb: @json(isset($oceanImport) && $oceanImport->etb ? $oceanImport->etb->format('Y-m-d') : ''),
                    freight_term: @json(isset($oceanImport) ? $oceanImport->freight_term : 'Prepaid'),
                    obl_type: @json(isset($oceanImport) ? $oceanImport->obl_type : 'ORIGINAL BILL OF LADING'),
                    latest_gate_in: @json(isset($oceanImport) && $oceanImport->latest_gate_in ? $oceanImport->latest_gate_in->format('Y-m-d') : ''),
                    ship_mode: @json(isset($oceanImport) ? $oceanImport->ship_mode : 'FCL'),
                    is_obl_received: @json(isset($oceanImport) && $oceanImport->is_obl_received ? true : false),
                    obl_received_date: @json(isset($oceanImport) && $oceanImport->obl_received_date ? $oceanImport->obl_received_date->format('Y-m-d') : ''),
                    service_term_from_id: @json(isset($oceanImport) ? $oceanImport->service_term_from_id : ''),
                    service_term_to_id: @json(isset($oceanImport) ? $oceanImport->service_term_to_id : ''),
                    is_released: @json(isset($oceanImport) && $oceanImport->is_released ? true : false),
                    released_date: @json(isset($oceanImport) && $oceanImport->released_date ? $oceanImport->released_date->format('Y-m-d') : ''),
                    business_referred_by_id: @json(isset($oceanImport) ? $oceanImport->business_referred_by_id : ''),
                    receipt_id: @json(isset($oceanImport) ? $oceanImport->receipt_id : ''),
                    receipt_etd: @json(isset($oceanImport) && $oceanImport->receipt_etd ? $oceanImport->receipt_etd->format('Y-m-d') : ''),
                    return_location_id: @json(isset($oceanImport) ? $oceanImport->return_location_id : ''),
                    is_ecommerce: @json(isset($oceanImport) && $oceanImport->is_ecommerce ? true : false),
                    display_unit: 'both',
                    internal_remark: @json(isset($oceanImport) ? $oceanImport->internal_remark : ''),
                    mark: @json(isset($oceanImport) ? $oceanImport->mark : ''),
                    description: @json(isset($oceanImport) ? $oceanImport->description : ''),
                    
                    // Filing
                    ams_no: @json(isset($oceanImport) ? $oceanImport->ams_no : ''),
                    isf_no: @json(isset($oceanImport) ? $oceanImport->isf_no : ''),
                    isf_matched_date: @json(isset($oceanImport) && $oceanImport->isf_matched_date ? $oceanImport->isf_matched_date->format('Y-m-d') : ''),
                    is_isf_3rd_party: @json(isset($oceanImport) && $oceanImport->is_isf_3rd_party ? true : false),
                    entry_no: @json(isset($oceanImport) ? $oceanImport->entry_no : ''),
                    entry_doc_sent_date: @json(isset($oceanImport) && $oceanImport->entry_doc_sent_date ? $oceanImport->entry_doc_sent_date->format('Y-m-d') : ''),
                    go_date: @json(isset($oceanImport) && $oceanImport->go_date ? $oceanImport->go_date->format('Y-m-d') : ''),
                    available_date: @json(isset($oceanImport) && $oceanImport->available_date ? $oceanImport->available_date->format('Y-m-d') : ''),
                    c_released_date: @json(isset($oceanImport) && $oceanImport->c_released_date ? $oceanImport->c_released_date->format('Y-m-d') : ''),
                    released_by_id: @json(isset($oceanImport) ? $oceanImport->released_by_id : ''),
                    is_ror: @json(isset($oceanImport) && $oceanImport->is_ror ? true : false),
                    is_hold: @json(isset($oceanImport) && $oceanImport->is_hold ? true : false),
                    door_delivery_date: @json(isset($oceanImport) && $oceanImport->door_delivery_date ? $oceanImport->door_delivery_date->format('Y-m-d') : ''),
                    trucker_id: @json(isset($oceanImport) ? $oceanImport->trucker_id : ''),
                    expiry_date: @json(isset($oceanImport) && $oceanImport->expiry_date ? $oceanImport->expiry_date->format('Y-m-d') : ''),
                    sales_type: @json(isset($oceanImport) ? $oceanImport->sales_type : ''),
                    incoterm_id: @json(isset($oceanImport) ? $oceanImport->incoterm_id : ''),
                    lfd: @json(isset($oceanImport) && $oceanImport->lfd ? $oceanImport->lfd->format('Y-m-d') : ''),

                    containers: @json(isset($oceanImport) && $oceanImport->containers->count() ? $oceanImport->containers->map(function($c) { return array_merge($c->toArray(), ['expanded' => false, 'selected' => false]); }) : []),
                    memos: @json(isset($oceanImport) && $oceanImport->memos ? $oceanImport->memos : []),
                    history: @json(isset($oceanImport) && $oceanImport->history ? $oceanImport->history()->with('user')->latest()->get() : [])
                },
                init() {
                    // Map DB charge fields to UI charge fields
                    this.chargesList = this.chargesList.map(c => {
                        return {
                            id: c.id || null,
                            selected: false,
                            party: c.type === 'AP' ? 'Agent' : 'Custom',
                            party_name_id: c.type === 'AP' ? (c.vendor_id || '') : (c.bill_to_id || ''),
                            sal: c.sal || 'Sea',
                            pr: c.type === 'AP' ? 'Pay' : 'Rec',
                            ppc: c.pc === 'PREPAID' ? 'Prepaid' : 'Colle',
                            chrg_code: c.charge_code || '',
                            currency: (c.currency && c.currency.code) ? c.currency.code : 'USD',
                            rate: parseFloat(c.rate) || 0,
                            qty: parseFloat(c.qty) || 1,
                            qty_type: c.unit || 'B/L',
                            roe: parseFloat(c.roe) || 1.0,
                            vat: parseFloat(c.vat) || 0,
                            inv_no: c.invoice_no || '',
                            financial_date: c.invoice_date ? c.invoice_date.substring(0, 10) : new Date().toISOString().split('T')[0],
                            eq_bl_no: c.remark || '',
                            remark: !!c.remark,
                            mbl_no: ''
                        };
                    });

                    // Format HBL dates
                    this.hbls.forEach(h => {
                        if (h.date_of_issue) h.date_of_issue = h.date_of_issue.substring(0, 10);
                        if (h.obl_received_date) h.obl_received_date = h.obl_received_date.substring(0, 10);
                        if (h.fr_released_date) h.fr_released_date = h.fr_released_date.substring(0, 10);
                        if (h.an_sent_date) h.an_sent_date = h.an_sent_date.substring(0, 10);
                        if (h.do_sent_date) h.do_sent_date = h.do_sent_date.substring(0, 10);
                        
                        h.show = true;
                        h.showMore = false;
                        h.showMemo = false;
                        
                        h.po_no = h.po_no || '';
                        h.po_mapping_type = h.po_mapping_type || 'container';
                        h.hbl_mark = h.hbl_mark || '';
                        h.hbl_description = h.hbl_description || '';
                        h.arrival_notice_remark = h.arrival_notice_remark || '';
                        h.delivery_order_remark = h.delivery_order_remark || '';
                        h.remark_tab = 'arrival_notice';
                        
                        // Map eager-loaded containers with pivot columns
                        h.containers = (h.containers || []).map(c => ({
                            container_no: c.container_no,
                            pkg_qty: c.pivot ? c.pivot.pkg_qty : (c.pkg_qty || ''),
                            pkg_unit: c.pivot ? c.pivot.pkg_unit : (c.pkg_unit || 'CARTON(S)'),
                            weight_kg: c.pivot ? c.pivot.weight_kg : (c.weight_kg || ''),
                            weight_unit: c.pivot ? c.pivot.weight_unit : (c.weight_unit || 'KG'),
                            measure_cbm: c.pivot ? c.pivot.measure_cbm : (c.measure_cbm || ''),
                            measure_unit: c.pivot ? c.pivot.measure_unit : (c.measure_unit || 'CBM'),
                            po_no: c.pivot ? c.pivot.po_no : (c.po_no || '')
                        }));
                        
                        h.commodities = (h.commodities || []).map(comm => ({
                            selected: false,
                            commodity_desc: comm.commodity_desc || '',
                            hts_code: comm.hts_code || '',
                            container_no: comm.container_no || '',
                            po_no: comm.po_no || ''
                        }));

                        h.receipts = (h.receipts || []).map(rec => ({
                            selected: false,
                            receipt_no: rec.receipt_no || '',
                            vin_no: rec.vin_no || '',
                            total_pcs: rec.total_pcs || 0,
                            available_pcs: rec.available_pcs || 0,
                            allocated_pcs: rec.allocated_pcs || 0,
                            unit: rec.unit || 'PCS',
                            actual_weight: rec.actual_weight || '',
                            measurement: rec.measurement || '',
                            remarks: rec.remarks || ''
                        }));
                    });

                    // Format filing dates
                    if (this.form.isf_matched_date) this.form.isf_matched_date = this.form.isf_matched_date.substring(0, 10);
                    if (this.form.entry_doc_sent_date) this.form.entry_doc_sent_date = this.form.entry_doc_sent_date.substring(0, 10);
                    if (this.form.go_date) this.form.go_date = this.form.go_date.substring(0, 10);
                    if (this.form.available_date) this.form.available_date = this.form.available_date.substring(0, 10);
                    if (this.form.c_released_date) this.form.c_released_date = this.form.c_released_date.substring(0, 10);
                    if (this.form.door_delivery_date) this.form.door_delivery_date = this.form.door_delivery_date.substring(0, 10);
                    if (this.form.expiry_date) this.form.expiry_date = this.form.expiry_date.substring(0, 10);

                    // Format container dates & set expanded state
                    this.form.containers.forEach(c => {
                        c.selected = false;
                        c.expanded = false;
                        if (c.lfd) c.lfd = c.lfd.substring(0, 10);
                        if (c.fdd) c.fdd = c.fdd.substring(0, 10);
                        if (c.storage_start_date) c.storage_start_date = c.storage_start_date.substring(0, 10);
                        if (c.storage_end_date) c.storage_end_date = c.storage_end_date.substring(0, 10);
                        if (c.unload_vessel_date) c.unload_vessel_date = c.unload_vessel_date.substring(0, 10);
                        if (c.gate_in_date) c.gate_in_date = c.gate_in_date.substring(0, 10);
                        if (c.rail_start_date) c.rail_start_date = c.rail_start_date.substring(0, 10);
                        if (c.pod_eta) c.pod_eta = c.pod_eta.substring(0, 10);
                        if (c.appointment_date) c.appointment_date = c.appointment_date.substring(0, 10);
                        if (c.pickup_date) c.pickup_date = c.pickup_date.substring(0, 10);
                        if (c.gate_out_date) c.gate_out_date = c.gate_out_date.substring(0, 10);
                        if (c.fdest_eta) c.fdest_eta = c.fdest_eta.substring(0, 10);
                        if (c.eta_door) c.eta_door = c.eta_door.substring(0, 10);
                        if (c.ata_door) c.ata_door = c.ata_door.substring(0, 10);
                        if (c.empty_conf_date) c.empty_conf_date = c.empty_conf_date.substring(0, 10);
                        if (c.empty_ret_date) c.empty_ret_date = c.empty_ret_date.substring(0, 10);
                    });
                    this.$watch('activeTab', val => sessionStorage.setItem('oceanImportActiveTab', val));
                    this.$watch('inputTotalMode', val => {
                        if (val && this.form.containers.length > 0) {
                            let pkg = prompt('Enter total package quantity:', this.calculateTotal('pkg_qty'));
                            if (pkg !== null && !isNaN(parseFloat(pkg))) {
                                let perContainer = parseFloat(pkg) / this.form.containers.length;
                                this.form.containers.forEach(c => { c.pkg_qty = perContainer; });
                            }
                        }
                    });
                },
                isMainValid() {
                    return !!this.form.mbl_no && !!this.form.office_id;
                },
                async checkMblUnique() {
                    if (!this.form.mbl_no) return true;
                    try {
                        const resp = await fetch('/ocean-import/list?search=' + encodeURIComponent(this.form.mbl_no), {
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                        });
                        if (resp.ok) {
                            const data = await resp.json();
                            const currentId = this.form.id ? String(this.form.id) : null;
                            const matches = (data.data || data).filter ? (data.data || data).filter(s => s.mbl_no === this.form.mbl_no && String(s.id) !== currentId) : [];
                            if (matches.length > 0) return false;
                        }
                    } catch (e) { /* ignore network errors, let server validate */ }
                    return true;
                },
                validateMainFields() {
                    let errors = [];
                    if (!this.form.mbl_no || !this.form.mbl_no.trim()) {
                        errors.push('MB/L No. is required.');
                    }
                    if (!this.form.office_id) {
                        errors.push('Office is required.');
                    }
                    return errors;
                },
                async saveMainTab() {
                    if (this.isSaving) return;
                    const mainErrors = this.validateMainFields();
                    if (mainErrors.length > 0) {
                        showToast('error', 'Please fix: ' + mainErrors.join(', '));
                        return;
                    }
                    const isUnique = await this.checkMblUnique();
                    if (!isUnique) {
                        showToast('error', 'MB/L No. "' + this.form.mbl_no + '" already exists. Please use a unique number.');
                        return;
                    }
                    this.isSaving = true;
                    this.saveError = '';
                    try {
                        const form = document.querySelector('form[action*="ocean-import"]');
                        const formData = new FormData(form);
                        const resp = await fetch(form.action, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                            body: formData
                        });
                        const data = await resp.json();
                        if (!resp.ok) {
                            const firstError = Object.values(data.errors || {}).flat()[0] || data.message || 'Validation failed';
                            throw new Error(firstError);
                        }
                        if (data.success) {
                            window.location.href = '/ocean-import/' + data.id + '/edit';
                        } else {
                            throw new Error(data.message || 'Save failed');
                        }
                    } catch (err) {
                        console.error(err);
                        this.saveError = err.message;
                        showToast('error', 'Error: ' + err.message);
                    } finally {
                        this.isSaving = false;
                    }
                },
                validateForm() {
                    let errors = [];
                    if (!this.form.mbl_no || !this.form.mbl_no.trim()) {
                        errors.push('MB/L No. is required.');
                    }
                    if (!this.form.office_id) {
                        errors.push('Office is required.');
                    }
                    for (let i = 0; i < this.hbls.length; i++) {
                        if (!this.hbls[i].hbl_no || !this.hbls[i].hbl_no.trim()) {
                            errors.push('HB/L No. is required for House B/L #' + (i + 1) + '.');
                        }
                    }
                    if (errors.length > 0) {
                        showToast('error', 'Please fix: ' + errors.join(', '));
                        return false;
                    }
                    return true;
                },
                addContainer(count = 1) {
                    const c = (typeof count === 'number') ? count : 1;
                    for(let i=0; i<c; i++) {
                        this.form.containers.push({
                            id: null,
                            selected: false,
                            container_no: '',
                            pp_ctf: '',
                            container_type_id: '',
                            seal_no: '',
                            seal_no2: '',
                            lfd: null,
                            fdd: null,
                            storage_start_date: null,
                            storage_end_date: null,
                            unload_vessel_date: null,
                            gate_in_date: null,
                            rail_start_date: null,
                            pod_eta: null,
                            appointment_date: null,
                            pickup_date: null,
                            gate_out_date: null,
                            fdest_eta: null,
                            eta_door: null,
                            ata_door: null,
                            empty_conf_date: null,
                            empty_ret_date: null,
                            pkg_qty: null,
                            weight_kg: null,
                            weight_lb: null,
                            measure_cbm: null,
                            measure_cft: null,
                            pickup_no: '',
                            cprs_no: '',
                            cnru_no: '',
                            it_no: '',
                            is_dg: 0,
                            is_carrier_release: 0,
                            yard_location: '',
                            is_avail_pickup: 0,
                            trucker_id: '',
                            chassis_days: null,
                            is_customs_hold: 0,
                            is_an_sent: 0,
                            an_sent_date: null,
                            is_do_sent: 0,
                            do_sent_date: null,
                            is_complete: 0,
                            remarks: '',
                            internal_remarks: '',
                            expanded: false,
                            tare_weight: null,
                            vgm: null,
                            net_weight: null
                        });
                    }
                },
                deleteSelectedContainers() {
                    this.form.containers = this.form.containers.filter(c => !c.selected);
                },
                toggleAllContainers(e) {
                    this.form.containers.forEach(c => c.selected = e.target.checked);
                },
                duplicateSelectedContainers() {
                    let toDuplicate = [];
                    this.form.containers.forEach(c => {
                        if (c.selected) {
                            let clone = JSON.parse(JSON.stringify(c));
                            clone.id = null;
                            clone.selected = false;
                            clone.container_no = clone.container_no + ' - Copy';
                            toDuplicate.push(clone);
                        }
                    });
                    this.form.containers.push(...toDuplicate);
                },
                addBulkContainers() {
                    let count = prompt("How many containers to add?", "5");
                    if (count && !isNaN(count)) {
                        this.addContainer(parseInt(count));
                    }
                },
                handleContainerImport(e) {
                    let file = e.target.files[0];
                    if (!file) return;
                    let formData = new FormData();
                    formData.append('file', file);
                    
                    fetch('{{ isset($oceanImport) ? route('ocean-import.containers.import', $oceanImport->id) : route('ocean-import.containers.import-temp') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.containers) {
                            data.containers.forEach(c => {
                                this.form.containers.push({
                                    id: null,
                                    selected: false,
                                    container_no: c.container_no || '',
                                    pp_ctf: c.pp_ctf || '',
                                    container_type_id: '',
                                    seal_no: c.seal_no || '',
                                    seal_no2: c.seal_no2 || '',
                                    lfd: '',
                                    fdd: '',
                                    storage_start_date: '',
                                    storage_end_date: '',
                                    unload_vessel_date: '',
                                    gate_in_date: '',
                                    rail_start_date: '',
                                    pod_eta: '',
                                    appointment_date: '',
                                    pickup_date: '',
                                    gate_out_date: '',
                                    fdest_eta: '',
                                    eta_door: '',
                                    ata_door: '',
                                    empty_conf_date: '',
                                    empty_ret_date: '',
                                    pkg_qty: c.pkg_qty || 0,
                                    weight_kg: c.weight_kg || 0,
                                    weight_lb: 0,
                                    measure_cbm: c.measure_cbm || 0,
                                    measure_cft: 0,
                                    pickup_no: '',
                                    cprs_no: '',
                                    cnru_no: '',
                                    it_no: '',
                                    is_dg: 0,
                                    is_carrier_release: 0,
                                    yard_location: '',
                                    is_avail_pickup: 0,
                                    trucker_id: '',
                                    is_complete: 0,
                                    remarks: '',
                                    internal_remarks: '',
                                    tare_weight: '',
                                    vgm: '',
                                    net_weight: '',
                                    expanded: false
                                });
                            });
                            showToast('success', 'Containers imported successfully!');
                        } else {
                            showToast('error', 'Failed to import containers.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast('error', 'Error importing containers.');
                    });
                },
                calculateTotal(field) {
                    return this.form.containers.reduce((sum, c) => sum + (parseFloat(c[field]) || 0), 0);
                },
                copyContainersFromMbl(hbl) {
                    hbl.containers = this.form.containers.map(c => ({
                        container_no: c.container_no,
                        pkg_qty: c.pkg_qty || '',
                        pkg_unit: 'CARTON(S)',
                        weight_kg: c.weight_kg || '',
                        weight_unit: 'KG',
                        measure_cbm: c.measure_cbm || '',
                        measure_unit: 'CBM',
                        po_no: ''
                    }));
                },
                createApFromContainers() {
                    if (!this.form.containers.length) { showToast('error', 'No containers to create A/P from.'); return; }
                    this.form.containers.forEach(c => {
                        if (c.selected || this.form.containers.length === 1) {
                            this.chargesList.push({
                                id: null, selected: false, party: 'Vendor', party_name_id: '', sal: 'Sea', pr: 'Pay', ppc: 'Collect',
                                chrg_code: 'CNTR', currency: 'USD', rate: 0, qty: 1, qty_type: 'CNTR', roe: 1, vat: 0,
                                inv_no: '', financial_date: new Date().toISOString().split('T')[0], eq_bl_no: c.container_no || '', remark: false, mbl_no: this.form.mbl_no || ''
                            });
                        }
                    });
                    this.activeTab = 'charges';
                    showToast('success', 'A/P charges created from selected containers.');
                },
                copyDataFromAllHbl() {
                    let totalPkg = 0, totalWgt = 0, totalVol = 0;
                    (this.hbls || []).forEach(h => {
                        (h.containers || []).forEach(c => {
                            totalPkg += parseFloat(c.pkg_qty) || 0;
                            totalWgt += parseFloat(c.weight_kg) || 0;
                            totalVol += parseFloat(c.measure_cbm) || 0;
                        });
                    });
                    if (!this.form.containers.length) this.addContainer();
                    this.form.containers.forEach(c => {
                        c.pkg_qty = (parseFloat(c.pkg_qty) || 0) + totalPkg;
                        c.weight_kg = (parseFloat(c.weight_kg) || 0) + totalWgt;
                        c.measure_cbm = (parseFloat(c.measure_cbm) || 0) + totalVol;
                    });
                    showToast('success', 'HBL container data copied to MBL containers. Totals updated.');
                },
                copyDescriptionFromAllHbl() {
                    const descs = (this.hbls || []).map(h => h.hbl_description || h.hbl_no || '').filter(Boolean);
                    if (descs.length) {
                        this.form.description = descs.join('; ');
                        showToast('success', 'Descriptions copied from all HBLs.');
                    } else {
                        showToast('info', 'No HBL descriptions found.');
                    }
                },
                getPoList(poStr) {
                    if (!poStr) return [];
                    return poStr.split(',').map(s => s.trim()).filter(s => s.length > 0);
                },
                calculateHblTotal(hbl, field) {
                    return (hbl.containers || []).reduce((sum, c) => sum + (parseFloat(c[field]) || 0), 0).toFixed(2);
                },
                addHblCommodity(hbl) {
                    hbl.commodities.push({
                        selected: false,
                        commodity_desc: '',
                        hts_code: '',
                        container_no: '',
                        po_no: ''
                    });
                },
                deleteSelectedHblCommodities(hbl) {
                    hbl.commodities = hbl.commodities.filter(c => !c.selected);
                },
                toggleAllHblCommodities(hbl, event) {
                    hbl.commodities.forEach(c => c.selected = event.target.checked);
                },
                copyToDescription(hbl, mode) {
                    let texts = [];
                    if (mode === 'po' || mode === 'both') {
                        if (hbl.po_no) {
                            texts.push("P.O. No.: " + hbl.po_no);
                        }
                    }
                    if (mode === 'commodity' || mode === 'both') {
                        (hbl.commodities || []).forEach(comm => {
                            if (comm.commodity_desc) {
                                let text = comm.commodity_desc;
                                if (comm.hts_code) text += " (HTS: " + comm.hts_code + ")";
                                texts.push(text);
                            }
                        });
                    }
                    hbl.hbl_description = texts.join("\n");
                },
                toggleAllHblReceipts(hbl, event) {
                    hbl.receipts.forEach(r => r.selected = event.target.checked);
                },
                deleteSelectedHblReceipts(hbl) {
                    hbl.receipts = hbl.receipts.filter(r => !r.selected);
                    if (hbl.auto_sync_receipts) {
                        this.syncReceiptTotalsToContainers(hbl);
                    }
                },
                openWarehouseReceiptModal(hbl) {
                    this.activeHblForReceipts = hbl;
                    this.wrSearchQuery = '';
                    this.wrSearchResults = [];
                    this.showWrModal = true;
                    this.searchWrList();
                },
                searchWrList() {
                    fetch('/ocean-import/warehouse-receipts/search?q=' + encodeURIComponent(this.wrSearchQuery))
                        .then(res => res.json())
                        .then(data => {
                            this.wrSearchResults = data;
                        });
                },
                loadSelectedReceipts() {
                    const selected = this.wrSearchResults.filter(r => r.selected);
                    if (selected.length > 0 && this.activeHblForReceipts) {
                        selected.forEach(s => {
                            const exists = this.activeHblForReceipts.receipts.some(r => r.receipt_no === s.receipt_no);
                            if (!exists) {
                                this.activeHblForReceipts.receipts.push({
                                    selected: false,
                                    receipt_no: s.receipt_no,
                                    vin_no: s.vin_no,
                                    total_pcs: s.total_pcs,
                                    available_pcs: s.available_pcs,
                                    allocated_pcs: s.allocated_pcs,
                                    unit: s.unit,
                                    actual_weight: s.actual_weight,
                                    measurement: s.measurement,
                                    remarks: s.remarks
                                });
                            }
                        });
                        if (this.activeHblForReceipts.auto_sync_receipts) {
                            this.syncReceiptTotalsToContainers(this.activeHblForReceipts);
                        }
                    }
                    this.showWrModal = false;
                },
                createHblReceiptLink(hbl) {
                    hbl.receipts.push({
                        selected: false,
                        receipt_no: 'WR-' + new Date().getTime().toString().substring(7),
                        vin_no: '1FTFW1EF' + Math.floor(100000 + Math.random() * 900000),
                        total_pcs: 1,
                        available_pcs: 1,
                        allocated_pcs: 0,
                        unit: 'PCS',
                        actual_weight: 0,
                        measurement: 0,
                        remarks: 'Linked receipt'
                    });
                },
                syncReceiptTotalsToContainers(hbl) {
                    const totalPcs = hbl.receipts.reduce((sum, r) => sum + (parseInt(r.total_pcs) || 0), 0);
                    const totalWeight = hbl.receipts.reduce((sum, r) => sum + (parseFloat(r.actual_weight) || 0), 0);
                    const totalMeasure = hbl.receipts.reduce((sum, r) => sum + (parseFloat(r.measurement) || 0), 0);
                    
                    if (hbl.containers.length > 0) {
                        hbl.containers[0].pkg_qty = totalPcs;
                        hbl.containers[0].weight_kg = totalWeight;
                        hbl.containers[0].measure_cbm = totalMeasure;
                    }
                },
                addHbl() {
                    this.hbls.push({
                        id: null,
                        show: true,
                        showMore: false,
                        showMemo: false,
                        hbl_no: '',
                        quotation_no: '',
                        customer_id: '',
                        sales_person_id: '',
                        customs_broker_id: '',
                        del_id: '',
                        delivery_location_id: '',
                        is_rail: false,
                        shipper_id: '',
                        date_of_issue: '',
                        pod_id: '',
                        pre_carriage_by: '',
                        vessel_name: '',
                        service_term: '',
                        ship_type: '',
                        freight_released_by_id: '',
                        consignee_id: '',
                        pol_id: '',
                        fdest_id: '',
                        voyage_no: '',
                        lc_no: '',
                        cargo_type: '',
                        is_do_sent: false,
                        do_sent_date: '',
                        notify_party_id: '',
                        receipt_id: '',
                        freight_payable_at: '',
                        incoterms_id: '',
                        sc_no: '',
                        ship_mode: '',
                        cfs_location_id: '',
                        is_express_bl: '0',
                        is_door_move: false,
                        is_customs_clear: false,
                        is_customs_hold: false,
                        referred_by_id: '',
                        is_obl_received: false,
                        obl_received_date: '',
                        is_fr_released: false,
                        fr_released_date: '',
                        is_an_sent: false,
                        an_sent_date: '',
                        name_account: '',
                        group_comm: '',
                        line_code: '',
                        is_ecommerce: false,
                        is_customs_doc: false,
                        hbl_remark: '',
                        po_no: '',
                        po_mapping_type: 'container',
                        hbl_mark: '',
                        hbl_description: '',
                        arrival_notice_remark: '',
                        delivery_order_remark: '',
                        remark_tab: 'arrival_notice',
                        containers: [],
                        commodities: [],
                        receipts: []
                    });
                    this.showMblSection = false;
                },
                removeHbl(idx) {
                    if(confirm('Are you sure you want to remove this HBL?')) {
                        this.hbls.splice(idx, 1);
                    }
                },
                confirmQuoteSelection() {
                    this.form.mbl_no = this.quoteForm.mbl_no;
                    this.form.eta = this.quoteForm.eta;
                    this.form.etd = this.quoteForm.etd;
                    if (this.quoteForm.customer_id) this.form.dm_customer_id = this.quoteForm.customer_id;
                    if (this.quoteForm.sales_person_id) this.form.dm_sales_person_id = this.quoteForm.sales_person_id;
                    if (this.quoteForm.pol_id) this.form.pol_id = this.quoteForm.pol_id;
                    if (this.quoteForm.pod_id) this.form.pod_id = this.quoteForm.pod_id;
                    if (this.quoteForm.incoterms_id) this.form.incoterm_id = this.quoteForm.incoterms_id;
                    if(this.hbls.length === 0) this.addHbl();
                    this.hbls[0].hbl_no = this.quoteForm.hbl_no;
                    if (this.quoteForm.quote_no) {
                        this.hbls[0].quotation_no = this.quoteForm.quote_no;
                    }
                    if (this.selectedQuote && this.selectedQuote.items) {
                        const items = this.selectedQuote.items.filter(item => item.selected !== false);
                        items.forEach(item => {
                            this.chargesList.push({
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
                saveShipment() {
                    // Let normal form submit handle it
                },

                // ============ CHARGES SECTION FUNCTIONS ============
                get customerName() {
                    if (this.form.dm_customer_id) {
                        const tp = @json($agents->map(fn($a) => ['id' => $a->id, 'name' => $a->name]));
                        const found = tp.find(a => a.id == this.form.dm_customer_id);
                        return found ? found.name : '';
                    }
                    return '';
                },
                chargesList: @json(isset($oceanImport) && $oceanImport->charges && count($oceanImport->charges) > 0 ? $oceanImport->charges : []),

                calculateTotalCharges() {
                    if (!this.chargesList || this.chargesList.length === 0) return 0;
                    return this.chargesList.reduce((sum, charge) => {
                        return sum + this.calculateLocalAmount(charge);
                    }, 0);
                },

                calculateArCharges() {
                    if (!this.chargesList || this.chargesList.length === 0) return 0;
                    return this.chargesList.filter(c => c.pr === 'Rec').reduce((sum, charge) => {
                        return sum + this.calculateLocalAmount(charge);
                    }, 0);
                },

                calculateApCharges() {
                    if (!this.chargesList || this.chargesList.length === 0) return 0;
                    return this.chargesList.filter(c => c.pr === 'Pay').reduce((sum, charge) => {
                        return sum + this.calculateLocalAmount(charge);
                    }, 0);
                },

                calculateLocalAmount(charge) {
                    let foreignAmount = (charge.rate || 0) * (charge.qty || 0);
                    let localAmount = foreignAmount * (charge.roe || 1);
                    if (charge.vat && charge.vat > 0) {
                        localAmount = localAmount + (localAmount * (charge.vat / 100));
                    }
                    return localAmount;
                },

                updateChargeAmount(idx) {
                    this.$forceUpdate();
                },

                updateLocalAmount(idx) {
                    this.$forceUpdate();
                },

                saveCharges() {
                    if (!this.validateForm()) return;
                    const formEl = document.querySelector('form[action*="ocean-import"]');
                    if (formEl) {
                        formEl.submit();
                    }
                },

                openCertificateModal() {
                    showToast('info', 'Certificate functionality - coming soon');
                },

                setDefaultCharges() {
                    this.chargesList = [
                        { id: null, selected: false, party: 'Custom', party_name_id: '', sal: 'Sea', pr: 'Rec', ppc: 'Colle', chrg_code: 'OFC', currency: 'USD', rate: 50, qty: 1, qty_type: 'B/L', roe: 120.0, vat: 0, inv_no: '', financial_date: new Date().toISOString().split('T')[0], eq_bl_no: '', remark: false, mbl_no: '' },
                        { id: null, selected: false, party: 'Custom', party_name_id: '', sal: 'Sea', pr: 'Rec', ppc: 'Colle', chrg_code: 'THC', currency: 'USD', rate: 10, qty: 1, qty_type: 'CBM', roe: 120.0, vat: 0, inv_no: '', financial_date: new Date().toISOString().split('T')[0], eq_bl_no: '', remark: false, mbl_no: '' }
                    ];
                },

                reloadCharges() {
                    if (confirm('Discard changes and reload charges from database?')) {
                        window.location.reload();
                    }
                },

                getChargeFilters() {
                    let list = [{ name: 'All', value: 'All' }];
                    if (this.form.file_no) {
                        list.push({ name: 'MBL: ' + this.form.file_no, value: this.form.file_no });
                    }
                    this.hbls.forEach(h => {
                        if (h.hbl_no) {
                            list.push({ name: 'HBL: ' + h.hbl_no, value: h.hbl_no });
                        }
                    });
                    this.form.containers.forEach(c => {
                        if (c.container_no) {
                            list.push({ name: 'Cont: ' + c.container_no, value: c.container_no });
                        }
                    });
                    return list;
                },

                shouldShowChargeRow(charge) {
                    if (this.activeChargeFilter === 'All') return true;
                    let filterVal = this.activeChargeFilter.trim().toLowerCase();
                    let mbl = (charge.mbl_no || '').trim().toLowerCase();
                    let eqBl = (charge.eq_bl_no || '').trim().toLowerCase();
                    return mbl === filterVal || eqBl === filterVal;
                },

                addNewCharge() {
                    let defaultEqBl = '';
                    let defaultMbl = '';
                    if (this.activeChargeFilter && this.activeChargeFilter !== 'All') {
                        if (this.activeChargeFilter === this.form.file_no) {
                            defaultMbl = this.form.file_no;
                        } else {
                            defaultEqBl = this.activeChargeFilter;
                        }
                    }
                    this.chargesList.push({
                        id: null,
                        selected: false,
                        party: 'Custom',
                        party_name_id: '',
                        sal: 'Sea',
                        pr: 'Rec',
                        ppc: 'Colle',
                        chrg_code: '',
                        currency: 'USD',
                        rate: 0,
                        qty: 1,
                        qty_type: 'B/L',
                        roe: 1.0,
                        vat: 0,
                        inv_no: '',
                        financial_date: new Date().toISOString().split('T')[0],
                        eq_bl_no: defaultEqBl,
                        remark: false,
                        mbl_no: defaultMbl
                    });
                },

                removeCharge(idx) {
                    this.chargesList.splice(idx, 1);
                },

                applyTemplate() {
                    if (!confirm('Are you sure you want to load the default template charges?')) return;
                    fetch(`/ocean-import/${this.form.id}/charges/template`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            showToast('error', 'Failed to apply template.');
                        }
                    });
                },

                copyFromQuote() {
                    let quoteId = prompt('Enter Quotation ID to copy charges from:');
                    if (!quoteId) return;
                    fetch(`/ocean-import/${this.form.id}/charges/copy-quote`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ quote_id: quoteId })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            showToast('error', 'Failed to copy charges.');
                        }
                    });
                },

                createInvoice() {
                    if (!confirm('Create invoice from uninvoiced charges?')) return;
                    fetch(`/ocean-import/${this.form.id}/charges/invoice`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', 'Invoice created successfully: ' + data.invoice_no);
                            window.location.reload();
                        } else {
                            showToast('error', 'Failed to create invoice: ' + data.message);
                        }
                    });
                },

                prorataCharges() {
                    let chargeId = prompt('Enter Charge ID to prorate:');
                    if (!chargeId) return;
                    let basis = prompt('Enter prorate basis (volume / weight):', 'volume');
                    if (basis !== 'volume' && basis !== 'weight') {
                        showToast('error', 'Invalid basis.');
                        return;
                    }
                    fetch(`/ocean-import/${this.form.id}/charges/prorata`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ charge_id: chargeId, basis: basis })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            showToast('error', 'Failed to prorate charges: ' + data.message);
                        }
                    });
                },

                exportChargesToExcel() {
                    window.location.href = `/ocean-import/${this.form.id}/charges/export`;
                },

                printCharges() {
                    window.open(`/ocean-import/${this.form.id}/charges/print`, '_blank');
                },

                deleteAllCharges() {
                    if (!confirm('Are you sure you want to delete all charges?')) return;
                    if (this.form.id) {
                        fetch(`/ocean-import/${this.form.id}/charges/all`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.chargesList = [];
                                showToast('success', 'All charges deleted successfully.');
                            } else {
                                showToast('error', 'Failed to delete charges.');
                            }
                        });
                    } else {
                        this.chargesList = [];
                    }
                },

                duplicateSelectedCharges() {
                    let ids = this.chargesList.filter(c => c.selected).map(c => c.id);
                    if (ids.length === 0) {
                        let chargeId = prompt('Enter charge ID to duplicate:');
                        if (chargeId) ids = [chargeId];
                        else {
                            showToast('error', 'Please select charges using checkbox or enter charge ID.');
                            return;
                        }
                    }
                    fetch(`/ocean-import/${this.form.id}/charges/duplicate`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ ids: ids })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            showToast('error', 'Failed to duplicate charges.');
                        }
                    });
                },

                bulkUpdateCurrency() {
                    let newCurrency = prompt('Enter new currency (USD/BDT/EUR/GBP):', 'USD');
                    if (newCurrency && ['USD','BDT','EUR','GBP'].includes(newCurrency.toUpperCase())) {
                        fetch(`/ocean-import/${this.form.id}/charges/bulk-currency`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ currency: newCurrency.toUpperCase() })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.chargesList.forEach(charge => charge.currency = newCurrency.toUpperCase());
                                showToast('success', 'Currency updated successfully!');
                            } else {
                                showToast('error', 'Failed to update currency.');
                            }
                        });
                    }
                },

                applyVatToAll() {
                    let vatPercent = prompt('Enter VAT percentage to apply to all charges (e.g., 15):', '0');
                    if (vatPercent !== null && !isNaN(parseFloat(vatPercent))) {
                        fetch(`/ocean-import/${this.form.id}/charges/apply-vat`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ vat: parseFloat(vatPercent) })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.chargesList.forEach(charge => charge.vat = parseFloat(vatPercent));
                                showToast('success', 'VAT ' + vatPercent + '% applied to all charges!');
                            } else {
                                showToast('error', 'Failed to apply VAT.');
                            }
                        });
                    }
                },

                // ============ DOCUMENT MODAL FUNCTIONS ============
                uploadDocument(e) {
                    let file = e.target.files[0];
                    if (!file) return;
                    let formData = new FormData();
                    formData.append('file', file);
                    formData.append('description', prompt('Enter document description (optional):', ''));
                    
                    fetch(this.form.id ? `/ocean-import/${this.form.id}/documents` : '/ocean-import/documents/store-temp', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.documents.push(data.document);
                            showToast('success', 'Document uploaded successfully!');
                        } else {
                            showToast('error', 'Upload failed: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast('error', 'Error uploading document.');
                    });
                },
                deleteDocument(id, idx) {
                    if (!confirm('Are you sure you want to delete this document?')) return;
                    if (id) {
                        fetch(`/ocean-import/documents/${id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.documents.splice(idx, 1);
                                showToast('success', 'Document deleted.');
                            } else {
                                showToast('error', 'Failed to delete document.');
                            }
                        });
                    } else {
                        this.documents.splice(idx, 1);
                    }
                },
                downloadDocument(id) {
                    if (id) {
                        window.location.href = `/ocean-import/documents/${id}/download`;
                    } else {
                        showToast('error', 'Unsaved document cannot be downloaded.');
                    }
                },

                // ============ MEMO FUNCTIONS ============
                addMemo() {
                    let subject = prompt('Enter note subject:', 'New Note');
                    if (!subject) return;
                    
                    if (this.form.id) {
                        fetch(`/ocean-import/${this.form.id}/memos`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ subject: subject, content: '' })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.form.memos.push(data.memo);
                                this.selectedMemoIndex = this.form.memos.length - 1;
                            }
                        });
                    } else {
                        this.form.memos.push({
                            id: null,
                            subject: subject,
                            content: '',
                            user_id: {{ auth()->id() ?? 'null' }},
                            user_name: '{{ auth()->user()->name ?? "System" }}',
                            updated_at: new Date().toISOString().split('T')[0]
                        });
                        this.selectedMemoIndex = this.form.memos.length - 1;
                    }
                },
                selectMemo(idx) {
                    this.selectedMemoIndex = idx;
                },
                deleteMemo(idx) {
                    if (!confirm('Are you sure you want to delete this note?')) return;
                    let memo = this.form.memos[idx];
                    if (memo.id) {
                        fetch(`/ocean-import/memos/${memo.id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.form.memos.splice(idx, 1);
                                if (this.selectedMemoIndex === idx) this.selectedMemoIndex = null;
                            }
                        });
                    } else {
                        this.form.memos.splice(idx, 1);
                        if (this.selectedMemoIndex === idx) this.selectedMemoIndex = null;
                    }
                },
                getUserName(id) {
                    if (!id) return 'N/A';
                    const users = @json($users->map(fn($u) => ['id' => $u->id, 'name' => $u->name]));
                    const user = users.find(u => u.id == id);
                    return user ? user.name : 'N/A';
                },
                copyContainerToClipboard() {
                    let text = 'Container No.\tTP/SZ\tSeal No.\tPKG\tTARE\tVGM\tNet Weight\tGross Weight\tMeasurement\n';
                    this.form.containers.forEach(c => {
                        text += (c.container_no || '-') + '\t' + (c.container_type_id || '-') + '\t' + (c.seal_no || '-') + '\t' + (c.pkg_qty || '0') + '\t-\t-\t-\t' + (c.weight_kg || '0') + '\t' + (c.measure_cbm || '0') + '\n';
                    });
                    navigator.clipboard.writeText(text).then(() => {
                        showToast('success', 'Container data copied to clipboard!');
                    }).catch(() => {
                        showToast('error', 'Failed to copy. Please select and copy manually.');
                    });
                },
                quoteItems: {
                    @foreach($quotations as $q)
                    "{{ $q->quote_no }}": {!! json_encode($q->items->map(fn($i) => [
                        'id' => $i->id,
                        'charge_code' => $i->charge_code,
                        'charge_name' => $i->charge_name,
                        'qty' => (float)$i->qty,
                        'unit' => $i->unit,
                        'currency' => $i->currency->code ?? 'USD',
                        'rate' => (float)$i->rate,
                        'amount' => (float)$i->amount,
                    ])->values()->toArray()) !!},
                    @endforeach
                }
            }
        }
    </script>

    <div class="page-content" x-data="oceanImportModule()">
        <!-- Breadcrumbs -->
           <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
                <li><a href="/ocean-import/list">Ocean Import</a> <i class="fa fa-angle-right"></i></li>
                <li><span style="color: #333; font-weight: 700;">{{ isset($oceanImport) ? 'Edit Shipment: ' . $oceanImport->file_no : 'New Shipment' }}</span></li>
            </ul>
        </div>

        <!-- Toolbar -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;">
            <h1 class="caption-subject" style="font-size: 18px;">{{ isset($oceanImport) ? 'Edit' : 'Create' }} Ocean Import Shipment</h1>
            <div style="display: flex; gap: 8px;">
                <template x-if="!saved">
                    <button type="button" class="btn-gofreight" @click="saveMainTab" :disabled="isSaving" style="background:#f59e0b;">
                        <i class="fa" :class="isSaving ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                        <span x-text="isSaving ? 'SAVING...' : 'SAVE MAIN'"></span>
                    </button>
                </template>
                <button type="button" class="btn-gofreight" x-show="saved" @click="if(validateForm()) $el.closest('form').submit()"><i class="fa fa-save"></i> SAVE SHIPMENT</button>
                <a href="{{ route('ocean-import.index') }}" class="btn-default-gf">BACK TO LIST</a>
            </div>
        </div>

        <!-- Main Tabs -->
        <ul class="gf-tabs">
            <li :class="activeTab === 'basic' ? 'active' : ''" @click="activeTab = 'basic'"><a>Main</a></li>
            <li :class="[activeTab === 'container' ? 'active' : '', !saved ? 'disabled-tab' : '']" @click="saved ? activeTab = 'container' : null"><a>Container & Items</a></li>
            <li :class="[activeTab === 'charges' ? 'active' : '', !saved ? 'disabled-tab' : '']" @click="saved ? activeTab = 'charges' : null"><a>Charges</a></li>
            <li :class="[activeTab === 'history' ? 'active' : '', !saved ? 'disabled-tab' : '']" @click="saved ? activeTab = 'history' : null"><a>History</a></li>
            <li :class="[activeTab === 'filing' ? 'active' : '', !saved ? 'disabled-tab' : '']" @click="saved ? activeTab = 'filing' : null"><a>Filing</a></li>
        </ul>

        <div style="padding-bottom: 50px;">
            <!-- BASIC TAB -->
            <div x-show="activeTab === 'basic'" class="main-grid">
                <div class="portlet light">
                    <div @click="showMblSection = !showMblSection" class="portlet-title" style="cursor: pointer; background: #f9fafb;">
                        <span class="caption-subject"><i class="fa" :class="showMblSection ? 'fa-minus-square-o' : 'fa-plus-square-o'"></i> MB/L</span>
                        <div class="actions">
                            <i class="fa fa-angle-down transition-transform" :class="showMblSection ? 'rotate-180' : ''"></i>
                        </div>
                    </div>
                    <div class="portlet-body" x-show="showMblSection" x-collapse>
                        <!-- Reminder Section for MBL -->
                        <div class="memo-section" style="margin-bottom: 10px;">
                            <div class="memo-header" @click="showMblMemo = !showMblMemo">
                                <span>Note</span>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <button type="button" class="btn-memo-doc" @click.stop="showDocumentModal = true">Document (<span x-text="documents.length"></span>) <i class="fa fa-external-link"></i></button>
                                    <i class="fa" :class="showMblMemo ? 'fa-angle-up' : 'fa-angle-down'"></i>
                                </div>
                            </div>
                            <div class="memo-body" x-show="showMblMemo" x-collapse>
                                <div style="display: flex; gap: 10px;">
                                    <div style="flex: 2;">
                                        <table class="memo-table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 30px; background: #32c5d2; border: none; text-align: center; cursor: pointer;" @click="addMemo"><i class="fa fa-plus"></i></th>
                                                    <th><i class="fa fa-bell"></i> Subject</th>
                                                    <th>Last Modified</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="(memo, idx) in form.memos" :key="idx">
                                                    <tr :style="selectedMemoIndex === idx ? 'background: #f1f5f9; font-weight: bold;' : ''" @click="selectMemo(idx)" style="cursor: pointer;">
                                                        <td style="text-align: center;">
                                                            <i class="fa fa-sticky-note-o" style="font-size: 10px; color: #32c5d2;"></i>
                                                        </td>
                                                        <td x-text="memo.subject"></td>
                                                        <td x-text="memo.updated_at ? memo.updated_at.substring(0,10) : ''"></td>
                                                        <td style="text-align: center;">
                                                            <button type="button" @click.stop="deleteMemo(idx)" class="btn-tool-icon" style="color:red; border:none; background:none; padding:0; cursor:pointer;"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                </template>
                                                <template x-if="form.memos.length === 0">
                                                    <tr>
                                                        <td colspan="4" style="text-align: center; color: #999; padding: 10px;">No notes found. Click + to add one.</td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                    <template x-if="selectedMemoIndex !== null">
                                    <div style="flex: 1;">
                                        <div class="flex flex-col gap-1">
                                            <input type="text" class="form-control-gf" placeholder="Subject..." x-model="form.memos[selectedMemoIndex].subject" style="margin-bottom: 5px; font-weight: bold;">
                                            <textarea :name="'memos['+selectedMemoIndex+'][content]'" class="memo-content-area" placeholder="Note content..." x-model="form.memos[selectedMemoIndex].content" style="height: 100px;"></textarea>
                                            <input type="hidden" :name="'memos['+selectedMemoIndex+'][id]'" :value="form.memos[selectedMemoIndex].id">
                                            <input type="hidden" :name="'memos['+selectedMemoIndex+'][subject]'" :value="form.memos[selectedMemoIndex].subject">
                                        </div>
                                    </div>
                                    </template>
                                    <div style="flex: 1; display: flex; align-items: center; justify-content: center; border: 1px dashed #cbd5e1; border-radius: 4px; padding: 10px; color: #64748b;" x-show="selectedMemoIndex === null">
                                        Select a note to view/edit content.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-grid-4">
                            <!-- Column 1 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">File No.</label><div class="form-input-container"><input type="text" name="file_no" class="form-control-gf" x-model="form.file_no" readonly style="background:#f5f5f5;"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Post Date</label><div class="form-input-container"><input type="date" name="post_date" class="form-control-gf" x-model="form.post_date"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Forwarding Agent</label><div class="form-input-container"><x-inline-select name="forwarding_agent_id" :options="$agents" module="trade-partner" type="agent" x-model="form.forwarding_agent_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'forwarding_agent_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">OP</label><div class="form-input-container"><select name="op_id" class="form-control-gf" x-model="form.op_id">@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div></div>
                                <div class="form-group-gf" x-show="isDirectMaster"><label class="form-label-gf">Customer Ref.</label><div class="form-input-container"><input type="text" name="agent_ref_no" class="form-control-gf" x-model="form.agent_ref_no"></div></div>
                                <div class="form-group-gf" x-show="isDirectMaster"><label class="form-label-gf">Customer</label><div class="form-input-container"><x-inline-select name="dm_customer_id" :options="$agents" module="trade-partner" type="customer" x-model="form.dm_customer_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'dm_customer_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf" x-show="isDirectMaster"><label class="form-label-gf">Sales</label><div class="form-input-container"><select name="dm_sales_person_id" class="form-control-gf" x-model="form.dm_sales_person_id"><option value="">Select...</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div></div>
                            </div>

                            <!-- Column 2 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf" style="color:red;">* MB/L No.</label><div class="form-input-container"><input type="text" name="mbl_no" class="form-control-gf" x-model="form.mbl_no" required></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Oversea Agent</label><div class="form-input-container"><x-inline-select name="oversea_agent_id" :options="$agents" module="trade-partner" type="agent" x-model="form.oversea_agent_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'oversea_agent_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Co-loader</label><div class="form-input-container"><x-inline-select name="co_loader_id" :options="$agents" module="trade-partner" type="agent" x-model="form.co_loader_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'co_loader_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Contract No.</label><div class="form-input-container"><input type="text" name="contract_no" class="form-control-gf" x-model="form.contract_no"></div></div>
                                <div class="form-group-gf" x-show="isDirectMaster"><label class="form-label-gf">Shipper</label><div class="form-input-container"><x-inline-select name="dm_shipper_id" :options="$agents" module="trade-partner" type="shipper" x-model="form.dm_shipper_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'dm_shipper_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf" x-show="isDirectMaster"><label class="form-label-gf">Bill To</label><div class="form-input-container"><x-inline-select name="dm_bill_to_id" :options="$agents" module="trade-partner" type="customer" x-model="form.dm_bill_to_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'dm_bill_to_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                            </div>

                            <!-- Column 3 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf" style="color:red;">* Office</label><div class="form-input-container"><select name="office_id" class="form-control-gf" x-model="form.office_id" required><option value="">Select...</option>@foreach($offices as $office)<option value="{{ $office->id }}">{{ $office->code }}</option>@endforeach</select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Carrier</label><div class="form-input-container"><x-inline-select name="carrier_id" :options="$agents" module="trade-partner" type="carrier" x-model="form.carrier_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'carrier_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Agent Ref No.</label><div class="form-input-container"><input type="text" name="agent_ref_no" class="form-control-gf" x-model="form.agent_ref_no"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Direct Master</label><div class="form-input-container"><input type="checkbox" name="is_direct_master" value="1" x-model="isDirectMaster"></div></div>
                                <div class="form-group-gf" x-show="isDirectMaster"><label class="form-label-gf">Consignee</label><div class="form-input-container"><x-inline-select name="dm_consignee_id" :options="$agents" module="trade-partner" type="consignee" x-model="form.dm_consignee_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'dm_consignee_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf" x-show="isDirectMaster"><label class="form-label-gf">Sales Type</label><div class="form-input-container"><select name="sales_type" class="form-control-gf" x-model="form.sales_type"><option value="">Select...</option><option value="NORMAL">NORMAL</option><option value="CO-LOAD">CO-LOAD</option></select></div></div>
                            </div>

                            <!-- Column 4 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">B/L Type</label><div class="form-input-container"><select name="bl_type" class="form-control-gf" x-model="form.bl_type"><option value="">Select...</option><option value="NORMAL">NORMAL</option><option value="MEMO">MEMO</option><option value="SEA WAYBILL">SEA WAYBILL</option><option value="SURRENDERED">SURRENDERED</option><option value="TELEX">TELEX</option></select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Acct. Carrier</label><div class="form-input-container"><x-inline-select name="acct_carrier_id" :options="$agents" module="trade-partner" type="carrier" x-model="form.acct_carrier_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'acct_carrier_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Sub B/L No.</label><div class="form-input-container"><input type="text" name="sub_bl_no" class="form-control-gf" x-model="form.sub_bl_no"></div></div>
                                <div class="form-group-gf" style="height: 19px;"></div>
                                 <div class="form-group-gf" x-show="isDirectMaster"><label class="form-label-gf">Notify</label><div class="form-input-container"><x-inline-select name="dm_notify_id" :options="$agents" module="trade-partner" type="notify" x-model="form.dm_notify_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'dm_notify_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf" x-show="isDirectMaster"><label class="form-label-gf">Cargo Type</label><div class="form-input-container"><select name="cargo_type" class="form-control-gf" x-model="form.cargo_type"><option value="">Select...</option><option value="GENERAL CARGO">GENERAL CARGO</option><option value="HAZARDOUS">HAZARDOUS</option><option value="REEFER">REEFER</option><option value="DANGEROUS">DANGEROUS</option><option value="OVERSIZE">OVERSIZE</option></select></div></div>
                            </div>
                        </div>

                        <div style="height: 15px;"></div>

                        <div class="form-grid-4">
                            <!-- Column 1 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Vessel</label><div class="form-input-container"><select name="vessel_id" class="form-control-gf" x-model="form.vessel_id"><option value="">Select...</option>@foreach($vessels as $vessel)<option value="{{ $vessel->id }}">{{ $vessel->name ?? '' }}</option>@endforeach</select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Port of Loading</label><div class="form-input-container"><x-inline-select name="pol_id" :options="$ports" module="port" x-model="form.pol_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('port', 'pol_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Place of Delivery</label><div class="form-input-container"><x-inline-select name="del_id" :options="$ports" module="port" x-model="form.del_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('port', 'del_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                            </div>

                            <!-- Column 2 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Voyage</label><div class="form-input-container"><input type="text" name="voyage" class="form-control-gf" x-model="form.voyage"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ETD</label><div class="form-input-container"><input type="date" name="etd" class="form-control-gf" x-model="form.etd"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ETA</label><div class="form-input-container"><input type="date" name="eta" class="form-control-gf" x-model="form.eta"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ATD</label><div class="form-input-container"><input type="date" name="atd" class="form-control-gf" x-model="form.atd"></div></div>
                            </div>

                            <!-- Column 3 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">CY Location</label><div class="form-input-container"><x-inline-select name="cy_location_id" :options="$agents" module="trade-partner" type="location" x-model="form.cy_location_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'cy_location_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Port of Discharge</label><div class="form-input-container"><x-inline-select name="pod_id" :options="$ports" module="port" x-model="form.pod_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('port', 'pod_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Final Destination</label><div class="form-input-container"><x-inline-select name="fdest_id" :options="$ports" module="port" x-model="form.fdest_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('port', 'fdest_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ATA</label><div class="form-input-container"><input type="date" name="ata" class="form-control-gf" x-model="form.ata"></div></div>
                            </div>

                            <!-- Column 4 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">CFS Location</label><div class="form-input-container"><x-inline-select name="cfs_location_id" :options="$agents" module="trade-partner" type="location" x-model="form.cfs_location_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'cfs_location_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf" style="color:red;">* ETA</label><div class="form-input-container"><input type="date" name="eta" class="form-control-gf" x-model="form.eta" required></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Final ETA</label><div class="form-input-container"><input type="date" name="final_eta" class="form-control-gf" x-model="form.final_eta"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ETB</label><div class="form-input-container"><input type="date" name="etb" class="form-control-gf" x-model="form.etb"></div></div>
                            </div>
                        </div>

                        <div style="height: 15px;"></div>

                        <div class="form-grid-4">
                            <!-- Column 1 -->
                            <div class="space-y-[4px]">
                                <div class="form-group-gf"><label class="form-label-gf">Freight</label><div class="form-input-container"><select name="freight_term" class="form-control-gf" x-model="form.freight_term"><option value="Prepaid">Prepaid</option><option value="Collect">Collect</option></select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">OB/L Type</label><div class="form-input-container"><select name="obl_type" class="form-control-gf" x-model="form.obl_type"><option value="ORIGINAL BILL OF LADING">ORIGINAL BILL OF LADING</option><option value="SEA WAYBILL">SEA WAYBILL</option></select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Latest Gate In</label><div class="form-input-container"><input type="date" name="latest_gate_in" class="form-control-gf" x-model="form.latest_gate_in"> <i class="fa fa-calendar text-[10px] text-gray-400"></i></div></div>
                            </div>

                            <!-- Column 2 -->
                            <div class="space-y-[4px]">
                                <div class="form-group-gf"><label class="form-label-gf">Ship Mode</label><div class="form-input-container"><select name="ship_mode" class="form-control-gf" x-model="form.ship_mode"><option value="FCL">FCL</option><option value="LCL">LCL</option></select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf"><input type="checkbox" name="is_obl_received" value="1" x-model="form.is_obl_received" class="mr-1"> OB/L Received</label><div class="form-input-container"><input type="date" name="obl_received_date" class="form-control-gf" x-model="form.obl_received_date"> <i class="fa fa-calendar text-[10px] text-gray-400"></i></div></div>
                            </div>

                            <!-- Column 3 -->
                            <div class="space-y-[4px]">
                                <div class="form-group-gf"><label class="form-label-gf">Service Term</label><div class="form-input-container"><select name="service_term_from_id" class="form-control-gf" style="width: 45%;" x-model="form.service_term_from_id"><option value="">Select...</option>@foreach($serviceTerms as $st)<option value="{{ $st->id }}">{{ $st->code }}</option>@endforeach</select><span class="mx-1">~</span><select name="service_term_to_id" class="form-control-gf" style="width: 45%;" x-model="form.service_term_to_id"><option value="">Select...</option>@foreach($serviceTerms as $st)<option value="{{ $st->id }}">{{ $st->code }}</option>@endforeach</select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf"><input type="checkbox" name="is_released" value="1" x-model="form.is_released" class="mr-1"> Released Date</label><div class="form-input-container"><input type="date" name="released_date" class="form-control-gf" x-model="form.released_date"> <i class="fa fa-calendar text-[10px] text-gray-400"></i></div></div>
                            </div>

                            <!-- Column 4 -->
                            <div class="space-y-[4px]">
                                <div class="form-group-gf"><label class="form-label-gf">Container/Qty</label><div class="form-input-container"><input type="text" class="form-control-gf" :value="form.containers.length + ' Container(s)'" readonly></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Business Referred By</label><div class="form-input-container"><x-inline-select name="business_referred_by_id" :options="$agents" module="trade-partner" type="customer" x-model="form.business_referred_by_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'business_referred_by_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                            </div>
                        </div>

                        <div style="height: 5px;"></div>
                        <div style="margin-bottom: 10px;">
                            <button type="button" @click="showMore = !showMore" class="btn-default-gf" style="border:none; color:#4b77be; font-weight:700;">
                                <span x-text="showMore ? 'More [-]' : 'More [+]'"></span>
                            </button>
                        </div>

                        <div class="form-grid-4" x-show="showMore" x-transition>
                            <div class="form-group-gf"><label class="form-label-gf">Place of Receipt</label><div class="form-input-container"><x-inline-select name="receipt_id" :options="$ports" module="port" x-model="form.receipt_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('port', 'receipt_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                            <div class="form-group-gf"><label class="form-label-gf">Place of Receipt ETD</label><div class="form-input-container"><input type="date" name="receipt_etd" class="form-control-gf" x-model="form.receipt_etd"> <i class="fa fa-calendar text-[10px] text-gray-400"></i></div></div>
                            <div class="form-group-gf"><label class="form-label-gf">Return Location</label><div class="form-input-container"><x-inline-select name="return_location_id" :options="$agents" module="trade-partner" type="location" x-model="form.return_location_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'return_location_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                            <div class="form-group-gf"><label class="form-label-gf">E-Commerce</label><div class="form-input-container" style="justify-content: flex-start;"><input type="checkbox" name="is_ecommerce" value="1" x-model="form.is_ecommerce" style="width: 14px; height: 14px;"></div></div>
                            <div class="form-group-gf" style="grid-column: span 4;">
                                <label class="form-label-gf">Internal Remarks</label>
                                <div class="form-input-container">
                                    <textarea name="internal_remark" class="form-control-gf" x-model="form.internal_remark" style="height: 50px; resize: vertical;"></textarea>
                                </div>
                            </div>
                        </div>

                        </div>
                    </div>

                <!-- House B/L (HB/L) Section -->
                <template x-for="(hbl, index) in hbls" :key="index">
                    <div class="portlet light" style="margin-top: 5px;">
                        <div class="portlet-title" style="background: #f2bc00; color: #fff; cursor: pointer; min-height: 24px; padding: 2px 10px;" @click="hbl.show = !hbl.show">
                            <span class="caption-subject" style="color: #fff; font-size: 11px;"><i class="fa fa-file-text-o"></i> HB/L Information <small style="color:rgba(255,255,255,0.8); margin-left: 10px; font-weight: normal;">OP : <span x-text="getUserName(form.op_id)"></span></small></span>
                            <div class="actions" style="display: flex; gap: 10px; align-items: center;">
                                <i @click.stop="removeHbl(index)" class="fa fa-times" style="font-size: 12px; opacity: 0.8; cursor: pointer;"></i>
                                <i class="fa fa-angle-down transition-transform" :class="hbl.show ? 'rotate-180' : ''" style="font-size: 12px;"></i>
                            </div>
                        </div>
                        <div class="portlet-body" x-show="hbl.show" x-collapse>
                            <!-- Reminder Section for HBL -->
                            <div class="memo-section" style="margin-bottom: 10px;">
                                <div class="memo-header" @click="hbl.showMemo = !hbl.showMemo">
                                    <span>HBL Note / Remark</span>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <button type="button" class="btn-memo-doc" @click.stop="showDocumentModal = true">Document (<span x-text="documents.length"></span>) <i class="fa fa-external-link"></i></button>
                                        <i class="fa" :class="hbl.showMemo ? 'fa-angle-up' : 'fa-angle-down'"></i>
                                    </div>
                                </div>
                                <div class="memo-body" x-show="hbl.showMemo" x-collapse>
                                    <div class="flex flex-col">
                                        <textarea :name="'hbls['+index+'][hbl_remark]'" class="memo-content-area" placeholder="HBL remark..." x-model="hbl.hbl_remark" style="height: 80px; width: 100%; border: 1px solid #cbd5e1; border-radius: 4px; padding: 8px; font-family: sans-serif; font-size: 11px;"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-grid-4">
                                <!-- Column 1: Basic -->
                                <div class="flex flex-col">
                                    <input type="hidden" :name="'hbls['+index+'][id]'" :value="hbl.id">
                                    <div class="form-group-gf"><label class="form-label-gf" style="color:red;">*HB/L No.</label><div class="form-input-container"><input type="text" :name="'hbls['+index+'][hbl_no]'" class="form-control-gf" x-model="hbl.hbl_no" required></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Quotation No.</label><div class="form-input-container"><select :name="'hbls['+index+'][quotation_no]'" class="form-control-gf" x-model="hbl.quotation_no"><option value="">Select...</option>@foreach($quotations as $q)<option value="{{ $q->quote_no }}">{{ $q->quote_no }}</option>@endforeach</select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Customer</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls['+index+'][customer_id]'" :options="$agents" module="trade-partner" type="customer" x-model="hbl.customer_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'hbls['+index+'][customer_id]')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Sales</label><div class="form-input-container"><select :name="'hbls['+index+'][sales_person_id]'" class="form-control-gf" x-model="hbl.sales_person_id"><option value="">Select...</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div></div>
                                    <div style="height: 5px;"></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Customs Broker</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls['+index+'][customs_broker_id]'" :options="$agents" module="trade-partner" type="agent" x-model="hbl.customs_broker_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'hbls['+index+'][customs_broker_id]')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Place of Delivery</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls['+index+'][del_id]'" :options="$ports" module="port" x-model="hbl.del_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('port', 'hbls['+index+'][del_id]')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Delivery Loc.</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls['+index+'][delivery_location_id]'" :options="$agents" module="trade-partner" type="cfs" x-model="hbl.delivery_location_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'hbls['+index+'][delivery_location_id]')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Rail</label><div class="form-input-container"><input type="checkbox" :name="'hbls['+index+'][is_rail]'" value="1" x-model="hbl.is_rail"> <select :name="'hbls['+index+'][pre_carriage_by]'" class="form-control-gf" x-model="hbl.pre_carriage_by"><option value="">Select...</option>@foreach($agents as $agent)<option value="{{ $agent->name }}">{{ $agent->name }}</option>@endforeach</select></div></div>
                                </div>

                                <!-- Column 2: Shipper Context -->
                                <div class="flex flex-col">
                                    <div class="form-group-gf"><label class="form-label-gf">Shipper</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls['+index+'][shipper_id]'" :options="$agents" module="trade-partner" type="shipper" x-model="hbl.shipper_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'hbls['+index+'][shipper_id]')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Date of Issue</label><div class="form-input-container"><input type="date" :name="'hbls['+index+'][date_of_issue]'" class="form-control-gf" x-model="hbl.date_of_issue"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Place of Discharge</label><div class="form-input-container"><select :name="'hbls['+index+'][pod_id]'" class="form-control-gf" x-model="hbl.pod_id"><option value="">Select...</option>@foreach($ports as $port)<option value="{{ $port->id }}">{{ $port->name ?? '' }}</option>@endforeach</select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf" style="margin-right: 15px;">PRE-CARRIAGE BY</label><div class="form-input-container"><input type="text" :name="'hbls['+index+'][pre_carriage_by]'" class="form-control-gf" x-model="hbl.pre_carriage_by"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">VESSEL</label><div class="form-input-container"><input type="text" :name="'hbls['+index+'][vessel_name]'" class="form-control-gf" x-model="hbl.vessel_name"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Service Term</label><div class="form-input-container"><select :name="'hbls['+index+'][service_term]'" class="form-control-gf" x-model="hbl.service_term"><option value="">Select...</option>@foreach($serviceTerms as $st)<option value="{{ $st->code }}">{{ $st->code }}</option>@endforeach</select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">SHIP TYPE</label><div class="form-input-container"><select :name="'hbls['+index+'][ship_type]'" class="form-control-gf" x-model="hbl.ship_type"><option value="">Select...</option><option value="FCL">FCL</option><option value="LCL">LCL</option><option value="FCL/LCL">FCL/LCL</option><option value="LCL/FCL">LCL/FCL</option></select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Released By</label><div class="form-input-container"><select :name="'hbls['+index+'][freight_released_by_id]'" class="form-control-gf" x-model="hbl.freight_released_by_id"><option value="">Select...</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div></div>
                                </div>

                                <!-- Column 3: Consignee Context -->
                                <div class="flex flex-col">
                                    <div class="form-group-gf"><label class="form-label-gf">Consignee</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls['+index+'][consignee_id]'" :options="$agents" module="trade-partner" type="consignee" x-model="hbl.consignee_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'hbls['+index+'][consignee_id]')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Place of Receipt</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls['+index+'][pol_id]'" :options="$ports" module="port" x-model="hbl.pol_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('port', 'hbls['+index+'][pol_id]')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Place of Delivery</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls['+index+'][fdest_id]'" :options="$ports" module="port" x-model="hbl.fdest_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('port', 'hbls['+index+'][fdest_id]')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">VOYAGE NO</label><div class="form-input-container"><input type="text" :name="'hbls['+index+'][voyage_no]'" class="form-control-gf" x-model="hbl.voyage_no"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">L/C No</label><div class="form-input-container"><input type="text" :name="'hbls['+index+'][lc_no]'" class="form-control-gf" x-model="hbl.lc_no"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">CARGO TYPE</label><div class="form-input-container"><select :name="'hbls['+index+'][cargo_type]'" class="form-control-gf" x-model="hbl.cargo_type"><option value="">Select...</option><option value="GENERAL">GENERAL</option><option value="HAZARDOUS">HAZARDOUS</option><option value="REEFER">REEFER</option><option value="DANGEROUS">DANGEROUS</option><option value="OVERSIZE">OVERSIZE</option></select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">DO Sent</label><div class="form-input-container"><input type="checkbox" :name="'hbls['+index+'][is_do_sent]'" value="1" x-model="hbl.is_do_sent"> <input type="date" :name="'hbls['+index+'][do_sent_date]'" class="form-control-gf" x-model="hbl.do_sent_date"></div></div>
                                </div>

                                <!-- Column 4: Notify Party Context -->
                                <div class="flex flex-col">
                                    <div class="form-group-gf"><label class="form-label-gf">Notify Party</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls['+index+'][notify_party_id]'" :options="$agents" module="trade-partner" type="notify" x-model="hbl.notify_party_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'hbls['+index+'][notify_party_id]')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Place of Loading</label><div class="form-input-container"><select :name="'hbls['+index+'][receipt_id]'" class="form-control-gf" x-model="hbl.receipt_id"><option value="">Select...</option>@foreach($ports as $port)<option value="{{ $port->id }}">{{ $port->name ?? '' }}</option>@endforeach</select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Final Destination</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls['+index+'][fdest_id]'" :options="$ports" module="port" x-model="hbl.fdest_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('port', 'hbls['+index+'][fdest_id]')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">FREIGHT PAYABLE AT</label><div class="form-input-container"><input type="text" :name="'hbls['+index+'][freight_payable_at]'" class="form-control-gf" x-model="hbl.freight_payable_at"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">INCOTERMS</label><div class="form-input-container"><select :name="'hbls['+index+'][incoterms_id]'" class="form-control-gf" x-model="hbl.incoterms_id"><option value="">Select...</option>@foreach($incoterms as $inco)<option value="{{ $inco->code }}">{{ $inco->code }} - {{ $inco->name }}</option>@endforeach</select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">S/C No</label><div class="form-input-container"><input type="text" :name="'hbls['+index+'][sc_no]'" class="form-control-gf" x-model="hbl.sc_no"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">SHIP MODE</label><div class="form-input-container"><select :name="'hbls['+index+'][ship_mode]'" class="form-control-gf" x-model="hbl.ship_mode"><option value="">Select...</option><option value="FCL">FCL</option><option value="LCL">LCL</option></select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">CY/CFS Loc.</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls['+index+'][cfs_location_id]'" :options="$agents" module="trade-partner" type="cfs" x-model="hbl.cfs_location_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'hbls['+index+'][cfs_location_id]')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                </div>
                            </div>

                            <div style="height: 15px;"></div>

                            <div class="form-grid-4">
                                <!-- Column 1 -->
                                <div class="flex flex-col">
                                    <div class="form-group-gf"><label class="form-label-gf">Express B/L</label><div class="form-input-container" style="font-size:9px;"><input type="radio" :name="'hbls['+index+'][is_express_bl]'" value="1" x-model="hbl.is_express_bl"> Yes <input type="radio" :name="'hbls['+index+'][is_express_bl]'" value="0" x-model="hbl.is_express_bl"> No</div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Door Move</label><div class="form-input-container" style="font-size:9px;"><input type="checkbox" :name="'hbls['+index+'][is_door_move]'" value="1" x-model="hbl.is_door_move"> Door Move &nbsp; <input type="checkbox" :name="'hbls['+index+'][is_customs_clear]'" value="1" x-model="hbl.is_customs_clear"> C.Clear &nbsp; <input type="checkbox" :name="'hbls['+index+'][is_customs_hold]'" value="1" x-model="hbl.is_customs_hold"> C.Hold</div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Referred By</label><div class="form-input-container"><x-inline-select name="" x-bind:name="'hbls['+index+'][referred_by_id]'" :options="$agents" module="trade-partner" type="customer" x-model="hbl.referred_by_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'hbls['+index+'][referred_by_id]')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf"><input type="checkbox" :name="'hbls['+index+'][is_obl_received]'" value="1" x-model="hbl.is_obl_received"> OB/L Recv.</label><div class="form-input-container"><input type="date" :name="'hbls['+index+'][obl_received_date]'" class="form-control-gf" x-model="hbl.obl_received_date"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf"><input type="checkbox" :name="'hbls['+index+'][is_fr_released]'" value="1" x-model="hbl.is_fr_released"> FR Released</label><div class="form-input-container"><input type="date" :name="'hbls['+index+'][fr_released_date]'" class="form-control-gf" x-model="hbl.fr_released_date"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf"><input type="checkbox" :name="'hbls['+index+'][is_an_sent]'" value="1" x-model="hbl.is_an_sent"> AN Sent</label><div class="form-input-container"><input type="date" :name="'hbls['+index+'][an_sent_date]'" class="form-control-gf" x-model="hbl.an_sent_date"></div></div>
                                </div>
                                <div class="flex flex-col"></div>
                                <div class="flex flex-col"></div>
                                <div class="flex flex-col">
                                    <div style="flex-grow: 1;"></div>
                                    <div class="form-group-gf" style="justify-content: flex-end; margin-top: 10px;"><button type="button" @click="hbl.showMore = !hbl.showMore" class="btn-default-gf" style="border:none; color:#00827f; font-weight:700; height:18px; padding:0;">More <i class="fa" :class="hbl.showMore ? 'fa-minus-square' : 'fa-plus-square'"></i></button></div>
                                </div>
                            </div>

                            <!-- More Section for HBL -->
                            <div x-show="hbl.showMore" x-transition style="margin-top: 5px; padding-top: 5px; border-top: 1px solid #eee;">
                                <div class="form-grid-4">
                                    <div class="form-group-gf"><label class="form-label-gf">Name Account</label><div class="form-input-container"><input type="text" :name="'hbls['+index+'][name_account]'" class="form-control-gf" x-model="hbl.name_account"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Group Comm</label><div class="form-input-container"><input type="text" :name="'hbls['+index+'][group_comm]'" class="form-control-gf" x-model="hbl.group_comm"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Line Code</label><div class="form-input-container"><input type="text" :name="'hbls['+index+'][line_code]'" class="form-control-gf" x-model="hbl.line_code"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">E-Commerce</label><div class="form-input-container"><input type="checkbox" :name="'hbls['+index+'][is_ecommerce]'" value="1" x-model="hbl.is_ecommerce"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Customs Doc</label><div class="form-input-container"><input type="checkbox" :name="'hbls['+index+'][is_customs_doc]'" value="1" x-model="hbl.is_customs_doc"></div></div>
                                </div>
                            </div>
                        </div>
                        </div>
                </template>

                <div class="flex justify-end" style="margin-top: 5px;">
                    <button type="button" @click="addHbl" class="btn-gofreight" style="background:#f2bc00; padding: 4px 15px; font-size: 11px; border-radius: 2px;"><i class="fa fa-plus"></i> ADD HB/L</button>
                </div>
            </div>

            <!-- CONTAINER & ITEMS TAB -->
            <div x-show="activeTab === 'container'" class="main-grid">
                <div class="portlet light">
                    <div class="portlet-title">
                        <span class="caption-subject"><i class="fa fa-cube"></i> Container List</span>
                    </div>
                    <div class="portlet-body">
                        <div class="container-toolbar">
                            <div style="display: flex; gap: 4px; align-items: center;">
                                <button type="button" @click="addContainer()" class="btn-tool" title="Add Row"><i class="fa fa-plus"></i> Add Row</button>
                                <button type="button" @click="addContainer(5)" class="btn-tool" style="background:#64748b; color:#fff;" title="Add 5 Rows"><i class="fa fa-plus"></i> Add 5 Rows</button>
                                <button type="button" @click="addBulkContainers" class="btn-tool-icon" title="Add Bulk"><i class="fa fa-plus-square"></i></button>
                                <button type="button" @click="duplicateSelectedContainers" class="btn-tool-icon" title="Duplicate"><i class="fa fa-copy"></i></button>
                                <button type="button" @click="deleteSelectedContainers" class="btn-tool-icon" style="color:red; border-color:red;" title="Delete Selected"><i class="fa fa-trash"></i></button>
                            </div>
                            <div style="display: flex; gap: 4px; margin-left: 10px;">
                                <button type="button" @click="$refs.importFileInput.click()" class="btn-tool"><i class="fa fa-cloud-upload"></i> Import Container</button>
                                <input type="file" x-ref="importFileInput" style="display:none;" @change="handleContainerImport" accept=".csv,.txt">
                                <button type="button" @click="createApFromContainers" class="btn-tool-outline">Create A/P <i class="fa fa-angle-down"></i></button>
                                <button type="button" @click="copyDataFromAllHbl" class="btn-tool-outline" style="color:#4b77be; border-color:#4b77be;">Copy Data from All HB/L</button>
                                <button type="button" @click="showClipboardModal = true" class="btn-tool-outline">Container info to clipboard <i class="fa fa-external-link"></i></button>
                            </div>
                            <div style="margin-left: auto;">
                                <button type="button" class="btn-tool-secondary" style="background: #9b59b6;" onclick="window.location.href='{{ isset($oceanImport) ? route('ocean-import.containers.export', $oceanImport->id) : '#' }}'"><i class="fa fa-sign-out"></i></button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="container-table">
                                <colgroup>
                                    <col style="width: 30px;">
                                    <col style="width: 30px;">
                                    <col style="width: 60px;">
                                    <col style="width: 160px;">
                                    <col style="width: 80px;">
                                    <col style="width: 100px;">
                                    <col style="width: 100px;">
                                    <col style="width: 100px;">
                                    <col style="width: 120px;">
                                    <col style="width: 100px;">
                                    <col style="width: 100px;">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" @change="toggleAllContainers"></th>
                                        <th>#</th>
                                        <th>PP/CTF</th>
                                        <th>Container No.</th>
                                        <th>TP/SZ</th>
                                        <th>Seal No.</th>
                                        <th>LFD</th>
                                        <th>FDD</th>
                                        <th>
                                            <div class="header-split">
                                                <div class="header-top">PKG</div>
                                                <div class="header-bottom">CARTON(S)</div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="header-split">
                                                <div class="header-top">Weight</div>
                                                <div class="header-bottom">KG</div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="header-split">
                                                <div class="header-top" style="font-size:9px;">Measurement</div>
                                                <div class="header-bottom">CBM</div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <template x-for="(cont, idx) in form.containers" :key="idx">
                                    <tbody style="border:none;">
                                        <tr class="row-main">
                                            <input type="hidden" :name="'containers['+idx+'][id]'" :value="cont.id">
                                            <td style="width:30px;"><input type="checkbox" x-model="cont.selected" style="display:block; margin:auto;"></td>
                                            <td style="width:30px; text-align:center;">
                                                <div class="flex items-center justify-center gap-1">
                                                    <i @click.stop="cont.expanded = !cont.expanded" class="fa cursor-pointer text-gray-400 hover:text-blue-500" :class="cont.expanded ? 'fa-minus-square' : 'fa-plus-square'" style="font-size:12px;"></i>
                                                    <span x-text="idx + 1" style="font-weight:bold;"></span>
                                                </div>
                                            </td>
                                            <td style="width:60px;"><input type="text" :name="'containers['+idx+'][pp_ctf]'" class="form-control-gf" x-model="cont.pp_ctf"></td>
                                            <td style="width:160px;">
                                                <input type="text" :name="'containers['+idx+'][container_no]'" class="form-control-gf" x-model="cont.container_no">
                                            </td>
                                            <td style="width:80px;"><select :name="'containers['+idx+'][container_type_id]'" class="form-control-gf" x-model="cont.container_type_id"><option value="">Select...</option>@foreach($containerTypes as $ct)<option value="{{ $ct->id }}">{{ $ct->code }}</option>@endforeach</select></td>
                                            <td style="width:100px;"><input type="text" :name="'containers['+idx+'][seal_no]'" class="form-control-gf" x-model="cont.seal_no"></td>
                                            <td style="width:100px;"><input type="date" :name="'containers['+idx+'][lfd]'" class="form-control-gf" x-model="cont.lfd"></td>
                                            <td style="width:100px;"><input type="date" :name="'containers['+idx+'][fdd]'" class="form-control-gf" x-model="cont.fdd"></td>
                                            <td style="width:120px;"><input type="number" :name="'containers['+idx+'][pkg_qty]'" class="form-control-gf" x-model="cont.pkg_qty" style="text-align:right;"></td>
                                            <td style="width:100px;"><input type="number" :name="'containers['+idx+'][weight_kg]'" class="form-control-gf" x-model="cont.weight_kg" step="0.01" style="text-align:right;"></td>
                                            <td style="width:100px;"><input type="number" :name="'containers['+idx+'][measure_cbm]'" class="form-control-gf" x-model="cont.measure_cbm" step="0.01" style="text-align:right;"></td>
                                        </tr>
                                        <tr x-show="cont.expanded" x-cloak class="expanded-row">
                                            <td colspan="2" style="border-right: 1px solid #dcdcdc; background:#fff !important;"></td>
                                            <td colspan="9">
                                                <div class="expanded-container">
                                                    <!-- Group 1: Occupies space of Col 3-4 (60+160 = 220px) -->
                                                    <div class="expanded-col" style="width: 220px;">
                                                        <div class="form-group-gf"><label class="form-label-gf">Seal No2.</label><div class="form-input-container"><input type="text" :name="'containers['+idx+'][seal_no2]'" class="form-control-gf" x-model="cont.seal_no2"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf">Pick Up No.</label><div class="form-input-container"><input type="text" :name="'containers['+idx+'][pickup_no]'" class="form-control-gf" x-model="cont.pickup_no"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf">CPRS No.</label><div class="form-input-container"><input type="text" :name="'containers['+idx+'][cprs_no]'" class="form-control-gf" x-model="cont.cprs_no"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf">CNRU No.</label><div class="form-input-container"><input type="text" :name="'containers['+idx+'][cnru_no]'" class="form-control-gf" x-model="cont.cnru_no"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf">IT No.</label><div class="form-input-container"><input type="text" :name="'containers['+idx+'][it_no]'" class="form-control-gf" x-model="cont.it_no"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf">D.G</label><div class="form-input-container"><select :name="'containers['+idx+'][is_dg]'" class="form-control-gf" x-model="cont.is_dg"><option value="0">No</option><option value="1">Yes</option></select></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf">Storage Start</label><div class="form-input-container"><input type="date" :name="'containers['+idx+'][storage_start_date]'" class="form-control-gf" x-model="cont.storage_start_date"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf">Storage End</label><div class="form-input-container"><input type="date" :name="'containers['+idx+'][storage_end_date]'" class="form-control-gf" x-model="cont.storage_end_date"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf">Weight LB</label><div class="form-input-container"><input type="text" :name="'containers['+idx+'][weight_lb]'" class="form-control-gf" x-model="cont.weight_lb"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf">Measure CFT</label><div class="form-input-container"><input type="text" :name="'containers['+idx+'][measure_cft]'" class="form-control-gf" x-model="cont.measure_cft"></div></div>
                                                        <div class="mt-2">
                                                            <div class="text-[9px] font-bold text-gray-500">Remarks</div>
                                                            <textarea :name="'containers['+idx+'][remarks]'" class="form-control-gf" x-model="cont.remarks"></textarea>
                                                        </div>
                                                        <div class="mt-1">
                                                            <div class="text-[9px] font-bold text-gray-500">Internal Remarks</div>
                                                            <textarea :name="'containers['+idx+'][internal_remarks]'" class="form-control-gf" x-model="cont.internal_remarks"></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- Group 2: Occupies space of Col 5-6 (80+100 = 180px) -->
                                                    <div class="expanded-col" style="width: 180px;">
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">Carrier rel.</label><div class="form-input-container"><input type="checkbox" :name="'containers['+idx+'][is_carrier_release]'" x-model="cont.is_carrier_release" style="width:12px;height:12px;"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">Yard Loc.</label><div class="form-input-container"><input type="text" :name="'containers['+idx+'][yard_location]'" class="form-control-gf" x-model="cont.yard_location"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">Unload Vessel</label><div class="form-input-container"><input type="date" :name="'containers['+idx+'][unload_vessel_date]'" class="form-control-gf" x-model="cont.unload_vessel_date"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">Gate In</label><div class="form-input-container"><input type="date" :name="'containers['+idx+'][gate_in_date]'" class="form-control-gf" x-model="cont.gate_in_date"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">Rail Start</label><div class="form-input-container"><input type="date" :name="'containers['+idx+'][rail_start_date]'" class="form-control-gf" x-model="cont.rail_start_date"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">P.O.D ETA</label><div class="form-input-container"><input type="date" :name="'containers['+idx+'][pod_eta]'" class="form-control-gf" x-model="cont.pod_eta"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">Avail Pickup</label><div class="form-input-container"><input type="checkbox" :name="'containers['+idx+'][is_avail_pickup]'" x-model="cont.is_avail_pickup" style="width:12px;height:12px;"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">Appt.</label><div class="form-input-container"><input type="date" :name="'containers['+idx+'][appointment_date]'" class="form-control-gf" x-model="cont.appointment_date"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">Trucker</label><div class="form-input-container"><select :name="'containers['+idx+'][trucker_id]'" class="form-control-gf" x-model="cont.trucker_id"><option value="">Select...</option>@foreach($agents as $agent)<option value="{{ $agent->id }}">{{ $agent->name }}</option>@endforeach</select></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">Pick Up</label><div class="form-input-container"><input type="date" :name="'containers['+idx+'][pickup_date]'" class="form-control-gf" x-model="cont.pickup_date"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">Gate Out</label><div class="form-input-container"><input type="date" :name="'containers['+idx+'][gate_out_date]'" class="form-control-gf" x-model="cont.gate_out_date"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">F.Dest ETA</label><div class="form-input-container"><input type="date" :name="'containers['+idx+'][fdest_eta]'" class="form-control-gf" x-model="cont.fdest_eta"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">ETA Door</label><div class="form-input-container"><input type="date" :name="'containers['+idx+'][eta_door]'" class="form-control-gf" x-model="cont.eta_door"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">ATA Door</label><div class="form-input-container"><input type="date" :name="'containers['+idx+'][ata_door]'" class="form-control-gf" x-model="cont.ata_door"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">Empty Conf.</label><div class="form-input-container"><input type="date" :name="'containers['+idx+'][empty_conf_date]'" class="form-control-gf" x-model="cont.empty_conf_date"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">Empty Ret.</label><div class="form-input-container"><input type="date" :name="'containers['+idx+'][empty_ret_date]'" class="form-control-gf" x-model="cont.empty_ret_date"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">Chassis days</label><div class="form-input-container"><input type="number" :name="'containers['+idx+'][chassis_days]'" class="form-control-gf" x-model="cont.chassis_days" step="0.1"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">C.Hold</label><div class="form-input-container"><input type="checkbox" :name="'containers['+idx+'][is_customs_hold]'" value="1" x-model="cont.is_customs_hold" style="width:12px;height:12px;"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">A/N</label><div class="form-input-container" style="gap:2px;"><input type="checkbox" :name="'containers['+idx+'][is_an_sent]'" value="1" x-model="cont.is_an_sent" style="width:12px;height:12px;flex-shrink:0;"><input type="date" :name="'containers['+idx+'][an_sent_date]'" class="form-control-gf" x-model="cont.an_sent_date" style="flex:1;"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">D/O</label><div class="form-input-container" style="gap:2px;"><input type="checkbox" :name="'containers['+idx+'][is_do_sent]'" value="1" x-model="cont.is_do_sent" style="width:12px;height:12px;flex-shrink:0;"><input type="date" :name="'containers['+idx+'][do_sent_date]'" class="form-control-gf" x-model="cont.do_sent_date" style="flex:1;"></div></div>
                                                        <div class="form-group-gf"><label class="form-label-gf" style="width:65px;">Complete</label><div class="form-input-container"><input type="checkbox" :name="'containers['+idx+'][is_complete]'" x-model="cont.is_complete" style="width:12px;height:12px;"></div></div>
                                                    </div>
                                                    <!-- Group 3: Occupies space of Col 7-11 (100+100+120+100+100 = 520px) -->
                                                    <div class="expanded-col flex-1" style="background: #fff; border-right:none;">
                                                        <div class="hbl-header">HB/L No.</div>
                                                        <div style="border: 1px solid #eee; min-height: 50px; background: #fff; padding: 4px;">
                                                            <template x-for="h in hbls" :key="h.id || h.hbl_no">
                                                                <div x-show="(h.containers || []).some(c => c.container_no === cont.container_no)" style="padding: 2px 4px; font-size: 10px; border-bottom: 1px solid #eee;">
                                                                    <span x-text="h.hbl_no" style="font-weight: 600; color: #3b82f6;"></span>
                                                                </div>
                                                            </template>
                                                            <template x-if="!hbls.some(h => (h.containers || []).some(c => c.container_no === cont.container_no))">
                                                                <div style="padding: 10px; text-align: center; color: #94a3b8; font-size: 10px;">No HBL assigned</div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </template>
                                <tfoot>
                                    <tr class="total-row">
                                        <td colspan="6" style="border:none;"></td>
                                        <td colspan="2" class="total-label-cell">Total</td>
                                        <td class="total-val-cell"><span x-text="calculateTotal('pkg_qty')"></span></td>
                                        <td class="total-val-cell"><span x-text="calculateTotal('weight_kg').toFixed(2)"></span></td>
                                        <td class="total-val-cell"><span x-text="calculateTotal('measure_cbm').toFixed(2)"></span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px; margin-top: 5px; font-size: 11px;">
                            <span style="color:#333;">Total</span>
                            <div class="flex items-center gap-1">
                                <input type="checkbox" id="input-total-new" x-model="inputTotalMode">
                                <label for="input-total-new" style="font-weight:normal; color:#555;">Input total number</label>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: flex-end; margin-top: 5px; align-items: center; gap: 10px;">
                            <label class="form-label-gf">Display Unit</label>
                            <select name="display_unit" class="form-control-gf" style="width: 150px;" x-model="form.display_unit"><option value="both">Show Both</option><option value="revenue">Revenue</option><option value="cost">Cost</option></select>
                        </div>

                        <div style="display: flex; gap: 20px; margin-top: 15px;">
                            <div style="flex: 1;">
                                <label class="caption-subject" style="font-size: 11px; margin-bottom: 5px; display: block;">Mark</label>
                                <textarea name="mark" class="form-control-gf" style="height: 80px;" x-model="form.mark"></textarea>
                            </div>
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                    <label class="caption-subject" style="font-size: 11px;">Description</label>
                                    <button type="button" @click="copyDescriptionFromAllHbl" class="btn-tool" style="padding: 2px 8px; font-size: 10px;">Copy from All HB/L</button>
                                </div>
                                <textarea name="description" class="form-control-gf" style="height: 80px;" x-model="form.description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HBL Containers & Items Sections -->
                <template x-for="(hbl, hblIdx) in hbls" :key="hblIdx">
                    <div class="portlet light" style="margin-top: 15px; border: 1px solid #f2bc00;">
                        <!-- Theme Header Bar -->
                        <div class="portlet-title" style="background: #f2bc00; color: #fff; cursor: pointer; min-height: 28px; padding: 4px 10px; display: flex; justify-content: space-between; align-items: center;" @click="hbl.show = !hbl.show">
                            <span class="caption-subject" style="color: #fff; font-size: 12px; font-weight: bold;">
                                <i class="fa fa-cube"></i> HB/L: <span x-text="hbl.hbl_no || 'Draft HBL'"></span> | Containers & Items
                            </span>
                            <div class="actions" style="display: flex; gap: 10px; align-items: center;">
                                <i class="fa fa-angle-down" :class="hbl.show ? 'rotate-180' : ''" style="font-size: 14px; color: #fff; transition: transform 0.2s;"></i>
                            </div>
                        </div>

                        <div class="portlet-body" x-show="hbl.show" x-collapse style="padding: 12px;">
                            <!-- Customer Reference / P.O. No. -->
                            <div class="flex justify-between items-center" style="margin-bottom: 12px; gap: 20px;">
                                <div style="flex: 1;">
                                    <label class="form-label-gf" style="font-weight: bold; margin-bottom: 4px; display: block;">Customer Reference / P.O. No. <span style="font-weight: normal; color: #666;">(Please list down P.O. No. for this HB/L)</span></label>
                                    <input type="text" :name="'hbls['+hblIdx+'][po_no]'" class="form-control-gf" placeholder="Add P.O. here..." x-model="hbl.po_no" style="width: 100%; height: 22px;">
                                </div>
                                <div style="width: 200px; text-align: right;">
                                    <span style="font-weight: bold; font-size: 11px; display: block; margin-bottom: 4px;">P.O. Mapping</span>
                                    <div class="flex gap-4 justify-end" style="font-size: 11px;">
                                        <label class="flex items-center gap-1 cursor-pointer" style="font-weight: normal;">
                                            <input type="radio" :name="'hbls['+hblIdx+'][po_mapping_type]'" value="container" x-model="hbl.po_mapping_type"> Container based
                                        </label>
                                        <label class="flex items-center gap-1 cursor-pointer" style="font-weight: normal;">
                                            <input type="radio" :name="'hbls['+hblIdx+'][po_mapping_type]'" value="item" x-model="hbl.po_mapping_type"> Item based
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Container List -->
                            <div style="margin-bottom: 15px;">
                                <div class="flex justify-between items-center" style="margin-bottom: 6px;">
                                    <span style="font-weight: bold; font-size: 12px; color: #333;">Container List</span>
                                    <button type="button" @click="copyContainersFromMbl(hbl)" class="btn-tool-outline" style="color:#f2bc00; border-color:#f2bc00; padding: 2px 10px; font-size: 11px;">
                                        <i class="fa fa-copy"></i> Copy Value from MB/L
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="container-table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th style="width: 40px;">#</th>
                                                <th>Container No.</th>
                                                <th style="width: 150px;">PKG</th>
                                                <th style="width: 150px;">Weight</th>
                                                <th style="width: 150px;">Measurement</th>
                                                <th x-show="hbl.po_mapping_type === 'container'">P.O. No.</th>
                                                <th style="width: 40px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(c, cIdx) in hbl.containers" :key="cIdx">
                                                <tr>
                                                    <td style="text-align: center; font-weight: bold;" x-text="cIdx + 1"></td>
                                                    <td>
                                                        <select class="form-control-gf" x-model="c.container_no" :name="c.container_no !== 'MANUAL' ? 'hbls['+hblIdx+'][containers]['+cIdx+'][container_no]' : ''">
                                                            <option value="">Select MBL Container...</option>
                                                            <template x-for="mblC in form.containers">
                                                                <option :value="mblC.container_no" x-text="mblC.container_no"></option>
                                                            </template>
                                                            <option value="MANUAL">+ Enter Manual</option>
                                                        </select>
                                                        <input type="text" class="form-control-gf mt-1" placeholder="Enter Container No." x-show="c.container_no === 'MANUAL'" x-model="c.manual_container_no" :name="c.container_no === 'MANUAL' ? 'hbls['+hblIdx+'][containers]['+cIdx+'][container_no]' : ''">
                                                    </td>
                                                    <td>
                                                        <div class="flex gap-1">
                                                            <input type="number" class="form-control-gf" style="width: 60px; text-align: right;" x-model="c.pkg_qty" :name="'hbls['+hblIdx+'][containers]['+cIdx+'][pkg_qty]'">
                                                            <select class="form-control-gf" style="flex: 1;" x-model="c.pkg_unit" :name="'hbls['+hblIdx+'][containers]['+cIdx+'][pkg_unit]'">
                                                                @foreach($packageUnits as $pu)
                                                                    <option value="{{ $pu->code }}">{{ $pu->code }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="flex gap-1">
                                                            <input type="number" class="form-control-gf" style="width: 60px; text-align: right;" step="0.01" x-model="c.weight_kg" :name="'hbls['+hblIdx+'][containers]['+cIdx+'][weight_kg]'">
                                                            <select class="form-control-gf" style="flex: 1;" x-model="c.weight_unit" :name="'hbls['+hblIdx+'][containers]['+cIdx+'][weight_unit]'">
                                                                <option value="KG">KG</option>
                                                                <option value="LBS">LBS</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="flex gap-1">
                                                            <input type="number" class="form-control-gf" style="width: 60px; text-align: right;" step="0.01" x-model="c.measure_cbm" :name="'hbls['+hblIdx+'][containers]['+cIdx+'][measure_cbm]'">
                                                            <select class="form-control-gf" style="flex: 1;" x-model="c.measure_unit" :name="'hbls['+hblIdx+'][containers]['+cIdx+'][measure_unit]'">
                                                                <option value="CBM">CBM</option>
                                                                <option value="CFT">CFT</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td x-show="hbl.po_mapping_type === 'container'">
                                                        <!-- P.O. Selector for Container-based Mapping -->
                                                        <select class="form-control-gf" x-model="c.po_no" :name="'hbls['+hblIdx+'][containers]['+cIdx+'][po_no]'">
                                                            <option value="">Select PO...</option>
                                                            <template x-for="po in getPoList(hbl.po_no)">
                                                                <option :value="po" x-text="po"></option>
                                                            </template>
                                                        </select>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <button type="button" @click="hbl.containers.splice(cIdx, 1)" class="btn-tool-icon" style="color: red; border:none; background:none;" title="Delete row">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                            <template x-if="!hbl.containers || hbl.containers.length === 0">
                                                <tr>
                                                    <td :colspan="hbl.po_mapping_type === 'container' ? 7 : 6" style="text-align: center; color: #999; padding: 10px;">No containers assigned yet. Click "Copy Value from MB/L" or add a row.</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                        <tfoot>
                                            <tr class="total-row" style="background: #fdfaf0;">
                                                <td colspan="2" style="font-weight: bold; text-align: right;">Total</td>
                                                <td style="font-weight: bold; text-align: right;" x-text="calculateHblTotal(hbl, 'pkg_qty')"></td>
                                                <td style="font-weight: bold; text-align: right;" x-text="calculateHblTotal(hbl, 'weight_kg')"></td>
                                                <td style="font-weight: bold; text-align: right;" x-text="calculateHblTotal(hbl, 'measure_cbm')"></td>
                                                <td :colspan="hbl.po_mapping_type === 'container' ? 2 : 1"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="mt-2">
                                    <button type="button" @click="hbl.containers.push({container_no:'', pkg_qty:'', pkg_unit:'CARTON(S)', weight_kg:'', weight_unit:'KG', measure_cbm:'', measure_unit:'CBM', po_no:''})" style="padding:2px; background-color:#3b82f6; color:#fff; white-space:no-wrap;" class="btn-tool-icon btn-tool-icon-blue" title="Add Row"><i class="fa fa-plus"></i> Add Container</button>
                                </div>
                            </div>

                            <!-- Commodity / Manifest Commodity -->
                            <div style="margin-bottom: 15px;">
                                <div class="flex justify-between items-center" style="margin-bottom: 6px;">
                                    <span style="font-weight: bold; font-size: 12px; color: #333;">Commodity / Manifest Commodity</span>
                                    <div class="flex gap-1">
                                        <button type="button" @click="addHblCommodity(hbl)" class="btn-tool-icon btn-tool-icon-blue" title="Add Row"><i class="fa fa-plus"></i></button>
                                        <button type="button" @click="deleteSelectedHblCommodities(hbl)" class="btn-tool-icon" style="color: red; border-color: red;" title="Delete Selected"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="container-table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th style="width: 30px;"><input type="checkbox" @change="toggleAllHblCommodities(hbl, $event)"></th>
                                                <th>* Commodity Description</th>
                                                <th style="width: 200px;">HTS Code</th>
                                                <th style="width: 200px;">Container</th>
                                                <th x-show="hbl.po_mapping_type === 'item'">P.O. No.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(comm, commIdx) in hbl.commodities" :key="commIdx">
                                                <tr>
                                                    <td><input type="checkbox" x-model="comm.selected"></td>
                                                    <td>
                                                        <input type="text" :name="'hbls['+hblIdx+'][commodities]['+commIdx+'][commodity_desc]'" class="form-control-gf" placeholder="Description..." x-model="comm.commodity_desc" required>
                                                    </td>
                                                    <td>
                                                        <input type="text" :name="'hbls['+hblIdx+'][commodities]['+commIdx+'][hts_code]'" class="form-control-gf" placeholder="HTS Code..." x-model="comm.hts_code">
                                                    </td>
                                                    <td>
                                                        <select :name="'hbls['+hblIdx+'][commodities]['+commIdx+'][container_no]'" class="form-control-gf" x-model="comm.container_no">
                                                            <option value="">Select Container...</option>
                                                            <template x-for="c in hbl.containers">
                                                                <option :value="c.container_no" x-text="c.container_no"></option>
                                                            </template>
                                                        </select>
                                                    </td>
                                                    <td x-show="hbl.po_mapping_type === 'item'">
                                                        <!-- P.O. Selector for Item-based Mapping -->
                                                        <select :name="'hbls['+hblIdx+'][commodities]['+commIdx+'][po_no]'" class="form-control-gf" x-model="comm.po_no">
                                                            <option value="">Select PO...</option>
                                                            <template x-for="po in getPoList(hbl.po_no)">
                                                                <option :value="po" x-text="po"></option>
                                                            </template>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </template>
                                            <template x-if="!hbl.commodities || hbl.commodities.length === 0">
                                                <tr>
                                                    <td :colspan="hbl.po_mapping_type === 'item' ? 5 : 4" style="text-align: center; color: #999; padding: 10px;">
                                                        No commodities added yet. Click <span class="text-blue-500 cursor-pointer" @click="addHblCommodity(hbl)">here</span> to add a new row.
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Mark & Description -->
                            <div class="form-grid-2" style="margin-bottom: 15px;">
                                <div>
                                    <label class="form-label-gf" style="font-weight: bold; margin-bottom: 4px; display: block;">Mark</label>
                                    <textarea :name="'hbls['+hblIdx+'][hbl_mark]'" class="form-control-gf" style="height: 100px; width:100%;" x-model="hbl.hbl_mark"></textarea>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center" style="margin-bottom: 4px;">
                                        <label class="form-label-gf" style="font-weight: bold; margin-bottom: 0;">Description</label>
                                        <div class="flex gap-1">
                                            <span style="font-size:10px; color:#555; align-self: center; margin-right: 4px;">Copy:</span>
                                            <button type="button" @click="copyToDescription(hbl, 'po')" class="btn-tool" style="padding: 1px 6px; font-size: 10px;">P.O.</button>
                                            <button type="button" @click="copyToDescription(hbl, 'commodity')" class="btn-tool" style="padding: 1px 6px; font-size: 10px;">Commodity</button>
                                            <button type="button" @click="copyToDescription(hbl, 'both')" class="btn-tool" style="padding: 1px 6px; font-size: 10px;">Commodity & HTS</button>
                                        </div>
                                    </div>
                                    <textarea :name="'hbls['+hblIdx+'][hbl_description]'" class="form-control-gf" style="height: 100px; width:100%;" x-model="hbl.hbl_description"></textarea>
                                </div>
                            </div>

                            <!-- Remark: Tabs for Arrival Notice and Delivery Order -->
                            <div style="margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
                                <div class="flex" style="background: #f5f5f5; border-bottom: 1px solid #ddd;">
                                    <button type="button" class="tab-btn" :class="hbl.remark_tab === 'arrival_notice' ? 'active-tab' : ''" @click="hbl.remark_tab = 'arrival_notice'">
                                        Arrival Notice
                                    </button>
                                    <button type="button" class="tab-btn" :class="hbl.remark_tab === 'delivery_order' ? 'active-tab' : ''" @click="hbl.remark_tab = 'delivery_order'">
                                        Delivery Order
                                    </button>
                                </div>
                                <div style="padding: 10px; background: #fff;">
                                    <div x-show="hbl.remark_tab === 'arrival_notice'">
                                        <textarea :name="'hbls['+hblIdx+'][arrival_notice_remark]'" class="form-control-gf" style="height: 80px; width:100%;" placeholder="Arrival Notice remarks..." x-model="hbl.arrival_notice_remark"></textarea>
                                    </div>
                                    <div x-show="hbl.remark_tab === 'delivery_order'">
                                        <textarea :name="'hbls['+hblIdx+'][delivery_order_remark]'" class="form-control-gf" style="height: 80px; width:100%;" placeholder="Delivery Order remarks..." x-model="hbl.delivery_order_remark"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Warehouse Receipt List -->
                            <div>
                                <div class="flex justify-between items-center" style="margin-bottom: 6px;">
                                    <span style="font-weight: bold; font-size: 12px; color: #333;"><i class="fa fa-file-text-o"></i> Warehouse Receipt List</span>
                                    <div class="flex gap-1">
                                        <button type="button" @click="openWarehouseReceiptModal(hbl)" class="btn-tool" style="background:#3498db; color:#fff; border:none; font-size: 11px; padding: 2px 10px;"><i class="fa fa-download"></i> Load from Warehouse</button>
                                        <button type="button" @click="createHblReceiptLink(hbl)" class="btn-tool" style="background:#2ecc71; color:#fff; border:none; font-size: 11px; padding: 2px 10px;"><i class="fa fa-link"></i> Create Item and Link</button>
                                        <button type="button" @click="deleteSelectedHblReceipts(hbl)" class="btn-tool-icon" style="color: red; border-color: red;" title="Delete Selected"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="container-table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th style="width: 30px;"><input type="checkbox" @change="toggleAllHblReceipts(hbl, $event)"></th>
                                                <th>Receipt No.</th>
                                                <th>Vin No.</th>
                                                <th>TOTAL PCS</th>
                                                <th>Available PCS</th>
                                                <th>Allocated PCS</th>
                                                <th>Unit</th>
                                                <th>Actual Weight</th>
                                                <th>Measurement</th>
                                                <th>Remarks for Load Plan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(rec, rIdx) in hbl.receipts" :key="rIdx">
                                                <tr>
                                                    <td><input type="checkbox" x-model="rec.selected"></td>
                                                    <td>
                                                        <span x-text="rec.receipt_no" style="font-weight:bold;"></span>
                                                        <input type="hidden" :name="'hbls['+hblIdx+'][receipts]['+rIdx+'][receipt_no]'" :value="rec.receipt_no">
                                                    </td>
                                                    <td>
                                                        <input type="text" :name="'hbls['+hblIdx+'][receipts]['+rIdx+'][vin_no]'" class="form-control-gf" x-model="rec.vin_no">
                                                    </td>
                                                    <td>
                                                        <input type="number" :name="'hbls['+hblIdx+'][receipts]['+rIdx+'][total_pcs]'" class="form-control-gf" style="text-align: right;" x-model="rec.total_pcs" @input="if(hbl.auto_sync_receipts) syncReceiptTotalsToContainers(hbl)">
                                                    </td>
                                                    <td>
                                                        <input type="number" :name="'hbls['+hblIdx+'][receipts]['+rIdx+'][available_pcs]'" class="form-control-gf" style="text-align: right;" x-model="rec.available_pcs">
                                                    </td>
                                                    <td>
                                                        <input type="number" :name="'hbls['+hblIdx+'][receipts]['+rIdx+'][allocated_pcs]'" class="form-control-gf" style="text-align: right;" x-model="rec.allocated_pcs">
                                                    </td>
                                                    <td>
                                                        <input type="text" :name="'hbls['+hblIdx+'][receipts]['+rIdx+'][unit]'" class="form-control-gf" x-model="rec.unit">
                                                    </td>
                                                    <td>
                                                        <input type="number" :name="'hbls['+hblIdx+'][receipts]['+rIdx+'][actual_weight]'" class="form-control-gf" style="text-align: right;" step="0.01" x-model="rec.actual_weight" @input="if(hbl.auto_sync_receipts) syncReceiptTotalsToContainers(hbl)">
                                                    </td>
                                                    <td>
                                                        <input type="number" :name="'hbls['+hblIdx+'][receipts]['+rIdx+'][measurement]'" class="form-control-gf" style="text-align: right;" step="0.01" x-model="rec.measurement" @input="if(hbl.auto_sync_receipts) syncReceiptTotalsToContainers(hbl)">
                                                    </td>
                                                    <td>
                                                        <input type="text" :name="'hbls['+hblIdx+'][receipts]['+rIdx+'][remarks]'" class="form-control-gf" x-model="rec.remarks">
                                                    </td>
                                                </tr>
                                            </template>
                                            <template x-if="!hbl.receipts || hbl.receipts.length === 0">
                                                <tr>
                                                    <td colspan="10" style="text-align: center; color: #999; padding: 10px;">No warehouse receipts linked.</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="flex items-center gap-1 mt-2" style="font-size: 11px;">
                                    <input type="checkbox" :id="'sync-wr-' + hblIdx" x-model="hbl.auto_sync_receipts" @change="syncReceiptTotalsToContainers(hbl)">
                                    <label :for="'sync-wr-' + hblIdx" style="font-weight:normal; color:#555; cursor:pointer;">Auto-sync package, weight and measurements</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Modal for Clipboard -->
            <div x-show="showClipboardModal" class="modal-overlay" style="display:none;" x-transition>
                <div class="modal-container" @click.away="showClipboardModal = false">
                    <div class="modal-header">
                        <span style="font-weight:700;">Container List</span>
                        <i class="fa fa-times cursor-pointer" @click="showClipboardModal = false"></i>
                    </div>
                    <div class="modal-body">
                        <table class="memo-table">
                            <thead>
                                <tr>
                                    <th>Container No.</th>
                                    <th>TP/SZ</th>
                                    <th>Seal No.</th>
                                    <th>PKG (CARTON(S))</th>
                                    <th>TARE (KG)</th>
                                    <th>VGM (KG)</th>
                                    <th>Net Weight (KG)</th>
                                    <th>Gross Weight (KG)</th>
                                    <th>Measurement (CBM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="cont in form.containers">
                                    <tr>
                                        <td x-text="cont.container_no || '-'"></td>
                                        <td x-text="cont.container_type_id || '-'"></td>
                                        <td x-text="cont.seal_no || '-'"></td>
                                        <td x-text="cont.pkg_qty || '0'"></td>
                                        <td x-text="cont.tare_weight || '-'"></td>
                                        <td x-text="cont.vgm || '-'"></td>
                                        <td x-text="cont.net_weight || '-'"></td>
                                        <td x-text="cont.weight_kg || '0'"></td>
                                        <td x-text="cont.measure_cbm || '0'"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-tool" style="background:#4b77be; padding: 6px 15px;" @click="copyContainerToClipboard">COPY TO CLIPBOARD</button>
                    </div>
                </div>
            </div>

            <!-- Modal for Documents -->
            <div x-show="showDocumentModal" class="modal-overlay" style="display:none;" x-cloak x-transition>
                <div class="modal-container" style="max-width: 700px;" @click.away="showDocumentModal = false">
                    <div class="modal-header">
                        <span style="font-weight:700;"><i class="fa fa-folder-open text-blue-500"></i> Shipment Documents & Attachments</span>
                        <i class="fa fa-times cursor-pointer" @click="showDocumentModal = false"></i>
                    </div>
                    <div class="modal-body">
                        <!-- Upload Section -->
                        <div style="margin-bottom: 15px; padding: 12px; background: #f8fafc; border: 1px dashed #3b82f6; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <span style="font-weight: 600; color: #1e3a8a;">Upload a new document:</span>
                                <p style="font-size: 10px; color: #64748b; margin: 2px 0 0 0;">Max size: 10MB (PDF, PNG, JPG, Docx, etc.)</p>
                            </div>
                            <div>
                                <input type="file" @change="uploadDocument" style="font-size: 11px;">
                            </div>
                        </div>

                        <!-- Documents List Table -->
                        <table class="memo-table">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Size</th>
                                    <th>Uploaded At</th>
                                    <th style="width: 100px; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(doc, idx) in documents" :key="idx">
                                    <tr>
                                        <td>
                                            <i class="fa fa-file-text-o text-gray-400" style="margin-right: 5px;"></i>
                                            <span x-text="doc.file_name"></span>
                                            <p style="font-size: 9px; color: #777; margin: 2px 0 0 0;" x-text="doc.description || 'No description'"></p>
                                        </td>
                                        <td x-text="doc.file_size ? (doc.file_size / 1024).toFixed(1) + ' KB' : '-'"></td>
                                        <td x-text="doc.created_at ? new Date(doc.created_at).toLocaleDateString() : ''"></td>
                                        <td style="text-align: center;">
                                            <div style="display: flex; gap: 5px; justify-content: center;">
                                                <button type="button" @click="downloadDocument(doc.id)" class="btn-tool-icon" style="color:#3b82f6; background:none; border:none; cursor:pointer;" title="Download"><i class="fa fa-download"></i></button>
                                                <button type="button" @click="deleteDocument(doc.id, idx)" class="btn-tool-icon" style="color:#ef4444; background:none; border:none; cursor:pointer;" title="Delete"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="documents.length === 0">
                                    <tr>
                                        <td colspan="4" style="text-align: center; color: #999; padding: 10px;">No documents uploaded yet.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-default-gf" @click="showDocumentModal = false">Close</button>
                    </div>
                </div>
            </div>

            <!-- Modal for Warehouse Receipts Lookup -->
            <div x-show="showWrModal" class="modal-overlay" style="display:none;" x-cloak x-transition>
                <div class="modal-container" style="max-width: 800px;" @click.away="showWrModal = false">
                    <div class="modal-header">
                        <span style="font-weight:700;"><i class="fa fa-search text-blue-500"></i> Search & Load Warehouse Receipts</span>
                        <i class="fa fa-times cursor-pointer" @click="showWrModal = false"></i>
                    </div>
                    <div class="modal-body">
                        <!-- Search form -->
                        <div class="flex gap-2" style="margin-bottom: 15px;">
                            <input type="text" class="form-control-gf" placeholder="Search by Receipt No, Carrier Name, Tracking No..." x-model="wrSearchQuery" @keyup.enter="searchWrList()" style="height: 28px; font-size: 11px; width: 100%;">
                            <button type="button" @click="searchWrList()" class="btn-gofreight" style="background:#3498db; padding: 6px 15px; font-size: 11px; border-radius: 3px; height: 28px; line-height: 1; border: none; color: #fff; cursor: pointer;">Search</button>
                        </div>

                        <!-- Results list -->
                        <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                            <table class="memo-table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 40px; text-align: center;">Select</th>
                                        <th>Receipt No.</th>
                                        <th>Vin No.</th>
                                        <th>Pcs</th>
                                        <th>Weight (KG)</th>
                                        <th>Measurement (CBM)</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(r, idx) in wrSearchResults" :key="idx">
                                        <tr style="cursor: pointer;" @click="r.selected = !r.selected">
                                            <td style="text-align: center;"><input type="checkbox" x-model="r.selected" @click.stop></td>
                                            <td x-text="r.receipt_no" style="font-weight:bold; color: #1e3a8a;"></td>
                                            <td x-text="r.vin_no"></td>
                                            <td x-text="r.total_pcs" style="text-align: right;"></td>
                                            <td x-text="r.actual_weight" style="text-align: right;"></td>
                                            <td x-text="r.measurement" style="text-align: right;"></td>
                                            <td x-text="r.remarks"></td>
                                        </tr>
                                    </template>
                                    <template x-if="wrSearchResults.length === 0">
                                        <tr>
                                            <td colspan="7" style="text-align: center; color: #999; padding: 15px;">No matching warehouse receipts found. Try changing the query.</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-default-gf" @click="showWrModal = false" style="margin-right: 5px;">Cancel</button>
                        <button type="button" class="btn-gofreight" @click="loadSelectedReceipts()" style="background:#2ecc71; padding: 6px 20px; font-size: 11px; border: none; color: #fff; cursor: pointer;">Load Selected</button>
                    </div>
                </div>
            </div>

          <!-- CHARGES TAB -->
<!-- CHARGES TAB -->
<div x-show="activeTab === 'charges'" class="main-grid">
    <div class="portlet light">
        <div class="portlet-title">
            <span class="caption-subject"><i class="fa fa-money"></i> Charge Manifestation</span>
        </div>
        <div class="portlet-body">

            <!-- Header Info Row -->
            <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 15px; padding: 8px 5px; background: #f9fafb; border-bottom: 1px solid #e7ecf1;">
                <div style="display: flex; align-items: center; gap: 5px;">
                    <span style="font-weight: 700; color: #4b77be;">KG :</span>
                    <span style="color: #333;" x-text="form.file_no || '-'"></span>
                </div>
                <template x-for="h in hbls" :key="h.id || h.hbl_no">
                    <div style="display: flex; align-items: center; gap: 5px;" x-show="h.hbl_no">
                        <span style="font-weight: 700; color: #4b77be;">GP :</span>
                        <span style="color: #333;" x-text="h.hbl_no"></span>
                    </div>
                </template>
                <div style="display: flex; align-items: center; gap: 5px;">
                    <button type="button" class="btn-default-gf" style="border: 1px solid #4b77be; color: #4b77be; padding: 2px 8px;" @click="activeChargeFilter = 'All'">GP : Show All of this BKG</button>
                </div>
                <div style="display: flex; align-items: center; gap: 5px; margin-left: auto;">
                    <span style="font-weight: 700; color: #4b77be;">CM :</span>
                    <span style="color: #333; font-weight: 700;" x-text="calculateTotalCharges().toFixed(2)">56,612.75</span>
                </div>
            </div>

            <!-- Filter Row - All | Dynamic filters -->
            <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 15px; padding: 6px 8px; background: #fff; border: 1px solid #e7ecf1; border-radius: 4px; align-items: center;">
                <template x-for="filter in getChargeFilters()" :key="filter.value">
                    <span :style="activeChargeFilter === filter.value ? 'background: #3b82f6; color: #fff;' : 'background: #f1f3f6; color: #333;'"
                          style="padding: 2px 10px; border-radius: 3px; font-size: 11px; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                          @click="activeChargeFilter = filter.value"
                          x-text="filter.name">
                    </span>
                </template>
                <div style="margin-left: auto; display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 11px; color: #666;">POLL :</span>
                    <span style="font-size: 11px; font-weight: 600;" x-text="form.pol_name || form.pol_id || '-'"></span>
                    <span style="font-size: 11px; color: #666;">POD :</span>
                    <span style="font-size: 11px; font-weight: 600;" x-text="form.pod_name || form.pod_id || '-'"></span>
                    <span style="font-size: 11px; font-weight: 600; background: #e8f4f8; padding: 2px 6px; border-radius: 3px;" x-text="form.incoterm_id || '-'"></span>
                    <span style="font-size: 11px; font-weight: 600;" x-text="customerName || '-'"></span>
                    <span style="font-size: 11px;" x-text="'C:' + calculateTotalCharges().toFixed(2)"></span>
                    <span style="font-size: 11px;" x-text="'A:' + calculateArCharges().toFixed(2)"></span>
                    <span style="font-size: 11px;" x-text="'R:' + calculateApCharges().toFixed(2)"></span>
                </div>
            </div>

            <!-- Charges Table -->
            <div class="table-responsive charges-table-container" style="margin-bottom: 15px;">
                <table class="table-custom" style="font-size: 11px; min-width: 1200px;">
                    <thead>
                        <tr style="background: #f1f3f6;">
                            <th style="padding: 6px 8px; width: 30px; text-align: center;">
                                <button type="button" @click="addNewCharge" class="btn-tool-icon-blue" style="border:none; padding: 2px 6px; border-radius: 2px; cursor: pointer; display: inline-block;">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                            <th style="padding: 6px 8px;">Party</th>
                            <th style="padding: 6px 8px;">Party Name</th>
                            <th style="padding: 6px 8px;">SAL</th>
                            <th style="padding: 6px 8px;">P/R</th>
                            <th style="padding: 6px 8px;">PP/C</th>
                            <th style="padding: 6px 8px;">Chrg</th>
                            <th style="padding: 6px 8px;">Curr.</th>
                            <th style="padding: 6px 8px;">Rate</th>
                            <th style="padding: 6px 8px;">Qty</th>
                            <th style="padding: 6px 8px;">Q.Type</th>
                            <th style="padding: 6px 8px;">F.Amnt</th>
                            <th style="padding: 6px 8px;">ROE</th>
                            <th style="padding: 6px 8px;">VAT</th>
                            <th style="padding: 6px 8px;">L.Amnt</th>
                            <th style="padding: 6px 8px;">Inv/Dr/Cr/No</th>
                            <th style="padding: 6px 8px;">Fin. Dt</th>
                            <th style="padding: 6px 8px;">JV</th>
                            <th style="padding: 6px 8px;">EQ/BL NO.</th>
                            <th style="padding: 6px 8px;">Remark</th>
                            <th style="padding: 6px 8px;">MBL#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(charge, idx) in chargesList" :key="idx">
                            <tr x-show="shouldShowChargeRow(charge)">
                                <td style="padding: 5px 8px; text-align: center;">
                                    <input type="hidden" :name="'charges[' + idx + '][id]'" :value="charge.id">
                                    <button type="button" @click="removeCharge(idx)" class="btn-tool-icon" style="color: #ef4444; border:none; padding: 2px; background: transparent; cursor: pointer; display: inline-block;">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                                <td style="padding: 5px 8px;">
                                    <select class="form-control-gf" style="width: 70px; font-size: 10px;" x-model="charge.party" :name="'charges[' + idx + '][party]'">
                                        <option value="Agent">Agent</option>
                                        <option value="C&F/CN">C&F/CN</option>
                                        <option value="Custom">Custom</option>
                                        <option value="Shipper">Shipper</option>
                                        <option value="Consignee">Consignee</option>
                                    </select>
                                </td>
                                <td style="padding: 5px 8px;">
                                    <select class="form-control-gf" style="width: 90px; font-size: 10px;" x-model="charge.party_name_id" :name="'charges[' + idx + '][party_name_id]'">
                                        <option value="">Select...</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="padding: 5px 8px;">
                                    <select class="form-control-gf" style="width: 50px;" x-model="charge.sal" :name="'charges[' + idx + '][sal]'">
                                        <option value="Sea">Sea</option>
                                        <option value="Air">Air</option>
                                        <option value="Land">Land</option>
                                    </select>
                                </td>
                                <td style="padding: 5px 8px;">
                                    <select class="form-control-gf" style="width: 55px;" x-model="charge.pr" :name="'charges[' + idx + '][pr]'">
                                        <option value="Rec">Rec</option>
                                        <option value="Pay">Pay</option>
                                    </select>
                                </td>
                                <td style="padding: 5px 8px;">
                                    <select class="form-control-gf" style="width: 60px;" x-model="charge.ppc" :name="'charges[' + idx + '][ppc]'">
                                        <option value="Colle">Colle</option>
                                        <option value="Proj">Proj</option>
                                        <option value="Prepaid">Prepaid</option>
                                    </select>
                                </td>
                                <td style="padding: 5px 8px;">
                                    <input type="text" class="form-control-gf" style="width: 60px;" x-model="charge.chrg_code" :name="'charges[' + idx + '][chrg_code]'" placeholder="Code">
                                </td>
                                <td style="padding: 5px 8px;">
                                    <select class="form-control-gf" style="width: 65px;" x-model="charge.currency" :name="'charges[' + idx + '][currency]'">
                                        <option value="">Select...</option>
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->code }}">{{ $currency->code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="padding: 5px 8px;">
                                    <input type="number" class="form-control-gf" style="width: 60px; text-align: right;" x-model="charge.rate" :name="'charges[' + idx + '][rate]'" @input="updateChargeAmount(idx)">
                                </td>
                                <td style="padding: 5px 8px;">
                                    <input type="number" class="form-control-gf" style="width: 50px; text-align: right;" x-model="charge.qty" :name="'charges[' + idx + '][qty]'" @input="updateChargeAmount(idx)">
                                </td>
                                <td style="padding: 5px 8px;">
                                    <select class="form-control-gf" style="width: 65px;" x-model="charge.qty_type" :name="'charges[' + idx + '][qty_type]'">
                                        <option value="">Select...</option>
                                        @foreach($packageUnits as $unit)
                                            <option value="{{ $unit->code }}">{{ $unit->code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="padding: 5px 8px;">
                                    <span x-text="(charge.rate * charge.qty).toFixed(2)" style="display: inline-block; min-width: 50px; text-align: right;">0.00</span>
                                </td>
                                <td style="padding: 5px 8px;">
                                    <input type="number" class="form-control-gf" style="width: 55px; text-align: right;" x-model="charge.roe" :name="'charges[' + idx + '][roe]'" @input="updateLocalAmount(idx)">
                                </td>
                                <td style="padding: 5px 8px;">
                                    <input type="number" class="form-control-gf" style="width: 50px; text-align: right;" x-model="charge.vat" :name="'charges[' + idx + '][vat]'" @input="updateLocalAmount(idx)">
                                </td>
                                <td style="padding: 5px 8px;">
                                    <span x-text="calculateLocalAmount(charge).toFixed(2)" style="display: inline-block; min-width: 65px; text-align: right; font-weight: 500;">0.00</span>
                                </td>
                                <td style="padding: 5px 8px;">
                                    <input type="text" class="form-control-gf" style="width: 110px;" x-model="charge.inv_no" :name="'charges[' + idx + '][inv_no]'" placeholder="INV/DR/CR No.">
                                </td>
                                <td style="padding: 5px 8px;">
                                    <input type="date" class="form-control-gf" style="width: 85px;" x-model="charge.financial_date" :name="'charges[' + idx + '][financial_date]'">
                                </td>
                                <td style="padding: 5px 8px;">
                                    <span style="background: #4b77be; color: #fff; padding: 1px 4px; border-radius: 2px; font-size: 9px;">JV</span>
                                </td>
                                <td style="padding: 5px 8px;">
                                    <input type="text" class="form-control-gf" style="width: 100px;" x-model="charge.eq_bl_no" :name="'charges[' + idx + '][eq_bl_no]'" placeholder="EQ/BL No.">
                                </td>
                                <td style="padding: 5px 8px;">
                                    <input type="hidden" :name="'charges[' + idx + '][remark]'" value="0">
                                    <input type="checkbox" x-model="charge.remark" :name="'charges[' + idx + '][remark]'" value="1" style="width: 16px; height: 16px;">
                                </td>
                                <td style="padding: 5px 8px;">
                                    <input type="text" class="form-control-gf" style="width: 80px;" x-model="charge.mbl_no" :name="'charges[' + idx + '][mbl_no]'" placeholder="MBL#">
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Buttons Row - Exactly as per image -->
            <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: flex-start; align-items: center; padding: 10px 0; border-top: 1px solid #e7ecf1;">
                <button type="button" class="btn-default-gf" @click="addNewCharge">Parcs</button>
                <button type="button" class="btn-default-gf" @click="openCertificateModal">Certificate</button>
                <button type="button" class="btn-default-gf" @click="applyTemplate">Template</button>
                <button type="button" class="btn-default-gf" @click="copyFromQuote">Copy From</button>
                <button type="button" class="btn-default-gf" @click="createInvoice">Create INV/CRN</button>
                <button type="button" class="btn-default-gf" @click="prorataCharges">Prorata</button>
                <button type="button" class="btn-default-gf" @click="setDefaultCharges">Default</button>
                <button type="button" class="btn-default-gf" @click="reloadCharges">Reload</button>
                <button type="button" class="btn-gofreight" @click="saveCharges">Save</button>
            </div>
 
            <!-- Dropdown for Multiple Options (as requested) -->
            <div style="position: relative; margin-top: 10px; display: flex; justify-content: flex-end;">
                <div class="dropdown" x-data="{ open: false }">
                    <button type="button" @click="open = !open" class="btn-default-gf" style="display: flex; align-items: center; gap: 5px;">
                        More Actions <i class="fa fa-angle-down"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" style="position: absolute; bottom: 100%; right: 0; margin-bottom: 5px; background: #fff; border: 1px solid #ccc; border-radius: 4px; min-width: 180px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); z-index: 100;">
                        <ul style="list-style: none; margin: 0; padding: 5px 0;">
                            <li><button type="button" class="dropdown-item" style="width: 100%; text-align: left; padding: 6px 12px; border: none; background: none; font-size: 11px; cursor: pointer;" @click="exportChargesToExcel">Export to Excel</button></li>
                            <li><button type="button" class="dropdown-item" style="width: 100%; text-align: left; padding: 6px 12px; border: none; background: none; font-size: 11px; cursor: pointer;" @click="printCharges">Print Charges</button></li>
                            <li><hr style="margin: 4px 0;"></li>
                            <li><button type="button" class="dropdown-item" style="width: 100%; text-align: left; padding: 6px 12px; border: none; background: none; font-size: 11px; cursor: pointer;" @click="deleteAllCharges">Delete All Charges</button></li>
                            <li><button type="button" class="dropdown-item" style="width: 100%; text-align: left; padding: 6px 12px; border: none; background: none; font-size: 11px; cursor: pointer;" @click="duplicateSelectedCharges">Duplicate Selected</button></li>
                            <li><hr style="margin: 4px 0;"></li>
                            <li><button type="button" class="dropdown-item" style="width: 100%; text-align: left; padding: 6px 12px; border: none; background: none; font-size: 11px; cursor: pointer;" @click="bulkUpdateCurrency">Bulk Update Currency</button></li>
                            <li><button type="button" class="dropdown-item" style="width: 100%; text-align: left; padding: 6px 12px; border: none; background: none; font-size: 11px; cursor: pointer;" @click="applyVatToAll">Apply VAT to All</button></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

            <!-- HISTORY TAB -->
            <div x-show="activeTab === 'history'" class="main-grid">
                <div class="portlet light">
                    <div class="portlet-title">
                        <span class="caption-subject"><i class="fa fa-history"></i> History</span>
                    </div>
                    <div class="portlet-body">
                        <div style="margin-bottom: 20px; padding: 15px; background: #f9fafb; border: 1px solid #eee; border-radius: 4px;">
                            <div class="caption-subject" style="font-size: 11px; margin-bottom: 10px; font-weight: bold; color: #4b77be; text-transform: uppercase;">Shipment Status Logs</div>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                <div style="background: #ebf5ff; color: #4b77be; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 600; border-left: 3px solid #4b77be;">BOOKING</div>
                                <div style="background: #e6fffa; color: #2d6a4f; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 600; border-left: 3px solid #2d6a4f;">MBL SUBMIT</div>
                                <div style="background: #e6fffa; color: #2d6a4f; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 600; border-left: 3px solid #2d6a4f;">AMS SUBMIT</div>
                                <div style="background: #e6fffa; color: #2d6a4f; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 600; border-left: 3px solid #2d6a4f;">ISF SUBMIT</div>
                                <div style="background: #e6fffa; color: #2d6a4f; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 600; border-left: 3px solid #2d6a4f;">BL RELEASE</div>
                                <div style="background: #e6fffa; color: #2d6a4f; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 600; border-left: 3px solid #2d6a4f;">BL SURRENDERED</div>
                            </div>
                        </div>
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="h in form.history" :key="h.id">
                                    <tr>
                                        <td x-text="h.created_at ? new Date(h.created_at).toLocaleString() : ''"></td>
                                        <td x-text="h.user ? h.user.name : 'System'"></td>
                                        <td x-text="h.action"></td>
                                        <td x-text="h.details"></td>
                                    </tr>
                                </template>
                                <template x-if="form.history.length === 0">
                                    <tr>
                                        <td colspan="4" style="text-align: center; color: #999; padding: 10px;">No history logs found.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- FILING TAB -->
            <template x-if="activeTab === 'filing'"><div class="main-grid">
                <div class="portlet light">
                    <div class="portlet-title">
                        <span class="caption-subject"><i class="fa fa-file-text-o"></i> Filing Details</span>
                    </div>
                    <div class="portlet-body">
                        <div class="form-grid-4">
                            <!-- Column 1 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Shipper</label><div class="form-input-container"><x-inline-select name="dm_shipper_id" :options="$agents" module="trade-partner" type="shipper" x-model="form.dm_shipper_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'dm_shipper_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Bill To</label><div class="form-input-container"><x-inline-select name="dm_bill_to_id" :options="$agents" module="trade-partner" type="customer" x-model="form.dm_bill_to_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'dm_bill_to_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Oversea Agent</label><div class="form-input-container"><x-inline-select name="oversea_agent_id" :options="$agents" module="trade-partner" type="agent" x-model="form.oversea_agent_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'oversea_agent_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div style="height: 5px;"></div>
                                <div class="form-group-gf"><label class="form-label-gf">Trucker</label><div class="form-input-container"><select name="trucker_id" class="form-control-gf" x-model="form.trucker_id"><option value="">Select...</option>@foreach($agents as $agent)<option value="{{ $agent->id }}">{{ $agent->name }}</option>@endforeach</select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">P.O.D ETA</label><div class="form-input-container"><input type="date" name="eta" class="form-control-gf" x-model="form.eta"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Ship Mode</label><div class="form-input-container"><select name="ship_mode" class="form-control-gf" x-model="form.ship_mode"><option value="FCL">FCL</option><option value="LCL">LCL</option></select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">G.O Date</label><div class="form-input-container"><input type="date" name="go_date" class="form-control-gf" x-model="form.go_date"></div></div>
                            </div>

                            <!-- Column 2 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Consignee</label><div class="form-input-container"><x-inline-select name="dm_consignee_id" :options="$agents" module="trade-partner" type="consignee" x-model="form.dm_consignee_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'dm_consignee_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Sub B/L No.</label><div class="form-input-container"><input type="text" name="sub_bl_no" class="form-control-gf" x-model="form.sub_bl_no"></div></div>
                                <div style="height: 5px;"></div>
                                <div class="form-group-gf"><label class="form-label-gf">CY/CFS Loc.</label><div class="form-input-container"><select name="cfs_location_id" class="form-control-gf" x-model="form.cfs_location_id"><option value="">Select...</option>@foreach($agents as $agent)<option value="{{ $agent->id }}">{{ $agent->name }}</option>@endforeach</select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Final Dest.</label><div class="form-input-container"><x-inline-select name="fdest_id" :options="$ports" module="port" x-model="form.fdest_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('port', 'fdest_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Freight</label><div class="form-input-container"><select name="freight_term" class="form-control-gf" x-model="form.freight_term"><option value="Prepaid">Prepaid</option><option value="Collect">Collect</option></select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Expiry Date</label><div class="form-input-container"><input type="date" name="expiry_date" class="form-control-gf" x-model="form.expiry_date"></div></div>
                            </div>

                            <!-- Column 3 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Notify</label><div class="form-input-container"><x-inline-select name="dm_notify_id" :options="$agents" module="trade-partner" type="notify" x-model="form.dm_notify_id" class="form-control-gf" /><button type="button" class="btn-default-gf" style="height:18px; padding:0 4px;" @click="openAddNewModal('trade-partner', 'dm_notify_id')"><i class="fa fa-external-link" style="font-size:9px;"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">OP</label><div class="form-input-container"><input type="text" class="form-control-gf" value="{{ auth()->user()->name ?? 'DEMO_USER' }}" disabled style="background:#f5f5f5;"></div></div>
                                <div style="height: 19px;"></div>
                                <div style="height: 5px;"></div>
                                <div class="form-group-gf"><label class="form-label-gf">Available</label><div class="form-input-container"><input type="date" name="available_date" class="form-control-gf" x-model="form.available_date"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Final ETA</label><div class="form-input-container"><input type="date" name="final_eta" class="form-control-gf" x-model="form.final_eta"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">LFD</label><div class="form-input-container"><input type="date" name="lfd" class="form-control-gf" x-model="form.lfd"></div></div>
                            </div>

                            <!-- Column 4 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">AMS No.</label><div class="form-input-container"><input type="text" name="ams_no" class="form-control-gf" x-model="form.ams_no"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ISF No.</label><div class="form-input-container"><input type="text" name="isf_no" class="form-control-gf" x-model="form.isf_no"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ISF Matched</label><div class="form-input-container"><input type="date" name="isf_matched_date" class="form-control-gf" x-model="form.isf_matched_date"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ISF 3rd Party</label><div class="form-input-container" style="justify-content: flex-start;"><input type="checkbox" name="is_isf_3rd_party" value="1" x-model="form.is_isf_3rd_party" :checked="form.is_isf_3rd_party"></div></div>
                            </div>
                        </div>

                        <div style="height: 15px;"></div>

                        <div class="form-grid-4">
                            <!-- Column 1 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Sales Type</label><div class="form-input-container"><select name="sales_type" class="form-control-gf" x-model="form.sales_type"><option value="NORMAL">NORMAL</option><option value="CO-LOAD">CO-LOAD</option></select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">C. Released</label><div class="form-input-container"><input type="date" name="c_released_date" class="form-control-gf" x-model="form.c_released_date"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Entry No.</label><div class="form-input-container"><input type="text" name="entry_no" class="form-control-gf" x-model="form.entry_no"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ROR</label><div class="form-input-container"><input type="checkbox" name="is_ror" value="1" x-model="form.is_ror" :checked="form.is_ror"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Released By</label><div class="form-input-container"><select name="released_by_id" class="form-control-gf" x-model="form.released_by_id"><option value="">Select...</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">DO Sent</label><div class="form-input-container"><input type="checkbox" name="is_do_sent" value="1" x-model="form.is_do_sent"><input type="date" name="do_sent_date" class="form-control-gf" x-model="form.do_sent_date"></div></div>
                            </div>

                            <!-- Column 2 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Incoterms</label><div class="form-input-container"><select name="incoterm_id" class="form-control-gf" x-model="form.incoterm_id"><option value="">Select...</option>@foreach($incoterms as $incoterm)<option value="{{ $incoterm->id }}">{{ $incoterm->code }} - {{ $incoterm->name }}</option>@endforeach</select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Service Term</label><div class="form-input-container"><select name="service_term_from_id" class="form-control-gf" style="width:45%;" x-model="form.service_term_from_id"><option value="">Select...</option>@foreach($serviceTerms as $st)<option value="{{ $st->id }}">{{ $st->code }}</option>@endforeach</select>~<select name="service_term_to_id" class="form-control-gf" style="width:45%;" x-model="form.service_term_to_id"><option value="">Select...</option>@foreach($serviceTerms as $st)<option value="{{ $st->id }}">{{ $st->code }}</option>@endforeach</select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Entry DOC Sent</label><div class="form-input-container"><input type="date" name="entry_doc_sent_date" class="form-control-gf" x-model="form.entry_doc_sent_date"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Hold</label><div class="form-input-container"><input type="checkbox" name="is_hold" value="1" x-model="form.is_hold" :checked="form.is_hold"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Door Deliv.</label><div class="form-input-container"><input type="date" name="door_delivery_date" class="form-control-gf" x-model="form.door_delivery_date"></div></div>
                            </div>

                            <!-- Column 3 -->
                            <div class="flex flex-col">
                                <div class="form-group-gf"><label class="form-label-gf">Cargo Type</label><div class="form-input-container"><select name="cargo_type" class="form-control-gf" x-model="form.cargo_type"><option value="">Select...</option><option value="GENERAL CARGO">GENERAL CARGO</option><option value="HAZARDOUS">HAZARDOUS</option><option value="REEFER">REEFER</option><option value="DANGEROUS">DANGEROUS</option><option value="OVERSIZE">OVERSIZE</option></select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Container/Qty</label><div class="form-input-container"><input type="text" class="form-control-gf" disabled style="background:#f5f5f5;" :value="form.containers.length + ' Container(s)'"></div></div>
                            </div>

                            <!-- Column 4 -->
                            <div class="flex flex-col">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </template>
        </div>

        <!-- Load Quotation Data Modal -->
        <div x-show="showQuoteModal" class="modal-overlay" style="display:none;" x-cloak>
            <div class="modal-container" style="max-width: 950px; display: flex; flex-direction: column;">
                <div class="modal-header">
                    <span><i class="fa fa-file-text-o text-blue-500"></i> Load Quotation Data</span>
                    <i class="fa fa-times cursor-pointer text-gray-500 hover:text-gray-700" @click="showQuoteModal = false"></i>
                </div>

                <div class="modal-body hide-scrollbar">
                    <style>
                        .hide-scrollbar::-webkit-scrollbar { display: none; }
                        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
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
                        <div class="form-grid-4" style="grid-template-columns: repeat(3, 1fr);">
                            <div class="main-grid">
                                <div class="form-group-gf"><label class="form-label-gf">Customer</label><div class="form-input-container">
                                    <x-inline-select name="customer" :options="$agents" module="trade-partner" type="customer" x-model="filters.customer" class="form-control-gf" />
                                </div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Port of Loading</label><div class="form-input-container">
                                    <x-inline-select name="pol" :options="$ports" module="port" x-model="filters.pol" class="form-control-gf" />
                                </div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Quote No.</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="filters.quote_no"></div></div>
                            </div>
                            <div class="main-grid">
                                <div class="form-group-gf"><label class="form-label-gf">Valid Date</label><div class="form-input-container"><input type="date" class="form-control-gf" x-model="filters.valid_date"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Port of Discharge</label><div class="form-input-container">
                                    <x-inline-select name="pod" :options="$ports" module="port" x-model="filters.pod" class="form-control-gf" />
                                </div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Status</label><div class="form-input-container">
                                    <select class="form-control-gf" x-model="filters.status">
                                        <option value="">Select...</option>
                                        <option value="Won">Won</option>
                                        <option value="Draft">Draft</option>
                                        <option value="Expired">Expired</option>
                                    </select>
                                </div></div>
                            </div>
                            <div class="main-grid">
                                <div class="form-group-gf"><label class="form-label-gf">Commodity</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="filters.commodity"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Sales</label><div class="form-input-container">
                                    <select class="form-control-gf" x-model="filters.sales">
                                        <option value="">Select...</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div></div>
                                <div class="form-group-gf"><label class="form-label-gf">OP</label><div class="form-input-container">
                                    <select class="form-control-gf" x-model="filters.op">
                                        <option value="">Select...</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div></div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: center; gap: 8px; margin: 10px 0;">
                            <button type="button" class="btn-default-gf" @click="clearSearch()">Clear</button>
                            <button type="button" class="btn-gofreight" @click="applySearch()"><i class="fa fa-search"></i> Search</button>
                        </div>

                        <hr style="border-top: 1px solid #e2e8f0; margin: 10px 0;">

                        <div style="display: flex; justify-content: flex-end; margin-bottom: 5px;">
                            <button type="button" class="btn-tool-secondary" @click="showQuoteConfig = !showQuoteConfig"><i class="fa fa-cogs"></i> Config</button>
                        </div>

                        <div x-show="showQuoteConfig" style="margin-bottom: 10px; padding: 8px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 10px;">
                            <span style="font-weight: 600; color: #475569; display: block; margin-bottom: 4px;">Toggle Columns:</span>
                            <label style="margin-right: 8px;"><input type="checkbox" x-model="colVisibility.select"> Select</label>
                            <label style="margin-right: 8px;"><input type="checkbox" x-model="colVisibility.quote_no"> Quote No.</label>
                            <label style="margin-right: 8px;"><input type="checkbox" x-model="colVisibility.valid_date"> Valid Date</label>
                            <label style="margin-right: 8px;"><input type="checkbox" x-model="colVisibility.status"> Status</label>
                            <label style="margin-right: 8px;"><input type="checkbox" x-model="colVisibility.creation_date"> Creation Date</label>
                            <label style="margin-right: 8px;"><input type="checkbox" x-model="colVisibility.commodity"> Commodity</label>
                            <label style="margin-right: 8px;"><input type="checkbox" x-model="colVisibility.pol"> POL</label>
                            <label style="margin-right: 8px;"><input type="checkbox" x-model="colVisibility.pod"> POD</label>
                            <label style="margin-right: 8px;"><input type="checkbox" x-model="colVisibility.carrier"> Carrier</label>
                            <label style="margin-right: 8px;"><input type="checkbox" x-model="colVisibility.sales"> Sales</label>
                        </div>

                        <div class="table-responsive" style="margin-bottom: 10px;">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th x-show="colVisibility.select" style="text-align: center;">Select</th>
                                    <th x-show="colVisibility.quote_no">Quote No.</th>
                                    <th x-show="colVisibility.valid_date">Valid Date <i class="fa fa-sort" style="float: right;"></i></th>
                                    <th x-show="colVisibility.status">Status <i class="fa fa-sort" style="float: right;"></i></th>
                                    <th x-show="colVisibility.creation_date">Creation Date <i class="fa fa-sort" style="float: right;"></i></th>
                                    <th x-show="colVisibility.commodity">Commodity</th>
                                    <th x-show="colVisibility.pol">Port of Loadi...</th>
                                    <th x-show="colVisibility.pod">Port of Disch...</th>
                                    <th x-show="colVisibility.carrier">Carrier</th>
                                    <th x-show="colVisibility.sales">Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotations as $quote)
                                <tr x-show="matchFilters({quote_no: '{{ $quote->quote_no }}', customer_id: '{{ $quote->customer_id }}', pol_id: '{{ $quote->pol_id }}', pod_id: '{{ $quote->pod_id }}', status: '{{ $quote->status }}', sales_person_id: '{{ $quote->sales_person_id }}'})">
                                    <td x-show="colVisibility.select" style="text-align: center;"><input type="radio" name="quote_sel" :checked="selectedQuote && selectedQuote.quote_no === '{{ $quote->quote_no }}'" @click="selectQuote({quote_id: '{{ $quote->id }}', quote_no: '{{ $quote->quote_no }}', mbl_no: '', hbl_no: '', eta: '{{ $quote->expiry_date ? $quote->expiry_date->format('Y-m-d') : '' }}', etd: '{{ $quote->quote_date ? $quote->quote_date->format('Y-m-d') : '' }}', customer: '{{ $quote->customer->name ?? '' }}', customer_id: '{{ $quote->customer_id }}', sales: '{{ $quote->salesPerson->name ?? '' }}', sales_person_id: '{{ $quote->sales_person_id }}', pol_id: '{{ $quote->pol_id }}', pod_id: '{{ $quote->pod_id }}', pol_name: '{{ $quote->pol->name ?? '' }}', pod_name: '{{ $quote->pod->name ?? '' }}', carrier_name: '', oversea_agent: '', service_term: '{{ $quote->service_term ?? '' }}', op: '', incoterms: '{{ $quote->incoterms_id ?? '' }}', incoterms_id: '{{ $quote->incoterms_id ?? '' }}', detail: '{{ $quote->internal_remark ?? '' }}', ship_mode: '{{ $quote->transport_mode ?? 'FCL' }}'})"></td>
                                    <td x-show="colVisibility.quote_no"><a href="#" style="color: #3b82f6; font-weight: 600;">{{ $quote->quote_no }}</a></td>
                                    <td x-show="colVisibility.valid_date">{{ $quote->quote_date ? $quote->quote_date->format('m-d-Y') : '' }} ~ {{ $quote->expiry_date ? $quote->expiry_date->format('m-d-Y') : '' }}</td>
                                    <td x-show="colVisibility.status"><span style="background: {{ $quote->status === 'ACCEPTED' ? '#10b981' : '#64748b' }}; color: #fff; padding: 1px 4px; border-radius: 2px; font-size: 9px; font-weight: 600;">{{ $quote->status }}</span></td>
                                    <td x-show="colVisibility.creation_date">{{ $quote->created_at->format('Y-m-d') }}</td>
                                    <td x-show="colVisibility.commodity">-</td>
                                    <td x-show="colVisibility.pol">{{ $quote->pol->name ?? '' }}</td>
                                    <td x-show="colVisibility.pod">{{ $quote->pod->name ?? '' }}</td>
                                    <td x-show="colVisibility.carrier">-</td>
                                    <td x-show="colVisibility.sales">{{ $quote->salesPerson->name ?? 'DEMO' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>

                    <!-- Step 2 Content -->
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
                                <div class="form-group-gf"><label class="form-label-gf">ETD</label><div class="form-input-container"><input type="date" class="form-control-gf" x-model="quoteForm.etd"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf" style="color: #ef4444;">*Customer</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="quoteForm.customer"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Oversea Agent</label><div class="form-input-container"><span x-text="quoteForm.oversea_agent || quoteForm.customer || '-'" style="font-size: 10px; color: #334155;"></span></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Sales</label><div class="form-input-container"><span x-text="quoteForm.sales" style="font-size: 10px; color: #334155;"></span></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Incoterms</label><div class="form-input-container"><span x-text="quoteForm.incoterms" style="font-size: 10px; color: #334155;"></span></div></div>
                            </div>
                            <div class="main-grid">
                                <div class="form-group-gf"><label class="form-label-gf" style="color: #ef4444;">*HB/L No.</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="quoteForm.hbl_no"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf" style="color: #ef4444;">*ETA</label><div class="form-input-container"><input type="date" class="form-control-gf" x-model="quoteForm.eta"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Ship Mode</label><div class="form-input-container">
                                     <select class="form-control-gf" x-model="quoteForm.ship_mode">
                                         <option value="FCL">FCL</option>
                                         <option value="LCL">LCL</option>
                                     </select>
                                 </div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Service Term</label><div class="form-input-container"><span x-text="quoteForm.service_term" style="font-size: 10px; color: #334155;"></span></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">OP</label><div class="form-input-container"><span x-text="quoteForm.op" style="font-size: 10px; color: #334155;"></span></div></div>
                            </div>
                        </div>
                        <div class="main-grid" style="margin-top: 4px;">
                            <div class="form-group-gf"><label class="form-label-gf">Detail</label><div class="form-input-container"><input type="text" class="form-control-gf" x-model="quoteForm.detail"></div></div>
                        </div>
                    </div>

                    <!-- Step 3 Content -->
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
                    <button type="button" class="btn-default-gf" @click="showQuoteModal = false">Cancel</button>
                    <button type="button" x-show="quoteStep > 1" class="btn-default-gf" @click="quoteStep--">Back</button>

                    <button type="button" x-show="quoteStep < 3"
                            :class="((quoteStep === 1 && !selectedQuote) || (quoteStep === 2 && (!quoteForm.mbl_no || !quoteForm.hbl_no || !quoteForm.customer || !quoteForm.eta))) ? 'btn-gofreight opacity-50 cursor-not-allowed' : 'btn-gofreight'"
                            :disabled="(quoteStep === 1 && !selectedQuote) || (quoteStep === 2 && (!quoteForm.mbl_no || !quoteForm.hbl_no || !quoteForm.customer || !quoteForm.eta))"
                            @click="quoteStep++">Next <i class="fa fa-arrow-right"></i></button>

                    <button type="button" x-show="quoteStep === 3" class="btn-gofreight" @click="confirmQuoteSelection"><i class="fa fa-check"></i> Confirm</button>
                </div>
            </div>
        </div>
    </div>

    </div>
    </form>

    <div id="toast-container" class="toast-container"></div>
    <script>
        function showToast(type, msg) {
            const icons = { success: 'check-circle', error: 'times-circle', info: 'info-circle', warning: 'exclamation-triangle' };
            const t = document.createElement('div');
            t.className = 'toast ' + type;
            t.innerHTML = '<i class="fa fa-' + (icons[type] || 'info-circle') + '"></i> ' + msg;
            document.getElementById('toast-container').appendChild(t);
            setTimeout(() => t.remove(), 7000);
        }
    </script>
</x-layout>
