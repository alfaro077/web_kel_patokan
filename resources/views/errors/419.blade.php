<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Habis (419) - Kelurahan Patokan</title>

    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 text-slate-800 antialiased selection:bg-emerald-500 selection:text-white relative overflow-hidden">
    
    <!-- Background Decoration -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-emerald-500/10 blur-[120px]"></div>
        <div class="absolute top-[60%] -right-[10%] w-[40%] h-[60%] rounded-full bg-amber-500/10 blur-[100px]"></div>
    </div>

    <div class="max-w-md w-full text-center space-y-6 relative z-10">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-rose-100 text-rose-500 mb-2 shadow-inner">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <div class="space-y-2">
            <h1 class="text-6xl font-black text-slate-900 tracking-tight drop-shadow-sm">419</h1>
            <h2 class="text-2xl font-extrabold text-slate-800">Sesi Telah Habis</h2>
        </div>
        
        <p class="text-slate-500 text-sm font-medium leading-relaxed max-w-xs mx-auto">
            Halaman yang Anda tuju sudah kadaluarsa karena terlalu lama tidak ada aktivitas. Silakan muat ulang atau kembali ke halaman sebelumnya.
        </p>

        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
            <button onclick="window.location.reload(true)" class="w-full sm:w-auto px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm rounded-xl transition shadow-md flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span>Muat Ulang Halaman</span>
            </button>
            <a href="{{ url()->previous() }}" class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold text-sm rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                <span>Kembali</span>
            </a>
        </div>
        
        <div class="mt-8 text-xs font-semibold text-slate-400">
            &copy; {{ date('Y') }} Kelurahan Patokan
        </div>
    </div>
</body>
</html>
