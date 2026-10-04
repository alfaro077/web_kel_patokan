@extends('layouts.admin')

@section('title', 'Statistik Dasar & Demografi')
@section('header-title', 'Statistik & Demografi Warga')
@section('header-subtitle', 'Mengatur data statistik penduduk, pekerjaan, dan pendidikan.')

@section('content')
<div id="data-container">
    <div class="space-y-6" x-data="{
        createOccOpen: false,
        editOccOpen: false,
        selectedOcc: null,
        selectedOccIndex: null,

        createEduOpen: false,
        editEduOpen: false,
        selectedEdu: null,
        selectedEduIndex: null,

        editBasicStatOpen: false,
        selectedBasicStat: null,
        selectedBasicStatIndex: null
    }">
<!-- TABEL 0: STATISTIK DASAR BERANDA -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col md:flex-row">
        
        <!-- Kolom Kiri: Judul & Deskripsi -->
        <div class="md:w-1/4 p-6 border-b md:border-b-0 md:border-r border-slate-100 flex flex-col items-start justify-start">
            <h3 class="text-lg font-extrabold text-slate-900 flex items-start gap-3">
                <i class="fas fa-table-cells-large text-emerald-700 mt-1 text-xl"></i>
                <span class="leading-tight">Kartu<br>Statistik<br>Beranda</span>
            </h3>
            <p class="text-sm text-slate-500 mt-4 leading-relaxed pr-2">Mengelola daftar kartu statistik (seperti Jumlah Penduduk, KK) yang tampil di beranda.</p>
        </div>

        <!-- Kolom Kanan: Tabel -->
        <div class="md:w-3/4 overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-4 px-6">Ikon & Judul</th>
                        <th class="py-4 px-6">Nilai / Angka</th>
                        <th class="py-4 px-6">Warna Tema</th>
                        <th class="py-4 px-6">Status Tampil</th>
                        <th class="py-4 px-6 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($profile['stats'] ?? [] as $index => $stat)
                        <tr class="hover:bg-slate-50/50 transition bg-white group">
                            <td class="py-4 px-6 font-bold text-slate-800 flex items-center gap-3">
                                <i class="{{ $stat['icon'] ?? 'fas fa-chart-bar' }} text-slate-400 text-sm w-4 text-center"></i>
                                {{ $stat['title'] ?? 'Tanpa Judul' }}
                            </td>
                            <td class="py-4 px-6 font-bold text-emerald-700">{{ $stat['value'] ?? '-' }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-{{ $stat['color'] ?? 'emerald' }}-100 text-{{ $stat['color'] ?? 'emerald' }}-700 uppercase">
                                    {{ $stat['color'] ?? 'EMERALD' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <form action="{{ route('admin.beranda.statistik.basic.toggle', $index) }}" method="POST" @submit.prevent="window.submitAjax($event, null, $data)">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer items-center justify-center rounded-full focus:outline-none transition-colors duration-200 ease-in-out {{ ($stat['is_active'] ?? false) ? 'bg-emerald-500' : 'bg-slate-200' }}">
                                        <span class="pointer-events-none absolute h-full w-full rounded-md bg-white opacity-0"></span>
                                        <span aria-hidden="true" class="pointer-events-none absolute left-0 inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition-transform duration-200 ease-in-out {{ ($stat['is_active'] ?? false) ? 'translate-x-5' : 'translate-x-1' }}"></span>
                                    </button>
                                </form>
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" @click="selectedBasicStat = {{ json_encode($stat) }}; selectedBasicStatIndex = {{ $index }}; editBasicStatOpen = true;" class="inline-flex items-center px-4 py-1.5 bg-white hover:bg-slate-50 text-slate-800 font-bold text-[11px] rounded-lg transition border border-slate-200 shadow-sm">
                                        Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-slate-400">Belum ada kartu statistik.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form 1: Demografi Utama -->
    <form action="{{ route('admin.beranda.update') }}" method="POST" @submit.prevent="window.submitAjax($event, null, $data)" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5"
          x-data="{
              demo_male: '{{ old('demo_male', $profile['demographics']['male'] ?? '') }}',
              demo_female: '{{ old('demo_female', $profile['demographics']['female'] ?? '') }}',
              get totalPenduduk() {
                  let m = parseFloat(this.demo_male.replace(/\./g, '').replace(/,/g, '.')) || 0;
                  let f = parseFloat(this.demo_female.replace(/\./g, '').replace(/,/g, '.')) || 0;
                  return (m + f).toLocaleString('id-ID');
              }
          }">
        @csrf
        <input type="hidden" name="section" value="demografi">
        <div class="border-b border-slate-100 pb-3 flex justify-between items-center">
            <div>
                <h3 class="text-base font-bold text-slate-900">Demografi Penduduk Warga</h3>
                <p class="text-xs text-slate-500 mt-0.5">Total penduduk otomatis dihitung dari Laki-laki + Perempuan.</p>
            </div>
            <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl transition shadow-sm">Simpan Demografi</button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div>
                <label class="block font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                    <span>Total Populasi Penduduk</span>
                    <span class="px-1.5 py-0.5 bg-slate-100 text-slate-500 rounded text-[9px] font-bold">OTOMATIS</span>
                </label>
                <input type="text" name="demo_total" x-model="totalPenduduk" readonly class="w-full p-2.5 border border-slate-200 bg-slate-50 text-slate-600 rounded-lg font-bold cursor-not-allowed">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1.5">Laki-laki</label>
                <input type="text" name="demo_male" x-model="demo_male" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1.5">Perempuan</label>
                <input type="text" name="demo_female" x-model="demo_female" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
            </div>
        </div>
    </form>

    <!-- Modal Create Kartu Statistik Beranda dihapus karena kartu tidak bisa ditambah/dihapus -->

    <!-- Edit -->
    <div x-show="editBasicStatOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div @click="editBasicStatOpen = false" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl w-full max-w-md shadow-2xl">
                <template x-if="selectedBasicStat">
                    <form :action="`{{ route('admin.beranda.statistik.basic.update', 'INDEX_PLACEHOLDER') }}`.replace('INDEX_PLACEHOLDER', selectedBasicStatIndex)" method="POST" @submit.prevent="window.submitAjax($event, 'editBasicStatOpen', $data)">
                        @csrf @method('PUT')
                        <div class="bg-white px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="font-bold text-slate-800">Edit Kartu Statistik</h3>
                            <button type="button" @click="editBasicStatOpen = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="p-6 space-y-4 text-xs">
                            <div>
                                <label class="block font-bold mb-1 text-slate-700">Judul / Keterangan *</label>
                                <input type="text" name="title" x-model="selectedBasicStat.title" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                            </div>
                            <div>
                                <label class="block font-bold mb-1 text-slate-700">Angka / Nilai *</label>
                                <input type="text" name="value" x-model="selectedBasicStat.value" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div x-data="{ 
                                    open: false, 
                                    icons: [
                                        'fas fa-users', 'fas fa-user-tie', 'fas fa-user', 'fas fa-male', 'fas fa-female', 'fas fa-child', 'fas fa-baby', 'fas fa-wheelchair', 
                                        'fas fa-chart-bar', 'fas fa-chart-pie', 'fas fa-chart-line', 
                                        'fas fa-building', 'fas fa-home', 'fas fa-city', 'fas fa-hospital', 'fas fa-school', 'fas fa-graduation-cap', 
                                        'fas fa-mosque', 'fas fa-church', 'fas fa-praying-hands', 
                                        'fas fa-map-marker-alt', 'fas fa-map', 'fas fa-id-card', 'fas fa-address-card', 'fas fa-file-alt', 'fas fa-briefcase', 
                                        'fas fa-heart', 'fas fa-leaf', 'fas fa-seedling', 'fas fa-tint', 'fas fa-sun', 
                                        'fas fa-car', 'fas fa-motorcycle', 'fas fa-bus', 'fas fa-truck', 'fas fa-bicycle', 'fas fa-road', 'fas fa-tractor', 
                                        'fas fa-tree', 'fas fa-wifi', 'fas fa-coins', 'fas fa-money-bill-wave', 'fas fa-wallet', 'fas fa-hand-holding-usd'
                                    ],
                                    selectIcon(icon) {
                                        selectedBasicStat.icon = icon;
                                        this.open = false;
                                    }
                                }">
                                    <label class="block font-bold mb-1 text-slate-700">Ikon Kartu</label>
                                    <div class="relative">
                                        <input type="hidden" name="icon" :value="selectedBasicStat.icon">
                                        <button type="button" @click="open = !open" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between hover:bg-slate-100 transition focus:outline-none">
                                            <div class="flex items-center gap-3">
                                                <div class="w-6 h-6 flex items-center justify-center rounded-md bg-white border border-slate-200 text-slate-700 shadow-sm">
                                                    <i :class="selectedBasicStat.icon"></i>
                                                </div>
                                                <span class="font-mono text-[10px] text-slate-500" x-text="selectedBasicStat.icon"></span>
                                            </div>
                                            <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                                        </button>
                                        
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 mt-2 w-full p-2 bg-white border border-slate-200 rounded-xl shadow-xl">
                                            <div class="grid grid-cols-6 gap-1 max-h-48 overflow-y-auto p-1">
                                                <template x-for="icon in icons" :key="icon">
                                                    <button type="button" @click="selectIcon(icon)" 
                                                            class="flex items-center justify-center p-2 rounded-lg hover:bg-emerald-50 transition focus:outline-none"
                                                            :class="selectedBasicStat.icon === icon ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-500' : 'text-slate-600 bg-slate-50 hover:text-emerald-600'">
                                                        <i :class="icon"></i>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-bold mb-1 text-slate-700">Warna Tema</label>
                                    <select name="color" x-model="selectedBasicStat.color" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold">
                                        <option value="emerald" class="text-emerald-700">Hijau (Emerald)</option>
                                        <option value="sky" class="text-sky-700">Biru (Sky)</option>
                                        <option value="amber" class="text-amber-700">Kuning (Amber)</option>
                                        <option value="rose" class="text-rose-700">Merah (Rose)</option>
                                        <option value="indigo" class="text-indigo-700">Ungu (Indigo)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 rounded-b-3xl">
                            <button type="submit" class="px-5 py-2 bg-emerald-700 text-white font-bold rounded-xl shadow-sm hover:bg-emerald-800 transition">Perbarui Kartu</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>

</div>
</div>
@endsection
