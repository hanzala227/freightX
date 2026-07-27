# Visual Guide: Air Export Accounting Buttons

## 📍 Location
**Route**: `http://localhost:8000/air-export/{id}/edit`  
**Tab**: Accounting

---

## 🎯 The Three Buttons

```
┌────────────────────────────────────────────────────────────────────┐
│  ACCOUNTING TAB - MAWB Section                                     │
├────────────────────────────────────────────────────────────────────┤
│                                                                    │
│  [➕ Origin Revenue (Invoice/AR) ▼]                               │
│  [➕ Destination Revenue/Cost (D/C Note) ▼]                       │
│  [➕ Origin Cost (AP) ▼]                                          │
│                                                                    │
│  □ Include Draft Amount                                            │
│                                                                    │
└────────────────────────────────────────────────────────────────────┘
```

---

## 🔄 User Flow Diagram

### Scenario 1: Unsaved Shipment (Create Page)

```
User visits:
http://localhost:8000/air-export/create
         │
         │ Clicks Accounting Tab
         ▼
┌─────────────────────────┐
│   Accounting Tab        │
│                         │
│   [Origin Revenue]      │◄──── User clicks
│   [Destination Rev/Cost]│
│   [Origin Cost]         │
└─────────────────────────┘
         │
         ▼
┌─────────────────────────┐
│   🔴 Toast Error        │
│   "Please save the      │
│   shipment first"       │
└─────────────────────────┘
         │
         ▼
   No action taken
   (stays on same page)
```

### Scenario 2: Saved Shipment (Edit Page)

```
User visits:
http://localhost:8000/air-export/4/edit
         │
         │ Clicks Accounting Tab
         ▼
┌─────────────────────────┐
│   Accounting Tab        │
│                         │
│   [Origin Revenue]      │◄──── User clicks "Origin Revenue"
│   [Destination Rev/Cost]│
│   [Origin Cost]         │
└─────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────────────┐
│  ✅ Validation Passed: Shipment ID = 4          │
└─────────────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────────────┐
│  🆕 NEW TAB OPENS:                              │
│                                                 │
│  http://localhost:8000/accounting/invoice/      │
│  create?type=AR&shipment_type=air_export&       │
│  shipment_id=4                                  │
└─────────────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────────────┐
│  Invoice Creation Form                          │
│  ┌────────────────────────────────────────────┐ │
│  │ Invoice No: INV-260127120000               │ │
│  │ Type: [AR ▼] ◄── Pre-selected             │ │
│  │ Invoice Date: 2026-01-27                   │ │
│  │ Bill To: [Select Customer ▼]               │ │
│  └────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────────────┐
│  Original Tab Still Open:                       │
│  Air Export Edit Page (no changes lost)         │
└─────────────────────────────────────────────────┘
```

---

## 📊 Button Actions Comparison

