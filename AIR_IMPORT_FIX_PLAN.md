# Air Import Create/Edit - Complete Dynamic Fix Plan

## Current Issues Identified
1. ❌ **Missing dynamic options** in select dropdowns
2. ❌ **Static/hardcoded values** in the view
3. ❌ **CRUD operations not working** in tabs
4. ❌ **No frontend validation** for required fields
5. ❌ **Data not posting to database** properly
6. ❌ **SQL errors and validation errors**

## Scope
Make `http://localhost:8000/air-import/create` completely dynamic with:
- ✅ All select tags populated with database data
- ✅ Full CRUD in all tabs (Container, Charges, Filing, History, Documents, HBLs)
- ✅ Frontend validation for non-nullable fields
- ✅ All form data saving to database without errors
- ✅ Proper backend validation
- ✅ AJAX operations for sub-items

---

## Implementation Steps

### Phase 1: Controller & Data Loading (CRITICAL)
**File**: `app/Http/Controllers/AirImportController.php`

#### Fix `create()` method
Currently passes empty collections. Need to load:
- ✅ Offices (active only)
- ✅ Ports (all)
- ✅ Trade Partners (filtered by type: carriers, customers, agents, etc.)
- ✅ Users (all - for OP, Sales)
- ✅ Package Units
- ✅ Container Types  
- ✅ Incoterms
- ✅ Service Terms
- ✅ Currencies
- ✅ Vessels (if needed)

#### Fix `edit()` method
Load shipment with ALL relationships:
- ✅ HBLs with their relationships
- ✅ Containers
- ✅ Charges with currency
- ✅ Documents with uploader
- ✅ Status logs with user

---

### Phase 2: View Updates - Make Dynamic
**File**: `resources/views/air-import/index.blade.php`

#### Replace ALL hardcoded selects:

**Current (Static):**
```html
<select class="form-control-gf"><option>Select...</option></select>
```

**Fixed (Dynamic):**
```html
<select name="office_id" class="form-control-gf" required>
    <option value="">Select Office...</option>
    @foreach($offices as $office)
        <option value="{{ $office->id }}" {{ (isset($airImport) && $airImport->office_id == $office->id) ? 'selected' : '' }}>
            {{ $office->code }} - {{ $office->name }}
        </option>
    @endforeach
</select>
```

#### Fields to fix (30+ dropdowns):
1. **Office** - `$offices` collection
2. **Carrier** - `$agents` filtered by type='CR'
3. **Oversea Agent** - `$agents` filtered by type='AG'
4. **Co-loader** - `$agents` filtered by type='CL'
5. **Forwarding Agent** - `$agents` filtered by type='FW'
6. **AWB Acct. Carrier** - `$agents` filtered by type='CR'
7. **Departure Port** - `$ports`
8. **Destination Port** - `$ports`
9. **Freight Location** - `$ports` or custom field
10. **Package Unit** - `$packageUnits`
11. **Incoterms** - `$incoterms`
12. **Service Term From/To** - `$serviceTerms`
13. **Cargo Type** - Enum or config
14. **Business Referred By** - `$agents`
15. **OP (Operator)** - `$users`
16. **Sales Person** - `$users`
17. **Customer** - `$agents` filtered by type='CS'
18. **Shipper** - `$agents`
19. **Consignee** - `$agents`
20. **Notify Party** - `$agents`
21. **Bill To** - `$agents`
22. **Trucker** - `$agents` filtered by type='TR'
23. **Customs Broker** - `$agents` filtered by type='CB'
24. **Delivery Location** - `$agents` or ports
25. **Currency** (in charges) - `$currencies`

---

### Phase 3: Form Structure & Name Attributes

#### Add proper form tag:
```html
<form action="{{ isset($airImport) ? route('air-import.update', $airImport) : route('air-import.store') }}" 
      method="POST" 
      id="air-import-form"
      enctype="multipart/form-data">
    @csrf
    @if(isset($airImport))
        @method('PUT')
    @endif
    
    <!-- All form fields here -->
</form>
```

