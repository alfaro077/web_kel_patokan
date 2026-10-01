@extends('layouts.app')

@section('title', 'Transparansi APBD - ' . ($villageProfile['village_name'] ?? 'Kelurahan Patokan'))

@section('content')

<!-- FontAwesome Icons CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@php
    // Get APBD Data. If it's empty, create a dummy structure so page doesn't crash.
    $apbdData = $villageProfile['apbd'] ?? [];
    if (!is_array($apbdData) || empty($apbdData) || !isset($apbdData[0])) {
        $apbdData = [
            [
                'year' => date('Y'),
                'incomes' => [],
                'allocations' => [],
                'financings' => []
            ]
        ];
    }
@endphp

<div class="w-full bg-slate-50 min-h-screen text-slate-800 pb-16"
         x-data="{
            viewMode: 'cards',
            printPage() {
                window.print();
            },
            apbdData: {{ json_encode(array_values($apbdData)) }},
            selectedYearIndex: 0,
            
            get activeYear() {
                return this.apbdData[this.selectedYearIndex] || { incomes: [], allocations: [], financings: [] };
            },

            get groupedIncomes() {
                let groups = {};
                (this.activeYear.incomes || []).forEach(item => {
                    let cat = item.category || 'LAIN-LAIN';
                    if(!groups[cat]) groups[cat] = [];
                    groups[cat].push(item);
                });
                return groups;
            },

            get groupedAllocations() {
                let groups = {};
                (this.activeYear.allocations || []).forEach(item => {
                    let cat = item.category || 'LAIN-LAIN';
                    if(!groups[cat]) groups[cat] = [];
                    groups[cat].push(item);
                });
                return groups;
            },

            get groupedFinancings() {
                let groups = {};
                (this.activeYear.financings || []).forEach(item => {
                    let cat = item.category || 'LAIN-LAIN';
                    if(!groups[cat]) groups[cat] = [];
                    groups[cat].push(item);
                });
                return groups;
            },

            cleanNumber(val) {
                if (!val) return 0;
                return parseFloat(val.toString().replace(/\./g, '').replace(/,/g, '.')) || 0;
            },

            formatRupiah(val) {
                if (val === undefined || val === null) return '0';
                let numericStr = val.toString().replace(/[^0-9-]/g, '');
                if (!numericStr || numericStr === '-') return '0';
                return parseInt(numericStr, 10).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
         }">

    <!-- Hero Section -->
    <div class="relative bg-emerald-900 overflow-hidden print:hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-900 opacity-90"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay opacity-30"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
            <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2 drop-shadow-md">
                Transparansi Anggaran (APBDes)
            </h1>
        </div>
        <div class="absolute bottom-0 inset-x-0 h-4 bg-gradient-to-t from-white to-transparent"></div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8 print:py-0 print:space-y-4" data-aos="fade-up">

        <!-- CARDS VIEW -->
        <div x-show="viewMode === 'cards'" x-transition.opacity class="print:hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="(yearData, index) in apbdData" :key="index">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition group cursor-pointer"
                         data-aos="zoom-in" data-aos-delay="100"
                         @click="selectedYearIndex = index; viewMode = 'details'; window.scrollTo({top: 0, behavior: 'smooth'})">
                        
                        <!-- Image Area -->
                        <div class="relative h-48 bg-slate-100 overflow-hidden">
                            <img :src="yearData.thumbnail ? '{{ url('') }}' + yearData.thumbnail : 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=800'" alt="Chart" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                            <!-- Year Badge Top Right -->
                            <div class="absolute top-3 right-3 bg-amber-400 text-amber-900 text-[10px] font-bold px-2 py-1 rounded shadow-sm" x-text="'TAHUN ' + yearData.year"></div>
                        </div>

                        <!-- Content Area -->
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-slate-800 mb-2 leading-snug">
                                Anggaran pendapatan & belanja Tahun <span x-text="yearData.year"></span>
                            </h3>
                            <p class="text-sm text-slate-500 mb-6 line-clamp-2" 
                               x-text="yearData.description ? yearData.description : 'Rincian data Anggaran Pendapatan dan Belanja Desa (APBDes) tahun anggaran ' + yearData.year + ' beserta capaian realisasinya.'">
                            </p>
                            
                            <!-- Footer -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-xs font-bold text-emerald-600 tracking-wider">LIHAT RINCIAN</span>
                                <div class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition">
                                    <i class="fas fa-chevron-right text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <template x-if="apbdData.length === 0">
                <div class="text-center py-16 bg-white rounded-2xl border border-slate-200">
                    <i class="fas fa-folder-open text-4xl text-slate-300 mb-3 block"></i>
                    <p class="text-slate-500 font-medium">Belum ada data APBDes.</p>
                </div>
            </template>
        </div>

        <!-- DETAILS VIEW -->
        <div x-show="viewMode === 'details'" x-transition.opacity style="display: none;">
            
            <div class="mb-6 flex items-center justify-between print:hidden">
                <button type="button" @click="viewMode = 'cards'" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl font-bold text-sm transition shadow-sm">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar</span>
                </button>
            </div>

        <h2 class="hidden print:block text-2xl font-bold text-center mb-6">Laporan APBDes Tahun <span x-text="activeYear.year"></span></h2>

        <!-- 1. PENDAPATAN DESA -->
        <div class="bg-white border border-slate-200 rounded-md overflow-hidden mb-6 shadow-sm">
            <div class="bg-slate-50 px-4 py-3 border-b border-slate-200">
                <h3 class="font-bold text-slate-800 text-sm">1. Pendapatan Desa</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-emerald-600 text-white text-xs">
                            <th class="py-2.5 px-4 font-bold border-b border-emerald-600 w-1/2"></th>
                            <th class="py-2.5 px-4 font-bold border-b border-emerald-600 text-right whitespace-nowrap">Rencana / Anggaran</th>
                            <th class="py-2.5 px-4 font-bold border-b border-emerald-600 text-right whitespace-nowrap">Realisasi</th>
                            <th class="py-2.5 px-4 font-bold border-b border-emerald-600 text-right whitespace-nowrap">Lebih/Kurang</th>
                        </tr>
                    </thead>
                    <template x-for="(items, category) in groupedIncomes" :key="category">
                        <tbody>
                            <tr class="bg-white">
                                <td colspan="4" class="py-2.5 px-4 font-bold text-emerald-700 text-xs uppercase" x-text="category"></td>
                            </tr>
                            <template x-for="(item, idx) in items" :key="idx">
                                <tr class="bg-white hover:bg-slate-50 border-b border-slate-50 transition-colors">
                                    <td class="py-2 px-4 pl-8 text-slate-600 text-[13px]" x-text="item.name"></td>
                                    <td class="py-2 px-4 text-right text-slate-500 text-[13px]" x-text="'Rp. ' + formatRupiah(item.anggaran)"></td>
                                    <td class="py-2 px-4 text-right text-slate-500 text-[13px]" x-text="'Rp. ' + formatRupiah(item.realisasi)"></td>
                                    <td class="py-2 px-4 text-right text-slate-500 text-[13px]" x-text="'Rp. ' + formatRupiah(cleanNumber(item.realisasi) - cleanNumber(item.anggaran))"></td>
                                </tr>
                            </template>
                        </tbody>
                    </template>
                    <template x-if="Object.keys(groupedIncomes).length === 0">
                        <tbody>
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">Tidak ada data pendapatan.</td>
                            </tr>
                        </tbody>
                    </template>
                </table>
            </div>
        </div>

        <!-- 2. BELANJA DESA -->
        <div class="bg-white border border-slate-200 rounded-md overflow-hidden mb-6 shadow-sm">
            <div class="bg-slate-50 px-4 py-3 border-b border-slate-200">
                <h3 class="font-bold text-slate-800 text-sm">2. Belanja Desa</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-emerald-600 text-white text-xs">
                            <th class="py-2.5 px-4 font-bold border-b border-emerald-600 w-1/2"></th>
                            <th class="py-2.5 px-4 font-bold border-b border-emerald-600 text-right whitespace-nowrap">Rencana / Anggaran</th>
                            <th class="py-2.5 px-4 font-bold border-b border-emerald-600 text-right whitespace-nowrap">Realisasi</th>
                            <th class="py-2.5 px-4 font-bold border-b border-emerald-600 text-right whitespace-nowrap">Lebih/Kurang</th>
                        </tr>
                    </thead>
                    <template x-for="(items, category) in groupedAllocations" :key="category">
                        <tbody>
                            <tr class="bg-white">
                                <td colspan="4" class="py-2.5 px-4 font-bold text-emerald-700 text-xs uppercase" x-text="category"></td>
                            </tr>
                            <template x-for="(item, idx) in items" :key="idx">
                                <tr class="bg-white hover:bg-slate-50 border-b border-slate-50 transition-colors">
                                    <td class="py-2 px-4 pl-8 text-slate-600 text-[13px]" x-text="item.name"></td>
                                    <td class="py-2 px-4 text-right text-slate-500 text-[13px]" x-text="'Rp. ' + formatRupiah(item.anggaran)"></td>
                                    <td class="py-2 px-4 text-right text-slate-500 text-[13px]" x-text="'Rp. ' + formatRupiah(item.realisasi)"></td>
                                    <td class="py-2 px-4 text-right text-slate-500 text-[13px]" x-text="'Rp. ' + formatRupiah(cleanNumber(item.realisasi) - cleanNumber(item.anggaran))"></td>
                                </tr>
                            </template>
                        </tbody>
                    </template>
                    <template x-if="Object.keys(groupedAllocations).length === 0">
                        <tbody>
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">Tidak ada data belanja.</td>
                            </tr>
                        </tbody>
                    </template>
                </table>
            </div>
        </div>

        <!-- 3. PEMBIAYAAN DESA -->
        <div class="bg-white border border-slate-200 rounded-md overflow-hidden mb-6 shadow-sm">
            <div class="bg-slate-50 px-4 py-3 border-b border-slate-200">
                <h3 class="font-bold text-slate-800 text-sm">3. Pembiayaan Desa</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-emerald-600 text-white text-xs">
                            <th class="py-2.5 px-4 font-bold border-b border-emerald-600 w-1/2"></th>
                            <th class="py-2.5 px-4 font-bold border-b border-emerald-600 text-right whitespace-nowrap">Rencana / Anggaran</th>
                            <th class="py-2.5 px-4 font-bold border-b border-emerald-600 text-right whitespace-nowrap">Realisasi</th>
                            <th class="py-2.5 px-4 font-bold border-b border-emerald-600 text-right whitespace-nowrap">Lebih/Kurang</th>
                        </tr>
                    </thead>
                    <template x-for="(items, category) in groupedFinancings" :key="category">
                        <tbody>
                            <tr class="bg-white">
                                <td colspan="4" class="py-2.5 px-4 font-bold text-emerald-700 text-xs uppercase" x-text="category"></td>
                            </tr>
                            <template x-for="(item, idx) in items" :key="idx">
                                <tr class="bg-white hover:bg-slate-50 border-b border-slate-50 transition-colors">
                                    <td class="py-2 px-4 pl-8 text-slate-600 text-[13px]" x-text="item.name"></td>
                                    <td class="py-2 px-4 text-right text-slate-500 text-[13px]" x-text="'Rp. ' + formatRupiah(item.anggaran)"></td>
                                    <td class="py-2 px-4 text-right text-slate-500 text-[13px]" x-text="'Rp. ' + formatRupiah(item.realisasi)"></td>
                                    <td class="py-2 px-4 text-right text-slate-500 text-[13px]" x-text="'Rp. ' + formatRupiah(cleanNumber(item.realisasi) - cleanNumber(item.anggaran))"></td>
                                </tr>
                            </template>
                        </tbody>
                    </template>
                    <template x-if="Object.keys(groupedFinancings).length === 0">
                        <tbody>
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">Tidak ada data pembiayaan.</td>
                            </tr>
                        </tbody>
                    </template>
                </table>
            </div>
        </div>

        </div>
    </div>
</div>

@endsection