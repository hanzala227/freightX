@forelse($containers as $c)
<tr data-id="{{ $c->id }}" data-ocean-import-id="{{ $c->ocean_import_id }}" onclick="rowClick(event, this)">
    <td class="sticky-col" style="left:0;text-align:center;" onclick="event.stopPropagation()">
        <input type="checkbox" name="ids[]" value="{{ $c->id }}" class="row-check" onchange="updateToolbar()">
    </td>
    <td class="sticky-col" style="left:25px;text-align:center;">
        <i class="fa fa-flag" style="color:{{ $c->is_dg ? '#ef4444' : '#cbd5e1' }};font-size:10px;"></i>
    </td>
    <td class="sticky-col" style="left:50px;" onclick="event.stopPropagation()">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <a href="{{ $c->oceanImport ? route('ocean-import.edit', $c->oceanImport->id) : '#' }}" class="col-link" target="_blank">{{ $c->oceanImport->file_no ?? 'N/A' }}</a>
            <i class="fa fa-external-link" style="color:#94a3b8;font-size:10px;cursor:pointer;" title="Open"></i>
        </div>
    </td>
    <td class="sticky-col" style="left:185px;text-align:center;">
        <span class="color-mark" style="background:{{ $c->oceanImport->color ?? '#94a3b8' }}" title="Click to change status color" onclick="event.stopPropagation();openColorPicker({{ $c->oceanImport->id }}, '{{ $c->oceanImport->color ?? '' }}')"></span>
    </td>
    <td class="sticky-col" style="left:220px;">{{ $c->container_no ?? 'N/A' }}</td>
    <td class="sticky-col" style="left:345px;">{{ $c->oceanImport->dmConsignee->name ?? 'N/A' }}</td>

    <td><input type="text" class="input-inline" style="width:100%;border:1px solid #e2e8f0;padding:2px 4px;" value="{{ $c->remarks ?? '' }}" onblur="saveRemarks({{ $c->id }}, this.value)" placeholder="-"></td>
    <td>
        <span style="color:#3b82f6;font-weight:600;">{{ $c->oceanImport->ship_mode ?? 'FCL' }}</span> / 
        <span style="color:#64748b;font-size:9px;">{{ $c->containerType->code ?? '' }}</span>
    </td>
    <td>
        @if($c->oceanImport && $c->oceanImport->hbls->count())
            {{ $c->oceanImport->hbls->pluck('hbl_no')->join(', ') }}
        @else
            --
        @endif
    </td>
    <td>{{ $c->oceanImport->cfsLocation->name ?? ($c->oceanImport->cyLocation->name ?? '--') }}</td>
    <td>{{ $c->rail_start_date ? 'Yes' : 'No' }}</td>
    <td>{{ $c->it_no ?? '--' }}</td>
    <td>{{ $c->oceanImport && $c->oceanImport->etd ? $c->oceanImport->etd->format('m-d-Y') : '--' }}</td>
    <td>{{ $c->oceanImport && $c->oceanImport->eta ? $c->oceanImport->eta->format('m-d-Y') : '--' }}</td>
    <td>{{ $c->updated_at ? $c->updated_at->format('m-d-Y H:i') : '--' }}</td>
    
    <!-- Container Fields -->
    <td>
        <input type="text" class="cell-input" 
               value="{{ $c->pp_ctf ?? '' }}" 
               @input="markChanged({{ $c->id }}, 'pp_ctf', $event.target.value)"
               placeholder="-">
    </td>
    <td>{{ $c->containerType->code ?? '--' }}</td>
    <td>
        <input type="text" class="cell-input" 
               value="{{ $c->seal_no ?? '' }}" 
               @input="markChanged({{ $c->id }}, 'seal_no', $event.target.value)"
               placeholder="-">
    </td>
    <td>
        <input type="text" class="cell-input" 
               value="{{ $c->seal_no2 ?? '' }}" 
               @input="markChanged({{ $c->id }}, 'seal_no2', $event.target.value)"
               placeholder="-">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->lfd ? $c->lfd->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'lfd', $event.target.value)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->fdd ? $c->fdd->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'fdd', $event.target.value)">
    </td>
    <td>
        <input type="number" class="cell-input" step="0.01" style="text-align:right;"
               value="{{ $c->pkg_qty ?? '' }}" 
               @input="markChanged({{ $c->id }}, 'pkg_qty', $event.target.value)"
               placeholder="0">
    </td>
    <td>
        <input type="number" class="cell-input" step="0.01" style="text-align:right;"
               value="{{ $c->weight_kg ?? '' }}" 
               @input="markChanged({{ $c->id }}, 'weight_kg', $event.target.value)"
               placeholder="0.00">
    </td>
    <td>
        <input type="number" class="cell-input" step="0.01" style="text-align:right;"
               value="{{ $c->weight_lb ?? '' }}" 
               @input="markChanged({{ $c->id }}, 'weight_lb', $event.target.value)"
               placeholder="0.00">
    </td>
    <td>
        <input type="number" class="cell-input" step="0.01" style="text-align:right;"
               value="{{ $c->measure_cbm ?? '' }}" 
               @input="markChanged({{ $c->id }}, 'measure_cbm', $event.target.value)"
               placeholder="0.00">
    </td>
    <td>
        <input type="number" class="cell-input" step="0.01" style="text-align:right;"
               value="{{ $c->measure_cft ?? '' }}" 
               @input="markChanged({{ $c->id }}, 'measure_cft', $event.target.value)"
               placeholder="0.00">
    </td>
    <td style="text-align:center;">
        <input type="checkbox" class="cell-input" style="width:auto;"
               {{ $c->is_dg ? 'checked' : '' }}
               @change="markChanged({{ $c->id }}, 'is_dg', $event.target.checked ? 1 : 0)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->unload_vessel_date ? $c->unload_vessel_date->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'unload_vessel_date', $event.target.value)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->gate_in_date ? $c->gate_in_date->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'gate_in_date', $event.target.value)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->rail_start_date ? $c->rail_start_date->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'rail_start_date', $event.target.value)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->pod_eta ? $c->pod_eta->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'pod_eta', $event.target.value)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->appointment_date ? $c->appointment_date->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'appointment_date', $event.target.value)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->pickup_date ? $c->pickup_date->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'pickup_date', $event.target.value)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->gate_out_date ? $c->gate_out_date->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'gate_out_date', $event.target.value)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->fdest_eta ? $c->fdest_eta->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'fdest_eta', $event.target.value)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->eta_door ? $c->eta_door->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'eta_door', $event.target.value)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->ata_door ? $c->ata_door->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'ata_door', $event.target.value)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->empty_conf_date ? $c->empty_conf_date->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'empty_conf_date', $event.target.value)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->empty_ret_date ? $c->empty_ret_date->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'empty_ret_date', $event.target.value)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->storage_start_date ? $c->storage_start_date->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'storage_start_date', $event.target.value)">
    </td>
    <td>
        <input type="date" class="cell-input" 
               value="{{ $c->storage_end_date ? $c->storage_end_date->format('Y-m-d') : '' }}" 
               @input="markChanged({{ $c->id }}, 'storage_end_date', $event.target.value)">
    </td>
    <td>
        <input type="text" class="cell-input" 
               value="{{ $c->pickup_no ?? '' }}" 
               @input="markChanged({{ $c->id }}, 'pickup_no', $event.target.value)"
               placeholder="-">
    </td>
    <td>
        <input type="text" class="cell-input" 
               value="{{ $c->cprs_no ?? '' }}" 
               @input="markChanged({{ $c->id }}, 'cprs_no', $event.target.value)"
               placeholder="-">
    </td>
    <td>
        <input type="text" class="cell-input" 
               value="{{ $c->cnru_no ?? '' }}" 
               @input="markChanged({{ $c->id }}, 'cnru_no', $event.target.value)"
               placeholder="-">
    </td>
    <td style="text-align:center;">
        <input type="checkbox" class="cell-input" style="width:auto;"
               {{ $c->is_carrier_release ? 'checked' : '' }}
               @change="markChanged({{ $c->id }}, 'is_carrier_release', $event.target.checked ? 1 : 0)">
    </td>
    <td>
        <input type="text" class="cell-input" 
               value="{{ $c->yard_location ?? '' }}" 
               @input="markChanged({{ $c->id }}, 'yard_location', $event.target.value)"
               placeholder="-">
    </td>
    <td style="text-align:center;">
        <input type="checkbox" class="cell-input" style="width:auto;"
               {{ $c->is_avail_pickup ? 'checked' : '' }}
               @change="markChanged({{ $c->id }}, 'is_avail_pickup', $event.target.checked ? 1 : 0)">
    </td>
    <td>
        <select class="cell-select" 
                @change="markChanged({{ $c->id }}, 'trucker_id', $event.target.value)">
            <option value="">-- Select --</option>
            @foreach($truckers ?? [] as $trucker)
                <option value="{{ $trucker->id }}" {{ $c->trucker_id == $trucker->id ? 'selected' : '' }}>
                    {{ $trucker->name }}
                </option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="number" class="cell-input" step="1" style="text-align:right;"
               value="{{ $c->chassis_days ?? '' }}" 
               @input="markChanged({{ $c->id }}, 'chassis_days', $event.target.value)"
               placeholder="0">
    </td>
    <td style="text-align:center;">
        <input type="checkbox" class="cell-input" style="width:auto;"
               {{ $c->is_customs_hold ? 'checked' : '' }}
               @change="markChanged({{ $c->id }}, 'is_customs_hold', $event.target.checked ? 1 : 0)">
    </td>
    <td>
        <div style="display:flex;gap:4px;align-items:center;">
            <input type="checkbox" class="cell-input" style="width:auto;"
                   {{ $c->is_an_sent ? 'checked' : '' }}
                   @change="markChanged({{ $c->id }}, 'is_an_sent', $event.target.checked ? 1 : 0)">
            <input type="date" class="cell-input" style="flex:1;"
                   value="{{ $c->an_sent_date ? $c->an_sent_date->format('Y-m-d') : '' }}" 
                   @input="markChanged({{ $c->id }}, 'an_sent_date', $event.target.value)">
        </div>
    </td>
    <td>
        <div style="display:flex;gap:4px;align-items:center;">
            <input type="checkbox" class="cell-input" style="width:auto;"
                   {{ $c->is_do_sent ? 'checked' : '' }}
                   @change="markChanged({{ $c->id }}, 'is_do_sent', $event.target.checked ? 1 : 0)">
            <input type="date" class="cell-input" style="flex:1;"
                   value="{{ $c->do_sent_date ? $c->do_sent_date->format('Y-m-d') : '' }}" 
                   @input="markChanged({{ $c->id }}, 'do_sent_date', $event.target.value)">
        </div>
    </td>
    <td>
        <input type="text" class="cell-input" 
               value="{{ $c->container_remarks ?? '' }}" 
               @input="markChanged({{ $c->id }}, 'container_remarks', $event.target.value)"
               placeholder="-">
    </td>
    <td style="text-align:center;">
        <input type="checkbox" class="cell-input" style="width:auto;"
               {{ $c->is_complete ? 'checked' : '' }}
               @change="markChanged({{ $c->id }}, 'is_complete', $event.target.checked ? 1 : 0)">
    </td>
    
    <!-- Shipment Fields -->
    <td>{{ $c->oceanImport->mbl_no ?? '--' }}</td>
    <td>{{ $c->oceanImport->carrier->name ?? '--' }}</td>
    <td>{{ $c->oceanImport->vessel->name ?? '--' }}</td>
    <td>{{ $c->oceanImport->portOfLoading->name ?? '--' }}</td>
    <td>{{ $c->oceanImport->portOfDischarge->name ?? '--' }}</td>
    <td>{{ $c->oceanImport->placeOfDelivery->name ?? '--' }}</td>
    <td>{{ $c->oceanImport->finalDestination->name ?? '--' }}</td>
    <td>{{ $c->oceanImport->cyLocation->name ?? '--' }}</td>
    <td>{{ $c->oceanImport->office->name ?? '--' }}</td>
    <td>{{ $c->oceanImport->salesPerson->name ?? '--' }}</td>
    <td>{{ $c->oceanImport->operator->name ?? '--' }}</td>
    <td>{{ $c->oceanImport->dmShipper->name ?? '--' }}</td>
    <td>{{ $c->oceanImport->dmNotify->name ?? '--' }}</td>
    <td>{{ $c->oceanImport->dmCustomer->name ?? '--' }}</td>
    <td>{{ $c->oceanImport->voyage ?? '--' }}</td>
    <td>{{ $c->oceanImport->ship_mode ?? '--' }}</td>
    <td>{{ $c->oceanImport->etb ? $c->oceanImport->etb->format('m-d-Y') : '--' }}</td>
    <td>{{ $c->oceanImport->obl_type ?? '--' }}</td>
    <td>{{ $c->oceanImport->freight_term ?? '--' }}</td>
    <td>{{ $c->oceanImport->sales_type ?? '--' }}</td>
    <td>{{ $c->oceanImport->isf_no ?? '--' }}</td>
    <td style="text-align:center;">
        @if($c->oceanImport && $c->oceanImport->is_isf_3rd_party)
            <i class="fa fa-check" style="color:#22c55e;"></i>
        @else
            <i class="fa fa-times" style="color:#ef4444;"></i>
        @endif
    </td>
    <td>{{ $c->oceanImport && $c->oceanImport->isf_matched_date ? $c->oceanImport->isf_matched_date->format('m-d-Y') : '--' }}</td>
    <td>{{ $c->oceanImport->entry_no ?? '--' }}</td>
    <td>{{ $c->oceanImport && $c->oceanImport->entry_doc_sent_date ? $c->oceanImport->entry_doc_sent_date->format('m-d-Y') : '--' }}</td>
    <td>{{ $c->oceanImport->contract_no ?? '--' }}</td>
    <td>{{ $c->oceanImport->receipt->name ?? '--' }}</td>
    <td>{{ $c->oceanImport && $c->oceanImport->receipt_etd ? $c->oceanImport->receipt_etd->format('m-d-Y') : '--' }}</td>
    
    <!-- HBL Fields -->
    <td>{{ $c->oceanImport && $c->oceanImport->hbls->count() ? $c->oceanImport->hbls->pluck('po_no')->filter()->join(', ') : '--' }}</td>
    <td>{{ $c->oceanImport && $c->oceanImport->hbls->count() ? $c->oceanImport->hbls->pluck('is_express_bl')->map(fn($v) => $v ? 'Yes' : 'No')->join(', ') : '--' }}</td>
    <td>{{ $c->oceanImport && $c->oceanImport->hbls->count() ? $c->oceanImport->hbls->pluck('is_fr_released')->map(fn($v) => $v ? 'Yes' : 'No')->join(', ') : '--' }}</td>
    <td>{{ $c->oceanImport && $c->oceanImport->hbls->count() ? $c->oceanImport->hbls->pluck('is_customs_doc')->map(fn($v) => $v ? 'Yes' : 'No')->join(', ') : '--' }}</td>
    <td>{{ $c->oceanImport && $c->oceanImport->hbls->count() ? $c->oceanImport->hbls->pluck('is_customs_clear')->map(fn($v) => $v ? 'Yes' : 'No')->join(', ') : '--' }}</td>
    <td>{{ $c->oceanImport && $c->oceanImport->hbls->count() ? $c->oceanImport->hbls->pluck('deliveryLocation.name')->filter()->join(', ') : '--' }}</td>
</tr>
@empty
<tr id="empty-row">
    <td colspan="90" style="text-align:center;padding:30px 10px;color:#94a3b8;">
        <i class="fa fa-inbox" style="font-size:32px;display:block;margin-bottom:12px;opacity:0.5;"></i>
        <div style="font-size:13px;font-weight:600;">No containers found</div>
        <div style="font-size:11px;margin-top:4px;">Try adjusting your filters or search criteria</div>
    </td>
</tr>
@endforelse
