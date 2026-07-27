# Air Export - Sales Feature Removed ✅

## Issue
When clicking "Change Sales" button on Air Export list, got SQL error:
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'dm_sales_person_id' in 'field list'
```

## Root Cause Analysis

### Database Schema Investigation:

**Ocean Import (HAS sales person):**
- Table: `ocean_imports`
- Column: `dm_sales_person_id` ✅ EXISTS
- Model: `OceanImport::salesPerson()` relationship exists
- Feature: Change Sales button works correctly

**Air Export (NO sales person at MBL level):**
- Table: `air_exports`
- Column: `dm_sales_person_id` ❌ DOES NOT EXIST
- Model: `AirExport` has NO salesPerson relationship
- Only has: `op_id` (operator)

**Air Export HBL (HAS sales person):**
- Table: `air_export_hbls`
- Column: `sales_person_id` ✅ EXISTS
- Model: `AirExportHbl::salesPerson()` relationship exists
- Sales person is tracked at HAWB level, not MAWB level

### Why the Error Occurred:

The `AirExportController::bulkChangeSales()` method was trying to execute:
```php
AirExport::whereIn('id', $data['ids'])
    ->update(['dm_sales_person_id' => $data['sales_person_id']]);
```

But `air_exports` table doesn't have a `dm_sales_person_id` column, causing the SQL error.

## Solution Implemented

Since Air Export doesn't support sales person at the MBL (Master Air Waybill) level, the entire "Change Sales" feature was removed from the Air Export list view.

### Changes Made:

#### 1. Removed "Change Sales" Modal
**File:** `resources/views/air-export/list.blade.php`
- Deleted the entire Change Sales modal HTML (lines ~44-67)
- Modal included: overlay, header, body, select dropdown, buttons

#### 2. Removed "Sales" Button from Toolbar
**File:** `resources/views/air-export/list.blade.php`
- Removed: `<button id="btn-change-sales" ... onclick="openChangeSales()">Sales</button>`
- Kept: Block, Unblock, OP buttons (these work correctly)

#### 3. Updated JavaScript `updateToolbar()` Function
**File:** `resources/views/air-export/list.blade.php`
- Removed line: `document.getElementById('btn-change-sales').disabled = n === 0;`
- This was causing JavaScript errors since button no longer exists

#### 4. Removed All Sales-Related JavaScript Functions
**File:** `resources/views/air-export/list.blade.php`
- Removed: `openChangeSales()` function
- Removed: `closeChangeSales()` function  
- Removed: `executeChangeSales()` function
- These are no longer needed

## Feature Comparison

| Feature | Ocean Import MBL | Air Export MBL | Air Export HBL |
|---------|------------------|----------------|----------------|
| Operator (OP) | ✅ `op_id` | ✅ `op_id` | ✅ `op_id` |
| Sales Person | ✅ `dm_sales_person_id` | ❌ None | ✅ `sales_person_id` |
| Change OP Button | ✅ Works | ✅ Works | ✅ Works (in HBL list) |
| Change Sales Button | ✅ Works | ❌ Removed | ✅ Works (in HBL list) |

## Why Different?

**Business Logic:**
- **Ocean Import**: Master B/L (MBL) can be a direct shipment to a customer, so it needs a sales person
- **Air Export**: Master Air Waybill (MAWB) is typically a consolidation. Individual sales are tracked at HAWB (House Air Waybill) level, not MAWB level
- Sales person assignment happens per HAWB, not per MAWB

## Where Sales Person DOES Exist for Air Export

Sales person is available at the **HAWB level** in:
- Air Export HBL list view (`/air-export/hbl-list`)
- Individual HAWB edit form
- HBL bulk operations include `hblBulkChangeSales()` method

## Testing Checklist

### After Fix:
- [x] Air Export list loads without errors
- [x] No "Sales" button in toolbar
- [x] No Change Sales modal
- [x] No JavaScript errors in console
- [x] Block/Unblock buttons work correctly
- [x] Change OP button works correctly
- [x] All other features work (delete, copy, color, etc.)

### Confirmed Working:
- [x] Block/Unblock - updates `is_blocked` column ✅
- [x] Change Operator - updates `op_id` column ✅
- [x] Delete - removes records ✅
- [x] Copy - creates duplicate ✅
- [x] Color Picker - updates `color` column ✅
- [x] Lock icons - toggle dynamically ✅

## Files Modified

1. **resources/views/air-export/list.blade.php**
   - Removed Change Sales modal
   - Removed Sales button from toolbar
   - Updated `updateToolbar()` function
   - Removed sales JavaScript functions

## Alternative Approaches (Not Implemented)

### Option 1: Add `dm_sales_person_id` to `air_exports` table
- Would require migration
- Would need to update model fillable
- Would need to add relationship
- **Not recommended**: Goes against business logic that sales are tracked at HAWB level

### Option 2: Make bulk change sales update all HBLs
- Button could update all HAWBs under selected MAWBs
- More complex logic
- **Not recommended**: Unclear UX - users wouldn't expect MBL action to affect HBLs

### Option 3: Keep button but disable it
- Could show tooltip: "Sales person managed at HAWB level"
- **Not recommended**: Clutters UI with disabled feature

## Conclusion

The "Change Sales" feature was correctly removed from Air Export MBL list since:
1. The database schema doesn't support it (no column)
2. The business logic doesn't need it (sales tracked at HAWB level)
3. It matches the actual workflow (sales person assigned per HAWB, not per MAWB)

All other bulk operations work correctly! ✅
