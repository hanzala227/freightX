# HBL List View - Implementation Status

**URL**: `http://localhost:8000/ocean-import/list/hbl`

---

## ✅ Completed Steps

### 1. Controller Updated ✅
**File**: `app/Http/Controllers/OceanImportController.php`
**Method**: `hblList()`

**Changes Made**:
- ✅ Added `filter_file_no` parameter support
- ✅ Added `filter_hbl_no` parameter support
- ✅ Added `filter_mbl_no` parameter support
- ✅ Added `filter_consignee` parameter support
- ✅ Kept legacy filter support (file_no, hbl_no, etc.)
- ✅ Added AJAX JSON response support
- ✅ Returns partial view for AJAX: `ocean-import.partials.hbl-list-rows`

### 2. Partial View Created ✅
**File**: `resources/views/ocean-import/partials/hbl-list-rows.blade.php`

**Features**:
- ✅ All 20 columns included
- ✅ Lock icons show `is_hold` database state
- ✅ External link icon on File No column
- ✅ External link icon on HBL No column
- ✅ All links open in new tabs (`target="_blank"`)
- ✅ Color picker functional
- ✅ Flag toggle functional
- ✅ Empty state with icon

---

## 🔄 Next Step: Update Main View

**File**: `resources/views/ocean-import/hbl-list.blade.php` (726 lines)

### Required Changes:

#### A. Add Mobile CSS (Top of File)
Copy mobile responsive CSS from MBL list:
- Button group styling
- Mobile breakpoints (768px, 480px)
- Sticky column reduction (6 → 3 → 1)
- iOS momentum scrolling
- Touch-friendly targets
- Grid wrapper overflow settings

#### B. Fix Filter Inputs
Change all filter inputs from:
```html
<input data-col-idx="3" oninput="applyFilters()">
```
To:
```html
<input data-param="filter_file_no" oninput="applyFiltersTyping()">
```

**Filter Mapping**:
- Col 3 (File No) → `filter_file_no`
- Col 5 (HBL No) → `filter_hbl_no`
- Col 9 (MBL No) → `filter_mbl_no`
- Col 10 (Consignee) → `filter_consignee`

#### C. Fix Excel Button
Change from:
```html
<a class="btn-action-round white" href="..." target="_blank">
```
To:
```html
<button class="btn-action-round white" onclick="exportExcel()">
```

Add `exportExcel()` function (hidden iframe technique).

#### D. Fix JavaScript Functions
Remove/fix:
- ❌ Remove IIFE if present
- ❌ Remove `window.` prefixes
- ❌ Change `const/let` to `var`
- ✅ Add `exportExcel()` function
- ✅ Fix `updateGrid()` to expect JSON
- ✅ Fix `toggleLock()` to update grid
- ✅ Fix `blockSelected()` to update grid
- ✅ Fix `unblockSelected()` to update grid
- ✅ Add `profitReport()` function (revenue-cost)
- ✅ Add `arrivalNotice()` function (edit page)
- ✅ Fix `applyFilters()` to use data-param
- ✅ Add `applyFiltersTyping()` with debounce

---

## 📋 Function List Needed

### Grid Management
```javascript
var searchDebounce;
var filterDebounce;

function updateGrid(url) {
    // AJAX fetch, expect JSON response
    // Update grid-body, pagination, stats
}

function quickSearch(val) {
    // Debounce 400ms, call updateGrid
}

function applyFiltersTyping() {
    // Debounce 400ms, call applyFilters
}

function applyFilters() {
    // Build URL with data-param values
    // Call updateGrid
}
```

### Toolbar
```javascript
function updateToolbar() {
    // Enable/disable buttons based on selection
}

function toggleSelectAll(el) {
    // Check/uncheck all
}

function rowClick(e, row) {
    // Toggle checkbox on row click
}
```

### Operations
```javascript
function toggleLock(el) {
    // Call backend, refresh grid
}

function blockSelected() {
    // Call backend, refresh grid
}

function unblockSelected() {
    // Call backend, refresh grid
}

function confirmDelete() {
    // Show modal
}

function executeDelete() {
    // Call backend, refresh grid
}

function copySelected() {
    // Navigate to create page with copy param
}

function changeSales(sel) {
    // Call backend, refresh grid
}

function changeOp(sel) {
    // Call backend, refresh grid
}
```

### Reports
```javascript
function profitReport() {
    // Navigate to revenue-cost report
}

function arrivalNotice() {
    // Navigate to edit page
}
```

### Excel
```javascript
function exportExcel() {
    // Hidden iframe download
}
```

### UI
```javascript
function toggleFilter() {
    // Show/hide filter row
}

function toggleConfig() {
    // Show/hide config panel
}

function showToast(type, msg) {
    // Show notification
}
```

---

## 🎯 Expected Outcome

After all changes:
- ✅ Zero JavaScript errors
- ✅ Filter works on typing with debounce
- ✅ Lock icons show DB state, toggle without refresh
- ✅ Excel downloads without refresh
- ✅ Block/Unblock refresh grid
- ✅ Delete refreshes grid
- ✅ Change Sales/OP refreshes grid
- ✅ Profit Report opens revenue-cost page (no 404)
- ✅ Arrival Notice opens edit page (no 404)
- ✅ Mobile responsive with smooth scrolling
- ✅ External link icons on File No and HBL columns
- ✅ All operations via AJAX, no hard refresh

---

## ⚠️ Current Challenges

The HBL view is 726 lines and has complex JavaScript. Best approach:

**Option 1**: Incremental fixes
- Fix filter inputs
- Fix JavaScript functions one by one
- Add mobile CSS
- Test each change

**Option 2**: Complete rewrite
- Use MBL list as template
- Adapt for HBL columns
- Replace entire file

**Recommendation**: Option 1 (safer, less breaking)

---

## 📝 Implementation Notes

1. **Lock State**: HBL has `is_hold` field, partial already uses it
2. **Flag**: HBL has `is_ecommerce` field for flag color
3. **Balances**: AR, AP, DC balances already calculated via withSum
4. **Links**: File No → shipment edit, HBL No → shipment edit #hbls
5. **Reports**: Same as MBL (revenue-cost for profit, edit for arrival)

---

## 🚀 Ready to Complete

Backend is ready, partial is ready. Main view needs JavaScript fixes and mobile CSS. Estimated 10-15 targeted string replacements needed.

**Next action: Fix the main HBL view file systematically.**
