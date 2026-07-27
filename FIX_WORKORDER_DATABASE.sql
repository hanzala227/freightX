-- Fix Work Order Database Records
-- Run this SQL to fix existing work orders with wrong workable_type

-- Fix Air Export work orders
UPDATE work_orders 
SET workable_type = 'App\\Models\\AirExport' 
WHERE workable_type = 'air_export';

-- Fix Air Import work orders
UPDATE work_orders 
SET workable_type = 'App\\Models\\AirImport' 
WHERE workable_type = 'air_import';

-- Fix Ocean Export work orders
UPDATE work_orders 
SET workable_type = 'App\\Models\\OceanExport' 
WHERE workable_type = 'ocean_export';

-- Fix Ocean Import work orders
UPDATE work_orders 
SET workable_type = 'App\\Models\\OceanImport' 
WHERE workable_type = 'ocean_import';

-- Verify the fix
SELECT id, work_order_no, workable_type, workable_id 
FROM work_orders 
ORDER BY id DESC 
LIMIT 10;
