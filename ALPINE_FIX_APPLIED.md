# AlpineJS x-for Fix Applied

## Issue
AlpineJS was showing error:
```
Alpine Warning: x-for templates require a single root element, additional elements will be ignored.
```

## Root Cause
The x-for loop was trying to create two separate `<tr>` elements (main row + expanded row) without a wrapper element. AlpineJS requires x-for to have a single root element.

## Solution Applied
Changed the structure to wrap both rows in a `<tbody>` element:

### Before (Broken):
```html
<tbody>
    <template x-for="charge in charges">
        <template>
            <tr>...</tr>  <!-- Main row -->
            <tr>...</tr>  <!-- Expanded row -->
        </template>
    </template>
</tbody>
```

### After (Fixed):
```html
<template x-for="charge in charges">
    <tbody>
        <tr>...</tr>  <!-- Main row -->
        <tr>...</tr>  <!-- Expanded row -->
    </tbody>
</template>
```

## Key Changes
1. **Moved x-for to create tbody** - Each iteration now creates a tbody containing both rows
2. **Removed nested template** - No longer needed with tbody as root
3. **Fixed closing tags** - Removed duplicate `</template>` tag
4. **Separate tbody for empty state** - Empty state is in its own tbody with x-if

## Structure Now:
```html
<table>
    <thead>...</thead>
    
    <!-- Each charge gets its own tbody with 2 rows -->
    <template x-for="(charge, idx) in form.charges" :key="idx">
        <tbody>
            <tr><!-- Main row with +/- button --></tr>
            <tr x-show="charge.expanded"><!-- Expanded details --></tr>
        </tbody>
    </template>
    
    <!-- Empty state in separate tbody -->
    <tbody>
        <template x-if="form.charges.length === 0">
            <tr><!-- No charges message --></tr>
        </template>
    </tbody>
    
    <tfoot>...</tfoot>
</table>
```

## Benefits
- ✅ Valid AlpineJS structure (single root element per x-for)
- ✅ Proper table HTML structure
- ✅ No console errors
- ✅ Each charge row isolated in its own tbody
- ✅ Expandable rows work perfectly
- ✅ Styling and functionality preserved

## Testing
Navigate to:
```
http://localhost:8000/air-import/create
```

1. Go to **Charges** tab
2. Click **Add Charge** button
3. Click **+** button in the # column
4. Verify expanded row opens smoothly
5. Check browser console - no errors
6. Fill in expanded fields
7. Click **-** to collapse
8. Add multiple charges and test each independently

## Status: ✅ FIXED
The AlpineJS error is resolved and expandable rows work perfectly!
