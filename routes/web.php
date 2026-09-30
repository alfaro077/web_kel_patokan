<?php

use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrganizationMemberController as AdminOrganizationMemberController;

use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;

use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\PostCategoryController as AdminPostCategoryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ResidentController as AdminResidentController;
use App\Http\Controllers\Admin\RtRwController as AdminRtRwController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ServiceTypeController as AdminServiceTypeController;
use App\Http\Controllers\Admin\AgendaController as AdminAgendaController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VillageProfileController as AdminVillageProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use Illuminate\Support\Facades\Route;

// ==========================================
// PUBLIC ROUTES
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/visi-misi', [HomeController::class, 'visiMisi'])->name('visi-misi');
Route::get('/struktur-organisasi', [HomeController::class, 'strukturOrganisasi'])->name('struktur-organisasi');
Route::get('/sejarah', [HomeController::class, 'sejarah'])->name('sejarah');
Route::get('/berita', [HomeController::class, 'berita'])->name('berita');
Route::get('/berita/{slug}', [HomeController::class, 'beritaDetail'])->name('berita.detail');
Route::get('/layanan/{slug}', [HomeController::class, 'layananDetail'])->name('layanan.detail');
Route::get('/lokasi', [HomeController::class, 'lokasi'])->name('lokasi');
Route::get('/layanan-whatsapp', [HomeController::class, 'layananWhatsapp'])->name('layanan-whatsapp');
Route::get('/galeri', [HomeController::class, 'galeri'])->name('galeri');
Route::get('/transparansi', [HomeController::class, 'transparansi'])->name('transparansi');
Route::get('/dokumen', [HomeController::class, 'dokumen'])->name('dokumen');
Route::get('/standar-pelayanan', [HomeController::class, 'standarPelayanan'])->name('standar-pelayanan');
Route::get('/pengumuman', [HomeController::class, 'pengumuman'])->name('pengumuman');
Route::get('/agenda', [HomeController::class, 'agenda'])->name('agenda');
Route::get('/halaman/{slug}', [HomeController::class, 'page'])->name('page');
Route::get('/profil/{slug}', [\App\Http\Controllers\ProfilePageController::class, 'show'])->name('profile.page');
Route::get('/lembaga', [HomeController::class, 'lembaga'])->name('lembaga');
Route::get('/statistik', [HomeController::class, 'statistik'])->name('statistik');

// ==========================================
// GUEST ONLY AUTHENTICATION ROUTES
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/reload-captcha', [AuthController::class, 'reloadCaptcha'])->name('captcha.reload');
    Route::post('/forgot-password', [AuthController::class, 'resetPassword'])->name('password.reset.submit');
});

