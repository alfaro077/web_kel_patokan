<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\OrganizationMember;

class UpdateOrganizationMemberRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'tupoksi' => 'nullable|string',
            'nip' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'delete_photo' => 'nullable|boolean',
            'parent_id' => 'nullable|exists:organization_members,id',
            'order' => 'nullable|integer',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $member = $this->route('struktur_organisasi');
            $memberId = $member instanceof OrganizationMember ? $member->id : $member;
            
            // Cannot be parent of itself
            if ($this->parent_id == $memberId) {
                $validator->errors()->add('parent_id', 'Anggota tidak bisa menjadi atasan untuk dirinya sendiri.');
            }

            // Check if trying to set as root, and another root exists
            if (empty($this->parent_id)) {
                $rootExists = OrganizationMember::whereNull('parent_id')
                    ->where('id', '!=', $memberId)
                    ->exists();
                if ($rootExists) {
                    $validator->errors()->add('parent_id', 'Ketua Kelurahan (Struktur paling atas) sudah ada.');
                }
            }
        });
    }
}
