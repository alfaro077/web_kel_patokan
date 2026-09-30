@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('header-title', 'Pengaturan Sistem SIMPEL KELURAHAN')
@section('header-subtitle', 'Konfigurasi umum aplikasi, media sosial, dan parameter sistem')

@section('content')
<div id="data-container">
<div class="space-y-6" x-data="{
    async submitForm(e) {
        const form = e.target;
        const submitBtn = form.querySelector('button[type=\'submit\']');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class=\'fas fa-spinner fa-spin mr-2\'></i>Menyimpan...';
        submitBtn.disabled = true;

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: form.method,
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            if (response.ok) {
                const htmlResponse = await fetch(window.location.href).then(res => res.text());
                const parser = new DOMParser();
                const doc = parser.parseFromString(htmlResponse, 'text/html');
                const newGrid = doc.querySelector('#data-container').innerHTML;
                document.querySelector('#data-container').innerHTML = newGrid;
                
                Swal.fire({
                    icon: 'success', title: 'Berhasil', text: 'Pengaturan berhasil disimpan!', timer: 1500, showConfirmButton: false
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Terjadi kesalahan saat menyimpan pengaturan.' });
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi bermasalah.' });
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }
}">

    <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" @submit.prevent="submitForm">
        @csrf

        <!-- Section 1: Identitas Aplikasi -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 bg-slate-50 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Identitas Aplikasi
                </h3>
            </div>
            <div class="p-5 space-y-4 text-xs">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Aplikasi *</label><input type="text" name="app_name" value="{{ $settings['app_name'] }}" required class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-600"></div>
                    <div><label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Subtitle / Tagline</label><input type="text" name="app_subtitle" value="{{ $settings['app_subtitle'] ?? '' }}" class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-600"></div>
                </div>
                <div x-data="{ previewLogo: null, previewBg: null }">
                    <div class="mt-4 mb-1.5">
                        <label class="block font-bold text-slate-700 uppercase tracking-wider">Logo Aplikasi (Kelurahan)</label>
                        <span class="text-[10px] text-slate-500 font-medium">Rekomendasi rasio 1:1 (persegi), minimal 512x512 pixel.</span>
                    </div>
                    <input type="file" name="app_logo" accept="image/*" 
                           @change="const file = $event.target.files[0]; if(file) { $dispatch('open-cropper', { file: file, aspectRatio: 1, onCrop: (blob, url) => { let dt = new DataTransfer(); dt.items.add(new File([blob], file.name, {type: file.type})); $event.target.files = dt.files; previewLogo = url; validateFileInput($event.target, 2); } }) }"
                           class="w-full p-2 bg-slate-50 border border-slate-300 rounded-xl text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    
                    <div class="mt-2.5 p-2.5 bg-slate-50 rounded-2xl border border-slate-200 flex items-center gap-3 mb-4">
                        <template x-if="previewLogo">
                            <img :src="previewLogo" class="w-14 h-14 object-contain rounded-xl border-2 border-sky-500 bg-white p-1 shadow-sm shrink-0">
                        </template>
                        <template x-if="!previewLogo">
                            @if(!empty($settings['app_logo']))
                                <img src="{{ asset('storage/' . $settings['app_logo']) }}" alt="Logo" class="w-14 h-14 object-contain rounded-xl border border-slate-200 bg-white p-1 shadow-sm shrink-0">
                            @else
                                <div class="w-14 h-14 rounded-xl bg-slate-200 text-slate-400 font-bold flex items-center justify-center text-[10px] shrink-0">Logo</div>
                            @endif
                        </template>
                        <div class="min-w-0 text-[11px]">
                            <div class="font-bold text-slate-800" x-text="previewLogo ? 'Preview Logo Baru' : 'Logo Aplikasi Aktif'"></div>
                            <div class="text-[10px] text-sky-600 font-medium truncate" x-text="previewLogo ? 'Terpilih, siap disimpan' : '{{ !empty($settings['app_logo']) ? basename($settings['app_logo']) : 'Belum diunggah' }}'"></div>
                        </div>
                    </div>
                    
                    <div class="mb-1.5">
                        <label class="block font-bold text-slate-700 uppercase tracking-wider">Background Login</label>
                        <span class="text-[10px] text-slate-500 font-medium">Rekomendasi rasio 8:9 (potret), minimal 960x1080 pixel untuk mengisi sisi kiri layar dengan pas.</span>
                    </div>
                    <input type="file" name="login_background" accept="image/*" 
                           @change="const file = $event.target.files[0]; if(file) { $dispatch('open-cropper', { file: file, aspectRatio: 8/9, onCrop: (blob, url) => { let dt = new DataTransfer(); dt.items.add(new File([blob], file.name, {type: file.type})); $event.target.files = dt.files; previewBg = url; validateFileInput($event.target, 5); } }) }"
                           class="w-full p-2 bg-slate-50 border border-slate-300 rounded-xl text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    
                    <div class="mt-2.5 p-2.5 bg-slate-50 rounded-2xl border border-slate-200 flex items-center gap-3">
                        <template x-if="previewBg">
                            <img :src="previewBg" class="w-14 h-[63px] object-cover rounded-xl border-2 border-sky-500 bg-white p-1 shadow-sm shrink-0">
                        </template>
                        <template x-if="!previewBg">
                            @if(!empty($settings['login_background']))
                                <img src="{{ asset('storage/' . $settings['login_background']) }}" alt="Bg Login" class="w-14 h-[63px] object-cover rounded-xl border border-slate-200 bg-white p-1 shadow-sm shrink-0">
                            @else
                                <div class="w-14 h-[63px] rounded-xl bg-slate-200 text-slate-400 font-bold flex items-center justify-center text-[10px] shrink-0">Cover</div>
                            @endif
                        </template>
                        <div class="min-w-0 text-[11px]">
                            <div class="font-bold text-slate-800" x-text="previewBg ? 'Preview Background Baru' : 'Background Login Aktif'"></div>
                            <div class="text-[10px] text-sky-600 font-medium truncate" x-text="previewBg ? 'Terpilih, siap disimpan' : '{{ !empty($settings['login_background']) ? basename($settings['login_background']) : 'Belum diunggah' }}'"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Parameter Sistem -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 bg-slate-50 border-b border-slate-200">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    Parameter Sistem
                </h3>
            </div>
            <div class="p-5 space-y-4 text-xs">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Maks. Upload Foto (MB) *</label>
                        <input type="number" name="max_upload_foto_mb" value="{{ $settings['max_upload_foto_mb'] ?? $settings['max_upload_mb'] ?? 2 }}" min="1" max="20" required class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-600">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Maks. Upload PDF (MB) *</label>
                        <input type="number" name="max_upload_pdf_mb" value="{{ $settings['max_upload_pdf_mb'] ?? $settings['max_upload_mb'] ?? 5 }}" min="1" max="50" required class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-600">
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-xl {{ ($settings['maintenance_mode'] ?? false) ? 'bg-amber-50 border border-amber-200' : 'bg-slate-50 border border-slate-200' }}">
                    <input type="hidden" name="maintenance_mode" value="0">
                    <input type="checkbox" name="maintenance_mode" value="1" {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }} id="maintenance_mode" class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                    <label for="maintenance_mode" class="font-bold text-slate-800 cursor-pointer">
                        Mode Pemeliharaan (Maintenance)
                        <span class="block text-[10px] text-slate-500 font-normal mt-0.5">Jika diaktifkan, website publik akan menampilkan halaman pemeliharaan.</span>
                    </label>
                </div>
            </div>
        </div>



        <!-- Submit Button -->
        <div class="flex items-center justify-end">
            <button type="submit" class="px-6 py-3 bg-sky-700 hover:bg-sky-800 text-white font-extrabold text-sm rounded-xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Semua Pengaturan
            </button>
        </div>

    </form>

</div>
</div>
@endsection

