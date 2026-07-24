# HBL List View - Fix Plan

**URL**: `http://localhost:8000/ocean-import/list/hbl`

**Task**: Apply all MBL fixes to HBL list view

---

## 🎯 Fixes to Apply (Same as MBL)

### 1. Filter System ✅
- Change from `data-col-idx` to `data-param`
- Use `applyFiltersTyping()` with debouncing (400ms)
- Update controller to accept `filter_*` parameters
- Return JSON for AJAX requests

### 2. Excel Download ✅
- Change from `<a href>` to `<button onclick="exportExcel()"`
- Use hidden iframe technique
- No hard refresh

### 3. Mobile Responsive ✅
- Add complete mobile CSS
- Reduce sticky columns on mobile (6 → 2 → 1)
- iOS momentum scrolling
- Touch-friendly targets (28px)

### 4. Lock Icons ✅
- Show database `is_hold` state
- Toggle without hard refresh
- Update backend then refresh grid via AJAX

### 5. JavaScript Pattern ✅
- Remove all `window.` prefixes
- No IIFE wrapper
- Use `var` instead of `const/let`
- Match main list pattern

### 6. AJAX Grid Updates ✅
- All operations refresh grid without page reload
- Block/Unblock refresh grid
- Delete refreshes grid
- Change Sales/OP refreshes grid

### 7. Report Buttons ✅
- Profit Report → Revenue/Cost Report
- Arrival Notice → Shipment edit page
- No 404 errors

### 8. Edit Icons ✅
- Add external link icons to File No column
- Add external link icons to HBL No column
- All open in new tabs

---

## 📋 HBL List Columns

Current columns in HBL list:
1. Checkbox (sticky)
2. Lock icon (sticky)
3. Flag icon (sticky)
4. File No. (sticky) - needs edit icon
5. Color (sticky)
6. HB/L No. (sticky) - needs edit icon
7. Latest Event
8. Journey
9. Latest Event Date
10. MB/L No.
11. Consignee
12. Package
13. Weight
14. Measurement
15. Hold
16. IT No.
17. OB/L
18. AR Balance
19. AP Balance
20. DC Balance

---

## 🔧 Files to Modify

### 1. Controller
**File**: `app/Http/Controllers/OceanImportController.php`
**Method**: `hblList()`
**Changes**:
- Add `filter_*` parameter support
- Add AJAX JSON response

### 2. View
**File**: `resources/views/ocean-import/hbl-list.blade.php`
**Changes**:
- Add mobile responsive CSS
- Fix filter inputs (data-param)
- Fix all JavaScript functions
- Add exportExcel() function
- Fix lock toggle
- Fix report buttons
- Add edit icons

### 3. Partial (New)
**File**: `resources/views/ocean-import/partials/hbl-list-rows.blade.php`
**Purpose**: AJAX grid updates
**Content**: Table rows HTML

---

## 🎨 Current Issues

Based on MBL experience, HBL list likely has:
1. ❌ Filter using `data-col-idx` (should be `data-param`)
2. ❌ Excel button is `<a href>` (should be button with iframe)
3. ❌ No mobile responsive CSS
4. ❌ Lock icons may not show DB state
5. ❌ JavaScript may have errors/IIFE
6. ❌ Hard refreshes on operations
7. ❌ Report buttons may 404
8. ❌ No edit icons in columns

---

## 📝 Implementation Steps

### Step 1: Update Controller (hblList method)
```php
// Add filter support
if ($request->filled('filter_file_no')) {
    $query->whereHas('oceanImport', fn($oq) => 
        $oq->where('file_no', 'like', "%{$request->filter_file_no}%"));
}
if ($request->filled('filter_hbl_no')) {
    $query->where('hbl_no', 'like', "%{$request->filter_hbl_no}%");
}
// ... more filters

// Add AJAX response
if ($request->ajax()) {
    return response()->json([
        'html' => partial view,
        'pagination' => ...,
        'first', 'last', 'total'
    ]);
}
```

### Step 2: Create Partial View
- Extract tbody content
- Add external link icons
- Include all columns

### Step 3: Update Main View
- Add mobile CSS (copy from MBL)
- Fix filter inputs (data-param)
- Fix JavaScript (remove window., IIFE)
- Add exportExcel() function
- Fix updateGrid() for JSON
- Fix toggleLock() to refresh grid
- Fix block/unblock to refresh grid
- Fix report buttons (revenue-cost)
- Add edit icons to columns

### Step 4: Test Everything
- Filter on typing
- Search on typing
- Excel without refresh
- Lock toggle without refresh
- Block/Unblock refresh grid
- Delete refreshes grid
- Change Sales/OP refreshes grid
- Report buttons work (no 404)
- Mobile scrolling smooth
- Edit icons present

---

## ⏱️ Estimated Time

- Controller update: 5 minutes
- Partial creation: 5 minutes
- View update: 15 minutes
- Testing: 5 minutes
- **Total**: ~30 minutes

---

## 🚀 Expected Result

After fixes:
- ✅ Zero JavaScript errors
- ✅ Zero hard refreshes
- ✅ Filter works on typing
- ✅ Lock toggle without reload
- ✅ All buttons functional
- ✅ Mobile smooth scrolling
- ✅ Edit icons present
- ✅ Report buttons work (no 404)
- ✅ AJAX for all operations
- ✅ Perfect pixel-to-pixel match with MBL

**Ready to implement all fixes on HBL list view!**
