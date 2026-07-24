# Missing Import Fix - OceanExportHbl

## Issue
Internal Server Error when accessing: `http://localhost:8000/ocean-export/create?copy_hbl=1&shipment_id=1`

**Error Message**:
```
Class "App\Http\Controllers\OceanExportHbl" not found
Location: app/Http/Controllers/OceanExportController.php:174
```

## Root Cause
The `OceanExportHbl` model was being used in the `OceanExportController@create` method (line 174) but was not imported at the top of the file.

**Code using the class**:
```php
// Line 174 in OceanExportController.php
$hbl = OceanExportHbl::with(['customer', 'shipper', 'consignee', 'notifyParty', 'pod', 'del', 'fdest', 'receipt'])->find($hblId);
```

## Solution Applied

### ✅ Added Missing Import Statement
**File**: `app/Http/Controllers/OceanExportController.php` (Line 6)

**Before**:
```php
<?php

namespace App\Http\Controllers;

use App\Models\OceanExport;
use App\Models\Office;
use App\Models\Port;
// ... other imports
```

**After**:
```php
<?php

namespace App\Http\Controllers;

use App\Models\OceanExport;
use App\Models\OceanExportHbl;  // ← Added this import
use App\Models\Office;
use App\Models\Port;
// ... other imports
```

## Impact
- ✅ **No Breaking Changes**: Only added a missing import
- ✅ **Fix Verified**: Import statement now present at line 6
- ✅ **Feature Restored**: Copy HBL functionality will now work

## Feature Context
This import is needed for the "Copy HBL" feature which allows users to:
1. View an HBL in the HBL list
2. Click to copy it
3. Create a new ocean export shipment based on that HBL
4. URL pattern: `/ocean-export/create?copy_hbl={hbl_id}&shipment_id={shipment_id}`

## Testing
To verify the fix works:
1. Visit: http://localhost:8000/ocean-export/list/hbl
2. Select an HBL row
3. Click any copy/duplicate button
4. Should open create page without error
5. Form should be pre-filled with HBL data

---

**Status**: ✅ **FIXED**
**Date**: 2026-07-24
**Files Modified**: 1 (OceanExportController.php)
**Lines Changed**: 1 (added import statement)
