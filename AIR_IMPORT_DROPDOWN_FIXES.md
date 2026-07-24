# Air Import - Dropdown Fixes Reference

## Controller Fixed ✅
The controller now loads:
- `$offices` - Active offices
- `$ports` - All ports (ordered)
- `$users` - All users (ordered)
- `$carriers` - Carriers only
- `$customers` - Customers only
- `$agents` - Agents only
- `$truckers` - Truckers only
- `$brokers` - Customs brokers only
- `$forwarders` - Forwarders only
- `$coloaders` - Co-loaders only
- `$allAgents` - All trade partners (fallback)
- `$packageUnits` - Package units
- `$containerTypes` - Container types
- `$incoterms` - Incoterms
- `$serviceTerms` - Service terms
- `$currencies` - Currencies

---

## View Fixes Required

### File: `resources/views/air-import/index.blade.php`

Replace each static dropdown with dynamic version:

---

### 1. Office Dropdown (Line ~300)
**Find:**
```html
<select class="form-control-gf"><option>MEO</option></select>
```

**Replace with:**
```html
<select name="office_id" class="form-control-gf" required>
    <option value="">Select Office...</option>
    @foreach($offices as $office)
        <option value="{{ $office->id }}" {{ (isset($airImport) && $airImport->office_id == $office->id) ? 'selected' : '' }}>
            {{ $office->code }}
        </option>
    @endforeach
</select>
```

---

### 2. Co-loader Dropdown (Line ~298)
**Find:**
```html
<div class="form-group-gf"><label class="form-label-gf">Co-loader</label><div class="form-input-container"><select class="form-control-gf"><option>Select...</option></select></div></div>
```

**Replace with:**
```html
<div class="form-group-gf"><label class="form-label-gf">Co-loader</label><div class="form-input-container"><select name="coloader_id" class="form-control-gf">
    <option value="">Select...</option>
    @foreach($coloaders as $cl)
        <option value="{{ $cl->id }}" {{ (isset($airImport) && $airImport->coloader_id == $cl->id) ? 'selected' : '' }}>
            {{ $cl->name }}
        </option>
    @endforeach
</select></div></div>
```

---

### 3. Oversea Agent Dropdown (Line ~306)
**Find:**
```html
<div class="form-group-gf"><label class="form-label-gf">Oversea Agent</label><div class="form-input-container"><select class="form-control-gf"><option>Select...</option></select></div></div>
```

**Replace with:**
```html
<div class="form-group-gf"><label class="form-label-gf">Oversea Agent</label><div class="form-input-container"><select name="oversea_agent_id" class="form-control-gf">
    <option value="">Select...</option>
    @foreach($agents as $agent)
        <option value="{{ $agent->id }}" {{ (isset($airImport) && $airImport->oversea_agent_id == $agent->id) ? 'selected' : '' }}>
            {{ $agent->name }}
        </option>
    @endforeach
</select></div></div>
```

---

### 4. Carrier Dropdown (Line ~313)
**Find:**
```html
<div class="form-group-gf"><label class="form-label-gf">Carrier</label><div class="form-input-container"><select class="form-control-gf"><option>Select...</option></select></div></div>
```

**Replace with:**
```html
<div class="form-group-gf"><label class="form-label-gf">Carrier</label><div class="form-input-container"><select name="carrier_id" class="form-control-gf">
    <option value="">Select...</option>
    @foreach($carriers as $carrier)
        <option value="{{ $carrier->id }}" {{ (isset($airImport) && $airImport->carrier_id == $carrier->id) ? 'selected' : '' }}>
            {{ $carrier->name }}
        </option>
    @endforeach
</select></div></div>
```

---

### 5. AWB Acct. Carrier Dropdown (Line ~320)
**Find:**
```html
<div class="form-group-gf"><label class="form-label-gf">AWB Acct. Carrier</label><div class="form-input-container"><select class="form-control-gf"><option>Select...</option></select></div></div>
```

