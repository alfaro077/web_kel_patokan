@extends('layouts.admin')

@section('title', 'Maklumat Pelayanan')
@section('header-title', 'Maklumat Pelayanan Publik')
@section('header-subtitle', 'Mengatur teks janji layanan yang muncul pada beranda.')

@section('content')
<div id="data-container">
<div class="space-y-6">

    <form x-data="{}" action="{{ route('admin.beranda.update-kemitraan') }}" method="POST" enctype="multipart/form-data" @submit.prevent="tinymce.triggerSave(); window.submitAjax($event)" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
        @csrf
        <input type="hidden" name="section" value="maklumat">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900">Teks Maklumat Pelayanan Publik</h3>
            <p class="text-xs text-slate-500">Teks ini akan muncul ketika warga mengklik tombol Maklumat Pelayanan di Beranda.</p>
        </div>
        
        <div class="space-y-4 text-xs">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Judul Kartu Maklumat *</label>
                    <input type="text" name="maklumat_card_title" required value="{{ old('maklumat_card_title', $profile['maklumat_card_title'] ?? 'Maklumat Pelayanan Publik Resmi') }}" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-600">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">Deskripsi Singkat Kartu *</label>
                    <textarea name="maklumat_card_desc" rows="3" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-600">{{ old('maklumat_card_desc', $profile['maklumat_card_desc'] ?? 'Komitmen penuh seluruh jajaran aparatur Kelurahan Patokan dalam memberikan hak pelayanan terbaik bagi seluruh warga.') }}</textarea>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1.5">Kutipan Maklumat (Quote) *</label>
                <textarea name="maklumat_card_quote" rows="2" required class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-600">{{ old('maklumat_card_quote', $profile['maklumat_card_quote'] ?? '"Dengan ini kami menyatakan sanggup menyelenggarakan pelayanan sesuai standar yang ditetapkan dan siap menerima sanksi apabila melanggar."') }}</textarea>
            </div>

            <div class="border-t border-slate-100 my-4 pt-4"></div>

            <div>
                <label class="block font-bold text-slate-700 mb-1.5">Foto Maklumat Pelayanan</label>
                @if(!empty($profile['maklumat_image']))
                    <div class="mb-3 text-emerald-600 font-medium text-sm flex items-center gap-1.5">
                        <i class="fas fa-image"></i>
                        <span>Foto saat ini telah terunggah.</span>
                        <a href="{{ asset('storage/' . $profile['maklumat_image']) }}" target="_blank" class="underline hover:text-emerald-800 ml-2">Lihat Foto</a>
                    </div>
                @endif
                <input type="file" name="maklumat_image" accept="image/jpeg, image/png, image/webp" @change="const file = $event.target.files[0]; if(file) { $dispatch('open-cropper', { file: file, aspectRatio: 4/3, onCrop: (blob, url) => { let dt = new DataTransfer(); dt.items.add(new File([blob], file.name, {type: file.type})); $event.target.files = dt.files; } }) }" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-[11px] text-slate-500 mt-1">Format: JPG, PNG, WEBP. Maks: {{ $systemSettings['max_upload_foto_mb'] ?? 2 }}MB. Jika diunggah, foto ini akan langsung ditampilkan kepada publik saat tombol ditekan.</p>
                @error('maklumat_image')
                    <span class="text-xs text-rose-500 font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1.5">Teks Maklumat Alternatif (Opsional)</label>
                <textarea name="maklumat_text" rows="5" class="tinymce-editor w-full p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600">{{ old('maklumat_text', $profile['maklumat_text'] ?? "Dengan ini, kami seluruh ASN dan Pegawai Pemerintah Kelurahan Patokan menyatakan sanggup menyelenggarakan pelayanan sesuai standar pelayanan yang telah ditetapkan dan siap menerima sanksi sesuai ketentuan perundang-undangan yang berlaku apabila pelayanan tidak sesuai janji.") }}</textarea>
                <p class="text-[11px] text-slate-500 mt-1">Teks ini akan ditampilkan jika Anda belum mengunggah foto di atas.</p>
            </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl transition">Simpan Maklumat</button>
        </div>
    </form>
</div>

<!-- TinyMCE Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        toolbar_mode: 'sliding',
        selector: '.tinymce-editor',
        plugins: 'lists link image media table code help fullscreen wordcount',
        toolbar: 'styles | bold underline removeformat | forecolor backcolor | bullist numlist align | table | link image media | fullscreen code help',
        menubar: false,
        height: 300,
        placeholder: 'Ketik konten di sini (bisa sisipkan gambar/tabel)...',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 14px; color: #334155; }',
        setup: function (editor) {
            editor.on('init', function () {
                var container = editor.getContainer();
                container.style.border = '2px solid #6ee7b7'; // emerald-300 / hijau
                container.style.borderRadius = '0.5rem';
                container.style.boxShadow = '0 1px 2px 0 rgba(0, 0, 0, 0.05)';
                container.style.overflow = 'hidden';
            });
        }
    });
</script>
</div>
@endsection
