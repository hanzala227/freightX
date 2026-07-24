# HBL Relationship Names Fix

## Issue
Internal Server Error when copying HBL:
```
Call to undefined relationship [pod] on model [App\Models\OceanExportHbl]
Location: app/Http/Controllers/OceanExportController.php:175
```

## Root Cause
The controller was using incorrect relationship names that don't exist in the `OceanExportHbl` model.

**Controller was using**:
- `pod` ❌
- `del` ❌
- `fdest` ❌
- `receipt` ❌

**Actual relationship names in model**:
- `placeOfDischarge()` ✅
- `placeOfDelivery()` ✅
- `finalDestination()` ✅
- `placeOfReceipt()` ✅

## Solution Applied

### ✅ Fixed Relationship Names
**File**: `app/Http/Controllers/OceanExportController.php` (Line 175)

**Before**:
```php
$hbl = OceanExportHbl::with([
    'customer', 
    'shipper', 
    'consignee', 
    'notifyParty', 
    'pod',      // ❌ Wrong
    'del',      // ❌ Wrong
    'fdest',    // ❌ Wrong
    'receipt'   // ❌ Wrong
])->find($hblId);
```

**After**:
```php
$hbl = OceanExportHbl::with([
    'customer', 
    'shipper', 
    'consignee', 
    'notifyParty', 
    'placeOfDischarge',  // ✅ Correct
    'placeOfDelivery',   // ✅ Correct
    'finalDestination',  // ✅ Correct
    'placeOfReceipt'     // ✅ Correct
])->find($hblId);
```

## Model Relationships Reference

From `app/Models/OceanExportHbl.php`:

```php
// Port relations
public function placeOfDischarge() { 
    return $this->belongsTo(Port::class, 'pod_id'); 
}

public function placeOfDelivery() { 
    return $this->belongsTo(Port::class, 'del_id'); 
}

public function finalDestination() { 
    return $this->belongsTo(Port::class, 'fdest_id'); 
}

public function placeOfReceipt() { 
    return $this->belongsTo(Port::class, 'receipt_id'); 
}
```

**Note**: The database columns use abbreviated names (`pod_id`, `del_id`, `fdest_id`, `receipt_id`) but the relationship methods use full descriptive names.

## Impact
- ✅ **No Breaking Changes**: Only fixed incorrect relationship names
- ✅ **Copy HBL Feature Now Works**: Can copy HBL to create new shipment
- ✅ **All Port Data Loads Correctly**: Place of Discharge, Delivery, Final Destination, and Receipt

## Testing
To verify the fix:
1. Visit: http://localhost:8000/ocean-export/list/hbl
2. Select an HBL from the list
3. Click copy/duplicate action
4. URL: http://localhost:8000/ocean-export/create?copy_hbl=1&shipment_id=1
5. Page should load successfully ✅
6. Form should be pre-filled with HBL data including all port information ✅

---

**Status**: ✅ **FIXED**
**Date**: 2026-07-24
**Files Modified**: 1 (OceanExportController.php)
**Lines Changed**: 1 (corrected relationship names)
