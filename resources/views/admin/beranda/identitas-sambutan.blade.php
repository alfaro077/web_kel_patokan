@extends('layouts.admin')

@section('title', 'Identitas Kelurahan')
@section('header-title', 'Identitas & Pejabat Kelurahan')
@section('header-subtitle', 'Kelola informasi profil dasar kelurahan, nama lurah, dan jajaran SOTK.')

@section('content')
<div id="data-container">
<div class="space-y-6">

    <form action="{{ route('admin.beranda.update') }}" method="POST" enctype="multipart/form-data" @submit.prevent="tinymce.triggerSave(); window.submitAjax($event)" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
        @csrf
        <input type="hidden" name="section" value="identitas_sambutan">

        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900">Identitas Kepala Kelurahan</h3>
            <p class="text-xs text-slate-500">Informasi dasar nama kelurahan dan pemimpin saat ini.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1.5">Nama Kelurahan *</label>
                <input type="text" name="village_name" value="{{ old('village_name', $profile['village_name'] ?? '') }}" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-600">
            </div>
        </div>

        <div class="mt-4 p-4 bg-sky-50 border border-sky-100 rounded-xl">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-sky-600 mt-0.5"></i>
                <div>
                    <p class="text-sm font-bold text-sky-900">Info Pengelolaan Data Lurah</p>
                    <p class="text-xs text-sky-700 mt-1">Nama, NIP, dan Foto Kepala Kelurahan (Lurah) sekarang dikelola secara terpusat melalui menu <strong>Struktur Organisasi</strong>. Data sambutan di bawah ini akan otomatis menggunakan foto dan nama Lurah dari struktur tersebut.</p>
                </div>
            </div>
        </div>

        <div class="border-b border-slate-100 pb-3 mt-6">
            <h3 class="text-base font-bold text-slate-900">Sambutan Kepala Kelurahan</h3>
            <p class="text-xs text-slate-500">Teks sambutan yang akan tampil di halaman depan website bersama foto Lurah.</p>
        </div>

        <div class="space-y-4 text-xs mt-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1.5">Judul Sambutan *</label>
                <input type="text" name="welcome_title" value="{{ old('welcome_title', $profile['welcome_title'] ?? '') }}" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-600">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1.5">Isi Sambutan *</label>
                <textarea name="welcome_text" id="welcome_editor" rows="6" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-600">{{ old('welcome_text', $profile['welcome_text'] ?? '') }}</textarea>
            </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex justify-end mt-6">
            <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl transition">Simpan Identitas</button>
        </div>
    </form>


</div>

<!-- TinyMCE Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        toolbar_mode: 'sliding',
        selector: '#welcome_editor',
        plugins: 'lists link image media table code help fullscreen wordcount',
        toolbar: 'styles | bold underline removeformat | forecolor backcolor | bullist numlist align | table | link image media | fullscreen code help',
        menubar: false,
        height: 350,
        placeholder: 'Ketik sambutan di sini...',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 14px; color: #334155; }',
        setup: function (editor) {
            editor.on('init', function () {
                var container = editor.getContainer();
                container.style.border = '2px solid #6ee7b7';
                container.style.borderRadius = '0.5rem';
                container.style.boxShadow = '0 1px 2px 0 rgba(0, 0, 0, 0.05)';
                container.style.overflow = 'hidden';
            });
        }
    });
</script>
</div>
@endsection

