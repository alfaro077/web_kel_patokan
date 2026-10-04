import os
import re

files_to_clean = [
    r'd:\kelurahan\webkel-patokan\resources\views\admin\struktur_organisasi\index.blade.php',
    r'd:\kelurahan\webkel-patokan\resources\views\admin\struktur_organisasi\edit.blade.php',
    r'd:\kelurahan\webkel-patokan\resources\views\admin\struktur_organisasi\create.blade.php',
    r'd:\kelurahan\webkel-patokan\resources\views\admin\documents\index.blade.php',
    r'd:\kelurahan\webkel-patokan\resources\views\admin\beranda\transparansi.blade.php',
    r'd:\kelurahan\webkel-patokan\resources\views\admin\beranda\statistik.blade.php',
    r'd:\kelurahan\webkel-patokan\resources\views\admin\operator\index.blade.php',
    r'd:\kelurahan\webkel-patokan\resources\views\admin\jenis-layanan\index.blade.php'
]

# Regex patterns to match blocks spanning multiple lines
p_success = re.compile(r'(\s*<!--\s*Alert Status\s*-->)?\s*@if\s*\(\s*session\(\'success\'\)\s*\).*?@endif\s*', re.DOTALL)
p_status = re.compile(r'(\s*<!--\s*Alert Status\s*-->)?\s*@if\s*\(\s*session\(\'status\'\)\s*\).*?@endif\s*', re.DOTALL)
p_errors = re.compile(r'\s*@if\s*\(\s*\$errors->any\(\)\s*\).*?@endif\s*', re.DOTALL)

for fpath in files_to_clean:
    if os.path.exists(fpath):
        with open(fpath, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # In operator/index.blade.php and lembaga.blade.php, they also have @if($errors->any()) inside alpine.js conditionals which are one-liners: 
        # {{ $errors->any() && !old('_method') ? 'true' : 'false' }} 
        # The regex `@if\s*\(\s*\$errors->any\(\)\s*\)` will specifically match `@if($errors->any())`, so it won't break Alpine.js inline PHP tags like `{{ $errors->any() }}`.

        new_content = content
        new_content = p_success.sub('\n', new_content)
        new_content = p_status.sub('\n', new_content)
        
        # Be careful not to remove the bottom sweetalert script if there is one that uses @if($errors->any()) in script form. Wait, looking at the regex, it matches `@if($errors->any())` to `@endif`. In views that have it as html block it will be removed.
        # But wait, in `lembaga.blade.php`, line 246 is `@if($errors->any())`. It might be a script! I didn't include lembaga.blade.php in the list so it's fine.
        
        new_content = p_errors.sub('\n', new_content)

        if content != new_content:
            with open(fpath, 'w', encoding='utf-8') as f:
                f.write(new_content)
            print(f"Cleaned {fpath}")
