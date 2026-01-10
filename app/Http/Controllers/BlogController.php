<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BlogController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
        protected SettingService $settingService
    ) {}

    /**
     * Display a listing of blog posts
     */
    public function index(Request $request): View
    {
        $sortBy = $request->get('d', 'latest');

        $sortMap = [
            'l' => 'latest',
            'o' => 'oldest',
            'p' => 'popular',
        ];

        $sort = $sortMap[$sortBy] ?? 'latest';

        $blogs = $this->blogService->getBlogs($sort, 25);
        $siteConfig = $this->settingService->getSiteConfig();

        return view('blogs.index', [
            'blogs' => $blogs,
            'siteConfig' => $siteConfig,
            'currentSort' => $sortBy,
        ]);
    }

    /**
     * Display a single blog post
     */
    public function show(int $id): View|RedirectResponse
    {
        $blog = $this->blogService->getBlogWithViewIncrement($id);

        if (!$blog) {
            return redirect()->route('blogs.index')
                ->with('error', 'Blog post not found');
        }

        $siteConfig = $this->settingService->getSiteConfig();

        return view('blogs.show', [
            'blog' => $blog,
            'siteConfig' => $siteConfig,
        ]);
    }

    /**
     * Show the form for creating a new blog post
     */
    public function create(): View|RedirectResponse
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'You must be logged in as an admin to create posts.');
        }

        $siteConfig = $this->settingService->getSiteConfig();

        return view('blogs.create', [
            'siteConfig' => $siteConfig,
        ]);
    }

    /**
     * Store a newly created blog post
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'You must be logged in as an admin to create posts.');
        }

        $validated = $request->validate([
            'title' => 'required|string|min:3|max:100',
            'body' => 'required|string|min:10|max:60000',
            'show_email' => 'required|boolean',
        ]);

        // Add admin's email to the validated data
        $validated['email'] = Auth::guard('admin')->user()->email ?? 'admin@blogbb.local';

        try {
            $blog = $this->blogService->createBlog($validated);

            return redirect()->route('blogs.show', $blog->id)
                ->with('success', 'Blog post created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('blogs.create')
                ->with('error', 'Failed to create blog post. Please try again.')
                ->withInput();
        }
    }

    /**
     * Search for blog posts
     */
    public function search(Request $request): View|RedirectResponse
    {
        $searchTerm = $request->get('q');

        if (empty($searchTerm)) {
            return redirect()->route('blogs.index');
        }

        $sortBy = $request->get('d', 'latest');

        $sortMap = [
            'l' => 'latest',
            'o' => 'oldest',
            'p' => 'popular',
        ];

        $sort = $sortMap[$sortBy] ?? 'latest';

        $blogs = $this->blogService->searchBlogs($searchTerm, $sort, 25);
        $siteConfig = $this->settingService->getSiteConfig();

        return view('blogs.search', [
            'blogs' => $blogs,
            'siteConfig' => $siteConfig,
            'searchTerm' => $searchTerm,
            'currentSort' => $sortBy,
        ]);
    }

    /**
     * Display the home page
     */
    public function home(): View
    {
        $siteConfig = $this->settingService->getSiteConfig();
        $recentBlogs = $this->blogService->getRecentBlogs(5);

        return view('home', [
            'siteConfig' => $siteConfig,
            'recentBlogs' => $recentBlogs,
        ]);
    }

    /**
     * Display the credits page
     */
    public function credits(): View
    {
        $siteConfig = $this->settingService->getSiteConfig();

        return view('credits', [
            'siteConfig' => $siteConfig,
        ]);
    }
}
