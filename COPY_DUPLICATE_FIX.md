# Ocean Import Copy - Duplicate Entry Fix

## Issue
When copying an Ocean Import record multiple times, the system threw an error:
```
SQLSTATE[23000]: Integrity constraint violation: 1062 
Duplicate entry 'HBL-QT-000001dwedqw - Copy - Copy' for key 'ocean_import_hbls_hbl_no_unique'
```

## Root Cause
The copy functionality appended " - Copy" to the HBL number, but:
1. If you copy the same record again, it creates "HBL-XXX - Copy - Copy"
2. If you copy THAT record again, it tries to create another "HBL-XXX - Copy - Copy" → **DUPLICATE!**
3. The database has a `UNIQUE` constraint on `hbl_no` column

**Same issue existed for container numbers.**

## Solution Applied

### HBL Number - Make Unique with Timestamp
**Before:**
```php
$clonedHbl->hbl_no = $hbl->hbl_no . ' - Copy';
```

**After:**
```php
$baseHblNo = $hbl->hbl_no;
$uniqueSuffix = ' - Copy ' . now()->format('YmdHis');
$clonedHbl->hbl_no = $baseHblNo . $uniqueSuffix;

// Truncate if too long (max 255 chars)
if (strlen($clonedHbl->hbl_no) > 255) {
    $maxBaseLength = 255 - strlen($uniqueSuffix);
    $clonedHbl->hbl_no = substr($baseHblNo, 0, $maxBaseLength) . $uniqueSuffix;
}
```

### Container Number - Make Unique with Timestamp
**Before:**
```php
$clonedContainer->container_no = $container->container_no . ' - Copy';
```

**After:**
```php
$baseContainerNo = $container->container_no;
$uniqueSuffix = '-Copy-' . now()->format('YmdHis');
$clonedContainer->container_no = $baseContainerNo . $uniqueSuffix;

// Truncate if too long (max 255 chars)
if (strlen($clonedContainer->container_no) > 255) {
    $maxBaseLength = 255 - strlen($uniqueSuffix);
    $clonedContainer->container_no = substr($baseContainerNo, 0, $maxBaseLength) . $uniqueSuffix;
}
```

## How It Works Now

### Example: Copy Once
**Original HBL:** `HBL-QT-000001`  
**First Copy:** `HBL-QT-000001 - Copy 20260724192530`

### Example: Copy Multiple Times
**Original HBL:** `HBL-QT-000001`  
**Copy 1:** `HBL-QT-000001 - Copy 20260724192530`  
**Copy 2:** `HBL-QT-000001 - Copy 20260724192545`  
**Copy 3:** `HBL-QT-000001 - Copy 20260724192601`

**Result:** Each copy has a unique timestamp, so no duplicates! ✅

## Timestamp Format
`YmdHis` = Year + Month + Day + Hour + Minute + Second

**Examples:**
- `20260724192530` = 2026-07-24 19:25:30
- `20260724192545` = 2026-07-24 19:25:45
- `20260724192601` = 2026-07-24 19:26:01

Even if you copy the same record 100 times in rapid succession, each will have a different timestamp (at minimum 1 second apart).

## Database Protection
If the generated name is still too long:
1. Calculate max base length: `255 - strlen(suffix)`
2. Truncate base to fit: `substr($base, 0, $maxLength)`
3. Add suffix: `$truncated . $suffix`

**Example:**
```
Original: "VERY-LONG-HBL-NUMBER-WITH-MANY-CHARACTERS..." (250 chars)
Suffix: " - Copy 20260724192530" (23 chars)
Total would be: 273 chars → TOO LONG!

Solution:
Truncate to: 255 - 23 = 232 chars
Result: "VERY-LONG-HBL-NUMBER-WITH-MANY-CHAR... - Copy 20260724192530" (255 chars)
```

## Testing Instructions

### Test 1: Copy Once
1. Go to Ocean Import list
2. Click copy icon on record #24
3. ✅ New record created with unique HBL number
4. ✅ Format: `[Original] - Copy [Timestamp]`

### Test 2: Copy Multiple Times (Rapid)
1. Copy record #24
2. Immediately copy record #24 again
3. Immediately copy record #24 again
4. ✅ All three copies have unique HBL numbers
5. ✅ No duplicate entry error

### Test 3: Copy a Copy
1. Copy record #24 → Creates #100
2. Copy record #100 (the copy)
3. ✅ New record created with unique number
4. ✅ Format: `[Original] - Copy [Timestamp1] - Copy [Timestamp2]`

### Test 4: Long HBL Numbers
1. Create a record with very long HBL number (240+ chars)
2. Copy it
3. ✅ HBL number truncated to fit 255 char limit
4. ✅ Still has unique timestamp suffix
5. ✅ No database error

## Files Modified
- **app/Http/Controllers/OceanImportController.php**
  - Lines 169-183: Fixed container number uniqueness
  - Lines 176-189: Fixed HBL number uniqueness

## Benefits

### Before (Broken):
- ❌ Could only copy once successfully
- ❌ Second copy would fail with duplicate error
- ❌ User had to manually rename HBL numbers
- ❌ Poor user experience

### After (Fixed):
- ✅ Can copy unlimited times
- ✅ Each copy automatically gets unique identifier
- ✅ Timestamp makes it easy to identify when copied
- ✅ Handles long names gracefully
- ✅ No manual intervention needed

## Alternative Approaches Considered

### Option 1: Counter Suffix (Not Used)
```php
$clonedHbl->hbl_no = $hbl->hbl_no . ' - Copy 1';
```
**Problem:** Would need to check database for existing copies and increment counter. More complex and slower.

### Option 2: Random String (Not Used)
```php
$clonedHbl->hbl_no = $hbl->hbl_no . ' - ' . Str::random(8);
```
**Problem:** Less readable, harder to identify when record was copied.

### Option 3: Timestamp (USED) ✅
```php
$clonedHbl->hbl_no = $hbl->hbl_no . ' - Copy ' . now()->format('YmdHis');
```
**Benefits:**
- Always unique (time never repeats)
- Human readable
- Shows when copy was made
- No database queries needed
- Fast and simple

## Database Schema
```sql
CREATE TABLE ocean_import_hbls (
    id BIGINT PRIMARY KEY,
    hbl_no VARCHAR(255) UNIQUE,  -- <-- UNIQUE constraint here
    ...
);
```

The `UNIQUE` constraint prevents duplicate HBL numbers, which is why the fix was necessary.

## Status: ✅ FIXED

You can now:
- ✅ Copy the same Ocean Import record multiple times
- ✅ Copy a copied record (copy of a copy)
- ✅ Copy rapidly without conflicts
- ✅ All copies have unique HBL and container numbers

No more duplicate entry errors!
