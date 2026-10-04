import os

file_path = r'd:\kelurahan\webkel-patokan\resources\views\admin\navigation\index.blade.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace button
old_button = '''<a href="{{ route('admin.navigation.create', ['section' => 'profil']) }}" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-sm transition flex items-center gap-1.5 inline-flex">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            Sub-Menu
                        </a>'''
new_button = '''<button type="button" @click="$dispatch('open-add-menu-modal', 'profil')" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-sm transition flex items-center gap-1.5 inline-flex">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            Sub-Menu
                        </button>'''

if old_button in content:
    content = content.replace(old_button, new_button)
else:
    print("Could not find the button.")

# Replace modal
# We will use regex to find the modal section
import re
modal_start = r'<!-- Modal Tambah Menu -->'
modal_end = r'<!-- Modal Edit Menu -->'

new_modal = '''<!-- Modal Tambah Menu -->
<div x-data="{ 
        open: false, 
        section: 'profil',
        isSubmitting: false,
        editor: null
    }" 
    @open-add-menu-modal.window="
        open = true; 
        section = $event.detail;
        if(section === 'profil') {
            setTimeout(() => {
                if(typeof tinymce !== 'undefined') {
                    tinymce.init({
                        toolbar_mode: 'sliding',
                        selector: '#add-inline-page-editor',
                        height: 350,
                        menubar: false,
                        plugins: 'lists link',
                        toolbar: 'bold italic | bullist numlist | link',
                        setup: function (ed) {
                            editor = ed;
                            ed.on('change', function () {
                                ed.save();
                            });
                        }
                    });
                }
            }, 500);
        }
    " 
    x-show="open" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <!-- Static backdrop (no @click="open=false") -->
        <div x-show="open" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        <div x-show="open" class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-4xl w-full">
            <form action="{{ route('admin.navigation.store') }}" method="POST" enctype="multipart/form-data" @submit="if(isSubmitting) { $event.preventDefault(); return false; } isSubmitting = true;">
                @csrf
                <div class="bg-gradient-to-r from-emerald-950 to-slate-900 px-6 pt-6 pb-4 border-b border-emerald-900/50">
                    <h3 class="text-lg font-bold text-white" id="modal-title">Tambah Tautan Menu & Halaman</h3>
                </div>
                <div class="px-6 py-4 space-y-4 max-h-[75vh] overflow-y-auto">
                    <div class="hidden">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Bagian (Dropdown)</label>
                        <select name="section" x-model="section" required class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-sm focus:ring-emerald-500 focus:border-emerald-500 pointer-events-none opacity-60" tabindex="-1">
                            <option value="profil">Dropdown Profil</option>
                            <option value="layanan">Dropdown Layanan</option>
                            <option value="dokumen">Dropdown Dokumen</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Menambahkan Tautan ke Menu: <span class="uppercase tracking-wide" x-text="section"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Label Tautan *</label>
                        <input type="text" name="title" required placeholder="Contoh: Sejarah Kelurahan" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <p class="mt-1 text-[11px] text-slate-500">Nama menu yang akan tampil di header publik.</p>
                    </div>

                    <!-- PROFIL: Bikin Halaman Baru -->
                    <div x-show="section === 'profil'" class="border border-emerald-100 rounded-2xl bg-emerald-50/20 p-5 space-y-5 mt-4">
                        <h4 class="text-sm font-bold text-slate-800 border-b border-emerald-100 pb-2">Konten Halaman Baru</h4>
                        
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1.5">Foto Banner (Opsional)</label>
                            <input type="file" name="page_banner" accept="image/png, image/jpeg, image/webp" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
                        </div>
                        
                        <div x-show="section === 'profil'">
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1.5">Isi Halaman Singkat</label>
                            <textarea id="add-inline-page-editor" name="page_content" class="w-full"></textarea>
                        </div>
                    </div>

                    <!-- Layanan -->
                    <div x-show="section === 'layanan'" class="p-4 border border-amber-200 rounded-xl bg-amber-50 text-amber-900 text-sm mt-4">
                        <p class="mb-3 font-medium">Menu layanan ditambahkan secara otomatis saat Anda membuat <strong>Standar Layanan</strong> baru. Namun Anda dapat menambahkannya manual di sini jika terhapus.</p>
                    </div>
                    
                    <div x-show="section === 'layanan'">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Target URL (Layanan)</label>
                        <input type="text" name="url" :disabled="section !== 'layanan'" :required="section === 'layanan'" placeholder="Contoh: /standar-pelayanan?id=3" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <p class="mt-1 text-[11px] text-slate-500">Ketikkan link tautan secara langsung.</p>
                    </div>

                    <!-- Dokumen -->
                    <div x-show="section === 'dokumen'" class="p-4 border border-amber-200 rounded-xl bg-amber-50 text-amber-900 text-sm mt-4">
                        <p class="mb-3 font-medium">Menu dokumen ditambahkan secara otomatis saat Anda mengunggah <strong>Dokumen Publik</strong> baru. Namun Anda dapat menambahkannya manual di sini jika terhapus.</p>
                    </div>
                    
                    <div x-show="section === 'dokumen'">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Target URL (Dokumen)</label>
                        <input type="text" name="url" :disabled="section !== 'dokumen'" :required="section === 'dokumen'" placeholder="Contoh: /dokumen?id=1" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <p class="mt-1 text-[11px] text-slate-500">Ketikkan link tautan secara langsung.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Urutan Tampil</label>
                            <input type="number" name="order" value="0" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Status Tampil</label>
                            <label class="inline-flex items-center mt-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                                <span class="ml-2 text-sm text-slate-700 font-bold">Tampilkan Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl border-t border-slate-200">
                    <button type="button" @click="open = false; if(editor) { tinymce.remove('#add-inline-page-editor'); }" class="px-4 py-2 text-slate-600 font-semibold text-sm hover:bg-slate-200 rounded-xl transition">Batal</button>
                    <button type="submit" :disabled="isSubmitting" class="px-6 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm rounded-xl shadow-sm transition flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isSubmitting">Simpan Menu Baru</span>
                        <span x-show="isSubmitting">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

'''

pattern = re.compile(re.escape('<!-- Modal Tambah Menu -->') + r'.*?(?=<!-- Modal Edit Menu -->)', re.DOTALL)
if pattern.search(content):
    content = pattern.sub(new_modal, content)
else:
    print("Could not find the modal section.")

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Done replacing modal and button.")
