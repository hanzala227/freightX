#!/usr/bin/env python3
"""
Script to remove the Container & Items tab from air-import/index.blade.php
"""

file_path = "/home/muhammad-hanzala/Downloads/shuwarma (3)/fms (2)/app (4) Backup with kiro/resources/views/air-import/index.blade.php"

# Read the file
with open(file_path, 'r', encoding='utf-8') as f:
    lines = f.readlines()

# Find the start and end of the Container & Items tab section
start_idx = None
end_idx = None

for i, line in enumerate(lines):
    if '<!-- CONTAINER & ITEMS TAB -->' in line:
        start_idx = i
    if start_idx is not None and '<!-- CHARGES TAB -->' in line:
        end_idx = i
        break

if start_idx is not None and end_idx is not None:
    print(f"Found Container & Items section from line {start_idx + 1} to line {end_idx}")
    print(f"Removing {end_idx - start_idx} lines...")
    
    # Remove the section (keep everything before start_idx and from end_idx onwards)
    new_lines = lines[:start_idx] + lines[end_idx:]
    
    # Write back
    with open(file_path, 'w', encoding='utf-8') as f:
        f.writelines(new_lines)
    
    print(f"✓ Successfully removed Container & Items tab!")
    print(f"File now has {len(new_lines)} lines (was {len(lines)} lines)")
else:
    print("ERROR: Could not find Container & Items tab markers")
    print(f"start_idx: {start_idx}, end_idx: {end_idx}")
