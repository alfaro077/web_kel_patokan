<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationMember;
use App\Http\Requests\Admin\StoreOrganizationMemberRequest;
use App\Http\Requests\Admin\UpdateOrganizationMemberRequest;
use Illuminate\Support\Facades\Storage;

class OrganizationMemberController extends Controller
{
    public function index()
    {
        $rootMembers = OrganizationMember::whereNull('parent_id')
            ->with('childrenRecursive')
            ->orderBy('order')
            ->get();
            
        $members = OrganizationMember::with('parent')->orderBy('order')->get(); // Keep this if needed
        return view('admin.struktur_organisasi.index', compact('members', 'rootMembers'));
    }

    public function create()
    {
        $members = OrganizationMember::all();
        return view('admin.struktur_organisasi.create', compact('members'));
    }

    public function store(StoreOrganizationMemberRequest $request)
    {
        $data = $request->validated();
        
        if (!isset($data['order']) || $data['order'] === null) {
            $data['order'] = OrganizationMember::where('parent_id', $data['parent_id'] ?? null)->max('order') + 1;
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('struktur', 'public');
        }

        OrganizationMember::create($data);
        $this->syncVillageProfileHead();

        return redirect()->route('admin.struktur_organisasi.index')->with('success', 'Anggota struktur berhasil ditambahkan.');
    }

    public function edit(OrganizationMember $struktur_organisasi)
    {
        $members = OrganizationMember::where('id', '!=', $struktur_organisasi->id)->get();
        return view('admin.struktur_organisasi.edit', compact('struktur_organisasi', 'members'));
    }

    public function update(UpdateOrganizationMemberRequest $request, OrganizationMember $struktur_organisasi)
    {
        $data = $request->validated();

        if ($request->boolean('delete_photo')) {
            if ($struktur_organisasi->photo) {
                Storage::disk('public')->delete($struktur_organisasi->photo);
            }
            $data['photo'] = null;
        } elseif ($request->hasFile('photo')) {
            if ($struktur_organisasi->photo) {
                Storage::disk('public')->delete($struktur_organisasi->photo);
            }
            $data['photo'] = $request->file('photo')->store('struktur', 'public');
        }

        // Reorder Logic
        $oldParentId = $struktur_organisasi->parent_id;
        $newParentId = $data['parent_id'] ?? null;
        $oldOrder = (int)$struktur_organisasi->order;
        $newOrder = isset($data['order']) ? (int)$data['order'] : $oldOrder;

        if ($oldParentId === $newParentId) {
            if ($oldOrder !== $newOrder) {
                if ($newOrder > $oldOrder) {
                    OrganizationMember::where('parent_id', $oldParentId)
                        ->whereBetween('order', [$oldOrder + 1, $newOrder])
                        ->where('id', '!=', $struktur_organisasi->id)
                        ->decrement('order');
                } else {
                    OrganizationMember::where('parent_id', $oldParentId)
                        ->whereBetween('order', [$newOrder, $oldOrder - 1])
                        ->where('id', '!=', $struktur_organisasi->id)
                        ->increment('order');
                }
            }
        } else {
            // Remove from old parent sequence
            OrganizationMember::where('parent_id', $oldParentId)
                ->where('order', '>', $oldOrder)
                ->decrement('order');
                
            if (!isset($data['order'])) {
                $newOrder = OrganizationMember::where('parent_id', $newParentId)->max('order') + 1;
                $data['order'] = $newOrder;
            } else {
                // Insert into new parent sequence
                OrganizationMember::where('parent_id', $newParentId)
                    ->where('order', '>=', $newOrder)
                    ->increment('order');
            }
        }

        $struktur_organisasi->update($data);

        $this->syncVillageProfileHead();

        return redirect()->route('admin.struktur_organisasi.index')->with('success', 'Anggota struktur berhasil diperbarui.');
    }

    public function destroy(OrganizationMember $struktur_organisasi)
    {
        // Reorder siblings after deletion
        $parentId = $struktur_organisasi->parent_id;
        $order = $struktur_organisasi->order;

        if ($struktur_organisasi->photo) {
            Storage::disk('public')->delete($struktur_organisasi->photo);
        }
        
        $struktur_organisasi->delete();
        
        OrganizationMember::where('parent_id', $parentId)
            ->where('order', '>', $order)
            ->decrement('order');

        $this->syncVillageProfileHead();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Anggota struktur berhasil dihapus.']);
        }

        return redirect()->route('admin.struktur_organisasi.index')->with('success', 'Anggota struktur berhasil dihapus.');
    }

    private function syncVillageProfileHead()
    {
        $root = OrganizationMember::whereNull('parent_id')->first();
        $configPath = storage_path('app/village_profile.json');
        if (\Illuminate\Support\Facades\File::exists($configPath)) {
            $data = json_decode(\Illuminate\Support\Facades\File::get($configPath), true) ?: [];
            if ($root) {
                $data['head_name'] = $root->name;
                $data['head_nip'] = $root->nip;
                $data['head_photo'] = $root->photo;
            } else {
                $data['head_photo'] = null;
            }
            \Illuminate\Support\Facades\File::put($configPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    }
}
