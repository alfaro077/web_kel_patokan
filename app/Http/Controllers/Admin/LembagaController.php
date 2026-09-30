<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Admin\VillageProfileController;

class LembagaController extends Controller
{
    public function index()
    {
        $profile = VillageProfileController::getProfileData();
        return view('admin.beranda.lembaga', compact('profile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:255',
            'ketua' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:' . (\App\Http\Controllers\Admin\SettingController::getSettings()['max_upload_foto_mb'] * 1024),
        ]);

        $profile = VillageProfileController::getProfileData();
        $lembagas = $profile['lembaga'] ?? [];

        // Validasi nama tidak boleh sama (case insensitive)
        foreach ($lembagas as $l) {
            if (strtolower($l['name']) === strtolower($request->name)) {
                return back()->withErrors(['name' => 'Nama lembaga sudah ada. Silakan gunakan nama lain.'])->withInput();
            }
        }

        $new['id'] = uniqid();
        $new['name'] = $request->name;
        $new['singkatan'] = $request->singkatan;
        $new['ketua'] = $request->ketua;
        $new['description'] = $request->description;
        $new['logo'] = '';

        if ($request->hasFile('logo')) {
            $new['logo'] = $request->file('logo')->store('lembaga', 'public');
        }

        $lembagas[] = $new;
        $profile['lembaga'] = $lembagas;
        
        File::put(storage_path('app/village_profile.json'), json_encode($profile, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Lembaga Kemasyarakatan berhasil ditambahkan.']);
        }
        return back()->with('success', 'Lembaga Kemasyarakatan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:255',
            'ketua' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:' . (\App\Http\Controllers\Admin\SettingController::getSettings()['max_upload_foto_mb'] * 1024),
        ]);

        $profile = VillageProfileController::getProfileData();
        $lembagas = $profile['lembaga'] ?? [];

        // Validasi nama tidak boleh sama (kecuali diri sendiri)
        foreach ($lembagas as $index => $l) {
            if ($l['id'] !== $id && strtolower($l['name']) === strtolower($request->name)) {
                return back()->withErrors(['name' => 'Nama lembaga sudah digunakan oleh lembaga lain.'])->withInput();
            }
        }

        foreach ($lembagas as $index => $l) {
            if ($l['id'] === $id) {
                $lembagas[$index]['name'] = $request->name;
                $lembagas[$index]['singkatan'] = $request->singkatan;
                $lembagas[$index]['ketua'] = $request->ketua;
                $lembagas[$index]['description'] = $request->description;

                if ($request->hasFile('logo')) {
                    if (!empty($l['logo']) && Storage::disk('public')->exists($l['logo'])) {
                        Storage::disk('public')->delete($l['logo']);
                    }
                    $lembagas[$index]['logo'] = $request->file('logo')->store('lembaga', 'public');
                }
                break;
            }
        }

        $profile['lembaga'] = $lembagas;
        File::put(storage_path('app/village_profile.json'), json_encode($profile, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Data Lembaga berhasil diperbarui.']);
        }
        return back()->with('success', 'Data Lembaga berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $profile = VillageProfileController::getProfileData();
        $lembagas = $profile['lembaga'] ?? [];

        foreach ($lembagas as $index => $l) {
            if ($l['id'] === $id) {
                if (!empty($l['logo']) && Storage::disk('public')->exists($l['logo'])) {
                    Storage::disk('public')->delete($l['logo']);
                }
                unset($lembagas[$index]);
                break;
            }
        }

        $profile['lembaga'] = array_values($lembagas);
        File::put(storage_path('app/village_profile.json'), json_encode($profile, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return back()->with('success', 'Lembaga Kemasyarakatan berhasil dihapus.');
    }
}
