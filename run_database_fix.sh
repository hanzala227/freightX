#!/bin/bash

# Work Order Database Fix Script
# This script fixes the polymorphic relationship workable_type values

echo "==============================================="
echo "Work Order Database Fix"
echo "==============================================="
echo ""
echo "This will update workable_type values from:"
echo "  'air_export' → 'App\\Models\\AirExport'"
echo "  'air_import' → 'App\\Models\\AirImport'"
echo "  'ocean_export' → 'App\\Models\\OceanExport'"
echo "  'ocean_import' → 'App\\Models\\OceanImport'"
echo ""
echo "==============================================="
echo ""

# Check if .env file exists
if [ ! -f .env ]; then
    echo "❌ ERROR: .env file not found"
    echo "Please run this script from the project root directory"
    exit 1
fi

# Extract database credentials from .env
DB_HOST=$(grep DB_HOST .env | cut -d '=' -f2)
DB_PORT=$(grep DB_PORT .env | cut -d '=' -f2)
DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2)
DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f2)
DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f2)

echo "📋 Database Connection:"
echo "  Host: $DB_HOST"
echo "  Port: $DB_PORT"
echo "  Database: $DB_DATABASE"
echo "  Username: $DB_USERNAME"
echo ""

# Ask for confirmation
read -p "Do you want to proceed? (yes/no): " confirm
if [ "$confirm" != "yes" ]; then
    echo "❌ Cancelled"
    exit 0
fi

echo ""
echo "🔄 Running database fixes..."
echo ""

# Run the SQL fix
MYSQL_PWD=$DB_PASSWORD mysql -h $DB_HOST -P $DB_PORT -u $DB_USERNAME $DB_DATABASE <<EOF

-- Show current state
SELECT 'BEFORE FIX - Current work orders:' as '';
SELECT id, work_order_no, workable_type, workable_id 
FROM work_orders 
ORDER BY id DESC 
LIMIT 5;

-- Fix Air Export work orders
UPDATE work_orders 
SET workable_type = 'App\\\\Models\\\\AirExport' 
WHERE workable_type = 'air_export';

-- Fix Air Import work orders
UPDATE work_orders 
SET workable_type = 'App\\\\Models\\\\AirImport' 
WHERE workable_type = 'air_import';

-- Fix Ocean Export work orders
UPDATE work_orders 
SET workable_type = 'App\\\\Models\\\\OceanExport' 
WHERE workable_type = 'ocean_export';

-- Fix Ocean Import work orders
UPDATE work_orders 
SET workable_type = 'App\\\\Models\\\\OceanImport' 
WHERE workable_type = 'ocean_import';

-- Show results
SELECT 'AFTER FIX - Updated work orders:' as '';
SELECT id, work_order_no, workable_type, workable_id 
FROM work_orders 
ORDER BY id DESC 
LIMIT 5;

EOF

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Database fix completed successfully!"
    echo ""
    echo "📝 What happened:"
    echo "  - All workable_type values have been updated to full class names"
    echo "  - Your work orders should now load without 'Class not found' errors"
    echo ""
    echo "🧪 Next steps:"
    echo "  1. Try editing an existing work order"
    echo "  2. Create a new work order"
    echo "  3. Verify no errors in browser console"
    echo ""
else
    echo ""
    echo "❌ ERROR: Database fix failed"
    echo "Please check your database connection and try again"
    echo ""
    echo "Manual fix option:"
    echo "  mysql -u $DB_USERNAME -p $DB_DATABASE < FIX_WORKORDER_DATABASE.sql"
    exit 1
fi
