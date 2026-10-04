<?php

namespace Database\Seeders;

use App\Models\NavigationMenu;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class MenuPageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan data lama
        NavigationMenu::whereIn('section', ['profil', 'layanan'])->delete();
        Page::whereIn('slug', ['sejarah-profil-kelurahan', 'visi-misi', 'lembaga-kemasyarakatan', 'struktur-organisasi'])->delete();

        // 2. Buat Halaman Dinamis (Disimpan ke tabel pages agar terbaca sebagai Halaman Dinamis di Admin Panel)
        $pages = [
            [
                'title' => 'Sejarah & Profil Kelurahan',
                'category' => 'profile',
                'type' => 'standard',
                'subtitle' => 'Mengenal lebih dekat Kelurahan Patokan',
                'badge_text' => 'Profil Singkat',
                'content' => '<p>Kelurahan Patokan adalah salah satu kelurahan yang terletak di wilayah strategis. Kami berkomitmen untuk memberikan pelayanan terbaik bagi warga dengan mengedepankan prinsip transparansi, akuntabilitas, dan inovasi.</p>',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Visi & Misi',
                'category' => 'profile',
                'type' => 'standard',
                'subtitle' => 'Arah dan tujuan pembangunan Kelurahan Patokan',
                'badge_text' => 'Visi Misi',
                'content' => '<h4>Visi</h4><p>Terwujudnya Kelurahan Patokan yang Maju, Sejahtera, dan Berbudaya melalui Pelayanan Publik yang Prima.</p><h4>Misi</h4><ul><li>Meningkatkan kualitas pelayanan administrasi kependudukan.</li><li>Mendorong partisipasi masyarakat dalam pembangunan.</li><li>Meningkatkan pemberdayaan ekonomi dan UMKM lokal.</li></ul>',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Lembaga Kemasyarakatan',
                'category' => 'profile',
                'type' => 'standard',
                'subtitle' => 'Daftar lembaga yang mendukung kinerja kelurahan',
                'badge_text' => 'Lembaga Desa',
                'content' => '<p>Kelurahan Patokan didukung oleh berbagai lembaga kemasyarakatan yang aktif, di antaranya adalah: LKM, Karang Taruna, PKK, dan Posyandu.</p>',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Struktur Organisasi',
                'category' => 'profile',
                'type' => 'standard',
                'subtitle' => 'Susunan pemerintahan Kelurahan Patokan',
                'badge_text' => 'Struktur',
                'content' => '<p>Struktur organisasi Kelurahan Patokan dipimpin oleh seorang Lurah, dibantu oleh Sekretaris Kelurahan dan beberapa Kepala Seksi (Kasi) yang membidangi urusan pemerintahan, pemberdayaan masyarakat, serta ketentraman dan ketertiban.</p>',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($pages as $pageData) {
            $pageData['slug'] = Str::slug($pageData['title']);
            Page::create($pageData);
        }

        // 3. Buat Navigation Menus dengan prefix URL '/halaman/...' agar dikenali sebagai Halaman Dinamis oleh sistem
        $menus = [
            // Menu Profil (Dropdown)
            ['section' => 'profil', 'title' => 'Profil Kelurahan', 'url' => '/halaman/sejarah-profil-kelurahan', 'order' => 1],
            ['section' => 'profil', 'title' => 'Visi & Misi', 'url' => '/halaman/visi-misi', 'order' => 2],
            ['section' => 'profil', 'title' => 'Lembaga Desa', 'url' => '/halaman/lembaga-kemasyarakatan', 'order' => 3],
            ['section' => 'profil', 'title' => 'Struktur Organisasi', 'url' => '/halaman/struktur-organisasi', 'order' => 4],
            
            // Menu Layanan
            ['section' => 'layanan', 'title' => 'Standar Pelayanan', 'url' => '/standar-pelayanan', 'order' => 1],
        ];

        foreach ($menus as $menu) {
            $menu['is_active'] = true;
            NavigationMenu::create($menu);
        }
    }
}
