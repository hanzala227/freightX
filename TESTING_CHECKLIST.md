# ✅ Work Order Testing Checklist

## 🔴 STEP 0: CRITICAL - FIX DATABASE FIRST!

```bash
./run_database_fix.sh
```

**OR** run this SQL manually:
```sql
UPDATE work_orders SET workable_type = 'App\\Models\\AirExport' WHERE workable_type = 'air_export';
```

---

## 📋 STEP 1: CREATE WORK ORDER TEST

### Actions:
- [ ] Open Air Export page at `/air-export/{id}/edit`
- [ ] **Important**: Save the shipment first if it's new (must have valid ID)
- [ ] Click on "Work Order" tab
- [ ] Click "Create Work Order" button

### Expected Results:
- [ ] ✅ New browser tab/window opens
- [ ] ✅ URL contains `workable_type=App%5CModels%5CAirExport`
- [ ] ✅ Form shows "Pickup & Delivery Order" title
- [ ] ✅ MAWB No field is pre-filled with shipment's MAWB
- [ ] ✅ File No field is pre-filled with shipment's File No
- [ ] ✅ Issue Date is pre-filled with today's date
- [ ] ✅ Work Order No is auto-generated

### Fill Form:
- [ ] Select a **Vendor/Trucker** from dropdown
- [ ] Verify Trucker Address auto-fills
- [ ] Set **Issue Date** and **Due Date**
- [ ] Fill **Empty Pickup Location**
- [ ] Fill **Freight Pickup Location**
- [ ] Enter **Total Packages** (e.g., 10)
- [ ] Select **Package Unit** (e.g., CARTON(S))
- [ ] Enter **Gross Weight** in KGS and LBS
- [ ] Add any **Instructions** in text area
- [ ] Optionally check "Do Not Break Down Pallet"

### Save:
- [ ] Click "SAVE & SYNC WORK ORDER" button at bottom