#### Add name attributes to ALL inputs:
```html
<!-- MAWB Fields -->
<input type="text" name="file_no" class="form-control-gf" value="{{ $airImport->file_no ?? 'MAI-'.date('ymd').'-'.str_pad(1, 4, '0', STR_PAD_LEFT) }}" required>
<input type="text" name="mawb_no" class="form-control-gf" value="{{ $airImport->mawb_no ?? '' }}" required>
<select name="office_id" class="form-control-gf" required>...</select>
<input type="date" name="post_date" class="form-control-gf" value="{{ $airImport->post_date ?? date('Y-m-d') }}">
<select name="carrier_id" class="form-control-gf">...</select>
<select name="oversea_agent_id" class="form-control-gf">...</select>
<select name="coloader_id" class="form-control-gf">...</select>
<input type="checkbox" name="is_direct_master" value="1" {{ (isset($airImport) && $airImport->is_direct_master) ? 'checked' : '' }}>
<select name="dep_port_id" class="form-control-gf">...</select>
<select name="dst_port_id" class="form-control-gf">...</select>
<input type="text" name="flight_no" class="form-control-gf" value="{{ $airImport->flight_no ?? '' }}">
<input type="date" name="etd" class="form-control-gf" value="{{ $airImport->etd ?? '' }}">
<input type="date" name="eta" class="form-control-gf" value="{{ $airImport->eta ?? '' }}" required>
<input type="date" name="atd" class="form-control-gf" value="{{ $airImport->atd ?? '' }}">
<input type="date" name="ata" class="form-control-gf" value="{{ $airImport->ata ?? '' }}">
<input type="number" name="pkg_qty" class="form-control-gf" value="{{ $airImport->pkg_qty ?? '' }}">
<select name="pkg_unit_id" class="form-control-gf">...</select>
<input type="number" name="gross_weight_kg" class="form-control-gf" value="{{ $airImport->gross_weight_kg ?? '' }}" step="0.01">
<input type="number" name="gross_weight_lb" class="form-control-gf" value="{{ $airImport->gross_weight_lb ?? '' }}" step="0.01">
<input type="number" name="chargeable_weight_kg" class="form-control-gf" value="{{ $airImport->chargeable_weight_kg ?? '' }}" step="0.01">
<input type="number" name="chargeable_weight_lb" class="form-control-gf" value="{{ $airImport->chargeable_weight_lb ?? '' }}" step="0.01">
<input type="number" name="volume_weight_kg" class="form-control-gf" value="{{ $airImport->volume_weight_kg ?? '' }}" step="0.01">
<input type="number" name="volume_cbm" class="form-control-gf" value="{{ $airImport->volume_cbm ?? '' }}" step="0.01">
<select name="freight_term" class="form-control-gf">...</select>
<select name="incoterm_id" class="form-control-gf">...</select>
<select name="svc_term_from_id" class="form-control-gf">...</select>
<select name="svc_term_to_id" class="form-control-gf">...</select>
<select name="referred_by_id" class="form-control-gf">...</select>
<input type="checkbox" name="stackable" value="1" {{ (isset($airImport) && $airImport->stackable) ? 'checked' : '' }}>
<select name="cargo_type" class="form-control-gf">...</select>
<input type="checkbox" name="is_ecommerce" value="1" {{ (isset($airImport) && $airImport->is_ecommerce) ? 'checked' : '' }}>
```

---

### Phase 4: Frontend Validation

#### Add HTML5 validation attributes:
```html
<!-- Required fields -->
<input type="text" name="mawb_no" class="form-control-gf" required>
<select name="office_id" class="form-control-gf" required>...</select>
<input type="date" name="eta" class="form-control-gf" required>

<!-- Visual indicators -->
<label class="form-label-gf" style="color:red;">*MAWB No.</label>
<label class="form-label-gf" style="color:red;">*Office</label>
<label class="form-label-gf" style="color:red;">*ETA</label>
```

#### Add JavaScript validation:
```javascript
document.getElementById('air-import-form').addEventListener('submit', function(e) {
    var mawbNo = document.querySelector('[name="mawb_no"]').value.trim();
    var officeId = document.querySelector('[name="office_id"]').value;
    var eta = document.querySelector('[name="eta"]').value;
    
    if (!mawbNo) {
        e.preventDefault();
        alert('MAWB Number is required');
        return false;
    }
    
    if (!officeId) {
        e.preventDefault();
        alert('Office is required');
        return false;
    }
    
    if (!eta) {
        e.preventDefault();
        alert('ETA is required');
        return false;
    }
    
    return true;
});
```

---

### Phase 5: Backend Validation (Request Classes)

**File**: `app/Http/Requests/StoreAirImportRequest.php`