**Replace with:**
```html
<div class="form-group-gf"><label class="form-label-gf">AWB Acct. Carrier</label><div class="form-input-container"><select name="acct_carrier_id" class="form-control-gf">
    <option value="">Select...</option>
    @foreach($carriers as $carrier)
        <option value="{{ $carrier->id }}" {{ (isset($airImport) && $airImport->acct_carrier_id == $carrier->id) ? 'selected' : '' }}>
            {{ $carrier->name }}
        </option>
    @endforeach
</select></div></div>
```

---

### 6. Departure Port Dropdown (Line ~328)
**Find:**
```html
<div class="form-group-gf"><label class="form-label-gf">Departure</label><div class="form-input-container"><select class="form-control-gf"><option>Select...</option></select></div></div>
```

**Replace with:**
```html
<div class="form-group-gf"><label class="form-label-gf">Departure</label><div class="form-input-container"><select name="dep_port_id" class="form-control-gf">
    <option value="">Select...</option>
    @foreach($ports as $port)
        <option value="{{ $port->id }}" {{ (isset($airImport) && $airImport->dep_port_id == $port->id) ? 'selected' : '' }}>
            {{ $port->code }} - {{ $port->name }}
        </option>
    @endforeach
</select></div></div>
```

---

### 7. Destination Port Dropdown (Line ~335)
**Find:**
```html
<div class="form-group-gf"><label class="form-label-gf">Destination</label><div class="form-input-container"><select class="form-control-gf"><option>Select...</option></select></div></div>
```

**Replace with:**
```html
<div class="form-group-gf"><label class="form-label-gf">Destination</label><div class="form-input-container"><select name="dst_port_id" class="form-control-gf">
    <option value="">Select...</option>
    @foreach($ports as $port)
        <option value="{{ $port->id }}" {{ (isset($airImport) && $airImport->dst_port_id == $port->id) ? 'selected' : '' }}>
            {{ $port->code }} - {{ $port->name }}
        </option>
    @endforeach
</select></div></div>
```

---

### 8. Freight Location Dropdown (Line ~330)
**Find:**
```html
<div class="form-group-gf"><label class="form-label-gf">Freight Location</label><div class="form-input-container"><select class="form-control-gf"><option>Select...</option></select></div></div>
```

**Replace with:**
```html
<div class="form-group-gf"><label class="form-label-gf">Freight Location</label><div class="form-input-container"><select name="freight_location_id" class="form-control-gf">
    <option value="">Select...</option>
    @foreach($ports as $port)
        <option value="{{ $port->id }}" {{ (isset($airImport) && $airImport->freight_location_id == $port->id) ? 'selected' : '' }}>
            {{ $port->name }}
        </option>
    @endforeach
</select></div></div>
```

---

### 9. Package Unit Dropdown (Line ~356)
**Find:**
```html
<select class="form-control-gf" style="flex:1; min-width:0;"><option>CARTON(S)</option></select>
```

**Replace with:**
```html
<select name="pkg_unit_id" class="form-control-gf" style="flex:1; min-width:0;">
    <option value="">Select...</option>
    @foreach($packageUnits as $unit)
        <option value="{{ $unit->id }}" {{ (isset($airImport) && $airImport->pkg_unit_id == $unit->id) ? 'selected' : '' }}>
            {{ $unit->name }}
        </option>
    @endforeach
</select>
```

---

### 10. Business Referred By Dropdown (Line ~389)
**Find:**
```html
<div class="form-group-gf"><label class="form-label-gf">Business Referred By</label><div class="form-input-container"><select class="form-control-gf"><option>Select...</option></select></div></div>
```

**Replace with:**
```html
<div class="form-group-gf"><label class="form-label-gf">Business Referred By</label><div class="form-input-container"><select name="referred_by_id" class="form-control-gf">
    <option value="">Select...</option>
    @foreach($allAgents as $agent)
        <option value="{{ $agent->id }}" {{ (isset($airImport) && $airImport->referred_by_id == $agent->id) ? 'selected' : '' }}>
            {{ $agent->name }}
        </option>
    @endforeach
</select></div></div>
```

---

### 11. Service Term From Dropdown (Line ~400)
**Find:**
```html
<select class="form-control-gf" style="width:45%;"><option>AIRPORT</option></select>
```

