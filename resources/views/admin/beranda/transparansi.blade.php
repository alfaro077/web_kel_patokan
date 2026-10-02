@extends('layouts.admin')

@section('title', 'Transparansi & APBD')
@section('header-title', 'Transparansi APBD & Dana Desa')
@section('header-subtitle', 'Mengatur data riwayat realisasi APBD/Dana Desa dari tahun ke tahun sesuai standar laporan.')

@section('content')
<div id="data-container">
<div class="space-y-6" x-data="{
    createYearModalOpen: false,
    editYearModalOpen: false,
    editYearIndex: null,
    editYearData: { year: '', description: '' },
    openEditYear(index, yearData) {
        this.editYearIndex = index;
        this.editYearData = { ...yearData };
        this.editYearModalOpen = true;
    },
    selectedYearIndex: null,
    
    createIncomeModalOpen: false,
    editIncomeModalOpen: false,
    selectedIncome: null,
    selectedIncomeIndex: null,

    createAllocModalOpen: false,
    editAllocModalOpen: false,
    selectedAlloc: null,
    selectedAllocIndex: null,

    createFinModalOpen: false,
    editFinModalOpen: false,
    selectedFin: null,
    selectedFinIndex: null,

    apbdData: {{ json_encode(array_values($profile['apbd'] ?? [])) }},
    apbdCategories: {{ json_encode($profile['apbd_categories'] ?? [
        'incomes' => ['PENDAPATAN ASLI DESA', 'PENDAPATAN TRANSFER', 'PENDAPATAN LAIN-LAIN'],
        'allocations' => ['PENYELENGGARAAN PEMERINTAHAN DESA', 'PELAKSANAAN PEMBANGUNAN DESA', 'PEMBINAAN KEMASYARAKATAN DESA', 'PEMBERDAYAAN MASYARAKAT DESA', 'BELANJA TAK TERDUGA'],
        'financings' => ['PENERIMAAN PEMBIAYAAN', 'PENGELUARAN PEMBIAYAAN']
    ]) }},
    
    get activeYear() {
        return this.apbdData[this.selectedYearIndex] || { incomes: [], allocations: [], financings: [] };
    },

    formatRupiah(val) {
        if (!val) return '';
        let numeric = val.toString().replace(/[^0-9]/g, '');
        if (!numeric) return '';
        return parseInt(numeric, 10).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },

    async manageCategory(action, type) {
        let inputCat = '';
        if (action === 'add') {
            const { value: categoryName } = await Swal.fire({
                title: 'Tambah Kategori',
                input: 'text',
                inputLabel: 'Nama Kategori Baru',
                inputPlaceholder: 'Masukkan nama kategori',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                allowOutsideClick: false,
                allowEscapeKey: false,
                inputValidator: (value) => {
                    if (!value) return 'Nama kategori tidak boleh kosong!'
                }
            });
            if (categoryName) inputCat = categoryName;
            else return;
        } else if (action === 'delete') {
            const options = {};
            this.apbdCategories[type].forEach(cat => {
                options[cat] = cat;
            });
            const { value: categoryToDelete } = await Swal.fire({
                title: 'Hapus Kategori',
                input: 'select',
                inputOptions: options,
                inputPlaceholder: 'Pilih kategori untuk dihapus',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                allowOutsideClick: false,
                allowEscapeKey: false,
                inputValidator: (value) => {
                    if (!value) return 'Anda harus memilih kategori!'
                }
            });
            if (categoryToDelete) inputCat = categoryToDelete;
            else return;
        }

        try {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('category', inputCat);
            if (action === 'delete') formData.append('_method', 'DELETE');

            const url = '{{ url('admin/kelola-beranda/apbd-category') }}/' + type + (action === 'delete' ? '/destroy' : '/store');
            
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            if (result.success) {
                this.apbdCategories = result.categories;
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: result.message || 'Kategori diperbarui.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                let errorMsg = result.message || 'Terjadi kesalahan pada respon server.';
                if (result.errors) {
                    errorMsg = Object.values(result.errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: errorMsg
                });
            }
        } catch (e) {
            console.error(e);
            Swal.fire('Error', 'Gagal menghubungi server: ' + e.message, 'error');
        }
    },

    async submitForm(event, modalToClose) {
        const form = event.target;
        const formData = new FormData(form);
        try {
            const response = await fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            const result = await response.json();
            if (result.success) {
                this.apbdData = result.apbd;
                this[modalToClose] = false;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
                if (['createYearModalOpen', 'createIncomeModalOpen', 'createAllocModalOpen', 'createFinModalOpen'].includes(modalToClose)) {
                    form.reset();
                }
            } else {
                let errorMsg = result.message || 'Terjadi kesalahan pada respon server.';
                if (result.errors) {
                    errorMsg = Object.values(result.errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: errorMsg
                });
            }
        } catch (e) {
            console.error(e);
            Swal.fire('Error', 'Gagal menghubungi server: ' + e.message, 'error');
        }
    },
    
    async deleteItem(url) {
        if (!confirm('Hapus data ini?')) return;
        
        try {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');
            
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            if (result.success) {
                this.apbdData = result.apbd;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        text: result.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            } else {
                let errorMsg = result.message || 'Data gagal dihapus.';
                if (result.errors) {
                    errorMsg = Object.values(result.errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: errorMsg
                });
            }
        } catch (e) {
            console.error(e);
            Swal.fire('Error', 'Gagal menghubungi server: ' + e.message, 'error');
        }
    }
}">

    <!-- ========================================== -->
    <!-- MASTER VIEW: DAFTAR TAHUN ANGGARAN         -->
    <!-- ========================================== -->
    <div x-show="selectedYearIndex === null" x-transition.opacity class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Riwayat Tahun Anggaran (APBDes)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pilih tahun anggaran yang ingin dikelola atau tambahkan tahun baru.</p>
                </div>
                <button type="button" @click="createYearModalOpen = true" class="px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl transition shadow-sm flex items-center gap-2 shrink-0">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Tahun APBD</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="(yearData, index) in apbdData" :key="index">
                    <div class="border border-slate-200 rounded-2xl p-5 hover:shadow-md hover:border-emerald-200 transition bg-slate-50 relative group">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-xl shadow-inner">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="flex items-center">
                                <button type="button" @click="openEditYear(index, yearData)" class="w-8 h-8 mr-2 rounded-lg bg-white border border-emerald-100 text-emerald-500 hover:bg-emerald-50 hover:border-emerald-200 flex items-center justify-center transition opacity-0 group-hover:opacity-100">
                                    <i class="fas fa-pencil-alt text-[11px]"></i>
                                </button>
                                <button type="button" @click="deleteItem('{{ url('admin/kelola-beranda/apbd/year') }}/' + index)" class="w-8 h-8 rounded-lg bg-white border border-rose-100 text-rose-500 hover:bg-rose-50 hover:border-rose-200 flex items-center justify-center transition opacity-0 group-hover:opacity-100">
                                    <i class="fas fa-trash-alt text-[11px]"></i>
                                </button>
                            </div>
                        </div>
                        <h4 class="text-2xl font-black text-slate-800 mb-5" x-text="yearData.year"></h4>
                        <button type="button" @click="selectedYearIndex = index" class="w-full py-2 bg-white border border-slate-300 hover:border-emerald-500 hover:text-emerald-700 text-slate-700 font-bold text-xs rounded-xl transition flex items-center justify-center gap-2">
                            <i class="fas fa-edit"></i>
                            <span>Kelola Data Anggaran</span>
                        </button>
                    </div>
                </template>
                <template x-if="apbdData.length === 0">
                    <div class="col-span-full py-10 text-center border border-dashed border-slate-300 rounded-2xl bg-slate-50">
                        <i class="fas fa-folder-open text-3xl text-slate-300 mb-3 block"></i>
                        <p class="text-sm font-bold text-slate-500">Belum ada data riwayat APBD.</p>
                        <p class="text-xs text-slate-400 mt-1">Klik tombol Tambah Tahun APBD di atas untuk memulai.</p>
                    </div>
                </template>
            </div>
        </div>
    </div>


    <!-- ========================================== -->
    <!-- DETAIL VIEW: KELOLA SATU TAHUN ANGGARAN    -->
    <!-- ========================================== -->
    <div x-show="selectedYearIndex !== null" style="display: none;" x-transition.opacity class="space-y-6">
        
        <div class="flex items-center justify-between">
            <button type="button" @click="selectedYearIndex = null" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:text-emerald-700 hover:border-emerald-200 hover:bg-emerald-50 transition text-sm font-bold shadow-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar Tahun</span>
            </button>
            <h2 class="text-lg font-black text-slate-800">Tahun Anggaran <span x-text="activeYear.year"></span></h2>
        </div>

        <div class="grid grid-cols-1 gap-6">
            
            <!-- 1. PENDAPATAN DESA -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="flex items-center justify-between gap-4 p-4 sm:p-5 border-b border-slate-200 bg-emerald-50/50">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="fas fa-download text-emerald-600 shrink-0"></i>
                            <span>1. Pendapatan Desa</span>
                        </h3>
                    </div>
                    <button type="button" @click="selectedIncome = {category: apbdCategories.incomes[0] || '', name: '', anggaran: '', realisasi: ''}; createIncomeModalOpen = true" 
                       class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-[11px] rounded-xl shadow-md transition flex items-center justify-center gap-1.5 shrink-0">
                        <i class="fas fa-plus"></i>
                        <span>Tambah</span>
                    </button>
                </div>
                <div class="overflow-x-auto min-w-full flex-1">
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead>
                            <tr class="bg-slate-100/80 border-b border-slate-200 text-[10px] font-bold text-slate-600 uppercase tracking-wider">
                                <th class="py-2.5 px-4 w-48">Kategori</th>
                                <th class="py-2.5 px-4">Nama Item</th>
                                <th class="py-2.5 px-4 text-right">Anggaran (Rp)</th>
                                <th class="py-2.5 px-4 text-right">Realisasi (Rp)</th>
                                <th class="py-2.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-xs">
                            <template x-for="(income, index) in (activeYear.incomes || [])" :key="index">
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-bold text-emerald-700 text-[10px]" x-text="income.category"></td>
                                    <td class="py-3 px-4 font-medium text-slate-900" x-text="income.name"></td>
                                    <td class="py-3 px-4 text-right text-slate-600" x-text="formatRupiah(income.anggaran)"></td>
                                    <td class="py-3 px-4 text-right font-medium text-emerald-600" x-text="formatRupiah(income.realisasi)"></td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap space-x-1">
                                        <button type="button" @click="selectedIncome = JSON.parse(JSON.stringify(income)); selectedIncomeIndex = index; editIncomeModalOpen = true;" class="inline-block px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-[10px] rounded transition border border-slate-300">Edit</button>
                                        <button type="button" @click="deleteItem('{{ url('admin/kelola-beranda/apbd-income') }}/' + selectedYearIndex + '/' + index)" class="px-2 py-1 bg-rose-50 hover:bg-rose-100 text-rose-800 font-bold text-[10px] rounded transition border border-rose-200">Hapus</button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!(activeYear.incomes) || activeYear.incomes.length === 0">
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400">
                                        <p class="font-semibold text-slate-600 text-[11px]">Belum ada rincian Pendapatan Desa.</p>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. BELANJA DESA -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="flex items-center justify-between gap-4 p-4 sm:p-5 border-b border-slate-200 bg-rose-50/50">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="fas fa-upload text-rose-600 shrink-0"></i>
                            <span>2. Belanja Desa</span>
                        </h3>
                    </div>
                    <button type="button" @click="selectedAlloc = {category: apbdCategories.allocations[0] || '', name: '', anggaran: '', realisasi: ''}; createAllocModalOpen = true;" 
                       class="px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-[11px] rounded-xl shadow-md transition flex items-center justify-center gap-1.5 shrink-0">
                        <i class="fas fa-plus"></i>
                        <span>Tambah</span>
                    </button>
                </div>
                <div class="overflow-x-auto min-w-full flex-1">
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead>
                            <tr class="bg-slate-100/80 border-b border-slate-200 text-[10px] font-bold text-slate-600 uppercase tracking-wider">
                                <th class="py-2.5 px-4 w-48">Kategori Bidang</th>
                                <th class="py-2.5 px-4">Nama Kegiatan</th>
                                <th class="py-2.5 px-4 text-right">Anggaran (Rp)</th>
                                <th class="py-2.5 px-4 text-right">Realisasi (Rp)</th>
                                <th class="py-2.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-xs">
                            <template x-for="(alloc, index) in (activeYear.allocations || [])" :key="index">
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-bold text-rose-700 text-[10px]" x-text="alloc.category"></td>
                                    <td class="py-3 px-4 font-medium text-slate-900" x-text="alloc.name"></td>
                                    <td class="py-3 px-4 text-right text-slate-600" x-text="formatRupiah(alloc.anggaran)"></td>
                                    <td class="py-3 px-4 text-right font-medium text-rose-600" x-text="formatRupiah(alloc.realisasi)"></td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap space-x-1">
                                        <button type="button" @click="selectedAlloc = JSON.parse(JSON.stringify(alloc)); selectedAllocIndex = index; editAllocModalOpen = true;" class="inline-block px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-[10px] rounded transition border border-slate-300">Edit</button>
                                        <button type="button" @click="deleteItem('{{ url('admin/kelola-beranda/apbd') }}/' + selectedYearIndex + '/' + index)" class="px-2 py-1 bg-rose-50 hover:bg-rose-100 text-rose-800 font-bold text-[10px] rounded transition border border-rose-200">Hapus</button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!(activeYear.allocations) || activeYear.allocations.length === 0">
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400">
                                        <p class="font-semibold text-slate-600 text-[11px]">Belum ada rincian Belanja Desa.</p>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. PEMBIAYAAN DESA -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="flex items-center justify-between gap-4 p-4 sm:p-5 border-b border-slate-200 bg-emerald-50/50">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i class="fas fa-hand-holding-usd text-emerald-600 shrink-0"></i>
                            <span>3. Pembiayaan Desa</span>
                        </h3>
                    </div>
                    <button type="button" @click="selectedFin = {category: apbdCategories.financings[0] || '', name: '', anggaran: '', realisasi: ''}; createFinModalOpen = true;" 
                       class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-[11px] rounded-xl shadow-md transition flex items-center justify-center gap-1.5 shrink-0">
                        <i class="fas fa-plus"></i>
                        <span>Tambah</span>
                    </button>
                </div>
                <div class="overflow-x-auto min-w-full flex-1">
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead>
                            <tr class="bg-slate-100/80 border-b border-slate-200 text-[10px] font-bold text-slate-600 uppercase tracking-wider">
                                <th class="py-2.5 px-4 w-48">Kategori</th>
                                <th class="py-2.5 px-4">Nama Item</th>
                                <th class="py-2.5 px-4 text-right">Anggaran (Rp)</th>
                                <th class="py-2.5 px-4 text-right">Realisasi (Rp)</th>
                                <th class="py-2.5 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-xs">
                            <template x-for="(fin, index) in (activeYear.financings || [])" :key="index">
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 font-bold text-emerald-700 text-[10px]" x-text="fin.category"></td>
                                    <td class="py-3 px-4 font-medium text-slate-900" x-text="fin.name"></td>
                                    <td class="py-3 px-4 text-right text-slate-600" x-text="formatRupiah(fin.anggaran)"></td>
                                    <td class="py-3 px-4 text-right font-medium text-emerald-600" x-text="formatRupiah(fin.realisasi)"></td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap space-x-1">
                                        <button type="button" @click="selectedFin = JSON.parse(JSON.stringify(fin)); selectedFinIndex = index; editFinModalOpen = true;" class="inline-block px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-[10px] rounded transition border border-slate-300">Edit</button>
                                        <button type="button" @click="deleteItem('{{ url('admin/kelola-beranda/apbd-financing') }}/' + selectedYearIndex + '/' + index)" class="px-2 py-1 bg-rose-50 hover:bg-rose-100 text-rose-800 font-bold text-[10px] rounded transition border border-rose-200">Hapus</button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!(activeYear.financings) || activeYear.financings.length === 0">
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400">
                                        <p class="font-semibold text-slate-600 text-[11px]">Belum ada rincian Pembiayaan Desa.</p>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL POPUPS (ALPINE JS)                   -->
    <!-- ========================================== -->

    <!-- MODAL: TAMBAH TAHUN ANGGARAN -->
    <div x-show="createYearModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl max-w-sm w-full border border-slate-200 my-8">
                <form action="{{ route('admin.beranda.apbd.year.store') }}" method="POST" @submit.prevent="submitForm($event, 'createYearModalOpen')">@csrf
                    <div class="bg-white px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-plus text-emerald-600"></i>
                            <h3 class="text-base font-bold text-slate-800">Tambah Tahun Baru</h3>
                        </div>
                        <button type="button" @click="createYearModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="p-6 space-y-4 text-xs text-slate-700">
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Tahun <span class="text-rose-500">*</span></label>
                            <input type="number" name="year" value="{{ date('Y') }}" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium text-lg text-center">
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="createYearModalOpen = false" class="px-4 py-2 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-md transition">Buat Tahun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: TAMBAH PENDAPATAN -->
    <div x-show="createIncomeModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl max-w-lg w-full border border-slate-200 my-8">
                <form :action="'{{ url('admin/kelola-beranda/apbd-income') }}/' + selectedYearIndex + '/store'" method="POST" @submit.prevent="submitForm($event, 'createIncomeModalOpen')">@csrf
                    <div class="bg-white px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-download text-emerald-600"></i>
                            <h3 class="text-base font-bold text-slate-800">Tambah Pendapatan</h3>
                        </div>
                        <button type="button" @click="createIncomeModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="p-6 space-y-4 text-xs text-slate-700">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block font-bold text-slate-800">Kategori Pendapatan <span class="text-rose-500">*</span></label>
                                <div class="text-[10px] space-x-2 font-bold">
                                    <button type="button" @click="manageCategory('add', 'incomes')" class="text-emerald-600 hover:text-emerald-700">+ Tambah</button>
                                    <span class="text-slate-300">|</span>
                                    <button type="button" @click="manageCategory('delete', 'incomes')" class="text-rose-500 hover:text-rose-600"><i class="fas fa-trash-alt mr-1"></i>Hapus</button>
                                </div>
                            </div>
                            <select name="apbd_income_category" x-model="selectedIncome.category" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                                <option value="" disabled>-- Pilih Kategori Pendapatan --</option>
                                <template x-for="cat in apbdCategories.incomes" :key="cat">
                                    <option :value="cat" x-text="cat"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Nama Item <span class="text-rose-500">*</span></label>
                            <input type="text" name="apbd_income_name" required placeholder="Cth: Dana Desa (DD)" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Anggaran (Rp) <span class="text-rose-500">*</span></label>
                                <input type="text" name="apbd_income_anggaran" @input="$event.target.value = formatRupiah($event.target.value)" required placeholder="0" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Realisasi (Rp) <span class="text-rose-500">*</span></label>
                                <input type="text" name="apbd_income_realisasi" @input="$event.target.value = formatRupiah($event.target.value)" required placeholder="0" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="createIncomeModalOpen = false" class="px-4 py-2 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-md transition">Simpan Pendapatan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT PENDAPATAN -->
    <div x-show="editIncomeModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl max-w-lg w-full border border-slate-200 my-8">
                <template x-if="selectedIncome !== null">
                    <form :action="'{{ url('admin/kelola-beranda/apbd-income') }}/' + selectedYearIndex + '/' + selectedIncomeIndex" method="POST" @submit.prevent="submitForm($event, 'editIncomeModalOpen')">
                        @csrf @method('PUT')
                        <div class="bg-white px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-download text-emerald-600"></i>
                                <h3 class="text-base font-bold text-slate-800">Edit Pendapatan</h3>
                            </div>
                            <button type="button" @click="editIncomeModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="p-6 space-y-4 text-xs text-slate-700">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block font-bold text-slate-800">Kategori Pendapatan <span class="text-rose-500">*</span></label>
                                    <div class="text-[10px] space-x-2 font-bold">
                                        <button type="button" @click="manageCategory('add', 'incomes')" class="text-emerald-600 hover:text-emerald-700">+ Tambah</button>
                                        <span class="text-slate-300">|</span>
                                        <button type="button" @click="manageCategory('delete', 'incomes')" class="text-rose-500 hover:text-rose-600"><i class="fas fa-trash-alt mr-1"></i>Hapus</button>
                                    </div>
                                </div>
                                <select name="apbd_income_category" x-model="selectedIncome.category" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                                    <template x-for="cat in apbdCategories.incomes" :key="cat">
                                        <option :value="cat" x-text="cat"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Nama Item <span class="text-rose-500">*</span></label>
                                <input type="text" name="apbd_income_name" x-model="selectedIncome.name" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-800 mb-1">Anggaran (Rp) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="apbd_income_anggaran" x-model="selectedIncome.anggaran" @input="selectedIncome.anggaran = formatRupiah($event.target.value)" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-800 mb-1">Realisasi (Rp) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="apbd_income_realisasi" x-model="selectedIncome.realisasi" @input="selectedIncome.realisasi = formatRupiah($event.target.value)" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                            <button type="button" @click="editIncomeModalOpen = false" class="px-4 py-2 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-md transition">Simpan Perubahan</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>


    <!-- MODAL: TAMBAH BELANJA -->
    <div x-show="createAllocModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl max-w-lg w-full border border-slate-200 my-8">
                <form :action="'{{ url('admin/kelola-beranda/apbd') }}/' + selectedYearIndex + '/store'" method="POST" @submit.prevent="submitForm($event, 'createAllocModalOpen')">@csrf
                    <div class="bg-white px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-upload text-rose-600"></i>
                            <h3 class="text-base font-bold text-slate-800">Tambah Belanja</h3>
                        </div>
                        <button type="button" @click="createAllocModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="p-6 space-y-4 text-xs text-slate-700">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block font-bold text-slate-800">Kategori Bidang <span class="text-rose-500">*</span></label>
                                <div class="text-[10px] space-x-2 font-bold">
                                    <button type="button" @click="manageCategory('add', 'allocations')" class="text-emerald-600 hover:text-emerald-700">+ Tambah</button>
                                    <span class="text-slate-300">|</span>
                                    <button type="button" @click="manageCategory('delete', 'allocations')" class="text-rose-500 hover:text-rose-600"><i class="fas fa-trash-alt mr-1"></i>Hapus</button>
                                </div>
                            </div>
                            <select name="apbd_alloc_category" x-model="selectedAlloc.category" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                                <option value="" disabled>-- Pilih Kategori Bidang --</option>
                                <template x-for="cat in apbdCategories.allocations" :key="cat">
                                    <option :value="cat" x-text="cat"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Nama Kegiatan <span class="text-rose-500">*</span></label>
                            <input type="text" name="apbd_alloc_name" required placeholder="Cth: Operasional RT/RW" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Anggaran (Rp) <span class="text-rose-500">*</span></label>
                                <input type="text" name="apbd_alloc_anggaran" @input="$event.target.value = formatRupiah($event.target.value)" required placeholder="0" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Realisasi (Rp) <span class="text-rose-500">*</span></label>
                                <input type="text" name="apbd_alloc_realisasi" @input="$event.target.value = formatRupiah($event.target.value)" required placeholder="0" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="createAllocModalOpen = false" class="px-4 py-2 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-xl shadow-md transition">Simpan Belanja</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT BELANJA -->
    <div x-show="editAllocModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl max-w-lg w-full border border-slate-200 my-8">
                <template x-if="selectedAlloc !== null">
                    <form :action="'{{ url('admin/kelola-beranda/apbd') }}/' + selectedYearIndex + '/' + selectedAllocIndex" method="POST" @submit.prevent="submitForm($event, 'editAllocModalOpen')">
                        @csrf @method('PUT')
                        <div class="bg-white px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-upload text-rose-600"></i>
                                <h3 class="text-base font-bold text-slate-800">Edit Belanja</h3>
                            </div>
                            <button type="button" @click="editAllocModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="p-6 space-y-4 text-xs text-slate-700">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block font-bold text-slate-800">Kategori Bidang <span class="text-rose-500">*</span></label>
                                    <div class="text-[10px] space-x-2 font-bold">
                                        <button type="button" @click="manageCategory('add', 'allocations')" class="text-emerald-600 hover:text-emerald-700">+ Tambah</button>
                                        <span class="text-slate-300">|</span>
                                        <button type="button" @click="manageCategory('delete', 'allocations')" class="text-rose-500 hover:text-rose-600"><i class="fas fa-trash-alt mr-1"></i>Hapus</button>
                                    </div>
                                </div>
                                <select name="apbd_alloc_category" x-model="selectedAlloc.category" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                                    <template x-for="cat in apbdCategories.allocations" :key="cat">
                                        <option :value="cat" x-text="cat"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Nama Kegiatan <span class="text-rose-500">*</span></label>
                                <input type="text" name="apbd_alloc_name" x-model="selectedAlloc.name" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-800 mb-1">Anggaran (Rp) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="apbd_alloc_anggaran" x-model="selectedAlloc.anggaran" @input="selectedAlloc.anggaran = formatRupiah($event.target.value)" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-800 mb-1">Realisasi (Rp) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="apbd_alloc_realisasi" x-model="selectedAlloc.realisasi" @input="selectedAlloc.realisasi = formatRupiah($event.target.value)" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                            <button type="button" @click="editAllocModalOpen = false" class="px-4 py-2 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-xl shadow-md transition">Simpan Perubahan</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>


    <!-- MODAL: TAMBAH PEMBIAYAAN -->
    <div x-show="createFinModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl max-w-lg w-full border border-slate-200 my-8">
                <form :action="'{{ url('admin/kelola-beranda/apbd-financing') }}/' + selectedYearIndex + '/store'" method="POST" @submit.prevent="submitForm($event, 'createFinModalOpen')">@csrf
                    <div class="bg-white px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-hand-holding-usd text-emerald-600"></i>
                            <h3 class="text-base font-bold text-slate-800">Tambah Pembiayaan</h3>
                        </div>
                        <button type="button" @click="createFinModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="p-6 space-y-4 text-xs text-slate-700">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block font-bold text-slate-800">Kategori <span class="text-rose-500">*</span></label>
                                <div class="text-[10px] space-x-2 font-bold">
                                    <button type="button" @click="manageCategory('add', 'financings')" class="text-emerald-600 hover:text-emerald-700">+ Tambah</button>
                                    <span class="text-slate-300">|</span>
                                    <button type="button" @click="manageCategory('delete', 'financings')" class="text-rose-500 hover:text-rose-600"><i class="fas fa-trash-alt mr-1"></i>Hapus</button>
                                </div>
                            </div>
                            <select name="apbd_financing_category" x-model="selectedFin.category" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                                <option value="" disabled>-- Pilih Kategori --</option>
                                <template x-for="cat in apbdCategories.financings" :key="cat">
                                    <option :value="cat" x-text="cat"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">Nama Item <span class="text-rose-500">*</span></label>
                            <input type="text" name="apbd_financing_name" required placeholder="Cth: SiLPA" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Anggaran (Rp) <span class="text-rose-500">*</span></label>
                                <input type="text" name="apbd_financing_anggaran" @input="$event.target.value = formatRupiah($event.target.value)" required placeholder="0" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Realisasi (Rp) <span class="text-rose-500">*</span></label>
                                <input type="text" name="apbd_financing_realisasi" @input="$event.target.value = formatRupiah($event.target.value)" required placeholder="0" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="createFinModalOpen = false" class="px-4 py-2 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-md transition">Simpan Pembiayaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT PEMBIAYAAN -->
    <div x-show="editFinModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl max-w-lg w-full border border-slate-200 my-8">
                <template x-if="selectedFin !== null">
                    <form :action="'{{ url('admin/kelola-beranda/apbd-financing') }}/' + selectedYearIndex + '/' + selectedFinIndex" method="POST" @submit.prevent="submitForm($event, 'editFinModalOpen')">
                        @csrf @method('PUT')
                        <div class="bg-white px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-hand-holding-usd text-emerald-600"></i>
                                <h3 class="text-base font-bold text-slate-800">Edit Pembiayaan</h3>
                            </div>
                            <button type="button" @click="editFinModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="p-6 space-y-4 text-xs text-slate-700">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block font-bold text-slate-800">Kategori <span class="text-rose-500">*</span></label>
                                    <div class="text-[10px] space-x-2 font-bold">
                                        <button type="button" @click="manageCategory('add', 'financings')" class="text-emerald-600 hover:text-emerald-700">+ Tambah</button>
                                        <span class="text-slate-300">|</span>
                                        <button type="button" @click="manageCategory('delete', 'financings')" class="text-rose-500 hover:text-rose-600"><i class="fas fa-trash-alt mr-1"></i>Hapus</button>
                                    </div>
                                </div>
                                <select name="apbd_financing_category" x-model="selectedFin.category" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                                    <template x-for="cat in apbdCategories.financings" :key="cat">
                                        <option :value="cat" x-text="cat"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Nama Item <span class="text-rose-500">*</span></label>
                                <input type="text" name="apbd_financing_name" x-model="selectedFin.name" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-800 mb-1">Anggaran (Rp) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="apbd_financing_anggaran" x-model="selectedFin.anggaran" @input="selectedFin.anggaran = formatRupiah($event.target.value)" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-800 mb-1">Realisasi (Rp) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="apbd_financing_realisasi" x-model="selectedFin.realisasi" @input="selectedFin.realisasi = formatRupiah($event.target.value)" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                            <button type="button" @click="editFinModalOpen = false" class="px-4 py-2 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-md transition">Simpan Perubahan</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT TAHUN ANGGARAN & KARTU -->
    <div x-show="editYearModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl max-w-lg w-full border border-slate-200 my-8">
                <template x-if="editYearModalOpen">
                    <form :action="'{{ url('admin/kelola-beranda/apbd/year') }}/' + editYearIndex + '/update'" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm($event, 'editYearModalOpen')">@csrf
                        <div class="bg-white px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-pencil-alt text-emerald-600"></i>
                                <h3 class="text-base font-bold text-slate-800">Edit Kartu APBDes</h3>
                            </div>
                            <button type="button" @click="editYearModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="p-6 space-y-4 text-xs text-slate-700">
                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Tahun Anggaran <span class="text-rose-500">*</span></label>
                                <input type="number" name="year" x-model="editYearData.year" required placeholder="Cth: 2024" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Deskripsi Singkat</label>
                                <textarea name="description" x-model="editYearData.description" rows="3" placeholder="Rincian data Anggaran Pendapatan dan Belanja Desa..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-600 font-medium"></textarea>
                                <p class="text-[10px] text-slate-400 mt-1">Teks ini akan muncul di kartu pada halaman publik.</p>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-800 mb-1">Gambar / Thumbnail Kartu</label>
                                <input type="file" name="thumbnail" accept="image/jpeg, image/png, image/webp" @change="const file = $event.target.files[0]; if(file) { $dispatch('open-cropper', { file: file, aspectRatio: 4/3, onCrop: (blob, url) => { let dt = new DataTransfer(); dt.items.add(new File([blob], file.name, {type: file.type})); $event.target.files = dt.files; } }) }" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl font-medium text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                <p class="text-[10px] text-slate-400 mt-1">Opsional. Biarkan kosong jika tidak ingin mengubah gambar.</p>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                            <button type="button" @click="editYearModalOpen = false" class="px-4 py-2 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
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
