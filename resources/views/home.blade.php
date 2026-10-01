@extends('layouts.app')

@section('title', 'Beranda - Portal Resmi ' . ($villageProfile['village_name'] ?? 'Kelurahan Patokan'))

@section('content')

<!-- FontAwesome Icons CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none !important;
    }
    .no-scrollbar {
        -ms-overflow-style: none !important;
        scrollbar-width: none !important;
    }
    .scroll-card {
        flex: 0 0 auto !important;
        width: 260px !important;
        min-width: 260px !important;
        max-width: 260px !important;
    }
    @media (min-width: 640px) {
        .scroll-card {
            width: 280px !important;
            min-width: 280px !important;
            max-width: 280px !important;
        }
    }
</style>

<div x-data="{ 
    // Modal States
    recapModalOpen: false,
    maklumatModalOpen: false,
    lightboxOpen: false,
    statModalOpen: false,
    activePhoto: null,
    videoModalOpen: false,
    activeVideo: null,
    activeVideo: null,

    // Methods
    openPhotoLightbox(photo) {
        this.activePhoto = photo;
        this.lightboxOpen = true;
    },
    openVideoPlayer(video) {
        this.activeVideo = video;
        this.videoModalOpen = true;
    }
}" 
class="relative overflow-x-hidden w-full max-w-full">

    <!-- ========================================================================= -->
    <!-- 1. HERO SLIDER BANNER                                                     -->
    <!-- ========================================================================= -->
    <section 
        x-data="{ 
            currentSlide: 0, 
            slides: {{ $sliderPosts->count() > 0 ? $sliderPosts->count() + 1 : 1 }},
            init() {
                if(this.slides > 1) {
                    setInterval(() => {
                        this.currentSlide = (this.currentSlide + 1) % this.slides;
                    }, 5000);
                }
            }
        }"
        class="relative bg-slate-900 min-h-[500px] lg:min-h-[580px] flex items-center overflow-hidden w-full max-w-full"
    >
        <!-- Slide 0: Static Hero (Selamat Datang) -->
        <div x-show="currentSlide === 0" 
             x-transition:enter="transition ease-in-out duration-1000"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in-out duration-1000"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 w-full h-full"
             style="display: block;"
        >
            <img src="{{ !empty($villageProfile['hero_image']) ? asset('storage/' . $villageProfile['hero_image']) : 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=1600&q=80' }}"
                 alt="Banner Selamat Datang"
                 class="absolute inset-0 w-full h-full object-cover object-center">
            
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/60 to-transparent"></div>

            <div class="relative z-10 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center py-16">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-white text-xs font-bold uppercase tracking-wider mb-4 shadow-sm backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>PORTAL RESMI KELURAHAN</span>
                    </div>
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black leading-tight tracking-tight mb-4 drop-shadow-lg text-white">
                        Selamat Datang di <br/>
                        <span class="text-white drop-shadow-[0_0_15px_rgba(255,255,255,0.5)]">{{ $villageProfile['village_name'] ?? 'Kelurahan Patokan' }}</span>
                    </h1>
                    <p class="text-white text-base sm:text-lg leading-relaxed mb-8 font-semibold drop-shadow-md max-w-lg">
                        Pusat pelayanan kependudukan mandiri, informasi publik, & transparansi APBD. Kami siap melayani Anda dengan sepenuh hati.
                    </p>
                </div>
            </div>
        </div>

        @if($sliderPosts->count() > 0)
            <!-- Slides 1..N: News Slider Posts -->
            @foreach($sliderPosts as $index => $post)
                <div x-show="currentSlide === {{ $index + 1 }}" 
                     x-transition:enter="transition ease-in-out duration-1000"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in-out duration-1000"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 w-full h-full"
                     style="display: none;"
                >
                    <!-- Background Image -->
                    <img src="{{ str_starts_with($post->image, 'http') ? $post->image : asset('storage/' . $post->image) }}"
                         alt="{{ $post->title }}"
                         class="absolute inset-0 w-full h-full object-cover object-center">
                    
                    <!-- Dark Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/60 to-transparent"></div>

                    <!-- Slide Content Overlay -->
                    <div class="relative z-10 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center py-16">
                        <div class="max-w-3xl">
                            <!-- Badge -->
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-white text-xs font-bold uppercase tracking-wider mb-4 shadow-sm backdrop-blur-md">
                                <span class="w-2 h-2 rounded-full bg-{{ $post->category->color_code ?? 'emerald' }}-400 animate-pulse"></span>
                                <span>{{ $post->category->name ?? 'INFORMASI' }}</span>
                            </div>

                            <!-- Slide Title -->
                            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black leading-tight tracking-tight mb-4 drop-shadow-lg text-white">
                                {{ $post->title }}
                            </h1>

                            <!-- Slide Subtitle / Excerpt -->
                            <p class="text-white text-base sm:text-lg leading-relaxed mb-8 font-semibold drop-shadow-md max-w-xl line-clamp-2">
                                {{ Str::limit(strip_tags($post->content), 120) }}
                            </p>
                            
                            <a href="{{ route('berita.detail', $post->slug) }}" class="inline-flex items-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-full shadow-md transition mt-4">
                                Baca Selengkapnya <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <!-- Indicators -->
            <div class="absolute bottom-8 left-0 right-0 z-20 flex justify-center gap-2">
                <!-- Indicator for Slide 0 -->
                <button @click="currentSlide = 0" 
                        :class="{'w-8 bg-emerald-500': currentSlide === 0, 'w-2 bg-white/50 hover:bg-white/80': currentSlide !== 0}"
                        class="h-2 rounded-full transition-all duration-300"></button>
                
                <!-- Indicators for News Slides -->
                @foreach($sliderPosts as $index => $post)
                    <button @click="currentSlide = {{ $index + 1 }}" 
                            :class="{'w-8 bg-emerald-500': currentSlide === {{ $index + 1 }}, 'w-2 bg-white/50 hover:bg-white/80': currentSlide !== {{ $index + 1 }}}"
                            class="h-2 rounded-full transition-all duration-300"></button>
                @endforeach
            </div>
        @endif
    </section>

    <!-- ========================================================================= -->
    <!-- 2. SEKSI SAMBUTAN LURAH / KEPALA INSTANSI                                 -->
    <!-- ========================================================================= -->
    <section class="w-full py-16 lg:py-24 bg-white border-b border-slate-200" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-20 items-center">
                
                <!-- Left: Foto Lurah / Pimpinan -->
                <div class="w-full lg:col-span-1 flex justify-center" data-aos="fade-right" data-aos-delay="100">
                    <div class="relative w-56 sm:w-64 lg:w-72">
                        <img src="{{ !empty($villageProfile['head_photo']) ? asset('storage/' . $villageProfile['head_photo']) : asset('images/sotk/lurah.png') }}"
                             alt="Foto Kepala {{ $villageProfile['village_name'] ?? 'Kelurahan Patokan' }}"
                             class="w-full aspect-[4/5] object-cover rounded-3xl shadow-sm bg-slate-100">

                        <!-- Floating Name Badge -->
                        <div class="absolute -bottom-6 inset-x-4 sm:inset-x-6 bg-white py-4 px-3 sm:py-5 sm:px-4 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] text-center">
                            <h3 class="font-bold text-sm sm:text-base text-slate-900 leading-tight">{{ $villageProfile['head_name'] ?? 'Drs. H. Ahmad Sudirman, M.Si' }}</h3>
                            <p class="text-[9px] sm:text-[10px] text-emerald-600 font-bold uppercase tracking-wider mt-1 sm:mt-2">Kepala {{ $villageProfile['village_name'] ?? 'Kelurahan Patokan' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Sambutan Resmi -->
                <div class="w-full lg:col-span-2 space-y-5 text-center lg:text-left min-w-0 mt-8 lg:mt-0" data-aos="fade-left" data-aos-delay="200">
                    <h4 class="text-emerald-600 text-xs sm:text-sm font-bold uppercase tracking-wide">
                        SAMBUTAN KEPALA KELURAHAN
                    </h4>

                    <h2 class="text-3xl sm:text-4xl lg:text-[42px] font-extrabold text-slate-900 leading-[1.15] tracking-tight">
                        {{ $villageProfile['welcome_title'] ?? 'Komitmen Pelayanan Publik yang Transparan, Cepat, & Responsif' }}
                    </h2>

                    <div class="text-slate-500 text-sm sm:text-base leading-relaxed space-y-4 max-w-3xl mx-auto lg:mx-0 pt-2 prose prose-emerald max-w-none prose-p:text-slate-500 prose-p:leading-relaxed">
                        {!! $villageProfile['welcome_text'] ?? '<p>Melalui sistem portal terpadu ini, Pemerintah Kelurahan Patokan berkomitmen penuh dalam mewujudkan pelayanan publik modern yang berbasis transparansi, kemudahan akses dokumen mandiri, dan akuntabilitas pengelolaan anggaran.</p><p>Kami terus berinovasi untuk memberikan pelayanan terbaik bagi warga Kraksaan tanpa kerumitan administrasi, ramah, akuntabel, dan 100% bebas dari segala bentuk pungutan liar.</p>' !!}
                    </div>

                    <div class="pt-4 flex flex-wrap items-center justify-center lg:justify-start gap-4">
                        <a href="{{ route('visi-misi') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold text-sm transition-colors">
                            <span>Visi & Misi Kami</span>
                            <i class="fas fa-arrow-right text-xs ml-1"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 3. BANNER HIGHLIGHT & STATISTIK TRANSPARANSI (Glassmorphism Hijau Gelap)  -->
    <!-- ========================================================================= -->
    <section id="stat-transparansi" class="py-16 sm:py-20 bg-slate-50 border-y border-slate-200 text-slate-900 relative w-full max-w-full" data-aos="fade-up">
        <!-- Background subtle glow -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-7xl h-96 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 flex flex-col gap-12 lg:gap-20">
            
            <!-- Action Buttons Banner Highlight -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 flex flex-col lg:flex-row items-center justify-between gap-6 shadow-sm border border-slate-200" data-aos="zoom-in" data-aos-delay="100">
                <div class="space-y-2 text-center lg:text-left">
                    <span class="px-3 py-1 rounded-md bg-emerald-500/20 text-emerald-800 font-extrabold text-[10px] uppercase tracking-wider border border-emerald-400/30">
                        DOKUMEN & TRANSPARANSI
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Dokumen & Transparansi {{ $villageProfile['village_name'] ?? 'Kelurahan Patokan' }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600">
                        Informasi & transparansi APBD / Dana Desa.
                    </p>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-3 shrink-0">
                    <a href="{{ route('dokumen') }}"
                       class="px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-lg transition flex items-center gap-2">
                        <i class="fas fa-folder-open"></i>
                        <span>Pusat Unduhan Dokumen</span>
                    </a>

                    <a href="{{ route('transparansi') }}"
                       class="px-6 py-3.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 font-bold text-xs sm:text-sm rounded-xl transition flex items-center gap-2">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Transparansi APBD</span>
                    </a>
                </div>
            </div>

            <!-- Header Section Statistik -->
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-emerald-400 text-xs font-black uppercase tracking-wider">DATA STATISTIK {{ strtoupper($villageProfile['village_name'] ?? 'KELURAHAN PATOKAN') }}</span>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 mt-2 mb-4 leading-tight tracking-tight">
                    Statistik Wilayah {{ $villageProfile['village_name'] ?? 'Kelurahan Patokan' }}
                </h2>
                <p class="text-sm sm:text-base text-slate-500 max-w-2xl mx-auto leading-relaxed">
                    Realisasi kinerja data kependudukan dan penanganan layanan masyarakat {{ $villageProfile['village_name'] ?? 'Kelurahan Patokan' }}.
                </p>
            </div>

            <!-- Grid Cards Statistik Real-Time -->
            <div class="w-full flex flex-wrap justify-center gap-4 sm:gap-6 items-stretch">
                @php
                    $activeStats = array_filter($villageProfile['stats'] ?? [], function($s) { return $s['is_active'] ?? false; });
                @endphp
                
                @foreach($activeStats as $stat)
                    @if(!($stat['is_active'] ?? false)) @continue @endif
                    @php
                        $c = $stat['color'] ?? 'emerald';
                        if ($c === 'emerald') {
                            $cardClass = 'bg-white border border-slate-200/80 hover:-translate-y-0.5 hover:shadow-md';
                            $iconClass = 'bg-emerald-50 text-emerald-600';
                            $valClass = 'text-slate-900';
                            $titleClass = 'text-slate-500';
                        } else {
                            $cardClass = match($c) {
                                'sky' => 'bg-sky-50/50 border border-sky-300 relative overflow-hidden group hover:-translate-y-0.5 hover:shadow-md',
                                'amber' => 'bg-amber-50/50 border border-amber-300 relative overflow-hidden group hover:-translate-y-0.5 hover:shadow-md',
                                'rose' => 'bg-rose-50/50 border border-rose-300 relative overflow-hidden group hover:-translate-y-0.5 hover:shadow-md',
                                'indigo' => 'bg-indigo-50/50 border border-indigo-300 relative overflow-hidden group hover:-translate-y-0.5 hover:shadow-md',
                                default => 'bg-slate-50 border border-slate-300 relative overflow-hidden group hover:-translate-y-0.5 hover:shadow-md'
                            };
                            $iconClass = match($c) {
                                'sky' => 'bg-sky-100/80 text-sky-600 border border-sky-300/50',
                                'amber' => 'bg-amber-100/80 text-amber-600 border border-amber-300/50',
                                'rose' => 'bg-rose-100/80 text-rose-600 border border-rose-300/50',
                                'indigo' => 'bg-indigo-100/80 text-indigo-600 border border-indigo-300/50',
                                default => 'bg-slate-100 text-slate-600'
                            };
                            $valClass = match($c) {
                                'sky' => 'text-sky-600',
                                'amber' => 'text-amber-600',
                                'rose' => 'text-rose-600',
                                'indigo' => 'text-indigo-600',
                                default => 'text-slate-600'
                            };
                            $titleClass = match($c) {
                                'sky' => 'text-sky-600/80',
                                'amber' => 'text-amber-600/80',
                                'rose' => 'text-rose-600/80',
                                'indigo' => 'text-indigo-600/80',
                                default => 'text-slate-500'
                            };
                        }
                        $modalAction = "class=\"";
                    @endphp

                    <div {!! $modalAction . $cardClass !!} rounded-2xl sm:rounded-3xl p-4 sm:p-5 h-full min-h-[120px] transition-all flex flex-col flex-1 min-w-[140px] max-w-[280px]">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-base mb-3 {{ $iconClass }}">
                            <i class="{{ $stat['icon'] ?? 'fas fa-chart-bar' }}"></i>
                        </div>
                        <div class="text-xl sm:text-2xl lg:text-3xl font-black tracking-tight mb-1 truncate {{ $valClass }}">{{ $stat['value'] ?? '-' }}</div>
                        <div class="text-[11px] font-bold uppercase mt-auto {{ $titleClass }}">{{ $stat['title'] ?? '-' }}</div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-center mt-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <a href="{{ route('statistik') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm shadow-md transition hover:-translate-y-0.5">
                    <span>Lihat Statistik Lengkap</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 4. INFO BERITA TERKINI & SIDEBAR MAKLUMAT PELAYANAN                       -->
    <!-- ========================================================================= -->
    <section class="py-16 sm:py-20 bg-slate-50 border-b border-slate-200 w-full max-w-full" data-aos="fade-up">
        <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8">
            <div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Left: Berita Terkini (lg:col-span-8) -->
                <div class="w-full lg:col-span-2 min-w-0 space-y-6" data-aos="fade-right" data-aos-delay="100">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                        <div>
                            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">KABAR KELURAHAN</span>
                            <h3 class="text-xl sm:text-2xl font-black text-slate-900">Berita & Informasi Terbaru</h3>
                        </div>

                        <a href="{{ route('berita') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1">
                            <span>Lihat Semua Berita</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <!-- News Grid (2 Columns) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">

                        @if($latestPosts->isEmpty())
                            <div class="col-span-1 sm:col-span-2 p-8 bg-white border border-slate-200 rounded-2xl text-center text-slate-500 text-sm shadow-sm flex flex-col items-center justify-center">
                                <i class="fas fa-newspaper text-3xl text-slate-300 mb-3"></i>
                                Belum ada berita atau informasi terbaru saat ini.
                            </div>
                        @else
                            @foreach($latestPosts as $post)

                        <article class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group flex flex-col justify-between w-full min-w-0 h-full" data-aos="fade-up" data-aos-delay="{{ 150 + ($loop->index * 100) }}">
                            <div>
                                <!-- Image Thumbnail (Fixed Height) -->
                                <div class="relative h-48 sm:h-52 overflow-hidden bg-slate-900 w-full shrink-0">
                                    <img src="{{ asset($post->image_url ?? ($post->image ? 'storage/'.$post->image : 'https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=800&q=80')) }}"
                                         alt="{{ $post->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    
                                    <span class="absolute top-3 left-3 bg-emerald-600 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider shadow">
                                        {{ $post->category?->name ?? 'BERITA PATOKAN' }}
                                    </span>
                                </div>

                                <!-- Body -->
                                <div class="p-5 space-y-3">
                                    <div class="flex items-center gap-2 text-[11px] font-medium text-slate-400">
                                        <i class="fas fa-calendar-alt text-emerald-600"></i>
                                        <span>{{ isset($post->published_at) ? \Carbon\Carbon::parse($post->published_at)->format('d M Y') : '15 Aug 2026' }}</span>
                                    </div>

                                    <h4 class="font-bold text-base text-slate-900 group-hover:text-emerald-700 transition leading-snug line-clamp-2">
                                        <a href="{{ route('berita.detail', $post->slug ?? '#') }}">{{ $post->title }}</a>
                                    </h4>

                                    <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">
                                        {{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 110) }}
                                    </p>
                                </div>
                            </div>

                            <div class="px-5 pb-5 pt-0">
                                <a href="{{ route('berita.detail', $post->slug ?? '#') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 inline-flex items-center gap-1.5">
                                    <span>Baca Selengkapnya</span>
                                    <i class="fas fa-chevron-right text-[9px]"></i>
                                </a>
                            </div>
                        </article>
                        @endforeach
                        @endif
                    </div>
                </div>

                <!-- Right: Maklumat Pelayanan Sidebar (lg:col-span-4) -->
                <div class="w-full lg:col-span-1 min-w-0 space-y-6" data-aos="fade-left" data-aos-delay="200">
                    <div class="bg-gradient-to-br from-white via-slate-50 to-emerald-50 text-slate-900 border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden space-y-6">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 border border-amber-200 flex items-center justify-center font-bold text-xl shadow-sm">
                            <i class="fas fa-award"></i>
                        </div>

                        <div class="space-y-2">
                            <span class="text-amber-600 font-extrabold text-[10px] uppercase tracking-wider">STANDAR MUTU PELAYANAN</span>
                            <h3 class="text-xl font-black text-slate-900 leading-snug">
                                {{ $villageProfile['maklumat_card_title'] ?? 'Maklumat Pelayanan Publik Resmi' }}
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed font-medium">
                                {{ $villageProfile['maklumat_card_desc'] ?? 'Komitmen penuh seluruh jajaran aparatur ' . ($villageProfile['village_name'] ?? 'Kelurahan Patokan') . ' dalam memberikan hak pelayanan terbaik bagi seluruh warga.' }}
                            </p>
                        </div>

                        <div class="bg-amber-50/80 border border-amber-200 rounded-2xl p-4 text-xs text-slate-700 leading-relaxed italic border-l-4 border-l-amber-500 shadow-sm">
                            {{ $villageProfile['maklumat_card_quote'] ?? '"Dengan ini kami menyatakan sanggup menyelenggarakan pelayanan sesuai standar yang ditetapkan dan siap menerima sanksi apabila melanggar."' }}
                        </div>

                        <button type="button" @click="maklumatModalOpen = true"
                                class="w-full py-3.5 bg-amber-500 hover:bg-amber-600 text-white shadow-md shadow-amber-500/20 text-xs font-extrabold rounded-xl transition flex items-center justify-center gap-2">
                            <i class="fas fa-file-contract"></i>
                            <span>Lihat Naskah Maklumat Resmi</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 5. SINERGI INSTANSI / TAUTAN TERKAIT                                      -->
    <!-- ========================================================================= -->
    <section class="py-16 sm:py-20 bg-gradient-to-b from-slate-50 via-white to-slate-50 border-b border-slate-200/80 w-full max-w-full block clear-both relative overflow-hidden" data-aos="fade-up">
        <!-- Subtle background glow decoration -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 space-y-10 relative z-10">
            <div class="text-center space-y-2 max-w-2xl mx-auto" data-aos="fade-down" data-aos-delay="100">
                <span class="px-3.5 py-1 rounded-full bg-emerald-100/80 text-emerald-800 text-[11px] font-extrabold uppercase tracking-wider inline-flex items-center gap-1.5 shadow-sm border border-emerald-200">
                    <i class="fas fa-handshake text-emerald-600"></i> SINERGI & KEMITRAAN
                </span>
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Sinergi Instansi & Portal Terkait</h3>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">Tautan resmi layanan publik, instansi kedinasan, dan portal pemerintah daerah Kabupaten Probolinggo.</p>
            </div>

            @if(!empty($relatedLinks) && count($relatedLinks) > 0)
                <div class="relative w-full">
                    <style>
                        .no-scrollbar::-webkit-scrollbar {
                            display: none;
                        }
                        .no-scrollbar {
                            -ms-overflow-style: none;
                            scrollbar-width: none;
                        }
                    </style>
                    <div class="flex overflow-x-auto gap-4 sm:gap-6 py-2 px-1 no-scrollbar scroll-smooth"
                         style="scrollbar-width: none; -ms-overflow-style: none;">
                        @foreach($relatedLinks as $link)
                        <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
                           data-aos="zoom-in" data-aos-delay="{{ 100 + (($loop->index % 5) * 80) }}"
                           class="group bg-white hover:bg-slate-900 rounded-3xl p-4 sm:p-5 border border-slate-200/80 hover:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between transform hover:-translate-y-1 relative overflow-hidden w-[240px] sm:w-[260px] md:w-[280px] shrink-0">
                            
                            <!-- Top Logo Container with High Contrast & Soft Frame -->
                            <div class="w-full h-20 sm:h-24 bg-gradient-to-br from-slate-100 to-slate-200/80 group-hover:from-slate-800 group-hover:to-slate-950 rounded-2xl p-3 flex items-center justify-center border border-slate-200/60 group-hover:border-slate-700/60 transition-all duration-300 relative shadow-inner">
                                <img src="{{ str_starts_with($link['logo'], 'http') ? $link['logo'] : asset('storage/' . $link['logo']) }}" 
                                     alt="{{ $link['name'] }}" 
                                     class="max-h-full max-w-full object-contain filter drop-shadow-md group-hover:scale-110 transition-transform duration-300"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($link['name']) }}&background=059669&color=fff'">
                            </div>

                            <!-- Card Info -->
                            <div class="mt-4 flex-1 flex flex-col justify-between text-left space-y-1">
                                <div>
                                    <h4 class="font-extrabold text-xs sm:text-sm text-slate-900 group-hover:text-white transition line-clamp-1 group-hover:translate-x-0.5 duration-300">
                                        {{ $link['name'] }}
                                    </h4>
                                    @if(!empty($link['desc']))
                                        <p class="text-[11px] text-slate-500 group-hover:text-slate-300 mt-1 line-clamp-2 leading-relaxed font-medium transition">
                                            {{ $link['desc'] }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Bottom Link Action -->
                                <div class="pt-3 border-t border-slate-100 group-hover:border-slate-800 flex items-center justify-between text-[11px] font-bold text-emerald-600 group-hover:text-emerald-400 transition mt-2">
                                    <span>Kunjungi Portal</span>
                                    <i class="fas fa-arrow-right text-[10px] transform group-hover:translate-x-1.5 transition duration-300"></i>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="max-w-md mx-auto p-8 bg-white rounded-3xl border border-slate-200 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
                        <i class="fas fa-link text-lg"></i>
                    </div>
                    <p class="text-xs font-semibold text-slate-500">Belum ada daftar mitra instansi yang ditambahkan.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 6. DOKUMENTASI TERPADU (GALERI FOTO & VIDEO KEGIATAN)                     -->
    <!-- ========================================================================= -->
    <section class="py-16 sm:py-20 bg-slate-50 border-t border-slate-200 text-slate-900 w-full max-w-full block clear-both" data-aos="fade-up">
        <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="text-center space-y-2">
                <span class="text-emerald-400 text-xs font-black uppercase tracking-wider">DOKUMENTASI KEGIATAN</span>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">
                    Galeri Foto & Video Kegiatan Kelurahan
                </h2>
            </div>

            <div class="w-full">
                <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-6">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-photo-video text-emerald-400"></i>
                        <span>Dokumentasi Terbaru</span>
                    </h3>
                    <a href="{{ route('galeri') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition">
                        Lihat Semua Galeri
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-5">
                    @foreach($galleries as $item)
                    <div class="bg-white border border-slate-200 shadow-sm rounded-2xl overflow-hidden group w-full min-w-0 flex flex-col h-full">
                        <div class="relative h-32 sm:h-36 lg:h-40 overflow-hidden shrink-0 w-full bg-slate-100">
                            <img src="{{ asset($item->image_url ?? ($item->image ? 'storage/'.$item->image : 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=800&q=80')) }}"
                                 alt="{{ $item->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            
                            @if($item->type === 'video')
                            <!-- Play Button Overlay -->
                            <button type="button" @click="openVideoPlayer({{ json_encode($item) }})"
                                    class="absolute inset-0 bg-slate-950/40 flex items-center justify-center text-white transition hover:bg-slate-950/60">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-2xl transition transform group-hover:scale-110">
                                    <i class="fas fa-play text-sm sm:text-base pl-0.5 sm:pl-1"></i>
                                </div>
                            </button>
                            <span class="absolute top-2 left-2 bg-rose-600 text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded shadow flex items-center gap-1 uppercase">
                                <i class="fas fa-video"></i> Video
                            </span>
                            @else
                            <button type="button" @click="openPhotoLightbox({{ json_encode($item) }})"
                                    class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white gap-2 font-bold text-xs">
                                <i class="fas fa-search-plus text-lg"></i>
                                <span>Perbesar</span>
                            </button>
                            <span class="absolute top-2 left-2 bg-emerald-600 text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded shadow flex items-center gap-1 uppercase">
                                <i class="fas fa-camera"></i> Foto
                            </span>
                            @endif
                        </div>
                        <div class="p-3 sm:p-4 space-y-1">
                            <div class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $item->category ?? 'KEGIATAN' }}</div>
                            <h4 class="font-bold text-xs text-slate-900 line-clamp-2">{{ $item->title }}</h4>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- MODAL POPUPS (ALPINE.JS)                                                  -->
    <!-- ========================================================================= -->

    <!-- 1. Modal Rekap Bulanan -->
    <div x-show="recapModalOpen" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        
        <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-4xl w-full p-6 sm:p-8 space-y-6 text-white max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                <h3 class="text-lg font-bold flex items-center gap-2 text-emerald-400">
                    <i class="fas fa-table"></i>
                    <span>Tabel Detail Rekapitulasi Pelayanan Bulanan (2026)</span>
                </h3>
                <button type="button" @click="recapModalOpen = false" class="text-slate-400 hover:text-white p-2">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-slate-600 border border-slate-800">
                    <thead class="bg-slate-950 text-slate-200 font-bold uppercase border-b border-slate-200">
                        <tr>
                            <th class="p-3 border-r border-slate-800">Bulan</th>
                            <th class="p-3 border-r border-slate-800">Permohonan Surat</th>
                            <th class="p-3 border-r border-slate-800">Surat Keluar</th>
                            <th class="p-3 border-r border-slate-800">Aduan Masuk</th>
                            <th class="p-3">Persentase Capaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach($stats['monthly'] ?? [] as $row)
                        <tr class="hover:bg-slate-800/50">
                            <td class="p-3 font-bold text-white border-r border-slate-800">{{ $row['month'] }}</td>
                            <td class="p-3 border-r border-slate-800 text-emerald-400 font-semibold">{{ $row['permohonan'] }} Dokumen</td>
                            <td class="p-3 border-r border-slate-800 text-amber-300 font-semibold">{{ $row['surat_keluar'] }} Dokumen</td>
                            <td class="p-3 border-r border-slate-800 font-semibold">{{ $row['pengaduan'] }} Aduan</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-extrabold text-[10px]">
                                    {{ $row['pct'] }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-800">
                <button type="button" @click="recapModalOpen = false" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- 2. Modal Maklumat Pelayanan -->
    <div x-show="maklumatModalOpen" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        
        <div class="bg-white border border-slate-200 rounded-3xl max-w-2xl w-full p-6 sm:p-8 space-y-6 text-slate-800 max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                    <i class="fas fa-award text-amber-500"></i>
                    <span>Naskah Maklumat Pelayanan Resmi</span>
                </h3>
                <button type="button" @click="maklumatModalOpen = false" class="text-slate-400 hover:text-slate-700 p-2">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            @if(!empty($villageProfile['maklumat_image']))
                <div class="w-full rounded-xl overflow-hidden border border-slate-200 bg-slate-100 flex items-center justify-center p-2">
                    <img src="{{ asset('storage/' . $villageProfile['maklumat_image']) }}" alt="Maklumat Pelayanan Publik" class="w-full aspect-[4/3] object-cover rounded-lg shadow-sm">
                </div>
            @else
                <div class="space-y-4 text-xs leading-relaxed text-slate-700 border-l-4 border-amber-400 pl-4 bg-amber-50/50 p-4 rounded-r-xl">
                    <div class="mb-4 sm:mb-6">
                        <span class="text-[9px] sm:text-[10px] font-extrabold text-emerald-700 tracking-widest">{{ strtoupper($villageProfile['regency'] ?? 'PEMERINTAH KABUPATEN PROBOLINGGO') }} &bull; {{ strtoupper($villageProfile['subdistrict'] ?? 'KECAMATAN KRAKSAAN') }} &bull; {{ strtoupper($villageProfile['village_name'] ?? 'KELURAHAN PATOKAN') }}</span>
                    </div>
                    
                    <h2 class="text-lg sm:text-2xl font-black text-slate-900 mb-6 leading-tight uppercase">Maklumat Pelayanan Publik</h2>
                    
                    <p class="italic whitespace-pre-wrap">"{!! $maklumatText ?? 'Dengan ini, kami seluruh ASN dan Pegawai Pemerintah Kelurahan Patokan menyatakan sanggup menyelenggarakan pelayanan sesuai standar pelayanan yang telah ditetapkan dan siap menerima sanksi sesuai ketentuan perundang-undangan yang berlaku apabila pelayanan tidak sesuai janji.' !!}"</p>
                    
                    <div class="mt-6 flex items-center justify-center gap-3 text-xs sm:text-sm font-bold text-slate-900">
                        <span>Kepala {{ $villageProfile['village_name'] ?? 'Kelurahan Patokan' }}</span>
                    </div>
                </div>
            @endif

            <div class="flex justify-end pt-4 border-t border-slate-200">
                <button type="button" @click="maklumatModalOpen = false" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl">
                    Saya Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- 3. Modal Photo Lightbox -->
    <div x-show="lightboxOpen" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md">
        
        <div class="bg-slate-900 border border-slate-800 rounded-3xl max-w-2xl w-full overflow-hidden text-white shadow-2xl flex flex-col">
            
            <!-- KUNCI UKURAN KONSISTEN: Ubah tinggi menggunakan h-80 (atau h-96 untuk layar lebih besar) dan maksimalkan lebar max-w-2xl pada modal di atas -->
            <div class="relative h-72 sm:h-96 w-full overflow-hidden bg-black flex items-center justify-center shrink-0">
                <img :src="activePhoto ? (activePhoto.image_url || ('/storage/' + activePhoto.image)) : ''"
                     :alt="activePhoto ? activePhoto.title : ''"
                     class="w-full h-full object-cover">
                
                <button type="button" @click="lightboxOpen = false" class="absolute top-4 right-4 bg-slate-950/70 hover:bg-slate-950 text-white w-9 h-9 rounded-full flex items-center justify-center transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6 h-36 flex flex-col justify-between shrink-0">
                <div>
                    <h4 class="font-bold text-base text-white line-clamp-1" x-text="activePhoto ? activePhoto.title : ''"></h4>
                    <p class="text-xs text-slate-400 line-clamp-2 mt-1" x-text="activePhoto ? (activePhoto.description || activePhoto.date) : ''"></p>
                </div>
                <div class="flex justify-end mt-2">
                    <a :href="activePhoto ? (activePhoto.image_url || ('/storage/' + activePhoto.image)) : '#'" download target="_blank"
                       class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl flex items-center gap-2 transition">
                        <i class="fas fa-download"></i>
                        <span>Unduh Foto</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Video Player (Ukuran & Proporsi Identik dengan Modal Foto) --}}
    <div x-show="videoModalOpen"
         x-cloak
         x-transition.opacity
         class="fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-md flex items-center justify-center p-4"
         style="display: none;">
        
        <!-- Wadah Utama: max-w-2xl w-full rounded-3xl (PERSIS SAMA SEPERTI MODAL FOTO) -->
        <div class="relative bg-slate-900 text-white rounded-3xl overflow-hidden max-w-2xl w-full border border-slate-800 shadow-2xl flex flex-col"
             >
            
            {{-- Modal Header --}}
            <div class="flex items-center justify-between p-4 bg-slate-950 border-b border-slate-800 shrink-0">
                <div class="flex items-center gap-2">
                    <span class="bg-rose-600 text-white text-[10px] font-black uppercase px-2.5 py-0.5 rounded-md flex items-center gap-1">
                        <i class="fab fa-youtube"></i> VIDEO
                    </span>
                    <span class="text-slate-400 text-xs font-bold truncate max-w-[320px]" x-text="activeVideo ? activeVideo.title : 'Pemutar Video'"></span>
                </div>

                <button type="button" @click="videoModalOpen = false" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-white transition flex items-center justify-center focus:outline-none">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            {{-- Modal Video Viewport: Kunci tinggi h-72 sm:h-96 (PERSIS SAMA DENGAN FOTO) --}}
            <div class="relative bg-black h-72 sm:h-96 flex items-center justify-center overflow-hidden w-full shrink-0">
                <template x-if="videoModalOpen && activeVideo">
                    <iframe class="w-full h-full border-0"
                            :src="'https://www.youtube.com/embed/' + (activeVideo.youtube_id || activeVideo.id) + '?autoplay=1'"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </template>
            </div>

            {{-- Modal Footer: Kunci tinggi h-36 dengan padding p-6 (PERSIS SAMA DENGAN FOOTER FOTO) --}}
            <div class="p-6 h-36 flex flex-col justify-between shrink-0 bg-slate-900 border-t border-slate-800">
                <div>
                    <h3 x-text="activeVideo ? activeVideo.title : ''" class="font-bold text-base text-white line-clamp-1"></h3>
                    <p class="text-xs text-slate-400 line-clamp-2 mt-1" x-text="activeVideo ? (activeVideo.date || (activeVideo.created_at ? activeVideo.created_at : '')) : ''"></p>
                </div>
                <div class="flex justify-end mt-2">
                    <a :href="activeVideo ? ('https://www.youtube.com/watch?v=' + (activeVideo.youtube_id || activeVideo.id)) : '#'" 
                       target="_blank"
                       class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl flex items-center gap-2 transition">
                        <i class="fab fa-youtube"></i>
                        <span>Tonton di YouTube</span>
                    </a>
                </div>
            </div>

        </div>
    </div>



</div>

@endsection
