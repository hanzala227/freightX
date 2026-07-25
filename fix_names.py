import re

with open('resources/views/air-import/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# We only want to process the expanded details row for charges
start_idx = content.find('<!-- Expanded Details Row -->')
end_idx = content.find('</template>', start_idx)

if start_idx == -1 or end_idx == -1:
    print('COULD NOT FIND BOUNDARIES')
    exit(1)

expanded_html = content[start_idx:end_idx]

# Add :name to inputs (type text, date, number)
expanded_html = re.sub(r'<input type="([^"]+)" x-model="charge\.([^"]+)"', r'<input type="\1" :name="\'charges[\'+idx+\'][\2]\'" x-model="charge.\2"', expanded_html)

# Add :name to inputs (checkbox)
expanded_html = re.sub(r'<input type="checkbox" x-model="charge\.([^"]+)"', r'<input type="checkbox" :name="\'charges[\'+idx+\'][\1]\'" value="1" x-model="charge.\1"', expanded_html)

# Add :name to selects
expanded_html = re.sub(r'<select x-model="charge\.([^"]+)"', r'<select :name="\'charges[\'+idx+\'][\1]\'" x-model="charge.\1"', expanded_html)

# Add :name to textareas
expanded_html = re.sub(r'<textarea x-model="charge\.([^"]+)"', r'<textarea :name="\'charges[\'+idx+\'][\1]\'" x-model="charge.\1"', expanded_html)


# Assemble
new_content = content[:start_idx] + expanded_html + content[end_idx:]

with open('resources/views/air-import/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(new_content)

print('UPDATED SUCCESSFULLY')
