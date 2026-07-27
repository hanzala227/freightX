# Work Order Tab - Quick Test Guide

## Pre-requisites
- Air Export shipment must be saved (have an ID)
- Navigate to: `http://localhost:8000/air-export/{id}/edit`
- Click on **"Work Order"** tab

---

## Test 1: Tab Load & Empty State

**Steps:**
1. Open an Air Export shipment (edit page)
2. Click "Work Order" tab

**Expected Results:**
- ✅ Tab loads without JavaScript errors
- ✅ Loading spinner appears briefly
- ✅ If no work orders: Empty state shows with inbox icon
- ✅ Message: "No work orders found. Click 'New Work Order' to create one."
- ✅ "New Work Order" button is visible and enabled

---

## Test 2: Create Work Order

**Steps:**
1. Click "New Work Order" button

**Expected Results:**
- ✅ New browser tab opens
- ✅ URL contains: `/ocean-export/work-order/create?workable_type=air_export&workable_id=X&mbl_no=...&file_no=...`
- ✅ Work order form displays
- ✅ Form shows shipment reference data
- ✅ Can fill out form and save

**After Saving:**
1. Close work order tab
2. Return to Air Export tab
3. Wait 2-3 seconds OR click refresh button

**Expected Results:**
- ✅ New work order appears in table
- ✅ W/O number is clickable link
- ✅ All data displayed correctly (subject, pickup, delivery, vendor, dates)

---

## Test 3: Edit Work Order

**Steps:**
1. Find a work order in the table
2. Click blue **Edit** button [✎]

**Expected Results:**
- ✅ New tab opens with work order edit form
- ✅ URL contains: `/ocean-export/work-order/{id}/edit?source=air_export&source_id=X`
- ✅ Form is pre-filled with existing data
- ✅ Can modify fields

**After Saving:**
1. Make a change (e.g., update subject)
2. Save work order
3. Close work order tab
4. Return to Air Export tab
5. Wait 2-3 seconds OR click refresh button

**Expected Results:**
- ✅ Changes reflected in table
- ✅ Updated data shows correctly
- ✅ "Last Modified" timestamp updated

---

## Test 4: Delete Single Work Order

**Steps:**
1. Find a work order in the table
2. Click red **Delete** button [🗑]

**Expected Results:**
- ✅ Confirmation dialog appears: "Are you sure you want to delete this work order?"
- ✅ Can cancel (work order remains)
- ✅ Can confirm (work order deleted)

**After Confirming:**
- ✅ Success toast: "Work order deleted successfully"
- ✅ Work order immediately removed from table
- ✅ NO page refresh occurs
- ✅ Other work orders remain in table

---

## Test 5: Select Work Orders

**Steps:**
1. Click checkbox next to a work order

**Expected Results:**
- ✅ Checkbox becomes checked
- ✅ Row highlights (light blue background)
- ✅ Counter updates: "X work order(s) | 1 selected"
- ✅ "Delete Selected" button becomes enabled

**Steps (continued):**
2. Check 2-3 more work orders

**Expected Results:**
- ✅ All checked rows highlighted
- ✅ Counter shows correct count: "X work order(s) | 3 selected"
- ✅ "Delete Selected" button remains enabled

---

## Test 6: Select All / Deselect All

**Steps:**
1. Click checkbox in table header (next to "W/O No.")

**Expected Results:**
- ✅ ALL work orders selected
- ✅ ALL rows highlighted
- ✅ Counter: "X work order(s) | X selected"
- ✅ Header checkbox is checked

**Steps (continued):**
2. Click header checkbox again

**Expected Results:**
- ✅ ALL work orders deselected
- ✅ NO rows highlighted
- ✅ Counter: "X work order(s)" (no selected count)
- ✅ "Delete Selected" button disabled
- ✅ Header checkbox is unchecked

---

## Test 7: Bulk Delete Work Orders

**Steps:**
1. Select 2-3 work orders using checkboxes
2. Click "Delete Selected" button

**Expected Results:**
- ✅ Confirmation dialog: "Are you sure you want to delete X work order(s)?"
- ✅ Can cancel (work orders remain)
- ✅ Can confirm (work orders deleted)

**After Confirming:**
- ✅ Toast shows: "X work order(s) deleted successfully"
- ✅ If any fail: "Failed to delete X work order(s)"
- ✅ Deleted work orders removed from table
- ✅ NO page refresh occurs
- ✅ Selection cleared
- ✅ Counter resets
- ✅ "Delete Selected" button disabled again

---

## Test 8: Refresh Button

**Steps:**
1. Click refresh button (⟳ icon, top right of portlet)

**Expected Results:**
- ✅ Toast: "Refreshing work orders..."
- ✅ Loading spinner appears briefly
- ✅ Work order list refreshes
- ✅ Any new work orders appear
- ✅ NO page refresh occurs

---

## Test 9: Work Order Counter

**Check continuously during tests:**

**With No Selection:**
- ✅ Shows: "5 work order(s)" (example)

**With 2 Selected:**
- ✅ Shows: "5 work order(s) | 2 selected"

**With All Selected:**
- ✅ Shows: "5 work order(s) | 5 selected"

**After Deletion:**
- ✅ Count updates: "3 work order(s)" (if 2 deleted)

---

## Test 10: Clickable W/O Number

**Steps:**
1. Click on a work order number link (e.g., "WO-20260127001")

**Expected Results:**
- ✅ New tab opens
- ✅ Work order edit form displayed
- ✅ Same as clicking edit button

---

## Test 11: Validation - Unsaved Shipment

