# Bulk Delete Work Orders - Visual UI Guide

## UI Layout

### Toolbar (Action Bar)
```
┌─────────────────────────────────────────────────────────────────────────────┐
│  [🔄] [⚙ Tools ▾]                                                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                               │
│  [+ New Work Order]  [🗑️ Delete Selected (3)]    Total: 5 work order(s) | 3 selected │
│  ▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔   ▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔                              │
│   Blue/Cyan           Red (when active)                                      │
│   Always active       Gray (when disabled)                                   │
│                                                                               │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Table Structure
```
┌──────────────────────────────────────────────────────────────────────────────────────┐
│ [☑] │ W/O No. │ Subject │ Pickup │ Delivery │ Vendor │ Issue Date │ Modified │ Actions │
├─────┼─────────┼─────────┼────────┼──────────┼────────┼────────────┼──────────┼─────────┤
│ [✓] │ WO-001  │ Pickup  │ ABC Co │ XYZ Loc  │ Truck1 │ 2024-01-15 │ 2h ago   │ [✎] [🗑] │ ← Selected (Blue bg)
├─────┼─────────┼─────────┼────────┼──────────┼────────┼────────────┼──────────┼─────────┤
│ [ ] │ WO-002  │ Deliver │ DEF Co │ ABC Loc  │ Truck2 │ 2024-01-16 │ 1h ago   │ [✎] [🗑] │ ← Not selected
├─────┼─────────┼─────────┼────────┼──────────┼────────┼────────────┼──────────┼─────────┤
│ [✓] │ WO-003  │ Return  │ GHI Co │ DEF Loc  │ Truck3 │ 2024-01-17 │ 30m ago  │ [✎] [🗑] │ ← Selected (Blue bg)
├─────┼─────────┼─────────┼────────┼──────────┼────────┼────────────┼──────────┼─────────┤
│ [ ] │ WO-004  │ Pickup  │ JKL Co │ GHI Loc  │ Truck1 │ 2024-01-18 │ 15m ago  │ [✎] [🗑] │ ← Not selected
├─────┼─────────┼─────────┼────────┼──────────┼────────┼────────────┼──────────┼─────────┤
│ [✓] │ WO-005  │ Empty   │ MNO Co │ JKL Loc  │ Truck4 │ 2024-01-19 │ 5m ago   │ [✎] [🗑] │ ← Selected (Blue bg)
└─────┴─────────┴─────────┴────────┴──────────┴────────┴────────────┴──────────┴─────────┘
```

## Visual States

### 1. No Selection (Default State)
```
Toolbar:
  [+ New Work Order]  [Delete Selected]
  ▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔   ▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔
   Blue/Cyan          Gray (disabled)
   
Counter:
  "Total: 5 work order(s)"
  
Table Header:
  [ ] ← Empty checkbox
  
Table Rows:
  All rows: White background
```

### 2. Single Selection
```
Toolbar:
  [+ New Work Order]  [🗑️ Delete Selected (1)]
  ▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔   ▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔
   Blue/Cyan          Red (active)
   
Counter:
  "Total: 5 work order(s) | 1 selected"
                           ▔▔▔▔▔▔▔▔▔▔
                           Red text
  
Table Header:
  [ ] ← Still empty (not all selected)
  
