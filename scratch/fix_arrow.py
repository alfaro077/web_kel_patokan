import os
import glob

directory = r"d:\kelurahan\webkel-patokan\resources\views\admin"
pattern = r"d:\kelurahan\webkel-patokan\resources\views\admin\**\*.blade.php"

mangled_string = '= class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"> '
mangled_string2 = '= class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">'

correct_string = '=> '

count = 0
for filepath in glob.glob(pattern, recursive=True):
    with open(filepath, 'r', encoding='utf-8') as file:
        content = file.read()
    
    if mangled_string in content or mangled_string2 in content:
        content = content.replace(mangled_string, correct_string)
        content = content.replace(mangled_string2, '=>')
        with open(filepath, 'w', encoding='utf-8') as file:
            file.write(content)
        print(f"Fixed {filepath}")
        count += 1

print(f"Total files fixed: {count}")
