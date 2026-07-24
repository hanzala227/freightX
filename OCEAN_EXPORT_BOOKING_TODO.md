# Ocean Export Booking List - Implementation Plan

## URL: http://localhost:8000/ocean-export/booking/list

## Current Status: ⚠️ NEEDS COMPLETE REWRITE

---

## Issues to Fix (Same Pattern as Previous Views)

### 1. JavaScript Conversion to ES5
- [ ] Convert all `const`/`let` to `var`
- [ ] Convert all arrow functions `=>` to `function() {}`
- [ ] Convert template literals to string concatenation
- [ ] Remove spread operators
- [ ] Remove array methods like `.map()`, `.forEach()` where they use arrow functions

### 2. Add AJAX Support
- [ ] Create `updateGrid()` function for AJAX refresh
- [ ] Update controller `index()` method to detect AJAX requests
- [ ] Return JSON response with `{success, html, pagination, first, last, total}`
- [ ] Update all operations to use AJAX (no hard refreshes)

### 3. Create Partial View
- [ ] Create `resources/views/ocean-export/partials/booking-list-rows.blade.php`
- [ ] Move table row rendering to partial
- [ ] Use `data-id` attributes
- [ ] Include all booking columns

### 4. Add Mobile Responsive CSS
- [ ] Sticky columns: reduce on mobile (6→3→1)
- [ ] Touch-friendly targets (28px min height)
- [ ] iOS momentum scrolling
- [ ] Stacked toolbar on mobile

### 5. Fix Operations
- [ ] Quick search with 400ms debouncing
- [ ] Filter with debouncing
- [ ] Delete with confirmation
- [ ] Convert to Shipment
- [ ] Change Sales/OP with modals
- [ ] Color picker
- [ ] Excel export via hidden iframe

### 6. Ensure Backend Routes Exist
- [ ] `ocean-bookings.bulk-delete`
- [ ] `ocean-bookings.bulk-change-sales`
- [ ] `ocean-bookings.bulk-change-op`
- [ ] `ocean-bookings.convert-to-shipment`
- [ ] `ocean-bookings.color`

---

## Implementation Steps (Follow Exact Pattern from Previous Views)

### Step 1: Add Mobile CSS
```css
@media (max-width: 768px) {
    .page-content { padding: 2px !important; overflow-x: hidden !important; }
    .portlet.light { margin: 0 !important; border-radius: 0 !important; }
    .grid-wrapper { 
        width: 100% !important;
        height: calc(100vh - 350px) !important;
        overflow-x: auto !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
    .grid-table { font-size: 8px !important; min-width: 1800px !important; }
    /* Reduce sticky columns on tablet/mobile */
    .grid-table th:nth-child(4), .grid-table td:nth-child(4),
    .grid-table th:nth-child(5), .grid-table td:nth-child(5),
    .grid-table th:nth-child(6), .grid-table td:nth-child(6) {
        position: static !important;
        left: auto !important;
    }
}
```

### Step 2: Create Partial View
File: `resources/views/ocean-export/partials/booking-list-rows.blade.php`

```blade
@forelse($bookings as $booking)
<tr id="booking-row-{{ $booking->id }}"
    data-id="{{ $booking->id }}"
    data-booking-no="{{ $booking->booking_no }}"
    onclick="rowClick(event, this)">
    
    <td class="sticky-col" style="left:0;text-align:center;" onclick="event.stopPropagation()">
        <input type="checkbox" name="ids[]" value="{{ $booking->id }}" class="row-check" onchange="updateToolbar()">
    </td>
    <td class="sticky-col" style="left:25px;text-align:center;">
        <span class="color-mark" style="background:{{ $booking->color ?? '#94a3b8' }}" 
              onclick="event.stopPropagation();openColorPicker({{ $booking->id }}, '{{ $booking->color ?? '' }}')"></span>
    </td>
    <td class="sticky-col" style="left:60px;" onclick="event.stopPropagation()">
        <a href="{{ route('ocean-bookings.edit', $booking->id) }}" class="col-link" target="_blank">{{ $booking->booking_no }}</a>
    </td>
    <!-- Add remaining columns here -->
</tr>
@empty
<tr id="empty-row">
    <td colspan="50" style="text-align:center;padding:30px 10px;color:#94a3b8;">
        <i class="fa fa-inbox" style="font-size:32px;display:block;margin-bottom:12px;opacity:0.5;"></i>
        <div style="font-size:13px;font-weight:600;">No bookings found</div>
    </td>
</tr>
@endforelse
```

### Step 3: Update Controller
Add AJAX support to `OceanBookingController@index`:

```php
public function index(Request $request)
{
    // ... existing query code ...
    
    $bookings = $query->latest()->paginate(20)->withQueryString();
    
    // Return JSON for AJAX requests
    if ($request->ajax() || $request->wantsJson()) {
        try {
            $html = view('ocean-export.partials.booking-list-rows', compact('bookings'))->render();
            $pagination = view('vendor.pagination.custom', ['paginator' => $bookings])->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => $pagination,
                'first' => $bookings->firstItem() ?? 0,
                'last' => $bookings->lastItem() ?? 0,
                'total' => $bookings->total(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    // ... existing return view code ...
}
```

### Step 4: Convert JavaScript to ES5
Replace all modern JavaScript:

