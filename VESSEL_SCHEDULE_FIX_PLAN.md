# Vessel Schedule List - Fix Plan

## URL: http://localhost:8000/ocean-export/vessel-schedule/list

## Current Issues
1. ❌ Using ES6 JavaScript (const, let, arrow functions, template literals, spread operators)
2. ❌ Using async/await
3. ❌ No mobile responsive CSS
4. ❌ updateGrid() uses DOM parsing instead of proper AJAX
5. ❌ Excel export reloads page

## Required Fixes (Follow Booking List Pattern)

### 1. Add Mobile Responsive CSS
Add after `@push('styles')`:

```css
<style>
    /* Mobile Responsive Enhancements */
    @media (max-width: 768px) {
        .page-content { padding: 2px !important; overflow-x: hidden !important; }
        .portlet.light { margin: 0 !important; border-radius: 0 !important; overflow: hidden !important; }
        
        .portlet-title { flex-direction: column !important; align-items: flex-start !important; padding: 6px !important; gap: 6px; }
        .portlet-title .caption { width: 100%; }
        .portlet-title .actions { width: 100%; flex-wrap: wrap; gap: 3px !important; }
        .btn-action-round { font-size: 9px !important; padding: 0 6px !important; height: 18px !important; }
        
        .portlet-tool { flex-direction: column !important; align-items: flex-start !important; padding: 6px !important; gap: 6px !important; }
        .portlet-tool > div, .portlet-tool > form { width: 100%; }
        .btn-group { width: 100%; justify-content: flex-start; flex-wrap: wrap; }
        .btn-tool { font-size: 8px !important; padding: 0 6px !important; height: 20px !important; flex: 0 1 auto; }
        .input-inline, .select-tool { width: 100% !important; font-size: 9px !important; }
        
        .portlet-body { padding: 0 !important; overflow: hidden !important; }
        .grid-container { width: 100% !important; overflow: hidden !important; }
        .grid-wrapper { 
            width: 100% !important;
            height: calc(100vh - 350px) !important;
            min-height: 200px !important;
            overflow-x: auto !important;
            overflow-y: auto !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        .grid-table { 
            font-size: 8px !important;
            width: auto !important;
            min-width: 2000px !important;
        }
        
        .grid-table th, .grid-table td { padding: 2px 4px !important; height: 22px !important; }
        
        /* Only 3 sticky columns on tablet */
        .sticky-col { font-size: 8px !important; }
        .grid-table th:nth-child(4), .grid-table td:nth-child(4) {
            position: static !important;
            left: auto !important;
        }
        
        .filter-input { height: 18px !important; font-size: 8px !important; }
        .portlet-tool.bottom { flex-direction: column !important; gap: 6px; }
        .portlet-tool.bottom > div { width: 100% !important; }
    }
    
    @media (max-width: 480px) {
        .grid-table { font-size: 7px !important; min-width: 1600px !important; }
        
        /* Only checkbox sticky on mobile */
        .grid-table th:nth-child(2), .grid-table td:nth-child(2),
        .grid-table th:nth-child(3), .grid-table td:nth-child(3) {
            position: static !important;
            left: auto !important;
        }
    }
    
    @media (hover: none) and (pointer: coarse) {
        .btn-tool, .btn-action-round {
            min-height: 28px !important;
            touch-action: manipulation;
        }
        .filter-input, .select-tool {
            min-height: 24px !important;
        }
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }
    }
</style>
```

### 2. Create Partial View
**File**: `resources/views/ocean-export/partials/vessel-schedule-list-rows.blade.php`

