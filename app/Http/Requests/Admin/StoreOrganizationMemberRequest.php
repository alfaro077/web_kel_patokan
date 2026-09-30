<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\OrganizationMember;

class StoreOrganizationMemberRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Assume admin is authorized
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'tupoksi' => 'nullable|string',
            'nip' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'parent_id' => 'nullable|exists:organization_members,id',
            'order' => 'nullable|integer',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if trying to add a root member (parent_id is null)
            if (empty($this->parent_id)) {
                $rootExists = OrganizationMember::whereNull('parent_id')->exists();
                if ($rootExists) {
                    $validator->errors()->add('parent_id', 'Ketua Kelurahan (Struktur paling atas) sudah ada dan tidak bisa ditambahkan lagi.');
                }
            }
        });
    }
}
