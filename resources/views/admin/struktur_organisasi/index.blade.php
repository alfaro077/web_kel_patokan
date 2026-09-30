@extends('layouts.admin')

@section('title', 'Manajemen Struktur Organisasi')
@section('header-title', 'Struktur Organisasi')
@section('header-subtitle', 'Kelola anggota struktur organisasi kelurahan')

@section('content')

<style>
/* Basic CSS for Organization Chart Tree in Admin */
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
    border-top: 2px solid #cbd5e1; /* slate-300 */
    width: 50%; height: 20px;
}
.org-tree li::after{
    right: auto; left: 50%;
    border-left: 2px solid #cbd5e1;
}

.org-tree li:only-child::after, .org-tree li:only-child::before {
    display: none;
}
.org-tree li:only-child{ padding-top: 0;}
.org-tree li:first-child::before, .org-tree li:last-child::after{
    border: 0 none;
}
.org-tree li:last-child::before{
    border-right: 2px solid #cbd5e1;
    border-radius: 0 5px 0 0;
}
.org-tree li:first-child::after{
    border-radius: 5px 0 0 0;
}

.org-tree ul ul::before{
    content: '';
    position: absolute; top: 0; left: 50%;
    border-left: 2px solid #cbd5e1;
    width: 0; height: 20px;
    margin-left: -1px;
}

/* Custom Scrollbar for overflow */
.custom-scrollbar::-webkit-scrollbar {
    height: 8px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9; 
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1; 
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8; 
}
</style>

<div class="space-y-6">
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                Bagan Struktur Organisasi
            </h2>
            <p class="text-sm text-slate-500 mt-1">Arahkan kursor (hover) ke setiap kotak untuk mengedit atau menghapus anggota.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-10 overflow-hidden relative">

    <div class="absolute top-4 right-4 z-50 flex flex-col gap-2 bg-white p-1.5 rounded-xl border border-slate-200 shadow-sm">
        <button type="button" onclick="window.zoomIn()" class="w-8 h-8 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg transition" title="Zoom In"><i class="fas fa-plus"></i></button>
        <button type="button" onclick="window.resetZoom()" class="w-8 h-8 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg transition" title="Reset Zoom"><i class="fas fa-compress"></i></button>
        <button type="button" onclick="window.zoomOut()" class="w-8 h-8 flex items-center justify-center bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg transition" title="Zoom Out"><i class="fas fa-minus"></i></button>
    </div>
    
        <div class="overflow-hidden w-full min-h-[500px] bg-slate-50 rounded-2xl relative cursor-move">
            <div class="min-w-fit flex justify-center py-6">
                
                @if(isset($rootMembers) && $rootMembers->count() > 0)
                <div class="org-tree" id="panzoom-element" style="transform-origin: top center;">
                    <ul>
                        @foreach($rootMembers as $root)
                            @include('admin.struktur_organisasi.org-node-admin', ['member' => $root])
                        @endforeach
                    </ul>
                </div>
                @else
                <div class="text-center py-10 bg-slate-50 rounded-2xl border border-slate-200 w-full max-w-2xl mx-auto">
                    <i class="fas fa-sitemap text-4xl text-slate-300 mb-3"></i>
                    <h3 class="text-lg font-bold text-slate-700">Belum ada struktur organisasi</h3>
                    <p class="text-sm text-slate-500 mt-1">Silakan tambahkan anggota pertama (Ketua/Lurah) untuk memulai.</p>
                    <a href="{{ route('admin.struktur_organisasi.create') }}" class="mt-4 inline-block px-4 py-2 bg-blue-100 text-blue-700 font-medium rounded-xl hover:bg-blue-200 transition">Mulai Buat Struktur</a>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@push('scripts')
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
@endpush

@endsection
