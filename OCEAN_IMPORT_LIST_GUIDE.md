# Ocean Import List View - User Guide

## 🎯 Quick Start

Access the list at: **http://localhost:8000/ocean-import/list**

## 🔧 Features Overview

### 1. **Search** 🔍
- Type in the search box at top-right
- Searches: File No, MBL No, Sub BL No, Voyage
- Auto-updates after 400ms of typing
- No need to press Enter

### 2. **Filter** 🎚️
- Click "Filter" button to toggle filter row
- Available filters:
  - File No
  - MBL No
  - Office
  - Consignee
  - ETD / ETA
  - POL / POD
  - Customer
  - Sales Person
- Type to filter (auto-updates after 400ms)
- Filters persist in URL (shareable links!)
- Click "Filter" again to clear and close

### 3. **Column Config** ⚙️
- Click "Config" button
- Check/uncheck columns to show/hide
- Pinned columns (always visible):
  - Checkbox
  - Lock icon
  - VIEW link
  - File No
  - Color
  - MBL No

### 4. **Excel Export** 📊
- Click "Excel" button
- Downloads CSV file with current filtered data
- File name: `ocean-import-YYYY-MM-DD.csv`
- Includes all visible columns

### 5. **Row Selection** ☑️
- Click checkbox to select individual rows
- Click anywhere on row to toggle selection
- Click header checkbox to select/deselect all
- Selection badge shows count

### 6. **Bulk Actions** 🔨

#### Copy Shipment
- Select exactly 1 row
- Click copy button (📋 icon)
- Opens create page with all data pre-filled

#### Delete Shipments
- Select one or more rows
- Click delete button (🗑️ icon)
- Confirm in modal
- Rows removed instantly (AJAX)

#### Block/Unblock
- Select one or more rows
- Click "Block" or "Unblock" button
- Updates lock status instantly

### 7. **Lock/Unlock Individual Shipment** 🔒
- Click lock icon (🔒) in any row
- Toggles between locked/unlocked
- Gray lock = Blocked
- Green unlock = Active
- Updates database immediately

### 8. **Color Status** 🎨
- Click colored square in any row
- Choose from status colors:
  - 🔴 Urgent
  - 🟡 Ready to bill
  - 🟢 Ready to close
  - 🔵 Postpone
  - ⚪ Freight Finalized
- Click "Clear / No Color" to remove

### 9. **MBL Quick View** 👁️
- Click eye icon (👁️) next to MBL No
- View shipment details in modal:
  - File No, MBL No
  - Carrier, Vessel/Voyage
  - Ports (POL, POD)
  - Dates (ETD, ETA)
  - Container count, HBL count

### 10. **Pagination** 📄
- 20 records per page
- Click page numbers to navigate
- Click arrows (← →) for prev/next
- Updates via AJAX (no page reload)
- Shows: "Showing X – Y of Z records"

### 11. **Edit Shipment** ✏️
- Click "VIEW" link (sticky column)
- Or click File No link
- Opens shipment edit page

## ⌨️ Keyboard Shortcuts

- `Enter` - Submit filter (when in filter input)
- `Esc` - Close modals
- Click outside modal to close

## 🎨 Visual Indicators

### Status Badges
- 🟢 **MATCHED** - Tracking matched
- 🔵 **Active** - Various status types

### Icons
- 🔒 **Lock** - Shipment blocked (gray)
- 🔓 **Unlock** - Shipment active (green)
- 👁️ **Eye** - Quick view MBL details
- ✏️ **VIEW** - Edit shipment
- 🎨 **Color Square** - Status color indicator

## 💡 Pro Tips

1. **Shareable Filters**: Copy URL to share exact filter state with team
2. **Quick Toggle**: Click row to select instead of hunting for checkbox
3. **Column Management**: Hide unused columns for cleaner view
4. **Fast Search**: Start typing immediately - no need to click search box first
5. **Bulk Operations**: Select multiple rows for efficient updates
6. **Color Coding**: Use colors to prioritize work (Urgent = Red)
7. **Lock Status**: Lock shipments to prevent accidental edits

## 🐛 Troubleshooting

### Page Not Loading?
- Check if Laravel server is running: `php artisan serve`
- Verify you're logged in (authentication required)
- Clear browser cache if styles look broken

### Filters Not Working?
- Make sure you're typing in the filter row (below headers)
- Wait 400ms for auto-update, or press Enter
- Check if filter button is active (highlighted)

### AJAX Not Working?
- Check browser console for JavaScript errors
- Verify CSRF token is present in page source
- Hard refresh page (Ctrl+F5 or Cmd+Shift+R)

### Excel Download Empty?
- Filters may be too restrictive
- Clear filters and try again
- Check if you have permission to export

## 📊 Data Reference

### Columns Available
1. Checkbox (selection)
2. Lock (block status)
3. VIEW (edit link)
4. File No (edit link)
5. Color (status indicator)
6. MBL No (with quick view)
7. Tracking EDI Response
8. Submit Status
9. Office
10. HBL No
11. Container Count (CT)
12. Container/Qty
13. Consignee
14. ETD
15. ETA
16. O. B/L Type
17. M. B/L Type
18. Port of Loading
19. Port of Discharge
20. Place of Delivery
21. Final Destination
22. Oversea Agent
23. Customer
24. Sales Person
25. Total Pieces
26. Total Weight
27. Total Volume
28. Freight Term (MBL)
29. Freight Term (HBL)
30. LFD (Last Free Day)
31. Vessel / Voyage
32. Container No
33. MBL Type
34. Sub BL No
35. Available Date
36. G.O Date
37. Trucker
38. Entry No
39. POD ETA
40. D.O. No
41. Final ETA
42. Release No
43. Hold Status
44. Entry DOC Sent
45. AMS No
46. ISF No
47. Incoterms
48. Post Date

## 🔐 Permissions Required

- **View**: Access to ocean-import.index route
- **Create**: Click new shipment button
- **Edit**: Click VIEW or File No links
- **Delete**: Bulk delete selected shipments
- **Update**: Block/unblock, color changes

## 📞 Support

If you encounter issues:
1. Check browser console for errors
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify database connection
4. Ensure all migrations are run
5. Clear application cache: `php artisan cache:clear`

---

**Last Updated**: 2026-07-23  
**Version**: 1.0 (Production Ready)
