# Ocean Import Validation Fix - Complete

## Issue
Multiple database constraint violation errors when saving Ocean Import containers:
1. ~~`Column 'pkg_qty' cannot be null`~~ (FIXED)
2. ~~`Column 'chassis_days' cannot be null`~~ (FIXED)

These occurred because container numeric fields were being sent as `null`, but the database schema defines them with `->default(0)`, meaning they must be numeric values, not null.

## Root Cause
The `addContainer()` function in the frontend was initializing numeric fields as `null`:
```javascript
pkg_qty: null,
weight_kg: null,
weight_lb: null,
measure_cbm: null,
measure_cft: null,
chassis_days: null,  // ← Also causing errors
```

## Changes Made

### 1. Fixed Container Initialization (Line ~490)
**File**: `resources/views/ocean-import/index.blade.php`

Changed the `addContainer()` function to initialize ALL numeric fields with `0` instead of `null`:

```javascript
pkg_qty: 0,
weight_kg: 0,
weight_lb: 0,
measure_cbm: 0,
measure_cft: 0,
chassis_days: 0,  // ← Added this fix
```

### 2. Enhanced Form Validation (Line ~441)
**File**: `resources/views/ocean-import/index.blade.php`

Added sanitization logic in the `validateForm()` function to ensure all container numeric fields are valid numbers before form submission:

```javascript
// Sanitize container numeric fields to prevent null values
this.form.containers.forEach((container, idx) => {
    // Ensure numeric fields have valid numbers, not null
    if (container.pkg_qty === null || container.pkg_qty === '' || container.pkg_qty === undefined) {
        container.pkg_qty = 0;
    }
    if (container.weight_kg === null || container.weight_kg === '' || container.weight_kg === undefined) {
        container.weight_kg = 0;
    }
    if (container.weight_lb === null || container.weight_lb === '' || container.weight_lb === undefined) {
        container.weight_lb = 0;
    }
    if (container.measure_cbm === null || container.measure_cbm === '' || container.measure_cbm === undefined) {
        container.measure_cbm = 0;
    }
    if (container.measure_cft === null || container.measure_cft === '' || container.measure_cft === undefined) {
        container.measure_cft = 0;
    }
    if (container.chassis_days === null || container.chassis_days === '' || container.chassis_days === undefined) {
        container.chassis_days = 0;  // ← Added this sanitization
    }
});
```

### 3. Already Had Proper Handling
The `addBulkContainers()` function already had proper fallback logic with `|| 0` for imported containers, so no changes were needed there.

## Database Schema Reference

### From migration `2026_05_16_194358_create_ocean_import_containers_table.php`:
```php
$table->decimal('pkg_qty', 15, 2)->default(0);
$table->decimal('weight_kg', 15, 3)->default(0);
$table->decimal('weight_lb', 15, 3)->default(0);
$table->decimal('measure_cbm', 15, 3)->default(0);
$table->decimal('measure_cft', 15, 3)->default(0);
```

### From migration `2026_06_25_000001_add_container_status_fields_to_ocean_imports_table.php`:
```php
$table->decimal('chassis_days', 8, 1)->default(0);
```

All these fields are defined with `->default(0)`, which means they:
- **Cannot be NULL**
- **Must have a default value of 0**
- **Must always be numeric**

## Complete List of Non-Nullable Numeric Fields in Containers
Based on the model and migrations, these fields MUST be numeric (not null):
1. ✅ `pkg_qty` - Package quantity (default: 0)
2. ✅ `weight_kg` - Weight in KG (default: 0)
3. ✅ `weight_lb` - Weight in LB (default: 0)
4. ✅ `measure_cbm` - Measurement in CBM (default: 0)
5. ✅ `measure_cft` - Measurement in CFT (default: 0)
6. ✅ `chassis_days` - Chassis days (default: 0)

## Result
✅ New containers are initialized with `0` for all numeric fields
✅ Form validation sanitizes all container numeric fields before submission
✅ No more "Column 'pkg_qty' cannot be null" errors
✅ No more "Column 'chassis_days' cannot be null" errors
✅ Database integrity constraints are properly respected
✅ Users can save containers even if they leave numeric fields empty (they'll be saved as 0)
✅ User-friendly validation - no SQL errors shown to users

## Testing
Test these scenarios:
1. ✅ Create new Ocean Import → Add container → Leave all fields empty → Save
2. ✅ Edit Ocean Import → Add container → Enter only container number → Save
3. ✅ Import containers from Excel → Save
4. ✅ Duplicate existing containers → Save
5. ✅ Copy entire shipment with containers → Save

All should work without null constraint violations!
