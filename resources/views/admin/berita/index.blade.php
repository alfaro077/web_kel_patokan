@extends('layouts.admin')

@section('title', 'Manajemen Berita & Artikel')
@section('header-title', 'Manajemen Berita & Artikel Kelurahan')
@section('header-subtitle', 'Publikasikan kabar kelurahan, foto unggulan, dan atur berita yang ditampilkan di beranda')

@section('content')
<div class="space-y-6" x-data="{ createModalOpen: false, editModalOpen: false, selectedPost: null,
    async submitForm(e, modalName) {
        if (typeof tinymce !== 'undefined') tinymce.triggerSave();
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
                
                if(modalName) this[modalName] = false;
                Swal.fire({
                    icon: 'success', title: 'Berhasil', text: 'Data berhasil disimpan!', timer: 1500, showConfirmButton: false
                });
                form.reset();
            } else {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Terjadi kesalahan saat menyimpan data.' });
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi bermasalah.' });
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }
}">

    <!-- Header Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <span>Daftar Artikel & Kabar Kelurahan</span>
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola konten informasi publik yang ditayangkan di portal masyarakat</p>
        </div>

        <button @click="createModalOpen = true" 
           class="px-4 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-extrabold text-xs rounded-xl shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tulis Berita Baru</span>
        </button>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.berita.index') }}" class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="w-full sm:w-80 relative">
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}"
                       placeholder="Cari judul berita..."
                       class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 shadow-sm">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <div class="flex items-center gap-2.5 w-full sm:w-auto">
                <select name="category_id" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600 shadow-sm font-medium text-slate-700">
                    <option value="all" {{ request('category_id') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-3.5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow transition whitespace-nowrap">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table Container -->
    <div id="data-container" class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        
        <div class="overflow-x-auto min-w-full">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-100/80 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-4 sm:px-5">Foto Unggulan</th>
                        <th class="py-3.5 px-4 sm:px-5">Judul Berita & Kutipan</th>
                        <th class="py-3.5 px-4 sm:px-5">Kategori</th>
                        <th class="py-3.5 px-4 sm:px-5 text-center">Tayangan</th>
                        <th class="py-3.5 px-4 sm:px-5 text-center">Status & Tgl</th>
                        <th class="py-3.5 px-4 sm:px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @forelse($posts as $post)
                        <tr class="hover:bg-slate-50 transition">
                            <!-- Thumbnail Foto -->
                            <td class="py-3.5 px-4 sm:px-5 whitespace-nowrap">
                                <div class="w-16 h-12 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                    @if($post->image)
                                        <img src="{{ str_starts_with($post->image ?? '', 'http') ? $post->image : asset('storage/' . $post->image) }}" 
                                             alt="{{ $post->title }}"
                                             class="w-full h-full object-cover"
                                             onerror="this.src='https://images.unsplash.com/photo-1585829365295-ab7cd400c167?auto=format&fit=crop&w=300&q=80'">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1585829365295-ab7cd400c167?auto=format&fit=crop&w=300&q=80" 
                                             alt="Default News"
                                             class="w-full h-full object-cover">
                                    @endif
                                </div>
                            </td>

                            <!-- Judul & Excerpt -->
                            <td class="py-3.5 px-4 sm:px-5">
                                <div class="font-bold text-slate-900 text-xs sm:text-sm max-w-md line-clamp-1">
                                    {{ $post->title }}
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5 line-clamp-1 max-w-md">
                                    {{ $post->excerpt }}
                                </div>
                                <div class="flex items-center gap-1.5 mt-1">
                                    @if($post->is_featured)
                                        <span class="px-1.5 py-0.5 text-[9px] font-extrabold bg-amber-100 text-amber-900 rounded">UTAMA</span>
                                    @endif
                                    @if($post->is_slider)
                                        <span class="px-1.5 py-0.5 text-[9px] font-extrabold bg-blue-100 text-blue-900 rounded">BANNER SLIDER</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Kategori -->
                            <td class="py-3.5 px-4 sm:px-5 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-[10px] font-bold bg-emerald-50 text-emerald-900 rounded-lg border border-emerald-200">
                                    {{ $post->category->name ?? 'Berita Umum' }}
                                </span>
                            </td>

                            <!-- Views -->
                            <td class="py-3.5 px-4 sm:px-5 text-center whitespace-nowrap font-mono font-bold text-slate-700">
                                {{ number_format($post->views ?? 0) }}x
                            </td>

                            <!-- Tanggal Publikasi -->
                            <td class="py-3.5 px-4 sm:px-5 text-center whitespace-nowrap">
                                @if($post->published_at)
                                    <span class="px-2 py-0.5 text-[10px] font-bold bg-emerald-100 text-emerald-800 rounded border border-emerald-200">PUBLISHED</span>
                                    <div class="text-[10px] text-slate-500 mt-1">{{ $post->published_at->translatedFormat('d M Y') }}</div>
                                @else
                                    <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-800 rounded border border-amber-200">DRAFT</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 sm:px-5 text-right whitespace-nowrap space-x-1 flex justify-end items-center">
                                <!-- Toggle Featured Button -->
                                <form action="{{ route('admin.berita.toggle_featured', $post->id) }}" method="POST" class="inline-block mr-1" @submit.prevent="window.submitAjax($event)">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-1.5 rounded-lg transition {{ $post->is_featured ? 'text-amber-500 hover:bg-amber-100' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-200' }}" title="{{ $post->is_featured ? 'Hapus dari Berita Utama' : 'Jadikan Berita Utama' }}">
                                        <svg class="w-4 h-4" fill="{{ $post->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                    </button>
                                </form>

                                @php
                                    // Prepare safe image preview url
                                    $safePreviewUrl = str_starts_with($post->image ?? '', 'http') ? $post->image : ($post->image ? asset('storage/' . $post->image) : null);
                                    $postData = $post->toArray();
                                    $postData['preview_url'] = $safePreviewUrl;
                                    $postData['status'] = $post->published_at ? 'published' : 'draft';
                                @endphp
                                <button @click="selectedPost = {{ json_encode($postData) }}; editModalOpen = true" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-[11px] rounded-lg transition border border-slate-300 inline-block">
                                    Edit
                                </button>

                                @if(auth()->user()->isAdmin())
                                <form action="{{ route('admin.berita.destroy', $post->id) }}" method="POST" class="inline" onsubmit="event.preventDefault(); window.ajaxDelete(this.action, document.querySelector('meta[name=csrf-token]').getAttribute('content'), 'Hapus berita ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-800 font-bold text-[11px] rounded-lg transition border border-rose-200">
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <p class="font-semibold text-slate-600">Belum ada berita / artikel yang dipublikasikan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-200 text-xs text-slate-500">
            {{ $posts->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH BERITA -->
    <div x-show="createModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-6">
            <div x-show="createModalOpen"  class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity"></div>

            <div x-show="createModalOpen" class="relative inline-block bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all max-w-4xl w-full border border-slate-200 my-8">
                <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm($event, 'createModalOpen')">
                    @csrf
                    
                    <div class="bg-gradient-to-r from-emerald-950 to-slate-900 px-6 py-4 text-white flex items-center justify-between">
                        <h3 class="text-base font-bold">Tulis Berita & Artikel Baru</h3>
                        <button type="button" @click="createModalOpen = false" class="text-emerald-300 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-5 text-xs max-h-[70vh] overflow-y-auto">
                        <!-- Judul Berita -->
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul Berita / Artikel *</label>
                            <input type="text" name="title" required placeholder="Contoh: Pelaksanaan Kerja Bakti Massal" class="w-full text-xs p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 shadow-sm font-semibold">
                        </div>

                        <!-- Grid Kategori & Foto -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider text-xs">Kategori Berita <span class="text-rose-500">*</span></label>
                                    <div class="flex items-center gap-1.5 text-xs font-bold">
                                        <button type="button" onclick="manageCategoryInline('add', 'berita', 'create_berita_category_id')" class="text-emerald-600 hover:text-emerald-700 transition flex items-center gap-0.5">
                                            <span>+ Tambah</span>
                                        </button>
                                        <span class="text-slate-300">|</span>
                                        <button type="button" onclick="manageCategoryInline('delete', 'berita', 'create_berita_category_id')" class="text-rose-600 hover:text-rose-700 transition flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <span>Hapus</span>
                                        </button>
                                    </div>
                                </div>
                                <select id="create_berita_category_id" data-category-type="berita" name="category_id" required class="w-full text-xs p-3 border border-slate-300 rounded-xl bg-white focus:ring-2 focus:ring-emerald-600 shadow-sm font-medium">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-data="{ imageMode: 'file', livePreview: null }">
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Foto Sampul (Thumbnail)</label>
                                
                                <div class="flex items-center p-1 bg-slate-100 rounded-lg w-max mb-3 border border-slate-200">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="image_mode" value="file" x-model="imageMode" class="sr-only peer">
                                        <div class="px-3 py-1.5 text-[11px] font-bold text-slate-500 rounded-md peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:shadow-sm transition">Upload File</div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="image_mode" value="url" x-model="imageMode" class="sr-only peer">
                                        <div class="px-3 py-1.5 text-[11px] font-bold text-slate-500 rounded-md peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:shadow-sm transition">Gunakan Link URL</div>
                                    </label>
                                </div>

                                <div x-show="imageMode === 'file'">
                                    <input type="file" name="image_file" accept="image/jpeg, image/png, image/webp" @change="const file = $event.target.files[0]; if(file) { $dispatch('open-cropper', { file: file, aspectRatio: 16/9, onCrop: (blob, url) => { let dt = new DataTransfer(); dt.items.add(new File([blob], file.name, {type: file.type})); $event.target.files = dt.files; livePreview = url; } }) }" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                </div>

                                <div x-show="imageMode === 'url'" x-cloak>
                                    <input type="url" name="image_url" placeholder="https://contoh.com/gambar.jpg" @input="livePreview = $event.target.value" class="w-full text-xs p-3 border border-slate-300 rounded-xl bg-white focus:ring-2 focus:ring-emerald-600 font-mono">
                                </div>

                                <template x-if="livePreview">
                                    <div class="mt-2.5 p-2.5 bg-slate-50 rounded-2xl border border-slate-200 flex items-center gap-3">
                                        <img :src="livePreview" class="w-14 h-14 rounded-xl object-cover border-2 border-emerald-500 shadow-sm shrink-0" onerror="this.src='https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=500&q=80'">
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Excerpt -->
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kutipan Ringkas (Excerpt)</label>
                            <textarea name="excerpt" rows="2" placeholder="Ringkasan singkat berita..." class="w-full text-xs p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 shadow-sm"></textarea>
                        </div>

                        <!-- Content -->
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Isi Lengkap Berita & Artikel *</label>
                            <textarea name="content" rows="10" required placeholder="Tuliskan berita lengkap di sini..." 
                            x-init="
                                tinymce.init({
        toolbar_mode: 'sliding',
                                    target: $el,
                                    plugins: 'lists link image media table code help fullscreen wordcount',
                                    toolbar: 'styles | bold underline removeformat | forecolor backcolor | bullist numlist align | table | link image media | fullscreen code help',
                                    menubar: false,
                                    height: 350,
                                    setup: function (editor) {
                                        editor.on('init', function () {
                                            var container = editor.getContainer();
                                            container.style.border = '2px solid #6ee7b7';
                                            container.style.borderRadius = '0.5rem';
                                            container.style.boxShadow = '0 1px 2px 0 rgba(0, 0, 0, 0.05)';
                                            container.style.overflow = 'hidden';
                                        });
                                        editor.on('change', function () {
                                            editor.save();
                                        });
                                    }
                                });
                            "
                            class="w-full text-xs p-4 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 shadow-sm leading-relaxed font-sans"></textarea>
                        </div>

                        <!-- Status Publikasi -->
                        <div class="pt-2">
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status Publikasi *</label>
                            <select name="status" required class="w-full text-xs p-3 border border-slate-300 rounded-xl bg-white focus:ring-2 focus:ring-emerald-600 shadow-sm font-bold text-slate-800">
                                <option value="published">🟢 Publikasikan Langsung</option>
                                <option value="draft">🟠 Simpan Sebagai Draft</option>
                            </select>
                        </div>

                        <!-- Flags -->
                        <div class="flex items-center gap-6 pt-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="font-bold text-slate-800">Berita Utama (Featured)</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_slider" value="1" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="font-bold text-slate-800">Banner Slider Beranda</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-200">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 text-slate-600 font-semibold rounded-xl hover:bg-slate-200">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-extrabold rounded-xl shadow transition">Publikasikan Berita</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT BERITA -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-6">
            <div x-show="editModalOpen"  class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity"></div>

            <div x-show="editModalOpen" class="relative inline-block bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all max-w-4xl w-full border border-slate-200 my-8">
                <template x-if="selectedPost">
                    <form :action="'{{ url('admin/berita') }}/' + selectedPost.id" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm($event, 'editModalOpen')">
                        @csrf
                        @method('PUT')
                        
                        <div class="bg-gradient-to-r from-sky-950 to-slate-900 px-6 py-4 text-white flex items-center justify-between">
                            <h3 class="text-base font-bold">Edit Berita & Artikel</h3>
                            <button type="button" @click="editModalOpen = false" class="text-sky-300 hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-5 text-xs max-h-[70vh] overflow-y-auto">
                            <!-- Judul Berita -->
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul Berita / Artikel *</label>
                                <input type="text" name="title" x-model="selectedPost.title" required class="w-full text-xs p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-600 font-semibold">
                            </div>

                            <!-- Grid Kategori & Foto -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <label class="block font-bold text-slate-700 uppercase tracking-wider text-xs">Kategori Berita <span class="text-rose-500">*</span></label>
                                        <div class="flex items-center gap-1.5 text-xs font-bold">
                                            <button type="button" onclick="manageCategoryInline('add', 'berita', 'edit_berita_category_id')" class="text-emerald-600 hover:text-emerald-700 transition flex items-center gap-0.5">
                                                <span>+ Tambah</span>
                                            </button>
                                            <span class="text-slate-300">|</span>
                                            <button type="button" onclick="manageCategoryInline('delete', 'berita', 'edit_berita_category_id')" class="text-rose-600 hover:text-rose-700 transition flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                    </div>
                                    <select id="edit_berita_category_id" data-category-type="berita" name="category_id" x-model="selectedPost.category_id" required class="w-full text-xs p-3 border border-slate-300 rounded-xl bg-white focus:ring-2 focus:ring-sky-600 font-medium">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div x-data="{ imageMode: 'file', localLivePreview: selectedPost.preview_url }" x-init="$watch('selectedPost', value => { if(value) localLivePreview = value.preview_url })">
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Foto Sampul (Thumbnail)</label>
                                    
                                    <div class="flex items-center p-1 bg-slate-100 rounded-lg w-max mb-3 border border-slate-200">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="image_mode" value="file" x-model="imageMode" class="sr-only peer">
                                            <div class="px-3 py-1.5 text-[11px] font-bold text-slate-500 rounded-md peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:shadow-sm transition">Upload File</div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="image_mode" value="url" x-model="imageMode" class="sr-only peer">
                                            <div class="px-3 py-1.5 text-[11px] font-bold text-slate-500 rounded-md peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:shadow-sm transition">Gunakan Link URL</div>
                                        </label>
                                    </div>

                                    <div x-show="imageMode === 'file'">
                                        <input type="file" name="image_file" accept="image/jpeg, image/png, image/webp" @change="const file = $event.target.files[0]; if(file) { $dispatch('open-cropper', { file: file, aspectRatio: 16/9, onCrop: (blob, url) => { let dt = new DataTransfer(); dt.items.add(new File([blob], file.name, {type: file.type})); $event.target.files = dt.files; localLivePreview = url; } }) }" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                        <p class="text-[10px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengganti foto.</p>
                                    </div>

                                    <div x-show="imageMode === 'url'" x-cloak>
                                        <input type="url" name="image_url" placeholder="https://contoh.com/gambar.jpg" @input="localLivePreview = $event.target.value" class="w-full text-xs p-3 border border-slate-300 rounded-xl bg-white focus:ring-2 focus:ring-sky-600 font-mono">
                                    </div>

                                    <div class="mt-2.5 p-2.5 bg-slate-50 rounded-2xl border border-slate-200 flex items-center gap-3">
                                        <template x-if="localLivePreview">
                                            <img :src="localLivePreview" class="w-14 h-14 rounded-xl object-cover border-2 border-emerald-500 shadow-sm shrink-0" onerror="this.src='https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=500&q=80'">
                                        </template>
                                        <template x-if="!localLivePreview">
                                            <div class="w-14 h-14 rounded-xl bg-slate-200 border-2 border-slate-300 flex items-center justify-center shrink-0">
                                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Excerpt -->
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kutipan Ringkas (Excerpt)</label>
                                <textarea name="excerpt" x-model="selectedPost.excerpt" rows="2" class="w-full text-xs p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-600 shadow-sm"></textarea>
                            </div>

                            <!-- Content -->
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Isi Lengkap Berita & Artikel *</label>
                                <textarea name="content" x-model="selectedPost.content" rows="10" required 
                                x-init="
                                    setTimeout(() => {
                                        tinymce.init({
        toolbar_mode: 'sliding',
                                            target: $el,
                                            plugins: 'lists link image media table code help fullscreen wordcount',
                                            toolbar: 'styles | bold underline removeformat | forecolor backcolor | bullist numlist align | table | link image media | fullscreen code help',
                                            menubar: false,
                                            height: 350,
                                            setup: function (editor) {
                                                editor.on('init', function () {
                                                    var container = editor.getContainer();
                                                    container.style.border = '2px solid #0284c7'; // sky-600 for edit
                                                    container.style.borderRadius = '0.5rem';
                                                    container.style.boxShadow = '0 1px 2px 0 rgba(0, 0, 0, 0.05)';
                                                    container.style.overflow = 'hidden';
                                                });
                                                editor.on('change', function () {
                                                    editor.save();
                                                    $el.dispatchEvent(new Event('input'));
                                                });
                                            }
                                        });
                                    }, 50);
                                "
                                class="w-full text-xs p-4 border border-slate-300 rounded-xl focus:ring-2 focus:ring-sky-600 shadow-sm leading-relaxed font-sans"></textarea>
                            </div>

                            <!-- Status Publikasi -->
                            <div class="pt-2">
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status Publikasi *</label>
                                <select name="status" x-model="selectedPost.status" required class="w-full text-xs p-3 border border-slate-300 rounded-xl bg-white focus:ring-2 focus:ring-sky-600 shadow-sm font-bold text-slate-800">
                                    <option value="published">🟢 Publikasikan</option>
                                    <option value="draft">🟠 Simpan Sebagai Draft</option>
                                </select>
                            </div>

                            <!-- Flags -->
                            <div class="flex items-center gap-6 pt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_featured" value="1" :checked="selectedPost.is_featured" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="font-bold text-slate-800">Berita Utama (Featured)</span>
                                </label>

                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_slider" value="1" :checked="selectedPost.is_slider" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="font-bold text-slate-800">Banner Slider Beranda</span>
                                </label>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-200">
                            <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-slate-600 font-semibold rounded-xl hover:bg-slate-200">Batal</button>
                            <button type="submit" class="px-6 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-extrabold rounded-xl shadow transition">Simpan Perubahan</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>

</div>

<!-- TinyMCE Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>

@endsection
