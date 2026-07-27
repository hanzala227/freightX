# Work Order Tab - Visual Guide

## 📍 Location
**Route**: `http://localhost:8000/air-export/{id}/edit`  
**Tab**: Work Order (3rd tab)

---

## 🎨 Visual Layout

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│ MAWB: MAE-20260127001                                         [⟳] [🔧 Tools ▼]    │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                     │
│  [➕ New Work Order]  [🗑 Delete Selected]           5 work order(s) | 2 selected │
│                                                                                     │
├──────┬───────────┬────────────┬──────────────┬──────────────┬─────────┬──────────┤
│ [ ]  │ W/O No.   │ Subject    │ Freight      │ Delivery     │ Vendor/ │ Actions  │
│      │           │            │ Pickup       │              │ Trucker │          │
├──────┼───────────┼────────────┼──────────────┼──────────────┼─────────┼──────────┤
│ [✓]  │ WO-001    │ PICKUP &   │ JFK Airport  │ LAX Airport  │ ABC     │ [✎] [🗑] │
│      │           │ DELIVERY   │ 2026-01-28   │ 2026-01-30   │ Truck   │          │
├──────┼───────────┼────────────┼──────────────┼──────────────┼─────────┼──────────┤
│ [ ]  │ WO-002    │ EXPORT     │ ORD Airport  │ DFW Airport  │ XYZ     │ [✎] [🗑] │
│      │           │ ORDER      │ 2026-01-29   │ 2026-01-31   │ Haul    │          │
├──────┼───────────┼────────────┼──────────────┼──────────────┼─────────┼──────────┤
│ [✓]  │ WO-003    │ CONTAINER  │ LAX Airport  │ SFO Airport  │ Fast    │ [✎] [🗑] │
│      │           │ PICKUP     │ 2026-01-30   │ 2026-02-01   │ Cargo   │          │
└──────┴───────────┴────────────┴──────────────┴──────────────┴─────────┴──────────┘

                          ┌──────────────┐
                          │ + Add HAWB   │
                          ├──────────────┤
                          │ HAWB #1      │
                          │ HAWB-001     │
                          ├──────────────┤
                          │ HAWB #2      │
                          │ HAWB-002     │
                          └──────────────┘