**Steps:**
1. Go to create page: `http://localhost:8000/air-export/create`
2. Try to click "Work Order" tab

**Expected Results:**
- ✅ Tab is disabled/greyed out
- ✅ Tooltip: "Save Basic tab first"
- ✅ Cannot click tab

**Alternative Test:**
1. If you can access the tab
2. Click "New Work Order" button

**Expected Results:**
- ✅ Error toast: "Please save the shipment first before creating work orders"
- ✅ No new tab opens

---

## Test 12: Empty State Design

**When No Work Orders Exist:**

**Visual Check:**
- ✅ Large inbox icon (📥) centered
- ✅ Icon is semi-transparent (opacity ~30%)
- ✅ Text: "No work orders found."
- ✅ Second line: "Click 'New Work Order' to create one."
- ✅ Text color is grey (#999)
- ✅ Centered in table
- ✅ Good spacing (40px padding)

---

## Test 13: Loading State

**Steps:**
1. Click "Work Order" tab
2. Observe during initial load

**Expected Results:**
- ✅ Spinner icon (⟳) visible
- ✅ Spinner is animated (spinning)
- ✅ Color: Teal (#32c5d2)
- ✅ Text: "Loading work orders..."
- ✅ Centered in area
- ✅ Disappears when data loaded

---

## Test 14: Row Highlighting

**Steps:**
1. Select a work order checkbox

**Expected Results:**
- ✅ Row background: Light blue (#f0f8ff)
- ✅ Highlight visible and clear
- ✅ Text remains readable

**Steps (continued):**
2. Deselect checkbox

**Expected Results:**
- ✅ Row returns to white background
- ✅ No visual artifacts

---

## Test 15: Button States

**"Delete Selected" Button:**
- ✅ Disabled when no selection (grey, 50% opacity)
- ✅ Enabled when items selected (normal appearance)
- ✅ Cursor changes (not-allowed vs pointer)

**"New Work Order" Button:**
- ✅ Always enabled on edit page
- ✅ Teal background (#32c5d2)
- ✅ White text
- ✅ Plus icon visible

---

## Browser Console Check

**Open DevTools (F12) → Console Tab**

**Should NOT see:**
- ❌ JavaScript errors (red text)
- ❌ Failed API requests (4xx/5xx errors)
- ❌ Warning messages about Alpine.js

**Should see:**
- ✅ Successful API calls: `GET /api/work-orders?...` → 200 OK
- ✅ Successful delete requests: `DELETE /ocean-export/work-order/X` → 200 OK

---

## Network Tab Check

**Open DevTools → Network Tab**

**When Tab Loads:**
- ✅ `GET /api/work-orders?workable_type=air_export&workable_id=X`
- ✅ Status: 200 OK
- ✅ Response: JSON array of work orders

**When Creating:**
- ✅ New tab navigates to work order create page

**When Deleting:**
- ✅ `DELETE /ocean-export/work-order/X`
- ✅ Status: 200 OK
- ✅ Then: `GET /api/work-orders?...` (refresh)

---

## Common Issues & Solutions

### Issue: Work orders not loading
**Check:**
- Shipment is saved (has ID)
- API endpoint exists: `/api/work-orders`
- No JavaScript errors in console
- Network request succeeds (200 OK)

### Issue: "New Work Order" does nothing
**Check:**
- Shipment is saved
- Popup blocker not blocking new tab
- Check browser console for errors

### Issue: Delete doesn't work
**Check:**
- CSRF token present: `<meta name="csrf-token" content="...">`
- User has permissions to delete
- Work order ID is valid

### Issue: Table shows old data
**Check:**
- Click refresh button manually
- Check if auto-refresh is working (2-second delay)
- Verify API returns updated data

### Issue: Checkboxes don't work
**Check:**
- Alpine.js is loaded
- No JavaScript errors
- `selectedWorkOrders` array is initialized

---

## Success Criteria

✅ **All tests pass**  
✅ **No JavaScript errors**  
✅ **No page refreshes during operations**  
✅ **All AJAX calls succeed**  
✅ **UI updates dynamically**  
✅ **Toast notifications appear**  
✅ **Loading states work**  
✅ **Empty states display correctly**  
✅ **Selection works properly**  
✅ **Bulk operations function**

---

## Test Results Template

```
Date: _____________
Tester: _____________
Browser: _____________
Air Export ID: _____________

[ ] Test 1: Tab Load & Empty State - PASS / FAIL
[ ] Test 2: Create Work Order - PASS / FAIL
[ ] Test 3: Edit Work Order - PASS / FAIL
[ ] Test 4: Delete Single Work Order - PASS / FAIL
[ ] Test 5: Select Work Orders - PASS / FAIL
[ ] Test 6: Select All / Deselect All - PASS / FAIL
[ ] Test 7: Bulk Delete Work Orders - PASS / FAIL
[ ] Test 8: Refresh Button - PASS / FAIL
[ ] Test 9: Work Order Counter - PASS / FAIL
[ ] Test 10: Clickable W/O Number - PASS / FAIL
[ ] Test 11: Validation - Unsaved Shipment - PASS / FAIL
[ ] Test 12: Empty State Design - PASS / FAIL
[ ] Test 13: Loading State - PASS / FAIL
[ ] Test 14: Row Highlighting - PASS / FAIL
[ ] Test 15: Button States - PASS / FAIL

Console Check: PASS / FAIL
Network Check: PASS / FAIL

Overall Status: PASS / FAIL
Issues Found: _________________________________
```

---

**If all tests pass: Work Order tab is 100% functional!** ✅
