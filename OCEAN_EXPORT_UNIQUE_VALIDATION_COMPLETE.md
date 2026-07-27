# Ocean Export - MBL & HBL Unique Validation Complete ✅

## Date: 2026-07-27

## What Was Fixed

### Route: `http://localhost:8000/ocean-export/create`

Made **MBL No** and **HBL No** unique with user-friendly error messages for Ocean Export module.

---

## Changes Applied

### 1. Added Unique Constraints

#### MBL No - Now Unique ✅
**Before**: `'mbl_no' => 'nullable|string'`  
**After**: `'mbl_no' => 'nullable|string|unique:ocean_exports,mbl_no'`

- For create: Must be unique across all ocean exports
- For update: Can keep same MBL No or use a different unique one

#### HBL No - Now Unique ✅  
**Before**: `'hbls.*.hbl_no' => 'required_with:hbls|string'`  
**After**: `'hbls.*.hbl_no' => 'required_with:hbls|string|unique:ocean_export_hbls,hbl_no'`

- Each HBL number must be unique across all ocean export HBLs
- Multiple HBLs in same shipment must have different numbers

---

### 2. User-Friendly Error Messages

#### For MBL No Duplicates:
**Old Error**: `SQLSTATE[23000]: Integrity constraint violation...`  
**New Error**: ✅ `This record already exists. MBL No "MBL-001" is already used.`

#### For HBL No Duplicates:
**Old Error**: `SQLSTATE[23000]: Integrity constraint violation...`  
**New Error**: ✅ `One or more HBL numbers are already used. Each HBL No must be unique.`

#### For File No Duplicates:
**New Error**: ✅ `This File No is already used. Please enter a unique File No.`

---

### 3. Enhanced Exception Handling

Added comprehensive error handling in `OceanExportController`:

#### Store Method (Create)
- Catches duplicate entry errors for File No, MBL No, HBL No, Container No
- Catches foreign key constraint errors
- Catches general database errors
- Logs all errors for debugging

#### Update Method (Edit)
- Same error handling as store
- Properly excludes current record from unique check
- Graceful handling of FK constraint failures

---

## Files Modified

### 1. Request Validation Classes

**StoreOceanExportRequest.php**
- ✅ Added `unique` constraint to `mbl_no`
- ✅ Added `unique` constraint to `hbls.*.hbl_no`
- ✅ Added `messages()` method with 30+ custom error messages
- ✅ Added `attributes()` method for field name mapping

**UpdateOceanExportRequest.php**
- ✅ Added `unique` constraint to `mbl_no` (with ID exclusion)
- ✅ Added `messages()` method with 30+ custom error messages
- ✅ Added `attributes()` method for field name mapping

### 2. Controller

**OceanExportController.php**
- ✅ Enhanced `store()` method with try-catch blocks
- ✅ Enhanced `update()` method with try-catch blocks
- ✅ Detects specific duplicate fields (file_no, mbl_no, hbl_no, container_no)
- ✅ User-friendly error messages for all error types
- ✅ Logs errors to Laravel log for debugging

---

## Error Messages Reference

### Unique Constraint Errors

| Field | Error Message |
|-------|--------------|
| File No | "This File No is already used. Please enter a unique File No." |
| MBL No (Create) | "This MBL No is already used. Please enter a unique MBL No." |
| MBL No (Update) | "This MBL No is already used by another shipment. Please enter a unique MBL No." |
| HBL No | "One or more HBL numbers are already used. Each HBL No must be unique." |
| Container No | "One or more container numbers are already used." |

### Validation Errors

| Field | Error Message |
|-------|--------------|
| File No (Required) | "File No is required." |
| HBL No (Required) | "HBL No is required for each HBL." |
| Invalid Office | "The selected office does not exist." |
| Invalid Carrier | "The selected carrier does not exist." |
| Invalid Vessel | "The selected vessel does not exist." |
| Invalid Port | "The selected Port of Loading does not exist." |

### Database Errors

| Type | Error Message |
|------|--------------|
| Foreign Key Error | "Invalid reference: One or more related records do not exist. Please check your selections." |
| Generic DB Error | "Unable to save/update the shipment. Please check your data and try again." |
| General Error | "An unexpected error occurred. Please try again or contact support if the problem persists." |

---

## Testing Guide

### Test 1: Duplicate MBL No

**Steps:**
1. Go to `http://localhost:8000/ocean-export/create`
2. Create Ocean Export with MBL No: **TEST-MBL-001**
3. Try to create another with same MBL No: **TEST-MBL-001**

**Expected Result:**
```
✅ "This record already exists. MBL No "TEST-MBL-001" is already used."
❌ NOT: SQL error message
```

### Test 2: Duplicate HBL No

**Steps:**
1. Create Ocean Export with File No: **TEST-FILE-001**
2. Add HBL with HBL No: **TEST-HBL-001**
3. Save successfully
4. Create another Ocean Export
5. Add HBL with same HBL No: **TEST-HBL-001**

**Expected Result:**
```
✅ "One or more HBL numbers are already used. Each HBL No must be unique."
❌ NOT: SQL error message
```

### Test 3: Update with Same MBL No

**Steps:**
1. Open existing Ocean Export for edit
2. Keep the same MBL No unchanged
3. Save

**Expected Result:**
```
✅ Saves successfully (unique check excludes current record)
```

### Test 4: Update with Duplicate MBL No

**Steps:**
1. Create Ocean Export A with MBL No: **MBL-A**
2. Create Ocean Export B with MBL No: **MBL-B**
3. Edit Ocean Export B, change MBL No to: **MBL-A**
4. Try to save

**Expected Result:**
```
✅ "This record already exists. MBL No "MBL-A" is already used by another shipment."
```

---

## Database Schema

### ocean_exports table
- ✅ `mbl_no` - VARCHAR, nullable, **UNIQUE INDEX**
- ✅ `file_no` - VARCHAR, required, **UNIQUE INDEX**

### ocean_export_hbls table
- ✅ `hbl_no` - VARCHAR, required, **UNIQUE INDEX**

---

## Benefits

✅ **No Duplicate MBL Numbers** - System prevents duplicate MBL entries  
✅ **No Duplicate HBL Numbers** - Each HBL must have unique number  
✅ **User-Friendly Errors** - Clear messages instead of SQL errors  
✅ **Better Data Integrity** - Enforced at both application and validation layers  
✅ **Professional UX** - Users know exactly what's wrong and how to fix it  
✅ **Debugging Support** - Errors logged to Laravel log for developers  

---

## Validation Flow

```
Create Ocean Export
    ↓
Form Submission
    ↓
FormRequest Validation
    ├─ File No unique? ✓
    ├─ MBL No unique? ✓
    └─ HBL No unique? ✓
    ↓
Controller::store()
    ↓
Try to save
    ├─ Success → Redirect to edit page
    └─ Error → Catch and show user-friendly message
```

---

## Status: ✅ COMPLETE

All unique validation and error handling for Ocean Export is now complete and ready for testing!

### Quick Summary:
- ✅ MBL No is now unique
- ✅ HBL No is now unique  
- ✅ User-friendly error messages
- ✅ Exception handling in controller
- ✅ Custom validation messages
- ✅ Error logging for debugging

**Test the changes at**: `http://localhost:8000/ocean-export/create`
