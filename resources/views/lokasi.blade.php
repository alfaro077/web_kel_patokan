@extends('layouts.app')

@section('title', 'Lokasi Kantor - ' . ($villageProfile['village_name'] ?? 'Kelurahan Patokan'))

@section('content')
<!-- Hero Section -->
<div class="relative bg-emerald-900 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-900 opacity-90"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay opacity-30"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 text-center" data-aos="fade-up">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-4 drop-shadow-md">
            Lokasi & Alamat Kantor
        </h1>
        <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-emerald-100 font-medium">
            Informasi alamat lengkap dan peta lokasi kantor {{ $villageProfile['village_name'] ?? 'Kelurahan Patokan' }}.
        </p>
    </div>
    
    <!-- Decorative bottom edge -->
    <div class="absolute bottom-0 inset-x-0 h-4 bg-gradient-to-t from-white to-transparent"></div>
</div>

<!-- Main Content -->
<div class="bg-white py-12 sm:py-16 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
            
            <!-- Left: Peta Lokasi (Takes 7 cols) -->
            <div class="lg:col-span-7 bg-white p-2 rounded-3xl shadow-sm border border-slate-200" data-aos="fade-right" data-aos-delay="100">
                <div class="w-full aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100 relative">
                    @if(!empty($villageProfile['map_embed']))
                        <iframe class="w-full h-full border-0 absolute inset-0" 
                            src="{{ $villageProfile['map_embed'] }}" 
                            allowfullscreen="" loading="lazy"></iframe>
                    @else
                        <div class="flex items-center justify-center w-full h-full text-slate-400">Peta belum diatur</div>
                    @endif
                </div>
            </div>

            <!-- Right: Info Alamat (Takes 5 cols) -->
            <div class="lg:col-span-5 space-y-8" data-aos="fade-left" data-aos-delay="200">
                <div>
                    <h3 class="text-emerald-600 font-bold text-sm tracking-wider uppercase mb-2">Pusat Pelayanan Warga</h3>
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-6">Kantor {{ $villageProfile['village_name'] ?? 'Kelurahan Patokan' }}</h2>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Kunjungi kantor kami pada jam operasional kerja untuk mengurus berbagai keperluan administrasi kependudukan dan layanan publik lainnya secara langsung.
                    </p>
                </div>

                <div class="space-y-6">
                    <!-- Address -->
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                            <i class="fas fa-map-marker-alt text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 mb-1">Alamat Lengkap</h4>
                            <p class="text-slate-500 text-sm leading-relaxed">{{ $villageProfile['address'] ?? 'Jl. Pahlawan No. 01, Kelurahan Patokan, Kecamatan Kraksaan, Kabupaten Probolinggo, Jawa Timur 67282' }}</p>
                        </div>
                    </div>

                    <!-- Office Hours -->
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 mb-1">Jam Operasional</h4>
                            <ul class="text-slate-500 text-sm space-y-1">
                                <li>Senin - Kamis: <span class="font-bold text-slate-700">{{ $villageProfile['office_hours_mon_thu'] ?? '08.00 - 15.30 WIB' }}</span></li>
                                <li>Jumat: <span class="font-bold text-slate-700">{{ $villageProfile['office_hours_fri'] ?? '08.00 - 14.30 WIB' }}</span></li>
                                <li>Sabtu & Minggu: <span class="font-bold text-rose-600">Libur / Tutup</span></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                            <i class="fas fa-envelope text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 mb-1">Email Resmi</h4>
                            <a href="mailto:{{ $villageProfile['email'] ?? 'kelurahanpatokan@probolinggokab.go.id' }}" class="text-emerald-600 hover:text-emerald-700 font-medium text-sm transition">
                                {{ $villageProfile['email'] ?? 'kelurahanpatokan@probolinggokab.go.id' }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-200 flex items-center gap-4">
                    <a href="https://maps.google.com/?q={{ urlencode($villageProfile['address'] ?? 'Kantor Kelurahan Patokan') }}" target="_blank" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm transition flex items-center gap-2 text-sm">
                        <i class="fas fa-directions"></i>
                        <span>Dapatkan Petunjuk Arah</span>
                    </a>
                </div>

            </div>

        </div>
    </div>
@endsection
