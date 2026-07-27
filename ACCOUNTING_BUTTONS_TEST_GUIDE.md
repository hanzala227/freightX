# Accounting Buttons Test Guide

## Quick Test Instructions

### Test 1: Unsaved Shipment Validation
**Route**: `http://localhost:8000/air-export/create`

1. Navigate to Air Export create page
2. Click on **Accounting** tab
3. Click any of the three buttons:
   - Origin Revenue (Invoice/AR)
   - Destination Revenue/Cost (D/C Note)
   - Origin Cost (AP)
4. **Expected**: Error toast message "Please save the shipment first before creating invoices"
5. **Expected**: No new tab opens
6. **Status**: ✅ Should prevent invoice creation for unsaved shipments

---

### Test 2: Saved Shipment - Origin Revenue (AR)
**Route**: `http://localhost:8000/air-export/{id}/edit` (replace {id} with actual shipment ID)

1. Navigate to existing Air Export shipment edit page
2. Click on **Accounting** tab
3. Click **"Origin Revenue (Invoice/AR)"** button
4. **Expected**: New tab opens with URL:
   ```
   /accounting/invoice/create?type=AR&shipment_type=air_export&shipment_id={id}
   ```
5. **Expected**: Invoice form opens with:
   - Invoice Type dropdown pre-selected to **"AR"** (Accounts Receivable)
   - Invoice No auto-generated (e.g., INV-260127120000)
6. **Expected**: Original shipment page remains open in background
7. **Status**: ✅ Should open AR invoice in new tab

---

### Test 3: Saved Shipment - Destination Revenue/Cost (DC)
**Route**: `http://localhost:8000/air-export/{id}/edit`

1. Click on **Accounting** tab
2. Click **"Destination Revenue/Cost (D/C Note)"** button
3. **Expected**: New tab opens with URL:
   ```
   /accounting/invoice/create?type=DC&shipment_type=air_export&shipment_id={id}
   ```
4. **Expected**: Invoice form opens with:
   - Invoice Type dropdown pre-selected to **"DC"** (Debit/Credit Note)
5. **Status**: ✅ Should open DC invoice in new tab

---

### Test 4: Saved Shipment - Origin Cost (AP)
**Route**: `http://localhost:8000/air-export/{id}/edit`

1. Click on **Accounting** tab
2. Click **"Origin Cost (AP)"** button
3. **Expected**: New tab opens with URL:
   ```
   /accounting/invoice/create?type=AP&shipment_type=air_export&shipment_id={id}
   ```
4. **Expected**: Invoice form opens with:
   - Invoice Type dropdown pre-selected to **"AP"** (Accounts Payable)
5. **Status**: ✅ Should open AP invoice in new tab

---

### Test 5: Form Submission Prevention
**Route**: `http://localhost:8000/air-export/{id}/edit`

1. Fill out some fields in the Main tab (e.g., change carrier or notes)
2. Click on **Accounting** tab
3. Click any of the three invoice buttons
4. **Expected**: Form does NOT submit
5. **Expected**: No "loading" spinner or page refresh
6. **Expected**: New tab opens for invoice creation
7. **Expected**: Return to original tab - all unsaved changes still present
8. **Status**: ✅ Should NOT trigger form submission

---

## Browser Console Test

Open browser DevTools (F12) and run:

```javascript
// Test the createInvoice function exists
console.log(typeof Alpine.raw(Alpine.$data(document.querySelector('[x-data]'))).createInvoice);
// Expected output: "function"

// Check if shipment ID is available (on edit page)
console.log(Alpine.raw(Alpine.$data(document.querySelector('[x-data]'))).form?.id);
// Expected output: shipment ID number (e.g., 4)
```

---

## Common Issues & Troubleshooting

### Issue 1: Buttons Submit Form
**Symptom**: Clicking button causes page refresh or form submission  
**Check**: Buttons should have `type="button"` and `@click.prevent`  
**Location**: Line 1042-1044 in `air-export/create.blade.php`

### Issue 2: Error "Please save shipment first"
**Symptom**: Error appears even on edit page  
**Check**: Verify `$airExport->id` is available in Blade template  
**Location**: Line 323 in `air-export/create.blade.php`

### Issue 3: Invoice Page Doesn't Pre-select Type
**Symptom**: Invoice form opens with default type instead of requested type  
**Check**: Verify `$defaultType` is passed to view in InvoiceController  
**Location**: Line 548 in `accounting/invoice-create.blade.php`

### Issue 4: New Tab Doesn't Open
**Symptom**: Nothing happens when clicking buttons  
**Check Browser Console**: Look for JavaScript errors  
**Check**: Alpine.js is loaded correctly  
**Location**: View page source, search for `x-data="airExportModule()"`

---

## Test Results Template

```
Date: _____________
Tester: _____________
Browser: _____________

[ ] Test 1: Unsaved Shipment Validation - PASS / FAIL
    Notes: _________________________________

[ ] Test 2: Origin Revenue (AR) - PASS / FAIL
    Notes: _________________________________

[ ] Test 3: Destination Revenue/Cost (DC) - PASS / FAIL
    Notes: _________________________________

[ ] Test 4: Origin Cost (AP) - PASS / FAIL
    Notes: _________________________________

[ ] Test 5: Form Submission Prevention - PASS / FAIL
    Notes: _________________________________

Overall Status: PASS / FAIL
Additional Comments: _________________________________
```

---

## Expected URLs for Each Button

| Button | Invoice Type | Expected URL |
|--------|--------------|--------------|
| Origin Revenue (Invoice/AR) | AR | `/accounting/invoice/create?type=AR&shipment_type=air_export&shipment_id={id}` |
| Destination Revenue/Cost (D/C Note) | DC | `/accounting/invoice/create?type=DC&shipment_type=air_export&shipment_id={id}` |
| Origin Cost (AP) | AP | `/accounting/invoice/create?type=AP&shipment_type=air_export&shipment_id={id}` |

---

## Success Criteria

✅ All buttons prevent form submission  
✅ Validation works for unsaved shipments  
✅ New tabs open with correct URLs  
✅ Invoice type is pre-populated correctly  
✅ Shipment context is passed via query parameters  
✅ No page refresh or navigation in original tab  
✅ User can complete workflow without losing data  

**If all criteria pass: Implementation is COMPLETE ✅**
