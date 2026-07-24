#!/usr/bin/env python3
"""
Script to add the expanded row details section
"""

file_path = "/home/muhammad-hanzala/Downloads/shuwarma (3)/fms (2)/app (4) Backup with kiro/resources/views/air-import/index.blade.php"

# Read the file
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Find the end of the main row and add expanded row + close template
old_row_end = '''                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="form.charges.length === 0">'''

expanded_row_html = '''                                            </td>
                                        </tr>
                                        
                                        <!-- Expanded Details Row -->
                                        <tr x-show="charge.expanded" x-cloak style="background: #fafbfc;">
                                            <td colspan="2" style="border-right: 1px solid #ddd; background: #fff;"></td>
                                            <td colspan="19" style="padding: 15px;">
                                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                                                    <!-- Column 1 -->
                                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">Seal No2.</label>
                                                            <div class="form-input-container">
                                                                <input type="text" x-model="charge.seal_no2" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">Pick Up No.</label>
                                                            <div class="form-input-container">
                                                                <input type="text" x-model="charge.pickup_no" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">CPRS No.</label>
                                                            <div class="form-input-container">
                                                                <input type="text" x-model="charge.cprs_no" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">CNRU No.</label>
                                                            <div class="form-input-container">
                                                                <input type="text" x-model="charge.cnru_no" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">IT No.</label>
                                                            <div class="form-input-container">
                                                                <input type="text" x-model="charge.it_no" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- More columns will be added in next phase -->
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    </template>
                                    <template x-if="form.charges.length === 0">'''

if old_row_end in content:
    content = content.replace(old_row_end, expanded_row_html, 1)
    print("✓ Added expanded row structure")
    
    # Write back
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
    print("✓ File saved successfully")
else:
    print("ERROR: Could not find row end marker")
    print("Looking for alternative pattern...")