```

---

## 🔄 State Visualizations

### 1. Loading State
```
┌─────────────────────────────────────────┐
│                                         │
│              ⟳ (spinning)               │
│                                         │
│         Loading work orders...          │
│                                         │
└─────────────────────────────────────────┘
```

### 2. Empty State
```
┌─────────────────────────────────────────┐
│                                         │
│                📥                       │
│         (semi-transparent)              │
│                                         │
│       No work orders found.             │
│   Click "New Work Order" to create one. │
│                                         │
└─────────────────────────────────────────┘
```

### 3. Selected Row
```
┌──────┬───────────┬────────────┬─────────┐
│ [✓]  │ WO-001    │ PICKUP &   │ [✎] [🗑]│ ← Blue highlight
│      │           │ DELIVERY   │         │   (#f0f8ff)
└──────┴───────────┴────────────┴─────────┘
```

### 4. All Selected
```
┌──────┬───────────┬────────────┐
│ [✓]  │ W/O No.   │ Subject    │ ← Header checkbox checked
├──────┼───────────┼────────────┤
│ [✓]  │ WO-001    │ P&D ORDER  │ ← All rows highlighted
│ [✓]  │ WO-002    │ EXPORT     │
│ [✓]  │ WO-003    │ CONTAINER  │
└──────┴───────────┴────────────┘

Counter: "3 work order(s) | 3 selected"
```

---

## 🎬 User Interaction Flows

### Flow 1: Creating a Work Order

```
Step 1: User on Air Export Edit Page
┌──────────────────────────────────┐
│ [Basic] [Accounting] [Work Order]│ ← User clicks
└──────────────────────────────────┘

Step 2: Work Order Tab Opens
┌─────────────────────────────────┐
│ [➕ New Work Order] [🗑 Delete] │ ← User clicks
└─────────────────────────────────┘

Step 3: Validation Check
┌─────────────────────────────────┐
│ ✓ Shipment ID exists: 4         │
│ ✓ MAWB No: MAE-20260127001      │
│ ✓ File No: MAE-20260127001      │
└─────────────────────────────────┘

Step 4: New Tab Opens
┌─────────────────────────────────────────────────────┐
│ 🆕 NEW TAB                                          │
│ URL: /ocean-export/work-order/create?               │
│      workable_type=air_export&                      │
│      workable_id=4&                                 │
│      mbl_no=MAE-20260127001&                        │
│      file_no=MAE-20260127001                        │
└─────────────────────────────────────────────────────┘

Step 5: Work Order Form
┌─────────────────────────────────┐
│ Work Order No: WO-20260127001   │
│ Subject: PICKUP & DELIVERY ORDER│
│ Vendor: [Select Trucker ▼]     │
│ Freight Pickup: [Select ▼]     │
│ [Save Work Order]               │
└─────────────────────────────────┘

Step 6: User Saves & Closes Tab

Step 7: Auto-Refresh (2 seconds)
┌─────────────────────────────────┐
│      ⟳ (brief spinner)          │
└─────────────────────────────────┘

Step 8: New Work Order Appears
┌──────┬───────────┬────────────┐
│ [ ]  │ WO-001    │ P&D ORDER  │ ← NEW!
└──────┴───────────┴────────────┘
```

---

### Flow 2: Deleting a Work Order

```
Step 1: Work Order List
┌──────┬───────────┬────────────┬─────────┐
│ [ ]  │ WO-001    │ P&D ORDER  │ [✎] [🗑]│ ← User clicks trash
└──────┴───────────┴────────────┴─────────┘

Step 2: Confirmation Dialog
┌─────────────────────────────────────────┐
│ ⚠️  Confirm Deletion                    │
│                                         │
│ Are you sure you want to delete         │
│ this work order?                        │
│                                         │
│         [Cancel]  [Delete]              │ ← User clicks Delete
└─────────────────────────────────────────┘

Step 3: AJAX Request
┌─────────────────────────────────────────┐
│ DELETE /ocean-export/work-order/1       │
│ Headers: X-CSRF-TOKEN: abc123...        │
│ Status: 200 OK                          │
└─────────────────────────────────────────┘

Step 4: Success Toast
┌─────────────────────────────────────────┐
│ ✅ Work order deleted successfully      │ ← Toast notification
└─────────────────────────────────────────┘

Step 5: List Refreshes (AJAX)
┌─────────────────────────────────────────┐
│ GET /api/work-orders?...                │
│ Status: 200 OK                          │
└─────────────────────────────────────────┘

Step 6: Work Order Removed
┌──────┬───────────┬────────────┬─────────┐
│ [ ]  │ WO-002    │ EXPORT     │ [✎] [🗑]│ ← WO-001 gone
│ [ ]  │ WO-003    │ CONTAINER  │ [✎] [🗑]│
└──────┴───────────┴────────────┴─────────┘

✅ NO PAGE REFRESH OCCURRED!
```

---

### Flow 3: Bulk Delete

```
Step 1: Select Multiple Work Orders
┌──────┬───────────┬────────────┐
│ [✓]  │ WO-001    │ P&D ORDER  │ ← Selected
│ [ ]  │ WO-002    │ EXPORT     │
│ [✓]  │ WO-003    │ CONTAINER  │ ← Selected
│ [✓]  │ WO-004    │ DELIVERY   │ ← Selected
└──────┴───────────┴────────────┘

Counter: "4 work order(s) | 3 selected"

Step 2: Click Delete Selected
┌─────────────────────────────────┐
│ [🗑 Delete Selected]             │ ← User clicks
└─────────────────────────────────┘

Step 3: Confirmation
┌─────────────────────────────────────────┐
│ ⚠️  Bulk Delete Confirmation            │
│                                         │
│ Are you sure you want to delete         │
│ 3 work order(s)?                        │
│                                         │
│         [Cancel]  [Delete All]          │ ← User confirms
└─────────────────────────────────────────┘

Step 4: Multiple AJAX Requests
┌─────────────────────────────────┐
│ DELETE /ocean-export/work-order/1│ ✓ 200 OK
│ DELETE /ocean-export/work-order/3│ ✓ 200 OK
│ DELETE /ocean-export/work-order/4│ ✓ 200 OK
└─────────────────────────────────┘

Step 5: Success Toast
┌─────────────────────────────────────────┐
│ ✅ 3 work order(s) deleted successfully │
└─────────────────────────────────────────┘

Step 6: List Updated
┌──────┬───────────┬────────────┐
│ [ ]  │ WO-002    │ EXPORT     │ ← Only one remains
└──────┴───────────┴────────────┘

Counter: "1 work order(s)"
✅ Selection cleared automatically
```

---

## 🎨 Button States

### "New Work Order" Button

**Normal State:**
```
┌──────────────────────┐
│ ➕ New Work Order    │ ← Teal background (#32c5d2)
└──────────────────────┘   White text
                           Cursor: pointer
```

**Hover:**
```
┌──────────────────────┐
│ ➕ New Work Order    │ ← Slightly darker
└──────────────────────┘   Cursor: pointer
```

**On Create Page (Disabled):**
```
┌──────────────────────┐
│ ➕ New Work Order    │ ← Shows error toast
└──────────────────────┘   "Save shipment first"
```

---

### "Delete Selected" Button

**Disabled (No Selection):**
```
┌──────────────────────┐
│ 🗑 Delete Selected   │ ← Grey, 50% opacity
└──────────────────────┘   Cursor: not-allowed
                           Disabled attribute
```

**Enabled (Items Selected):**
```
┌──────────────────────┐
│ 🗑 Delete Selected   │ ← Normal colors
└──────────────────────┘   Cursor: pointer
                           Clickable
```

---

### Action Buttons (Per Row)

**Edit Button:**
```
┌─────┐
│ ✎   │ ← Blue background (#3b82f6)
└─────┘   White text
          9px font
          4px padding
```

**Delete Button:**
```
┌─────┐
│ 🗑  │ ← Red background (#e74c3c)
└─────┘   White text
          9px font
          4px padding
```

---

## 📊 Counter Display

### No Selection
```
5 work order(s)
```

### With Selection
```
5 work order(s) | 2 selected
     ↑               ↑
   total         selected
```

### After Deletion
```
3 work order(s)
     ↑
  updated count
```

---

## 🔔 Toast Notifications

### Success (Green)
```
┌─────────────────────────────────────┐
│ ✅ Work order deleted successfully  │
└─────────────────────────────────────┘
Auto-dismiss after 3-5 seconds
```

### Error (Red)
```
┌─────────────────────────────────────┐
│ ❌ Failed to delete work order      │
└─────────────────────────────────────┘
Auto-dismiss after 3-5 seconds
```

### Info (Blue)
```
┌─────────────────────────────────────┐
│ ℹ️  Refreshing work orders...       │
└─────────────────────────────────────┘
Auto-dismiss after 2-3 seconds
```

### Warning (Yellow)
```
┌─────────────────────────────────────┐
│ ⚠️  Please save shipment first      │
└─────────────────────────────────────┘
Auto-dismiss after 3-5 seconds
```

---

## 🎯 Click Targets

### Checkbox (Individual)
```
┌───┐
│ ✓ │ ← 16x16px click area
└───┘   Visual feedback on hover
```

### Checkbox (Select All - Header)
```
┌───┐
│ ✓ │ ← In table header
└───┘   Selects/deselects all rows
```

### W/O Number Link
```
WO-20260127001
↑↑↑↑↑↑↑↑↑↑↑↑↑
Entire text is clickable
Opens edit form in new tab
Color: #4b77be (blue)
Underline on hover
```

### Action Buttons
```
┌────┬────┐
│ ✎  │ 🗑 │ ← Each button 22x22px
└────┴────┘   4px gap between
```

### Refresh Button
```
┌────┐
│ ⟳  │ ← Top right of portlet
└────┘   Next to Tools button
```

---

## 📱 Responsive Behavior

### Desktop (>1200px)
```
┌────────────────────────────────────────────────────────────────┐
│ Full width table                                               │
│ All columns visible                                            │
│ No horizontal scroll                                           │
└────────────────────────────────────────────────────────────────┘
```

### Tablet (768px - 1200px)
```
┌──────────────────────────────────────┐
│ Table width > viewport               │→
│ Horizontal scroll enabled            │
│ All data accessible                  │
└──────────────────────────────────────┘
```

### Mobile (<768px)
```
┌──────────────────────┐
│ Compact layout       │→
│ Horizontal scroll    │
│ Touch-optimized      │
│ Larger tap targets   │
└──────────────────────┘
```

---

## 🎭 Animation & Transitions

### Row Selection
```
Before: background: white
        ↓ (smooth transition)
After:  background: #f0f8ff
        Duration: 0.2s
```

### Button Hover
```
Before: opacity: 1.0
        ↓ (transition)
After:  opacity: 0.8
        transform: scale(1.05)
        Duration: 0.15s
```

### Toast Appearance
```
Slide in from right →
Fade in (opacity 0 to 1)
Duration: 0.3s
```

### Toast Dismissal
```
Fade out (opacity 1 to 0)
Slide out to right →
Duration: 0.3s
After 3-5 seconds
```

### Loading Spinner
```
┌───┐
│ ⟳ │ ← Continuous rotation
└───┘   animation: spin 1s linear infinite
```

---

## 🔍 Visual Hierarchy

### Importance Levels

**Level 1 (Most Important):**
- ➕ New Work Order button (Teal, prominent)
- Work Order numbers (Blue, clickable)

**Level 2 (Important):**
- Action buttons (Edit, Delete)
- Subject column
- Counter display

**Level 3 (Supporting):**
- Location details
- Dates
- Vendor names
- Last modified

**Level 4 (Least Important):**
- Checkboxes
- Table borders
- Background colors

---

## 🎨 Color Palette

### Primary Colors
- **Teal**: `#32c5d2` - Primary actions (New Work Order)
- **Blue**: `#3b82f6` - Edit button, links
- **Red**: `#e74c3c` - Delete button

### State Colors
- **Selected**: `#f0f8ff` - Light blue background
- **Hover**: `#f1f5f9` - Light grey

### Text Colors
- **Primary**: `#333` - Main text
- **Secondary**: `#666` - Supporting text
- **Tertiary**: `#999` - Timestamps, metadata

### Table Colors
- **Header**: `#a0a8b3` - Grey header
- **Border**: `#e7ecf1` - Light grey borders
- **Background**: `#fff` - White cells

---

## 📐 Spacing & Layout

### Table Cell Padding
```
┌─────────────────────┐
│ ↕ 8px               │
│←5px→ Content  ←5px→ │
│ ↕ 8px               │
└─────────────────────┘
```

### Button Padding
```
┌──────────────────┐
│ ↕ 4px            │
│←8px→ Text  ←8px→ │
│ ↕ 4px            │
└──────────────────┘
```

### Gap Between Buttons
```
[Button 1]  4px  [Button 2]
```

### Section Margins
```
Header
  ↕ 10px
Toolbar
  ↕ 5px
Table
```

---

## ✨ Visual Feedback Summary

| Action | Visual Feedback |
|--------|-----------------|
| Click checkbox | ✓ Checkmark appears, row highlights |
| Hover button | Opacity change, scale up slightly |
| Click delete | Confirmation dialog slides in |
| AJAX loading | Spinner animates, text shows |
| Success | Green toast slides in from right |
| Error | Red toast slides in from right |
| Empty state | Large icon + helpful message |
| Tab switch | Content fades in smoothly |

---

**Visual Guide Complete!** 🎨

All UI states, interactions, and visual feedback mechanisms are documented with ASCII diagrams and detailed descriptions.
