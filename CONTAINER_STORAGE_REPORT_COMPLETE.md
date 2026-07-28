# Container Storage Report - Complete Implementation

## Overview
Fully functional Container Storage Report with dynamic dropdowns, comprehensive filtering, and print functionality.

---

## Features Implemented

### 1. Dynamic Select Inputs ✅
All dropdown options load dynamically from API endpoints:

- **Office Dropdown**: Loads all active offices
- **Party Dropdown**: Loads trade partners filtered by type (Customer/Oversea Agent)

### 2. Filter Options ✅

**Period (Required):**
- Date From input
- Date To input

**Department Type (Required):**
- Ocean Import (checkbox)
- Ocean Export (checkbox)
- Trucker (checkbox)
- Misc (checkbox)

**Office:**
- Dropdown with all active offices
- "All" option to include all offices

**Party (Required):**
- Radio buttons: Customer / Oversea Agent
- Dynamic dropdown filtered by selected party type
- Options load from trade partners

**View Option:**
- Checkbox: "Show containers without storage Start Date"

### 3. Report Generation ✅

**Process:**
1. User fills in filter criteria
2. Clicks "Print" button
3. Report opens in new window
4. Report displays filtered container data
5. User can print or close window

**Report Features:**
- Professional header with report title
- Filter information display (Period, Customer, Office, Department)
- Container table with columns:
  - Container #
  - TP/SZ (Container Type)
  - File #
  - MBL #
  - HBL #
  - Start Date
  - End Date
  - Days (calculated storage days)
- Total row showing container count and total days
- Footer with generation timestamp and user info
- Print and Close buttons
- Print-ready CSS styling

### 4. Backend Implementation ✅

**Controllers:**
- `ReportController@containerStorage` - Main form view
- `ReportController@containerStoragePrint` - Generate report
- `DropdownOptionsController` - API endpoints for dropdowns

**API Endpoints:**
```
GET /api/dropdown-options/agents
GET /api/dropdown-options/offices
GET /api/dropdown-options/ports
GET /api/dropdown-options/users
GET /api/dropdown-options/locations
GET /api/dropdown-options/package-units
GET /api/dropdown-options/container-types
GET /api/dropdown-options/warehouses
GET /api/dropdown-options/currencies
GET /api/dropdown-options/quotations
GET /api/dropdown-options/truckers
GET /api/dropdown-options/vendors
```

**Database Queries:**
- Ocean Import Containers: `ocean_import_containers` table
- Ocean Export Containers: `ocean_export_containers` table
- Truck Shipment Containers: `truck_shipment_containers` table
- Joins with shipment tables and container types
- Filters by date range, office, party type/id, and departments

### 5. Storage Days Calculation ✅

**Logic:**
- Ocean Import/Export: Uses `storage_start_date` and `storage_end_date`
- Trucker: Uses `pickup_date` and `empty_return_date`
- Calculates difference in days using Carbon
- Handles null dates gracefully (shows 0 days)

---

## Files Created/Modified

### Created:
1. `app/Http/Controllers/Api/DropdownOptionsController.php`
   - API controller for all dropdown options
   - Handles agents, offices, ports, users, etc.

2. `app/Models/Location.php`
   - Location model for location dropdown

3. `resources/views/report/container-storage-print.blade.php`
   - Print view for the report
   - Professional layout with print CSS

### Modified:
1. `resources/views/report/container-storage.blade.php`
   - Added Alpine.js for dynamic behavior
   - Dynamic dropdowns with API integration
   - Form validation
   - Toast notifications
   - Loading states

2. `app/Http/Controllers/ReportController.php`
   - Added `containerStorage()` method
   - Added `containerStoragePrint()` method with full query logic

3. `routes/web.php`
   - Updated container-storage routes to use controller

4. `routes/api.php`
   - Added dropdown-options API routes
   - Added truck shipment container/commodity routes

---

## Usage Guide

### For Users:

1. **Navigate to Report:**
   - Go to `http://localhost:8000/report/container-storage`

