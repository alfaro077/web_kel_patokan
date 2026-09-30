@extends('layouts.app')

@section('title', 'Struktur Organisasi (SOTK) - ' . ($villageProfile['village_name'] ?? 'Kelurahan Patokan'))

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Basic CSS for Organization Chart Tree */
.org-tree ul {
    padding-top: 20px; position: relative;
    transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
    display: flex;
    justify-content: center;
}

.org-tree li {
    float: left; text-align: center;
    list-style-type: none;
    position: relative;
    padding: 20px 10px 0 10px;
    transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
}

.org-tree li::before, .org-tree li::after{
    content: '';
    position: absolute; top: 0; right: 50%;
    border-top: 1px solid #cbd5e1; /* slate-300 */
    width: 50%; height: 20px;
}
.org-tree li::after{
    right: auto; left: 50%;
    border-left: 1px solid #cbd5e1;
}

.org-tree li:only-child::after, .org-tree li:only-child::before {
    display: none;
}
.org-tree li:only-child{ padding-top: 0;}
.org-tree li:first-child::before, .org-tree li:last-child::after{
    border: 0 none;
}
.org-tree li:last-child::before{
    border-right: 1px solid #cbd5e1;
    border-radius: 0 5px 0 0;
    -webkit-border-radius: 0 5px 0 0;
    -moz-border-radius: 0 5px 0 0;
}
.org-tree li:first-child::after{
    border-radius: 5px 0 0 0;
    -webkit-border-radius: 5px 0 0 0;
    -moz-border-radius: 5px 0 0 0;
}

.org-tree ul ul::before{
    content: '';
    position: absolute; top: 0; left: 50%;
    border-left: 1px solid #cbd5e1;
    width: 0; height: 20px;
    margin-left: -1px;
}
</style>

<!-- Hero Section -->
<div class="relative bg-emerald-900 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-900 opacity-90"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay opacity-30"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 text-center">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-4 drop-shadow-md">
            Struktur Organisasi & SOTK
        </h1>
        <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-emerald-100 font-medium">
            Bagan alur kelembagaan, hierarki aparatur resmi, dan tata kerja Kelurahan.
        </p>
    </div>
    
    <!-- Decorative bottom edge -->
    <div class="absolute bottom-0 inset-x-0 h-4 bg-gradient-to-t from-white to-transparent"></div>
</div>

<!-- Main Content -->
<div class="bg-white py-12 sm:py-16" x-data="{ modalOpen: false, modalName: '', modalPosition: '', modalTupoksi: '' }" @open-tupoksi.window="modalName = $event.detail.name; modalPosition = $event.detail.position; modalTupoksi = $event.detail.tupoksi; modalOpen = true;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="w-full space-y-6">
            <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-slate-200 space-y-8">
                
                <div class="text-center max-w-2xl mx-auto space-y-2">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-black uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        BAGAN STRUKTUR RESMI
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Struktur Organisasi {{ $villageProfile['village_name'] ?? 'Kelurahan Patokan' }}
                    </h2>
                </div>

                <div class="overflow-hidden w-full min-h-[600px] bg-white rounded-2xl relative cursor-move">

                <div class="absolute top-6 right-6 z-50 flex flex-col gap-2 bg-white p-1.5 rounded-xl border border-slate-200 shadow-sm">
                    <button type="button" onclick="window.zoomIn()" class="w-8 h-8 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg transition shadow-sm" title="Zoom In"><i class="fas fa-plus"></i></button>
                    <button type="button" onclick="window.resetZoom()" class="w-8 h-8 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg transition shadow-sm" title="Reset Zoom"><i class="fas fa-compress"></i></button>
                    <button type="button" onclick="window.zoomOut()" class="w-8 h-8 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg transition shadow-sm" title="Zoom Out"><i class="fas fa-minus"></i></button>
                </div>
    
                    <div class="min-w-fit flex justify-center py-6">
                        
                        @if(isset($rootMembers) && $rootMembers->count() > 0)
                        <div class="org-tree" id="panzoom-element" style="transform-origin: top center;">
                            <ul>
                                @foreach($rootMembers as $root)
                                    @include('partials.org-node', ['member' => $root])
                                @endforeach
                            </ul>
                        </div>
                        @else
                        <div class="text-center py-10 bg-slate-50 rounded-2xl border border-slate-200 w-full max-w-2xl">
                            <i class="fas fa-users text-4xl text-slate-300 mb-3"></i>
                            <h3 class="text-lg font-bold text-slate-700">Belum ada struktur organisasi</h3>
                            <p class="text-sm text-slate-500 mt-1">Data struktur organisasi akan ditampilkan di sini.</p>
                        </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Tupoksi Modal -->
    <div x-show="modalOpen" 
         style="display: none;"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalOpen = false"></div>

        <!-- Modal Content -->
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-lg font-bold text-slate-900">Tugas Pokok & Fungsi</h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-red-500 transition-colors p-1">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <div class="mb-4">
                    <h4 class="font-extrabold text-slate-900 text-lg" x-text="modalName"></h4>
                    <span class="inline-block mt-1 bg-slate-900 text-white font-extrabold text-[10px] uppercase px-2.5 py-1 rounded-md tracking-wider" x-text="modalPosition"></span>
                </div>
                
                <div class="prose prose-sm max-w-none text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100" x-html="modalTupoksi">
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                <button @click="modalOpen = false" class="px-5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold rounded-xl text-sm transition-all shadow-sm">Tutup</button>
            </div>
        </div>
    </div>

</div>

<script src="https://unpkg.com/@panzoom/panzoom@4.5.1/dist/panzoom.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const elem = document.getElementById('panzoom-element');
        if(elem) {
            const panzoom = Panzoom(elem, {
                maxScale: 2,
                minScale: 0.3,
                step: 0.1,
                cursor: 'grab'
            });
            
            elem.parentElement.addEventListener('wheel', function(e) {
                e.preventDefault();
                panzoom.zoomWithWheel(e);
            });

            elem.addEventListener('panzoomstart', () => { elem.style.cursor = 'grabbing'; });
            elem.addEventListener('panzoomend', () => { elem.style.cursor = 'grab'; });

            window.zoomIn = () => panzoom.zoomIn();
            window.zoomOut = () => panzoom.zoomOut();
            window.resetZoom = () => { panzoom.reset(); };
        }
    });
</script>

@endsection
