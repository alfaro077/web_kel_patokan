@extends('layouts.admin')

@section('title', 'Agenda Kegiatan')
@section('header-title', 'Agenda Kegiatan')
@section('header-subtitle', 'Pengaturan jadwal dan kegiatan yang akan dilaksanakan atau sudah dilaksanakan')

@section('content')
<div class="space-y-6" x-data="{ createModalOpen: false, editModalOpen: false, selectedAgenda: null,
    async submitForm(e, modalName) {
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
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
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
                if(modalName === 'createModalOpen') form.reset();
            } else {
                let errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                try {
                    const errorData = await response.json();
                    if (errorData.errors) {
                        errorMsg = Object.values(errorData.errors).flat().join('<br>');
                    } else if (errorData.message) {
                        errorMsg = errorData.message;
                    }
                } catch (e) {}
                Swal.fire({ icon: 'error', title: 'Periksa Data Anda', html: errorMsg });
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
                <svg class="w-5 h-5 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Daftar Agenda Kegiatan</span>
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola agenda kegiatan yang akan tampil di halaman publik</p>
        </div>

        <button @click="createModalOpen = true" 
                class="px-4 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-extrabold text-xs rounded-xl shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Agenda Baru</span>
        </button>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto pb-2 sm:pb-0 hide-scroll">
            <a href="{{ route('admin.agenda.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !request('status') || request('status') !== 'arsip' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">
                🟢 Agenda Aktif
            </a>
            <a href="{{ route('admin.agenda.index', ['status' => 'arsip']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request('status') === 'arsip' ? 'bg-slate-800 text-white' : 'bg-slate-50 text-slate-500 hover:bg-slate-100' }}">
                📦 Arsip Agenda
            </a>
        </div>
        <form method="GET" action="{{ route('admin.agenda.index') }}" class="w-full sm:w-80 relative">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul agenda..." class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 shadow-sm">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </form>
    </div>

    <!-- Data Table Container -->
    <div id="data-container" class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        
        <div class="overflow-x-auto min-w-full">
            <table class="w-full text-left border-collapse min-w-[750px]">
                <thead>
                    <tr class="bg-slate-100/80 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-4 sm:px-5">Tanggal & Waktu</th>
                        <th class="py-3.5 px-4 sm:px-5">Judul Kegiatan</th>
                        <th class="py-3.5 px-4 sm:px-5">Lokasi</th>
                        <th class="py-3.5 px-4 sm:px-5 text-center">Status Tayang</th>
                        <th class="py-3.5 px-4 sm:px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    @forelse($agendas as $agenda)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4 sm:px-5 whitespace-nowrap">
                                <div class="font-bold text-emerald-700">
                                    {{ \Carbon\Carbon::parse($agenda->agenda_date)->locale('id')->isoFormat('D MMMM Y') }}
                                    @if($agenda->agenda_end_date && $agenda->agenda_date->format('Y-m-d') !== $agenda->agenda_end_date->format('Y-m-d'))
                                        - {{ \Carbon\Carbon::parse($agenda->agenda_end_date)->locale('id')->isoFormat('D MMMM Y') }}
                                    @endif
                                </div>
                                @if($agenda->agenda_time)
                                <div class="text-[10px] text-slate-500 mt-0.5 font-mono">
                                    <i class="far fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($agenda->agenda_time)->format('H:i') }}
                                    @if($agenda->agenda_end_time)
                                        - {{ \Carbon\Carbon::parse($agenda->agenda_end_time)->format('H:i') }}
                                    @endif
                                    WIB
                                </div>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 sm:px-5 font-bold text-slate-900">
                                {{ $agenda->title }}
                                @if($agenda->description)
                                    <p class="font-normal text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $agenda->description }}</p>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 sm:px-5 text-slate-700">
                                {{ $agenda->location ?? '-' }}
                            </td>

                            <!-- Status Toggle -->
                            <td class="py-3.5 px-4 sm:px-5 text-center whitespace-nowrap">
                                <form action="{{ route('admin.agenda.toggle', $agenda->id) }}" method="POST" @submit.prevent="submitForm($event)">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold transition shadow-sm border {{ $agenda->is_active ? 'bg-emerald-100 text-emerald-900 border-emerald-300 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-300 hover:bg-slate-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $agenda->is_active ? 'bg-emerald-600' : 'bg-slate-400' }}"></span>
                                        <span>{{ $agenda->is_active ? 'Tampil (Aktif)' : 'Non-Aktif' }}</span>
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 sm:px-5 text-right whitespace-nowrap space-x-1">
                                <button @click="selectedAgenda = {{ json_encode($agenda) }}; editModalOpen = true" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-[11px] rounded-lg transition border border-slate-300">
                                    Edit
                                </button>

                                @if(auth()->user()->isAdmin())
                                <form action="{{ route('admin.agenda.destroy', $agenda->id) }}" method="POST" class="inline" onsubmit="event.preventDefault(); window.ajaxDelete(this.action, document.querySelector('meta[name=csrf-token]').getAttribute('content'), 'Hapus agenda ini?');">
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
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <p class="font-semibold text-slate-600">Belum ada jadwal kegiatan yang ditambahkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-200 text-xs text-slate-500">
            {{ $agendas->links() }}
        </div>
    </div>


    <!-- MODAL TAMBAH AGENDA -->
    <div x-show="createModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-6">
            <div x-show="createModalOpen"  class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity"></div>

            <div x-show="createModalOpen" class="relative inline-block bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all max-w-lg w-full border border-slate-200 my-8">
                <form action="{{ route('admin.agenda.store') }}" method="POST" @submit.prevent="submitForm($event, 'createModalOpen')">
                    @csrf
                    <div class="bg-gradient-to-r from-emerald-950 to-slate-900 px-6 py-4 text-white flex items-center justify-between">
                        <h3 class="text-base font-bold">Tambah Agenda Baru</h3>
                        <button type="button" @click="createModalOpen = false" class="text-emerald-300 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Kegiatan *</label>
                            <input type="text" name="title" required placeholder="Contoh: Rapat Koordinasi RT/RW" class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Tgl Mulai *</label>
                                <input type="date" name="agenda_date" required class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Mulai (Opsional)</label>
                                <input type="time" name="agenda_time" class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Tgl Selesai (Opsional)</label>
                                <input type="date" name="agenda_end_date" class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Selesai (Opsional)</label>
                                <input type="time" name="agenda_end_time" class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Lokasi (Opsional)</label>
                            <input type="text" name="location" placeholder="Contoh: Balai Kelurahan" class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Singkat (Opsional)</label>
                            <textarea name="description" rows="3" placeholder="Deskripsi atau keterangan kegiatan..." class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600"></textarea>
                        </div>

                        <div class="flex items-center gap-4 pt-1">
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                                <input type="checkbox" name="is_active" value="1" checked class="rounded text-emerald-600">
                                <span>Langsung Tayangkan</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-200">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 text-slate-600 font-semibold rounded-xl hover:bg-slate-200">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-extrabold rounded-xl shadow">Simpan Agenda</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT AGENDA -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-6">
            <div x-show="editModalOpen"  class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity"></div>

            <div x-show="editModalOpen" class="relative inline-block bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all max-w-lg w-full border border-slate-200 my-8">
                <template x-if="selectedAgenda">
                    <form :action="'{{ url('admin/agenda') }}/' + selectedAgenda.id" method="POST" @submit.prevent="submitForm($event, 'editModalOpen')">
                        @csrf
                        @method('PUT')
                        <div class="bg-gradient-to-r from-emerald-950 to-slate-900 px-6 py-4 text-white flex items-center justify-between">
                            <h3 class="text-base font-bold">Edit Agenda</h3>
                            <button type="button" @click="editModalOpen = false" class="text-emerald-300 hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-6 space-y-4 text-xs">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Kegiatan *</label>
                                <input type="text" name="title" x-model="selectedAgenda.title" required class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Tgl Mulai *</label>
                                    <input type="date" name="agenda_date" :value="selectedAgenda.agenda_date.substring(0, 10)" required class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Mulai</label>
                                    <input type="time" name="agenda_time" :value="selectedAgenda.agenda_time ? selectedAgenda.agenda_time.substring(0, 5) : ''" class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Tgl Selesai (Opsional)</label>
                                    <input type="date" name="agenda_end_date" :value="selectedAgenda.agenda_end_date ? selectedAgenda.agenda_end_date.substring(0, 10) : ''" class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Selesai</label>
                                    <input type="time" name="agenda_end_time" :value="selectedAgenda.agenda_end_time ? selectedAgenda.agenda_end_time.substring(0, 5) : ''" class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600">
                                </div>
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Lokasi (Opsional)</label>
                                <input type="text" name="location" x-model="selectedAgenda.location" class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Singkat (Opsional)</label>
                                <textarea name="description" x-model="selectedAgenda.description" rows="3" class="w-full p-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-600"></textarea>
                            </div>

                            <div class="flex items-center gap-4 pt-1">
                                <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-800">
                                    <input type="checkbox" name="is_active" value="1" :checked="selectedAgenda.is_active" class="rounded text-emerald-600">
                                    <span>Aktif Tayang</span>
                                </label>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-200">
                            <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-slate-600 font-semibold rounded-xl hover:bg-slate-200">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-extrabold rounded-xl shadow">Simpan Perubahan</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>

</div>
@endsection
