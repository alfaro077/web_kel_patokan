<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavigationMenu;
use Illuminate\Http\Request;

class NavigationMenuController extends Controller
{
    public function index()
    {
        $menus = NavigationMenu::orderBy('section')
            ->orderBy('order')
            ->get()
            ->groupBy('section');

        $pages = \App\Models\Page::all();

        return view('admin.navigation.index', compact('menus', 'pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'section' => 'required|in:profil,layanan,dokumen',
            'title' => 'required|string|max:255',
            'url' => in_array($request->section, ['dokumen', 'profil', 'layanan']) ? 'nullable|string' : 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        $url = $request->url;

        if ($request->section === 'dokumen') {
            $document = \App\Models\Document::create([
                'name' => $request->title,
                'is_active' => $request->has('is_active'),
            ]);
            $url = '/dokumen?id=' . $document->id;
        } elseif ($request->section === 'profil') {
            if ($request->filled('url') && $request->url !== 'new') {
                $url = $request->url;
            } else {
                $pageData = [
                    'title' => $request->input('page_title', $request->title),
                    'subtitle' => $request->input('page_subtitle'),
                    'content' => $request->input('page_content'),
                    'is_active' => $request->has('is_active'),
                ];
                
                if ($request->hasFile('page_banner')) {
                    $pageData['banner_image'] = $request->file('page_banner')->store('pages/banners', 'public');
                }

                $page = \App\Models\Page::create($pageData);
                $url = '/halaman/' . $page->slug;
            }
        } elseif ($request->section === 'layanan') {
            $service = \App\Models\ServiceType::create([
                'name' => $request->title,
                'slug' => \Illuminate\Support\Str::slug($request->title),
                'is_active' => $request->has('is_active'),
            ]);
            $url = '/standar-pelayanan?id=' . $service->id;
        }

        NavigationMenu::create([
            'section' => $request->section,
            'title' => $request->title,
            'url' => $url,
            'order' => $request->order,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Menu navigasi berhasil ditambahkan.');
    }

    public function update(Request $request, NavigationMenu $navigation)
    {
        $request->validate([
            'section' => 'required|in:profil,layanan,dokumen',
            'title' => 'required|string|max:255',
            'url' => in_array($request->section, ['dokumen', 'profil', 'layanan']) ? 'nullable|string' : 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        $oldTitle = $navigation->title;

        $navigation->update([
            'section' => $request->section,
            'title' => $request->title,
            'url' => in_array($request->section, ['dokumen', 'profil', 'layanan']) ? $navigation->url : $request->url,
            'order' => $request->order,
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->section === 'dokumen' && $oldTitle !== $request->title) {
            if (preg_match('/id=(\d+)/', $navigation->url, $matches)) {
                $doc = \App\Models\Document::find($matches[1]);
                if ($doc) {
                    $doc->update([
                        'name' => $request->title,
                        'is_active' => $request->has('is_active'),
                    ]);
                }
            }
        } elseif ($request->section === 'profil' && $oldTitle !== $request->title) {
            if (preg_match('/^\/halaman\/(.+)$/', $navigation->url, $matches)) {
                $page = \App\Models\Page::where('slug', $matches[1])->first();
                if ($page) {
                    $page->update([
                        'title' => $request->title,
                        'is_active' => $request->has('is_active'),
                    ]);
                    $navigation->update(['url' => '/halaman/' . $page->slug]);
                }
            }
        } elseif ($request->section === 'layanan' && $oldTitle !== $request->title) {
            if (preg_match('/id=(\d+)/', $navigation->url, $matches)) {
                $service = \App\Models\ServiceType::find($matches[1]);
                if ($service) {
                    $service->update([
                        'name' => $request->title,
                        'is_active' => $request->has('is_active'),
                    ]);
                }
            }
        }

        return back()->with('success', 'Menu navigasi berhasil diperbarui.');
    }

    public function destroy(NavigationMenu $navigation)
    {
        $protectedUrls = ['/visi-misi', '/sejarah', '/struktur-organisasi', '/lembaga'];
        if (in_array($navigation->url, $protectedUrls)) {
            return back()->with('error', 'Menu utama profil sistem tidak dapat dihapus.');
        }

        if ($navigation->section === 'dokumen') {
            if (preg_match('/id=(\d+)/', $navigation->url, $matches)) {
                $doc = \App\Models\Document::find($matches[1]);
                if ($doc) {
                    $doc->delete();
                }
            }
        } elseif ($navigation->section === 'profil') {
            if (preg_match('/^\/halaman\/(.+)$/', $navigation->url, $matches)) {
                $page = \App\Models\Page::where('slug', $matches[1])->first();
                if ($page) {
                    $page->delete();
                }
            }
        } elseif ($navigation->section === 'layanan') {
            if (preg_match('/id=(\d+)/', $navigation->url, $matches)) {
                $service = \App\Models\ServiceType::find($matches[1]);
                if ($service) {
                    $service->delete();
                }
            }
        }
        $navigation->delete();
        return back()->with('success', 'Menu navigasi berhasil dihapus.');
    }

    public function getPageContent(\App\Models\Page $page)
    {
        return response()->json([
            'title' => $page->title,
            'subtitle' => $page->subtitle,
            'slug' => $page->slug,
            'banner_image' => $page->banner_image ? asset('storage/' . $page->banner_image) : '',
            'content' => $page->content,
        ]);
    }

    public function updatePageContent(Request $request, \App\Models\Page $page)
    {
        $request->validate([
            'page_title' => 'required|string|max:255',
            'page_subtitle' => 'nullable|string',
            'page_content' => 'nullable|string',
            'page_banner' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $request->page_title,
            'subtitle' => $request->page_subtitle,
            'content' => $request->page_content,
        ];

        if ($request->hasFile('page_banner')) {
            if ($page->banner_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($page->banner_image);
            }
            $data['banner_image'] = $request->file('page_banner')->store('pages', 'public');
        }

        $page->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Konten halaman berhasil diperbarui.']);
        }
        return back()->with('success', 'Konten halaman berhasil diperbarui.');
    }
}
