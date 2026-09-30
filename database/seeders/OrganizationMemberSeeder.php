<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrganizationMember;

class OrganizationMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Root (Kepala Kelurahan)
        $lurah = OrganizationMember::create([
            'name' => 'Drs. H. Ahmad Sudirman, M.Si',
            'position' => 'Kepala Kelurahan Patokan',
            'nip' => '19700101 199503 1 001',
            'order' => 1,
        ]);

        // 2. Sekretaris Kelurahan
        $sekretaris = OrganizationMember::create([
            'parent_id' => $lurah->id,
            'name' => 'Budi Santoso, S.STP',
            'position' => 'Sekretaris Kelurahan',
            'nip' => '19820412 200501 1 003',
            'order' => 1,
        ]);

        // 3. Kasi-Kasi (Level 2, di bawah Lurah sejajar Sekkel atau di bawah Sekkel? Biasanya di bawah Lurah)
        $kasiPemerintahan = OrganizationMember::create([
            'parent_id' => $lurah->id,
            'name' => 'Siti Aminah, S.Sos',
            'position' => 'Kasi Pemerintahan',
            'nip' => '19750822 199903 2 004',
            'order' => 2,
        ]);

        $kasiKesmas = OrganizationMember::create([
            'parent_id' => $lurah->id,
            'name' => 'H. Zainal Arifin, SE',
            'position' => 'Kasi Kesmas',
            'nip' => '19721115 199803 1 002',
            'order' => 3,
        ]);

        $kasiTrantib = OrganizationMember::create([
            'parent_id' => $lurah->id,
            'name' => 'M. Fauzi',
            'position' => 'Kasi Trantib',
            'nip' => '19800510 200212 1 005',
            'order' => 4,
        ]);

        // 4. Staf-Staf (Level 3)
        // Staf di bawah Sekretaris
        OrganizationMember::create([
            'parent_id' => $sekretaris->id,
            'name' => 'Dewi Lestari, A.Md',
            'position' => 'Staf Administrasi',
            'nip' => '19900215 201201 2 006',
            'order' => 1,
        ]);

        // Staf di bawah Kasi Pemerintahan
        OrganizationMember::create([
            'parent_id' => $kasiPemerintahan->id,
            'name' => 'Agus Setiawan',
            'position' => 'Staf Pemerintahan',
            'nip' => '19880707 201004 1 008',
            'order' => 1,
        ]);

        // Staf di bawah Kasi Kesmas
        OrganizationMember::create([
            'parent_id' => $kasiKesmas->id,
            'name' => 'Rini Yulianti',
            'position' => 'Staf Kesmas',
            'nip' => '19920912 201503 2 009',
            'order' => 1,
        ]);
    }
}
