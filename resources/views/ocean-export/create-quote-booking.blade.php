<x-layout title="New Booking from Quote">
    @push('styles')
    <x-form-styles />
    <style>
        .hc-stepper { display: flex; align-items: center; justify-content: center; margin-bottom: 30px; padding: 0 50px; }
        .step { display: flex; flex-direction: column; align-items: center; gap: 8px; position: relative; z-index: 1; }
        .step-id { width: 32px; height: 32px; border-radius: 50%; background: #fff; border: 2px solid #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #64748b; font-size: 12px; }
        .step.active .step-id { background: #3b82f6; border-color: #3b82f6; color: #fff; }
        .step.completed .step-id { background: #22c55e; border-color: #22c55e; color: #fff; }
        .step-title { font-size: 10px; font-weight: 600; color: #64748b; text-transform: uppercase; }
        .step-divider { flex: 1; height: 2px; background: #e2e8f0; margin: 0 15px; margin-bottom: 20px; }
        .step-divider.active { background: #3b82f6; }
        .form-grid-modal { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .form-group-sm { margin-bottom: 10px; }
        .form-label-sm { font-size: 11px; color: #64748b; margin-bottom: 3px; display: block; font-weight: 600; }
        .form-control-sm { height: 22px; font-size: 11px; padding: 2px 6px; border-radius: 2px; border: 1px solid #cbd5e1; width: 100%; }
        .modal-table-container { border: 1px solid #e2e8f0; margin-top: 20px; max-height: 300px; overflow: auto; }
        .modal-table { width: 100%; border-collapse: collapse; font-size: 10px; }
        .modal-table th { background: #f8fafc; padding: 6px 10px; text-align: left; font-weight: 700; color: #475569; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; position: sticky; top: 0; }
        .modal-table td { padding: 6px 10px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; vertical-align: middle; }
        .modal-table tr:hover { background: #f8fafc; }
        .status-badge { padding: 2px 8px; border-radius: 20px; color: #fff; font-size: 9px; font-weight: 700; text-transform: uppercase; }
    </style>
    @endpush

    <div style="opacity: 0.3; pointer-events: none; padding: 15px;">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
                <li>Ocean Export <i class="fa fa-angle-right"></i></li>
                <li><span style="color: #333; font-weight: 700;">New Booking</span></li>
            </ul>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h1 class="caption-subject" style="font-size: 18px;">Create Ocean Export Booking</h1>
            <div style="display: flex; gap: 8px;">
                <button class="btn-gofreight"><i class="fa fa-save"></i> SAVE BOOKING</button>
            </div>
        </div>
        <div style="height: 500px; border: 1px solid #e2e8f0; background: #fff; border-radius: 4px;"></div>
    </div>

    <div class="modal-overlay" x-data="window.quoteSelectorModule()">
        <div class="modal-container">
            <div class="modal-header">
                <span style="font-size: 13px; font-weight: 700; color: #0f172a;">Load Quotation Data</span>
                <button @click="closeModal()" style="background: none; border: none; font-size: 20px; color: #94a3b8; cursor: pointer;">&times;</button>
            </div>

            <div class="modal-body">
                    <!-- Wizard Steps Header -->
                    <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div class="wizard-circle" :style="step >= 1 ? 'background: #3b82f6;' : 'background: #cbd5e1;'">
                                <template x-if="step > 1"><i class="fa fa-check"></i></template>
                                <template x-if="step === 1"><span>1</span></template>
                            </div>
                            <span :style="step >= 1 ? 'color: #1e293b; font-size: 10px; font-weight: 600;' : 'color: #94a3b8; font-size: 10px;'">Select Quotation</span>
                        </div>
                        <div style="height: 1px; width: 20px; background: #e2e8f0;"></div>

                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div class="wizard-circle" :style="step >= 2 ? 'background: #3b82f6;' : 'background: #cbd5e1;'">
                                <template x-if="step > 2"><i class="fa fa-check"></i></template>
                                <template x-if="step <= 2"><span>2</span></template>
                            </div>
                            <span :style="step >= 2 ? 'color: #1e293b; font-size: 10px; font-weight: 600;' : 'color: #94a3b8; font-size: 10px;'">Fill in shipment data</span>
                        </div>
                        <div style="height: 1px; width: 20px; background: #e2e8f0;"></div>

                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div class="wizard-circle" :style="step >= 3 ? 'background: #3b82f6;' : 'background: #cbd5e1;'">
                                <span>3</span>
                            </div>
                            <span :style="step >= 3 ? 'color: #1e293b; font-size: 10px; font-weight: 600;' : 'color: #94a3b8; font-size: 10px;'">Select invoice items</span>
                        </div>
                    </div>

                <div x-show="step === 1">
                    <div class="form-grid-modal">
                        <div class="form-group-sm">
                            <label class="form-label-sm">Customer</label>
                            <select class="form-control-sm" x-model="filters.customer_id">
                                <option value="">Select...</option>
                                <template x-for="c in customers" :key="c.id">
                                    <option :value="c.id" x-text="c.name"></option>
                                </template>
                            </select>
                        </div>
                        <div class="form-group-sm">
                            <label class="form-label-sm">Valid Date</label>
                            <div style="display: flex; gap: 5px;">
                                <input type="date" class="form-control-sm" x-model="filters.date_from">
                                <input type="date" class="form-control-sm" x-model="filters.date_to">
                            </div>
                        </div>
                        <div class="form-group-sm">
                            <label class="form-label-sm">Port of Loading</label>
                            <select class="form-control-sm" x-model="filters.pol_id">
                                <option value="">Select...</option>
                                <template x-for="p in ports" :key="p.id">
                                    <option :value="p.id" x-text="p.name"></option>
                                </template>
                            </select>
                        </div>
                        <div class="form-group-sm">
                            <label class="form-label-sm">Port of Discharge</label>
                            <select class="form-control-sm" x-model="filters.pod_id">
                                <option value="">Select...</option>
                                <template x-for="p in ports" :key="p.id">
                                    <option :value="p.id" x-text="p.name"></option>
                                </template>
                            </select>
                        </div>
                        <div class="form-group-sm">
                            <label class="form-label-sm">Sales</label>
                            <select class="form-control-sm" x-model="filters.sales_person_id">
                                <option value="">Select...</option>
                                <template x-for="u in users" :key="u.id">
                                    <option :value="u.id" x-text="u.name"></option>
                                </template>
                            </select>
                        </div>
                        <div class="form-group-sm">
                            <label class="form-label-sm">Quote No.</label>
                            <input type="text" class="form-control-sm" x-model="filters.quote_no">
                        </div>
                        <div class="form-group-sm">
                            <label class="form-label-sm">Status</label>
                            <select class="form-control-sm" x-model="filters.status">
                                <option value="">All</option>
                                <template x-for="s in statuses" :key="s">
                                    <option :value="s" x-text="s"></option>
                                </template>
                            </select>
                        </div>
                        <div class="form-group-sm">
                            <label class="form-label-sm">Commodity</label>
                            <input type="text" class="form-control-sm" x-model="filters.commodity">
                        </div>
                    </div>

                    <div style="text-align: center; margin: 15px 0;">
                        <button class="btn-default-gf" @click="clearFilters()">Clear</button>
                        <button class="btn-gofreight" @click="applyFilters()">Search</button>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #ebedf2;">

                    <div class="modal-table-container">
                        <table class="modal-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align: center;">Select</th>
                                    <th>Quote No.</th>
                                    <th>Valid Date</th>
                                    <th>Status</th>
                                    <th>Creation Date</th>
                                    <th>Commodity</th>
                                    <th>POL</th>
                                    <th>POD</th>
                                    <th>Carrier</th>
                                    <th>Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(q, idx) in filteredQuotes" :key="q.id">
                                    <tr @click="selectQuote(q.id)" style="cursor: pointer;" :style="selectedQuoteId === q.id ? 'background: #eef1f5' : ''">
                                        <td style="text-align: center;"><input type="radio" name="q" :checked="selectedQuoteId === q.id" @click.stop="selectQuote(q.id)"></td>
                                        <td style="color: #337ab7; font-weight: 700;" x-text="q.quote_no"></td>
                                        <td x-text="q.expiry_date ? q.expiry_date.split(' ')[0] : '-'"></td>
                                        <td><span class="status-badge" :style="'background: ' + (q.status === 'ACCEPTED' ? '#26c281' : '#888')" x-text="q.status || '-'"></span></td>
                                        <td x-text="q.quote_date ? q.quote_date.split(' ')[0] : '-'"></td>
                                        <td x-text="q.commodity || '-'"></td>
                                        <td x-text="q.pol_name || '-'"></td>
                                        <td x-text="q.pod_name || '-'"></td>
                                        <td x-text="q.carrier || '-'"></td>
                                        <td x-text="q.sales_name || '-'"></td>
                                    </tr>
                                </template>
                                <tr x-show="filteredQuotes.length === 0">
                                    <td colspan="10" style="text-align: center; padding: 20px; color: #999;">No quotations found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div x-show="step === 2">
                    <h5 style="font-size: 13px; font-weight: 700; color: #4b77be; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Fill in the Booking Information</h5>
                    <div class="form-grid-modal">
                        <div class="form-group-sm">
                            <label class="form-label-sm">Booking No.</label>
                            <input type="text" class="form-control-sm" :value="nextBookingNo" disabled style="background:#f5f5f5;">
                        </div>
                        <div class="form-group-sm">
                            <label class="form-label-sm">Booking Date</label>
                            <input type="date" class="form-control-sm" x-model="bookingForm.booking_date">
                        </div>
                        <div class="form-group-sm">
                            <label class="form-label-sm">ETD</label>
                            <input type="date" class="form-control-sm" x-model="bookingForm.etd">
                        </div>
                        <div class="form-group-sm">
                            <label class="form-label-sm">ETA</label>
                            <input type="date" class="form-control-sm" x-model="bookingForm.eta">
                        </div>
                        <div class="form-group-sm" style="grid-column: span 2;">
                            <label class="form-label-sm">Customer</label>
                            <input type="text" class="form-control-sm" :value="selectedQuote ? selectedQuote.customer_name : ''" disabled style="background:#f5f5f5;">
                        </div>
                        <div class="form-group-sm">
                            <label class="form-label-sm">Ship Mode</label>
                            <select class="form-control-sm" x-model="bookingForm.ship_mode">
                                <option value="FCL">FCL</option>
                                <option value="LCL">LCL</option>
                            </select>
                        </div>
                        <div class="form-group-sm">
                            <label class="form-label-sm">POL</label>
                            <input type="text" class="form-control-sm" :value="selectedQuote ? selectedQuote.pol_name : ''" disabled style="background:#f5f5f5;">
                        </div>
                        <div class="form-group-sm">
                            <label class="form-label-sm">POD</label>
                            <input type="text" class="form-control-sm" :value="selectedQuote ? selectedQuote.pod_name : ''" disabled style="background:#f5f5f5;">
                        </div>
                        <div class="form-group-sm">
                            <label class="form-label-sm">Sales Person</label>
                            <input type="text" class="form-control-sm" :value="selectedQuote ? selectedQuote.sales_name : ''" disabled style="background:#f5f5f5;">
                        </div>
                        <div class="form-group-sm" style="grid-column: span 3;">
                            <label class="form-label-sm">Items from Quotation</label>
                            <div style="font-size: 12px; color: #666;" x-text="(selectedQuote ? selectedQuote.items_count : 0) + ' charge items will be copied'"></div>
                        </div>
                    </div>
                </div>

                <div x-show="step === 3">
                    <h5 style="font-size: 13px; font-weight: 700; color: #4b77be; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Select Freight Item(s)</h5>
                    <div style="margin-bottom: 15px;">
                        <label style="font-size: 11px;"><input type="checkbox" x-model="saveAsDraft"> Save as a draft invoice</label>
                    </div>
                    <div class="modal-table-container">
                        <table class="modal-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align: center;"><input type="checkbox" @click="toggleAllItems" :checked="allItemsSelected"></th>
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
                                <template x-for="(item, idx) in selectedQuoteItems" :key="idx">
                                    <tr>
                                        <td style="text-align: center;"><input type="checkbox" x-model="selectedItems" :value="item.id"></td>
                                        <td x-text="item.charge_code || '-'"></td>
                                        <td x-text="item.charge_name || '-'"></td>
                                        <td x-text="item.unit || '-'"></td>
                                        <td x-text="item.currency_id || '-'"></td>
                                        <td x-text="item.qty || '0'"></td>
                                        <td x-text="item.rate || '0'"></td>
                                        <td x-text="item.amount || '0'"></td>
                                    </tr>
                                </template>
                                <tr x-show="selectedQuoteItems.length === 0">
                                    <td colspan="8" style="text-align: center; padding: 20px; color: #999;">No charge items in this quotation</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-default-gf" @click="closeModal()">Cancel</button>
                <button class="btn-default-gf" x-show="step > 1" @click="step--">Back</button>
                <button class="btn-gofreight" x-show="step < 3" @click="step++" :disabled="!selectedQuoteId">Next <i class="fa fa-arrow-right"></i></button>
                <button class="btn-gofreight" x-show="step === 3" @click="finish()"><i class="fa fa-check"></i> Finish</button>
            </div>
        </div>
    </div>

    <script>
        window.quoteSelectorModule = function() {
            return {
                step: 1,
                selectedQuoteId: null,

                customers: @json($customers),
                ports: @json($ports),
                users: @json($users),
                statuses: @json($statuses),
                quotations: @json($quotationsData),
                nextBookingNo: '{{ $nextBookingNo }}',

                filters: {
                    customer_id: '',
                    date_from: '',
                    date_to: '',
                    pol_id: '',
                    pod_id: '',
                    sales_person_id: '',
                    quote_no: '',
                    status: '',
                    commodity: '',
                },

                bookingForm: {
                    booking_date: new Date().toISOString().split('T')[0],
                    etd: '',
                    eta: '',
                    ship_mode: 'FCL',
                },

                saveAsDraft: false,
                selectedItems: [],

                get selectedQuote() {
                    if (!this.selectedQuoteId) return null;
                    return this.quotations.find(q => q.id === this.selectedQuoteId) || null;
                },

                get selectedQuoteItems() {
                    return this.selectedQuote ? this.selectedQuote.items : [];
                },

                get allItemsSelected() {
                    return this.selectedQuoteItems.length > 0 && this.selectedItems.length === this.selectedQuoteItems.length;
                },

                get filteredQuotes() {
                    let result = [...this.quotations];
                    if (this.filters.customer_id) {
                        result = result.filter(q => q.customer_id == this.filters.customer_id);
                    }
                    if (this.filters.date_from) {
                        result = result.filter(q => q.expiry_date >= this.filters.date_from);
                    }
                    if (this.filters.date_to) {
                        result = result.filter(q => q.expiry_date <= this.filters.date_to);
                    }
                    if (this.filters.pol_id) {
                        result = result.filter(q => q.pol_id == this.filters.pol_id);
                    }
                    if (this.filters.pod_id) {
                        result = result.filter(q => q.pod_id == this.filters.pod_id);
                    }
                    if (this.filters.sales_person_id) {
                        result = result.filter(q => q.sales_person_id == this.filters.sales_person_id);
                    }
                    if (this.filters.quote_no) {
                        result = result.filter(q => q.quote_no && q.quote_no.toLowerCase().includes(this.filters.quote_no.toLowerCase()));
                    }
                    if (this.filters.status) {
                        result = result.filter(q => q.status === this.filters.status);
                    }
                    return result;
                },

                selectQuote(id) {
                    this.selectedQuoteId = id;
                },

                clearFilters() {
                    this.filters = {
                        customer_id: '',
                        date_from: '',
                        date_to: '',
                        pol_id: '',
                        pod_id: '',
                        sales_person_id: '',
                        quote_no: '',
                        status: '',
                        commodity: '',
                    };
                },

                applyFilters() {
                    this.step = 1;
                },

                toggleAllItems() {
                    if (this.allItemsSelected) {
                        this.selectedItems = [];
                    } else {
                        this.selectedItems = this.selectedQuoteItems.map(i => i.id);
                    }
                },

                closeModal() {
                    window.location.href = '/ocean-export/booking/list';
                },

                finish() {
                    if (!this.selectedQuoteId) return;
                    let ids = this.selectedItems.length ? this.selectedItems.join(',') : '';
                    window.location.href = '/ocean-export/booking/create?quote_id=' + this.selectedQuoteId + '&items=' + ids + '&ship_mode=' + this.bookingForm.ship_mode + '&etd=' + this.bookingForm.etd + '&eta=' + this.bookingForm.eta;
                }
            }
        };
    </script>
</x-layout>
