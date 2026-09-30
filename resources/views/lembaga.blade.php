@extends('layouts.app')

@section('title', 'Lembaga Kemasyarakatan - ' . ($villageProfile['village_name'] ?? 'Kelurahan Patokan'))

@section('content')
<!-- Hero Section -->
<div class="relative bg-emerald-900 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-900 opacity-90"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay opacity-30"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 text-center">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-4 drop-shadow-md">
            Lembaga Kemasyarakatan
        </h1>
        <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-emerald-100 font-medium">
            Mengenal berbagai lembaga yang berperan dalam pembangunan dan pemberdayaan masyarakat di {{ $villageProfile['village_name'] ?? 'Kelurahan Patokan' }}.
        </p>
    </div>
    
    <!-- Decorative bottom edge -->
    <div class="absolute bottom-0 inset-x-0 h-4 bg-gradient-to-t from-white to-transparent"></div>
</div>

<!-- Main Content -->
<div class="bg-white py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center gap-3 mb-8 pb-4 border-b-2 border-emerald-500/20">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-emerald-800 tracking-tight">Lembaga Kemasyarakatan</h2>
        </div>

        <div class="overflow-x-auto min-w-full rounded-xl sm:rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-50 border-b-2 border-slate-200">
                        <th class="py-4 px-6 text-sm font-bold text-slate-600 w-2/5">Nama Lembaga</th>
                        <th class="py-4 px-6 text-sm font-bold text-slate-600 w-2/5">Ketua/Pimpinan & Deskripsi</th>
                        <th class="py-4 px-6 text-sm font-bold text-slate-600 text-center w-1/5">Logo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($profile['lembaga'] ?? [] as $lembaga)
                        <tr class="hover:bg-slate-50 transition duration-150 ease-in-out group">
                            <!-- Column 1: Name and Badge -->
                            <td class="py-5 px-6 align-top">
                                <div class="text-emerald-700 font-black text-sm sm:text-base uppercase tracking-wide group-hover:text-emerald-600 transition">
                                    {{ $lembaga['name'] }}
                                </div>
                                @if(!empty($lembaga['singkatan']))
                                    <div class="mt-1.5 inline-block px-2 py-0.5 bg-emerald-600 text-white rounded text-[10px] sm:text-xs font-bold uppercase tracking-wider shadow-sm">
                                        {{ $lembaga['singkatan'] }}
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Column 2: Details / Chairman -->
                            <td class="py-5 px-6 align-top text-sm text-slate-600 font-medium leading-relaxed">
                                @if(!empty($lembaga['ketua']))
                                    <div class="mb-1"><span class="text-slate-400 font-semibold text-xs uppercase tracking-wider block mb-0.5">Ketua/Pimpinan:</span> {{ $lembaga['ketua'] }}</div>
                                @endif
                                
                                @if(!empty($lembaga['description']))
                                    <div>
                                        @if(!empty($lembaga['ketua']))
                                            <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider block mt-2 mb-0.5">Keterangan:</span>
                                        @endif
                                        {{ $lembaga['description'] }}
                                    </div>
                                @endif
                                
                                @if(empty($lembaga['ketua']) && empty($lembaga['description']))
                                    <span class="text-slate-300 italic">-</span>
                                @endif
                            </td>
                            
                            <!-- Column 3: Logo -->
                            <td class="py-5 px-6 align-middle text-center">
                                <div class="flex justify-center">
                                    @if(!empty($lembaga['logo']))
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border border-slate-200 p-1.5 bg-white shadow-sm flex items-center justify-center overflow-hidden transition-transform duration-300 group-hover:scale-110 group-hover:shadow-md">
                                            <img src="{{ asset('storage/' . $lembaga['logo']) }}" alt="{{ $lembaga['name'] }}" class="max-w-full max-h-full object-contain">
                                        </div>
                                    @else
                                        <!-- Placeholder like the camera icon with cross in screenshot -->
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-[3px] border-slate-200 bg-slate-50 flex flex-col items-center justify-center text-slate-300">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4l16 16" class="text-slate-200"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 mb-4">
                                    <i class="fas fa-users-slash text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Data</h3>
                                <p class="text-sm text-slate-500">Data lembaga kemasyarakatan belum ditambahkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
