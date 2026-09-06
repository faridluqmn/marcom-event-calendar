import sys

file_path = 'resources/views/events/index.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace("route('events.index'", "route(request()->route()->getName()")

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)
print("Replaced successfully")
