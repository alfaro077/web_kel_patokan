@extends('layouts.app')

@section('title', 'Sejarah Desa - ' . ($villageProfile['village_name'] ?? 'Kelurahan Patokan'))

@section('content')

<!-- FontAwesome Icons CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Hero Section -->
<div class="relative bg-emerald-900 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-900 opacity-90"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay opacity-30"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 text-center" data-aos="fade-up">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-4 drop-shadow-md">
            Sejarah Singkat {{ $villageProfile['village_name'] ?? 'Kelurahan Patokan' }}
        </h1>
        <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-emerald-100 font-medium">
            Menelusuri akar sejarah, asal usul nama, dan perjalanan era pemerintahan.
        </p>
    </div>
    
    <!-- Decorative bottom edge -->
    <div class="absolute bottom-0 inset-x-0 h-4 bg-gradient-to-t from-white to-transparent"></div>
</div>

<!-- Main Content -->
<div class="bg-white py-12 sm:py-16 relative">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-6 sm:p-10 md:p-12 rounded-3xl shadow-sm border border-slate-200" data-aos="fade-up" data-aos-delay="100">
            <div class="space-y-8">
                {{-- Title Header --}}
                <div class="border-b border-slate-100 pb-6">
                    <span class="inline-flex items-center gap-2 text-emerald-700 text-xs font-bold uppercase tracking-widest px-3 py-1 bg-emerald-50 rounded-full border border-emerald-200 mb-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        Sejarah Desa
                    </span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug">
                        Sejarah Desa
                    </h2>
                </div>

                {{-- Narrative & Content --}}
                <div class="space-y-6">
                    <div class="prose prose-slate text-xs sm:text-sm text-slate-600 leading-relaxed space-y-4 font-normal max-w-none">
                        
                        {!! $villageProfile['history_text'] ?? '
                        <p>Belum ada data sejarah yang ditambahkan.</p>
                        ' !!}

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
