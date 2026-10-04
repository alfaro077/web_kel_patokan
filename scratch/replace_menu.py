with open('d:/kelurahan/webkel-patokan/resources/views/layouts/admin.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

with open('d:/kelurahan/webkel-patokan/scratch/new_menu.txt', 'r', encoding='utf-8') as f:
    new_menu = f.read()

# We want to replace line 110 to 305 (inclusive).
# In python index, that's lines[109:305].
# Verify the lines to make sure we are replacing the right block:
if 'SECTION 2' not in lines[109]:
    print(f"Error: expected SECTION 2 at line 110, found: {lines[109]}")
    exit(1)

if '</nav>' not in lines[305]:
    print(f"Error: expected </nav> at line 306, found: {lines[305]}")
    exit(1)

lines = lines[:109] + [new_menu + '\n'] + lines[305:]

with open('d:/kelurahan/webkel-patokan/resources/views/layouts/admin.blade.php', 'w', encoding='utf-8') as f:
    f.writelines(lines)

print("Menu replaced successfully.")