```javascript
// BAD (ES6+)
const ids = getSelectedIds();
const updateGrid = async () => { ... };
document.querySelectorAll('.row-check').forEach(cb => cb.checked = true);
const html = `<div>${value}</div>`;

// GOOD (ES5)
var ids = getSelectedIds();
function updateGrid() { ... }
var cbs = document.querySelectorAll('.row-check');
for (var i = 0; i < cbs.length; i++) {
    cbs[i].checked = true;
}
var html = '<div>' + value + '</div>';
```

### Step 5: Add Core AJAX Functions

```javascript
function getCSRF() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

function updateGrid() {
    var url = new URL(window.location.href);
    url.searchParams.set('ajax', '1');
    
    fetch(url.toString(), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success) {
            document.getElementById('grid-body').innerHTML = data.html;
            document.getElementById('pagination-container').innerHTML = data.pagination;
            document.getElementById('stat-first').textContent = data.first;
            document.getElementById('stat-last').textContent = data.last;
            document.getElementById('stat-total').textContent = data.total;
            updateToolbar();
        }
    })
    .catch(function(err) {
        showToast('error', 'Failed to update grid');
    });
}

function quickSearch(val) {
    clearTimeout(searchDebounce);
    searchDebounce = setTimeout(function() {
        var url = new URL(window.location.href);
        if (val.trim()) {
            url.searchParams.set('search', val.trim());
        } else {
            url.searchParams.delete('search');
        }
        window.history.replaceState({}, '', url.toString());
        updateGrid();
    }, 400);
}
```

### Step 6: Add Hidden Iframe for Excel
```html
<iframe id="excel-frame" style="display:none;"></iframe>
```

### Step 7: Excel Export Function
```javascript
function exportExcel() {
    showToast('info', 'Preparing Excel export...');
    var baseUrl = '{{ route("ocean-bookings.export-csv") }}';
    var params = new URLSearchParams(window.location.search);
    var url = baseUrl + (params.toString() ? '?' + params.toString() : '');
    var iframe = document.getElementById('excel-frame');
    if (iframe) {
        iframe.src = url;
        setTimeout(function() {
            showToast('success', 'Excel file download started');
        }, 500);
    }
}
```

---

## Booking-Specific Features

### Convert to Shipment
```javascript
function confirmConvert() {
    var ids = getSelectedIds();
    if (!ids.length) return;
    document.getElementById('convert-msg').textContent =
        'Convert ' + ids.length + ' booking(s) to ocean export shipments?';
    document.getElementById('convert-overlay').classList.add('open');
}

function executeConvert() {
    closeConvert();
    var ids = getSelectedIds();
    
    fetch('{{ route("ocean-bookings.convert-to-shipment") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRF(),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ ids: ids })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success) {
            showToast('success', d.message);
            setTimeout(function() { updateGrid(); }, 600);
        }
    });
}
```

### Change Sales/OP Modals
```javascript
var _changeUserType = null;

function changeSales() {
    var ids = getSelectedIds();
    if (!ids.length) return;
    _changeUserType = 'sales';
    document.getElementById('change-user-title').textContent = 'Change Sales Person';
    document.getElementById('change-user-overlay').classList.add('open');
}

function changeOp() {
    var ids = getSelectedIds();
    if (!ids.length) return;
    _changeUserType = 'op';
    document.getElementById('change-user-title').textContent = 'Change Operator';
    document.getElementById('change-user-overlay').classList.add('open');
}

function executeChangeUser() {
    var userId = document.getElementById('change-user-select').value;
    if (!userId) return;
    
    var ids = getSelectedIds();
    var route = _changeUserType === 'sales' 
        ? '{{ route("ocean-bookings.bulk-change-sales") }}'
        : '{{ route("ocean-bookings.bulk-change-op") }}';
    var param = _changeUserType === 'sales' ? 'sales_person_id' : 'op_id';
    
    var body = { ids: ids };
    body[param] = userId;
    
    fetch(route, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRF(),
            'Accept': 'application/json'
        },
        body: JSON.stringify(body)
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        closeChangeUser();
        showToast('success', d.message);
        setTimeout(function() { updateGrid(); }, 600);
    });
}
```

---

## Files to Modify

1. **resources/views/ocean-export/booking-list.blade.php**
   - Add mobile CSS
   - Convert JavaScript to ES5
   - Replace tbody with `@include` for partial
   - Add updateGrid() function
   - Add all AJAX handlers

2. **resources/views/ocean-export/partials/booking-list-rows.blade.php** (NEW)
   - Create with complete row structure
   - Use `data-id` attributes
   - All booking columns

3. **app/Http/Controllers/OceanBookingController.php**
   - Add AJAX detection to `index()` method
   - Return JSON for AJAX requests
   - Ensure all bulk operation methods exist

---

## Testing Checklist

- [ ] Page loads without errors
- [ ] Quick search works with 400ms debounce
- [ ] Filter row toggles and applies
- [ ] Pagination works via AJAX
- [ ] Row selection and toolbar
- [ ] Delete with confirmation
- [ ] Convert to Shipment works
- [ ] Change Sales modal works
- [ ] Change OP modal works
- [ ] Color picker works
- [ ] Excel export via iframe
- [ ] Mobile responsive (sticky columns reduce)
- [ ] No hard refreshes on any operation

---

## Estimated Time: 2-3 hours

This is a complete rewrite following the exact pattern established in:
- Ocean Export Main List
- Ocean Export MBL List
- Ocean Export HBL List

All using ES5 JavaScript, AJAX operations, partial views, and mobile responsive design.

---

**Status**: Ready to implement
**Priority**: High
**Pattern**: Follow exact structure from completed views