```php
public function rules()
{
    return [
        // Required fields
        'mawb_no' => 'required|string|max:255',
        'office_id' => 'required|exists:offices,id',
        'eta' => 'required|date',
        
        // Optional with validation
        'file_no' => 'nullable|string|max:255',
        'post_date' => 'nullable|date',
        'carrier_id' => 'nullable|exists:trade_partners,id',
        'oversea_agent_id' => 'nullable|exists:trade_partners,id',
        'coloader_id' => 'nullable|exists:trade_partners,id',
        'op_id' => 'nullable|exists:users,id',
        'dep_port_id' => 'nullable|exists:ports,id',
        'dst_port_id' => 'nullable|exists:ports,id',
        'flight_no' => 'nullable|string|max:50',
        'etd' => 'nullable|date',
        'atd' => 'nullable|date',
        'ata' => 'nullable|date',
        'pkg_qty' => 'nullable|numeric|min:0',
        'pkg_unit_id' => 'nullable|exists:package_units,id',
        'gross_weight_kg' => 'nullable|numeric|min:0',
        'gross_weight_lb' => 'nullable|numeric|min:0',
        'chargeable_weight_kg' => 'nullable|numeric|min:0',
        'chargeable_weight_lb' => 'nullable|numeric|min:0',
        'volume_weight_kg' => 'nullable|numeric|min:0',
        'volume_cbm' => 'nullable|numeric|min:0',
        'freight_term' => 'nullable|string|max:50',
        'incoterm_id' => 'nullable|exists:incoterms,id',
        'svc_term_from_id' => 'nullable|exists:service_terms,id',
        'svc_term_to_id' => 'nullable|exists:service_terms,id',
        'referred_by_id' => 'nullable|exists:trade_partners,id',
        'cargo_type' => 'nullable|string|max:50',
        'is_direct_master' => 'nullable|boolean',
        'stackable' => 'nullable|boolean',
        'is_ecommerce' => 'nullable|boolean',
        
        // Direct Master fields (conditional)
        'dm_customer_id' => 'nullable|exists:trade_partners,id',
        'dm_shipper_id' => 'nullable|exists:trade_partners,id',
        'dm_consignee_id' => 'nullable|exists:trade_partners,id',
        'dm_notify_id' => 'nullable|exists:trade_partners,id',
        'dm_bill_to_id' => 'nullable|exists:trade_partners,id',
        'dm_sales_person_id' => 'nullable|exists:users,id',
    ];
}

public function messages()
{
    return [
        'mawb_no.required' => 'MAWB Number is required',
        'office_id.required' => 'Office is required',
        'eta.required' => 'ETA is required',
        'office_id.exists' => 'Selected office is invalid',
        'carrier_id.exists' => 'Selected carrier is invalid',
        // ... more custom messages
    ];
}
```

---

### Phase 6: CRUD Operations for Sub-Items

#### A. Containers Tab - Full CRUD via AJAX

**Routes** (already exist):
```php
Route::post('/air-import/{airImport}/containers', [AirImportController::class, 'addContainer']);
Route::put('/air-import/containers/{container}', [AirImportController::class, 'updateContainer']);
Route::delete('/air-import/containers/{container}', [AirImportController::class, 'deleteContainer']);
```

**JavaScript**:
```javascript
// Add container
addContainer() {
    if (!this.airImportId) {
        alert('Please save the shipment first');
        return;
    }
    
    fetch(`/air-import/${this.airImportId}/containers`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            container_no: this.newContainer.number,
            pp_ctf: this.newContainer.pp_ctf,
            container_type: this.newContainer.type,
            seal_no: this.newContainer.seal,
            seal_no2: this.newContainer.seal2,
            lfd: this.newContainer.lfd,
            fdd: this.newContainer.fdd,
            pkg_qty: this.newContainer.pkg,
            weight_kg: this.newContainer.weight,
            measure_cbm: this.newContainer.measure
        })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            this.loadContainers();
            this.resetNewContainer();
            showToast('success', 'Container added');
        }
    })
    .catch(e => showToast('error', 'Failed to add container'));
}
```

#### B. Charges Tab - Full CRUD via AJAX

