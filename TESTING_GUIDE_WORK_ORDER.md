# Work Order Auto-Load Testing Guide

## What Was Fixed

1. **Duplicate init() methods merged** - JavaScript now executes work order tab logic
2. **Alpine.js error fixed** - HAWB commodities array properly initialized
3. **Auto-load on save** - Work orders load automatically after create/update
4. **Auto-load on tab switch** - Work orders load when manually switching to Work Order tab

## Testing Steps

### Test 1: Create Work Order (Auto-Load on Save)

1. Navigate to an existing Air Export shipment (e.g., `/air-export/1/edit`)
2. Click the **"Work Order"** tab
3. Click **"New Work Order"** button
4. Fill in the work order form
5. Click **"SAVE"** button

**Expected Result:**
- ✅ Redirects back to Air Export page
- ✅ Work Order tab is ALREADY OPEN (not Basic tab)
- ✅ Work order list shows the new work order WITHOUT clicking refresh
- ✅ Success toast appears: "Work order created successfully"
- ✅ NO console errors

### Test 2: Edit Work Order (Auto-Load on Update)

1. From the Work Order tab, click **"Edit"** button on any work order
2. Make some changes
3. Click **"SAVE"** button

**Expected Result:**
- ✅ Redirects back to Air Export page
- ✅ Work Order tab is ALREADY OPEN
- ✅ Updated work order appears in list WITHOUT clicking refresh
- ✅ Success toast appears: "Work order updated successfully"
- ✅ NO console errors

### Test 3: Manual Tab Switch

1. Navigate to an existing Air Export shipment
2. You're on the **"Basic"** tab by default
3. Click the **"Work Order"** tab

**Expected Result:**
- ✅ Work orders load automatically
- ✅ Table shows all work orders for this shipment
- ✅ NO need to click refresh button
- ✅ NO console errors about `hawb.commodities.length`

### Test 4: Direct URL with Tab Parameter

1. Open browser console (F12)
2. Navigate to: `/air-export/{id}/edit?tab=workorder` (replace {id} with actual shipment ID)

**Expected Result:**
- ✅ Page loads with Work Order tab ALREADY OPEN
- ✅ Work orders load automatically
- ✅ NO console errors

### Test 5: HAWB Commodities (No Alpine.js Error)

1. Navigate to any Air Export shipment
2. Click the **"HAWB"** tab
3. Open browser console (F12)
4. Look at the commodities table in any HAWB section

**Expected Result:**
- ✅ NO error: "Cannot read properties of undefined (reading 'length')"
- ✅ Table shows "No commodities added." if empty
- ✅ Table shows commodities if any exist

### Test 6: Refresh Button Still Works

1. Navigate to Work Order tab
2. Click the **manual refresh button** (🔄)

**Expected Result:**
- ✅ Work orders reload
- ✅ Toast message: "Refreshing work orders..."
- ✅ Button still functional (not removed)

## Console Verification

Open browser console (F12) and check:

### Should SEE:
- ✅ No errors
- ✅ No warnings about Alpine.js expressions

### Should NOT SEE:
- ❌ "Alpine Expression Error: Cannot read properties of undefined (reading 'length')"
- ❌ "Uncaught TypeError: Cannot read properties of undefined"
- ❌ Any JavaScript errors related to init() or activeTab

## Network Tab Verification

1. Open Network tab in browser console (F12)
2. Switch to Work Order tab
3. Look for API call

**Expected Result:**
- ✅ You should see: `GET /api/work-orders?workable_type=App\Models\AirExport&workable_id={id}`
- ✅ Response should be 200 OK
- ✅ Response should contain array of work orders

## All Tests Passed?

If all tests pass:
- ✅ Work orders auto-load on save
- ✅ Work orders auto-load on tab switch
- ✅ No Alpine.js errors
- ✅ No manual refresh needed
- ✅ Refresh button still available

**Status: FIXED 100% ✅**

## If Something Doesn't Work

1. Clear browser cache (Ctrl+Shift+Delete)
2. Hard refresh (Ctrl+F5)
3. Check browser console for errors
4. Verify you're on the latest code version
5. Check Laravel logs: `storage/logs/laravel.log`

## Files Modified (For Reference)

1. `resources/views/air-export/create.blade.php`
   - Merged duplicate init() methods
   - Added work order tab auto-load logic
   - Fixed HAWB commodities initialization

2. `app/Http/Controllers/WorkOrderController.php`
   - Already had correct redirect logic with ?tab=workorder parameter

3. `resources/views/ocean-export/work-order-form.blade.php`
   - Already had correct source parameter hidden fields
