@extends('layouts.admin')

@section('title', 'Statistik Dasar & Demografi')
@section('header-title', 'Statistik & Demografi Warga')
@section('header-subtitle', 'Mengatur data statistik penduduk, pekerjaan, dan pendidikan.')

@section('content')
<div id="data-container">
    <div class="space-y-6" x-data="{
        demo_male: '{{ old('demo_male', $profile['demographics']['male'] ?? '') }}',
        demo_female: '{{ old('demo_female', $profile['demographics']['female'] ?? '') }}',
        get totalPenduduk() {
            let m = parseFloat(this.demo_male.replace(/\./g, '').replace(/,/g, '.')) || 0;
            let f = parseFloat(this.demo_female.replace(/\./g, '').replace(/,/g, '.')) || 0;
            return m + f;
        },
        get totalPendudukFormatted() {
            return this.totalPenduduk.toLocaleString('id-ID');
        },

        ageGroups: {{ json_encode($profile['age_groups'] ?? [
            ['label' => 'Balita (0-5 thn)', 'count' => 0],
            ['label' => 'Anak-anak (6-12 thn)', 'count' => 0],
            ['label' => 'Remaja (13-17 thn)', 'count' => 0],
            ['label' => 'Usia Produktif (18-59 thn)', 'count' => 0],
            ['label' => 'Lansia (60+ thn)', 'count' => 0],
        ]) }},
        get ageGroupsTotal() { return this.ageGroups.reduce((sum, item) => sum + (parseInt(item.count) || 0), 0); },

        educations: {{ json_encode($profile['educations'] ?? [
            ['label' => 'Belum / Tidak Sekolah', 'count' => 0],
            ['label' => 'SD / Sederajat', 'count' => 0],
            ['label' => 'SMP / Sederajat', 'count' => 0],
            ['label' => 'SMA / SMK / Sederajat', 'count' => 0],
            ['label' => 'Diploma / Sarjana (D3/S1/S2/S3)', 'count' => 0],
        ]) }},
        get educationsTotal() { return this.educations.reduce((sum, item) => sum + (parseInt(item.count) || 0), 0); },

        occupations: {{ json_encode($profile['occupations'] ?? [
            ['label' => 'Wiraswasta / Pedagang', 'count' => 0],
            ['label' => 'Karyawan Swasta', 'count' => 0],
            ['label' => 'ASN / PNS / TNI / Polri', 'count' => 0],
            ['label' => 'Buruh Harian', 'count' => 0],
            ['label' => 'Petani / Peternak', 'count' => 0],
            ['label' => 'Pelajar / Mahasiswa', 'count' => 0],
            ['label' => 'Belum Bekerja', 'count' => 0],
        ]) }},
        get occupationsTotal() { return this.occupations.reduce((sum, item) => sum + (parseInt(item.count) || 0), 0); },

        territory: {{ json_encode(array_merge([
            'north' => '', 'east' => '', 'south' => '', 'west' => '',
            'rw' => 0, 'rt' => 0, 'schools' => 0, 'mosques' => 0, 'health' => 0, 'markets' => 0,
            'rw_distribution' => []
        ], $profile['territory'] ?? [])) }},

        editBasicStatOpen: false,
        addBasicStatOpen: false,
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
            <button type="button" @click="addBasicStatOpen = true" class="mt-5 inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                <i class="fas fa-plus"></i> Tambah Kartu
            </button>
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
                                    <button type="submit" class="relative inline-flex items-center h-6 w-11 shrink-0 cursor-pointer rounded-full focus:outline-none transition-colors duration-200 ease-in-out {{ ($stat['is_active'] ?? false) ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                        <span class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform duration-200 ease-in-out shadow-sm {{ ($stat['is_active'] ?? false) ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                </form>
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" @click="selectedBasicStat = {{ json_encode($stat) }}; selectedBasicStatIndex = {{ $index }}; editBasicStatOpen = true;" class="inline-flex items-center px-4 py-1.5 bg-white hover:bg-slate-50 text-slate-800 font-bold text-[11px] rounded-lg transition border border-slate-200 shadow-sm">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.beranda.statistik.basic.destroy', $index) }}" method="POST" onsubmit="event.preventDefault(); window.ajaxDelete(this.action, document.querySelector('meta[name=csrf-token]').getAttribute('content'), 'Apakah Anda yakin ingin menghapus kartu ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-7 h-7 bg-white hover:bg-rose-50 text-rose-500 hover:text-rose-600 font-bold rounded-lg transition border border-rose-200 shadow-sm">
                                            <i class="fas fa-trash-alt text-[10px]"></i>
                                        </button>
                                    </form>
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
    <form action="{{ route('admin.beranda.update') }}" method="POST" @submit.prevent="window.submitAjax($event, null, $data)" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
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
                <input type="text" name="demo_total" :value="totalPendudukFormatted" readonly class="w-full p-2.5 border border-slate-200 bg-slate-50 text-slate-600 rounded-lg font-bold cursor-not-allowed">
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

    <!-- Form 3: Kelompok Usia -->
    <form action="{{ route('admin.beranda.statistik.age_groups.update') }}" method="POST" @submit.prevent="window.submitAjax($event, null, $data)" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
        @csrf
        <div class="border-b border-slate-100 pb-3 flex justify-between items-center flex-wrap gap-3">
            <div>
                <h3 class="text-base font-bold text-slate-900">Kelola Data Kelompok Usia</h3>
                <p class="text-xs text-slate-500 mt-0.5">Klasifikasi usia warga untuk statistik.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-xs text-right">
                    <div class="font-bold text-slate-600">Total Terisi: <span :class="ageGroupsTotal > totalPenduduk ? 'text-rose-600' : 'text-emerald-600'" x-text="ageGroupsTotal"></span> / <span x-text="totalPendudukFormatted"></span> Jiwa</div>
                    <div class="text-[10px]" :class="ageGroupsTotal > totalPenduduk ? 'text-rose-500 font-bold' : 'text-slate-400'">Sisa Kuota: <span x-text="Math.max(0, totalPenduduk - ageGroupsTotal)"></span> Jiwa</div>
                </div>
                <button type="submit" :disabled="ageGroupsTotal > totalPenduduk" :class="ageGroupsTotal > totalPenduduk ? 'bg-slate-300 cursor-not-allowed text-slate-500' : 'bg-emerald-700 hover:bg-emerald-800 text-white'" class="px-5 py-2 font-bold text-xs rounded-xl transition shadow-sm">Simpan</button>
            </div>
        </div>
        
        <div x-show="ageGroupsTotal > totalPenduduk" x-cloak class="p-3 bg-rose-50 border border-rose-200 rounded-lg text-rose-700 text-xs font-bold flex items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i> Jumlah melebihi total populasi penduduk kelurahan!
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs">
            <template x-for="(age, index) in ageGroups" :key="index">
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5" x-text="age.label"></label>
                    <input type="hidden" :name="`age_groups[${index}][label]`" :value="age.label">
                    <input type="number" :name="`age_groups[${index}][count]`" x-model.number="age.count" min="0" class="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-emerald-500" :class="ageGroupsTotal > totalPenduduk ? 'border-rose-300 bg-rose-50' : 'border-slate-300'">
                </div>
            </template>
        </div>
    </form>

    <!-- Form 4: Tingkat Pendidikan -->
    <form action="{{ route('admin.beranda.statistik.educations.update') }}" method="POST" @submit.prevent="window.submitAjax($event, null, $data)" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
        @csrf
        <div class="border-b border-slate-100 pb-3 flex justify-between items-center flex-wrap gap-3">
            <div>
                <h3 class="text-base font-bold text-slate-900">Kelola Data Tingkat Pendidikan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Distribusi pendidikan warga.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-xs text-right">
                    <div class="font-bold text-slate-600">Total Terisi: <span :class="educationsTotal > totalPenduduk ? 'text-rose-600' : 'text-emerald-600'" x-text="educationsTotal"></span> / <span x-text="totalPendudukFormatted"></span> Jiwa</div>
                    <div class="text-[10px]" :class="educationsTotal > totalPenduduk ? 'text-rose-500 font-bold' : 'text-slate-400'">Sisa Kuota: <span x-text="Math.max(0, totalPenduduk - educationsTotal)"></span> Jiwa</div>
                </div>
                <button type="submit" :disabled="educationsTotal > totalPenduduk" :class="educationsTotal > totalPenduduk ? 'bg-slate-300 cursor-not-allowed text-slate-500' : 'bg-emerald-700 hover:bg-emerald-800 text-white'" class="px-5 py-2 font-bold text-xs rounded-xl transition shadow-sm">Simpan</button>
            </div>
        </div>

        <div x-show="educationsTotal > totalPenduduk" x-cloak class="p-3 bg-rose-50 border border-rose-200 rounded-lg text-rose-700 text-xs font-bold flex items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i> Jumlah melebihi total populasi penduduk kelurahan!
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs">
            <template x-for="(edu, index) in educations" :key="index">
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5" x-text="edu.label"></label>
                    <input type="hidden" :name="`educations[${index}][label]`" :value="edu.label">
                    <input type="number" :name="`educations[${index}][count]`" x-model.number="edu.count" min="0" class="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-emerald-500" :class="educationsTotal > totalPenduduk ? 'border-rose-300 bg-rose-50' : 'border-slate-300'">
                </div>
            </template>
        </div>
    </form>

    <!-- Form 5: Jenis Pekerjaan -->
    <form action="{{ route('admin.beranda.statistik.occupations.update') }}" method="POST" @submit.prevent="window.submitAjax($event, null, $data)" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
        @csrf
        <div class="border-b border-slate-100 pb-3 flex justify-between items-center flex-wrap gap-3">
            <div>
                <h3 class="text-base font-bold text-slate-900">Kelola Data Mata Pencaharian / Pekerjaan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Anda dapat menambah atau menghapus kategori profesi.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-xs text-right">
                    <div class="font-bold text-slate-600">Total Terisi: <span :class="occupationsTotal > totalPenduduk ? 'text-rose-600' : 'text-emerald-600'" x-text="occupationsTotal"></span> / <span x-text="totalPendudukFormatted"></span> Jiwa</div>
                    <div class="text-[10px]" :class="occupationsTotal > totalPenduduk ? 'text-rose-500 font-bold' : 'text-slate-400'">Sisa Kuota: <span x-text="Math.max(0, totalPenduduk - occupationsTotal)"></span> Jiwa</div>
                </div>
                <button type="submit" :disabled="occupationsTotal > totalPenduduk" :class="occupationsTotal > totalPenduduk ? 'bg-slate-300 cursor-not-allowed text-slate-500' : 'bg-emerald-700 hover:bg-emerald-800 text-white'" class="px-5 py-2 font-bold text-xs rounded-xl transition shadow-sm">Simpan</button>
            </div>
        </div>

        <div x-show="occupationsTotal > totalPenduduk" x-cloak class="p-3 bg-rose-50 border border-rose-200 rounded-lg text-rose-700 text-xs font-bold flex items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i> Jumlah melebihi total populasi penduduk kelurahan!
        </div>

        <div class="space-y-3">
            <template x-for="(occ, index) in occupations" :key="index">
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <input type="text" :name="`occupations[${index}][label]`" x-model="occ.label" placeholder="Nama Pekerjaan" class="w-full p-2.5 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500" required>
                    </div>
                    <div class="w-32">
                        <input type="number" :name="`occupations[${index}][count]`" x-model.number="occ.count" min="0" placeholder="Jumlah Jiwa" class="w-full p-2.5 border rounded-lg text-xs focus:ring-2 focus:ring-emerald-500" :class="occupationsTotal > totalPenduduk ? 'border-rose-300 bg-rose-50' : 'border-slate-300'" required>
                    </div>
                    <button type="button" @click="occupations.splice(index, 1)" class="w-10 h-10 flex items-center justify-center bg-rose-50 text-rose-500 hover:bg-rose-100 hover:text-rose-700 rounded-lg transition" title="Hapus">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </template>
            <button type="button" @click="occupations.push({label: '', count: 0})" class="px-4 py-2 border border-dashed border-emerald-500 text-emerald-700 hover:bg-emerald-50 text-xs font-bold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Kategori Profesi
            </button>
        </div>
    </form>

    <!-- Form 6: Distribusi Wilayah (RT & RW) -->
    <form action="{{ route('admin.beranda.statistik.territory.update') }}" method="POST" @submit.prevent="window.submitAjax($event, null, $data)" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-5">
        @csrf
        <div class="border-b border-slate-100 pb-3 flex justify-between items-center">
            <div>
                <h3 class="text-base font-bold text-slate-900">Kelola Distribusi Wilayah (RT & RW)</h3>
                <p class="text-xs text-slate-500 mt-0.5">Informasi batas, fasilitas, dan penduduk per RW.</p>
            </div>
            <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl transition shadow-sm">Simpan Wilayah</button>
        </div>

        <div class="max-w-3xl">
            <!-- Lingkungan & Fasilitas -->
            <div class="space-y-4">
                <h4 class="font-bold text-sm text-slate-800 border-b pb-2">Informasi Umum Wilayah</h4>
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jumlah RW</label>
                        <input type="number" name="territory[rw]" x-model.number="territory.rw" min="0" class="w-full p-2 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jumlah RT</label>
                        <input type="number" name="territory[rt]" x-model.number="territory.rt" min="0" class="w-full p-2 border border-slate-300 rounded-lg">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div><label class="block font-bold text-slate-700 mb-1">Batas Utara</label><input type="text" name="territory[north]" x-model="territory.north" class="w-full p-2 border border-slate-300 rounded-lg"></div>
                    <div><label class="block font-bold text-slate-700 mb-1">Batas Timur</label><input type="text" name="territory[east]" x-model="territory.east" class="w-full p-2 border border-slate-300 rounded-lg"></div>
                    <div><label class="block font-bold text-slate-700 mb-1">Batas Selatan</label><input type="text" name="territory[south]" x-model="territory.south" class="w-full p-2 border border-slate-300 rounded-lg"></div>
                    <div><label class="block font-bold text-slate-700 mb-1">Batas Barat</label><input type="text" name="territory[west]" x-model="territory.west" class="w-full p-2 border border-slate-300 rounded-lg"></div>
                </div>
                <h4 class="font-bold text-sm text-slate-800 border-b pb-2 mt-4">Fasilitas Umum</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                    <div><label class="block font-bold text-slate-700 mb-1">Sekolah</label><input type="number" name="territory[schools]" x-model.number="territory.schools" min="0" class="w-full p-2 border border-slate-300 rounded-lg"></div>
                    <div><label class="block font-bold text-slate-700 mb-1">Ibadah</label><input type="number" name="territory[mosques]" x-model.number="territory.mosques" min="0" class="w-full p-2 border border-slate-300 rounded-lg"></div>
                    <div><label class="block font-bold text-slate-700 mb-1">Kesehatan</label><input type="number" name="territory[health]" x-model.number="territory.health" min="0" class="w-full p-2 border border-slate-300 rounded-lg"></div>
                    <div><label class="block font-bold text-slate-700 mb-1">Pasar</label><input type="number" name="territory[markets]" x-model.number="territory.markets" min="0" class="w-full p-2 border border-slate-300 rounded-lg"></div>
                </div>
            </div>
        </div>
    </form>

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
                                        <option value="sky" class="text-emerald-700">Biru (Sky)</option>
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

    <!-- Tambah Basic Stat Modal -->
    <div x-show="addBasicStatOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div @click="addBasicStatOpen = false" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl w-full max-w-md shadow-2xl">
                <form action="{{ route('admin.beranda.statistik.basic.store') }}" method="POST" @submit.prevent="window.submitAjax($event, 'addBasicStatOpen', $data)">
                    @csrf
                    <div class="bg-white px-6 py-4 border-b border-slate-100 flex items-center justify-between rounded-t-3xl">
                        <h3 class="font-bold text-slate-800">Tambah Kartu Statistik Baru</h3>
                        <button type="button" @click="addBasicStatOpen = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="p-6 space-y-4 text-xs">
                        <div>
                            <label class="block font-bold mb-1 text-slate-700">Judul / Keterangan *</label>
                            <input type="text" name="title" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl" placeholder="Misal: Jumlah Sekolah">
                        </div>
                        <div>
                            <label class="block font-bold mb-1 text-slate-700">Angka / Nilai *</label>
                            <input type="text" name="value" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl" placeholder="Misal: 12">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div x-data="{ 
                                open: false, 
                                icon: 'fas fa-chart-bar',
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
                                selectIcon(i) {
                                    this.icon = i;
                                    this.open = false;
                                }
                            }">
                                <label class="block font-bold mb-1 text-slate-700">Ikon Kartu</label>
                                <div class="relative">
                                    <input type="hidden" name="icon" :value="icon">
                                    <button type="button" @click="open = !open" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between hover:bg-slate-100 transition focus:outline-none">
                                        <div class="flex items-center gap-3">
                                            <div class="w-6 h-6 flex items-center justify-center rounded-md bg-white border border-slate-200 text-slate-700 shadow-sm">
                                                <i :class="icon"></i>
                                            </div>
                                        </div>
                                        <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                                    </button>
                                    
                                    <div x-show="open" @click.away="open = false" x-transition class="absolute bottom-full mb-1 left-0 z-10 w-64 bg-white border border-slate-200 rounded-xl shadow-xl p-3 grid grid-cols-6 gap-2 max-h-60 overflow-y-auto" style="display: none;">
                                        <template x-for="i in icons" :key="i">
                                            <button type="button" @click="selectIcon(i)" class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-emerald-50 hover:text-emerald-600 transition" :class="icon === i ? 'bg-emerald-100 text-emerald-700' : 'text-slate-500'">
                                                <i :class="i"></i>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block font-bold mb-1 text-slate-700">Warna Tema</label>
                                <select name="color" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-emerald-500">
                                    <option value="emerald">Hijau (Emerald)</option>
                                    <option value="sky">Biru (Sky)</option>
                                    <option value="amber">Kuning (Amber)</option>
                                    <option value="rose">Merah (Rose)</option>
                                    <option value="indigo">Ungu (Indigo)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex justify-end gap-3 px-6 py-4 bg-slate-50 rounded-b-3xl mt-4">
                        <button type="button" @click="addBasicStatOpen = false" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition">Simpan Kartu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
</div>
@endsection