```php
@forelse($schedules as $s)
<tr id="schedule-row-{{ $s->id }}"
    data-id="{{ $s->id }}"
    data-schedule="{{ $s->schedule_no }}"
    onclick="rowClick(event, this)">
    
    <td class="sticky-col" style="left:0;width:25px;text-align:center;" onclick="event.stopPropagation()">
        <input type="checkbox" name="ids[]" value="{{ $s->id }}" class="row-check" onchange="updateToolbar()">
    </td>
    <td class="sticky-col" style="left:25px;" onclick="event.stopPropagation()">
        <a href="{{ route('vessel-schedules.edit', $s->id) }}" class="col-link" target="_blank">{{ $s->schedule_no ?? 'VS-' . $s->id }}</a>
    </td>
    <td class="sticky-col" style="left:185px;text-align:center;">
        <span class="color-mark" style="background:{{ $s->color ?? '#94a3b8' }}" onclick="event.stopPropagation();openColorPicker({{ $s->id }}, '{{ $s->color ?? '' }}')"></span>
    </td>
    <td class="sticky-col" style="left:213px;">{{ $s->customer->name ?? '--' }}</td>
    <td>{{ $s->office->code ?? '--' }}</td>
    <td>{{ $s->vessel->name ?? ($s->vessel_name ?? '--') }}</td>
    <td>{{ $s->voyage ?? '--' }}</td>
    <td>{{ $s->etd ? $s->etd->format('m-d-Y') : '--' }}</td>
    <td>{{ $s->eta ? $s->eta->format('m-d-Y') : '--' }}</td>
    <td>{{ $s->pol->name ?? ($s->pol_name ?? '--') }}</td>
    <td>{{ $s->pod->name ?? ($s->pod_name ?? '--') }}</td>
    <td>{{ $s->fdest->name ?? '--' }}</td>
    <td>{{ $s->por->name ?? '--' }}</td>
    <td>{{ $s->del->name ?? '--' }}</td>
    <td>{{ $s->carrier_bkg_no ?? '--' }}</td>
    <td>{{ $s->carrier->name ?? '--' }}</td>
    <td>{{ $s->overseaAgent->name ?? '--' }}</td>
    <td>{{ $s->forwardingAgent->name ?? ($s->shipping_agent ?? '--') }}</td>
    <td>{{ $s->op->name ?? '--' }}</td>
    <td>{{ $s->svcTermFrom->code ?? '--' }}</td>
    <td>{{ $s->svcTermTo->code ?? '--' }}</td>
    <td>{{ $s->cargo_type ?? '--' }}</td>
    <td>{{ $s->ship_mode ?? '--' }}</td>
    <td>{{ $s->post_date ? $s->post_date->format('m-d-Y') : '--' }}</td>
</tr>
@empty
<tr id="empty-row">
    <td colspan="50" style="text-align:center;padding:30px 10px;color:#94a3b8;">
        <i class="fa fa-inbox" style="font-size:32px;display:block;margin-bottom:12px;opacity:0.5;"></i>
        <div style="font-size:13px;font-weight:600;">No vessel schedules found</div>
        <div style="font-size:11px;margin-top:4px;">Try adjusting your filters or search criteria</div>
    </td>
</tr>
@endforelse
```

### 3. Update tbody to use partial
Replace:
```php
<tbody id="grid-body">
@forelse($schedules as $s)
    <!-- all the rows -->
@empty
    <!-- empty state -->
@endforelse
</tbody>
```

With:
```php
<tbody id="grid-body">
    @include('ocean-export.partials.vessel-schedule-list-rows')
</tbody>
```

### 4. Add Hidden Iframe for Excel
Before `@push('scripts')`:
```html
{{-- Hidden iframe for Excel download --}}
<iframe id="excel-frame" style="display:none;"></iframe>
```

### 5. Convert ALL JavaScript to ES5

**Replace all ES6 patterns**:
- `const` → `var`
- `let` → `var`
- `[...array]` → loops or Array.from()
- Arrow functions `=>` → `function() {}`
- Template literals → string concatenation
- `async/await` → `.then()` chains

**Key Functions to Rewrite**:

```javascript
// OLD (ES6)
const checked = [...document.querySelectorAll('.row-check:checked')];

// NEW (ES5)
var checked = document.querySelectorAll('.row-check:checked');
var checkedArray = [];
for (var i = 0; i < checked.length; i++) {
    checkedArray.push(checked[i]);
}
```

```javascript
// OLD (ES6)
async function updateGrid(url) {
    const response = await fetch(url);
    const text = await response.text();
    // ...
}

// NEW (ES5)
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
    .then(function(response) {
        if (!response.ok) {
            return response.json().then(function(err) {
                throw new Error(err.error || 'HTTP ' + response.status);
            });
        }
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            document.getElementById('grid-body').innerHTML = data.html;
            document.getElementById('pagination-container').innerHTML = data.pagination;
            document.getElementById('stat-first').textContent = data.first;
            document.getElementById('stat-last').textContent = data.last;
            document.getElementById('stat-total').textContent = data.total;
            updateToolbar();
        } else {
            showToast('error', data.message || 'Failed to refresh');
        }
    })
    .catch(function(err) {
        console.error(err);
        showToast('error', 'Failed to update grid');
    });
}
```

### 6. Fix Excel Export

