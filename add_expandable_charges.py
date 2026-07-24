#!/usr/bin/env python3
"""
Script to add expandable row functionality to Charges table
"""

file_path = "/home/muhammad-hanzala/Downloads/shuwarma (3)/fms (2)/app (4) Backup with kiro/resources/views/air-import/index.blade.php"

# Read the file
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Find and replace the # column cell to add +/- button
old_number_cell = '''<td x-text="idx + 1" style="text-align: center;"></td>'''

new_number_cell = '''<td style="text-align: center;">
                                                    <div style="display: flex; align-items: center; justify-content: center; gap: 4px;">
                                                        <button type="button" @click="charge.expanded = !charge.expanded" class="btn-default-gf" style="padding: 0; height: 16px; width: 16px; line-height: 1; border-radius: 2px; background: #fff; border: 1px solid #ccc;">
                                                            <i :class="charge.expanded ? 'fa fa-minus' : 'fa fa-plus'" style="font-size: 9px; color: #555;"></i>
                                                        </button>
                                                        <span x-text="idx + 1" style="font-weight: bold; font-size: 11px;"></span>
                                                    </div>
                                                </td>'''

# Replace
if old_number_cell in content:
    content = content.replace(old_number_cell, new_number_cell, 1)
    print("✓ Added +/- button to # column")
else:
    print("ERROR: Could not find number cell to replace")

# Now we need to wrap the <tr> in a template and add expanded row
# Find the charges row structure
old_row_start = '''<template x-for="(charge, idx) in form.charges.filter(c => activeChargeFilter === 'All' || (activeChargeFilter === 'AR' && c.pr === 'Rec') || (activeChargeFilter === 'AP' && c.pr === 'Pay'))" :key="idx">
                                        <tr>'''

new_row_start = '''<template x-for="(charge, idx) in form.charges.filter(c => activeChargeFilter === 'All' || (activeChargeFilter === 'AR' && c.pr === 'Rec') || (activeChargeFilter === 'AP' && c.pr === 'Pay'))" :key="idx">
                                        <template>
                                            <!-- Main Row -->
                                            <tr :style="charge.selected ? 'background:#fef9e7;' : ''">'''

if old_row_start in content:
    content = content.replace(old_row_start, new_row_start, 1)
    print("✓ Wrapped row in template")
else:
    print("ERROR: Could not find row start")

# Write back
with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("✓ Phase 1 complete - Added +/- button structure")
