<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the village portal homepage.
     */
    public function lembaga()
    {
        $profile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();
        return view('lembaga', compact('profile'));
    }

    public function index()
    {
        $announcements = Announcement::where('is_active', true)
            ->latest()
            ->get();

        $sliderPosts = Post::with('category')
            ->where('is_slider', true)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->take(5)
            ->get();

        $services = \App\Models\ServiceType::where('is_active', true)
            ->where('show_on_homepage', true)
            ->orderBy('order', 'asc')
            ->get();

        $featuredPosts = Post::with('category')
            ->whereNotNull('published_at')
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(2)->get();

        $neededPosts = 2 - $featuredPosts->count();
        $fallbackPosts = collect();
        
        if ($neededPosts > 0) {
            $fallbackPosts = Post::with('category')
                ->whereNotNull('published_at')
                ->where('is_featured', false)
                ->latest('published_at')
                ->take($neededPosts)->get();
        }

        $latestPosts = $featuredPosts->merge($fallbackPosts);

        $galleryPhotos = Gallery::where('show_on_homepage', true)->where('type', 'foto')->latest()->take(2)->get();
        $galleryVideos = Gallery::where('show_on_homepage', true)->where('type', 'video')->latest()->take(2)->get();
        $galleries = $galleryPhotos->merge($galleryVideos)->sortByDesc('created_at')->values();

        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();
        $stats = $villageProfile['stats'] ?? [
            'penduduk' => '8,425',
            'kk' => '2,640',
            'rt_rw' => '32 / 08',
            'luas' => '3.82 km²',
        ];

        $relatedLinks = $villageProfile['kemitraan'] ?? [];
        $maklumatText = $villageProfile['maklumat_text'] ?? "Dengan ini, kami seluruh ASN dan Pegawai Pemerintah Kelurahan Patokan menyatakan sanggup menyelenggarakan pelayanan sesuai standar pelayanan yang telah ditetapkan dan siap menerima sanksi sesuai ketentuan perundang-undangan yang berlaku apabila pelayanan tidak sesuai janji.";

        return view('home', compact(
            'announcements',
            'sliderPosts',
            'services',
            'latestPosts',
            'galleries',
            'stats',
            'villageProfile',
            'relatedLinks',
            'maklumatText'
        ));
    }

    /**
     * Display the village organizational structure page.
     */
    public function visiMisi()
    {
        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();

        return view('visi-misi', compact('villageProfile'));
    }

    /**
     * Display the village organizational structure page.
     */
    public function strukturOrganisasi()
    {
        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();
        $rootMembers = \App\Models\OrganizationMember::whereNull('parent_id')
            ->with('childrenRecursive')
            ->orderBy('order')
            ->get();

        return view('struktur-organisasi', compact('villageProfile', 'rootMembers'));
    }

    /**
     * Display the brief history page of Kelurahan Patokan.
     */
    public function sejarah()
    {
        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();

        return view('sejarah', compact('villageProfile'));
    }

    /**
     * Display the location and address page.
     */
    public function lokasi()
    {
        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();
        return view('lokasi', compact('villageProfile'));
    }

    /**
     * Display the WhatsApp service info page.
     */
    public function layananWhatsapp()
    {
        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();
        return view('layanan-whatsapp', compact('villageProfile'));
    }

    /**
     * Display all published news/articles with pagination.
     */
    public function berita(Request $request)
    {
        $query = Post::with('category')->whereNotNull('published_at')->latest('published_at');

        if ($request->filled('kategori')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->kategori));
        }

        $posts = $query->paginate(9)->withQueryString();
        $categories = \App\Models\Category::withCount(['posts' => fn($q) => $q->whereNotNull('published_at')])->get();
        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();

        return view('berita', compact('posts', 'categories', 'villageProfile'));
    }

    /**
     * Display a single news article by slug.
     */
    public function beritaDetail(string $slug)
    {
        $post = Post::with('category')->where('slug', $slug)->whereNotNull('published_at')->firstOrFail();
        $post->increment('views');

        $relatedPosts = Post::with('category')
            ->whereNotNull('published_at')
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();

        return view('berita-detail', compact('post', 'relatedPosts', 'villageProfile'));
    }

    /**
     * Display all gallery photos with pagination and category filtering.
     */
    public function galeri(Request $request)
    {
        $type = $request->query('type', 'foto');
        $query = Gallery::with('images')->where('type', $type)->latest();

        if ($request->filled('kategori')) {
            $query->where('category', $request->kategori);
        }

        $galleries = $query->paginate(12)->withQueryString();
        $categories = Gallery::select('category', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->whereNotNull('category')
            ->where('type', $type)
            ->groupBy('category')
            ->get();
        $totalPhotos = Gallery::where('type', $type)->count();
        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();

        return view('galeri', compact('galleries', 'categories', 'totalPhotos', 'villageProfile', 'type'));
    }

    /**
     * Display the village statistics page.
     */
    public function statistik()
    {
        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();

        return view('statistik', compact('villageProfile'));
    }

    /**
     * Display the budget transparency page.
     */
    public function transparansi()
    {
        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();

        return view('transparansi', compact('villageProfile'));
    }

    /**
     * Display the public documents page.
     */
    public function dokumen(Request $request)
    {
        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();
        
        if ($request->filled('id')) {
            $document = \App\Models\Document::where('is_active', true)->findOrFail($request->id);
            
            $filesQuery = $document->files();
            
            if ($request->filled('filter_month')) {
                $filesQuery->where('month', $request->filter_month);
            }
            if ($request->filled('filter_year')) {
                $filesQuery->where('year', $request->filter_year);
            }
            
            $document->setRelation('files', $filesQuery->orderBy('year', 'desc')->orderBy('month', 'desc')->get());
            
            // For filter options
            $availableYears = $document->files()->select('year')->whereNotNull('year')->distinct()->orderBy('year', 'desc')->pluck('year');
            
            return view('dokumen-detail', compact('villageProfile', 'document', 'availableYears'));
        }

        $query = \App\Models\Document::withCount('files')->where('is_active', true)->orderBy('created_at', 'desc')->orderBy('name', 'asc');
        $documents = $query->paginate(12)->withQueryString();

        return view('dokumen', compact('villageProfile', 'documents'));
    }

    /**
     * Display the standard public services page.
     */
    public function standarPelayanan()
    {
        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();
        $services = \App\Models\ServiceType::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('standar-pelayanan', compact('villageProfile', 'services'));
    }

    /**
     * Display a specific service detail page by slug.
     */
    public function layananDetail(string $slug)
    {
        $service = \App\Models\ServiceType::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();

        return view('standar-pelayanan', compact('villageProfile', 'service'));
    }


    /**
     * Display the public announcements page.
     */
    public function pengumuman(Request $request)
    {
        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();
        
        $query = \App\Models\Announcement::with('category')->where('is_active', true)->latest();
        
        if ($request->filled('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->kategori);
            });
        }
        
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $announcements = $query->paginate(12)->withQueryString();
        $categories = \App\Models\Category::where('type', 'pengumuman')->orderBy('name', 'asc')->get();
        $activeCategory = $request->kategori;

        return view('pengumuman', compact('villageProfile', 'announcements', 'categories', 'activeCategory'));
    }

    /**
     * Display the public agenda page.
     */
    public function agenda(Request $request)
    {
        \App\Models\Agenda::autoArchivePastAgendas();

        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();
        
        $query = \App\Models\Agenda::where('is_active', true)->orderBy('agenda_date', 'asc')->orderBy('agenda_time', 'asc');
        
        // Hanya tampilkan agenda yang belum berlalu (berdasarkan bulan ini ke depan) atau semua
        // Sebagai contoh kita tampilkan semua, atau filter yang akan datang
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $agendas = $query->paginate(12)->withQueryString();

        return view('agenda', compact('villageProfile', 'agendas'));
    }

    public function page($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $villageProfile = \App\Http\Controllers\Admin\VillageProfileController::getProfileData();
        return view('page', compact('page', 'villageProfile'));
    }
}
