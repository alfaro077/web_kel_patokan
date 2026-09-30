@extends('layouts.admin')

@section('title', 'Kelola Header Navigasi')
@section('header-title', 'Kelola Header Navigasi')
@section('header-subtitle', 'Manajemen tautan menu Profil, Layanan, dan Dokumen pada Navbar Beranda')

@section('content')
<div x-data="{
    async submitForm(e, alpineComponent) {
        if (typeof tinymce !== 'undefined') tinymce.triggerSave();
        const form = e.target;
        const submitBtn = form.querySelector('button[type=\'submit\']');
        const originalText = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.innerHTML = '<i class=\'fas fa-spinner fa-spin mr-2\'></i>...';
            submitBtn.disabled = true;
        }

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
                
                if (alpineComponent) alpineComponent.open = false;
                Swal.fire({
                    icon: 'success', title: 'Berhasil', text: 'Tindakan berhasil!', timer: 1500, showConfirmButton: false
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Terjadi kesalahan saat menyimpan data.' });
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi bermasalah.' });
        } finally {
            if (submitBtn) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }
    }
}">
<div id="data-container">
<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    Struktur Hierarki Menu Navbar
                </h2>
                <p class="text-sm text-slate-500 mt-1">Atur urutan dan struktur menu yang akan ditampilkan di navbar publik (sesuai nomor urut #).</p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h4 class="text-sm font-bold text-blue-900 mb-1">Informasi Sinkronisasi Menu Otomatis</h4>
                <p class="text-xs text-blue-800 leading-relaxed">Menu dropdown untuk <strong>Layanan</strong> dan <strong>Dokumen</strong> disinkronkan secara otomatis. Setiap kali Anda menambahkan Standar Layanan atau Dokumen Publik baru, tautan menunya akan otomatis dibuat tanpa perlu mengisinya secara manual di sini. Anda dapat langsung menambahkan layanan/dokumen baru melalui tombol <span class="font-semibold bg-blue-100 px-1.5 py-0.5 rounded text-blue-900">Kelola Layanan</span> atau <span class="font-semibold bg-blue-100 px-1.5 py-0.5 rounded text-blue-900">Kelola Dokumen</span> di bawah.</p>
            </div>
        </div>

        <div class="space-y-6">

            <!-- BERANDA (Menu Utama) -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between p-4 gap-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-8 h-8 rounded-full bg-slate-800 text-white font-black text-xs flex items-center justify-center shrink-0">#1</div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-bold text-slate-800 text-lg">Beranda</h3>
                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold tracking-wider uppercase">Menu Utama</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs font-mono text-slate-500 mt-1">
                            <span>URL: <span class="bg-slate-100 px-1 py-0.5 rounded">/</span></span>
                            <span>Tab: Tab Sama (_self)</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto justify-end">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 uppercase border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>
                    <button type="button" class="px-4 py-1.5 text-xs font-bold text-slate-400 bg-slate-50 border border-slate-200 rounded-lg cursor-not-allowed">Statis</button>
                </div>
            </div>

            <!-- PROFIL -->
            @php $profilCount = isset($menus['profil']) ? count($menus['profil']) : 0; @endphp
            <div class="bg-slate-50/50 border border-slate-200 rounded-2xl p-4 shadow-sm relative">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-8 h-8 rounded-full bg-slate-800 text-white font-black text-xs flex items-center justify-center shrink-0">#2</div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-bold text-slate-800 text-lg">Profil</h3>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold tracking-wider uppercase">Dropdown ({{ $profilCount }} Sub-Menu)</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs font-mono text-slate-500 mt-1">
                                <span>URL: <span class="bg-slate-100 px-1 py-0.5 rounded">#</span></span>
                                <span>Tab: Tab Sama (_self)</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto justify-end">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 uppercase border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>
                        <button type="button" @click="$dispatch('open-add-menu-modal', 'profil')" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-sm transition flex items-center gap-1.5 inline-flex">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                            Sub-Menu
                        </button>
                    </div>
                </div>

                <!-- Tree Children -->
                @if($profilCount > 0)
                <div class="mt-4 pl-4 sm:pl-10 relative">
                    <div class="absolute left-6 sm:left-12 top-0 bottom-6 w-px bg-emerald-200"></div>
                    <div class="space-y-3">
                        @foreach($menus['profil'] as $menu)
                        <div class="relative flex flex-col sm:flex-row sm:items-center bg-white border border-emerald-100 rounded-xl p-3 shadow-sm hover:border-emerald-300 transition group ml-6 gap-3 sm:gap-0">
                            <!-- Tree Line Connector -->
                            <div class="absolute -left-6 top-6 sm:top-1/2 w-6 h-px bg-emerald-200"></div>
                            
                            <div class="flex-1 flex flex-col sm:flex-row sm:items-center items-start gap-2 sm:gap-3 w-full">
                                <div class="text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                                <div class="text-xs font-black text-slate-400">#{{ $menu->order }}</div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <div class="font-bold text-slate-800 text-sm">{{ $menu->title }}</div>
                                        @if(in_array($menu->url, ['/visi-misi', '/sejarah', '/struktur-organisasi', '/lembaga']))
                                            <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-700 text-[9px] font-bold uppercase">Menu Utama</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded bg-purple-100 text-purple-700 text-[9px] font-bold uppercase">Sub-Menu Custom</span>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs font-mono text-slate-500 mt-1 overflow-hidden w-full">
                                        <span>URL: {{ $menu->url }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 mt-2 sm:mt-0 w-full sm:w-auto">
                                @if($menu->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 mr-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold text-slate-500 bg-slate-100 border border-slate-200 mr-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif</span>
                                @endif
                                
                                @php
                                    $urlPath = parse_url($menu->url, PHP_URL_PATH);
                                    $isDynamicPage = Str::startsWith($urlPath, '/halaman/');
                                    if ($isDynamicPage) {
                                        $pageSlug = str_replace('/halaman/', '', $urlPath);
                                        $page = \App\Models\Page::where('slug', $pageSlug)->first();
                                    }
                                @endphp
                                @if(in_array($menu->url, ['/visi-misi', '/sejarah']))
                                    <a href="{{ route('admin.beranda.visi_misi_sejarah') }}" class="p-1.5 text-blue-600 hover:bg-blue-50 border border-blue-100 rounded shadow-sm transition" title="Kelola Konten Profil">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                @elseif($menu->url === '/struktur-organisasi')
                                    <a href="{{ route('admin.beranda.sotk') }}" class="p-1.5 text-blue-600 hover:bg-blue-50 border border-blue-100 rounded shadow-sm transition" title="Kelola SOTK">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                @elseif($menu->url === '/lembaga')
                                    <a href="{{ route('admin.beranda.lembaga') }}" class="p-1.5 text-blue-600 hover:bg-blue-50 border border-blue-100 rounded shadow-sm transition" title="Kelola Lembaga">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                @elseif($isDynamicPage && isset($page) && $page->type === 'standard')
                                    <button type="button" @click="$dispatch('open-modal', 'modal-edit-page'); $dispatch('edit-page', { id: {{ $page->id }} })" class="p-1.5 text-emerald-600 hover:bg-emerald-50 border border-emerald-100 rounded shadow-sm transition" title="Edit Konten Halaman">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                @endif
                                
                                <button type="button" @click="$dispatch('open-edit-menu', {{ json_encode($menu) }})" class="p-1.5 text-blue-600 hover:bg-blue-50 border border-blue-100 rounded shadow-sm transition" title="Edit Menu Info">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                
                                @if(!in_array($menu->url, ['/visi-misi', '/sejarah', '/struktur-organisasi', '/lembaga']))
                                <form action="{{ route('admin.navigation.destroy', $menu->id) }}" method="POST" class="inline-block m-0" onsubmit="event.preventDefault(); window.ajaxDelete(this.action, document.querySelector('meta[name=csrf-token]').getAttribute('content'), 'Yakin ingin menghapus tautan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 border border-rose-100 rounded shadow-sm transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- LAYANAN -->
            @php $layananCount = isset($menus['layanan']) ? count($menus['layanan']) : 0; @endphp
            <div class="bg-slate-50/50 border border-slate-200 rounded-2xl p-4 shadow-sm relative">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-8 h-8 rounded-full bg-slate-800 text-white font-black text-xs flex items-center justify-center shrink-0">#3</div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-bold text-slate-800 text-lg">Layanan</h3>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold tracking-wider uppercase">Dropdown ({{ $layananCount }} SOP & Sub-Menu)</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs font-mono text-slate-500 mt-1">
                                <span>URL: <span class="bg-slate-100 px-1 py-0.5 rounded">/layanan</span></span>
                                <span>Tab: Tab Sama (_self)</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto justify-end">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 uppercase border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>
                        <a href="{{ route('admin.jenis-layanan.index') }}" class="px-4 py-1.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-lg shadow-sm transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            Kelola Layanan
                        </a>
                    </div>
                </div>

                <!-- Tree Children -->
                @if($layananCount > 0)
                <div class="mt-4 pl-4 sm:pl-10 relative">
                    <div class="absolute left-6 sm:left-12 top-0 bottom-6 w-px bg-emerald-200"></div>
                    <div class="space-y-3">
                        @foreach($menus['layanan'] as $menu)
                        <div class="relative flex flex-col sm:flex-row sm:items-center bg-white border border-emerald-100 rounded-xl p-3 shadow-sm hover:border-emerald-300 transition group ml-6 gap-3 sm:gap-0">
                            <!-- Tree Line Connector -->
                            <div class="absolute -left-6 top-6 sm:top-1/2 w-6 h-px bg-emerald-200"></div>
                            
                            <div class="flex-1 flex flex-col sm:flex-row sm:items-center items-start gap-2 sm:gap-3 w-full">
                                <div class="text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                                <div class="text-xs font-black text-slate-400">#{{ $menu->order }}</div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <div class="font-bold text-slate-800 text-sm">{{ $menu->title }}</div>
                                        <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-700 text-[9px] font-bold uppercase">SOP Layanan</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs font-mono text-slate-500 mt-1 overflow-hidden w-full">
                                        <span>URL: {{ $menu->url }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 mt-2 sm:mt-0 w-full sm:w-auto">
                                @if($menu->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 mr-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold text-slate-500 bg-slate-100 border border-slate-200 mr-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif</span>
                                @endif
                                
                                <button type="button" @click="$dispatch('open-edit-menu', {{ json_encode($menu) }})" class="p-1.5 text-blue-600 hover:bg-blue-50 border border-blue-100 rounded shadow-sm transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                
                                <form action="{{ route('admin.navigation.destroy', $menu->id) }}" method="POST" class="inline-block m-0" onsubmit="event.preventDefault(); window.ajaxDelete(this.action, document.querySelector('meta[name=csrf-token]').getAttribute('content'), 'Yakin ingin menghapus tautan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 border border-rose-100 rounded shadow-sm transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- DOKUMEN -->
            @php $dokumenCount = isset($menus['dokumen']) ? count($menus['dokumen']) : 0; @endphp
            <div class="bg-slate-50/50 border border-slate-200 rounded-2xl p-4 shadow-sm relative">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-8 h-8 rounded-full bg-slate-800 text-white font-black text-xs flex items-center justify-center shrink-0">#4</div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-bold text-slate-800 text-lg">Dokumen</h3>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold tracking-wider uppercase">Dropdown ({{ $dokumenCount }} Sub-Menu)</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs font-mono text-slate-500 mt-1">
                                <span>URL: <span class="bg-slate-100 px-1 py-0.5 rounded">#</span></span>
                                <span>Tab: Tab Sama (_self)</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto justify-end">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 uppercase border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>
                        <a href="{{ route('admin.documents.index') }}" class="px-4 py-1.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-lg shadow-sm transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            Kelola Dokumen
                        </a>
                    </div>
                </div>

                <!-- Tree Children -->
                @if($dokumenCount > 0)
                <div class="mt-4 pl-4 sm:pl-10 relative">
                    <div class="absolute left-6 sm:left-12 top-0 bottom-6 w-px bg-emerald-200"></div>
                    <div class="space-y-3">
                        @foreach($menus['dokumen'] as $menu)
                        <div class="relative flex flex-col sm:flex-row sm:items-center bg-white border border-emerald-100 rounded-xl p-3 shadow-sm hover:border-emerald-300 transition group ml-6 gap-3 sm:gap-0">
                            <!-- Tree Line Connector -->
                            <div class="absolute -left-6 top-6 sm:top-1/2 w-6 h-px bg-emerald-200"></div>
                            
                            <div class="flex-1 flex flex-col sm:flex-row sm:items-center items-start gap-2 sm:gap-3 w-full">
                                <div class="text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                                <div class="text-xs font-black text-slate-400">#{{ $menu->order }}</div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <div class="font-bold text-slate-800 text-sm">{{ $menu->title }}</div>
                                        <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-[9px] font-bold uppercase">Dokumen File</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs font-mono text-slate-500 mt-1 overflow-hidden w-full">
                                        <span>URL: {{ $menu->url }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 mt-2 sm:mt-0 w-full sm:w-auto">
                                @if($menu->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 mr-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-bold text-slate-500 bg-slate-100 border border-slate-200 mr-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif</span>
                                @endif
                                
                                <button type="button" @click="$dispatch('open-edit-menu', {{ json_encode($menu) }})" class="p-1.5 text-blue-600 hover:bg-blue-50 border border-blue-100 rounded shadow-sm transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                
                                <form action="{{ route('admin.navigation.destroy', $menu->id) }}" method="POST" class="inline-block m-0" onsubmit="event.preventDefault(); window.ajaxDelete(this.action, document.querySelector('meta[name=csrf-token]').getAttribute('content'), 'Yakin ingin menghapus tautan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 border border-rose-100 rounded shadow-sm transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- INFORMASI (Menu Statis) -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between p-4 gap-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-8 h-8 rounded-full bg-slate-800 text-white font-black text-xs flex items-center justify-center shrink-0">#5</div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-bold text-slate-800 text-lg">Informasi</h3>
                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold tracking-wider uppercase">Dropdown Default</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs font-mono text-slate-500 mt-1">
                            <span>Berita, Pengumuman, Agenda, Galeri</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto justify-end">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 uppercase border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>
                    <button type="button" class="px-4 py-1.5 text-xs font-bold text-slate-400 bg-slate-50 border border-slate-200 rounded-lg cursor-not-allowed">Statis</button>
                </div>
            </div>

            <!-- HUBUNGI (Menu Statis) -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between p-4 gap-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-8 h-8 rounded-full bg-slate-800 text-white font-black text-xs flex items-center justify-center shrink-0">#6</div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-bold text-slate-800 text-lg">Hubungi</h3>
                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold tracking-wider uppercase">Dropdown Default</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs font-mono text-slate-500 mt-1">
                            <span>Lokasi, WhatsApp, SP4N</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto justify-end">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 uppercase border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>
                    <button type="button" class="px-4 py-1.5 text-xs font-bold text-slate-400 bg-slate-50 border border-slate-200 rounded-lg cursor-not-allowed">Statis</button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Tambah Menu -->
<div x-data="{ 
        open: false, 
        section: 'profil',
        profilUrlType: 'new',
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
                        plugins: 'lists link image media table code help fullscreen wordcount',
                        toolbar: 'styles | bold italic underline removeformat | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | table | link image media | fullscreen code help',
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
            <form action="{{ route('admin.navigation.store') }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm($event, $data)">
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
                    <div x-show="section === 'profil' && profilUrlType === 'new'" class="border border-emerald-100 rounded-2xl bg-emerald-50/20 p-5 space-y-5 mt-4">
                        <h4 class="text-sm font-bold text-slate-800 border-b border-emerald-100 pb-2">Konten Halaman Baru</h4>
                        
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1.5">Teks Header / Judul Halaman Utama <span class="text-rose-500">*</span></label>
                            <input type="text" name="page_title" :required="section === 'profil'" placeholder="Contoh: Sejarah Singkat Kelurahan Patokan" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1.5">Deskripsi Lengkap Dibawah Header (Opsional)</label>
                            <textarea name="page_subtitle" rows="2" placeholder="Contoh: Menelusuri jejak langkah berdirinya Kelurahan Patokan dari masa ke masa..." class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1.5">Foto Banner (Opsional)</label>
                            <input type="file" name="page_banner" accept="image/png, image/jpeg, image/webp" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
                        </div>
                        
                        <div x-show="section === 'profil' && profilUrlType === 'new'">
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1.5">Isi Teks Utama Lengkap</label>
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
                    <button type="submit" class="px-6 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm rounded-xl shadow-sm transition flex items-center gap-2">
                        <span>Simpan Menu Baru</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Menu -->
<div x-data="{ open: false, data: {} }" @open-edit-menu.window="data = $event.detail; open = true" x-show="open" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <div x-show="open" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        <div x-show="open" class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg w-full">
            <form :action="`/admin/navigation/${data.id}`" method="POST" @submit.prevent="submitForm($event, $data)">
                @csrf @method('PUT')
                <div class="bg-white px-6 pt-6 pb-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800" id="modal-title">Edit Tautan Menu</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Bagian (Dropdown)</label>
                        <select name="section" x-model="data.section" required class="w-full px-3 py-2 border border-slate-200 rounded-lg bg-slate-50 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="profil">Dropdown Profil</option>
                            <option value="layanan">Dropdown Layanan</option>
                            <option value="dokumen">Dropdown Dokumen</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Label Tautan</label>
                        <input type="text" name="title" x-model="data.title" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Target Halaman / Dokumen</label>
                        
                        <!-- Tampilkan Target Secara Readonly -->
                        <div class="relative">
                            <input type="text" :value="data.url" disabled class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-slate-100 text-slate-500 cursor-not-allowed">
                            <div class="absolute inset-y-0 right-3 flex items-center">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-500">Target tautan tidak dapat diubah setelah dibuat demi menjaga integritas data sistem.</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Urutan Tampil</label>
                            <input type="number" name="order" x-model="data.order" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Status Tampil</label>
                            <label class="inline-flex items-center mt-2">
                                <input type="checkbox" name="is_active" value="1" :checked="data.is_active" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                                <span class="ml-2 text-sm text-slate-700 font-semibold">Tampilkan</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" @click="open = false" class="px-4 py-2 text-slate-600 font-semibold text-sm hover:bg-slate-200 rounded-xl transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-sm transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Konten Halaman -->
<div x-data="{ 
        open: false, 
        pageId: null, 
        title: '', 
        subtitle: '',
        slug: '', 
        bannerUrl: '',
        content: '',
        loading: false,
        editor: null
    }" 
    @open-modal.window="if($event.detail === 'modal-edit-page') { open = true; }" 
    @edit-page.window="
        pageId = $event.detail.id;
        loading = true;
        title = ''; subtitle = ''; slug = ''; bannerUrl = ''; content = '';
        if(editor) { tinymce.remove('#edit-inline-page-editor'); editor = null; }
        fetch('{{ url('admin/navigation/page') }}/' + pageId)
            .then(res => res.json())
            .then(data => {
                title = data.title;
                subtitle = data.subtitle || '';
                slug = data.slug;
                bannerUrl = data.banner_image;
                content = data.content || '';
                loading = false;
                setTimeout(() => {
                    if(typeof tinymce !== 'undefined') {
                        tinymce.init({
                            toolbar_mode: 'sliding',
                            selector: '#edit-inline-page-editor',
                            height: 350,
                            menubar: false,
                            plugins: 'lists link image media table code help fullscreen wordcount',
                            toolbar: 'styles | bold italic underline removeformat | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | table | link image media | fullscreen code help',
                            setup: function (ed) {
                                editor = ed;
                                ed.on('init', function () {
                                    ed.setContent(content);
                                });
                                ed.on('change', function () {
                                    ed.save();
                                });
                            }
                        });
                    }
                }, 100);
            });
    "
    x-show="open" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <div x-show="open" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        <div x-show="open" class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-3xl w-full">
            <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Edit Konten Halaman
                </h3>
                <button @click="open = false; if(editor) { tinymce.remove('#edit-inline-page-editor'); editor = null; }" type="button" class="text-emerald-100 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form :action="'{{ url('admin/navigation/page') }}/' + pageId" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm($event, $data)">
                @csrf
                @method('PUT')
                <div class="px-6 py-5">
                    
                    <div x-show="loading" class="py-10 flex flex-col items-center justify-center text-emerald-600 font-bold animate-pulse">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Memuat data halaman...
                    </div>
                    
                    <div x-show="!loading" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Judul Halaman <span class="text-rose-500">*</span></label>
                            <input type="text" name="page_title" x-model="title" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Deskripsi Lengkap Dibawah Header (Opsional)</label>
                            <textarea name="page_subtitle" x-model="subtitle" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Slug / Tautan</label>
                            <div class="flex items-center">
                                <span class="px-3 py-2 bg-slate-100 border border-r-0 border-slate-200 rounded-l-lg text-sm text-slate-500 font-mono">/halaman/</span>
                                <input type="text" name="page_slug" :value="title ? title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '') : slug" readonly class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-r-lg text-sm text-slate-500 focus:outline-none font-mono cursor-not-allowed">
                            </div>
                            <p class="mt-1 text-[11px] text-slate-500">Tautan ini dibuat secara otomatis berdasarkan judul halaman.</p>
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1.5">Ganti Foto Banner (Opsional)</label>
                            <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto justify-end">
                                <template x-if="bannerUrl">
                                    <img :src="bannerUrl" class="w-16 h-10 object-cover rounded shadow-sm border border-slate-200">
                                </template>
                                <div class="flex-1">
                                    <input type="file" name="page_banner" accept="image/png, image/jpeg, image/webp" 
                                           @change="const file = $event.target.files[0]; if(file) { $dispatch('open-cropper', { file: file, aspectRatio: 16/9, onCrop: (blob, url) => { let dt = new DataTransfer(); dt.items.add(new File([blob], file.name, {type: file.type})); $event.target.files = dt.files; bannerUrl = url; } }) }"
                                           class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1.5">Isi Halaman Lengkap</label>
                            <textarea id="edit-inline-page-editor" name="page_content" class="w-full"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-200">
                    <button type="button" @click="open = false; if(editor) { tinymce.remove('#edit-inline-page-editor'); editor = null; }" class="px-4 py-2 text-sm text-slate-600 font-semibold rounded-xl hover:bg-slate-200 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
</div>
</div>
@endsection