| Action | Origin Revenue | Destination Rev/Cost | Origin Cost |
|--------|---------------|---------------------|-------------|
| **Button Color** | 🔵 Teal (#32c5d2) | 🔵 Teal (#32c5d2) | 🔵 Teal (#32c5d2) |
| **Alpine Function** | `createInvoice('revenue')` | `createInvoice('dc_note')` | `createInvoice('cost')` |
| **Invoice Type** | AR | DC | AP |
| **Full Form** | Accounts Receivable | Debit/Credit Note | Accounts Payable |
| **Purpose** | Origin revenue invoices | Destination charges | Origin cost/expense |
| **Opens New Tab** | ✅ Yes | ✅ Yes | ✅ Yes |
| **Validates Saved** | ✅ Yes | ✅ Yes | ✅ Yes |
| **URL Parameter** | `type=AR` | `type=DC` | `type=AP` |

---

## 🔍 Behind the Scenes

### When You Click a Button:

```javascript
// 1️⃣ Button clicked
<button @click.prevent="createInvoice('revenue')">

// 2️⃣ Alpine.js executes function
createInvoice(type) {
    // 3️⃣ Check if saved
    if (!shipmentId) {
        showToast('error', 'Please save...');
        return; // ❌ Stop here
    }
    
    // 4️⃣ Build URL with parameters
    const url = `/accounting/invoice/create?` +
                `type=AR&` +
                `shipment_type=air_export&` +
                `shipment_id=123`;
    
    // 5️⃣ Open in new tab
    window.open(url, '_blank'); // ✅ New tab opens
}
```

---

## 🎨 Visual State Indicators

### Before Clicking (Normal State)
```
┌───────────────────────────────────────┐
│  [➕ Origin Revenue (Invoice/AR) ▼]  │ ← Teal background, white text
└───────────────────────────────────────┘
      Cursor: pointer (hand icon)
      Hover: Slight opacity change
```

### On Click (Processing)
```
┌───────────────────────────────────────┐
│  [➕ Origin Revenue (Invoice/AR) ▼]  │ ← Brief flash
└───────────────────────────────────────┘
           ↓
    Validation runs...
           ↓
    New tab opens ✨
```

### Error State (Unsaved Shipment)
```
┌─────────────────────────────────────┐
│  🔴 Toast Notification              │
│  ⚠️ Please save the shipment first  │
│     before creating invoices        │
└─────────────────────────────────────┘
         (Auto-dismiss after 3-5 seconds)
```

---

## 📱 Multi-Tab Workflow

```
TAB 1 (Original)               TAB 2 (New)
┌─────────────────┐           ┌─────────────────┐
│ Air Export Edit │           │ Invoice Create  │
│                 │           │                 │
│ [Save Changes]  │           │ [Save Invoice]  │
│                 │           │                 │
│ (Accounting Tab)│           │ Type: AR ✓      │
│                 │           │                 │
│ ✅ Still Active │           │ ⬅️ Working here  │
└─────────────────┘           └─────────────────┘
      ↑                               ↑
      │                               │
   No refresh                    Fresh form
   No data loss                  Pre-populated
```

**User Can**:
- ✅ Keep Air Export tab open
- ✅ Create multiple invoices (open multiple new tabs)
- ✅ Switch between tabs freely
- ✅ Close invoice tab and return to shipment
- ✅ Continue editing shipment without losing work

---

## 🧪 Quick Visual Test

### ✅ SUCCESS Indicators:
- [ ] Clicking button does NOT refresh page
- [ ] No loading spinner on Air Export page
- [ ] New browser tab opens
- [ ] New tab URL contains `?type=AR` (or DC/AP)
- [ ] Invoice form shows with correct type selected
- [ ] Can close invoice tab and return to shipment
- [ ] Shipment data unchanged after returning

### ❌ FAILURE Indicators:
- [ ] Page refreshes when clicking button
- [ ] Form submits (see "loading" screen)
- [ ] Nothing happens (no new tab)
- [ ] JavaScript error in console (F12)
- [ ] Invoice form shows wrong type
- [ ] Lose unsaved data in shipment

---

## 🎯 Expected Results Summary

| Test Case | Expected Visual Result |
|-----------|----------------------|
| Click on unsaved shipment | 🔴 Red error toast appears, no tab opens |
| Click on saved shipment | 🆕 New tab opens immediately |
| Check new tab URL | URL contains `type=AR` (or DC/AP) |
| Check invoice form | Type dropdown shows correct selection |
| Return to original tab | All data intact, no changes |
| Submit shipment form | Should still work normally |

---

## 🖼️ Tab Bar Visual

```
Before Click:
[Air Export Edit #4 - localhost:8000]

After Click:
[Air Export Edit #4 - localhost:8000] [New Invoice - localhost:8000] ← Active
                    ↑                              ↑
              Original tab                    New tab (focus)
            (stays open)                    (invoice form)
```

---

## 💡 User Experience Flow

```
1. User edits air export shipment
   └─ Makes changes to fields
   
2. Clicks "Accounting" tab
   └─ Sees three invoice buttons
   
3. Clicks "Origin Revenue (Invoice/AR)"
   └─ Instant feedback (new tab opens)
   
4. New tab shows invoice form
   └─ Type already set to AR
   └─ Ready to add line items
   
5. User creates invoice
   └─ Saves invoice
   
6. Closes invoice tab
   └─ Returns to shipment tab
   └─ Continues working on shipment
   
7. Saves shipment
   └─ Both shipment and invoice saved independently
```

---

## 🎬 Animation Sequence

```
[User Action]       [System Response]           [User Sees]
─────────────────────────────────────────────────────────────
Click button   →    Validate shipment    →    (Instant)
                    ↓
                    Create URL          →    (Milliseconds)
                    ↓
                    window.open()       →    New tab appears
                    ↓
                    Load invoice page   →    Form renders
                    ↓
                    Set type=AR         →    Dropdown selected
                    ↓
                    Ready for input     →    ✅ User can work
```

**Total Time**: < 1 second from click to form ready

---

## ✨ Key Features Visualized

### 1. Non-Disruptive
```
Before Click:                    After Click:
Shipment [X unsaved changes]     Shipment [X unsaved changes] ← Still there!
                                 Invoice [Empty form] ← New tab
```

### 2. Context Preservation
```
URL Parameters Passed:
┌─────────────────────────────────────────┐
│ type = AR                               │ ← Invoice type
│ shipment_type = air_export              │ ← Module context
│ shipment_id = 123                       │ ← Specific shipment
└─────────────────────────────────────────┘
```

### 3. Type Safety
```
Button Click → Alpine Function → URL Build → New Tab
     ↓              ↓               ↓           ↓
'revenue'      validate()      type=AR     AR selected ✓
'dc_note'      validate()      type=DC     DC selected ✓
'cost'         validate()      type=AP     AP selected ✓
```

---

**Visual Guide Complete** ✅

For testing instructions, see: `ACCOUNTING_BUTTONS_TEST_GUIDE.md`  
For technical details, see: `AIR_EXPORT_ACCOUNTING_BUTTONS_COMPLETE.md`  
For task summary, see: `TASK_SUMMARY.md`
