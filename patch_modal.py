import re

with open('resources/views/truck/create.blade.php', 'r') as f:
    content = f.read()

# Extract the modal block
start_idx = content.find('<div class="modal" x-show="showQuoteModal" style="display: none;">')
end_idx = content.find('<!-- Toolbar -->', start_idx)

original_modal = content[start_idx:end_idx]

new_modal = """<div class="modal" x-show="showQuoteModal" style="display: none;">
            <div class="modal-dialog" style="width: 800px; max-width: 95%;" @click.stop>
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Load Quotation Data</h4>
                        <button type="button" class="close-btn" @click="closeQuoteModal()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="step-container">
                            <div class="step">
                                <div class="step-id" :class="quoteStep >= 1 ? 'active' : ''">
                                    <template x-if="quoteStep > 1"><i class="fa fa-check"></i></template>
                                    <template x-if="quoteStep === 1"><span>1</span></template>
                                </div>
                                <div class="step-title" :style="quoteStep >= 1 ? 'color:#333' : 'color:#999'">Select Quotation</div>
                            </div>
                            <div class="step-divider" :class="quoteStep > 1 ? 'active' : ''"></div>
                            <div class="step">
                                <div class="step-id" :class="quoteStep >= 2 ? 'active' : ''">
                                    <template x-if="quoteStep > 2"><i class="fa fa-check"></i></template>
                                    <template x-if="quoteStep <= 2"><span>2</span></template>
                                </div>
                                <div class="step-title" :style="quoteStep >= 2 ? 'color:#333' : 'color:#999'">Fill in shipment data</div>
                            </div>
                            <div class="step-divider" :class="quoteStep > 2 ? 'active' : ''"></div>
                            <div class="step">
                                <div class="step-id" :class="quoteStep >= 3 ? 'active' : ''"><span>3</span></div>
                                <div class="step-title" :style="quoteStep >= 3 ? 'color:#333' : 'color:#999'">Select invoice items</div>
                            </div>
                        </div>
                        
                        <!-- STEP 1: Search -->
                        <div x-show="quoteStep === 1">
                            <div class="form-grid-4" style="margin-bottom: 20px;">
                                <div class="form-group-gf">
                                    <span class="form-label-gf">Customer</span>
                                    <div class="form-input-container">
                                        <select class="form-control-gf" x-model="quoteSearch.customer_id">
                                            <option value="">Select...</option>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group-gf">
                                    <span class="form-label-gf">Commodity</span>
                                    <div class="form-input-container">
                                        <input type="text" class="form-control-gf" x-model="quoteSearch.commodity">
                                    </div>
                                </div>
                                <div class="form-group-gf">
                                    <span class="form-label-gf">Sales</span>
                                    <div class="form-input-container">
                                        <select class="form-control-gf" x-model="quoteSearch.sales_id">
                                            <option value="">Select...</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group-gf" style="display:flex; align-items:flex-end; padding-bottom: 2px;">
                                    <button type="button" class="btn-gf" style="margin: 0; background-color: #578ebe;" @click="searchQuotes()">Search</button>
                                    <button type="button" class="btn-default-gf dark" style="margin-left:5px; padding: 6px 12px; font-size: 12px;" @click="resetQuoteSearch()">Clear</button>
                                </div>
                            </div>
                            
                            <table class="memo-table" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Quote No.</th>
                                        <th>Valid Date</th>
                                        <th>Status</th>
                                        <th>Creation Date</th>
                                        <th>Commodity</th>
                                        <th>Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-if="quoteSearch.results.length === 0">
                                        <tr>
                                            <td colspan="7" style="padding: 30px; text-align: center; font-style: italic; color: #888;">No quotations found. Use the search filters above.</td>
                                        </tr>
                                    </template>
                                    <template x-for="(quote, idx) in quoteSearch.results" :key="quote.id">
                                        <tr>
                                            <td><input type="radio" name="selected_quote" :value="quote.id" x-model="quoteSearch.selected_id"></td>
                                            <td x-text="quote.quote_no"></td>
                                            <td x-text="quote.expiry_date"></td>
                                            <td><span x-text="quote.status"></span></td>
                                            <td x-text="quote.created_at ? quote.created_at.substring(0, 10) : ''"></td>
                                            <td x-text="quote.commodity || 'N/A'"></td>
                                            <td x-text="quote.sales_person ? quote.sales_person.name : 'N/A'"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- STEP 2: Quote Data Details -->
                        <div x-show="quoteStep === 2" style="display:none;">
                            <table class="table-form" style="width: 100%; border: 1px solid #ddd; margin-bottom: 15px;">
                                <tbody>
                                    <tr>
                                        <td style="background: #f9f9f9; width: 15%; font-weight: 600; padding: 8px;">Quote No.</td>
                                        <td style="width: 35%; padding: 8px;"><input type="text" class="form-control-gf" x-model="quoteForm.quote_no" readonly></td>
                                        <td style="background: #f9f9f9; width: 15%; font-weight: 600; padding: 8px;">Commodity</td>
                                        <td style="width: 35%; padding: 8px;"><input type="text" class="form-control-gf" x-model="quoteForm.commodity"></td>
                                    </tr>
                                    <tr>
                                        <td style="background: #f9f9f9; font-weight: 600; padding: 8px;">Customer</td>
                                        <td style="padding: 8px;"><input type="text" class="form-control-gf" x-model="quoteForm.customer" readonly style="background-color: #fff;"></td>
                                        <td style="background: #f9f9f9; font-weight: 600; padding: 8px;">Sales</td>
                                        <td style="padding: 8px;"><input type="text" class="form-control-gf" x-model="quoteForm.sales" readonly></td>
                                    </tr>
                                    <tr>
                                        <td style="background: #f9f9f9; font-weight: 600; padding: 8px;">Service Term</td>
                                        <td style="padding: 8px;"><input type="text" class="form-control-gf" x-model="quoteForm.service_term"></td>
                                        <td style="background: #f9f9f9; font-weight: 600; padding: 8px;">Incoterms</td>
                                        <td style="padding: 8px;"><input type="text" class="form-control-gf" x-model="quoteForm.incoterms"></td>
                                    </tr>
                                    <tr>
                                        <td style="background: #f9f9f9; font-weight: 600; padding: 8px;">Package</td>
                                        <td style="padding: 8px;">
                                            <div style="display:flex;gap:5px;">
                                                <input type="number" step="any" class="form-control-gf" x-model="quoteForm.pkg_qty" style="width:40%;">
                                                <input type="text" class="form-control-gf" x-model="quoteForm.pkg_unit" style="width:60%;">
                                            </div>
                                        </td>
                                        <td style="background: #f9f9f9; font-weight: 600; padding: 8px;">Weight</td>
                                        <td style="padding: 8px;">
                                            <div style="display:flex;gap:5px;">
                                                <input type="number" step="any" class="form-control-gf" x-model="quoteForm.weight_kg" placeholder="KG">
                                                <input type="number" step="any" class="form-control-gf" x-model="quoteForm.weight_lb" placeholder="LB">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background: #f9f9f9; font-weight: 600; padding: 8px;">Measurement</td>
                                        <td style="padding: 8px;" colspan="3">
                                            <div style="display:flex;gap:5px;width:50%;">
                                                <input type="number" step="any" class="form-control-gf" x-model="quoteForm.volume_cbm" placeholder="CBM">
                                                <input type="number" step="any" class="form-control-gf" x-model="quoteForm.volume_cft" placeholder="CFT">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- STEP 3: Invoice Items -->
                        <div x-show="quoteStep === 3" style="display:none;">
                            <table class="memo-table">
                                <thead>
                                    <tr>
                                        <th style="width:30px;"><input type="checkbox" @change="quoteCharges.forEach(c => c.selected = $event.target.checked)"></th>
                                        <th>Type</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Qty</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-if="quoteCharges.length === 0">
                                        <tr>
                                            <td colspan="7" style="text-align: center; padding: 20px;">No charges found for this quote.</td>
                                        </tr>
                                    </template>
                                    <template x-for="(chg, idx) in quoteCharges" :key="idx">
                                        <tr>
                                            <td><input type="checkbox" x-model="chg.selected"></td>
                                            <td><span x-text="chg.type === 'AR' ? 'Revenue' : 'Cost'" :style="'color:' + (chg.type === 'AR' ? '#26c281' : '#d05454')"></span></td>
                                            <td x-text="chg.charge_code"></td>
                                            <td x-text="chg.charge_name"></td>
                                            <td x-text="chg.qty"></td>
                                            <td x-text="parseFloat(chg.rate).toFixed(2)"></td>
                                            <td x-text="parseFloat(chg.amount).toFixed(2)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-default-gf dark" style="padding: 6px 12px; font-size: 12px;" @click="closeQuoteModal()">Cancel</button>
                        <button type="button" x-show="quoteStep > 1" class="btn-default-gf" style="padding: 6px 15px; font-size: 13px; background: #e5e5e5; border: none; color: #333;" @click="quoteStep--">Back</button>
                        <button type="button" x-show="quoteStep === 1" class="btn-tool" style="padding: 6px 15px; font-size: 13px; background: #36c6d3; border: none; color: #fff;" :disabled="!quoteSearch.selected_id" @click="loadSelectedQuote()">Next</button>
                        <button type="button" x-show="quoteStep === 2" class="btn-tool" style="padding: 6px 15px; font-size: 13px; background: #36c6d3; border: none; color: #fff;" @click="quoteStep++">Next</button>
                        <button type="button" x-show="quoteStep === 3" class="btn-tool" style="padding: 6px 15px; font-size: 13px; background: #36c6d3; border: none; color: #fff;" @click="confirmQuoteSelection()">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
        """

new_content = content[:start_idx] + new_modal + content[end_idx:]

with open('resources/views/truck/create.blade.php', 'w') as f:
    f.write(new_content)
