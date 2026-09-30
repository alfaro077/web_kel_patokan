@extends('layouts.app')

@section('title', 'Pusat Dokumen Publik - ' . ($villageProfile['village_name'] ?? 'Kelurahan Patokan'))

@section('content')

<!-- Hero Section -->
<div class="relative bg-emerald-900 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-900 opacity-90"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay opacity-30"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 text-center">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-4 drop-shadow-md">
            Arsip Dokumen Publik
        </h1>
        <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-emerald-100 font-medium">
            Daftar dokumen publik yang tersedia untuk diunduh dan dipelajari.
        </p>
    </div>
    
    <!-- Decorative bottom edge -->
    <div class="absolute bottom-0 inset-x-0 h-4 bg-gradient-to-t from-white to-transparent"></div>
</div>

<!-- Main Content -->
<div class="w-full bg-slate-50 py-12 sm:py-16 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="space-y-6">
            @if(count($documents) > 0)
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl overflow-hidden">
                    <div class="divide-y divide-slate-100">
                        @foreach($documents as $doc)
                            <div class="px-6 py-5 hover:bg-slate-50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4 group">
                                <div class="flex items-start gap-4 flex-1">
                                    <div class="w-12 h-12 bg-slate-100 text-slate-400 group-hover:bg-emerald-100 group-hover:text-emerald-600 rounded-xl flex items-center justify-center shrink-0 transition-colors">
                                        <i class="fas fa-folder-open text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-800 group-hover:text-emerald-700 transition-colors mb-1">{{ $doc->name }}</h3>
                                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{!! strip_tags($doc->description) ?: 'Tidak ada deskripsi' !!}</p>
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-[10px] font-bold">
                                                <i class="fas fa-file-pdf"></i> {{ $doc->files_count }} File Tersedia
                                            </span>
                                            <span class="text-[10px] text-slate-400 font-medium">{{ $doc->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0 sm:self-center self-start pl-16 sm:pl-0">
                                    <a href="{{ route('dokumen', ['id' => $doc->id]) }}" class="px-5 py-2.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white text-xs font-bold rounded-xl transition-colors inline-flex items-center gap-2">
                                        <span>Buka Dokumen</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    {{ $documents->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col items-center justify-center text-slate-400 py-16">
                    <i class="fas fa-box-open text-5xl mb-4 text-slate-300"></i>
                    <p class="font-bold text-slate-500">Belum ada arsip dokumen publik.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
