# Container List - Inline Editable Cells Implementation Plan

## Current State
- URL: `http://localhost:8000/ocean-import/list/containers`
- View: `resources/views/ocean-import/containers.blade.php`
- Rows Partial: `resources/views/ocean-import/partials/container-list-rows.blade.php`
- Currently displays static text in most cells
- Only "Remarks" field is currently editable

## Goal
Make ALL cells editable with:
1. ✅ Input fields instead of static text
2. ✅ Save button appears only when fields are changed
3. ✅ Filter functionality remains working
4. ✅ Similar to demo screenshots provided

---

## Implementation Steps

### **Step 1: Add AlpineJS for State Management**

Add to `containers.blade.php` head section:
```html
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

### **Step 2: Create Alpine Component**

Wrap the table in Alpine component:
```javascript
<div x-data="containerGrid()">
    <!-- Table here -->
    <!-- Save button -->
    <div x-show="hasChanges" class="save-bar">
        <button @click="saveChanges()">
            <i class="fa fa-save"></i> Save Changes
        </button>
    </div>
</div>

<script>
function containerGrid() {
    return {
        containers: @json($containers),
        changedRows: {},
        hasChanges: false,
        
        markChanged(containerId, field, value) {
            if (!this.changedRows[containerId]) {
                this.changedRows[containerId] = {};
            }
            this.changedRows[containerId][field] = value;
            this.hasChanges = Object.keys(this.changedRows).length > 0;
        },
        
        async saveChanges() {
            const response = await fetch('/ocean-import/containers/batch-update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    containers: this.changedRows
                })
            });
            
            if (response.ok) {
                this.changedRows = {};
                this.hasChanges = false;
                showToast('success', 'Changes saved successfully!');
            }
        }
    }
}
</script>
```

### **Step 3: Convert Static Cells to Editable Inputs**

In `container-list-rows.blade.php`, convert cells like:

**BEFORE:**
```html
<td>{{ $c->container_no ?? 'N/A' }}</td>
```

**AFTER:**
```html
<td>
    <input type="text" 
           class="cell-input" 
           value="{{ $c->container_no ?? '' }}" 
           @input="markChanged({{ $c->id }}, 'container_no', $event.target.value)"
           placeholder="-">
</td>
```

### **Step 4: Handle Different Field Types**

#### **Text Fields:**
```html
<td>
    <input type="text" class="cell-input" 
           value="{{ $c->seal_no ?? '' }}"
           @input="markChanged({{ $c->id }}, 'seal_no', $event.target.value)">
</td>
```

#### **Date Fields:**
```html
<td>
    <input type="date" class="cell-input" 
           value="{{ $c->lfd ? $c->lfd->format('Y-m-d') : '' }}"
           @input="markChanged({{ $c->id }}, 'lfd', $event.target.value)">
</td>
```

#### **Number Fields:**
```html
<td>
    <input type="number" class="cell-input" step="0.01"
           value="{{ $c->pkg_qty ?? '' }}"
           @input="markChanged({{ $c->id }}, 'pkg_qty', $event.target.value)">
</td>
```

#### **Select Fields (for dropdowns like Trucker):**
```html
<td>
    <select class="cell-select"
            @change="markChanged({{ $c->id }}, 'trucker_id', $event.target.value)">
        <option value="">Select...</option>
        @foreach($truckers as $trucker)
            <option value="{{ $trucker->id }}" {{ $c->trucker_id == $trucker->id ? 'selected' : '' }}>
                {{ $trucker->name }}
            </option>
        @endforeach
    </select>
</td>
```

#### **Checkbox Fields:**
```html
<td style="text-align:center;">
    <input type="checkbox" 
           {{ $c->is_dg ? 'checked' : '' }}
           @change="markChanged({{ $c->id }}, 'is_dg', $event.target.checked)">
