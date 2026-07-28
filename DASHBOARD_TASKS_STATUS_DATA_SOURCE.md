# Dashboard - Tasks Status Data Source

## Question:
"from where this tasks status data coming from database tell me"

---

## Answer:

The **TASKS STATUS** table on the dashboard is populated from the `quotations` table in the database.

---

## Database Table:
**Table Name:** `quotations`

---

## Controller Method:
**File:** `app/Http/Controllers/DashboardController.php`  
**Method:** `calculateTasks()`

---

## SQL Query:
```php
$quotations = Quotation::with(['customer', 'salesPerson'])
    ->latest()
    ->take(10)
    ->get();
```

**Translation:**
```sql
SELECT * FROM quotations
LEFT JOIN trade_partners ON quotations.customer_id = trade_partners.id
LEFT JOIN users ON quotations.sales_person_id = users.id
ORDER BY quotations.created_at DESC
LIMIT 10
```

---

## Data Mapping:

### Columns in Tasks Status Table:

| Column | Database Source | Notes |
|--------|----------------|-------|
| **Name** | `trade_partners.name` or `trade_partners.company` | Customer name from quotation's customer relationship |
| **Last Contacted** | `quotations.updated_at` | Formatted as "Jul 20, 2026" |
| **Sales Representative** | `users.name` | From quotation's salesPerson relationship |
| **Status** | `quotations.status` | Values: Draft, Sent, Pending, Approved, Won, Lost, etc. |
| **Deal Value** | `quotations.total_amount` or `quotations.amount` | Displayed in thousands (e.g., $50.0K) |

---

## Status Badge Colors:

The badge color is determined by the quotation status:

```php
$status = $q->status ?? 'draft';
$statusLower = strtolower($status);

if (in_array($statusLower, ['approved', 'won', 'closed'])) {
    $badgeClass = 'badge-subtle-success'; // Green
    $dealColor = '#0ab39c';
    
} elseif (in_array($statusLower, ['sent', 'negotiation', 'pending'])) {
    $badgeClass = 'badge-subtle-warning'; // Yellow
    $dealColor = '#f59e0b';
    
} elseif (in_array($statusLower, ['lost', 'cancelled', 'rejected'])) {
    $badgeClass = 'badge-subtle-danger'; // Red
    $dealColor = '#ef4444';
    
} else {
    $badgeClass = 'badge-subtle-info'; // Blue (default for Draft)
    $dealColor = '#3b82f6';
}
```

### Badge Mapping:

| Status | Badge Color | Deal Value Color | Badge Class |
|--------|-------------|------------------|-------------|
| Sent, Pending, Negotiation | Yellow | Orange (#f59e0b) | badge-subtle-warning |
| Draft | Blue | Blue (#3b82f6) | badge-subtle-info |
| Approved, Won, Closed | Green | Green (#0ab39c) | badge-subtle-success |
| Lost, Cancelled, Rejected | Red | Red (#ef4444) | badge-subtle-danger |
| Expired | Blue | Blue (#3b82f6) | badge-subtle-info |

---

## Model Relationships:

### Quotation Model (`app/Models/Quotation.php`):

```php
public function customer()
{
    return $this->belongsTo(TradePartner::class, 'customer_id');
}

public function salesPerson()
{
    return $this->belongsTo(User::class, 'sales_person_id');
}
```

---

## Database Schema:

### `quotations` table (relevant columns):

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `customer_id` | bigint | Foreign key to trade_partners table |
| `sales_person_id` | bigint | Foreign key to users table |
| `status` | varchar | Quote status (sent, draft, pending, expired, approved, won, lost, etc.) |
| `total_amount` | decimal | Total quote value |
| `amount` | decimal | Fallback if total_amount is null |
| `created_at` | timestamp | Creation date |
| `updated_at` | timestamp | Last update date (used for "Last Contacted") |

### `trade_partners` table (relevant columns):

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `name` | varchar | Customer/company name |
| `company` | varchar | Fallback company name |
| `type` | varchar | Partner type (CS, CLIENT, PR, AGENT, etc.) |

### `users` table (relevant columns):

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `name` | varchar | User full name |

---

## Example Data in Dashboard:

Based on your screenshot:

| Name | Last Contacted | Sales Representative | Status | Deal Value |
|------|----------------|---------------------|--------|------------|
| Unknown | Jul 20, 2026 | Devraj | Sent | $0.0K |
| TP-AUTO-178154143407 | Jul 25, 2026 | Ahmed | Draft | $0.0K |
| TP-AUTO-178154114340 | Jul 21, 2026 | Ahmed | Expired | $0.0K |

**Actual Database Query Result:**
```php
[
    [
        'name' => 'Unknown', // From trade_partners.name
        'last_contacted' => 'Jul 20, 2026', // From quotations.updated_at
        'sales_rep_name' => 'Devraj', // From users.name
        'status' => 'Sent', // From quotations.status
        'deal_value' => 0, // From quotations.total_amount
        'badgeClass' => 'badge-subtle-warning', // Yellow badge
        'dealColor' => '#f59e0b' // Orange text
    ],
    // ... more records
]
```

---

## Empty State:

If no quotations are found, the table shows:

```
┌─────────────────────────────────┐
│   📦 (inbox icon)               │
│   No leads found.               │
│   Create a lead to see          │
│   tasks here.                   │
└─────────────────────────────────┘
```

---

## How to Add/Update Data:

### 1. Create a New Quotation:
Navigate to the quotation module and create a new quote. It will automatically appear in the dashboard.

### 2. Update Status:
Change the quotation status to see different badge colors:
- Change to "Sent" → Yellow badge
- Change to "Approved" → Green badge
- Change to "Lost" → Red badge

### 3. Assign Sales Rep:
Set the `sales_person_id` in the quotation to show the correct sales representative.

### 4. Update Last Contacted:
Any update to the quotation will update the `updated_at` timestamp, which is displayed as "Last Contacted".

---

## Summary:

✅ **Data Source:** `quotations` table  
✅ **Limit:** Shows latest 10 quotations  
✅ **Relationships:** Joins with `trade_partners` (customer) and `users` (sales rep)  
✅ **Status-based Styling:** Different badge colors based on quotation status  
✅ **Deal Value:** From `total_amount` or `amount` column  
✅ **Last Contacted:** From `updated_at` column  

The Tasks Status table is essentially a **Quotation Pipeline Dashboard** showing recent quotes with their status, assigned sales rep, and deal value!
