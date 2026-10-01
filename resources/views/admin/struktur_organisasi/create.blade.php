@extends('layouts.admin')

@section('title', 'Tambah Anggota Struktur')
@section('header-title', 'Tambah Anggota')
@section('header-subtitle', 'Tambahkan anggota baru ke dalam struktur organisasi kelurahan')

@section('content')
<div class="max-w-3xl" x-data="{
    photoPreview: null,
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
                Swal.fire({
                    icon: 'success', title: 'Berhasil', text: 'Data berhasil disimpan!', timer: 1500, showConfirmButton: false
                }).then(() => {
                    window.location.href = '{{ route('admin.struktur_organisasi.index') }}';
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Terjadi kesalahan saat menyimpan data.' });
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
    <form action="{{ route('admin.struktur_organisasi.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6" @submit.prevent="submitForm">
        @csrf
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label for="name" class="block text-sm font-semibold text-slate-900">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-2.5 transition-all" placeholder="Masukkan nama lengkap">
            </div>

            <div class="space-y-2">
                <label for="position" class="block text-sm font-semibold text-slate-900">Jabatan <span class="text-red-500">*</span></label>
                <input type="text" name="position" id="position" value="{{ old('position') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-2.5 transition-all" placeholder="Contoh: Kepala Kelurahan">
            </div>

            <div class="space-y-2 col-span-1 sm:col-span-2">
                <label for="tupoksi" class="block text-sm font-semibold text-slate-900">Tugas Pokok & Fungsi (Tupoksi) <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <textarea name="tupoksi" id="tupoksi" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-2.5 transition-all" placeholder="Jelaskan tugas dan fungsi secara singkat...">{{ old('tupoksi') }}</textarea>
            </div>


            <div class="space-y-2 col-span-1 sm:col-span-2">
                <label for="nip" class="block text-sm font-semibold text-slate-900">NIP <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input type="text" name="nip" id="nip" value="{{ old('nip') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-2.5 transition-all" placeholder="Masukkan NIP jika ada">
            </div>
        </div>

        <div class="space-y-2">
            <label for="parent_id" class="block text-sm font-semibold text-slate-900">Atasan (Parent) <span class="text-slate-400 font-normal">(Biarkan kosong jika ini adalah Ketua/Lurah)</span></label>
            <select name="parent_id" id="parent_id" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-2.5 transition-all">
                <option value="">-- Paling Atas (Root / Ketua Kelurahan) --</option>
                @foreach($members as $m)
                    <option value="{{ $m->id }}" {{ old('parent_id', request('parent_id')) == $m->id ? 'selected' : '' }}>
                        {{ $m->name }} ({{ $m->position }})
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-slate-500 mt-1">Pilih atasan langsung untuk membuat struktur hierarki.</p>
        </div>

        <div class="space-y-2">
            <label for="photo" class="block text-sm font-semibold text-slate-900">Foto Profil <span class="text-slate-400 font-normal">(Opsional)</span></label>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center border border-slate-200 overflow-hidden shrink-0">
                    <template x-if="photoPreview">
                        <img :src="photoPreview" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!photoPreview">
                        <i class="fas fa-user text-slate-400 text-xl"></i>
                    </template>
                </div>
                <div class="flex-1">
                    <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg" 
                        @change="const file = $event.target.files[0]; if(file) { $dispatch('open-cropper', { file: file, aspectRatio: 1, onCrop: (blob, url) => { let dt = new DataTransfer(); dt.items.add(new File([blob], file.name, {type: file.type})); $event.target.files = dt.files; photoPreview = url; } }) }"
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                    <p class="text-xs text-slate-500 mt-1">Format: JPG, JPEG, PNG. Maks {{ $systemSettings['max_upload_foto_mb'] ?? 2 }}MB.</p>
                </div>
            </div>
        </div>

        <div class="pt-4 flex gap-3 border-t border-slate-100">
            <a href="{{ route('admin.struktur_organisasi.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-all">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all">
                Simpan Anggota
            </button>
        </div>
    </form>
</div>
@endsection
