# Ocean Export Form - Debug Instructions

## Current Status
I've updated all form fields to use `getFormValue()` which should preserve values after validation errors. However, you're reporting it's still clearing all inputs.

## Debug Steps

### Step 1: Check Browser Console
1. Open http://localhost:8000/ocean-export/create
2. Open Browser DevTools (F12) → Console tab
3. Look for this debug output:
   ```
   === OCEAN EXPORT FORM DEBUG ===
   Old input data: {...}
   Has old data? true/false
   ```

### Step 2: Test with Duplicate MBL No
1. Fill in the form with test data:
   - File No: TEST-001
   - MBL No: (use an existing one to trigger duplicate error)
   - Customer: Select any
   - Office: Select any
   - Add some data in various fields

2. Click **Save** button

3. **IMPORTANT:** Check console output again after page reloads
   - If `Has old data? true` → The old() data IS being preserved by Laravel
   - If `Has old data? false` → Laravel is NOT preserving the data (session issue)

### Step 3: Check Network Tab
1. Open DevTools → Network tab
2. Submit the form
3. Click on the POST request to `/ocean-export`
4. Check:
   - **Status Code**: Should be `302` (redirect)
   - **Response Headers**: Should contain `Location: ...` (redirect URL)
   - **Form Data tab**: Shows what was submitted

### Step 4: Check if Form is Submitting via AJAX
Look in the console for any fetch/axios/jQuery AJAX calls. If the form is being submitted via AJAX instead of normal form submission, that would explain why old() doesn't work.

## Possible Issues & Solutions

### Issue 1: Session Not Working
**Symptom:** Console shows `Has old data? false` even after submitting with errors

**Solution:** Check `.env` file:
```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

Clear session cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan session:table  # If using database sessions
```

### Issue 2: Form Submitting via AJAX
**Symptom:** Form doesn't actually reload page, errors appear without page refresh

**Solution:** Need to modify JavaScript to preserve form data manually or use normal form submission

### Issue 3: Alpine.js Reinitializing Form
**Symptom:** Form data appears for a split second then disappears

**Solution:** This could be an Alpine.js issue. Check if there's any `x-init` or lifecycle hooks clearing data.

### Issue 4: Browser Cache
**Symptom:** Using old cached JavaScript that doesn't have the fix

**Solution:** Hard refresh browser (Ctrl+Shift+R) or use Incognito mode

## What I've Changed

### 1. Updated getFormValue() Function
```javascript
const oldInputData = @json(request()->old());

const getFormValue = (field, defaultValue = '') => {
    if (oldInputData && oldInputData[field] !== undefined && oldInputData[field] !== null) {
        return oldInputData[field];
    }
    return defaultValue;
};
```

### 2. All 73+ Fields Now Use getFormValue()
```javascript
form: {
    file_no: getFormValue('file_no', @json(...)),
    mbl_no: getFormValue('mbl_no', @json(...)),
    // ... all other fields
}
```

### 3. Arrays Also Use getFormValue()
```javascript
hbls: getFormValue('hbls', @json(...)),
containers: getFormValue('containers', @json(...)),
```

## Next Steps - Please Provide

After testing, please provide:

1. **Console Output:** What does the debug message show?
   - `Has old data? true` or `false`?
   - If true, what fields are in `oldInputData`?

2. **Network Tab:** 
   - Is the form doing a normal POST or AJAX?
   - What's the response status code?

3. **Behavior:**
   - Do fields flash with data then disappear?
   - Or are they empty immediately?

4. **Specific Fields:**
   - Are ALL fields empty or just some?
   - What about HBLs and containers?

With this information, I can identify the exact issue and provide the correct fix.