</td>
```

### **Step 5: Add CSS Styling**

Add to `containers.blade.php`:
```css
<style>
.cell-input, .cell-select {
    width: 100%;
    border: 1px solid #e2e8f0;
    padding: 4px 6px;
    font-size: 11px;
    border-radius: 3px;
    background: white;
}

.cell-input:focus, .cell-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

.cell-input[data-changed="true"] {
    background-color: #fef3c7;
    border-color: #f59e0b;
}

.save-bar {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #22c55e;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 1000;
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        transform: translateY(100px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.save-bar button {
    background: transparent;
    border: none;
    color: white;
    font-weight: 600;
    cursor: pointer;
    font-size: 14px;
}
</style>
```

### **Step 6: Create Backend Endpoint**

Add route in `routes/web.php`:
```php
Route::post('/ocean-import/containers/batch-update-inline', 
    [OceanImportController::class, 'batchUpdateInline'])
    ->name('ocean-import.containers.batch-update-inline');
```

Add method in `OceanImportController.php`:
```php
public function batchUpdateInline(Request $request)
{
    $containers = $request->input('containers', []);
    
    foreach ($containers as $containerId => $fields) {
        $container = OceanImportContainer::find($containerId);
        if ($container) {
            $container->update($fields);
        }
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Containers updated successfully'
    ]);
}
```

---

## Fields to Make Editable

### **Container Fields:**
- ✅ Container No.
- ✅ PP/CTF
- ✅ TP/SZ (Container Type - select)
- ✅ Seal No.
- ✅ Seal No. 2
- ✅ LFD (date)
- ✅ FDD (date)
- ✅ PKG (number)
- ✅ Weight(KG) (number)
- ✅ Weight(LB) (number)
- ✅ Meas(CBM) (number)
- ✅ Meas(CFT) (number)
- ✅ D.G (checkbox)
- ✅ Unload Vessel (date)
- ✅ Gate In (date)
- ✅ Rail Start (date)
- ✅ P.O.D ETA (date)
- ✅ Appt. (date)
- ✅ Pick Up (date)
- ✅ Gate Out (date)
- ✅ F.Dest ETA (date)
- ✅ ETA Door (date)
- ✅ ATA Door (date)
- ✅ Empty Conf. (date)
- ✅ Empty Ret. (date)
- ✅ Storage Start (date)
- ✅ Storage End (date)
- ✅ Pick No. (text)
- ✅ CPRS No. (text)
- ✅ CNRU No. (text)
- ✅ Carrier Rel. (checkbox)
- ✅ Yard Location (text)
- ✅ Avail Pickup (checkbox)
- ✅ Trucker (select)
- ✅ Chassis Days (number)
- ✅ C.Hold (checkbox)
- ✅ Remarks (already editable)
- ✅ Complete (checkbox)

### **Read-Only Fields (Keep as static):**
- File No. (link)
- Consignee
- Ship Mode / Type
- HB/L No.
- Location
- ETD/ETA (from MBL)
- MBL fields (all read-only)

---

## Testing Checklist

1. ✅ All input fields are editable
2. ✅ Save button appears when any field is changed
3. ✅ Save button disappears after successful save
4. ✅ Changes are persisted to database
5. ✅ Filter functionality still works
6. ✅ Column config still works
7. ✅ No performance issues with many rows
8. ✅ Mobile responsive

---

## Benefits

1. **Quick Edits**: Users can edit directly in the list without opening individual records
2. **Batch Updates**: Multiple containers can be edited and saved at once
3. **Visual Feedback**: Changed fields are highlighted
4. **User-Friendly**: Save button only appears when needed
5. **Efficient**: No page reload needed

---

## Similar to Demo Screenshots

Your screenshots show:
- ✅ Editable inputs in cells
- ✅ Filter row at top
- ✅ Clean table layout
- ✅ Multiple columns

This implementation will match that exactly!

---

## Next Steps

Would you like me to:
1. Implement this complete solution now?
2. Start with a subset of fields first?
3. Add additional features like undo/redo?

Let me know and I'll proceed with the full implementation!
