@extends('layouts.app')

@section('title', 'Agenda Kegiatan - ' . ($villageProfile['village_name'] ?? 'Kelurahan Patokan'))

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
            Agenda & Jadwal Kegiatan
        </h1>
        <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-emerald-100 font-medium">
            Informasi jadwal kegiatan yang akan dilaksanakan atau telah dilaksanakan di lingkungan kelurahan.
        </p>
    </div>
    
    <!-- Decorative bottom edge -->
    <div class="absolute bottom-0 inset-x-0 h-4 bg-gradient-to-t from-white to-transparent"></div>
</div>

<!-- Main Content -->
<div class="bg-white py-12 sm:py-16 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="w-full space-y-6">

            {{-- Agenda Grid --}}
            @if($agendas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($agendas as $index => $agenda)
                        <article class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col hover:shadow-md transition duration-200 h-full" data-aos="fade-up" data-aos-delay="{{ 50 + (($index % 6) * 50) }}">
                            
                            {{-- Card Header --}}
                            <div class="bg-emerald-600 px-5 py-4 text-white">
                                <div class="flex items-center justify-between">
                                    <div class="font-bold text-lg leading-tight">
                                        {{ \Carbon\Carbon::parse($agenda->agenda_date)->locale('id')->isoFormat('D MMM Y') }}
                                        @if($agenda->agenda_end_date && $agenda->agenda_date->format('Y-m-d') !== $agenda->agenda_end_date->format('Y-m-d'))
                                            <div class="text-xs font-semibold text-emerald-100 mt-0.5">
                                                s/d {{ \Carbon\Carbon::parse($agenda->agenda_end_date)->locale('id')->isoFormat('D MMM Y') }}
                                            </div>
                                        @endif
                                    </div>
                                    @if($agenda->agenda_time)
                                        <div class="bg-white/20 px-2.5 py-1 rounded-lg text-xs font-mono font-semibold flex items-center gap-1.5">
                                            <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($agenda->agenda_time)->format('H:i') }}
                                            @if($agenda->agenda_end_time)
                                                - {{ \Carbon\Carbon::parse($agenda->agenda_end_time)->format('H:i') }}
                                            @endif
                                            WIB
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-5 sm:p-6 flex-1 flex flex-col">
                                <h3 class="font-bold text-slate-800 text-lg leading-snug mb-3">
                                    {{ $agenda->title }}
                                </h3>
                                
                                @if($agenda->description)
                                    <div class="text-sm text-slate-600 leading-relaxed mb-4 flex-1">
                                        {{ $agenda->description }}
                                    </div>
                                @else
                                    <div class="flex-1"></div>
                                @endif

                                @if($agenda->location)
                                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-start gap-2.5 text-xs text-slate-500 font-medium">
                                        <i class="fas fa-map-marker-alt text-rose-500 mt-0.5"></i>
                                        <span>{{ $agenda->location }}</span>
                                    </div>
                                @endif
                            </div>

                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8 flex justify-center" data-aos="fade-up">
                    {{ $agendas->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-16 bg-white rounded-2xl border border-slate-200 shadow-sm" data-aos="fade-up">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-slate-200">
                        <i class="far fa-calendar-times text-2xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Agenda</h3>
                    <p class="text-sm text-slate-500 mb-4">Saat ini tidak ada jadwal kegiatan yang ditampilkan.</p>
                </div>
            @endif

        </div>

    </div>
</div>

@endsection
