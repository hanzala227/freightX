# Ocean Export MBL List - Work in Progress

## Status
Partially fixed - needs completion

## What's Been Done ✅
1. ✅ Fixed first ~200 lines of JavaScript:
   - `getCSRF()` function
   - `showToast()` function  
   - `getSelectedIds()` function (ES5)
   - `updateGrid()` function (ES5)
   - `updateToolbar()` function (ES5)
   - `toggleSelectAll()` function (ES5)
   - `rowClick()` function (ES5)
   - `toggleLock()` function with backend API
   - `confirmDelete()`, `closeConfirm()`, `executeDelete()` (ES5)
   - `copySelected()` function (ES5)
   - `blockSelected()` function with immediate icon update (ES5)
   - `unblockSelected()` function with immediate icon update (ES5)

## What Still Needs Fixing ❌

### Remaining ES6 Code (lines ~615-816):
1. ❌ Duplicate functions need removal (lines ~615-618)
2. ❌ Profit/Arrival button event listeners need conversion
3. ❌ Change OP dropdown event listener needs conversion
4. ❌ Filter functions (`toggleFilter`, `applyFilters`) need ES5 conversion
5. ❌ Quick search function needs ES5 conversion
6. ❌ Column config functions need ES5 conversion
7. ❌ showToast duplicate with template literals (line ~621)
8. ❌ Color picker functions with ES6 syntax
9. ❌ Excel export function needs adding
10. ❌ Init function (IIFE) needs conversion
11. ❌ DOMContentLoaded listeners need conversion

### Specific ES6 Patterns to Fix:
- `const` / `let` → `var`
- Arrow functions `=>` → `function() {}`
- Template literals `` ` `` → string concatenation
- Spread operators `[...]` → loops
- `.forEach()`, `.map()`, `.filter()` → `for` loops
- Optional chaining `?.` → explicit checks
- `.includes()` → `.indexOf() !== -1`
- `Object.entries()` → manual loop
- IIFE `(function() {})()` → regular function

### Excel Button Fix:
Current (line ~60):
```blade
<a class="btn-action-round white" href="{{ route('ocean-export.export-csv') }}" title="Export to CSV" target="_blank">
```

Should be:
```blade
<button class="btn-action-round white" onclick="exportExcel()" title="Export to CSV" id="btn-excel">
```

Plus add function:
```javascript
function exportExcel() {
    showToast('info', 'Preparing Excel export...');
    var baseUrl = '/ocean-export/export-csv';
    var params = new URLSearchParams(window.location.search);
    var queryString = params.toString();
    var url = baseUrl + (queryString ? '?' + queryString : '');
    var iframe = document.getElementById('excel-frame');
    if (iframe) {
        iframe.src = url;
        setTimeout(function() {
            showToast('success', 'Excel file download started');
        }, 500);
    }
}
```

### Mobile Responsive CSS:
Need to add mobile responsive styles in `@push('styles')` section (after line 4).

Copy from ocean-import/mbl-list.blade.php or ocean-export/list.blade.php:
- Sticky column reduction (6→2→1)
- Touch targets (28px min)
- iOS momentum scrolling
- Responsive breakpoints

### Hidden iframe for Excel:
Add before closing `</div>` of page-content (around line 305):
```blade
{{-- Hidden iframe for Excel download --}}
<iframe id="excel-frame" style="display:none;"></iframe>
```

## Recommended Approach

### Option 1: Complete Manual Fix (Time-consuming but thorough)
1. Read lines 615-816
2. Convert each function one by one to ES5
3. Remove duplicates
4. Test each function

### Option 2: Reference Copy (Faster, needs adaptation)
1. Copy entire `<script>` section from ocean-import/mbl-list.blade.php
2. Adapt route names from `ocean-import.*` to `ocean-export.*`
3. Adapt any ocean-import specific logic to ocean-export
4. Ensure `data-id` attributes match (not `data-idx`)

### Option 3: Hybrid (Recommended)
1. Keep what's already fixed (lines 307-615)
2. Copy remaining functions from working ocean-import MBL list
3. Adapt routes and IDs
4. Remove duplicates
5. Add Excel export function
6. Add mobile CSS

## Files to Reference

**Working Examples**:
1. `/resources/views/ocean-import/mbl-list.blade.php` - Perfect reference, already ES5
2. `/resources/views/ocean-export/list.blade.php` - Just fixed, has correct patterns
3. `/resources/views/ocean-import/list.blade.php` - Another working example

**Target File**:
- `/resources/views/ocean-export/mbl-list.blade.php` - Needs completion

## Key Differences Ocean Import vs Ocean Export

### Routes:
- `ocean-import.*` → `ocean-export.*`
- Same route structure, just different module

### Data Attributes:
- Ocean Import MBL uses: `data-id` ✅ (correct)
- Make sure ocean-export MBL uses: `data-id` (not `data-idx`)

### Controller Methods:
Both have identical methods:
- `index()` - returns list with AJAX support
- `mblList()` - returns MBL list with AJAX support
- `bulk-delete`, `bulk-block`, `bulk-unblock`
- `export-csv`
- `update-color`

## Quick Fix Script

If starting fresh, this is the complete working JavaScript structure needed:
1. Variable declarations (COLOR_OPTIONS, _colorShipmentId)
2. Helper functions (getCSRF, showToast, getSelectedIds)
3. Grid functions (updateGrid, updateToolbar)
4. Selection functions (toggleSelectAll, rowClick)
5. Lock function (toggleLock with API)
6. CRUD functions (confirmDelete, closeConfirm, executeDelete, copySelected)
7. Bulk operations (blockSelected, unblockSelected)
8. Report functions (profit, arrival)
9. Change OP function
10. Filter functions (toggleFilter, applyFilters, quickSearch)
11. Column config (toggleConfig, buildConfigPanel, toggleColumn)
12. Color picker (openColorPicker, selectColor, closeColorPicker, clearColor)
13. Excel export (exportExcel)
14. Init (DOMContentLoaded)

## Testing After Fix

- [ ] Page loads without JavaScript errors
- [ ] Select shipments - buttons enable/disable
- [ ] Delete selected - shows modal, deletes, updates grid
- [ ] Block selected - icons change immediately
- [ ] Unblock selected - icons change immediately
- [ ] Click lock icon - toggles and updates backend
- [ ] Copy shipment - redirects to create with data
- [ ] Quick search - updates grid after typing
- [ ] Filter row - toggles on/off
- [ ] Column config - shows/hides columns
- [ ] Color picker - opens, selects, updates
- [ ] Excel export - downloads file
- [ ] Change OP - updates selected shipments
- [ ] Pagination - works via AJAX
- [ ] Mobile view - scrolls smoothly, sticky columns work

---

**Next Step**: Complete the JavaScript conversion following Option 3 (Hybrid approach)
