<x-layout title="New Booking from Quote">
    @push('styles')
    <x-form-styles />
    <style>
        [x-cloak] { display: none !important; }
        .wizard-circle { width: 18px; height: 18px; min-width: 18px; min-height: 18px; flex-shrink: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 10px; font-weight: bold; }
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 999999; }
        .modal-container { background: #fff; border-radius: 4px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); width: 900px; max-width: 95vw; display: flex; flex-direction: column; overflow: hidden; }
        .form-group-gf { margin-bottom: 0; }
        .form-label-gf { font-size: 11px; font-weight: 600; color: #333; margin-bottom: 2px; }
        .form-control-gf { width: 100%; border: 1px solid #ccc; border-radius: 2px; font-size: 12px; padding: 2px 6px; box-shadow: inset 0 1px 1px rgba(0,0,0,.075); transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s; }
        .form-control-gf:focus { border-color: #66afe9; outline: 0; box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6); }
        .table-custom { width: 100%; border-collapse: collapse; font-size: 11px; }
        .table-custom th, .table-custom td { border: 1px solid #ddd; padding: 4px 8px; text-align: left; vertical-align: middle; }
        .table-custom th { background: #f9fafb; font-weight: 600; color: #333; }
        .table-custom tr:hover td { background-color: #f5f5f5; }
        .btn-gofreight { background-color: #1abc9c; color: white; border: 1px solid #1abc9c; padding: 4px 10px; font-size: 12px; font-weight: 600; border-radius: 3px; cursor: pointer; transition: background 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 4px; }
        .btn-gofreight:hover { background-color: #16a085; }
        .btn-default-gf { background-color: #fff; color: #333; border: 1px solid #ccc; padding: 4px 10px; font-size: 12px; font-weight: 600; border-radius: 3px; cursor: pointer; transition: background 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 4px; }
        .btn-default-gf:hover { background-color: #e6e6e6; border-color: #adadad; }
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

    <div class="modal-overlay" x-data="window.quoteSelectorModule()" x-cloak>
        <div class="modal-container">
            <div style="padding: 15px; border-bottom: 1px solid #e5e5e5; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; font-size: 18px; color: #333; font-weight: 500;">Load Quotation Data</h4>
                <button type="button" @click="closeQuoteModal()" style="background: none; border: none; font-size: 21px; cursor: pointer; color: #000; opacity: .2;">&times;</button>
            </div>

            <div class="modal-body" style="padding: 20px;">
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
                        <div style="width: 33.33%; padding: 5px;">
                            <div class="form-group-gf" style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                                <label class="form-label-gf" style="text-align: left; width: auto;">Customer</label>
                                <select class="form-control-gf" style="height: 24px;" x-model="filters.customer_id">
                                    <option value="">Select...</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div style="width: 33.33%; padding: 5px;">
                            <div class="form-group-gf" style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                                <label class="form-label-gf" style="text-align: left; width: auto;">Valid Date</label>
                                <div style="display: flex; width: 100%; gap: 5px;">
                                    <input type="date" class="form-control-gf" style="height: 24px; flex: 1;" x-model="filters.date_from">
                                    <input type="date" class="form-control-gf" style="height: 24px; flex: 1;" x-model="filters.date_to">
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
                                <label class="form-label-gf" style="text-align: left; width: auto;">Departure (POL)</label>
                                <select class="form-control-gf" style="height: 24px;" x-model="filters.pol_id">
                                    <option value="">Select...</option>
                                    @foreach($ports as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div style="width: 33.33%; padding: 5px;">
                            <div class="form-group-gf" style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                                <label class="form-label-gf" style="text-align: left; width: auto;">Destination (POD)</label>
                                <select class="form-control-gf" style="height: 24px;" x-model="filters.pod_id">
                                    <option value="">Select...</option>
                                    @foreach($ports as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div style="width: 33.33%; padding: 5px;">
                            <div class="form-group-gf" style="display: flex; flex-direction: column; align-items: flex-start; gap: 2px;">
                                <label class="form-label-gf" style="text-align: left; width: auto;">Sales</label>
                                <select class="form-control-gf" style="height: 24px;" x-model="filters.sales_person_id">
                                    <option value="">Select...</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
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
                                    @foreach($statuses as $s)
                                        <option value="{{ $s }}">{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div style="width: 33.33%; padding: 5px;">
                            <!-- Empty block for alignment if OP is not there, or leave empty -->
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
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(quote, idx) in quotations" :key="quote.id">
                                    <tr x-show="matchFilters(quote)" style="border-bottom: 1px solid #e7ecf1;">
                                        <td style="text-align: center; padding: 6px;">
                                            <input type="radio" name="quote_sel" :checked="selectedQuote && selectedQuote.id === quote.id" @click="selectQuote(quote)">
                                        </td>
                                        <td style="padding: 6px;"><a href="#" style="color: #337ab7; text-decoration: none;" x-text="quote.quote_no"></a></td>
                                        <td style="padding: 6px;" x-text="quote.quote_date + ' ~ ' + quote.expiry_date"></td>
                                        <td style="padding: 6px;">
                                            <span style="color: #fff; padding: 2px 5px; border-radius: 2px; font-size: 10px;" 
                                                  :style="quote.status === 'ACCEPTED' ? 'background: #26c281;' : 'background: #888;'" 
                                                  x-text="quote.status || '-'"></span>
                                        </td>
                                        <td style="padding: 6px;" x-text="quote.quote_date"></td>
                                        <td style="padding: 6px;" x-text="quote.commodity || '-'"></td>
                                        <td style="padding: 6px;" x-text="quote.pol_name || '-'"></td>
                                        <td style="padding: 6px;" x-text="quote.pod_name || '-'"></td>
                                        <td style="padding: 6px;" x-text="quote.carrier || '-'"></td>
                                        <td style="padding: 6px;" x-text="quote.sales_name || '-'"></td>
                                    </tr>
                                </template>
                                <tr x-show="quotations.filter(q => matchFilters(q)).length === 0">
                                    <td colspan="10" style="text-align: center; padding: 20px; color: #999;">No quotations found</td>
                                </tr>
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
                                <th style="width: 50px; text-align: center;">Select</th>
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
                                <td x-text="selectedQuote ? selectedQuote.carrier : 'DEMO CARRIER'"></td>
                            </tr>
                            <tr x-show="!selectedQuote">
                                <td colspan="5" style="text-align: center; color: #999; padding: 10px;">No route information in this quotation</td>
                            </tr>
                        </tbody>
                    </table>

                    <h5 style="font-size: 13px; font-weight: 600; color: #333; margin: 0 0 10px 0; border-bottom: 1px solid #eee; padding-bottom: 5px;">Fill in the Booking Information</h5>
                    <table class="table-custom">
                        <tbody>
                            <tr>
                                <td style="background: #f9fafb; font-weight: 600; width: 15%;">Booking No.</td>
                                <td style="width: 35%;">
                                    <input type="text" class="form-control-gf" :value="nextBookingNo" disabled style="background:#eee; height: 24px; border: none;">
                                </td>
                                <td style="background: #f9fafb; font-weight: 600; width: 15%;"><span style="color: red;">*</span>Booking Date</td>
                                <td style="width: 35%;">
                                    <div style="display: flex; width: 100%;">
                                        <input type="date" class="form-control-gf" x-model="bookingForm.booking_date" style="height: 24px; border-right: none;">
                                        <div style="background: #eee; border: 1px solid #ccc; padding: 0 8px; display: flex; align-items: center; color: #666;"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="background: #f9fafb; font-weight: 600;"><span style="color: red;">*</span>ETD</td>
                                <td>
                                    <div style="display: flex; width: 100%;">
                                        <input type="date" class="form-control-gf" x-model="bookingForm.etd" style="height: 24px; border-right: none;">
                                        <div style="background: #eee; border: 1px solid #ccc; padding: 0 8px; display: flex; align-items: center; color: #666;"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </td>
                                <td style="background: #f9fafb; font-weight: 600;">ETA</td>
                                <td>
                                    <div style="display: flex; width: 100%;">
                                        <input type="date" class="form-control-gf" x-model="bookingForm.eta" style="height: 24px; border-right: none;">
                                        <div style="background: #eee; border: 1px solid #ccc; padding: 0 8px; display: flex; align-items: center; color: #666;"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="background: #f9fafb; font-weight: 600;"><span style="color: red;">*</span>Customer</td>
                                <td colspan="3"><input type="text" class="form-control-gf" x-model="bookingForm.customer_name" style="height: 24px;" readonly style="background-color: #eee;"></td>
                            </tr>
                            <tr>
                                <td style="background: #f9fafb; font-weight: 600;">Ship Mode</td>
                                <td>
                                    <select class="form-control-gf" x-model="bookingForm.ship_mode" style="height: 24px;">
                                        <option value="FCL">FCL</option>
                                        <option value="LCL">LCL</option>
                                    </select>
                                </td>
                                <td style="background: #f9fafb; font-weight: 600;">Sales Person</td>
                                <td><input type="text" class="form-control-gf" x-model="bookingForm.sales_name" style="height: 24px;" readonly style="background-color: #eee;"></td>
                            </tr>
                            <tr>
                                <td style="background: #f9fafb; font-weight: 600;">POL</td>
                                <td><input type="text" class="form-control-gf" x-model="bookingForm.pol_name" style="height: 24px;" readonly style="background-color: #eee;"></td>
                                <td style="background: #f9fafb; font-weight: 600;">POD</td>
                                <td><input type="text" class="form-control-gf" x-model="bookingForm.pod_name" style="height: 24px;" readonly style="background-color: #eee;"></td>
                            </tr>
                            <tr>
                                <td style="background: #f9fafb; font-weight: 600;">Items from Quotation</td>
                                <td colspan="3" style="color: #666; font-style: italic;"><span x-text="selectedQuote ? selectedQuote.items_count : 0"></span> charge items will be copied</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Step 3 Content -->
                <div x-show="quoteStep === 3" x-cloak>
                    <h5 style="font-size: 13px; font-weight: 600; color: #333; margin: 0 0 10px 0; border-bottom: 1px solid #eee; padding-bottom: 5px;">Select Freight Item(s)</h5>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                        <label style="display: flex; align-items: center; gap: 5px; font-size: 11px;">
                            <input type="checkbox" x-model="saveAsDraft"> Save as a draft invoice
                        </label>
                        <div style="display: flex; align-items: center; gap: 5px; font-size: 11px;">
                            <span>Applied Unit</span> <i class="fa fa-info-circle" style="color: #4b77be;"></i>
                            <select class="form-control-gf" style="width: 100px; height: 22px;"></select>
                        </div>
                    </div>

                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th style="width: 70px; text-align: center;"><input type="checkbox" @click="toggleAllItems" :checked="allItemsSelected"> Select</th>
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
                                        <td style="text-align: center; padding: 6px;"><input type="checkbox" x-model="item.selected" @change="updateSelection()"></td>
                                        <td style="padding: 6px;" x-text="item.charge_code || '-'"></td>
                                        <td style="padding: 6px;" x-text="item.charge_name || '-'"></td>
                                        <td style="padding: 6px;" x-text="item.unit || '-'"></td>
                                        <td style="padding: 6px;" x-text="item.currency_id || '-'"></td>
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
                <button type="button" class="btn-default-gf" style="padding: 6px 12px; font-size: 12px; border-radius: 4px;" x-show="quoteStep > 1" @click="quoteStep--">Back</button>
                <button type="button" class="btn-gofreight" 
                        :disabled="(quoteStep === 1 && !selectedQuote) || (quoteStep === 2 && (!bookingForm.booking_date || !bookingForm.etd))" 
                        :style="((quoteStep === 1 && !selectedQuote) || (quoteStep === 2 && (!bookingForm.booking_date || !bookingForm.etd))) ? 'background: #ccc; border: none; color: #666; cursor: not-allowed; opacity: 0.7; padding: 6px 12px; font-size: 12px; border-radius: 4px;' : 'background: #1abc9c; padding: 6px 12px; font-size: 12px; border-radius: 4px;'" 
                        x-show="quoteStep < 3" @click="quoteStep++">Next <i class="fa fa-arrow-right"></i></button>
                <button type="button" class="btn-gofreight" style="background: #1abc9c; padding: 6px 12px; font-size: 12px; border-radius: 4px;" x-show="quoteStep === 3" x-cloak @click="confirmQuoteSelection()"><i class="fa fa-check"></i> Finish</button>
            </div>
        </div>
    </div>

    <script>
        window.quoteSelectorModule = function() {
            return {
                quoteStep: 1,
                selectedQuote: null,

                quotations: @json($quotationsData).map(q => ({
                    ...q,
                    items: q.items ? q.items.map(i => ({...i, selected: true})) : []
                })),
                
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
                
                activeFilters: {
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
                    customer_name: '',
                    sales_name: '',
                    pol_name: '',
                    pod_name: ''
                },

                saveAsDraft: false,

                get allItemsSelected() {
                    if (!this.selectedQuote || !this.selectedQuote.items || this.selectedQuote.items.length === 0) return false;
                    return this.selectedQuote.items.every(i => i.selected);
                },

                matchFilters(quote) {
                    if (this.activeFilters.customer_id && quote.customer_id != this.activeFilters.customer_id) return false;
                    if (this.activeFilters.pol_id && quote.pol_id != this.activeFilters.pol_id) return false;
                    if (this.activeFilters.pod_id && quote.pod_id != this.activeFilters.pod_id) return false;
                    if (this.activeFilters.sales_person_id && quote.sales_person_id != this.activeFilters.sales_person_id) return false;
                    if (this.activeFilters.status && quote.status !== this.activeFilters.status) return false;
                    if (this.activeFilters.quote_no && !quote.quote_no.toLowerCase().includes(this.activeFilters.quote_no.toLowerCase())) return false;
                    if (this.activeFilters.commodity && (!quote.commodity || !quote.commodity.toLowerCase().includes(this.activeFilters.commodity.toLowerCase()))) return false;
                    
                    if (this.activeFilters.date_from && quote.expiry_date < this.activeFilters.date_from) return false;
                    if (this.activeFilters.date_to && quote.expiry_date > this.activeFilters.date_to) return false;
                    
                    return true;
                },

                selectQuote(quote) {
                    this.selectedQuote = quote;
                    
                    this.bookingForm.customer_name = quote.customer_name;
                    this.bookingForm.sales_name = quote.sales_name;
                    this.bookingForm.pol_name = quote.pol_name;
                    this.bookingForm.pod_name = quote.pod_name;
                    
                    this.bookingForm.etd = quote.quote_date;
                    this.bookingForm.eta = quote.expiry_date;
                },

                clearSearch() {
                    this.filters = {
                        customer_id: '', date_from: '', date_to: '', pol_id: '', pod_id: '', sales_person_id: '', quote_no: '', status: '', commodity: ''
                    };
                    this.activeFilters = { ...this.filters };
                },

                applySearch() {
                    this.activeFilters = { ...this.filters };
                    this.quoteStep = 1;
                    this.selectedQuote = null;
                },

                toggleAllItems() {
                    if (!this.selectedQuote || !this.selectedQuote.items) return;
                    const val = !this.allItemsSelected;
                    this.selectedQuote.items.forEach(i => i.selected = val);
                },
                
                updateSelection() {
                    // force reactivity if needed
                },

                closeQuoteModal() {
                    window.location.href = '/ocean-export/booking/list';
                },

                confirmQuoteSelection() {
                    if (!this.selectedQuote) return;
                    let selectedItems = this.selectedQuote.items.filter(i => i.selected).map(i => i.id);
                    let ids = selectedItems.length ? selectedItems.join(',') : '';
                    window.location.href = '/ocean-export/booking/create?quote_id=' + this.selectedQuote.id + 
                                           '&items=' + ids + 
                                           '&ship_mode=' + this.bookingForm.ship_mode + 
                                           '&etd=' + this.bookingForm.etd + 
                                           '&eta=' + this.bookingForm.eta;
                }
            }
        };
    </script>
</x-layout>