Table Rows:
  1 row: Blue background (#e8f4fd) with blue left border
  4 rows: White background
```

### 3. Multiple Selection (Not All)
```
Toolbar:
  [+ New Work Order]  [🗑️ Delete Selected (3)]
  ▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔   ▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔
   Blue/Cyan          Red (active)
   
Counter:
  "Total: 5 work order(s) | 3 selected"
                           ▔▔▔▔▔▔▔▔▔▔
                           Red text, bold
  
Table Header:
  [ ] ← Still empty (not all selected)
  
Table Rows:
  3 rows: Blue background with blue left border
  2 rows: White background
```

### 4. All Selected
```
Toolbar:
  [+ New Work Order]  [🗑️ Delete Selected (5)]
  ▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔   ▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔
   Blue/Cyan          Red (active)
   
Counter:
  "Total: 5 work order(s) | 5 selected"
                           ▔▔▔▔▔▔▔▔▔▔
                           Red text, bold
  
Table Header:
  [✓] ← Checked (all selected)
  
Table Rows:
  All 5 rows: Blue background with blue left border
```

### 5. During Deletion (Loading State)
```
Toolbar:
  [+ New Work Order]  [Delete Selected]
  ▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔▔   ▔▔▔▔▔▔▔▔▔▔▔▔▔▔
   Disabled           Disabled
   
Toast Notification:
  ┌─────────────────────────────────┐
  │ ℹ Deleting 3 work order(s)...  │
  └─────────────────────────────────┘
  
Table:
  ┌─────────────────────────────────┐
  │         🔄 Loading...           │
  │   Loading work orders...        │
  └─────────────────────────────────┘
```

### 6. After Successful Deletion
```
Toast Notification:
  ┌───────────────────────────────────────────┐
  │ ✓ Successfully deleted 3 work order(s)    │
  └───────────────────────────────────────────┘
  Green background, white text
  
Table:
  Refreshed with remaining work orders
  Selection cleared
  Counter updated: "Total: 2 work order(s)"
```

## Color Scheme

### Buttons
| State | Background | Text | Border |
|-------|-----------|------|--------|
| New Work Order | `#32c5d2` (Cyan) | White | None |
| Delete Disabled | `#f5f5f5` (Light Gray) | `#999` (Gray) | `#ddd` |
| Delete Active | `#e74c3c` (Red) | White | `#c0392b` |

### Table Rows
| State | Background | Left Border | Hover |
|-------|-----------|-------------|-------|
| Normal | White | None | `#f9fafb` |
| Selected | `#e8f4fd` (Light Blue) | `3px #3b82f6` | `#d6ebff` |

### Counter Text
| State | Color | Weight |
|-------|-------|--------|
| Total count | `#666` (Dark Gray) | 500 |
| Selected count | `#e74c3c` (Red) | 600 (Bold) |

## Interactive Elements

### Checkboxes
```
Size: 14px × 14px
Cursor: pointer
States:
  [ ] Unchecked - White background, gray border
  [✓] Checked - Blue background, white checkmark
```

### Buttons
```
New Work Order:
  Size: 6px padding, 12px horizontal
  Hover: Slightly darker cyan
  
Delete Selected:
  Size: 6px padding, 12px horizontal
  Disabled: Not clickable, gray appearance
  Active Hover: Darker red (#c0392b)
```

### Table Rows
```
Hover Effect:
  Normal row → Light gray background
  Selected row → Darker blue background
  
Transition: 0.2s smooth
```

## Confirmation Dialog

```
┌──────────────────────────────────────────────┐
│  ⚠ Confirm Deletion                          │
├──────────────────────────────────────────────┤
│                                               │
│  Are you sure you want to delete 3 work      │
│  order(s)?                                    │
│                                               │
│  This action cannot be undone.                │
│                                               │
│               [Cancel]    [OK]                │
└──────────────────────────────────────────────┘
```

## Toast Notifications

### Success Toast
```
┌─────────────────────────────────────────────────┐
│ ✓ Successfully deleted 3 work order(s)          │
└─────────────────────────────────────────────────┘
Green (#2ecc71), white text, 5 second duration
```

### Warning Toast (Partial Success)
```
┌─────────────────────────────────────────────────┐
│ ⚠ Deleted 2 work order(s), but 1 failed         │
└─────────────────────────────────────────────────┘
Orange (#f39c12), white text, 7 second duration
```

### Error Toast
```
┌─────────────────────────────────────────────────┐
│ ✕ Failed to delete 3 work order(s)              │
└─────────────────────────────────────────────────┘
Red (#e74c3c), white text, 7 second duration
```

### Info Toast (During Deletion)
```
┌─────────────────────────────────────────────────┐
│ ℹ Deleting 3 work order(s)...                   │
└─────────────────────────────────────────────────┘
Blue (#3498db), white text, remains until complete
```

## User Flow Diagram

```
┌─────────────────┐
│  View Work      │
│  Orders List    │
└────────┬────────┘
         │
         ├─────────────────────────────────┐
         │                                 │
         v                                 v
┌─────────────────┐              ┌─────────────────┐
│  Click Single   │              │  Click "Select  │
│  Checkbox       │              │  All" Header    │
└────────┬────────┘              └────────┬────────┘
         │                                 │
         v                                 v
┌─────────────────┐              ┌─────────────────┐
│  Row Highlights │              │  All Rows       │
│  in Blue        │              │  Highlight      │
└────────┬────────┘              └────────┬────────┘
         │                                 │
         └─────────────┬───────────────────┘
                       │
                       v
              ┌─────────────────┐
              │  "Delete        │
              │  Selected"      │
              │  Button Active  │
              └────────┬────────┘
                       │
                       v
              ┌─────────────────┐
              │  Click "Delete  │
              │  Selected (n)"  │
              └────────┬────────┘
                       │
                       v
              ┌─────────────────┐
              │  Confirmation   │
              │  Dialog Appears │
              └────────┬────────┘
                       │
         ┌─────────────┴─────────────┐
         v                           v
┌─────────────────┐         ┌─────────────────┐
│  Click Cancel   │         │  Click OK       │
│  → No Action    │         │  → Delete       │
└─────────────────┘         └────────┬────────┘
                                     │
                                     v
                            ┌─────────────────┐
                            │  Loading        │
                            │  Spinner Shows  │
                            └────────┬────────┘
                                     │
                                     v
                            ┌─────────────────┐
                            │  Deletion       │
                            │  Processes      │
                            └────────┬────────┘
                                     │
                   ┌─────────────────┼─────────────────┐
                   v                 v                 v
          ┌─────────────┐   ┌─────────────┐  ┌─────────────┐
          │  All        │   │  Partial    │  │  All Failed │
          │  Succeed    │   │  Success    │  │             │
          └──────┬──────┘   └──────┬──────┘  └──────┬──────┘
                 │                 │                 │
                 v                 v                 v
          ┌─────────────┐   ┌─────────────┐  ┌─────────────┐
          │  Green      │   │  Orange     │  │  Red Toast  │
          │  Toast      │   │  Toast      │  │             │
          └──────┬──────┘   └──────┬──────┘  └──────┬──────┘
                 │                 │                 │
                 └─────────────────┴─────────────────┘
                                   │
                                   v
                          ┌─────────────────┐
                          │  List Refreshes │
                          │  Selection      │
                          │  Cleared        │
                          └─────────────────┘
```

## Keyboard Shortcuts (Future Enhancement)

Potential shortcuts that could be added:
- `Ctrl + A` - Select all work orders
- `Delete` - Delete selected work orders (after confirmation)
- `Escape` - Clear selection
- `Space` - Toggle selection on focused row

## Responsive Behavior

### Desktop (>1200px)
- Full table with all columns visible
- Buttons side by side
- Counter on right side

### Tablet (768px - 1200px)
- Table scrolls horizontally if needed
- Buttons remain side by side
- Counter below buttons

### Mobile (<768px)
- Card view instead of table (future enhancement)
- Buttons stack vertically
- Counter full width below buttons

## Accessibility Features

✅ Checkbox labels with titles  
✅ Button disabled states clearly visible  
✅ High contrast colors for selected state  
✅ Focus indicators on interactive elements  
✅ Confirmation dialogs prevent accidents  
✅ Clear toast notifications  

## Status: ✅ IMPLEMENTED

All visual elements and interactions are fully implemented and ready for use!
