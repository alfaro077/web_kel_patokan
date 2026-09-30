@extends('layouts.admin')

@section('title', 'Tambah Sub-Menu Profil')
@section('header-title', 'Tambah Sub-Menu Profil')
@section('header-subtitle', 'Buat halaman dinamis baru untuk menu profil')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.navigation.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl font-bold text-sm transition-all shadow-sm">
            <i class="fas fa-arrow-left text-slate-400"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden max-w-4xl">
        <div class="bg-gradient-to-r from-emerald-950 to-slate-900 px-8 py-5 text-white flex items-center justify-between border-b border-emerald-900/50">
            <h3 class="text-lg font-bold">Form Tambah Tautan Profil</h3>
        </div>

        <form action="{{ route('admin.navigation.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            <input type="hidden" name="section" value="{{ $section }}">
            
            <div class="space-y-6">
                <!-- Label Tautan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Label Tautan *</label>
                    <input type="text" name="title" required placeholder="Contoh: Sejarah Kelurahan" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <p class="mt-1.5 text-xs text-slate-500">Nama menu yang akan tampil di header publik.</p>
                </div>

                <!-- Konten Halaman -->
                <div class="border border-emerald-100 rounded-2xl bg-emerald-50/20 p-6 space-y-6 mt-4">
                    <h4 class="text-sm font-bold text-slate-800 border-b border-emerald-100 pb-2">Konten Halaman Baru</h4>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Foto Banner (Opsional)</label>
                        <input type="file" name="page_banner" accept="image/png, image/jpeg, image/webp" 
                               class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Isi Halaman Singkat</label>
                        <textarea id="page-editor" name="page_content" class="w-full"></textarea>
                    </div>
                </div>

                <!-- Pengaturan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Urutan Tampil *</label>
                        <input type="number" name="order" value="0" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Status Tampil</label>
                        <label class="inline-flex items-center mt-3 cursor-pointer">
                            <input type="checkbox" name="is_active" checked class="rounded border-slate-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                            <span class="ml-2 font-bold text-sm text-slate-700">Aktif Tayang</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.navigation.index') }}" class="px-6 py-2.5 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-extrabold rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                    Simpan Menu Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/{{ env('TINYMCE_API_KEY', 'no-api-key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
        toolbar_mode: 'sliding',
                selector: '#page-editor',
                height: 400,
                plugins: 'lists link image table code help wordcount',
                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | link image | code',
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save();
                    });
                }
            });
        }
    });
</script>
@endpush

