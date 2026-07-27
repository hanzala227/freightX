# Air Import - Unique Validation Added ✅

## Issue
Air Import was allowing duplicate File Numbers and MAWB Numbers, which could cause data integrity issues.

## Solution Applied

### 1. Added Unique Validation for MAWB Number

**File:** `app/Http/Requests/StoreAirImportRequest.php`

**Before:**
```php
'mawb_no' => 'required|string|max:255',
```

**After:**
```php
'mawb_no' => 'required|string|max:255|unique:air_imports,mawb_no',
```

### 2. Added User-Friendly Error Messages

**File:** `app/Http/Requests/StoreAirImportRequest.php`

Added clear, user-friendly messages:
```php
'file_no.unique' => 'This file number already exists. Please use a unique file number.',
'mawb_no.unique' => 'This MAWB Number already exists. Please use a unique MAWB number.',
```

### 3. Updated UpdateAirImportRequest

**File:** `app/Http/Requests/UpdateAirImportRequest.php`

**Added:**
- Unique validation for MAWB with exclusion for current record:
  ```php
  'mawb_no' => 'nullable|string|unique:air_imports,mawb_no,' . $id,
  ```
- Added `messages()` method with user-friendly error messages

## Validation Rules Now Active

### Create (StoreAirImportRequest):
- ✅ **File No**: Required, must be unique
- ✅ **MAWB No**: Required, must be unique

### Update (UpdateAirImportRequest):
- ✅ **File No**: Required, must be unique (excludes current record)
- ✅ **MAWB No**: Optional, must be unique if provided (excludes current record)

## Testing

1. **Test Duplicate File No:**
   - Go to http://localhost:8000/air-import/create
   - Use an existing File No
   - Click Save
   - **Expected:** Error toast: "This file number already exists. Please use a unique file number."

2. **Test Duplicate MAWB No:**
   - Go to http://localhost:8000/air-import/create
   - Use an existing MAWB No
   - Click Save
   - **Expected:** Error toast: "This MAWB Number already exists. Please use a unique MAWB number."

3. **Test Update with Same Values:**
   - Edit an existing Air Import
   - Keep the same File No and MAWB No
   - Click Save
   - **Expected:** Success (validation excludes current record)

## Consistency with Other Modules

Now Air Import has the same validation pattern as:
- ✅ Ocean Export (file_no and mbl_no are unique)
- ✅ Ocean Import (file_no and mbl_no are unique)
- ✅ Air Import (file_no and mawb_no are unique)

## Files Modified

1. `app/Http/Requests/StoreAirImportRequest.php`
   - Added `unique:air_imports,mawb_no` to mawb_no validation
   - Updated messages() with clear error messages

2. `app/Http/Requests/UpdateAirImportRequest.php`
   - Added `unique:air_imports,mawb_no,{$id}` to mawb_no validation
   - Added messages() method with user-friendly errors

## Status: COMPLETE ✅

Air Import now prevents duplicate File Numbers and MAWB Numbers with clear, user-friendly error messages.
