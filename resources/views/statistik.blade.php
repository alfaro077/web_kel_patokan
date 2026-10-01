@extends('layouts.app')

@section('title', 'Statistik Wilayah - ' . ($villageProfile['village_name'] ?? 'Kelurahan Patokan'))

@section('content')

<!-- FontAwesome Icons CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="w-full bg-slate-50 min-h-screen text-slate-800"
         x-data="{
            activeTab: 'kependudukan', 
            printPage() {
                window.print();
            }
         }">

    <!-- Hero Section -->
    <div class="relative bg-emerald-900 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-900 opacity-90"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay opacity-30"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800/50 border border-emerald-400/30 text-emerald-100 text-xs font-bold uppercase tracking-wider mb-4">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>DATA STATISTIK DESA</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-4 drop-shadow-md">
                Statistik Wilayah & Demografi
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-emerald-100 font-medium">
                Portal data terbuka demografi kependudukan, wilayah, dan capaian kinerja Kelurahan Patokan.
            </p>
        </div>
        
        <!-- Decorative bottom edge -->
        <div class="absolute bottom-0 inset-x-0 h-4 bg-gradient-to-t from-white to-transparent"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-6" data-aos="fade-up">


        <!-- TAB SELECTOR NAVIGATION -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto custom-scrollbar text-xs font-bold">
            <button type="button" @click="activeTab = 'kependudukan'"
                    :class="activeTab === 'kependudukan' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
                    class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 whitespace-nowrap">
                <i class="fas fa-users"></i>
                <span>Demografi Penduduk</span>
            </button>
            <button type="button" @click="activeTab = 'wilayah'"
                    :class="activeTab === 'wilayah' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
                    class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 whitespace-nowrap">
                <i class="fas fa-map-marked-alt"></i>
                <span>Profil Wilayah & Sarpras</span>
            </button>
        </div>

        <!-- TAB 1: DEMOGRAFI & KEPENDUDUKAN -->
        <div x-show="activeTab === 'kependudukan'" class="space-y-6">
            <div class="grid grid-cols-1 gap-6">
                <!-- Rasio Jenis Kelamin -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                            <i class="fas fa-venus-mars text-emerald-500"></i>
                            <span>Komposisi Gender</span>
                        </h3>
                        <span class="text-[11px] text-slate-400">Total: {{ $villageProfile['demographics']['total'] ?? '0' }} Jiwa</span>
                    </div>

                    <!-- Progress Bar Perbandingan -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-bold text-blue-600 flex items-center gap-1.5"><i class="fas fa-mars"></i> Laki-Laki ({{ $villageProfile['demographics']['male'] ?? '0' }})</span>
                            <span class="font-bold text-pink-600 flex items-center gap-1.5">Perempuan ({{ $villageProfile['demographics']['female'] ?? '0' }}) <i class="fas fa-venus"></i></span>
                        </div>
                        @php
                            $maleCount = (float) str_replace(['.', ','], ['', '.'], $villageProfile['demographics']['male'] ?? '0');
                            $femaleCount = (float) str_replace(['.', ','], ['', '.'], $villageProfile['demographics']['female'] ?? '0');
                            $totalCount = $maleCount + $femaleCount;
                            $malePct = $totalCount > 0 ? round(($maleCount / $totalCount) * 100, 1) : 50;
                            $femalePct = $totalCount > 0 ? round(($femaleCount / $totalCount) * 100, 1) : 50;
                        @endphp
                        <div class="w-full bg-slate-100 rounded-full h-4 flex overflow-hidden border border-slate-200/60">
                            <div class="bg-blue-500 h-full flex items-center justify-center text-[10px] font-bold text-white transition-all" style="width: {{ $malePct }}%">{{ $malePct }}%</div>
                            <div class="bg-pink-500 h-full flex items-center justify-center text-[10px] font-bold text-white transition-all" style="width: {{ $femalePct }}%">{{ $femalePct }}%</div>
                        </div>
                    </div>
                </div>

                <!-- Kelompok Umur, Pendidikan, dan Pekerjaan -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Kelompok Umur -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
                        <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                            <i class="fas fa-users text-amber-500"></i>
                            <span>Kelompok Umur</span>
                        </h3>
                        <div class="space-y-3 mt-3">
                            @foreach($villageProfile['age_groups'] ?? [] as $age)
                            <div>
                                <div class="flex justify-between text-[11px] font-semibold text-slate-600 mb-1">
                                    <span>{{ $age['label'] }}</span>
                                    <span class="text-slate-900 font-bold">{{ $age['count'] }} jiwa</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-amber-400 h-2 rounded-full" style="width: {{ $totalCount > 0 ? min(100, ($age['count'] / $totalCount) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tingkat Pendidikan -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
                        <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                            <i class="fas fa-graduation-cap text-sky-500"></i>
                            <span>Tingkat Pendidikan</span>
                        </h3>
                        <div class="space-y-3 mt-3">
                            @foreach($villageProfile['educations'] ?? [] as $edu)
                            <div>
                                <div class="flex justify-between text-[11px] font-semibold text-slate-600 mb-1">
                                    <span>{{ $edu['label'] }}</span>
                                    <span class="text-slate-900 font-bold">{{ $edu['count'] }} jiwa</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-sky-500 h-2 rounded-full" style="width: {{ $totalCount > 0 ? min(100, ($edu['count'] / $totalCount) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Jenis Pekerjaan -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
                        <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                            <i class="fas fa-briefcase text-indigo-500"></i>
                            <span>Jenis Pekerjaan</span>
                        </h3>
                        <div class="space-y-3 mt-3">
                            @foreach($villageProfile['occupations'] ?? [] as $occ)
                            <div>
                                <div class="flex justify-between text-[11px] font-semibold text-slate-600 mb-1">
                                    <span class="truncate pr-2">{{ $occ['label'] }}</span>
                                    <span class="text-slate-900 font-bold shrink-0">{{ $occ['count'] }} jiwa</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $totalCount > 0 ? min(100, ($occ['count'] / $totalCount) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: PROFIL WILAYAH, RT/RW, & SARPRAS -->
        <div x-show="activeTab === 'wilayah'" class="space-y-6" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Batas Wilayah Administrasi -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
                    <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                        <i class="fas fa-compass text-emerald-500"></i>
                        <span>Batas Wilayah Administrasi</span>
                    </h3>
                    <div class="grid grid-cols-1 gap-2 text-xs">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                            <span class="font-bold text-slate-500">UTARA</span>
                            <span class="font-semibold text-slate-800">{{ $villageProfile['territory']['north'] ?? '-' }}</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                            <span class="font-bold text-slate-500">TIMUR</span>
                            <span class="font-semibold text-slate-800">{{ $villageProfile['territory']['east'] ?? '-' }}</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                            <span class="font-bold text-slate-500">SELATAN</span>
                            <span class="font-semibold text-slate-800">{{ $villageProfile['territory']['south'] ?? '-' }}</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                            <span class="font-bold text-slate-500">BARAT</span>
                            <span class="font-semibold text-slate-800">{{ $villageProfile['territory']['west'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Fasilitas & Sarana Prasarana -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4 lg:col-span-2">
                    <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                        <i class="fas fa-building text-emerald-500"></i>
                        <span>Inventaris Sarana & Prasarana Umum</span>
                    </h3>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                        <div class="p-3.5 bg-emerald-50/60 rounded-xl border border-emerald-100">
                            <i class="fas fa-school text-emerald-600 text-lg mb-1 block"></i>
                            <span class="text-xl font-black text-slate-900 block">{{ $villageProfile['territory']['schools'] ?? '0' }}</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Sekolah (SD/SMP/SMA)</span>
                        </div>
                        <div class="p-3.5 bg-blue-50/60 rounded-xl border border-blue-100">
                            <i class="fas fa-mosque text-blue-600 text-lg mb-1 block"></i>
                            <span class="text-xl font-black text-slate-900 block">{{ $villageProfile['territory']['mosques'] ?? '0' }}</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Masjid & Musholla</span>
                        </div>
                        <div class="p-3.5 bg-pink-50/60 rounded-xl border border-pink-100">
                            <i class="fas fa-hospital-alt text-pink-600 text-lg mb-1 block"></i>
                            <span class="text-xl font-black text-slate-900 block">{{ $villageProfile['territory']['health'] ?? '0' }}</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Posyandu & Polindes</span>
                        </div>
                        <div class="p-3.5 bg-amber-50/60 rounded-xl border border-amber-100">
                            <i class="fas fa-store text-amber-600 text-lg mb-1 block"></i>
                            <span class="text-xl font-black text-slate-900 block">{{ $villageProfile['territory']['markets'] ?? '0' }}</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Pasar Tradisional</span>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs text-slate-600 leading-relaxed">
                        <span class="font-bold text-slate-800 block mb-1">Informasi Pemekaran Lingkungan:</span>
                        Kelurahan terbagi ke dalam <strong>{{ $villageProfile['territory']['rw'] ?? '0' }} Rukun Warga (RW)</strong> dan <strong>{{ $villageProfile['territory']['rt'] ?? '0' }} Rukun Tetangga (RT)</strong> yang aktif menyelenggarakan rembug warga dan kegiatan poskamling swakarsa secara berkesinambungan.
                    </div>
                </div>


            </div>
        </div>

    </div>
</div>

<style>
/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    height: 4px;
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
</style>

@endsection
