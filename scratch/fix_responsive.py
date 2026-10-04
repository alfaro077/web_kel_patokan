import os

file_path = r'd:\kelurahan\webkel-patokan\resources\views\admin\navigation\index.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

replacements = {
    # Main container wrappers
    '<div class="flex items-center justify-between">': '<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">',
    '<div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex items-center justify-between p-4">': '<div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between p-4 gap-4">',
    
    # Inner flex gap wrappers
    '<div class="flex items-center gap-4">': '<div class="flex items-center gap-3 sm:gap-4">',
    '<div class="flex items-center gap-2">': '<div class="flex flex-wrap items-center gap-2">',
    
    # URL lines
    '<div class="flex items-center gap-4 text-xs font-mono text-slate-500 mt-1">': '<div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs font-mono text-slate-500 mt-1">',
    '<div class="flex items-center gap-3 text-xs font-mono text-slate-500 mt-1">': '<div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs font-mono text-slate-500 mt-1 overflow-hidden w-full">',
    
    # Action buttons wrappers
    '<div class="flex items-center gap-3">': '<div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto justify-end">',
    
    # Tree items
    '<div class="relative flex items-center bg-white border border-emerald-100 rounded-xl p-3 shadow-sm hover:border-emerald-300 transition group ml-6">': '<div class="relative flex flex-col sm:flex-row sm:items-center bg-white border border-emerald-100 rounded-xl p-3 shadow-sm hover:border-emerald-300 transition group ml-6 gap-3 sm:gap-0">',
    '<div class="absolute -left-6 top-1/2 w-6 h-px bg-emerald-200"></div>': '<div class="absolute -left-6 top-6 sm:top-1/2 w-6 h-px bg-emerald-200"></div>',
    '<div class="flex-1 flex items-center gap-3">': '<div class="flex-1 flex flex-col sm:flex-row sm:items-center items-start gap-2 sm:gap-3 w-full">',
}

for old, new in replacements.items():
    content = content.replace(old, new)
    
# Specifically for tree action buttons which are <div class="flex items-center gap-2"> but we already replaced above to <div class="flex flex-wrap items-center gap-2">, let's fix it for the actions:
content = content.replace(
    '<div class="flex flex-wrap items-center gap-2">\n                                @if($menu->is_active)',
    '<div class="flex flex-wrap items-center gap-2 mt-2 sm:mt-0 w-full sm:w-auto">\n                                @if($menu->is_active)'
)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Done replacing.")
