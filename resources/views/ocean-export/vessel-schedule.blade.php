<x-layout title="New Vessel Schedule">
    @push('styles')
    <x-form-styles />
    <style>
        .right-sidebar { position: fixed; right: 20px; top: 100px; width: 160px; z-index: 1000; }
        .sidebar-card { background: #fff; border: 1px solid #e2e8f0; padding: 8px; margin-bottom: 8px; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .sidebar-btn { background: #f1f5f9; border: 1px solid #cbd5e1; width: 100%; padding: 4px; text-align: center; font-weight: 700; margin-bottom: 4px; cursor: pointer; font-size: 10px; }
        .booking-info-card { background: #f59e0b; color: #fff; padding: 10px; border-radius: 4px; position: relative; overflow: hidden; min-height: 100px; }
        .booking-info-card i.anchor-bg { position: absolute; right: -10px; bottom: -10px; font-size: 60px; opacity: 0.2; transform: rotate(-15deg); }

        .shipment-status-badge { background: #3b82f6; color: #fff; padding: 1px 5px; border-radius: 10px; font-size: 9px; font-weight: 700; margin-right: 5px; }
        .well-gf { background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px; margin-bottom: 5px; border-radius: 3px; }
        .table-accounting { width: 100%; border-collapse: collapse; font-size: 10px; background: #fff; border: 1px solid #e2e8f0; }
        .table-accounting th { background: #f8fafc; color: #475569; font-weight: 700; text-align: center; border: 1px solid #e2e8f0; padding: 4px; }
        .table-accounting td { border: 1px solid #e2e8f0; padding: 4px; vertical-align: middle; }
        .btn-accounting { background: #3b82f6; color: #fff; border: 1px solid #2563eb; padding: 4px 10px; font-size: 11px; cursor: pointer; border-radius: 2px; display: inline-block; text-decoration: none; font-weight: 600; }
        .btn-accounting:hover { background: #2563eb; }
        .accounting-toolbar { display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }

        .pdo-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; display: flex; align-items: flex-start; justify-content: center; overflow-y: auto; padding: 20px 0; }
        .pdo-modal { background: #fff; width: 900px; max-width: 95%; border: 1px solid #cbd5e1; box-shadow: 0 5px 15px rgba(0,0,0,0.5); }
        .pdo-header { display: flex; justify-content: space-between; align-items: flex-start; padding: 20px 30px; }
        .pdo-title { font-size: 24px; font-weight: bold; color: #1e293b; margin: 0; }
        .pdo-address { font-size: 10px; line-height: 1.4; color: #334155; margin-top: 10px; }
        .pdo-top-table { border: 2px solid #475569; width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .pdo-top-table td { border: 1px solid #475569; padding: 4px; font-size: 10px; font-weight: bold; }
        .pdo-body { padding: 0 30px 30px 30px; }
        .pdo-layout { display: flex; gap: 10px; }
        .pdo-left { flex: 4; display: flex; flex-direction: column; gap: 5px; }
        .pdo-right { flex: 6; display: flex; flex-direction: column; gap: 5px; }
        .pdo-block { border: 1px solid #3b82f6; border-radius: 0; padding: 5px; position: relative; }
        .pdo-block-title { font-size: 10px; font-weight: bold; font-style: italic; color: #334155; display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px; text-transform: uppercase; }
        .pdo-input { width: 100%; border: 1px solid #cbd5e1; font-size: 10px; padding: 2px 4px; margin-bottom: 4px; background: #fff; height: 20px; box-sizing: border-box; }
        .pdo-textarea { width: 100%; border: 1px solid #cbd5e1; font-size: 10px; padding: 4px; resize: vertical; min-height: 80px; box-sizing: border-box; }
        .pdo-table { width: 100%; border-collapse: collapse; font-size: 10px; }
        .pdo-table td { border: 1px solid #3b82f6; padding: 4px; }
        .pdo-table .td-label { font-weight: bold; color: #334155; text-transform: uppercase; font-size: 9px; }
        .pdo-toolbar { background: #f8fafc; padding: 10px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; position: sticky; top: 0; z-index: 10001; }
        .text-blue { color: #3b82f6; }
    </style>
    @endpush

    <div class="page-content" x-data="vesselScheduleModule()">
        <!-- Right Floating Sidebar -->
        <div class="right-sidebar">
            <div style="display:flex; flex-direction:column; gap:5px;">
                <button class="sidebar-btn" @click="addBooking" style="background: #f9f9f9; border: 1px solid #ddd; border-radius: 2px; color: #333; padding: 6px;">+ Add Booking</button>
                <div style="font-size: 10px; color: #888; display:flex; justify-content:space-between; align-items:center; margin-top: 5px;">
                   <span>Sort by: <select style="font-size: 9px; border:1px solid #ccc; background:#fff; padding: 2px;"><option>Create Date</option></select></span>
                   <span style="color:#4b77be; cursor:pointer; font-weight: 700;">ASC <i class="fa fa-long-arrow-up"></i></span>
                </div>
                <div style="margin-top: 5px; font-size: 10px; color:#888; cursor:pointer; margin-bottom: 5px;"><i class="fa fa-cogs"></i> Card Setting</div>
            </div>
            
            <template x-for="(hbl, index) in bookings" :key="index">
                <div class="booking-info-card" style="margin-bottom: 5px; cursor: pointer; padding: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); min-height: 120px;">
                    <div style="font-weight: 700; font-size: 12px; display:flex; align-items:center; gap:5px;">
                        <i class="fa fa-info-circle" style="color: #fff;"></i> <span x-text="hbl.booking_no || 'New Booking'"></span>
                    </div>
                    <i class="fa fa-anchor anchor-bg" style="font-size: 80px; right: -20px; bottom: -20px;"></i>
                </div>
            </template>
        </div>

        <div style="margin-right: 180px;">
            <!-- Breadcrumbs -->
            <div style="font-size: 10px; color: #8e9eae; margin-bottom: 10px;">
                <i class="fa fa-home"></i> Home <i class="fa fa-angle-right" style="margin: 0 4px;"></i> Ocean Export <i class="fa fa-angle-right" style="margin: 0 4px;"></i> 
                <span style="color: #333; font-weight: 700;">New Vessel Schedule</span>
            </div>

            <!-- Tabs -->
            <ul class="gf-tabs">
                <li :class="activeTab === 'basic' ? 'active' : ''" @click="activeTab = 'basic'"><a>Basic</a></li>
                <li :class="(activeTab === 'accounting' ? 'active' : '') + (saved ? '' : ' disabled-tab')" @click="saved ? activeTab = 'accounting' : null"><a>Accounting</a></li>
                <li :class="(activeTab === 'document' ? 'active' : '') + (saved ? '' : ' disabled-tab')" @click="saved ? activeTab = 'document' : null"><a>Doc Center</a></li>
                <li :class="(activeTab === 'workorder' ? 'active' : '') + (saved ? '' : ' disabled-tab')" @click="saved ? activeTab = 'workorder' : null"><a>Work Order</a></li>
                <li :class="(activeTab === 'status' ? 'active' : '') + (saved ? '' : ' disabled-tab')" @click="saved ? activeTab = 'status' : null"><a>Status</a></li>
            </ul>

            <div x-show="activeTab === 'basic'">
                <form action="{{ isset($schedule) ? route('vessel-schedules.update', $schedule->id) : route('vessel-schedules.store') }}" method="POST" id="vesselScheduleForm" x-on:submit.prevent="validateAndSubmit">
                    @csrf
                    @if(isset($schedule))
                        @method('PUT')
                    @endif
                    <input type="hidden" name="bookings_json" id="bookings-json" value="[]">
                    <input type="hidden" name="containers_json" id="containers-json" value="[]">
                    <input type="hidden" name="memos_json" id="memos-json" value="[]">
                    @if(session('success'))
                        <div class="alert alert-success" style="background:#e8f5e9;border:1px solid #66bb6a;color:#2e7d32;padding:10px 15px;border-radius:4px;margin-bottom:15px;"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger" style="background:#fce4e4;border:1px solid #e57373;color:#c62828;padding:10px 15px;border-radius:4px;margin-bottom:15px;"><i class="fa fa-exclamation-circle"></i> {{ session('error') }}</div>
                    @endif
                    @if($errors && $errors->any())
                        <div class="alert alert-danger" style="background:#fce4e4;border:1px solid #e57373;color:#c62828;padding:10px 15px;border-radius:4px;margin-bottom:15px;"><strong>Validation Error</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                    @endif
                <!-- Vessel Schedule Board (Darker/MBL style) -->
                <div class="portlet light">
                    <div class="portlet-title" style="background: #444; color: #fff; min-height: 24px; padding: 2px 10px;" @click="hideVessel = !hideVessel">
                        <span class="caption-subject" style="color: #fff; font-size: 11px;"><span class="shipment-status-badge" style="background:#32c5d2;">Open</span> Vessel Schedule</span>
                        <div class="actions"><i class="fa fa-angle-down" :class="hideVessel ? '' : 'rotate-180'"></i></div>
                    </div>
                    <div class="portlet-body" x-show="!hideVessel">
                        <div class="well-gf">
                            <div class="form-grid-4">
                                <div>
                                    <div class="form-group-gf"><label class="form-label-gf" style="color:red;">* Vessel Sched No.</label><div class="form-input-container"><input type="text" name="schedule_no" class="form-control-gf" value="{{ old('schedule_no', $schedule->schedule_no ?? '') }}"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Carrier Bkg. No.</label><div class="form-input-container"><input type="text" name="carrier_bkg_no" class="form-control-gf" value="{{ old('carrier_bkg_no', $schedule->carrier_bkg_no ?? '') }}"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Shipping Agent</label><div class="form-input-container"><input type="text" name="shipping_agent" class="form-control-gf" value="{{ old('shipping_agent', $schedule->shipping_agent ?? '') }}"><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                </div>
                                <div>
                                    <div class="form-group-gf"><label class="form-label-gf" style="color:red;">* Office</label><div class="form-input-container"><select name="office_id" class="form-control-gf"><option value="">Select Office</option>@foreach($offices as $office)<option value="{{ $office->id }}" {{ old('office_id', $schedule->office_id ?? '') == $office->id ? 'selected' : '' }}>{{ $office->name }}</option>@endforeach</select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">ITN No.</label><div class="form-input-container"><input type="text" name="itn_no" class="form-control-gf" value="{{ old('itn_no', $schedule->itn_no ?? '') }}"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Oversea Agent</label><div class="form-input-container"><select name="oversea_agent_id" class="form-control-gf"><option value="">Select...</option>@foreach($agents as $agent)<option value="{{ $agent->id }}" {{ old('oversea_agent_id', $schedule->oversea_agent_id ?? '') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-search"></i></button></div></div>
                                </div>
                                <div>
                                    <div class="form-group-gf"><label class="form-label-gf">B/L Type</label><div class="form-input-container"><select name="bl_type" class="form-control-gf"><option value="">Select</option><option value="NORMAL" {{ old('bl_type', $schedule->bl_type ?? '') == 'NORMAL' ? 'selected' : '' }}>NORMAL</option><option value="SEAWAY BILL" {{ old('bl_type', $schedule->bl_type ?? '') == 'SEAWAY BILL' ? 'selected' : '' }}>SEAWAY BILL</option><option value="SURRENDERED" {{ old('bl_type', $schedule->bl_type ?? '') == 'SURRENDERED' ? 'selected' : '' }}>SURRENDERED</option></select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Notify</label><div class="form-input-container"><select name="notify_id" class="form-control-gf"><option value="">Select...</option>@foreach($tradePartners as $partner)<option value="{{ $partner->id }}" {{ old('notify_id', $schedule->notify_id ?? '') == $partner->id ? 'selected' : '' }}>{{ $partner->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-search"></i></button></div></div>
                                    <div class="form-group-gf" style="justify-content: flex-end; font-size: 9px; font-weight: bold; color: #888; padding-top: 5px;">OP: {{ isset($schedule) && $schedule->op ? $schedule->op->name : $loggedUser->name }} ({{ isset($schedule) && $schedule->op ? $schedule->op->email : $loggedUser->email }})</div>
                                    <input type="hidden" name="op_id" value="{{ old('op_id', $schedule->op_id ?? $loggedUser->id ?? '') }}">
                                </div>
                                <div>
                                    <div class="form-group-gf"><label class="form-label-gf">Post Date</label><div class="form-input-container"><input type="date" name="post_date" class="form-control-gf" value="{{ old('post_date', isset($schedule) && $schedule->post_date ? \Carbon\Carbon::parse($schedule->post_date)->format('Y-m-d') : date('Y-m-d')) }}"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Forwarding Agent</label><div class="form-input-container"><select name="forwarding_agent_id" class="form-control-gf"><option value="">Select...</option>@foreach($agents as $agent)<option value="{{ $agent->id }}" {{ old('forwarding_agent_id', $schedule->forwarding_agent_id ?? '') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-search"></i></button></div></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-grid-4" style="margin-top: 10px;">
                            <div>
                                <div class="form-group-gf"><label class="form-label-gf">Vessel</label><div class="form-input-container"><select name="vessel_id" class="form-control-gf"><option value="">Select</option>@foreach($vessels as $vessel)<option value="{{ $vessel->id }}" {{ old('vessel_id', $schedule->vessel_id ?? '') == $vessel->id ? 'selected' : '' }}>{{ $vessel->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Port of Loading</label><div class="form-input-container"><select name="pol_id" class="form-control-gf"><option value="">Select</option>@foreach($ports as $port)<option value="{{ $port->id }}" {{ old('pol_id', $schedule->pol_id ?? '') == $port->id ? 'selected' : '' }}>{{ $port->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Port of Discharge</label><div class="form-input-container"><select name="pod_id" class="form-control-gf"><option value="">Select</option>@foreach($ports as $port)<option value="{{ $port->id }}" {{ old('pod_id', $schedule->pod_id ?? '') == $port->id ? 'selected' : '' }}>{{ $port->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Final Destination</label><div class="form-input-container"><select name="fdest_id" class="form-control-gf"><option value="">Select...</option>@foreach($ports as $port)<option value="{{ $port->id }}" {{ old('fdest_id', $schedule->fdest_id ?? '') == $port->id ? 'selected' : '' }}>{{ $port->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                            </div>
                            <div>
                                <div class="form-group-gf"><label class="form-label-gf">Voyage</label><div class="form-input-container"><input type="text" name="voyage" class="form-control-gf" value="{{ old('voyage', $schedule->voyage ?? '') }}"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf" style="color:red;">* ETD</label><div class="form-input-container"><input type="date" name="etd" class="form-control-gf" value="{{ old('etd', isset($schedule) && $schedule->etd ? \Carbon\Carbon::parse($schedule->etd)->format('Y-m-d') : '') }}"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">ETA</label><div class="form-input-container"><input type="date" name="eta" class="form-control-gf" value="{{ old('eta', isset($schedule) && $schedule->eta ? \Carbon\Carbon::parse($schedule->eta)->format('Y-m-d') : '') }}"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Final ETA</label><div class="form-input-container"><input type="date" name="final_eta" class="form-control-gf" value="{{ old('final_eta', isset($schedule) && $schedule->final_eta ? \Carbon\Carbon::parse($schedule->final_eta)->format('Y-m-d') : '') }}"></div></div>
                            </div>
                            <div>
                                <div class="form-group-gf"><label class="form-label-gf">Delivery To/Pier</label><div class="form-input-container"><select name="delivery_to_pier" class="form-control-gf"><option value="">Select...</option>@foreach($tradePartners as $tp)<option value="{{ $tp->name }}" {{ old('delivery_to_pier', $schedule->delivery_to_pier ?? '') == $tp->name ? 'selected' : '' }}>{{ $tp->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Place of Receipt</label><div class="form-input-container"><select name="por_id" class="form-control-gf"><option value="">Select...</option>@foreach($ports as $port)<option value="{{ $port->id }}" {{ old('por_id', $schedule->por_id ?? '') == $port->id ? 'selected' : '' }}>{{ $port->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Place of Deliv(DEL)</label><div class="form-input-container"><select name="del_id" class="form-control-gf"><option value="">Select...</option>@foreach($ports as $port)<option value="{{ $port->id }}" {{ old('del_id', $schedule->del_id ?? '') == $port->id ? 'selected' : '' }}>{{ $port->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                            </div>
                            <div>
                                <div class="form-group-gf"><label class="form-label-gf">Empty Pickup</label><div class="form-input-container"><select name="empty_pickup" class="form-control-gf"><option value="">Select...</option>@foreach($truckers as $t)<option value="{{ $t->name }}" {{ old('empty_pickup', $schedule->empty_pickup ?? '') == $t->name ? 'selected' : '' }}>{{ $t->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">POR ETD</label><div class="form-input-container"><input type="date" name="por_etd" class="form-control-gf" value="{{ old('por_etd', isset($schedule) && $schedule->por_etd ? \Carbon\Carbon::parse($schedule->por_etd)->format('Y-m-d') : '') }}"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">DEL ETA</label><div class="form-input-container"><input type="date" name="del_eta" class="form-control-gf" value="{{ old('del_eta', isset($schedule) && $schedule->del_eta ? \Carbon\Carbon::parse($schedule->del_eta)->format('Y-m-d') : '') }}"></div></div>
                            </div>
                        </div>

                        <div class="form-grid-4" style="margin-top: 10px; border-top: 1px solid #eee; padding-top: 5px;">
                            <div>
                                <div class="form-group-gf"><label class="form-label-gf">Freight</label><div class="form-input-container"><select name="freight" class="form-control-gf"><option value="">Select</option><option value="COLLECT" {{ old('freight', $schedule->freight ?? '') == 'COLLECT' ? 'selected' : '' }}>COLLECT</option><option value="PREPAID" {{ old('freight', $schedule->freight ?? '') == 'PREPAID' ? 'selected' : '' }}>PREPAID</option></select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">OB/L Type</label><div class="form-input-container"><select name="obl_type" class="form-control-gf"><option value="">Select</option><option value="EXPRESS BILL" {{ old('obl_type', $schedule->obl_type ?? '') == 'EXPRESS BILL' ? 'selected' : '' }}>EXPRESS BILL</option><option value="ORIGINAL" {{ old('obl_type', $schedule->obl_type ?? '') == 'ORIGINAL' ? 'selected' : '' }}>ORIGINAL</option><option value="TELEX" {{ old('obl_type', $schedule->obl_type ?? '') == 'TELEX' ? 'selected' : '' }}>TELEX</option></select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">On Board Date</label><div class="form-input-container"><input type="date" name="on_board_date" class="form-control-gf" value="{{ old('on_board_date', isset($schedule) && $schedule->on_board_date ? \Carbon\Carbon::parse($schedule->on_board_date)->format('Y-m-d') : '') }}"></div></div>
                            </div>
                            <div>
                                <div class="form-group-gf"><label class="form-label-gf">Ship Mode</label><div class="form-input-container"><select name="ship_mode" class="form-control-gf"><option value="">Select</option><option value="FCL" {{ old('ship_mode', $schedule->ship_mode ?? '') == 'FCL' ? 'selected' : '' }}>FCL</option><option value="LCL" {{ old('ship_mode', $schedule->ship_mode ?? '') == 'LCL' ? 'selected' : '' }}>LCL</option></select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Doc Cut-Off</label><div class="form-input-container"><input type="date" name="doc_cutoff" class="form-control-gf" value="{{ old('doc_cutoff', isset($schedule) && $schedule->doc_cutoff ? \Carbon\Carbon::parse($schedule->doc_cutoff)->format('Y-m-d') : '') }}"></div></div>
                                <div @click="showVesselMore = !showVesselMore" style="color:#4b77be; font-weight:700; font-size:10px; cursor:pointer; margin-top:5px;">More <i class="fa fa-angle-down"></i></div>
                            </div>
                            <div class="col-span-2">
                                <div class="form-group-gf"><label class="form-label-gf">SVC Term</label><div class="form-input-container"><select name="svc_term_from_id" class="form-control-gf"><option value="">Select</option>@foreach($serviceTerms as $st)<option value="{{ $st->id }}" {{ old('svc_term_from_id', $schedule->svc_term_from_id ?? '') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>@endforeach</select> <span>~</span> <select name="svc_term_to_id" class="form-control-gf"><option value="">Select</option>@foreach($serviceTerms as $st)<option value="{{ $st->id }}" {{ old('svc_term_to_id', $schedule->svc_term_to_id ?? '') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>@endforeach</select></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Port Cut-Off</label><div class="form-input-container"><input type="date" name="port_cutoff" class="form-control-gf" value="{{ old('port_cutoff', isset($schedule) && $schedule->port_cutoff ? \Carbon\Carbon::parse($schedule->port_cutoff)->format('Y-m-d') : '') }}"></div></div>
                                <div class="form-group-gf"><label class="form-label-gf">Rail Cut-Off</label><div class="form-input-container"><input type="date" name="rail_cutoff" class="form-control-gf" value="{{ old('rail_cutoff', isset($schedule) && $schedule->rail_cutoff ? \Carbon\Carbon::parse($schedule->rail_cutoff)->format('Y-m-d') : '') }}"></div></div>
                            </div>
                        </div>

                        <!-- MBL Container List -->
                        <div style="margin-top: 15px;">
                            <div class="caption-subject" style="margin-bottom: 5px; display: flex; align-items: center; gap: 5px;">Container List <button type="button" class="btn-tool" @click="addContainer"><i class="fa fa-plus"></i></button> <button type="button" class="btn-default-gf" @click="createMbl">Create MB/L <i class="fa fa-angle-down"></i></button></div>
                            <table class="table-custom">
                                <thead>
                                    <tr><th style="width:25px;"><input type="checkbox" :checked="containers.length > 0 && containers.every(c => c.selected)" @click="toggleSelectAll"></th><th style="width:30px;">#</th><th>Container No.</th><th>TP/SZ</th><th>Seal No.</th><th>Booking No.</th><th>PKG</th><th>Weight</th><th>Measure</th><th>MB/L</th><th>Action</th></tr>
                                </thead>
                                <tbody>
                                    <template x-for="(c, i) in containers" :key="i">
                                        <tr :style="c.selected ? 'background:#fff3cd;' : ''">
                                            <td style="text-align: center;"><input type="checkbox" x-model="c.selected"></td>
                                            <td style="text-align: center;" x-text="i + 1"></td>
                                            <td><input type="text" x-model="c.container_no" class="form-control-gf" style="width:100%;"></td>
                                            <td><input type="text" x-model="c.type_size" class="form-control-gf" style="width:70px;"></td>
                                            <td><input type="text" x-model="c.seal_no" class="form-control-gf" style="width:80px;"></td>
                                            <td><input type="text" x-model="c.booking_no" class="form-control-gf" style="width:90px;"></td>
                                            <td><input type="number" x-model="c.pkg" class="form-control-gf" style="width:60px;text-align:right;" @input="calcContainerTotals"></td>
                                            <td><input type="number" step="0.01" x-model="c.weight" class="form-control-gf" style="width:70px;text-align:right;" @input="calcContainerTotals"></td>
                                            <td><input type="number" step="0.01" x-model="c.measure" class="form-control-gf" style="width:70px;text-align:right;" @input="calcContainerTotals"></td>
                                            <td style="text-align: center; font-size: 10px;" x-text="c.mbl_no || ''"></td>
                                            <td style="text-align: center;"><button type="button" @click="containers.splice(i, 1); calcContainerTotals()" class="btn-default-gf" style="color:#e43a45;padding:1px 4px;"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                    </template>
                                    <tr style="background:#f9f9f9; font-weight:700;">
                                        <td colspan="6" style="text-align:right;">Total</td>
                                        <td style="text-align:right;" x-text="containerTotals.pkg"></td>
                                        <td style="text-align:right;" x-text="containerTotals.weight"></td>
                                        <td style="text-align:right;" x-text="containerTotals.measure"></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Booking Boards (Yellow/HBL style) -->
                <template x-for="(hbl, index) in bookings" :key="index">
                    <div class="portlet light" style="margin-top: 5px;">
                        <div class="portlet-title" style="background: #f2bc00; color: #fff; min-height: 24px; padding: 2px 10px;">
                            <span class="caption-subject" style="color: #fff; font-size: 11px;"><span class="shipment-status-badge" style="background:#fff; color:#f2bc00;">Booking</span> Information</span>
                            <div class="actions" style="display:flex; gap:10px; align-items:center;">
                                <button class="btn-default-gf" style="border:none; background:rgba(255,255,255,0.2); color:#fff; font-size:9px;">Preference</button>
                                <i class="fa fa-times cursor-pointer" style="font-size:12px; opacity:0.8;" @click="bookings.splice(index, 1)"></i>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="form-grid-4">
                                <!-- Col 1 -->
                                <div class="well-gf">
                                    <div class="form-group-gf"><label class="form-label-gf" style="color:red;">* Booking No.</label><div class="form-input-container"><input type="checkbox" x-model="bookings[index].auto_booking_no" checked><input type="text" x-model="bookings[index].booking_no" class="form-control-gf"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf" style="color:red;">* Booking Date</label><div class="form-input-container"><input type="date" x-model="bookings[index].booking_date" class="form-control-gf"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">HB/L No.</label><div class="form-input-container"><input type="text" x-model="bookings[index].hbl_no" class="form-control-gf" disabled style="background:#f5f5f5;"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Quotation No.</label><div class="form-input-container"><input type="text" x-model="bookings[index].quotation_no" class="form-control-gf"><button type="button" class="btn-default-gf"><i class="fa fa-search"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">ITN No.</label><div class="form-input-container"><input type="text" x-model="bookings[index].itn_no" class="form-control-gf"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Sales</label><div class="form-input-container"><select x-model="bookings[index].sales_person_id" class="form-control-gf"><option value="">Select</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div></div>
                                    <div style="font-size: 9px; font-weight: bold; color: #999; margin-top: 10px;">OP: {{ $loggedUser->name }} ({{ $loggedUser->email }})</div>
                                </div>
                                <!-- Col 2 -->
                                <div>
                                    <div class="form-group-gf"><label class="form-label-gf">Reference No.</label><div class="form-input-container"><input type="text" x-model="bookings[index].reference_no" class="form-control-gf"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Carrier Bkg No.</label><div class="form-input-container"><input type="text" x-model="bookings[index].carrier_bkg_no" class="form-control-gf"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Carrier</label><div class="form-input-container"><select x-model="bookings[index].carrier_id" class="form-control-gf"><option value="">Select...</option>@foreach($carriers as $tp)<option value="{{ $tp->id }}">{{ $tp->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-search"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Ship Mode</label><div class="form-input-container"><select x-model="bookings[index].ship_mode" class="form-control-gf"><option value="">Select</option><option value="FCL">FCL</option><option value="LCL">LCL</option></select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Service Term</label><div class="form-input-container"><select x-model="bookings[index].svc_term_from_id" class="form-control-gf"><option value="">Select</option>@foreach($serviceTerms as $st)<option value="{{ $st->id }}">{{ $st->name }}</option>@endforeach</select> <span>~</span> <select x-model="bookings[index].svc_term_to_id" class="form-control-gf"><option value="">Select</option>@foreach($serviceTerms as $st)<option value="{{ $st->id }}">{{ $st->name }}</option>@endforeach</select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Incoterms</label><div class="form-input-container"><select x-model="bookings[index].incoterms" class="form-control-gf"><option value="">Select</option></select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Awail Shipper</label><div class="form-input-container"><select x-model="bookings[index].actual_shipper_id" class="form-control-gf"><option value="">Select...</option>@foreach($tradePartners as $tp)<option value="{{ $tp->id }}">{{ $tp->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-search"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Customer</label><div class="form-input-container"><select x-model="bookings[index].customer_id" class="form-control-gf"><option value="">Select...</option>@foreach($customers as $tp)<option value="{{ $tp->id }}">{{ $tp->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-search"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Bill To</label><div class="form-input-container"><select x-model="bookings[index].bill_to_id" class="form-control-gf"><option value="">Select...</option>@foreach($tradePartners as $tp)<option value="{{ $tp->id }}">{{ $tp->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-search"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Consignee</label><div class="form-input-container"><select x-model="bookings[index].consignee_id" class="form-control-gf"><option value="">Select...</option>@foreach($tradePartners as $tp)<option value="{{ $tp->id }}">{{ $tp->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-search"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Notify</label><div class="form-input-container"><select x-model="bookings[index].notify_id" class="form-control-gf"><option value="">Select...</option>@foreach($tradePartners as $tp)<option value="{{ $tp->id }}">{{ $tp->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-search"></i></button></div></div>
                                </div>
                                <!-- Col 3 -->
                                <div>
                                    <div class="form-group-gf"><label class="form-label-gf">Vessel</label><div class="form-input-container"><input type="text" x-model="bookings[index].vessel" class="form-control-gf"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Voyage</label><div class="form-input-container"><input type="text" x-model="bookings[index].voyage" class="form-control-gf"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Pickup No.</label><div class="form-input-container"><input type="text" x-model="bookings[index].pickup_no" class="form-control-gf"><button type="button" class="btn-tool"><i class="fa fa-plus"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Place Receipt</label><div class="form-input-container"><select x-model="bookings[index].por_id" class="form-control-gf"><option value="">Select...</option>@foreach($ports as $port)<option value="{{ $port->id }}">{{ $port->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Port of Loading</label><div class="form-input-container"><select x-model="bookings[index].pol_id" class="form-control-gf"><option value="">Select...</option>@foreach($ports as $port)<option value="{{ $port->id }}">{{ $port->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">ETD</label><div class="form-input-container"><input type="date" x-model="bookings[index].etd" class="form-control-gf"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Port Discharge</label><div class="form-input-container"><select x-model="bookings[index].pod_id" class="form-control-gf"><option value="">Select...</option>@foreach($ports as $port)<option value="{{ $port->id }}">{{ $port->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">ETA</label><div class="form-input-container"><input type="date" x-model="bookings[index].eta" class="form-control-gf"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Place Deliv(DEL)</label><div class="form-input-container"><select x-model="bookings[index].del_id" class="form-control-gf"><option value="">Select...</option>@foreach($ports as $port)<option value="{{ $port->id }}">{{ $port->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Final Dest</label><div class="form-input-container"><select x-model="bookings[index].fdest_id" class="form-control-gf"><option value="">Select...</option>@foreach($ports as $port)<option value="{{ $port->id }}">{{ $port->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Final ETA</label><div class="form-input-container"><input type="date" x-model="bookings[index].final_eta" class="form-control-gf"></div></div>
                                </div>
                                <!-- Col 4 -->
                                <div>
                                    <div class="form-group-gf"><label class="form-label-gf">Cargo Type</label><div class="form-input-container"><select x-model="bookings[index].cargo_type" class="form-control-gf"><option value="">Select</option><option value="GENERAL CARGO">GENERAL CARGO</option><option value="DANGEROUS GOODS">DANGEROUS GOODS</option><option value="REEFER">REEFER</option></select></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Referred By</label><div class="form-input-container"><select x-model="bookings[index].referred_by_id" class="form-control-gf"><option value="">Select...</option>@foreach($tradePartners as $tp)<option value="{{ $tp->id }}">{{ $tp->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-search"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Cargo Pickup</label><div class="form-input-container"><input type="text" x-model="bookings[index].cargo_pickup" class="form-control-gf"><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Trucker</label><div class="form-input-container"><select x-model="bookings[index].trucker_id" class="form-control-gf"><option value="">Select...</option>@foreach($truckers as $tp)<option value="{{ $tp->id }}">{{ $tp->name }}</option>@endforeach</select><button type="button" class="btn-default-gf"><i class="fa fa-search"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Deliv To/Pier</label><div class="form-input-container"><input type="text" x-model="bookings[index].delivery_to_pier" class="form-control-gf"><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Cargo Ready</label><div class="form-input-container"><input type="date" x-model="bookings[index].cargo_ready" class="form-control-gf"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Empty Pickup</label><div class="form-input-container"><input type="text" x-model="bookings[index].empty_pickup" class="form-control-gf"><button type="button" class="btn-default-gf"><i class="fa fa-edit"></i></button></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">WH Cut-Off</label><div class="form-input-container"><input type="date" x-model="bookings[index].wh_cutoff" class="form-control-gf"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Doc Cut-Off</label><div class="form-input-container"><input type="date" x-model="bookings[index].doc_cutoff" class="form-control-gf"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Port Cut-Off</label><div class="form-input-container"><input type="date" x-model="bookings[index].port_cutoff" class="form-control-gf"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">VGM Cut-Off</label><div class="form-input-container"><input type="date" x-model="bookings[index].vgm_cutoff" class="form-control-gf"></div></div>
                                    <div class="form-group-gf"><label class="form-label-gf">Office</label><div class="form-input-container"><select x-model="bookings[index].office_id" class="form-control-gf"><option value="">Select</option>@foreach($offices as $office)<option value="{{ $office->id }}">{{ $office->name }}</option>@endforeach</select></div></div>
                                </div>
                            </div>

                            <div style="border-top: 1px solid #eee; margin: 10px 0; padding-top: 10px;">
                                <div class="form-group-gf">
                                    <label class="form-label-gf" style="font-weight:700;">P.O. No.</label>
                                    <div class="form-input-container"><input type="text" x-model="bookings[index].po_no" class="form-control-gf" style="width: 250px;" placeholder="Add P.O. No..."></div>
                                    <div style="margin-left: auto; display: flex; gap: 10px; font-size: 10px;">
                                        <label><input type="radio" name="po_map" checked> Container based</label>
                                        <label><input type="radio" name="po_map"> Item based</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Container List Area -->
                            <div style="background:#ebf3f7; padding: 5px; border: 1px solid #ddd;">
                                <div style="display: flex; gap: 15px; margin-bottom: 5px; align-items: center;">
                                    <label style="font-size: 10px; font-weight: 700;"><input type="radio" checked> Booking</label>
                                    <label style="font-size: 10px; font-weight: 700;"><input type="radio"> Receiving</label>
                                    <button class="btn-default-gf" style="color:#4b77be;">Load from Warehouse Receipt</button>
                                    <button class="btn-default-gf" style="color:#4b77be;">Create Item and Link WHR</button>
                                </div>
                                <table class="table-custom">
                                    <thead>
                                        <tr><th style="width:100px;">Total</th><th colspan="2">PKG</th><th colspan="2">Weight</th><th colspan="2">Measurement</th><th>P.O. No.</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td style="font-weight:700; text-align:center;">Booking</td><td colspan="2" style="text-align:center;">0</td><td colspan="2" style="text-align:right;">0.00 KGS</td><td colspan="2" style="text-align:right;">0.00 CBM</td><td></td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div style="margin-top: 10px;">
                                <div class="caption-subject" style="margin-bottom: 5px;">Commodity <button type="button" class="btn-tool"><i class="fa fa-plus"></i></button></div>
                                <table class="table-custom">
                                    <thead>
                                        <tr><th><input type="checkbox"></th><th>Description</th><th>PKG</th><th>PCS</th><th>Net Wt</th><th>Gross Wt</th><th>Price</th><th>Amount</th><th>Container</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td colspan="9" style="text-align:center; color:#999; padding:10px;">No Data Available. Click <span style="color:#32c5d2; cursor:pointer;">here</span> to add.</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-grid-4" style="margin-top: 10px;">
                                <div class="col-span-2">
                                    <label class="form-label-gf" style="text-align:left; font-weight:700;">Mark</label>
                                    <textarea x-model="bookings[index].mark" class="form-control-gf" style="height: 40px; margin-top: 2px;"></textarea>
                                </div>
                                <div class="col-span-2">
                                    <div style="display:flex; justify-content:space-between;"><label class="form-label-gf" style="text-align:left; font-weight:700;">Description</label> <div style="font-size:9px;">Copy: <button type="button" class="btn-default-gf">P.O.</button> <button type="button" class="btn-default-gf">Comm</button></div></div>
                                    <textarea x-model="bookings[index].description" class="form-control-gf" style="height: 40px; margin-top: 2px;"></textarea>
                                </div>
                            </div>

                            <div style="margin-top: 10px;">
                                <div style="display:flex; gap:2px; border-bottom:1px solid #ddd;">
                                    <button type="button" class="btn-default-gf" style="background:#32c5d2; color:#fff; border-bottom:none;">Booking</button>
                                    <button type="button" class="btn-default-gf">Shipping Instruction</button>
                                    <button type="button" class="btn-default-gf">Manifest by Vessel</button>
                                </div>
                                <textarea class="form-control-gf" style="height: 50px; border-top:none;"></textarea>
                            </div>

                            <div class="memo-section" style="margin-top: 10px;">
                                <div class="memo-header">
                                    <span>Memo</span>
                                    <button type="button" class="btn-default-gf">Document (<span x-text="memos.length"></span>)</button>
                                </div>
                                <div class="memo-body">
                                    <div style="display:flex; gap:5px;">
                                        <div style="flex:2;">
                                            <table class="table-custom">
                                                <thead><tr><th style="width:20px;"><button type="button" @click="addMemo" class="btn-tool-icon" title="Add Memo"><i class="fa fa-plus"></i></button></th><th style="width:20px;"><i class="fa fa-bell"></i></th><th>Subject</th><th>Last Modified</th><th>Created</th><th>Action</th></tr></thead>
                                                <tbody>
                                                    <template x-for="(m, i) in memos" :key="i">
                                                        <tr @click="selectMemo(i)" :style="selectedMemoIdx === i ? 'background:#e8f4fd;' : ''">
                                                            <td></td>
                                                            <td></td>
                                                            <td x-text="m.subject || '(No subject)'"></td>
                                                            <td x-text="m.updated_at ? new Date(m.updated_at).toLocaleString() : ''"></td>
                                                            <td x-text="m.created_at ? new Date(m.created_at).toLocaleString() : ''"></td>
                                                            <td style="white-space:nowrap;"><button type="button" @click.stop="deleteMemo(i)" class="btn-default-gf" style="color:#e43a45;padding:1px 4px;" title="Delete"><i class="fa fa-trash"></i></button></td>
                                                        </tr>
                                                    </template>
                                                    <tr x-show="memos.length === 0">
                                                        <td colspan="6" style="text-align:center; color:#888; font-style:italic; padding:10px;">No memos found. Click "+" to create a memo.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div style="flex:1;">
                                            <textarea x-model="memoContent" class="form-control-gf" style="height:50px;" placeholder="Memo preview..." :readonly="selectedMemoIdx < 0"></textarea>
                                            <div style="margin-top:5px; text-align:right;" x-show="selectedMemoIdx >= 0">
                                                <button type="button" @click="saveMemo" class="btn-default-gf" style="padding:2px 8px; font-size:11px;">Save</button>
                                                <button type="button" @click="selectedMemoIdx = -1; memoContent = ''" class="btn-default-gf" style="padding:2px 8px; font-size:11px; margin-left:5px;">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Footer Toolbar -->
                <div style="display: flex; justify-content: center; gap: 10px; margin-top: 20px; padding-bottom: 50px;">
                    <button type="submit" class="btn-tool" style="background:#32c5d2; padding: 6px 30px; font-size: 12px; border-radius: 4px;">SAVE VESSEL SCHEDULE</button>
                    <a href="{{ route('vessel-schedules.index') }}" class="btn-default-gf" style="padding: 6px 30px; font-size: 12px; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">CANCEL</a>
                </div>
                </form>
            </div>

            <!-- Accounting Tab -->
            <div x-show="activeTab === 'accounting'" style="display: none;">
                <!-- MBL Header -->
                <div class="portlet light" style="margin-bottom: 10px !important;">
                    <div class="portlet-title" style="background: #444; color: #fff; min-height: 30px; padding: 5px 10px;">
                        <span style="font-size: 13px; font-weight: 600;">Vessel Schedule {{ $schedule->schedule_no ?? 'New' }}</span>
                        <div class="actions">
                            <button class="btn-default-gf" style="background: rgba(255,255,255,0.2); border: none; color: #fff;"><i class="fa fa-info"></i></button>
                            <button class="btn-default-gf" style="background: rgba(255,255,255,0.2); border: none; color: #fff;"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                        </div>
                    </div>
                </div>

                <template x-for="(hbl, index) in bookings" :key="index">
                    <div class="portlet light" style="margin-top: 5px; border-color: #f3c200;">
                        <div class="portlet-title" style="background: #f3c200; color: #fff; min-height: 30px; padding: 5px 10px;">
                            <span style="font-size: 13px; font-weight: 600;" x-text="'Booking ' + (hbl.booking_no || 'New')"></span>
                            <div class="actions">
                                <button class="btn-default-gf" style="background: rgba(255,255,255,0.2); border: none; color: #fff;"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                                <i class="fa fa-times cursor-pointer" style="margin-left: 10px;" @click="bookings.splice(index, 1)"></i>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 0;">
                            <div class="accounting-toolbar">
                                <div style="display: flex; gap: 5px;">
                                    <button class="btn-accounting" @click="openChargeModal('AR')">Origin Revenue (Invoice/AR)</button>
                                    <button class="btn-accounting" @click="openChargeModal('DC')">Destination Revenue/Cost (D/C Note)</button>
                                    <button class="btn-accounting" @click="openChargeModal('AP')">Origin Cost (AP)</button>
                                </div>
                                <div style="font-size: 11px; color: #555;">
                                    <label style="cursor: pointer;"><input type="checkbox" checked style="vertical-align: middle;"> Include Draft Amount</label>
                                </div>
                            </div>
                            
                            <div style="padding: 10px;">
                                <table class="table-accounting">
                                    <thead>
                                        <tr>
                                            <th style="width: 25px;"></th>
                                            <th style="width: 25px;"></th>
                                            <th style="text-align: left;">Charge Code/Name</th>
                                            <th style="text-align: left;">Party</th>
                                            <th style="text-align: right;">Revenue</th>
                                            <th style="text-align: right;">Cost</th>
                                            <th style="text-align: right;">Balance</th>
                                            <th style="text-align: center;">Type</th>
                                            <th style="text-align: right;">Post Date</th>
                                            <th style="text-align: right;">Invoice Date</th>
                                            <th style="text-align: center;">Inv#</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(c, i) in charges" :key="c.id">
                                            <tr>
                                                <td style="text-align: center;"><input type="checkbox" class="charge-select" :value="c.id"></td>
                                                <td style="text-align: center;"><i class="fa fa-file-text"></i></td>
                                                <td x-text="(c.charge_code || '') + ' - ' + (c.charge_name || '')"></td>
                                                <td x-text="c.bill_to?.name || c.vendor?.name || ''"></td>
                                                <td style="text-align: right; color: #32c5d2;" x-text="c.type === 'AR' ? parseFloat(c.total_amount || c.amount || 0).toFixed(2) : '0.00'"></td>
                                                <td style="text-align: right; color: #e43a45;" x-text="c.type === 'AP' ? parseFloat(c.total_amount || c.amount || 0).toFixed(2) : '0.00'"></td>
                                                <td style="text-align: right;" x-text="parseFloat(c.total_amount || c.amount || 0).toFixed(2)"></td>
                                                <td style="text-align: center;"><span class="shipment-status-badge" x-text="c.type"></span></td>
                                                <td style="text-align: right;" x-text="c.created_at ? new Date(c.created_at).toLocaleDateString() : ''"></td>
                                                <td style="text-align: right;" x-text="c.invoice_date ? new Date(c.invoice_date).toLocaleDateString() : ''"></td>
                                                <td style="text-align: center;" x-text="c.invoice_no || ''"></td>
                                                <td style="text-align: center;">
                                                    <button @click="editChargeModal(c)" class="btn-default-gf" style="padding: 1px 4px;"><i class="fa fa-pencil"></i></button>
                                                    <button @click="deleteCharge(c.id)" class="btn-default-gf" style="padding: 1px 4px; color: #e43a45;"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        </template>
                                        <tr x-show="charges.length === 0">
                                            <td colspan="12" style="text-align: center; color: #999; padding: 20px;">No charges yet. Use the buttons above to add charges.</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="text-align: right; font-weight: 700;">Total</td>
                                            <td style="text-align: right; color: #32c5d2; font-weight: 700;" x-text="chargesAr().toFixed(2)"></td>
                                            <td style="text-align: right; color: #e43a45; font-weight: 700;" x-text="chargesAp().toFixed(2)"></td>
                                            <td style="text-align: right; color: #333; font-weight: 700;" x-text="chargesTotal().toFixed(2)"></td>
                                            <td colspan="5"></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table-accounting" style="margin-top: -1px;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="text-align: right; color: #888;">Amount</th>
                                            <th style="text-align: right; color: #888;">Profit Percentage</th>
                                            <th style="text-align: right; color: #888;">Profit Margin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="text-align: right; font-weight: 700;">Schedule Profit</td>
                                            <td style="text-align: right; color: #32c5d2; font-weight: 700;" x-text="(chargesAr() - chargesAp()).toFixed(2)"></td>
                                            <td style="text-align: right; color: #32c5d2; font-weight: 700;" x-text="chargesAr() > 0 ? ((chargesAr() - chargesAp()) / chargesAr() * 100).toFixed(1) + '%' : 'N/A'"></td>
                                            <td style="text-align: right; color: #32c5d2; font-weight: 700;" x-text="chargesAr() > 0 ? (chargesAr() - chargesAp()).toFixed(2) : 'N/A'"></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="memo-section" style="margin-top: 10px;">
                                    <div class="memo-header" style="background: #eef1f5; color: #333; padding: 6px 10px; border-bottom: 1px solid #ddd;">
                                        <span>Memo</span>
                                        <button class="btn-default-gf" style="font-size: 10px;">Document (0) <i class="fa fa-angle-down"></i></button>
                                    </div>
                                    <div class="memo-body" style="padding: 0;">
                                        <div style="display: flex;">
                                            <div style="flex: 2; border-right: 1px solid #ddd;">
                                                <table class="table-accounting" style="border: none;">
                                                    <thead style="background: #8e9eae; color: #fff;">
                                                        <tr>
                                                            <th style="background: #32c5d2; border: 1px solid #32c5d2; color: #fff; width: 30px;"><i class="fa fa-plus"></i></th>
                                                            <th style="background: #8e9eae; border: 1px solid #8e9eae; color: #fff; width: 30px;"><i class="fa fa-bell"></i></th>
                                                            <th style="background: #8e9eae; border: 1px solid #8e9eae; color: #fff; text-align: left;">Subject <i class="fa fa-sort"></i></th>
                                                            <th style="background: #8e9eae; border: 1px solid #8e9eae; color: #fff;">Last Modified <i class="fa fa-sort"></i></th>
                                                            <th style="background: #8e9eae; border: 1px solid #8e9eae; color: #fff;">Created <i class="fa fa-caret-up"></i></th>
                                                            <th style="background: #8e9eae; border: 1px solid #8e9eae; color: #fff;">Action / TP</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="6" style="text-align: center; color: #999; padding: 20px;"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div style="flex: 1; padding: 10px; background: #f9f9f9;">
                                                <textarea class="form-control-gf" style="height: 100%; min-height: 80px; border: 1px solid #ddd; background: #eee;" readonly></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Doc Center Tab -->
            <div x-show="activeTab === 'document'" style="display: none;">
                <!-- MBL Doc Center -->
                <div class="portlet light" style="margin-bottom: 10px !important;">
                    <div class="portlet-title" style="background: #444; color: #fff; min-height: 30px; padding: 5px 10px;">
                        <span style="font-size: 13px; font-weight: 600;">Vessel Schedule {{ $schedule->schedule_no ?? 'New' }}</span>
                        <div class="actions">
                            <button class="btn-default-gf" style="background: rgba(255,255,255,0.2); border: none; color: #fff;"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                        </div>
                    </div>
                    <div class="portlet-body" style="padding: 10px;">
                        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                            <!-- Left: File Upload -->
                            <div style="flex: 1; min-width: 250px; max-width: 300px;">
                                <div class="portlet light" style="border: 1px solid #e7ecf1; margin: 0;">
                                    <div class="portlet-title" style="min-height: 25px; padding: 5px 10px;">
                                        <div style="color: #999; font-size: 11px; font-weight: 700;"><i class="fa fa-cog"></i> SELECT FILES</div>
                                    </div>
                                    <div class="portlet-body" style="padding: 10px;">
                                        <div style="border: 2px dashed #ddd; text-align: center; padding: 30px 10px; color: #666; font-size: 11px; margin-bottom: 10px;">
                                            Drag and drop file(s) here...
                                        </div>
                                        <input type="file" style="font-size: 10px;" @change="uploadDocument">
                                    </div>
                                </div>
                            </div>
                            <!-- Right: Document List -->
                            <div style="flex: 3; min-width: 400px;">
                                <div class="portlet light" style="border: 1px solid #e7ecf1; margin: 0;">
                                    <div class="portlet-title" style="min-height: 25px; padding: 5px 10px;">
                                        <div style="color: #999; font-size: 11px; font-weight: 700;"><i class="fa fa-cog"></i> DOCUMENT LIST <span style="font-weight: 400;">0 file(s)</span></div>
                                    </div>
                                    <div class="portlet-body" style="padding: 10px;">
                                        <div style="margin-bottom: 5px;">
                                            <button class="btn-default-gf" style="padding: 4px 8px;"><i class="fa fa-envelope-o"></i></button>
                                            <button class="btn-default-gf" style="padding: 4px 8px;" @click="scheduleDocuments.forEach(d => deleteDocument(d.id))"><i class="fa fa-trash"></i></button>
                                        </div>
                                        <table class="table-custom" style="width: 100%;">
                                            <thead style="background: #f9fafc;">
                                                <tr>
                                                    <th style="width: 30px; text-align: center;"><input type="checkbox"></th>
                                                    <th>NAME</th>
                                                    <th>DATE</th>
                                                    <th>SIZE</th>
                                                    <th>TYPE</th>
                                                    <th style="text-align: center;">ACTIONS</th>
                                                    <th>CREATOR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="doc in scheduleDocuments" :key="doc.id">
                                                    <tr>
                                                        <td style="text-align: center;"><input type="checkbox"></td>
                                                        <td><a :href="VESSEL_SCHEDULE_ROUTES.documentDownload(doc.id)" x-text="doc.file_name"></a></td>
                                                        <td x-text="new Date(doc.created_at).toLocaleDateString()"></td>
                                                        <td x-text="doc.file_size ? (doc.file_size / 1024).toFixed(1) + ' KB' : ''"></td>
                                                        <td x-text="doc.file_extension"></td>
                                                        <td style="text-align: center;">
                                                            <a :href="VESSEL_SCHEDULE_ROUTES.documentDownload(doc.id)" class="btn-default-gf" style="padding: 1px 4px; text-decoration: none;" target="_blank"><i class="fa fa-download"></i></a>
                                                            <button @click="deleteDocument(doc.id)" class="btn-default-gf" style="padding: 1px 4px; color: #e43a45;"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                        <td x-text="doc.uploader?.name || ''"></td>
                                                    </tr>
                                                </template>
                                                <tr x-show="scheduleDocuments.length === 0"><td colspan="7" style="height: 30px; text-align: center; color: #999;">No documents uploaded yet.</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HBL Doc Center -->
                <template x-for="(hbl, index) in bookings" :key="index">
                    <div class="portlet light" style="margin-top: 5px; border-color: #f3c200;">
                        <div class="portlet-title" style="background: #f3c200; color: #fff; min-height: 30px; padding: 5px 10px;">
                            <span style="font-size: 13px; font-weight: 600;" x-text="'Booking ' + (hbl.booking_no || 'New')"></span>
                            <div class="actions">
                                <button class="btn-default-gf" style="background: rgba(255,255,255,0.2); border: none; color: #fff;"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                                <i class="fa fa-times cursor-pointer" style="margin-left: 10px;" @click="bookings.splice(index, 1)"></i>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 10px;">
                            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                <!-- Left: File Upload -->
                                <div style="flex: 1; min-width: 250px; max-width: 300px;">
                                    <div class="portlet light" style="border: 1px solid #e7ecf1; margin: 0;">
                                        <div class="portlet-title" style="min-height: 25px; padding: 5px 10px;">
                                            <div style="color: #999; font-size: 11px; font-weight: 700;"><i class="fa fa-cog"></i> SELECT FILES</div>
                                        </div>
                                        <div class="portlet-body" style="padding: 10px;">
                                            <div style="border: 2px dashed #ddd; text-align: center; padding: 30px 10px; color: #666; font-size: 11px; margin-bottom: 10px;">
                                                Drag and drop file(s) here...
                                            </div>
                                            <input type="file" style="font-size: 10px;">
                                        </div>
                                    </div>
                                </div>
                                <!-- Right: Document List -->
                                <div style="flex: 3; min-width: 400px;">
                                    <div class="portlet light" style="border: 1px solid #e7ecf1; margin: 0;">
                                        <div class="portlet-title" style="min-height: 25px; padding: 5px 10px;">
                                        <div style="color: #999; font-size: 11px; font-weight: 700;"><i class="fa fa-cog"></i> DOCUMENT LIST <span style="font-weight: 400;" x-text="scheduleDocuments.length + ' file(s)'"></span></div>
                                        </div>
                                        <div class="portlet-body" style="padding: 10px;">
                                            <div style="margin-bottom: 5px;">
                                                <button class="btn-default-gf" style="padding: 4px 8px;"><i class="fa fa-envelope-o"></i></button>
                                                <button class="btn-default-gf" style="padding: 4px 8px;"><i class="fa fa-trash"></i></button>
                                            </div>
                                            <table class="table-custom" style="width: 100%;">
                                                <thead style="background: #f9fafc;">
                                                    <tr>
                                                        <th style="width: 30px; text-align: center;"><input type="checkbox"></th>
                                                        <th>NAME</th>
                                                        <th>DATE</th>
                                                        <th>SIZE</th>
                                                        <th>TYPE</th>
                                                        <th style="text-align: center;">ACTIONS</th>
                                                        <th>CREATOR</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr><td colspan="7" style="height: 30px;"></td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Work Order Tab -->
            <div x-show="activeTab === 'workorder'" style="display: none;">
                <!-- MBL Work Order -->
                <div class="portlet light" style="margin-bottom: 10px !important;">
                    <div class="portlet-title" style="background: #444; color: #fff; min-height: 30px; padding: 5px 10px;">
                        <span style="font-size: 13px; font-weight: 600;">Vessel Schedule {{ $schedule->schedule_no ?? 'New' }}</span>
                        <div class="actions">
                            <button class="btn-default-gf" style="background: rgba(255,255,255,0.2); border: none; color: #fff;"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                        </div>
                    </div>
                    <div class="portlet-body" style="padding: 10px; background: #eef1f5;">
                        <div style="margin-bottom: 5px; display: flex; gap: 5px;">
                            <button class="btn-tool" style="background: #32c5d2; padding: 4px 10px;" @click="pdoType = 'mbl'; showPdoModal = true"><i class="fa fa-plus"></i></button>
                            <button class="btn-default-gf" style="padding: 4px 10px;" @click="scheduleWorkOrders.forEach(wo => deleteWorkOrder(wo.id))"><i class="fa fa-trash"></i></button>
                        </div>
                        <table class="table-custom" style="width: 100%;">
                            <thead style="background: #8e9eae; color: #fff;">
                                <tr>
                                    <th style="width: 30px; text-align: center; background: #8e9eae; color: #fff; border-color: #8e9eae;"><input type="checkbox"></th>
                                    <th style="text-align: center; background: #8e9eae; color: #fff; border-color: #8e9eae;">No.</th>
                                    <th style="text-align: center; background: #8e9eae; color: #fff; border-color: #8e9eae;">D/O Type</th>
                                    <th style="background: #8e9eae; color: #fff; border-color: #8e9eae;">Freight Pickup</th>
                                    <th style="background: #8e9eae; color: #fff; border-color: #8e9eae;">Delivery</th>
                                    <th style="background: #8e9eae; color: #fff; border-color: #8e9eae;">Trucker</th>
                                    <th style="text-align: center; background: #8e9eae; color: #fff; border-color: #8e9eae;">Last Modified</th>
                                    <th style="text-align: center; background: #8e9eae; color: #fff; border-color: #8e9eae;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(wo, i) in scheduleWorkOrders" :key="wo.id">
                                    <tr>
                                        <td style="text-align: center; background: #fff;"><input type="checkbox"></td>
                                        <td style="text-align: center; background: #fff;" x-text="i + 1"></td>
                                        <td style="text-align: center; background: #fff;" x-text="wo.work_order_no || 'PDO'"></td>
                                        <td style="background: #fff;" x-text="wo.freight_pickup_address || wo.freight_pickup_location?.name || ''"></td>
                                        <td style="background: #fff;" x-text="wo.empty_pickup_address || wo.empty_pickup_location?.name || ''"></td>
                                        <td style="background: #fff;" x-text="wo.vendor?.name || ''"></td>
                                        <td style="text-align: center; background: #fff;" x-text="wo.updated_at ? new Date(wo.updated_at).toLocaleDateString() : ''"></td>
                                        <td style="text-align: center; background: #fff;">
                                            <button @click="deleteWorkOrder(wo.id)" class="btn-default-gf" style="padding: 1px 4px; color: #e43a45;"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="scheduleWorkOrders.length === 0"><td colspan="8" style="height: 30px; background: #fff; text-align: center; color: #999;">No work orders yet.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- HBL Work Order -->
                <template x-for="(hbl, index) in bookings" :key="index">
                    <div class="portlet light" style="margin-top: 5px; border-color: #f3c200;">
                        <div class="portlet-title" style="background: #f3c200; color: #fff; min-height: 30px; padding: 5px 10px;">
                            <span style="font-size: 13px; font-weight: 600;" x-text="'Booking ' + (hbl.booking_no || 'New')"></span>
                            <div class="actions">
                                <button class="btn-default-gf" style="background: rgba(255,255,255,0.2); border: none; color: #fff;"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                                <i class="fa fa-times cursor-pointer" style="margin-left: 10px;" @click="bookings.splice(index, 1)"></i>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 10px; background: #eef1f5;">
                            <div style="margin-bottom: 5px; display: flex; gap: 5px;">
                                <button class="btn-tool" style="background: #32c5d2; padding: 4px 10px;" @click="pdoType = 'hbl'; showPdoModal = true"><i class="fa fa-plus"></i></button>
                                <button class="btn-default-gf" style="padding: 4px 10px;"><i class="fa fa-trash"></i></button>
                            </div>
                            <table class="table-custom" style="width: 100%;">
                                <thead style="background: #8e9eae; color: #fff;">
                                    <tr>
                                        <th style="width: 30px; text-align: center; background: #8e9eae; color: #fff; border-color: #8e9eae;"><input type="checkbox"></th>
                                        <th style="text-align: center; background: #8e9eae; color: #fff; border-color: #8e9eae;">No.</th>
                                        <th style="text-align: center; background: #8e9eae; color: #fff; border-color: #8e9eae;">D/O Type</th>
                                        <th style="background: #8e9eae; color: #fff; border-color: #8e9eae;">Freight Pickup</th>
                                        <th style="background: #8e9eae; color: #fff; border-color: #8e9eae;">Delivery</th>
                                        <th style="background: #8e9eae; color: #fff; border-color: #8e9eae;">Trucker</th>
                                        <th style="text-align: center; background: #8e9eae; color: #fff; border-color: #8e9eae;">Last Modified</th>
                                        <th style="text-align: center; background: #8e9eae; color: #fff; border-color: #8e9eae;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td colspan="8" style="height: 30px; background: #fff;"></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>
            </div> <!-- Close Work Order Tab -->

            <!-- Status Tab -->
            <div x-show="activeTab === 'status'" style="display: none;">
                <!-- MBL Status -->
                <div class="portlet light" style="margin-bottom: 10px !important;">
                    <div class="portlet-title" style="background: #444; color: #fff; min-height: 30px; padding: 5px 10px;">
                        <span style="font-size: 13px; font-weight: 600;">Vessel Schedule {{ $schedule->schedule_no ?? 'New' }}</span>
                        <div class="actions">
                            <button class="btn-default-gf" style="background: rgba(255,255,255,0.2); border: none; color: #fff;"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                        </div>
                    </div>
                    <div class="portlet-body" style="padding: 15px; background: #fff; border: 1px solid #e7ecf1; border-top: none;">
                        <div style="display: flex; gap: 20px;">
                            <div style="flex: 0 0 33%;">
                                <h4 style="font-size: 14px; font-weight: 600; margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Role</h4>
                                <div style="display: flex; align-items: center; gap: 10px; font-size: 12px; margin-bottom: 10px;">
                                    <span style="font-weight: 600; width: 40px;">OP :</span>
                                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: #5b6e84; color: #fff; border-radius: 50% !important; font-weight: bold;">{{ $schedule->op->name[0] ?? $loggedUser->name[0] ?? 'D' }}</span>
                                    <select name="op_id" class="form-control-gf" style="flex: 1; height: 26px; padding: 2px 5px;"><option value="">Select</option>@foreach($users as $user)<option value="{{ $user->id }}" {{ old('op_id', $schedule->op_id ?? $loggedUser->id ?? '') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>@endforeach</select>
                                </div>
                            </div>
                            <div style="flex: 1;">
                                <h4 style="font-size: 14px; font-weight: 600; margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Internal Message</h4>
                                <textarea x-model="internalMessage" class="form-control-gf" style="width: 100%; min-height: 55px; resize: none; border-radius: 2px;"></textarea>
                                <button @click="saveStatus" class="btn-tool" style="margin-top: 5px; padding: 4px 12px;" x-show="activeTab === 'status'">Save</button>
                            </div>
                        </div>

                        <h4 style="font-size: 14px; font-weight: 600; margin-top: 20px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Change Log</h4>
                        <div style="background: #f9f9f9; padding: 15px; border-left: 3px solid #d0d0d0;">
                            <template x-for="(log, i) in statusLogs" :key="log.id">
                                <div style="display: flex; gap: 20px; margin-bottom: 10px;">
                                    <div style="flex: 0 0 auto; text-align: right; width: 80px; font-size: 11px; color: #888;">
                                        <div x-text="new Date(log.event_time || log.created_at).toLocaleDateString()"></div>
                                        <div x-text="new Date(log.event_time || log.created_at).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'})"></div>
                                    </div>
                                    <div style="flex: 0 0 auto; position: relative;">
                                        <div style="width: 30px; height: 30px; background: #8e9eae; color: #fff; border-radius: 50% !important; display: flex; align-items: center; justify-content: center; font-weight: bold; position: relative; z-index: 2;" x-text="(log.user?.name?.[0] || '?').toUpperCase()"></div>
                                        <div style="position: absolute; top: 30px; bottom: -15px; left: 14px; width: 2px; background: #d0d0d0; z-index: 1;" x-show="i < statusLogs.length - 1"></div>
                                    </div>
                                    <div style="flex: 1; padding-top: 5px;">
                                        <div style="font-size: 13px; font-weight: 600; margin-bottom: 5px; color: #333;" x-text="log.status_name"></div>
                                        <div style="font-size: 12px; color: #666;" x-text="log.details"></div>
                                    </div>
                                </div>
                            </template>
                            <div x-show="statusLogs.length === 0" style="color: #999; font-size: 12px; text-align: center; padding: 10px;">No status updates yet.</div>
                        </div>
                    </div>
                </div>

                <!-- HBL Status -->
                <template x-for="(hbl, index) in bookings" :key="index">
                    <div class="portlet light" style="margin-top: 15px; border-color: #f3c200;">
                        <div class="portlet-title" style="background: #f3c200; color: #fff; min-height: 30px; padding: 5px 10px;">
                            <span style="font-size: 13px; font-weight: 600;" x-text="'Booking ' + (hbl.booking_no || 'New')"></span>
                            <div class="actions">
                                <button class="btn-default-gf" style="background: rgba(255,255,255,0.2); border: none; color: #fff;"><i class="fa fa-cogs"></i> Tools <i class="fa fa-angle-down"></i></button>
                                <i class="fa fa-times cursor-pointer" style="margin-left: 10px;" @click="bookings.splice(index, 1)"></i>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 15px; background: #fff; border: 1px solid #e7ecf1; border-top: none;">
                            <div style="display: flex; gap: 20px;">
                                <div style="flex: 0 0 33%;">
                                    <h4 style="font-size: 14px; font-weight: 600; margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Role</h4>
                                    <div style="display: flex; align-items: center; gap: 10px; font-size: 12px; margin-bottom: 10px;">
                                        <span style="font-weight: 600; width: 40px;">OP :</span>
                                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: #5b6e84; color: #fff; border-radius: 50% !important; font-weight: bold;">{{ $loggedUser->name[0] ?? 'D' }}</span>
                                        <select x-model="bookings[index].op_id" class="form-control-gf" style="flex: 1; height: 26px; padding: 2px 5px;"><option value="">Select</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 10px; font-size: 12px; margin-bottom: 10px;">
                                        <span style="font-weight: 600; width: 40px;">Sales :</span>
                                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: #5b6e84; color: #fff; border-radius: 50% !important; font-weight: bold;"></span>
                                        <select x-model="bookings[index].sales_person_id" class="form-control-gf" style="flex: 1; height: 26px; padding: 2px 5px;"><option value="">Select...</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select>
                                    </div>
                                </div>
                                <div style="flex: 1;">
                                    <h4 style="font-size: 14px; font-weight: 600; margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Internal Message</h4>
                                    <textarea class="form-control-gf" style="width: 100%; min-height: 55px; resize: none; border-radius: 2px;"></textarea>
                                </div>
                            </div>

                            <h4 style="font-size: 14px; font-weight: 600; margin-top: 20px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Change Log</h4>
                            <div style="background: #f9f9f9; padding: 15px; border-left: 3px solid #f3c200;">
                                <div style="display: flex; gap: 20px;">
                                    <div style="flex: 0 0 auto; text-align: right; width: 80px; font-size: 11px; color: #888;">
                                        <div>05-07-2026</div>
                                        <div>18:55</div>
                                    </div>
                                    <div style="flex: 0 0 auto; position: relative;">
                                        <div style="width: 30px; height: 30px; background: #8e9eae; color: #fff; border-radius: 50% !important; display: flex; align-items: center; justify-content: center; font-weight: bold; position: relative; z-index: 2;">D</div>
                                        <div style="position: absolute; top: 30px; bottom: -15px; left: 14px; width: 2px; background: #d0d0d0; z-index: 1;"></div>
                                    </div>
                                    <div style="flex: 1; padding-top: 5px;">
                                        <div style="font-size: 13px; font-weight: 600; margin-bottom: 5px; color: #333;">Booking Created</div>
                                         <div style="font-size: 12px; color: #666;">{{ $loggedUser->name }} ({{ $loggedUser->email }})</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div> <!-- Close Status Tab -->
        </div> <!-- Close inner container -->
        
        <!-- PDO Modal -->
        <div class="pdo-overlay" x-show="showPdoModal" style="display: none;" x-transition>
            <div class="pdo-modal" @click.away="showPdoModal = false">
                <div class="pdo-toolbar">
                    <div style="font-size: 12px; font-weight: bold; color: #4b77be;">Pickup / Delivery Order</div>
                    <div>
                        <button class="btn-tool" style="background: #32c5d2; padding: 4px 12px;" @click="submitWorkOrder"><i class="fa fa-save"></i> Save Work Order</button>
                        <button class="btn-default-gf" @click="showPdoModal = false"><i class="fa fa-times"></i> Close</button>
                    </div>
                </div>
                
                <div class="pdo-header">
                    <div style="flex: 1;">
                         <div class="pdo-title">{{ $loggedUser->name }}</div>
                         <div class="pdo-address">
                             {{ $loggedUser->email ? 'Email: ' . $loggedUser->email : '' }}<br>
                             <strong>Prepared by {{ $loggedUser->name }} {{ now()->format('m-d-Y H:i') }} (PDT)</strong>
                        </div>
                    </div>
                    <div style="width: 250px; text-align: right;">
                        <select class="pdo-input" style="height: 24px; font-size: 12px; margin-bottom: 0;">
                            <option>PICKUP & DELIVERY ORDER</option>
                        </select>
                    </div>
                </div>

                <div class="pdo-body">
                    <table class="pdo-top-table">
                        <tr>
                            <td width="15%">ISSUED AT:</td>
                            <td width="35%" style="font-weight: normal;">{{ now()->format('m-d-Y') }}</td>
                            <td width="15%">ISSUED BY:</td>
                            <td width="35%" style="font-weight: normal;">{{ $loggedUser->name }}</td>
                        </tr>
                    </table>

                    <div class="pdo-layout">
                        <!-- Left Column -->
                        <div class="pdo-left">
                            <div class="pdo-block">
                                <div class="pdo-block-title">TRUCKER <select style="width: 100px; font-size: 9px;"><option>Select...</option></select></div>
                                <textarea class="pdo-textarea" style="min-height: 50px;"></textarea>
                            </div>
                            
                            <div class="pdo-block">
                                <div class="pdo-block-title"><label><input type="checkbox" checked style="margin:0;"> EMPTY PICK UP LOCATION</label> <select style="width: 100px; font-size: 9px;"><option>Select...</option></select></div>
                                <textarea class="pdo-textarea"></textarea>
                                <div style="display: flex; align-items: center; gap: 5px; margin-top: 4px;">
                                    <span style="font-size: 9px; width: 60px;">REF. NO.:</span>
                                    <input type="text" class="pdo-input" style="margin: 0; background: #eee;">
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px; margin-top: 4px;">
                                    <span style="font-size: 9px; width: 60px;">DATE:</span>
                                    <input type="text" class="pdo-input" style="margin: 0; background: #eee;">
                                    <span style="font-size: 10px; font-weight: bold;">//</span>
                                </div>
                            </div>
                            
                            <div class="pdo-block">
                                <div class="pdo-block-title">FREIGHT PICK UP LOCATION <select style="width: 100px; font-size: 9px;"><option>Select...</option></select></div>
                                <textarea class="pdo-textarea"></textarea>
                                <div style="display: flex; align-items: center; gap: 5px; margin-top: 4px;">
                                    <span style="font-size: 9px; width: 60px;">REF. NO.:</span>
                                    <input type="text" class="pdo-input" style="margin: 0; background: #eee;">
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px; margin-top: 4px;">
                                    <span style="font-size: 9px; width: 60px;">DATE:</span>
                                    <input type="text" class="pdo-input" style="margin: 0; background: #eee;">
                                    <span style="font-size: 10px; font-weight: bold;">//</span>
                                </div>
                            </div>

                            <div class="pdo-block">
                                <div class="pdo-block-title"><label><input type="checkbox" checked style="margin:0;"> LOADED RETURN/DELIVERY TO</label> <select style="width: 100px; font-size: 9px;"><option>Select...</option></select></div>
                                <textarea class="pdo-textarea"></textarea>
                                <div style="display: flex; align-items: center; gap: 5px; margin-top: 4px;">
                                    <span style="font-size: 9px; width: 60px;">REF. NO.:</span>
                                    <input type="text" class="pdo-input" style="margin: 0; background: #eee;">
                                </div>
                                <div style="display: flex; align-items: center; gap: 5px; margin-top: 4px;">
                                    <span style="font-size: 9px; width: 60px;">DATE:</span>
                                    <input type="text" class="pdo-input" style="margin: 0; background: #eee;">
                                    <span style="font-size: 10px; font-weight: bold;">//</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="pdo-right">
                            <table class="pdo-table">
                                <tr>
                                    <td width="50%">
                                        <div class="td-label">MB/L NO.</div>
                                        <div style="height: 14px;"></div>
                                    </td>
                                    <td width="50%">
                                        <div class="td-label">HB/L NO.</div>
                                        <div style="height: 14px;"></div>
                                    </td>
                                </tr>
                                <tr x-show="pdoType === 'mbl'">
                                    <td>
                                        <div class="td-label">OUR REF. NO.</div>
                                        <div>VS-26050002</div>
                                    </td>
                                    <td>
                                        <div class="td-label">CARRIER BKG NO.</div>
                                        <input type="text" class="pdo-input" style="margin-top: 4px; background: #eee;">
                                    </td>
                                </tr>
                                <tr x-show="pdoType === 'hbl'" style="display: none;">
                                    <td>
                                        <div class="td-label">BOOKING NO.</div>
                                        <div>MOB-26050002</div>
                                    </td>
                                    <td>
                                        <div class="td-label">CARRIER BKG NO.</div>
                                        <input type="text" class="pdo-input" style="margin-top: 4px; background: #eee;">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="td-label" style="display: flex; justify-content: space-between; align-items: center;">CARRIER <select style="width: 150px; font-weight: normal; font-size: 9px;"><option>Select...</option></select></div>
                                        <input type="text" class="pdo-input" style="margin-top: 4px; background: #eee;">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="td-label">VESSEL INFO.</div>
                                        <div style="height: 14px;"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="td-label">PLACE OF RECEIPT</div>
                                        <input type="text" class="pdo-input" style="margin-top: 4px; background: #eee;">
                                    </td>
                                    <td>
                                        <div class="td-label">ETD</div>
                                        <div style="height: 18px;"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="td-label">PORT OF LOADING</div>
                                        <input type="text" class="pdo-input" style="margin-top: 4px; background: #eee;">
                                    </td>
                                    <td>
                                        <div class="td-label">ETD</div>
                                        <div>05-21-2026</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="td-label">PORT OF DISCHARGE</div>
                                        <input type="text" class="pdo-input" style="margin-top: 4px; background: #eee;">
                                    </td>
                                    <td>
                                        <div class="td-label">ETA</div>
                                        <div style="height: 18px;"></div>
                                    </td>
                                </tr>
                                
                                <!-- MBL: Container first, then Packages -->
                                <template x-if="pdoType === 'mbl'">
                                    <tr>
                                        <td colspan="2" style="padding: 0;">
                                            <table style="width: 100%; border-collapse: collapse;">
                                                <tr>
                                                    <td colspan="2" style="border: none; padding: 4px;">
                                                        <div class="td-label">Container/Qty.</div>
                                                        <input type="text" class="pdo-input" style="margin-top: 4px; background: #eee;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" style="border-right: 1px solid #4b77be; border-top: 1px solid #4b77be; border-bottom: none; border-left: none; padding: 4px;">
                                                        <div class="td-label">TOTAL PACKAGES</div>
                                                        <div style="display: flex; gap: 5px; margin-top: 4px;">
                                                            <input type="text" value="0" class="pdo-input" style="width: 50px; margin: 0;">
                                                            <select class="pdo-input" style="flex: 1; margin: 0;"><option>CARTON(S)</option></select>
                                                        </div>
                                                    </td>
                                                    <td width="50%" style="border-top: 1px solid #4b77be; border-bottom: none; border-left: none; border-right: none; padding: 4px;">
                                                        <div class="td-label">PORT CUT-OFF</div>
                                                        <div style="height: 18px;"></div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </template>

                                <!-- HBL: Packages first, then Container and Early Return -->
                                <template x-if="pdoType === 'hbl'">
                                    <tr>
                                        <td colspan="2" style="padding: 0;">
                                            <table style="width: 100%; border-collapse: collapse;">
                                                <tr>
                                                    <td width="50%" style="border-right: 1px solid #4b77be; border-bottom: none; border-top: none; border-left: none; padding: 4px;">
                                                        <div class="td-label">TOTAL PACKAGES</div>
                                                        <div style="display: flex; gap: 5px; margin-top: 4px;">
                                                            <input type="text" value="0" class="pdo-input" style="width: 50px; margin: 0;">
                                                            <select class="pdo-input" style="flex: 1; margin: 0;"><option>CARTON(S)</option></select>
                                                        </div>
                                                    </td>
                                                    <td width="50%" style="border-bottom: none; border-top: none; border-left: none; border-right: none; padding: 4px;">
                                                        <div class="td-label">PORT CUT-OFF</div>
                                                        <div style="height: 18px;"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" style="border-right: 1px solid #4b77be; border-top: 1px solid #4b77be; border-bottom: none; border-left: none; padding: 4px;">
                                                        <div class="td-label">Container/Qty.</div>
                                                        <input type="text" class="pdo-input" style="margin-top: 4px; background: #eee;">
                                                    </td>
                                                    <td width="50%" style="border-top: 1px solid #4b77be; border-bottom: none; border-left: none; border-right: none; padding: 4px;">
                                                        <div class="td-label">EARLY RETURN</div>
                                                        <div style="height: 18px;"></div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </template>
                                <tr>
                                    <td colspan="2" style="padding: 0;">
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td width="50%" style="border-right: 1px solid #4b77be; border-bottom: none; border-top: none; border-left: none;">
                                                    <div class="td-label">GROSS WEIGHT</div>
                                                    <div style="display: flex; gap: 5px; align-items: center; margin-top: 4px;">
                                                        <input type="text" value="0.00" class="pdo-input" style="width: 50px; margin:0; text-align: right;"> KGS
                                                        <input type="text" value="0.00" class="pdo-input" style="width: 50px; margin:0; text-align: right;"> LBS
                                                    </div>
                                                </td>
                                                <td width="50%" style="border: none;">
                                                    <div class="td-label">MEASUREMENT</div>
                                                    <div style="display: flex; gap: 5px; align-items: center; margin-top: 4px;">
                                                        <input type="text" value="0.00" class="pdo-input" style="width: 50px; margin:0; text-align: right;"> CBM
                                                        <input type="text" value="0.00" class="pdo-input" style="width: 50px; margin:0; text-align: right;"> CFT
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="td-label">COMMODITY</div>
                                        <div style="height: 14px;"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="td-label">PO NO.</div>
                                        <div style="height: 14px;"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <label style="font-size: 10px; margin-bottom: 4px; display: block;">Show: <input type="checkbox" checked style="margin:0;"> Bill To Party</label>
                                        <div class="td-label" style="display: flex; justify-content: space-between; align-items: center;">BILL TO <select style="width: 150px; font-weight: normal; font-size: 9px;"><option>Select...</option></select></div>
                                         <textarea class="pdo-textarea" style="min-height: 45px; margin-top: 4px;">{{ $schedule->customer->name ?? $loggedUser->name ?? '' }}
{{ $schedule->customer->address ?? '' }}</textarea>
                                        <div style="display: flex; align-items: center; gap: 5px; margin-top: 4px;">
                                            <span style="font-size: 9px; width: 60px; font-weight: bold;">REF. NO.:</span>
                                            <input type="text" class="pdo-input" style="margin: 0; background: #eee;">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <table class="pdo-table" style="margin-top: 10px;">
                        <tr style="background: #f9f9f9; text-align: center;">
                            <td width="30"><input type="checkbox" checked></td>
                            <td>CONTAINER NO.</td>
                            <td>TYPE</td>
                            <td>SEAL NO.</td>
                            <td>PACKAGE</td>
                            <td>WEIGHT</td>
                            <td>PICKUP NO.</td>
                            <td>L.F.D</td>
                        </tr>
                        <tr><td colspan="8" style="height: 20px;"></td></tr>
                    </table>

                    <table class="pdo-table" style="margin-top: 10px;">
                        <tr>
                            <td width="40%" style="font-size: 9px; font-weight: bold; border-right: none;">
                                P.O.D REQUIRED WITH BILLING INVOICE<br>
                                PLEASE FAX PROOF OF DELIVERY TO 999-000-5555
                            </td>
                            <td width="60%" style="border-left: none;">
                                <div class="td-label">DESCRIPTION / INSTRUCTION</div>
                                <textarea class="pdo-textarea" style="min-height: 60px; margin-top: 5px;"></textarea>
                            </td>
                        </tr>
                    </table>

                    <div style="margin-top: 10px; font-size: 12px; font-weight: bold;">
                        <label><input type="checkbox" style="margin: 0;"> DO NOT BREAK DOWN PALLET</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charge Modal -->
    <div class="pdo-overlay" x-show="showChargeModal" style="display: none;" x-transition>
        <div class="pdo-modal" @click.away="showChargeModal = false">
            <div class="pdo-toolbar">
                <div style="font-size: 12px; font-weight: bold; color: #4b77be;">Add/Edit Charge</div>
                <div>
                    <button class="btn-tool" style="background: #32c5d2; padding: 4px 12px;" @click="saveChargeModal"><i class="fa fa-save"></i> Save Charge</button>
                    <button class="btn-default-gf" @click="showChargeModal = false"><i class="fa fa-times"></i> Close</button>
                </div>
            </div>
            
            <div class="pdo-body" style="padding: 15px;">
                <div class="form-grid-4">
                    <div class="form-group-gf">
                        <label class="form-label-gf">Type</label>
                        <div class="form-input-container">
                            <select x-model="chargeModalForm.type" class="form-control-gf">
                                <option value="AR">AR (Revenue)</option>
                                <option value="AP">AP (Cost)</option>
                                <option value="DC">DC (Destination)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group-gf">
                        <label class="form-label-gf">Charge Code</label>
                        <div class="form-input-container">
                            <input type="text" x-model="chargeModalForm.charge_code" class="form-control-gf">
                        </div>
                    </div>
                    
                    <div class="form-group-gf">
                        <label class="form-label-gf">Charge Name</label>
                        <div class="form-input-container">
                            <input type="text" x-model="chargeModalForm.charge_name" class="form-control-gf">
                        </div>
                    </div>
                    
                    <div class="form-group-gf">
                        <label class="form-label-gf">Rate</label>
                        <div class="form-input-container">
                            <input type="number" step="0.01" x-model="chargeModalForm.rate" class="form-control-gf" @input="chargeModalForm.amount = (parseFloat(chargeModalForm.rate) || 0) * (parseFloat(chargeModalForm.qty) || 1)">
                        </div>
                    </div>
                    
                    <div class="form-group-gf">
                        <label class="form-label-gf">Quantity</label>
                        <div class="form-input-container">
                            <input type="number" x-model="chargeModalForm.qty" class="form-control-gf" @input="chargeModalForm.amount = (parseFloat(chargeModalForm.rate) || 0) * (parseFloat(chargeModalForm.qty) || 1)">
                        </div>
                    </div>
                    
                    <div class="form-group-gf">
                        <label class="form-label-gf">Amount</label>
                        <div class="form-input-container">
                            <input type="number" step="0.01" x-model="chargeModalForm.amount" class="form-control-gf" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group-gf">
                        <label class="form-label-gf">Currency</label>
                        <div class="form-input-container">
                            <select x-model="chargeModalForm.currency_id" class="form-control-gf">
                                <option value="">Select</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group-gf">
                        <label class="form-label-gf">PC</label>
                        <div class="form-input-container">
                            <select x-model="chargeModalForm.pc" class="form-control-gf">
                                <option value="COLLECT">COLLECT</option>
                                <option value="PREPAID">PREPAID</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group-gf">
                        <label class="form-label-gf">Vendor</label>
                        <div class="form-input-container">
                            <select x-model="chargeModalForm.vendor_id" class="form-control-gf">
                                <option value="">Select</option>
                                @foreach($tradePartners as $tp)
                                    <option value="{{ $tp->id }}">{{ $tp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group-gf">
                        <label class="form-label-gf">Bill To</label>
                        <div class="form-input-container">
                            <select x-model="chargeModalForm.bill_to_id" class="form-control-gf">
                                <option value="">Select</option>
                                @foreach($tradePartners as $tp)
                                    <option value="{{ $tp->id }}">{{ $tp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group-gf">
                        <label class="form-label-gf">Remark</label>
                        <div class="form-input-container">
                            <input type="text" x-model="chargeModalForm.remark" class="form-control-gf">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Work Order Subject Modal -->
    <div class="pdo-overlay" x-show="showWorkOrderSubjectModal" style="display: none;" x-transition>
        <div class="pdo-modal" style="width: 400px;" @click.away="showWorkOrderSubjectModal = false">
            <div class="pdo-toolbar">
                <div style="font-size: 12px; font-weight: bold; color: #4b77be;">Work Order Subject</div>
                <div>
                    <button class="btn-tool" style="background: #32c5d2; padding: 4px 12px;" @click="submitWorkOrderWithSubject"><i class="fa fa-save"></i> Create Work Order</button>
                    <button class="btn-default-gf" @click="showWorkOrderSubjectModal = false"><i class="fa fa-times"></i> Cancel</button>
                </div>
            </div>
            
            <div class="pdo-body" style="padding: 15px;">
                <div class="form-group-gf">
                    <label class="form-label-gf">Subject</label>
                    <div class="form-input-container">
                        <input type="text" x-model="workOrderSubject" class="form-control-gf" placeholder="Enter work order subject">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        @php
            $routeParams = [
                'status' => fn($id) => $id ? route('vessel-schedules.status.list', ['schedule' => $id]) : '#',
                'statusSave' => fn($id) => $id ? route('vessel-schedules.status.save', ['schedule' => $id]) : '#',
                'chargesList' => fn($id) => route('vessel-schedules.charges.list', ['schedule' => $id]),
                'chargeStore' => fn($id) => route('vessel-schedules.charges.store', ['schedule' => $id]),
                'chargeUpdate' => fn($id) => route('vessel-schedules.charges.update', ['charge' => $id]),
                'chargeDelete' => fn($id) => route('vessel-schedules.charges.destroy', ['charge' => $id]),
                'documentsStore' => fn($id) => route('vessel-schedules.documents.store', ['schedule' => $id]),
                'documentDelete' => fn($id) => route('vessel-schedules.documents.destroy', ['document' => $id]),
                'documentDownload' => fn($id) => route('vessel-schedules.documents.download', ['document' => $id]),
                'workOrderStore' => route('ocean-export.work-order.store'),
                'workOrderDelete' => fn($id) => route('ocean-export.work-order.destroy', ['id' => $id]),
            ];
        @endphp
        const VESSEL_SCHEDULE_ROUTES = {
            status: (id) => '{{ $routeParams["status"]($schedule->id ?? null) }}'.replace('ID', id),
            statusSave: (id) => '{{ $routeParams["statusSave"]($schedule->id ?? null) }}'.replace('ID', id),
            chargesList: (id) => '{{ $routeParams["chargesList"]($schedule->id ?? "ID") }}'.replace('ID', id),
            chargeStore: (id) => '{{ $routeParams["chargeStore"]($schedule->id ?? "ID") }}'.replace('ID', id),
            chargeUpdate: (id) => '{{ $routeParams["chargeUpdate"]("ID") }}'.replace('ID', id),
            chargeDelete: (id) => '{{ $routeParams["chargeDelete"]("ID") }}'.replace('ID', id),
            documentsStore: (id) => '{{ $routeParams["documentsStore"]($schedule->id ?? "ID") }}'.replace('ID', id),
            documentDelete: (id) => '{{ $routeParams["documentDelete"]("ID") }}'.replace('ID', id),
            documentDownload: (id) => '{{ $routeParams["documentDownload"]("ID") }}'.replace('ID', id),
            workOrderStore: '{{ $routeParams["workOrderStore"] }}',
            workOrderDelete: (id) => '{{ $routeParams["workOrderDelete"]("ID") }}'.replace('ID', id)
        };

        function vesselScheduleModule() {
            const scheduleId = {{ isset($schedule) ? $schedule->id : 'null' }};
            const baseUrl = '/ocean-export/vessel-schedule';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

            return {
                showPdoModal: false,
                pdoType: 'mbl',
                activeTab: 'basic',
                hideVessel: false,
                showVesselMore: false,
                saved: @json(isset($schedule) ? true : false),
                showChargeModal: false,
                chargeModalForm: {
                    type: 'AR',
                    charge_code: '',
                    charge_name: '',
                    rate: '',
                    qty: '1',
                    amount: '',
                    currency_id: '',
                    pc: 'COLLECT',
                    vendor_id: '',
                    bill_to_id: '',
                    remark: ''
                },
                editingChargeModalId: null,
                showWorkOrderSubjectModal: false,
                workOrderSubject: '',
                bookings: [],

                // Containers
                containers: [],
                containerTotals: { pkg: 0, weight: '0.00', measure: '0.00' },

                // Memos
                memos: [],
                selectedMemoIdx: -1,
                memoContent: '',
                internalMessage: '{{ isset($schedule) ? $schedule->internal_message : '' }}',
                statusLogs: [],

                // Accounting tab
                charges: [],
                // Doc Center tab
                scheduleDocuments: [],

                // Work Order tab
                scheduleWorkOrders: [],

                init() {
                    this.addBooking();
                    if (scheduleId) {
                        this.loadContainers();
                        this.loadMemos();
                        this.loadCharges();
                        this.loadDocuments();
                        this.loadWorkOrders();
                        this.loadStatusLogs();
                    }
                },

                // ===== CONTAINERS =====
                loadContainers() {
                    @if(isset($schedule) && $schedule->containers_data)
                        this.containers = @json($schedule->containers_data);
                    @endif
                    this.$nextTick(() => this.calcContainerTotals());
                },

                // ===== BOOKINGS =====
                emptyBooking() {
                    return {
                        auto_booking_no: true,
                        booking_no: '',
                        booking_date: new Date().toISOString().split('T')[0],
                        hbl_no: '', quotation_no: '', itn_no: '',
                        sales_person_id: '', reference_no: '', carrier_bkg_no: '',
                        carrier_id: '', ship_mode: '', svc_term_from_id: '', svc_term_to_id: '',
                        incoterms: '', actual_shipper_id: '', customer_id: '', bill_to_id: '',
                        consignee_id: '', notify_id: '', vessel: '', voyage: '', pickup_no: '',
                        por_id: '', pol_id: '', etd: '', pod_id: '', eta: '', del_id: '',
                        fdest_id: '', final_eta: '', cargo_type: '', referred_by_id: '',
                        cargo_pickup: '', trucker_id: '', delivery_to_pier: '', cargo_ready: '',
                        empty_pickup: '', wh_cutoff: '', doc_cutoff: '', port_cutoff: '',
                        vgm_cutoff: '', office_id: '', op_id: '', po_no: '', po_map: 'container', mark: '', description: '',
                    };
                },
                addBooking() {
                    this.bookings.push(this.emptyBooking());
                },

                // ===== CONTAINERS =====
                emptyContainer() {
                    return { container_no: '', type_size: '', seal_no: '', booking_no: '', pkg: 0, weight: 0, measure: 0, mbl_no: '', selected: false };
                },
                addContainer() {
                    this.containers.push(this.emptyContainer());
                    this.$nextTick(() => this.calcContainerTotals());
                },
                toggleSelectAll() {
                    const allSelected = this.containers.length > 0 && this.containers.every(c => c.selected);
                    this.containers.forEach(c => c.selected = !allSelected);
                },
                createMbl() {
                    const selected = this.containers.filter(c => c.selected);
                    if (!selected.length) { alert('Select at least one container.'); return; }
                    const mblNo = 'MBL-' + new Date().toISOString().slice(0,10).replace(/-/g,'') + '-' + String(Date.now()).slice(-4);
                    selected.forEach(c => c.mbl_no = mblNo);
                    selected.forEach(c => c.selected = false);
                    alert('MB/L ' + mblNo + ' created for ' + selected.length + ' container(s).');
                },
                calcContainerTotals() {
                    const pkg = this.containers.reduce((s, c) => s + (parseFloat(c.pkg) || 0), 0);
                    const weight = this.containers.reduce((s, c) => s + (parseFloat(c.weight) || 0), 0);
                    const measure = this.containers.reduce((s, c) => s + (parseFloat(c.measure) || 0), 0);
                    this.containerTotals = { pkg, weight: weight.toFixed(2), measure: measure.toFixed(2) };
                },

                // ===== MEMOS =====
                emptyMemo() {
                    return { id: null, subject: '', content: '', created_at: '', updated_at: '' };
                },
                loadMemos() {
                    @if(isset($schedule) && $schedule->memos_data)
                        this.memos = @json($schedule->memos_data);
                    @endif
                },
                addMemo() {
                    this.memos.push(this.emptyMemo());
                    this.selectedMemoIdx = this.memos.length - 1;
                    this.memoContent = '';
                },
                selectMemo(idx) {
                    this.selectedMemoIdx = idx;
                    this.memoContent = this.memos[idx].content || '';
                },
                saveMemo() {
                    if (this.selectedMemoIdx < 0 || this.selectedMemoIdx >= this.memos.length) return;
                    this.memos[this.selectedMemoIdx].content = this.memoContent;
                    this.memos[this.selectedMemoIdx].updated_at = new Date().toISOString();
                    if (!this.memos[this.selectedMemoIdx].created_at) {
                        this.memos[this.selectedMemoIdx].created_at = new Date().toISOString();
                    }
                    this.selectedMemoIdx = -1;
                    this.memoContent = '';
                },
                deleteMemo(idx) {
                    if (!confirm('Delete this memo?')) return;
                    this.memos.splice(idx, 1);
                    if (this.selectedMemoIdx === idx) {
                        this.selectedMemoIdx = -1;
                        this.memoContent = '';
                    }
                },

                // ===== STATUS TAB =====
                loadStatusLogs() {
                    if (!scheduleId) {
                        this.statusLogs = [];
                        return;
                    }
                    fetch(VESSEL_SCHEDULE_ROUTES.status(scheduleId), { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } })
                        .then(r => r.json())
                        .then(d => { this.statusLogs = Array.isArray(d) ? d : []; })
                        .catch(() => {
                            this.statusLogs = [];
                            console.error('Error loading status logs');
                        });
                },
                saveStatus() {
                    if (!scheduleId) return alert('Save the schedule first.');
                    const opSelect = document.querySelector('[name="op_id"]');
                    const op_id = opSelect ? opSelect.value : '';
                    fetch(VESSEL_SCHEDULE_ROUTES.statusSave(scheduleId), {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ internal_message: this.internalMessage, op_id: op_id })
                    })
                    .then(r => r.json())
                    .then(d => { if (d.success) { this.loadStatusLogs(); alert('Status saved!'); } })
                    .catch(() => alert('Error saving status'));
                },

                // ===== ACCOUNTING / CHARGES =====
                loadCharges() {
                    if (!scheduleId) return;
                    fetch(VESSEL_SCHEDULE_ROUTES.chargesList(scheduleId), { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken } })
                        .then(r => r.json())
                        .then(d => { if (d.success) this.charges = d.charges; })
                        .catch(() => alert('Error loading charges'));
                },
                chargesTotal() {
                    return this.charges.reduce((s, c) => s + parseFloat(c.total_amount || c.amount || 0), 0);
                },
                chargesAr() {
                    return this.charges.filter(c => c.type === 'AR').reduce((s, c) => s + parseFloat(c.total_amount || c.amount || 0), 0);
                },
                chargesAp() {
                    return this.charges.filter(c => c.type === 'AP').reduce((s, c) => s + parseFloat(c.total_amount || c.amount || 0), 0);
                },
                deleteCharge(id) {
                    if (!confirm('Delete this charge?')) return;
                    fetch(VESSEL_SCHEDULE_ROUTES.chargeDelete(id), {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    })
                    .then(r => r.json())
                    .then(d => { if (d.success) this.loadCharges(); })
                    .catch(() => alert('Error deleting charge'));
                },
                applyChargeTemplate() {
                    if (!scheduleId) return alert('Save the schedule first.');
                    fetch(VESSEL_SCHEDULE_ROUTES.chargesList(scheduleId) + '/template', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content }
                    })
                    .then(r => r.json())
                    .then(d => { if (d.success) { this.loadCharges(); alert('Template applied!'); } })
                    .catch(() => alert('Error applying template'));
                },

                // ===== DOC CENTER =====
                loadDocuments() {
                    if (!scheduleId) return;
                    this.scheduleDocuments = @json(isset($schedule) ? $schedule->documents : []);
                },
                uploadDocument(e) {
                    if (!scheduleId) return alert('Save the schedule first.');
                    const file = e.target.files[0];
                    if (!file) return;
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('description', file.name);
                    fetch(`${baseUrl}/${scheduleId}/documents`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
                        body: formData
                    })
                    .then(r => r.json())
                    .then(d => { if (d.success) { this.loadDocuments(); e.target.value = ''; } })
                    .catch(() => alert('Error uploading document'));
                },
                deleteDocument(id) {
                    if (!confirm('Delete this document?')) return;
                    fetch(`${baseUrl}/documents/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content }
                    })
                    .then(r => r.json())
                    .then(d => { if (d.success) this.loadDocuments(); })
                    .catch(() => alert('Error deleting document'));
                },

                // ===== WORK ORDERS =====
                loadWorkOrders() {
                    if (!scheduleId) return;
                    this.scheduleWorkOrders = @json(isset($schedule) ? $schedule->workOrders : []);
                },
                deleteWorkOrder(id) {
                    if (!confirm('Delete this work order?')) return;
                    fetch(VESSEL_SCHEDULE_ROUTES.workOrderDelete(id), {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    })
                    .then(r => r.json())
                    .then(d => { if (d.success) this.loadWorkOrders(); })
                    .catch(() => alert('Error deleting work order'));
                },
                submitWorkOrder() {
                    if (!scheduleId) return alert('Save the schedule first.');
                    this.workOrderSubject = '';
                    this.showWorkOrderSubjectModal = true;
                },

                submitWorkOrderWithSubject() {
                    if (!this.workOrderSubject) return alert('Please enter a subject.');
                    const woNo = 'WO-' + new Date().toISOString().slice(0,10).replace(/-/g,'') + '-' + Math.floor(1000 + Math.random()*9000);
                    fetch(VESSEL_SCHEDULE_ROUTES.workOrderStore, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({
                            work_order_no: woNo,
                            workable_type: 'App\\Models\\Schedule',
                            workable_id: scheduleId,
                            subject: this.workOrderSubject,
                            issue_date: new Date().toISOString().split('T')[0],
                            instructions: this.workOrderSubject,
                            status: 'PENDING',
                        })
                    })
                    .then(r => {
                        const ct = r.headers.get('content-type') || '';
                        if (ct.includes('json')) return r.json();
                        return r.json().catch(() => ({ success: true, id: null }));
                    })
                    .then(d => {
                        if (d.success || d.id) {
                            this.loadWorkOrders();
                            this.showPdoModal = false;
                            this.showWorkOrderSubjectModal = false;
                            alert('Work Order created!');
                        } else {
                            alert('Error creating work order');
                        }
                    })
                    .catch(() => alert('Error creating work order'));
                },

                // ===== FORM VALIDATION =====
                validateAndSubmit() {
                    // Validate required fields
                    const requiredFields = [
                        { name: 'vessel_id', label: 'Vessel' },
                        { name: 'pol_id', label: 'Port of Loading' },
                        { name: 'pod_id', label: 'Port of Discharge' },
                        { name: 'etd', label: 'ETD' },
                        { name: 'office_id', label: 'Office' }
                    ];
                    
                    for (const field of requiredFields) {
                        const input = document.querySelector(`[name="${field.name}"]`);
                        if (!input || !input.value) {
                            alert(`Please enter ${field.label}`);
                            return;
                        }
                    }
                    
                    // Validate bookings
                    if (this.bookings.length === 0) {
                        alert('Please add at least one booking');
                        return;
                    }
                    
                    // Validate booking fields
                    for (const booking of this.bookings) {
                        if (!booking.booking_no) {
                            alert('Booking No. is required for all bookings');
                            return;
                        }
                        if (!booking.booking_date) {
                            alert('Booking Date is required for all bookings');
                            return;
                        }
                        if (!booking.pol_id) {
                            alert('Port of Loading is required for all bookings');
                            return;
                        }
                        if (!booking.pod_id) {
                            alert('Port of Discharge is required for all bookings');
                            return;
                        }
                    }
                    
                    // Serialize data
                    document.getElementById('bookings-json').value = JSON.stringify(this.bookings);
                    document.getElementById('containers-json').value = JSON.stringify(this.containers);
                    document.getElementById('memos-json').value = JSON.stringify(this.memos);
                    
                    // Submit form
                    this.$el.submit();
                },

                // ===== CHARGE MODAL =====
                openChargeModal(type) {
                    this.chargeModalForm = {
                        type: type,
                        charge_code: '',
                        charge_name: '',
                        rate: '',
                        qty: '1',
                        amount: '',
                        currency_id: '',
                        pc: 'COLLECT',
                        vendor_id: '',
                        bill_to_id: '',
                        remark: ''
                    };
                    this.editingChargeModalId = null;
                    this.showChargeModal = true;
                },
                editChargeModal(charge) {
                    this.chargeModalForm = { ...charge };
                    this.editingChargeModalId = charge.id;
                    this.showChargeModal = true;
                },
                saveChargeModal() {
                    if (!scheduleId) return alert('Save the schedule first.');
                    
                    // Validate charge form
                    if (!this.chargeModalForm.charge_code) {
                        alert('Charge code is required');
                        return;
                    }
                    if (!this.chargeModalForm.charge_name) {
                        alert('Charge name is required');
                        return;
                    }
                    if (!this.chargeModalForm.rate) {
                        alert('Rate is required');
                        return;
                    }
                    
                    // Calculate amount
                    this.chargeModalForm.amount = (parseFloat(this.chargeModalForm.rate) || 0) * (parseFloat(this.chargeModalForm.qty) || 1);
                    
                    const url = this.editingChargeModalId
                        ? VESSEL_SCHEDULE_ROUTES.chargeUpdate(this.editingChargeModalId)
                        : VESSEL_SCHEDULE_ROUTES.chargeStore(scheduleId);
                    const method = this.editingChargeModalId ? 'PUT' : 'POST';
                    
                    fetch(url, {
                        method,
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify(this.chargeModalForm)
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            this.loadCharges();
                            this.showChargeModal = false;
                        }
                    })
                    .catch(() => alert('Error saving charge'));
                },
            }
        }
    </script>
</x-layout>