### Expected After Save:
- [ ] ✅ Success page appears with animated green checkmark
- [ ] ✅ Message: "Work Order Saved! Returning to shipment page..."
- [ ] ✅ Spinner animation shows
- [ ] ✅ After ~1.5 seconds, **parent tab becomes active** (focus switches)
- [ ] ✅ Parent page shows **Work Order tab** (not another tab)
- [ ] ✅ New work order appears in the table
- [ ] ✅ Child window **closes automatically**
- [ ] ✅ Success toast shows: "Work order created successfully"
- [ ] ✅ **NO page reload** in parent (check if page flickers - it shouldn't)

### Console Check (Press F12):
- [ ] ✅ NO "Class 'air_export' not found" error
- [ ] ✅ NO "Form responses must redirect" error
- [ ] ✅ NO Turbo errors
- [ ] ✅ Console shows: "Work Order Saved: { source: 'air_export', sourceId: '...', workOrderId: '...' }"
- [ ] ✅ Console shows: "Parent window found, switching tab and refreshing..."
- [ ] ✅ Console shows: "Parent updated successfully"

---

## 📋 STEP 2: EDIT WORK ORDER TEST

### Actions:
- [ ] In Work Order tab, find the work order you just created
- [ ] Click the **Edit (pencil) icon** on the work order row

### Expected Results:
- [ ] ✅ New browser tab/window opens
- [ ] ✅ URL contains `work-order/{id}/edit?source=air_export&source_id=...`
- [ ] ✅ Form loads with **all previous data**:
  - [ ] Work Order No matches
  - [ ] Vendor is selected correctly
  - [ ] Trucker Address shows
  - [ ] Dates are correct
  - [ ] Pickup locations are correct
  - [ ] Package counts are correct
  - [ ] Instructions text is there
  - [ ] Checkbox state is preserved

### Modify Data:
- [ ] Change the **Vendor/Trucker**
- [ ] Verify new Trucker Address auto-fills
- [ ] Change **Total Packages** (e.g., from 10 to 15)
- [ ] Add text to **Instructions**

### Save:
- [ ] Click "SAVE & SYNC WORK ORDER" button

### Expected After Save:
- [ ] ✅ Success page appears (same as create)
- [ ] ✅ Parent tab becomes active
- [ ] ✅ Work order list refreshes
- [ ] ✅ Modified values show in table (e.g., new trucker name)
- [ ] ✅ Child window closes
- [ ] ✅ Success toast shows: "Work order updated successfully"
- [ ] ✅ NO "Class 'air_export' not found" error (this was the main bug!)

### Special Check:
- [ ] Click Edit icon again to verify changes were saved to database
- [ ] ✅ Form shows your modified values (not old values)

---

## 📋 STEP 3: DELETE SINGLE WORK ORDER TEST

### Actions:
- [ ] In Work Order tab, find any work order
- [ ] Click the **Trash (delete) icon**

### Expected Results:
- [ ] ✅ Confirmation dialog appears: "Are you sure you want to delete this work order?"
- [ ] Click **OK**
- [ ] ✅ Work order disappears from table
- [ ] ✅ Success toast shows: "Work order deleted successfully"
- [ ] ✅ Table updates without page reload
- [ ] ✅ NO errors in console

---

## 📋 STEP 4: BULK DELETE TEST

### Actions:
- [ ] Create 2-3 test work orders (use STEP 1)
- [ ] In Work Order tab, check the **checkboxes** for 2+ work orders
- [ ] Click **"Delete Selected"** button at top of table

### Expected Results:
- [ ] ✅ Confirmation dialog shows count: "Are you sure you want to delete 2 work order(s)?"
- [ ] Click **OK**
- [ ] ✅ All selected work orders disappear
- [ ] ✅ Success toast shows: "2 work order(s) deleted successfully"
- [ ] ✅ Checkboxes are cleared
- [ ] ✅ Table updates without page reload

---

## 📋 STEP 5: DATA INTEGRITY TEST

### Actions:
- [ ] Create a new work order with ALL fields filled:
  - [ ] Work Order No
  - [ ] Vendor/Trucker
  - [ ] Issue Date, Due Date
  - [ ] Subject (default: "PICKUP & DELIVERY ORDER")
  - [ ] Carrier ID, Carrier Booking No
  - [ ] Place of Receipt, ETD
  - [ ] Vessel Info
  - [ ] Empty Pickup Location, Address, Ref, Date
  - [ ] Freight Pickup Location, Address, Ref, Date
  - [ ] Total Packages, Package Unit
  - [ ] Container Qty
  - [ ] Gross Weight KGS, LBS
  - [ ] Show Bill To (checkbox)
  - [ ] Bill To ID, Address, Ref
  - [ ] Instructions (long text)
  - [ ] Do Not Break Down Pallet (checkbox)
- [ ] Save work order
- [ ] Edit same work order

### Verify All Fields Saved:
- [ ] ✅ Work Order No is the same
- [ ] ✅ Vendor is selected
- [ ] ✅ Dates are correct
- [ ] ✅ Subject is correct
- [ ] ✅ All carrier info is there
- [ ] ✅ All pickup info is there
- [ ] ✅ All package info is there
- [ ] ✅ All weights are correct
- [ ] ✅ Bill To section is correct
- [ ] ✅ Instructions text is preserved
- [ ] ✅ Checkbox states are correct

---

## 📋 STEP 6: DATABASE VERIFICATION

### Check Database Directly:
```sql
SELECT id, work_order_no, workable_type, workable_id, vendor_id
FROM work_orders
WHERE workable_type LIKE '%AirExport%'
ORDER BY id DESC
LIMIT 10;
```

### Expected:
- [ ] ✅ `workable_type` column shows: `'App\Models\AirExport'`
- [ ] ✅ NOT: `'air_export'` (lowercase snake_case)
- [ ] ✅ `workable_id` matches your Air Export shipment ID
- [ ] ✅ `vendor_id` matches the selected vendor
- [ ] ✅ All other fields have correct data

---

## 📋 STEP 7: NETWORK TAB CHECK (Developer Tools)

### Actions:
- [ ] Open Developer Tools (F12)
- [ ] Go to **Network** tab
- [ ] Create a new work order
- [ ] Find the POST request to `/ocean-export/work-order`

### Verify Request Payload:
- [ ] ✅ `workable_type` = `"App\Models\AirExport"` (full class name)
- [ ] ✅ `workable_id` = (valid shipment ID)
- [ ] ✅ `source` = `"air_export"`
- [ ] ✅ `source_id` = (valid shipment ID)
- [ ] ✅ All form fields are in payload

### Verify Response:
- [ ] ✅ Status: 200 OK
- [ ] ✅ Response returns HTML (success page)
- [ ] ✅ Response headers include:
  - `X-Turbo-Visit-Control: disable`
  - `Turbo-Visit-Control: reload`

---

## 📋 STEP 8: CONSOLE LOG CHECK

### During Success Flow, Console Should Show:
```
Work Order Saved: {source: 'air_export', sourceId: '1', workOrderId: '...'}
Parent window found, switching tab and refreshing...
Alpine found in parent, updating...
Parent updated successfully
```

### Console Should NOT Show:
- ❌ "Class 'air_export' not found"
- ❌ "Form responses must redirect to another location"
- ❌ Any Turbo errors
- ❌ Any 500 Internal Server errors
- ❌ Any Alpine.js errors

---

## 📋 STEP 9: EDGE CASES TEST

### Test 1: Without Saving Shipment
- [ ] Create NEW Air Export (don't save)
- [ ] Try to click "Create Work Order"
- [ ] ✅ Toast shows: "Please save the shipment first before creating work orders"

### Test 2: Browser Blocks Window.close()
- [ ] Create work order
- [ ] If child window doesn't close automatically
- [ ] ✅ Page shows: "You can close this window now" with Close button
- [ ] Click button
- [ ] ✅ Window closes

### Test 3: Multiple Work Orders
- [ ] Create 5+ work orders for same shipment
- [ ] ✅ All appear in table
- [ ] ✅ List is scrollable
- [ ] ✅ Edit any one
- [ ] ✅ Delete any one
- [ ] ✅ Bulk delete 3+

### Test 4: Unique Work Order Number
- [ ] Edit work order
- [ ] Change Work Order No to match another existing one
- [ ] Try to save
- [ ] ✅ Validation error: "The work order no has already been taken"

---

## 🎯 FINAL CHECKLIST

After completing all tests above:

### Functional Requirements:
- [ ] ✅ Work orders can be created
- [ ] ✅ Work orders can be edited
- [ ] ✅ Work orders can be deleted (single)
- [ ] ✅ Work orders can be bulk deleted
- [ ] ✅ All form fields save correctly
- [ ] ✅ Parent tab updates without reload

### User Experience:
- [ ] ✅ No page reloads or flickers
- [ ] ✅ No loading screens
- [ ] ✅ Smooth transitions
- [ ] ✅ Success animations work
- [ ] ✅ Toast notifications appear
- [ ] ✅ Windows open/close smoothly

### Technical Requirements:
- [ ] ✅ No "Class not found" errors
- [ ] ✅ No Turbo errors
- [ ] ✅ No console errors
- [ ] ✅ Database has correct format (`App\Models\AirExport`)
- [ ] ✅ Network requests are successful
- [ ] ✅ Window.opener communication works

### Data Integrity:
- [ ] ✅ All form inputs save correctly
- [ ] ✅ Polymorphic relationship works
- [ ] ✅ Foreign keys are correct
- [ ] ✅ Validation rules work
- [ ] ✅ Data persists after edit

---

## ✅ SUCCESS CRITERIA

**If all checkboxes are ticked above, the Work Order feature is 100% functional!**

---

## 🐛 If Any Test Fails:

1. **Check browser console** (F12) for JavaScript errors
2. **Check Laravel logs** at `storage/logs/laravel.log`
3. **Verify database** with SQL query above
4. **Clear browser cache** and try again
5. **Review** `WORK_ORDER_COMPLETE_GUIDE.md` for troubleshooting
6. **Confirm** database fix was run correctly

---

## 📁 Reference Documents:

- `QUICK_START.md` - Quick instructions
- `WORK_ORDER_FIX_SUMMARY.md` - What was fixed and why
- `WORK_ORDER_COMPLETE_GUIDE.md` - Complete technical documentation
- `FIX_WORKORDER_DATABASE.sql` - SQL fix script
- `run_database_fix.sh` - Automated fix script

---

**Remember: Fix database first, then run all tests systematically!**
