<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class VillageStatisticController extends Controller
{
    protected string $configPath;

    public function __construct()
    {
        $this->configPath = storage_path('app/village_profile.json');
    }

    protected function getProfileData(): array
    {
        if (File::exists($this->configPath)) {
            $data = json_decode(File::get($this->configPath), true);
            return is_array($data) ? $data : [];
        }
        return [];
    }

    protected function saveProfileData(array $data): void
    {
        File::put($this->configPath, json_encode($data, JSON_PRETTY_PRINT));
    }

    protected function getTotalPenduduk(): float
    {
        $data = $this->getProfileData();
        $male = (float) str_replace(['.', ','], ['', '.'], $data['demographics']['male'] ?? '0');
        $female = (float) str_replace(['.', ','], ['', '.'], $data['demographics']['female'] ?? '0');
        return $male + $female;
    }

    public function updateAgeGroups(Request $request)
    {
        $totalPenduduk = $this->getTotalPenduduk();
        
        $request->validate([
            'age_groups' => 'required|array',
            'age_groups.*.label' => 'required|string',
            'age_groups.*.count' => 'required|numeric|min:0',
        ]);

        $sum = collect($request->age_groups)->sum('count');
        if ($sum > $totalPenduduk) {
            return response()->json([
                'success' => false,
                'message' => 'Total jiwa kelompok umur melebihi total penduduk kelurahan.',
                'errors' => ['age_groups' => ['Total jiwa kelompok umur ('.$sum.') melebihi total penduduk ('.$totalPenduduk.').']]
            ], 422);
        }

        $data = $this->getProfileData();
        $data['age_groups'] = $request->age_groups;
        $this->saveProfileData($data);

        return response()->json([
            'success' => true,
            'message' => 'Data kelompok usia berhasil diperbarui.'
        ]);
    }

    public function updateEducations(Request $request)
    {
        $totalPenduduk = $this->getTotalPenduduk();
        
        $request->validate([
            'educations' => 'required|array',
            'educations.*.label' => 'required|string',
            'educations.*.count' => 'required|numeric|min:0',
        ]);

        $sum = collect($request->educations)->sum('count');
        if ($sum > $totalPenduduk) {
            return response()->json([
                'success' => false,
                'message' => 'Total jiwa tingkat pendidikan melebihi total penduduk kelurahan.',
                'errors' => ['educations' => ['Total jiwa tingkat pendidikan ('.$sum.') melebihi total penduduk ('.$totalPenduduk.').']]
            ], 422);
        }

        $data = $this->getProfileData();
        $data['educations'] = $request->educations;
        $this->saveProfileData($data);

        return response()->json([
            'success' => true,
            'message' => 'Data tingkat pendidikan berhasil diperbarui.'
        ]);
    }

    public function updateOccupations(Request $request)
    {
        $totalPenduduk = $this->getTotalPenduduk();
        
        $request->validate([
            'occupations' => 'required|array',
            'occupations.*.label' => 'required|string',
            'occupations.*.count' => 'required|numeric|min:0',
        ]);

        $sum = collect($request->occupations)->sum('count');
        if ($sum > $totalPenduduk) {
            return response()->json([
                'success' => false,
                'message' => 'Total jiwa jenis pekerjaan melebihi total penduduk kelurahan.',
                'errors' => ['occupations' => ['Total jiwa jenis pekerjaan ('.$sum.') melebihi total penduduk ('.$totalPenduduk.').']]
            ], 422);
        }

        $data = $this->getProfileData();
        $data['occupations'] = $request->occupations;
        $this->saveProfileData($data);

        return response()->json([
            'success' => true,
            'message' => 'Data jenis pekerjaan berhasil diperbarui.'
        ]);
    }

    public function updateTerritory(Request $request)
    {
        $request->validate([
            'territory' => 'required|array',
            'territory.rw' => 'required|numeric|min:0',
            'territory.rt' => 'required|numeric|min:0',
            'territory.north' => 'nullable|string',
            'territory.east' => 'nullable|string',
            'territory.south' => 'nullable|string',
            'territory.west' => 'nullable|string',
            'territory.schools' => 'required|numeric|min:0',
            'territory.mosques' => 'required|numeric|min:0',
            'territory.health' => 'required|numeric|min:0',
            'territory.markets' => 'required|numeric|min:0',
        ]);

        $data = $this->getProfileData();
        $data['territory'] = $request->territory;
        
        // Auto-sync RT / RW stat cards
        if (isset($data['stats']) && is_array($data['stats'])) {
            foreach ($data['stats'] as &$stat) {
                if (stripos($stat['title'], 'rt') !== false || stripos($stat['title'], 'rw') !== false) {
                    $stat['value'] = $request->territory['rt'] . ' / ' . str_pad($request->territory['rw'], 2, '0', STR_PAD_LEFT);
                }
            }
        }
        
        $this->saveProfileData($data);

        return response()->json([
            'success' => true,
            'message' => 'Data distribusi wilayah & fasilitas berhasil diperbarui.'
        ]);
    }
}