**Replace with:**
```html
<select name="svc_term_from_id" class="form-control-gf" style="width:45%;">
    <option value="">Select...</option>
    @foreach($serviceTerms as $term)
        <option value="{{ $term->id }}" {{ (isset($airImport) && $airImport->svc_term_from_id == $term->id) ? 'selected' : '' }}>
            {{ $term->code }}
        </option>
    @endforeach
</select>
```

---

### 12. Service Term To Dropdown (Line ~400)
**Find (second select):**
```html
<select class="form-control-gf" style="width:45%;"><option>AIRPORT</option></select>
```

**Replace with:**
```html
<select name="svc_term_to_id" class="form-control-gf" style="width:45%;">
    <option value="">Select...</option>
    @foreach($serviceTerms as $term)
        <option value="{{ $term->id }}" {{ (isset($airImport) && $airImport->svc_term_to_id == $term->id) ? 'selected' : '' }}>
            {{ $term->code }}
        </option>
    @endforeach
</select>
```

---

### 13. Cargo Type Dropdown (Line ~401)
**Find:**
```html
<div class="form-group-gf"><label class="form-label-gf">Cargo Type</label><div class="form-input-container"><select class="form-control-gf"><option>GENERAL CARGO</option></select></div></div>
```

**Replace with:**
```html
<div class="form-group-gf"><label class="form-label-gf">Cargo Type</label><div class="form-input-container"><select name="cargo_type" class="form-control-gf">
    <option value="">Select...</option>
    <option value="GENERAL CARGO" {{ (isset($airImport) && $airImport->cargo_type == 'GENERAL CARGO') ? 'selected' : '' }}>GENERAL CARGO</option>
    <option value="DANGEROUS GOODS" {{ (isset($airImport) && $airImport->cargo_type == 'DANGEROUS GOODS') ? 'selected' : '' }}>DANGEROUS GOODS</option>
    <option value="PERISHABLE" {{ (isset($airImport) && $airImport->cargo_type == 'PERISHABLE') ? 'selected' : '' }}>PERISHABLE</option>
    <option value="TEMPERATURE CONTROLLED" {{ (isset($airImport) && $airImport->cargo_type == 'TEMPERATURE CONTROLLED') ? 'selected' : '' }}>TEMPERATURE CONTROLLED</option>
</select></div></div>
```

---

## Additional Required Inputs (Add `name` attributes)

### File No
```html
<input type="text" name="file_no" class="form-control-gf" value="{{ $airImport->file_no ?? 'MAI-'.date('ymd').'-'.str_pad(1, 4, '0', STR_PAD_LEFT) }}" readonly style="background:#f5f5f5;">
```

### MAWB No (REQUIRED)
```html
<input type="text" name="mawb_no" class="form-control-gf" value="{{ $airImport->mawb_no ?? '' }}" required>
```

### Post Date
```html
<input type="date" name="post_date" class="form-control-gf" value="{{ $airImport->post_date ?? date('Y-m-d') }}" readonly style="background:#f5f5f5;">
```

### Direct Master Checkbox
```html
<input type="checkbox" name="is_direct_master" value="1" {{ (isset($airImport) && $airImport->is_direct_master) ? 'checked' : '' }} x-model="isDirectMaster">
```

### ETD
```html
<input type="date" name="etd" class="form-control-gf" value="{{ $airImport->etd ?? '' }}">
```

### ETA (REQUIRED)
```html
<input type="date" name="eta" class="form-control-gf" value="{{ $airImport->eta ?? '' }}" required>
```

### ATD
```html
<input type="date" name="atd" class="form-control-gf" value="{{ $airImport->atd ?? '' }}">
```

### ATA
```html
<input type="date" name="ata" class="form-control-gf" value="{{ $airImport->ata ?? '' }}">
```

### Flight No
```html
<input type="text" name="flight_no" class="form-control-gf" value="{{ $airImport->flight_no ?? '' }}">
```

### Package Qty
```html
<input type="number" name="pkg_qty" class="form-control-gf" value="{{ $airImport->pkg_qty ?? '' }}" step="1" min="0">
```