**Routes** (already exist):
```php
Route::get('/air-import/{airImport}/charges', [AirImportController::class, 'getCharges']);
Route::post('/air-import/{airImport}/charges', [AirImportController::class, 'addCharge']);
Route::put('/air-import/charges/{charge}', [AirImportController::class, 'updateCharge']);
Route::delete('/air-import/charges/{charge}', [AirImportController::class, 'deleteCharge']);
Route::delete('/air-import/{airImport}/charges/all', [AirImportController::class, 'deleteAllCharges']);
```

**JavaScript**: Similar pattern to containers

#### C. HBLs - Full CRUD via AJAX

**Need to add routes**:
```php
Route::get('/air-import/{airImport}/hbls', [AirImportController::class, 'getHbls']);
Route::post('/air-import/{airImport}/hbls', [AirImportController::class, 'addHbl']);
Route::put('/air-import/hbls/{hbl}', [AirImportController::class, 'updateHbl']);
Route::delete('/air-import/hbls/{hbl}', [AirImportController::class, 'deleteHbl']);
```

**Need to add controller methods**

#### D. Documents - Full CRUD via AJAX

**Routes** (already exist):
```php
Route::post('/air-import/{airImport}/documents', [AirImportController::class, 'uploadDocument']);
Route::delete('/air-import/documents/{document}', [AirImportController::class, 'deleteDocument']);
Route::get('/air-import/documents/{document}/download', [AirImportController::class, 'downloadDocument']);
```

#### E. Filing Tab - AJAX Update

**Routes** (already exist):
```php
Route::post('/air-import/{airImport}/filing', [AirImportController::class, 'updateFiling']);
```

#### F. History/Status Logs - View Only

**Routes** (already exist):
```php
Route::get('/air-import/{airImport}/history', [AirImportController::class, 'getHistory']);
```

---

### Phase 7: Database Schema Verification

Check `air_imports` table has all columns:
```sql
- id
- file_no
- mawb_no
- office_id
- post_date
- carrier_id
- oversea_agent_id
- coloader_id
- op_id
- dep_port_id
- dst_port_id
- flight_no
- etd, eta, atd, ata
- pkg_qty, pkg_unit_id
- gross_weight_kg, gross_weight_lb
- chargeable_weight_kg, chargeable_weight_lb
- volume_weight_kg, volume_cbm
- freight_term
- incoterm_id
- svc_term_from_id, svc_term_to_id
- referred_by_id
- cargo_type
- is_direct_master
- stackable
- is_ecommerce
- dm_customer_id, dm_shipper_id, dm_consignee_id
- dm_notify_id, dm_bill_to_id, dm_sales_person_id
- created_at, updated_at, deleted_at
```

If columns missing, create migration.

---

## Estimated Files to Modify

1. ✅ `app/Http/Controllers/AirImportController.php` - Fix create/edit methods
2. ✅ `resources/views/air-import/index.blade.php` - Complete rewrite
3. ✅ `app/Http/Requests/StoreAirImportRequest.php` - Add validation rules
4. ✅ `app/Http/Requests/UpdateAirImportRequest.php` - Add validation rules
5. ✅ `routes/web.php` - Add missing HBL routes if needed
6. ✅ `app/Models/AirImport.php` - Verify fillable/relationships
7. ✅ Migration file (if columns missing)

---

## Priority Order

1. **HIGH**: Fix controller data loading (Phase 1)
2. **HIGH**: Make all selects dynamic (Phase 2)
3. **HIGH**: Add proper form structure & name attributes (Phase 3)
4. **MEDIUM**: Add frontend validation (Phase 4)
5. **MEDIUM**: Add backend validation (Phase 5)
6. **MEDIUM**: Implement CRUD for containers (Phase 6A)
7. **LOW**: Implement CRUD for charges (Phase 6B)
8. **LOW**: Implement CRUD for HBLs (Phase 6C)
9. **LOW**: Implement document upload (Phase 6D)
10. **LOW**: Verify database schema (Phase 7)

---

## Testing Checklist

- [ ] Page loads without errors
- [ ] All dropdowns populated with data
- [ ] Required fields show validation
- [ ] Form submits and creates record
- [ ] Edit page loads existing data
- [ ] Update works correctly
- [ ] Container CRUD works
- [ ] Charges CRUD works
- [ ] HBL CRUD works
- [ ] Document upload works
- [ ] Filing tab saves
- [ ] History tab shows logs
- [ ] No SQL errors
- [ ] No validation errors
- [ ] No JavaScript errors

---

Ready to implement? This is a LARGE task requiring 500+ lines of code changes across multiple files. Confirm to proceed step by step.
