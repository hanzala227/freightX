# Container List - Testing Checklist

## 🧪 TEST URL
`http://localhost:8000/ocean-import/list/containers`

## ✅ WHAT TO TEST

### 1. Page Load
- [ ] Page loads without errors
- [ ] No JavaScript console errors
- [ ] Table displays with data
- [ ] Pagination shows at bottom
- [ ] Column visibility applied correctly

### 2. Search Functionality
- [ ] Type in "Quick search" input
- [ ] Grid updates after 400ms automatically
- [ ] No hard page refresh
- [ ] URL updates with `?search=...`
- [ ] Toast notification appears
- [ ] Results filtered correctly

### 3. Filter Functionality
- [ ] Click "Filter" button - filter row appears
- [ ] Type in "File No" filter input
- [ ] Grid updates after 400ms automatically
- [ ] Type in "Container No" filter
- [ ] Grid updates automatically
- [ ] Type in "Consignee" filter
- [ ] Grid updates automatically
- [ ] All filters work together
- [ ] URL contains all filter params

### 4. Pagination
- [ ] Click "Next" page link
- [ ] Grid updates without hard refresh
- [ ] URL updates with `?page=2`
- [ ] Stats update (Showing X-Y of Z)
- [ ] Select checkboxes, then paginate - selection clears
- [ ] Pagination maintains search/filter params

### 5. Selection & Toolbar
- [ ] Click row - checkbox toggles
- [ ] Click "Select All" - all checkboxes toggle
- [ ] Badge shows "X selected" when items selected
- [ ] Delete button enabled when items selected
- [ ] Block button enabled when items selected
- [ ] Unblock button enabled when items selected
- [ ] All buttons disabled when nothing selected

### 6. Delete Operation
- [ ] Select 1+ containers
- [ ] Click Delete button
- [ ] Confirmation modal appears
- [ ] Click "Cancel" - modal closes
- [ ] Click Delete button again
- [ ] Click "Delete" in modal
- [ ] Toast shows "X container(s) deleted"
- [ ] Grid refreshes automatically
- [ ] Deleted items removed from list

### 7. Block/Unblock Operations
- [ ] Select 1+ containers
- [ ] Click "Block" button
- [ ] Toast shows "Containers blocked"
- [ ] Grid refreshes automatically
- [ ] C.Hold column shows lock icon
- [ ] Select blocked containers
- [ ] Click "Unblock" button
- [ ] Toast shows "Containers unblocked"
- [ ] Grid refreshes automatically
- [ ] C.Hold column shows unlock icon

### 8. Inline Remarks
- [ ] Click in "Remarks" input field
- [ ] Type some text
- [ ] Click outside (blur event)
- [ ] Toast shows "Remarks saved"
- [ ] No page refresh
- [ ] Reload page - remarks persisted

### 9. Color Picker
- [ ] Click color dot in "Color" column
- [ ] Color picker modal opens
- [ ] Click a color option
- [ ] Toast shows "Status color updated"
- [ ] Modal closes
- [ ] Grid refreshes
- [ ] Color dot updates to new color
- [ ] Open color picker again
- [ ] Click "Clear / No Color"
- [ ] Color resets to gray

### 10. Excel Export
- [ ] Add some filters (search, file no, etc.)
- [ ] Click "Excel" button
- [ ] Toast shows "Downloading Excel file..."
- [ ] NO page refresh
- [ ] CSV file downloads
- [ ] Open CSV - data matches filtered results

### 11. Column Visibility
- [ ] Click "Config" button
- [ ] Config panel opens with checkboxes
- [ ] Uncheck "PP/CTF" column
- [ ] Column disappears immediately
- [ ] Check "PP/CTF" column again
- [ ] Column reappears
- [ ] Refresh page - column preferences persist
- [ ] Toggle 5+ columns - all work

### 12. Mobile Responsive
- [ ] Open Chrome DevTools
- [ ] Switch to mobile device (iPhone 12)
- [ ] Page adapts to mobile layout
- [ ] Toolbar stacks vertically
- [ ] Table scrolls horizontally
- [ ] Only 1 sticky column (checkbox)
- [ ] All buttons still clickable
- [ ] Touch targets >= 28px
- [ ] Scrolling smooth with momentum
- [ ] Switch to tablet (iPad)
- [ ] 2 sticky columns (checkbox, flag)
- [ ] Everything still functional

## 🐛 ERRORS TO WATCH FOR

### Console Errors (F12):
- ❌ "COLOR_OPTIONS already declared"
- ❌ "Cannot read properties of null"
- ❌ "updateGrid is not defined"
- ❌ "saveRemarks is not defined"
- ❌ "Unexpected token '<'"
- ❌ Any red error messages

### Network Errors (F12 Network Tab):
- ❌ 422 Unprocessable Content
- ❌ 500 Internal Server Error
- ❌ 404 Not Found
- ❌ Failed to load resource

### Visual Issues:
- ❌ Buttons not aligned
- ❌ Pagination looks broken
- ❌ Table columns misaligned
- ❌ Modal doesn't center
- ❌ Toast notifications don't appear
- ❌ Sticky columns don't stick
- ❌ Filters not visible when toggled

## ✅ SUCCESS CRITERIA

All tests pass with:
- ✅ ZERO hard page refreshes (except initial load)
- ✅ ZERO JavaScript console errors
- ✅ ZERO Laravel errors
- ✅ ZERO 422/500 HTTP errors
- ✅ All operations show toast notifications
- ✅ All operations update grid via AJAX
- ✅ Mobile scrolling smooth and functional
- ✅ UI matches MBL list style

## 🎯 EXPECTED BEHAVIOR

Every action should:
1. Work without hard refresh
2. Show toast notification
3. Update grid content via AJAX
4. Update URL with pushState (search/filter/pagination)
5. Preserve user's context (filters, search, page)
6. Display smooth animations and transitions
7. Work on desktop, tablet, and mobile
8. Handle errors gracefully with user feedback

## 📝 NOTES

- If you see "Undefined variable $elements" error in pagination, the `custom.blade.php` pagination view needs to be fixed
- If you see "Column not found: is_hold" error, it's trying to update wrong table - should use parent shipment's is_hold for HBLs
- If Excel download triggers page navigation, iframe technique not working
- If search doesn't trigger after typing, debouncing may not be working
- If grid doesn't update, check Network tab for actual AJAX call and response

## 🔍 DEBUGGING TIPS

1. **Open Browser Console (F12)** before testing
2. **Check Network tab** to see AJAX requests
3. **Look for red error messages** in console
4. **Check Response payload** of AJAX calls
5. **Verify URL changes** when searching/filtering
6. **Test on actual mobile device** for touch issues
7. **Check localStorage** for column visibility (dev tools → Application → Local Storage)

---

**READY TO TEST!** 🚀
