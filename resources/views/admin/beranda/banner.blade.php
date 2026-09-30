@extends('layouts.admin')

@section('title', 'Hero Banner')
@section('header-title', 'Hero Banner')
@section('header-subtitle', 'Mengatur gambar hero banner beranda.')

@section('content')
<div id="data-container">
<div class="space-y-6">

    <form action="{{ route('admin.beranda.update') }}" method="POST" enctype="multipart/form-data" @submit.prevent="window.submitAjax($event)" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
        @csrf
        <input type="hidden" name="section" value="banner">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900">Hero Banner</h3>
        </div>
        <div class="space-y-4 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1.5">Gambar Hero Banner (Min. 1920x1080px)</label>
                <input type="file" name="hero_image" accept="image/jpeg, image/png, image/webp" @change="const file = $event.target.files[0]; if(file) { $dispatch('open-cropper', { file: file, aspectRatio: 16/9, onCrop: (blob, url) => { let dt = new DataTransfer(); dt.items.add(new File([blob], file.name, {type: file.type})); $event.target.files = dt.files; } }) }" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-[10px] text-slate-500 mt-1.5 italic">Biarkan kosong jika tidak ingin mengganti file saat ini.</p>
                @if(!empty($profile['hero_image']))
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $profile['hero_image']) }}" class="w-full sm:w-1/2 h-auto object-cover rounded-lg border border-slate-200 shadow-sm" alt="Hero Banner Saat Ini">
                    </div>
                @endif
            </div>
        </div>
        <div class="pt-3 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl transition">Simpan Perubahan</button>
        </div>
    </form>
</div>
</div>
@endsection