2. **Fill Filter Criteria:**
   - Select date range (required)
   - Check department types (required, at least one)
   - Select office (optional, defaults to "All")
   - Choose party type (Customer or Oversea Agent)
   - Select specific party from dropdown (required)
   - Optionally check "Show containers without storage Start Date"

3. **Generate Report:**
   - Click "Print" button
   - Report opens in new window
   - Review filtered data
   - Click "Print Report" to print or "Close" to exit

### For Developers:

**Adding New Filter:**
```javascript
// In Alpine.js data
filters: {
    new_filter: ''
}

// In backend
$newFilter = $request->input('new_filter');
if ($newFilter) {
    $query->where('column', $newFilter);
}
```

**Adding New Department:**
```javascript
// In departments array
departments: ['Ocean Import', 'Ocean Export', 'Trucker', 'Misc', 'New Department']

// In backend switch statement
case 'New Department':
    $query = DB::table('new_department_containers as c')
        // ... query logic
    break;
```

---

## API Response Formats

### Agents Endpoint:
```json
{
    "data": [
        {
            "id": 1,
            "name": "ABC Company",
            "company_name": "ABC Company",
            "is_customer": true,
            "is_oversea_agent": false
        }
    ]
}
```

### Offices Endpoint:
```json
{
    "data": [
        {
            "id": 1,
            "name": "Main Office",
            "code": "MEO"
        }
    ]
}
```

---

## Validation Rules

1. **Date From & Date To**: Required
2. **Departments**: At least one must be selected
3. **Party Type**: Required (radio button)
4. **Party**: Required (dropdown)

Toast notifications show validation errors if criteria not met.

---

## Technical Details

### Alpine.js Methods:

- `init()` - Initializes component, loads dropdown options
- `loadDropdownOptions()` - Fetches all dropdown data from API
- `generateReport()` - Validates and opens report in new window
- `filteredAgents` - Computed property to filter agents by type

### CSS Classes:

- `.btn-print` - Print button styling
- `.toast-container` - Toast notification container
- `.toast` - Individual toast styling with animations

### Database Tables Used:

- `ocean_import_containers`
- `ocean_exports`  
- `ocean_export_containers`
- `ocean_imports`
- `truck_shipment_containers`
- `truck_shipments`
- `container_types`
- `trade_partners`
- `offices`

---

## Browser Compatibility

✅ Chrome/Edge (Chromium)
✅ Firefox
✅ Safari

Print functionality works in all modern browsers.

---

## Future Enhancements

Potential improvements:
- Export to Excel/PDF
- Email report functionality
- Save filter presets
- Scheduled report generation
- Additional storage metrics (costs, utilization)
- Chart/graph visualizations
- Multi-currency support for storage charges

---

## Testing Checklist

- [x] Form loads with empty dropdowns initially
- [x] Dropdowns populate from API
- [x] Date inputs accept valid dates
- [x] Department checkboxes can be toggled
- [x] Office dropdown shows "All" and offices
- [x] Party type radio buttons switch dropdown
- [x] Party dropdown filters by type
- [x] View option checkbox toggles
- [x] Validation prevents empty required fields
- [x] Print button opens new window
- [x] Report displays correct data
- [x] Storage days calculated correctly
- [x] Print functionality works
- [x] Toast notifications appear
- [x] Loading state shows on button

---

## Troubleshooting

**Dropdowns Empty:**
- Check API endpoints are accessible
- Verify database has data
- Check browser console for errors

**Report Shows No Data:**
- Verify containers exist in database with dates in range
- Check party association with shipments
- Ensure departments have matching data

**Print Not Working:**
- Check browser popup blocker
- Try different browser
- Check console for JavaScript errors

---

## Summary

The Container Storage Report is now fully functional with:
✅ Dynamic dropdown options loaded via API
✅ Comprehensive filtering system
✅ Professional print-ready report layout
✅ Storage days calculation
✅ Toast notifications
✅ Form validation
✅ Loading states
✅ Responsive design
✅ Clean, maintainable code

Users can now generate accurate container storage reports filtered by date range, departments, office, and party with all data populated dynamically from the database.
