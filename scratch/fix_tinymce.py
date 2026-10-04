import glob
import os

views_dir = r"d:\kelurahan\webkel-patokan\resources\views\admin"

files = glob.glob(os.path.join(views_dir, "**", "*.blade.php"), recursive=True)
count = 0

for file in files:
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    if "tinymce.init({" in content and "toolbar_mode" not in content:
        # insert toolbar_mode: 'sliding',
        content = content.replace("tinymce.init({", "tinymce.init({\n        toolbar_mode: 'sliding',")
        
        with open(file, 'w', encoding='utf-8') as f:
            f.write(content)
        count += 1

print(f"Updated {count} files.")
