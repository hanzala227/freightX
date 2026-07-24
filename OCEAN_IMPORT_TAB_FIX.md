# Ocean Import Tab Navigation Fix

## Issue
When navigating to Ocean Import create/edit pages, the system was remembering the last active tab from sessionStorage, causing users to land on different tabs (Charges, Containers, etc.) instead of always starting on the Main tab.

## Changes Made

### 1. Removed Tab Persistence in Ocean Import
**File**: `resources/views/ocean-import/index.blade.php`

- **Line 34**: Changed `activeTab` initialization from:
  ```javascript
  activeTab: sessionStorage.getItem('oceanImportActiveTab') || 'basic',
  ```
  To:
  ```javascript
  activeTab: 'basic', // Always start on Main tab
  ```

- **Line 362**: Removed the watcher that saved tab state to sessionStorage:
  ```javascript
  // REMOVED: this.$watch('activeTab', val => sessionStorage.setItem('oceanImportActiveTab', val));
  ```

### 2. Verified Other Views
- **Air Import** (`resources/views/air-import/index.blade.php`): Already defaults to 'basic' tab ✓
- **Ocean Export** (`resources/views/ocean-export/index.blade.php`): Already defaults to 'basic' tab ✓

## Result
✅ Ocean Import now ALWAYS opens on the Main tab when you navigate to create or edit pages
✅ No tab state is persisted across page loads
✅ Consistent behavior across all import/export views

## Testing
Test these URLs to verify they all open on the Main tab:
- `http://localhost:8000/ocean-import/create`
- `http://localhost:8000/ocean-import/24/edit`
- `http://localhost:8000/ocean-import/create?copy=33`
- Any other Ocean Import create/edit URLs
