import re

with open('resources/views/air-import/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace checkboxes that have :name="'charges[... but no value="1"
content = re.sub(r'<input type="checkbox" :name="\'charges\[\'\+idx\+\]\[([^\]]+)\]\'"(?!.*?value="1")[^>]*>', 
                 lambda m: m.group(0).replace('type="checkbox"', 'type="checkbox" value="1"'), 
                 content)

with open('resources/views/air-import/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print('FIXED CHECKBOXES')
