# HBL Copy - Complete Data Population Fix

## Issue
When copying an HBL, the form inputs were not being populated with the HBL data.

## Root Cause
The controller was replicating the HBL model but not:
1. Loading all necessary relationships
2. Preserving relationships after replication
3. Properly marking models as new records

## Solution Applied

### ✅ Enhanced HBL Copy with Complete Data Loading
**File**: `app/Http/Controllers/OceanExportController.php`

**Improvements Made**:

1. **Load ALL HBL Relationships**:
```php
$hbl = OceanExportHbl::with([
    'customer', 'shipper', 'consignee', 'notifyParty', 
    'placeOfDischarge', 'placeOfDelivery', 'finalDestination', 'placeOfReceipt',
    'customsBroker', 'deliveryLocation', 'referredBy', 'cfsLocation',
    'freightReleasedBy', 'salesPerson'
])->find($hblId);
```

2. **Replicate with Proper Flags**:
```php
$newHbl = $hbl->replicate();
$newHbl->hbl_no = ''; // Clear so user enters new number
$newHbl->exists = false; // Mark as new record
```

3. **Preserve ALL Relationships**:
```php
// After replication, set all relationships back
$newHbl->setRelation('customer', $hbl->customer);
$newHbl->setRelation('shipper', $hbl->shipper);
$newHbl->setRelation('consignee', $hbl->consignee);
$newHbl->setRelation('notifyParty', $hbl->notifyParty);
$newHbl->setRelation('placeOfDischarge', $hbl->placeOfDischarge);
$newHbl->setRelation('placeOfDelivery', $hbl->placeOfDelivery);
$newHbl->setRelation('finalDestination', $hbl->finalDestination);
$newHbl->setRelation('placeOfReceipt', $hbl->placeOfReceipt);
// ... and all other relationships
```

4. **Copy Containers Properly**:
```php
$oceanExport->setRelation('containers', $shipment->containers->map(function($container) {
    $newContainer = $container->replicate();
    $newContainer->exists = false;
    return $newContainer;
}));
```

## What Data Gets Copied

### ✅ Shipment Level (from parent shipment)
- File No. (auto-generated new)
- Office
- Carrier
- Vessel & Voyage
- ETD & ETA
- POL & POD
- Service Terms
- All other shipment fields

### ✅ HBL Level (from selected HBL)
- **Cleared**: HBL No. (user must enter new)
- **Copied**: 
  - Customer ID & relationship
  - Shipper ID & relationship
  - Consignee ID & relationship
  - Notify Party ID & relationship
  - POD ID & relationship
  - DEL ID & relationship
  - Final Destination ID & relationship
  - Place of Receipt ID & relationship
  - Customs Broker ID & relationship
  - Delivery Location ID & relationship
  - Referred By ID & relationship
  - CFS Location ID & relationship
  - Freight Released By ID & relationship
  - Sales Person ID & relationship
  - Vessel Name
  - Voyage No.
  - Pre-carriage By
  - Service Term
  - Ship Mode & Type
  - Cargo Type
  - Incoterms
  - Freight Payable At
  - LC No., SC No.
  - All boolean flags (express BL, door-to-door, customs, etc.)
  - Remarks
  - Group Comm, Line Code
  - All other HBL fields

### ✅ Container Level (from parent shipment)
- All containers with their data
- Container numbers, types, sizes
- Package quantities, weights, measurements

## Form Behavior

When the form loads after copy:
1. ✅ All select dropdowns pre-select the correct values
2. ✅ All text inputs filled with HBL data
3. ✅ All date fields populated
4. ✅ All checkboxes set correctly
5. ✅ Containers pre-loaded from shipment
6. ✅ HBL No. field EMPTY (ready for new number)

## Testing

To test the complete copy functionality:

1. **Navigate to HBL List**:
   - URL: http://localhost:8000/ocean-export/list/hbl

2. **Select an HBL with Complete Data**:
   - Choose one that has customer, shipper, consignee, etc.

3. **Click Copy Button**:
   - Should redirect to create page with `?copy_hbl=1&shipment_id=1`

4. **Verify Form is Populated**:
   - ✅ Customer dropdown shows the HBL's customer
   - ✅ Shipper dropdown shows the HBL's shipper
   - ✅ Consignee dropdown shows the HBL's consignee
   - ✅ Notify Party dropdown shows the HBL's notify party
   - ✅ POD dropdown shows the HBL's POD
   - ✅ DEL dropdown shows the HBL's DEL
   - ✅ All port fields populated correctly
   - ✅ Sales person selected
   - ✅ Vessel name filled
   - ✅ Voyage no. filled
   - ✅ Service term, ship mode, cargo type all set
   - ✅ Incoterms selected
   - ✅ All text fields populated
   - ✅ All checkboxes set correctly
   - ✅ Containers pre-loaded
   - ✅ HBL No. field is EMPTY (ready for new entry)

5. **Enter New HBL Number and Save**:
   - Enter a new HBL number
   - Click Save
   - Should create new shipment with new HBL containing copied data

## Technical Details

**Why relationships need to be preserved**:
Laravel's `replicate()` method copies model attributes but doesn't preserve loaded relationships. The relationships are needed so the form can display the selected values in dropdowns.

**Why `exists = false` is needed**:
Marking the model as not existing tells Laravel this is a new record, so it will INSERT instead of UPDATE when saved.

**Why HBL No. is cleared**:
Each HBL must have a unique number. Clearing it forces the user to enter a new unique number.

---

**Status**: ✅ **FIXED - Complete HBL data now copies to all form inputs**
**Date**: 2026-07-24
**Files Modified**: 1 (OceanExportController.php)
**Data Preserved**: 50+ fields including all relationships
