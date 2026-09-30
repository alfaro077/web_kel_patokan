<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(\Illuminate\Routing\UrlGenerator $url): void
    {
        // Force HTTPS if using ngrok or in production
        if (str_contains(config('app.url'), 'https://')) {
            $url->forceScheme('https');
        }

        // Removed pending count global variable since letter request is deprecated

        // Share village profile and system settings globally for headers, footers, and admin sidebars
        View::composer('*', function ($view) {
            // We use * because it's needed in both public app layout and admin layout
            $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();
            $view->with('villageProfile', $villageProfile);

            // Load System Settings
            $systemSettings = \App\Http\Controllers\Admin\SettingController::getSettings();
            $view->with('systemSettings', $systemSettings);
        });

        // Share service types globally untuk navbar layouts.app
        View::composer('layouts.app', function ($view) {
            if (\Illuminate\Support\Facades\Schema::hasTable('navigation_menus')) {
                $menus = \App\Models\NavigationMenu::where('is_active', true)
                    ->orderBy('order')
                    ->get()
                    ->groupBy('section');
                
                $view->with('navProfil', $menus->get('profil', collect()));
                $view->with('navLayanan', $menus->get('layanan', collect()));
                $view->with('navDokumen', $menus->get('dokumen', collect()));
            } else {
                // Fallback before migration is run
                $view->with('navProfil', collect());
                $view->with('navLayanan', collect());
                $view->with('navDokumen', collect());
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('announcements')) {
                $view->with('globalAnnouncements', \App\Models\Announcement::where('is_active', true)->latest()->get());
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('pages')) {
                $view->with('profilePages', \App\Models\Page::where('category', 'profile')->where('is_active', true)->orderBy('order')->get());
            } else {
                $view->with('profilePages', collect());
            }
        });
    }
}