### Gross Weight KG
```html
<input type="number" name="gross_weight_kg" class="form-control-gf" value="{{ $airImport->gross_weight_kg ?? '' }}" step="0.01" min="0">
```

### Gross Weight LB
```html
<input type="number" name="gross_weight_lb" class="form-control-gf" value="{{ $airImport->gross_weight_lb ?? '' }}" step="0.01" min="0">
```

### Chargeable Weight KG
```html
<input type="number" name="chargeable_weight_kg" class="form-control-gf" value="{{ $airImport->chargeable_weight_kg ?? '' }}" step="0.01" min="0">
```

### Chargeable Weight LB
```html
<input type="number" name="chargeable_weight_lb" class="form-control-gf" value="{{ $airImport->chargeable_weight_lb ?? '' }}" step="0.01" min="0">
```

### Volume Weight KG
```html
<input type="number" name="volume_weight_kg" class="form-control-gf" value="{{ $airImport->volume_weight_kg ?? '' }}" step="0.01" min="0">
```

### Volume CBM
```html
<input type="number" name="volume_cbm" class="form-control-gf" value="{{ $airImport->volume_cbm ?? '' }}" step="0.01" min="0">
```

### Freight Term
```html
<select name="freight_term" class="form-control-gf">
    <option value="">Select...</option>
    <option value="PREPAID" {{ (isset($airImport) && $airImport->freight_term == 'PREPAID') ? 'selected' : '' }}>PREPAID</option>
    <option value="COLLECT" {{ (isset($airImport) && $airImport->freight_term == 'COLLECT') ? 'selected' : '' }}>COLLECT</option>
</select>
```

### Incoterms
```html
<select name="incoterm_id" class="form-control-gf">
    <option value="">Select...</option>
    @foreach($incoterms as $inco)
        <option value="{{ $inco->id }}" {{ (isset($airImport) && $airImport->incoterm_id == $inco->id) ? 'selected' : '' }}>
            {{ $inco->code }}
        </option>
    @endforeach
</select>
```

### Stackable
```html
<input type="radio" name="stackable" value="1" {{ (isset($airImport) && $airImport->stackable == 1) ? 'checked' : 'checked' }}> Yes 
<input type="radio" name="stackable" value="0" {{ (isset($airImport) && $airImport->stackable == 0) ? 'checked' : '' }}> No
```

### E-Commerce
```html
<input type="checkbox" name="is_ecommerce" value="1" {{ (isset($airImport) && $airImport->is_ecommerce) ? 'checked' : '' }}>
```

### Storage Start Date
```html
<input type="date" name="storage_start_date" class="form-control-gf" value="{{ $airImport->storage_start_date ?? '' }}">
```

---

## Form Wrapper (Critical!)

Add at the beginning of the Main tab content (after `<div x-show="activeTab === 'basic'">`):

```html
<form action="{{ isset($airImport) ? route('air-import.update', $airImport->id) : route('air-import.store') }}" 
      method="POST" 
      id="air-import-form">
    @csrf
    @if(isset($airImport))
        @method('PUT')
    @endif
```

Add at the end before closing the Main tab div:

```html
</form>
```

---

## Save Button Fix

Change the save button from:
```html
<button type="button" @click="saveShipment" class="btn-gofreight">
```

To:
```html
<button type="submit" form="air-import-form" class="btn-gofreight">
```

---

## Validation Errors Display

Add after the breadcrumbs:
```html
@if ($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 15px; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; color: #721c24;">
        <strong>Please fix the following errors:</strong>
        <ul style="margin: 5px 0 0 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success" style="margin-bottom: 15px; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; color: #155724;">
        {{ session('success') }}
    </div>
@endif
```

---

## Summary

1. ✅ Controller loads all data
2. ⏳ Apply 13 dropdown fixes above
3. ⏳ Add name attributes to all inputs
4. ⏳ Wrap form with proper form tag
5. ⏳ Fix save button to submit form
6. ⏳ Add validation error display

This will make the form functional with dynamic data!