```javascript
function exportExcel() {
    showToast('info', 'Preparing Excel export...');
    var baseUrl = '{{ route("vessel-schedules.export-csv") }}';  // Need to add this route
    var params = new URLSearchParams(window.location.search);
    var queryString = params.toString();
    var url = baseUrl + (queryString ? '?' + queryString : '');
    var iframe = document.getElementById('excel-frame');
    if (iframe) {
        iframe.src = url;
        setTimeout(function() {
            showToast('success', 'Excel file download started');
        }, 500);
    } else {
        showToast('error', 'Excel frame not found');
    }
}
```

### 7. Update Controller for AJAX

Add to `VesselScheduleController@index`:

```php
public function index(Request $request)
{
    // ... existing query code ...
    
    $schedules = $query->latest()->paginate(20)->withQueryString();
    
    // Return JSON for AJAX requests
    if ($request->ajax() || $request->wantsJson()) {
        try {
            $html = view('ocean-export.partials.vessel-schedule-list-rows', compact('schedules'))->render();
            $pagination = view('vendor.pagination.custom', ['paginator' => $schedules])->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => $pagination,
                'first' => $schedules->firstItem() ?? 0,
                'last' => $schedules->lastItem() ?? 0,
                'total' => $schedules->total(),
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

### 8. Add Export Route & Method

**routes/web.php**:
```php
Route::get('/ocean-export/vessel-schedule/export-csv', [VesselScheduleController::class, 'exportCsv'])->name('vessel-schedules.export-csv');
```

**VesselScheduleController**:
```php
public function exportCsv(Request $request)
{
    $query = VesselSchedule::with(['customer', 'office', 'vessel', 'pol', 'pod', 'fdest', 'por', 'del', 'carrier', 'overseaAgent', 'forwardingAgent', 'op', 'svcTermFrom', 'svcTermTo']);
    
    $this->applyFiltersToQuery($request, $query);
    
    $schedules = $query->latest()->get();
    
    $filename = 'vessel-schedules-' . date('Y-m-d-His') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];
    
    $callback = function() use ($schedules) {
        $file = fopen('php://output', 'w');
        
        fputcsv($file, [
            'Schedule No.', 'Customer', 'Office', 'Vessel', 'Voyage', 'ETD', 'ETA',
            'POL', 'POD', 'Final Dest', 'POR', 'DEL', 'Carrier Bkg No.', 'Carrier',
            'Oversea Agent', 'Fwd Agent', 'OP', 'Svc From', 'Svc To', 
            'Cargo Type', 'Ship Mode', 'Post Date'
        ]);
        
        foreach ($schedules as $s) {
            fputcsv($file, [
                $s->schedule_no ?? 'VS-' . $s->id,
                $s->customer->name ?? '',
                $s->office->code ?? '',
                $s->vessel->name ?? $s->vessel_name ?? '',
                $s->voyage ?? '',
                $s->etd ? $s->etd->format('m-d-Y') : '',
                $s->eta ? $s->eta->format('m-d-Y') : '',
                $s->pol->name ?? $s->pol_name ?? '',
                $s->pod->name ?? $s->pod_name ?? '',
                $s->fdest->name ?? '',
                $s->por->name ?? '',
                $s->del->name ?? '',
                $s->carrier_bkg_no ?? '',
                $s->carrier->name ?? '',
                $s->overseaAgent->name ?? '',
                $s->forwardingAgent->name ?? $s->shipping_agent ?? '',
                $s->op->name ?? '',
                $s->svcTermFrom->code ?? '',
                $s->svcTermTo->code ?? '',
                $s->cargo_type ?? '',
                $s->ship_mode ?? '',
                $s->post_date ? $s->post_date->format('m-d-Y') : '',
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}
```

## Implementation Priority

1. ✅ Add mobile CSS (copy from booking list)
2. ✅ Create partial view
3. ✅ Update tbody to use partial
4. ✅ Add hidden iframe
5. ✅ Convert ALL JavaScript to ES5
6. ✅ Fix updateGrid() to use proper AJAX
7. ✅ Fix exportExcel() to use iframe
8. ✅ Update controller for AJAX support
9. ✅ Add export route and method

## Testing Checklist

- [ ] Page loads without errors
- [ ] Quick search works with debounce
- [ ] Filter row toggles and applies
- [ ] Pagination works via AJAX
- [ ] Delete works
- [ ] Copy works
- [ ] Color picker works
- [ ] Excel downloads without page reload
- [ ] Mobile responsive (sticky columns reduce)
- [ ] No console errors
- [ ] All operations via AJAX (no hard refreshes)

---

**Pattern**: Exact match with booking list
**Files to Modify**: 3 (view, partial, controller, routes)
**Expected Time**: 1-2 hours
