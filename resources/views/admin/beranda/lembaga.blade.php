@extends('layouts.admin')

@section('title', 'Lembaga Kemasyarakatan')
@section('header-title', 'Lembaga Kemasyarakatan Kelurahan')
@section('header-subtitle', 'Kelola daftar lembaga kemasyarakatan (RT/RW, PKK, Karang Taruna, dll).')

@section('content')
<div id="data-container">
<div class="space-y-6" x-data="{
    createModalOpen: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
    editModalOpen: {{ $errors->any() && old('_method') === 'PUT' ? 'true' : 'false' }},
    selectedLembaga: {!! $errors->any() && old('_method') === 'PUT' ? json_encode(['id' => old('id'), 'name' => old('name'), 'singkatan' => old('singkatan'), 'ketua' => old('ketua'), 'description' => old('description'), 'logo' => old('existing_logo')]) : 'null' !!},
    selectedIndex: null
}">

    <!-- Data Table Container -->
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Header Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 sm:p-5 border-b border-slate-200">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-users-cog text-emerald-700 shrink-0"></i>
                    <span>Daftar Lembaga Kemasyarakatan</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar lembaga seperti RT/RW, PKK, Posyandu, dll yang ada di kelurahan.</p>
            </div>

            <button @click="createModalOpen = true" 
               class="px-4 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-extrabold text-xs rounded-xl shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 shrink-0">
                <i class="fas fa-plus"></i>
                <span>Tambah Lembaga</span>
            </button>
        </div>

        <div class="overflow-x-auto min-w-full">
            <table class="w-full text-left border-collapse min-w-[750px]">
                <thead>
                    <tr class="bg-slate-100/80 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-4 sm:px-5">Logo</th>
                        <th class="py-3.5 px-4 sm:px-5">Nama Lembaga</th>
                        <th class="py-3.5 px-4 sm:px-5">Ketua/Pimpinan</th>
                        <th class="py-3.5 px-4 sm:px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @forelse($profile['lembaga'] ?? [] as $index => $l)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4 sm:px-5 w-24">
                                @if(!empty($l['logo']))
                                    <div class="w-16 h-12 bg-white border border-slate-200 rounded-lg p-1.5 flex items-center justify-center shadow-sm">
                                        <img src="{{ asset('storage/' . $l['logo']) }}" alt="{{ $l['name'] }}" class="max-w-full max-h-full object-contain">
                                    </div>
                                @else
                                    <div class="w-16 h-12 bg-slate-100 border border-slate-200 rounded-lg flex items-center justify-center text-slate-400">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 sm:px-5 font-bold text-slate-900">
                                {{ $l['name'] }}
                                @if(!empty($l['singkatan']))
                                    <span class="ml-1 px-1.5 py-0.5 bg-slate-100 text-slate-600 rounded text-[9px] uppercase font-bold">{{ $l['singkatan'] }}</span>
                                @endif
                                @if(!empty($l['description']))
                                    <p class="text-[11px] font-normal text-slate-500 mt-1 line-clamp-1">{{ $l['description'] }}</p>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 sm:px-5 text-slate-700">
                                {{ $l['ketua'] ?? '-' }}
                            </td>

                            <td class="py-3.5 px-4 sm:px-5 text-right whitespace-nowrap space-x-1">
                                <button @click="selectedLembaga = {{ json_encode($l) }}; selectedIndex = {{ $index }}; editModalOpen = true;" class="inline-block px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-[11px] rounded-lg transition border border-slate-300">
                                    Edit
                                </button>

                                <form action="{{ route('admin.beranda.lembaga.destroy', $l['id'] ?? '') }}" method="POST" class="inline" onsubmit="event.preventDefault(); window.ajaxDelete(this.action, document.querySelector('meta[name=csrf-token]').getAttribute('content'), 'Hapus lembaga ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-800 font-bold text-[11px] rounded-lg transition border border-rose-200">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-slate-200">
                                    <i class="fas fa-users-cog text-xl text-slate-400"></i>
                                </div>
                                <p class="font-semibold text-slate-600">Belum ada Lembaga Kemasyarakatan yang ditambahkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL 1: TAMBAH LEMBAGA -->
    <div x-show="createModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"></div>

            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl max-w-xl w-full border border-slate-200 my-8">
                <form action="{{ route('admin.beranda.lembaga.store') }}" method="POST" enctype="multipart/form-data" @submit.prevent="window.submitAjax($event, 'createModalOpen', $data)">
                    @csrf
                    
                    <div class="bg-white px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-600 inline-block"></span>
                            <h3 class="text-base font-bold text-slate-800">Tambah Lembaga Baru</h3>
                        </div>
                        <button type="button" @click="createModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 text-xs text-slate-700 max-h-[80vh] overflow-y-auto">
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Nama Lembaga <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required value="{{ old('name') }}" placeholder="Misal: Pemberdayaan Kesejahteraan Keluarga" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            @error('name') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Singkatan</label>
                            <input type="text" name="singkatan" value="{{ old('singkatan') }}" placeholder="Misal: PKK" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Ketua / Pimpinan</label>
                            <input type="text" name="ketua" value="{{ old('ketua') }}" placeholder="Nama ketua lembaga..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Deskripsi Singkat</label>
                            <input type="text" name="description" value="{{ old('description') }}" placeholder="Fungsi atau keterangan singkat lembaga..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                        </div>

                        <div x-data="{ selectedFileName: null }">
                            <label class="block font-bold text-slate-800 mb-1">Logo Lembaga (Opsional)</label>
                            <input type="file" name="logo" accept="image/png, image/jpeg, image/webp"
                                @change="const file = $event.target.files[0]; if(file) { $dispatch('open-cropper', { file: file, aspectRatio: 1, onCrop: (blob, url) => { let dt = new DataTransfer(); dt.items.add(new File([blob], file.name, {type: file.type})); $event.target.files = dt.files; selectedFileName = file.name; } }) }"
                                class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            <p class="text-[11px] text-slate-400 mt-1">Maks. {{ $systemSettings['max_upload_foto_mb'] ?? 2 }}MB. Format: JPG, PNG, WEBP</p>
                            <template x-if="selectedFileName">
                                <div class="mt-2 p-2.5 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center gap-2 text-xs font-semibold text-emerald-900">
                                    <span>🖼️ File Terpilih:</span>
                                    <span class="font-mono text-[11px] truncate" x-text="selectedFileName"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-md transition">Simpan Lembaga</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- MODAL 2: EDIT LEMBAGA -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"></div>

            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl max-w-xl w-full border border-slate-200 my-8">
                <template x-if="selectedLembaga !== null">
                    <form :action="'{{ url('admin/kelola-beranda/lembaga') }}/' + selectedLembaga.id" method="POST" enctype="multipart/form-data" @submit.prevent="window.submitAjax($event, 'editModalOpen', $data)">
                        @csrf @method('PUT')
                        <input type="hidden" name="id" :value="selectedLembaga.id">
                        <input type="hidden" name="existing_logo" :value="selectedLembaga.logo">
                        
                        <div class="bg-white px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-600 inline-block"></span>
                                <h3 class="text-base font-bold text-slate-800">Edit Lembaga Kemasyarakatan</h3>
                            </div>
                            <button type="button" @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-4 text-xs text-slate-700 max-h-[80vh] overflow-y-auto">
                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Nama Lembaga <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" x-model="selectedLembaga.name" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Singkatan</label>
                                <input type="text" name="singkatan" x-model="selectedLembaga.singkatan" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Ketua / Pimpinan</label>
                                <input type="text" name="ketua" x-model="selectedLembaga.ketua" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Deskripsi Singkat</label>
                                <input type="text" name="description" x-model="selectedLembaga.description" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            </div>

                            <div x-data="{ selectedFileName: null }">
                                <label class="block font-bold text-slate-800 mb-1">Upload Logo Baru (Opsional)</label>
                                <input type="file" name="logo" accept="image/png, image/jpeg, image/webp"
                                    @change="const file = $event.target.files[0]; if(file) { $dispatch('open-cropper', { file: file, aspectRatio: 1, onCrop: (blob, url) => { let dt = new DataTransfer(); dt.items.add(new File([blob], file.name, {type: file.type})); $event.target.files = dt.files; selectedFileName = file.name; } }) }"
                                    class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                
                                <template x-if="selectedFileName">
                                    <div class="mt-2 p-2.5 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center gap-2 text-xs font-semibold text-emerald-900">
                                        <span>🖼️ File Baru Terpilih:</span>
                                        <span class="font-mono text-[11px] truncate" x-text="selectedFileName"></span>
                                    </div>
                                </template>

                                <template x-if="!selectedFileName && selectedLembaga.logo">
                                    <div class="mt-3 p-3 bg-slate-50 rounded-2xl border border-slate-200 inline-block">
                                        <div class="text-[10px] text-slate-500 font-bold mb-2 uppercase">Logo Saat Ini:</div>
                                        <div class="h-16 bg-white border border-slate-200 rounded-lg p-2 flex items-center justify-center shadow-sm max-w-[120px]">
                                            <img :src="'{{ asset('storage') }}/' + selectedLembaga.logo" class="max-w-full max-h-full object-contain">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                            <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-md transition">Simpan Perubahan</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</div>


</div>
@endsection
