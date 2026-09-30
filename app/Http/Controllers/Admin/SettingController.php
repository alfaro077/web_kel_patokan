<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    protected string $configPath;

    public function __construct()
    {
        $this->configPath = storage_path('app/system_settings.json');
    }

    /**
     * Ambil data pengaturan sistem.
     */
    public static function getSettings(): array
    {
        $defaults = [
            'app_name' => 'SIMPEL KELURAHAN',
            'app_subtitle' => 'Sistem Informasi Manajemen Pelayanan Kelurahan Patokan',
            'maintenance_mode' => false,
            'max_upload_mb' => 3, // Legacy
            'max_upload_foto_mb' => 2,
            'max_upload_pdf_mb' => 5,
            'items_per_page' => 10,
            'app_logo' => null,
            'login_background' => null,
        ];

        $path = storage_path('app/system_settings.json');
        if (File::exists($path)) {
            $data = json_decode(File::get($path), true);
            if (is_array($data)) {
                return array_merge($defaults, $data);
            }
        }

        return $defaults;
    }

    /**
     * Tampilkan halaman pengaturan sistem.
     */
    public function index()
    {
        $settings = self::getSettings();
        return view('admin.pengaturan.index', compact('settings'));
    }

    /**
     * Perbarui pengaturan sistem.
     */
    public function update(Request $request)
    {
        // For self-referencing max upload limit, we use fallback values to prevent infinite loop or undefined errors
        $currentSettings = self::getSettings();
        $fotoMax = (isset($currentSettings['max_upload_foto_mb']) ? $currentSettings['max_upload_foto_mb'] : 2) * 1024;

        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_subtitle' => 'nullable|string|max:500',
            'maintenance_mode' => 'boolean',
            'max_upload_foto_mb' => 'required|integer|min:1|max:20',
            'max_upload_pdf_mb' => 'required|integer|min:1|max:20',
            'app_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:' . $fotoMax,
            'login_background' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:' . $fotoMax,
        ], [
            'app_name.required' => 'Nama aplikasi wajib diisi.',
            'max_upload_foto_mb.required' => 'Batas upload foto wajib diisi.',
            'max_upload_pdf_mb.required' => 'Batas upload PDF wajib diisi.',
        ]);

        $settings = self::getSettings();

        $settings['app_name'] = $request->input('app_name');
        $settings['app_subtitle'] = $request->input('app_subtitle');
        $settings['maintenance_mode'] = $request->boolean('maintenance_mode');
        if ($request->has('max_upload_foto_mb')) {
            $settings['max_upload_foto_mb'] = (int) $request->input('max_upload_foto_mb');
        }
        if ($request->has('max_upload_pdf_mb')) {
            $settings['max_upload_pdf_mb'] = (int) $request->input('max_upload_pdf_mb');
        }

        if ($request->hasFile('app_logo')) {
            if (!empty($settings['app_logo']) && Storage::disk('public')->exists($settings['app_logo'])) {
                Storage::disk('public')->delete($settings['app_logo']);
            }
            $settings['app_logo'] = $request->file('app_logo')->store('settings', 'public');
            
            // Sync to public/favicon.ico for default browser requests
            try {
                @copy(storage_path('app/public/' . $settings['app_logo']), public_path('favicon.ico'));
            } catch (\Throwable $e) {}
        }

        if ($request->hasFile('login_background')) {
            if (!empty($settings['login_background']) && Storage::disk('public')->exists($settings['login_background'])) {
                Storage::disk('public')->delete($settings['login_background']);
            }
            $settings['login_background'] = $request->file('login_background')->store('settings', 'public');
        }

        File::put($this->configPath, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return back()->with('status', 'Pengaturan sistem berhasil diperbarui.');
    }
}
