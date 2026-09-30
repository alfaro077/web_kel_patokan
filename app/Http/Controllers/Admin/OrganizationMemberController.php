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

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('struktur', 'public');
        }

        OrganizationMember::create($data);

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

        if ($request->hasFile('photo')) {
            if ($struktur_organisasi->photo) {
                Storage::disk('public')->delete($struktur_organisasi->photo);
            }
            $data['photo'] = $request->file('photo')->store('struktur', 'public');
        }

        $struktur_organisasi->update($data);

        return redirect()->route('admin.struktur_organisasi.index')->with('success', 'Anggota struktur berhasil diperbarui.');
    }

    public function destroy(OrganizationMember $struktur_organisasi)
    {
        // Update children's parent_id to null or handle cascading, but let's just null it as defined in migration
        if ($struktur_organisasi->photo) {
            Storage::disk('public')->delete($struktur_organisasi->photo);
        }
        
        $struktur_organisasi->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Anggota struktur berhasil dihapus.']);
        }

        return redirect()->route('admin.struktur_organisasi.index')->with('success', 'Anggota struktur berhasil dihapus.');
    }
}