// ==========================================
// AUTHENTICATED ROUTES
// ==========================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ==========================================
    // 1. SHARED ADMIN & STAFF PANEL ROUTES (Role: Admin & Staff)
    // ==========================================
    Route::middleware('staff')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);

        // CMS: Berita & Artikel
        Route::prefix('berita')->name('berita.')->group(function () {
            Route::get('/', [AdminPostController::class, 'index'])->name('index');
            Route::post('/', [AdminPostController::class, 'store'])->name('store');
            Route::put('/{id}', [AdminPostController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminPostController::class, 'destroy'])->name('destroy');
            Route::patch('/{id}/toggle-featured', [AdminPostController::class, 'toggleFeatured'])->name('toggle_featured');
        });

        // CMS: Media Library & Pengelola Berkas Publik
        Route::prefix('media')->name('media.')->group(function () {
            Route::get('/', [AdminMediaController::class, 'index'])->name('index');
            Route::post('/', [AdminMediaController::class, 'store'])->name('store');
            Route::delete('/', [AdminMediaController::class, 'destroy'])->name('destroy');
        });

        // CMS: Pengumuman & Running Text Marquee
        Route::prefix('pengumuman')->name('pengumuman.')->group(function () {
            Route::get('/', [AdminAnnouncementController::class, 'index'])->name('index');
            Route::post('/', [AdminAnnouncementController::class, 'store'])->name('store');
            Route::put('/{id}', [AdminAnnouncementController::class, 'update'])->name('update');
            Route::patch('/{id}/toggle', [AdminAnnouncementController::class, 'toggle'])->name('toggle');
            Route::delete('/{id}', [AdminAnnouncementController::class, 'destroy'])->name('destroy');
        });

        // CMS: Agenda Kegiatan
        Route::prefix('agenda')->name('agenda.')->group(function () {
            Route::get('/', [AdminAgendaController::class, 'index'])->name('index');
            Route::post('/', [AdminAgendaController::class, 'store'])->name('store');
            Route::put('/{id}', [AdminAgendaController::class, 'update'])->name('update');
            Route::patch('/{id}/toggle', [AdminAgendaController::class, 'toggle'])->name('toggle');
            Route::delete('/{id}', [AdminAgendaController::class, 'destroy'])->name('destroy');
        });

        // CMS: Struktur Organisasi
        Route::resource('struktur-organisasi', AdminOrganizationMemberController::class)->except(['show'])->names('struktur_organisasi');


        // CMS: Galeri Kegiatan Foto
        Route::prefix('galeri')->name('galeri.')->group(function () {
            Route::get('/', [AdminGalleryController::class, 'index'])->name('index');
            Route::post('/', [AdminGalleryController::class, 'store'])->name('store');
            Route::put('/{id}', [AdminGalleryController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminGalleryController::class, 'destroy'])->name('destroy');
            Route::delete('/{galleryId}/image/{imageId}', [AdminGalleryController::class, 'destroyImage'])->name('destroyImage');
            Route::patch('/{id}/toggle-homepage', [AdminGalleryController::class, 'toggleHomepage'])->name('toggle_homepage');
        });

        // Statistik Kelurahan
        Route::prefix('kelola-beranda')->name('beranda.')->group(function () {
            Route::get('statistik', [\App\Http\Controllers\Admin\VillageProfileController::class, 'statistik'])->name('statistik');
            
            // Basic Stats Routes
            Route::post('statistik/basic/store', [\App\Http\Controllers\Admin\VillageProfileController::class, 'storeBasicStat'])->name('statistik.basic.store');
            Route::put('statistik/basic/{index}', [\App\Http\Controllers\Admin\VillageProfileController::class, 'updateBasicStat'])->name('statistik.basic.update');
            Route::delete('statistik/basic/{index}', [\App\Http\Controllers\Admin\VillageProfileController::class, 'destroyBasicStat'])->name('statistik.basic.destroy');
            Route::patch('statistik/basic/{index}/toggle', [\App\Http\Controllers\Admin\VillageProfileController::class, 'toggleBasicStat'])->name('statistik.basic.toggle');
            
            // Generic Stats Routes
            Route::post('statistik/{type}/store', [\App\Http\Controllers\Admin\VillageProfileController::class, 'storeStatistik'])->name('statistik.store');
            Route::put('statistik/{type}/{index}', [\App\Http\Controllers\Admin\VillageProfileController::class, 'updateStatistik'])->name('statistik.update');
            Route::delete('statistik/{type}/{index}', [\App\Http\Controllers\Admin\VillageProfileController::class, 'destroyStatistik'])->name('statistik.destroy');
            
            // New Detail Stats Routes
            Route::post('statistik/age-groups/update', [\App\Http\Controllers\Admin\VillageStatisticController::class, 'updateAgeGroups'])->name('statistik.age_groups.update');
            Route::post('statistik/educations/update', [\App\Http\Controllers\Admin\VillageStatisticController::class, 'updateEducations'])->name('statistik.educations.update');
            Route::post('statistik/occupations/update', [\App\Http\Controllers\Admin\VillageStatisticController::class, 'updateOccupations'])->name('statistik.occupations.update');
            Route::post('statistik/territory/update', [\App\Http\Controllers\Admin\VillageStatisticController::class, 'updateTerritory'])->name('statistik.territory.update');
        });
    });

    // ==========================================
    // 2. ADMIN ONLY PANEL ROUTES (Role: Admin)
    // ==========================================
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // CMS: Kategori Informasi (Berita, Pengumuman, Galeri)
        Route::prefix('kategori')->name('kategori.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('destroy');
        });

        // CMS: Kelola Header Navigasi Dinamis
        Route::resource('navigation', \App\Http\Controllers\Admin\NavigationMenuController::class)->except(['show', 'edit']);
        Route::get('navigation/page/{page}', [\App\Http\Controllers\Admin\NavigationMenuController::class, 'getPageContent'])->name('navigation.page.get');
        Route::put('navigation/page/{page}', [\App\Http\Controllers\Admin\NavigationMenuController::class, 'updatePageContent'])->name('navigation.page.update');

        // CMS: Dokumen Publik
        Route::resource('documents', App\Http\Controllers\Admin\DocumentController::class);
        Route::delete('documents/file/{id}', [App\Http\Controllers\Admin\DocumentController::class, 'destroyFile'])->name('documents.destroyFile');

        // CMS: Kelola Beranda (Section-based) - Other than Statistik
        Route::prefix('kelola-beranda')->name('beranda.')->group(function () {
            Route::get('identitas-sambutan', [\App\Http\Controllers\Admin\VillageProfileController::class, 'identitasSambutan'])->name('identitas_sambutan');
            Route::get('sotk', [\App\Http\Controllers\Admin\VillageProfileController::class, 'sotk'])->name('sotk');
            Route::get('visi-misi-sejarah', [\App\Http\Controllers\Admin\VillageProfileController::class, 'visiMisiSejarah'])->name('visi_misi_sejarah');
            Route::get('banner', [\App\Http\Controllers\Admin\VillageProfileController::class, 'banner'])->name('banner');
            Route::get('transparansi', [\App\Http\Controllers\Admin\VillageProfileController::class, 'transparansi'])->name('transparansi');
            Route::get('kontak', [\App\Http\Controllers\Admin\VillageProfileController::class, 'kontak'])->name('kontak');
            Route::get('footer', [\App\Http\Controllers\Admin\VillageProfileController::class, 'footer'])->name('footer');
            Route::post('update', [\App\Http\Controllers\Admin\VillageProfileController::class, 'update'])->name('update');
            
            // Multi-Year APBD endpoints for Modal UI
            Route::post('apbd/year/store', [\App\Http\Controllers\Admin\VillageProfileController::class, 'storeApbdYear'])->name('apbd.year.store');
            Route::post('apbd/year/{yearIndex}/update', [\App\Http\Controllers\Admin\VillageProfileController::class, 'updateApbdYear'])->name('apbd.year.update');
            Route::delete('apbd/year/{yearIndex}', [\App\Http\Controllers\Admin\VillageProfileController::class, 'destroyApbdYear'])->name('apbd.year.destroy');
            Route::post('apbd/{yearIndex}/store', [\App\Http\Controllers\Admin\VillageProfileController::class, 'storeApbd'])->name('apbd.store');
            Route::put('apbd/{yearIndex}/{index}', [\App\Http\Controllers\Admin\VillageProfileController::class, 'updateApbd'])->name('apbd.update');
            Route::delete('apbd/{yearIndex}/{index}', [\App\Http\Controllers\Admin\VillageProfileController::class, 'destroyApbd'])->name('apbd.destroy');
            
            // Incomes endpoints
            Route::post('apbd-income/{yearIndex}/store', [\App\Http\Controllers\Admin\VillageProfileController::class, 'storeApbdIncome'])->name('apbd.income.store');
            Route::put('apbd-income/{yearIndex}/{index}', [\App\Http\Controllers\Admin\VillageProfileController::class, 'updateApbdIncome'])->name('apbd.income.update');
            Route::delete('apbd-income/{yearIndex}/{index}', [\App\Http\Controllers\Admin\VillageProfileController::class, 'destroyApbdIncome'])->name('apbd.income.destroy');
            
            // Financing endpoints
            Route::post('apbd-financing/{yearIndex}/store', [\App\Http\Controllers\Admin\VillageProfileController::class, 'storeApbdFinancing'])->name('apbd.financing.store');
            Route::put('apbd-financing/{yearIndex}/{index}', [\App\Http\Controllers\Admin\VillageProfileController::class, 'updateApbdFinancing'])->name('apbd.financing.update');
            Route::delete('apbd-financing/{yearIndex}/{index}', [\App\Http\Controllers\Admin\VillageProfileController::class, 'destroyApbdFinancing'])->name('apbd.financing.destroy');
            
            // Categories endpoints
            Route::post('apbd-category/{type}/store', [\App\Http\Controllers\Admin\VillageProfileController::class, 'storeApbdCategory'])->name('apbd.category.store');
            Route::delete('apbd-category/{type}/destroy', [\App\Http\Controllers\Admin\VillageProfileController::class, 'destroyApbdCategory'])->name('apbd.category.destroy');
            
            Route::get('maklumat', [\App\Http\Controllers\Admin\KemitraanMaklumatController::class, 'maklumat'])->name('maklumat');
            Route::get('kemitraan', [\App\Http\Controllers\Admin\KemitraanMaklumatController::class, 'kemitraan'])->name('kemitraan');
            Route::post('update-kemitraan', [\App\Http\Controllers\Admin\KemitraanMaklumatController::class, 'update'])->name('update-kemitraan');

            // Lembaga Kemasyarakatan
            Route::get('lembaga', [\App\Http\Controllers\Admin\LembagaController::class, 'index'])->name('lembaga');
            Route::post('lembaga/store', [\App\Http\Controllers\Admin\LembagaController::class, 'store'])->name('lembaga.store');
            Route::put('lembaga/{id}', [\App\Http\Controllers\Admin\LembagaController::class, 'update'])->name('lembaga.update');
            Route::delete('lembaga/{id}', [\App\Http\Controllers\Admin\LembagaController::class, 'destroy'])->name('lembaga.destroy');


            // Single-item Kemitraan endpoints for Modal UI
            Route::post('kemitraan/store', [\App\Http\Controllers\Admin\KemitraanMaklumatController::class, 'storeMitra'])->name('kemitraan.store');
            Route::put('kemitraan/{index}', [\App\Http\Controllers\Admin\KemitraanMaklumatController::class, 'updateMitra'])->name('kemitraan.update');
            Route::delete('kemitraan/{index}', [\App\Http\Controllers\Admin\KemitraanMaklumatController::class, 'destroyMitra'])->name('kemitraan.destroy');
        });

        // Pelayanan: Master Layanan & Jenis Surat
        Route::prefix('jenis-layanan')->name('jenis-layanan.')->group(function () {
            Route::get('/', [AdminServiceTypeController::class, 'index'])->name('index');
            Route::post('/', [AdminServiceTypeController::class, 'store'])->name('store');
            Route::put('/{id}', [AdminServiceTypeController::class, 'update'])->name('update');
            Route::patch('/{id}/toggle', [AdminServiceTypeController::class, 'toggleStatus'])->name('toggle');
            Route::patch('/{id}/toggle-homepage', [AdminServiceTypeController::class, 'toggleHomepage'])->name('toggle-homepage');
            Route::delete('/{id}', [AdminServiceTypeController::class, 'destroy'])->name('destroy');
        });

        // Redirect legacy layanan-publik route
        Route::get('/layanan-publik', fn() => redirect()->route('admin.jenis-layanan.index'))->name('layanan-publik.index');

        // Pengguna: Operator & Hak Akses
        Route::prefix('operator')->name('operator.')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::post('/', [AdminUserController::class, 'store'])->name('store');
            Route::put('/{id}', [AdminUserController::class, 'update'])->name('update');
            Route::patch('/{id}/toggle', [AdminUserController::class, 'toggleStatus'])->name('toggle');
            Route::post('/{id}/reset-password', [AdminUserController::class, 'resetPassword'])->name('reset-password');
            Route::delete('/{id}', [AdminUserController::class, 'destroy'])->name('destroy');
        });

        // Pengaturan Identitas & Konfigurasi Sistem
        Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
            Route::get('/', [AdminSettingController::class, 'index'])->name('index');
            Route::post('/', [AdminSettingController::class, 'update'])->name('update');
        });

        // Log Aktivitas Sistem
        Route::prefix('log-aktivitas')->name('activity-log.')->group(function () {
            Route::get('/', [AdminActivityLogController::class, 'index'])->name('index');
            Route::delete('/clear', [AdminActivityLogController::class, 'clear'])->name('clear');
        });
    });
});