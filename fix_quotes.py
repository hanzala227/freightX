import re

with open('resources/views/air-import/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace \\' with '
content = content.replace(r"\'", "'")

with open('resources/views/air-import/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print('FIXED QUOTES')
