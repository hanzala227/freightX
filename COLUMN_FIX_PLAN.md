# Container List Column Fix Plan

## Problem Identified:
Headers show: Carrier, Vessel, POL, POD, DEL, Office, Sales, OP/Operator
But data shows: DATES (07/21/2026, 07/29/2026, etc.)

## Root Cause:
Body rows have FEWER columns than headers BEFORE the "Shipment Fields" section.
This causes all subsequent columns to shift left, showing wrong data.

## Header Order (88 columns total):
1-6: Sticky columns (checkbox, flag, file_no, color, container_no, consignee)
7-15: Container info (remarks, stages, hbl, location, rail, rail_code, etd, eta, last_edi)
16-54: Container editable fields (pp_ctf through complete)
55-82: Shipment fields (mbl_no through receipt_etd) ← THIS IS WHERE DATA SHOULD SHOW
83-88: HBL fields (po_no through delivery_loc)

## Current Body Structure Issue:
The body has editable inputs for columns 16-54 BUT some are missing/malformed,
causing the count to be off by the time we reach column 55 (mbl_no).

## Solution:
Count exact TDs in body and match to headers, adding any missing columns.

## Next Steps:
1. Create clean version with all 88 columns properly mapped
2. Ensure each header position matches body position exactly
3. Test to verify data shows correctly
