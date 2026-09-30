@extends('layouts.app')

@section('title', 'Visi & Misi - ' . ($villageProfile['village_name'] ?? 'Kelurahan Patokan'))

@section('content')

<!-- FontAwesome Icons CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Hero Section -->
<div class="relative bg-emerald-900 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-900 opacity-90"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay opacity-30"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 text-center">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-4 drop-shadow-md">
            Visi & Misi Pembangunan
        </h1>
        <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-emerald-100 font-medium">
            Arah kebijakan, cita-cita, dan komitmen pelayanan Pemerintah {{ $villageProfile['village_name'] ?? 'Kelurahan Patokan' }}.
        </p>
    </div>
    
    <!-- Decorative bottom edge -->
    <div class="absolute bottom-0 inset-x-0 h-4 bg-gradient-to-t from-white to-transparent"></div>
</div>

<!-- Main Content -->
<div class="bg-white py-12 sm:py-16 relative">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-6 sm:p-10 md:p-12 rounded-3xl shadow-sm border border-slate-200">
            <div class="space-y-10">
                {{-- Header Banner --}}
                <div class="border-b border-slate-100 pb-6">
                    <span class="inline-flex items-center gap-2 text-emerald-700 text-xs font-bold uppercase tracking-widest px-3 py-1 bg-emerald-50 rounded-full border border-emerald-200 mb-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        Pedoman Pembangunan Kelurahan
                    </span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug">
                        Visi Misi {{ str_replace('Kelurahan', 'Desa', $villageProfile['village_name'] ?? 'Desa Patokan') }}
                    </h2>
                </div>

                {{-- VISI SECTION --}}
                <div class="space-y-4">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Visi</h3>
                    <div class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-none font-medium">
                        {!! $villageProfile['vision'] ?? 'Terwujudnya Desa Patokan yang maju, mandiri, sejahtera, dan berkeadilan dengan tata kelola pemerintahan yang transparan dan akuntabel' !!}
                    </div>
                </div>

                {{-- MISI SECTION --}}
                <div class="space-y-4">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Misi</h3>
                    <div class="prose prose-slate prose-li:marker:text-slate-400 text-sm sm:text-base text-slate-600 leading-relaxed max-w-none">
                        {!! $villageProfile['mission'] ?? '
                        <ul class="list-disc ml-5 space-y-2">
                            <li><strong>Peningkatan Pelayanan Publik:</strong> Memberikan pelayanan yang prima, cepat, transparan, dan adil kepada seluruh masyarakat tanpa membedakan golongan.</li>
                            <li><strong>Pembangunan Infrastruktur:</strong> Mempercepat pemerataan dan perbaikan infrastruktur desa, seperti renovasi fasilitas umum/balai desa, perbaikan jalan, dan saluran irigasi.</li>
                            <li><strong>Pemberdayaan Ekonomi</strong></li>
                            <li><strong>Peningkatan SDM dan Kesehatan:</strong> Meningkatkan kualitas sumber daya manusia melalui dukungan pendidikan, pembinaan kepemudaan, serta program kesehatan seperti pencegahan stunting dan layanan posyandu</li>
                        </ul>
                        ' !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
