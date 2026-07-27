# Work Order Polymorphic Relationship Fix

## Issue
When creating or editing work orders for Air Export, getting error:
```
Class "air_export" not found
```

## Root Cause
Laravel's `morphTo()` relationship expects the `workable_type` column to contain the **full class name** (e.g., `App\Models\AirExport`), but the code was sending just `"air_export"` (snake_case string).

## Error Location
- **File**: `app/Models/WorkOrder.php`
- **Line**: 67
- **Method**: `workable()` relationship
```php
public function workable()
{
    return $this->morphTo();  // Line 67 - tries to instantiate class from workable_type
}
```

## Solution Applied

### 1. Updated JavaScript - Create Work Order
**File**: `resources/views/air-export/create.blade.php`

**Before**:
```javascript
const url = `/ocean-export/work-order/create?` +
           `workable_type=air_export&` +  // ❌ Wrong format
           `workable_id=${shipmentId}`;
```

**After**:
```javascript
const url = `/ocean-export/work-order/create?` +
           `workable_type=App\\Models\\AirExport&` +  // ✅ Full class name
           `workable_id=${shipmentId}`;
```

**Note**: We use `App\\Models\\AirExport` (double backslash) because:
- Single `\` would be interpreted as escape character in JavaScript
- Double `\\` produces actual backslash in the URL
- URL encoding converts it to `App%5CModels%5CAirExport`
- Laravel decodes it back to `App\Models\AirExport`

### 2. Updated JavaScript - Fetch Work Orders
**File**: `resources/views/air-export/create.blade.php`

**Before**:
```javascript
const response = await fetch(`/api/work-orders?workable_type=air_export&workable_id=${shipmentId}`);
```

**After**:
```javascript
const response = await fetch(`/api/work-orders?workable_type=App\\Models\\AirExport&workable_id=${shipmentId}`);
```

## How Polymorphic Relationships Work

### Database Structure
```
work_orders table:
┌────┬────────────────────────────┬──────────────┐
│ id │ workable_type              │ workable_id  │
├────┼────────────────────────────┼──────────────┤
│ 1  │ App\Models\AirExport       │ 4            │ ✅ Correct
│ 2  │ App\Models\OceanExport     │ 10           │ ✅ Correct
│ 3  │ air_export                 │ 5            │ ❌ Wrong - causes error
└────┴────────────────────────────┴──────────────┘
```

### Laravel's morphTo() Behavior
```php
// When you call: $workOrder->workable
// Laravel does:

1. Read workable_type: "App\Models\AirExport"
2. Read workable_id: 4
3. Instantiate class: new App\Models\AirExport()
4. Query: AirExport::find(4)
5. Return: The related model instance
```

**If workable_type = "air_export":**
```php
1. Read workable_type: "air_export"
2. Try to instantiate: new air_export()  // ❌ Class doesn't exist!
3. Error: "Class 'air_export' not found"
```

## URL Encoding Explanation

### How the URL is Built and Decoded

**Step 1 - JavaScript builds URL:**
```javascript
`workable_type=App\\Models\\AirExport`
// Double backslash in JS string = single backslash in output
```

**Step 2 - Browser encodes URL:**
```
workable_type=App%5CModels%5CAirExport
// %5C is URL-encoded backslash
```

**Step 3 - Laravel decodes:**
```php
$request->query('workable_type')  
// Returns: "App\Models\AirExport"
```

**Step 4 - Saved to database:**
```sql
INSERT INTO work_orders (workable_type) VALUES ('App\Models\AirExport')
```

**Step 5 - Retrieved and used:**
```php
$workOrder->workable  
// Laravel: "Ah, I need to load App\Models\AirExport with ID X"
// Works perfectly! ✅
```

## Testing the Fix

### Test 1: Create Work Order
```bash
# URL should look like:
http://localhost:8000/ocean-export/work-order/create?
  workable_type=App%5CModels%5CAirExport&
  workable_id=4&
  mbl_no=MAE-001&
  file_no=MAE-001

# Database should save:
workable_type: "App\Models\AirExport"
workable_id: 4
```

### Test 2: Fetch Work Orders
```bash
# API call should be:
GET /api/work-orders?
  workable_type=App%5CModels%5CAirExport&
  workable_id=4

# Query should filter:
WHERE workable_type = 'App\Models\AirExport'
  AND workable_id = 4
```

### Test 3: Edit Work Order
```bash
# Edit URL:
http://localhost:8000/ocean-export/work-order/1/edit

# Controller loads:
$workOrder = WorkOrder::find(1);
$workable = $workOrder->workable;  // ✅ Should work now

# Queries executed:
# 1. SELECT * FROM work_orders WHERE id = 1
# 2. SELECT * FROM air_exports WHERE id = 4  (if workable_id = 4)
```

## Other Modules Using Same System

This fix applies to **all modules** that use work orders:

| Module | Correct workable_type |
|--------|----------------------|
| Air Export | `App\Models\AirExport` |
| Air Import | `App\Models\AirImport` |
| Ocean Export | `App\Models\OceanExport` |
| Ocean Import | `App\Models\OceanImport` |

**Note**: Ocean Export already uses the correct format, which is why it works.

## Alternative Solution (If Needed)

If you want to use snake_case strings like "air_export", you need to add a **morph map**:

### Add to AppServiceProvider.php

**File**: `app/Providers/AppServiceProvider.php`

```php
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\{AirExport, AirImport, OceanExport, OceanImport};

public function boot()
{
    Relation::enforceMorphMap([
        'air_export' => AirExport::class,
        'air_import' => AirImport::class,
        'ocean_export' => OceanExport::class,
        'ocean_import' => OceanImport::class,
    ]);
}
```

**Then you can use:**
```javascript
workable_type=air_export  // Will be mapped to App\Models\AirExport
```

**However**, I recommend **not using this approach** because:
1. Requires additional configuration
2. Another place to maintain
3. Full class names are more explicit and clear
4. No magic mappings to remember

## Database Migration (If You Have Existing Records)

If you already have work orders with wrong `workable_type` values:

```sql
-- Fix Air Export work orders
UPDATE work_orders 
SET workable_type = 'App\\Models\\AirExport' 
WHERE workable_type = 'air_export';

-- Fix Air Import work orders
UPDATE work_orders 
SET workable_type = 'App\\Models\\AirImport' 
WHERE workable_type = 'air_import';

-- Fix Ocean Export work orders (if any are wrong)
UPDATE work_orders 
SET workable_type = 'App\\Models\\OceanExport' 
WHERE workable_type = 'ocean_export';

-- Fix Ocean Import work orders (if any are wrong)
UPDATE work_orders 
SET workable_type = 'App\\Models\\OceanImport' 
WHERE workable_type = 'ocean_import';
```

## Verification Checklist

After applying the fix:

- [ ] Create work order from Air Export → Check database `workable_type`
- [ ] Edit work order → No error "Class not found"
- [ ] Fetch work orders → Returns correct records
- [ ] Delete work order → Works correctly
- [ ] Create work order from Ocean Export → Still works (regression test)
- [ ] Check browser console → No JavaScript errors
- [ ] Check Laravel logs → No PHP errors

## Summary

✅ **Fixed**: JavaScript now sends `App\\Models\\AirExport`  
✅ **Database**: Stores `App\Models\AirExport`  
✅ **Laravel**: Can instantiate class successfully  
✅ **Relationship**: `$workOrder->workable` works  

**Status**: ✅ FIXED

---

**Fixed Date**: January 27, 2026  
**Issue**: Polymorphic relationship class resolution  
**Solution**: Use full class names in workable_type
