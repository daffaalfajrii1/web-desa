<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('type', 'post')->orderBy('name')->get();

        $search = $request->get('search');
        $status = $request->get('status');
        $categoryId = $request->get('category_id');
        $sort = $request->get('sort', 'latest');

        $posts = Post::with(['author', 'category'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('excerpt', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($categoryId === 'uncategorized', function ($query) {
                $query->whereNull('category_id');
            })
            ->when($categoryId && $categoryId !== 'uncategorized', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });

        switch ($sort) {
            case 'oldest':
                $posts->oldest();
                break;
            case 'most_viewed':
                $posts->orderBy('views', 'desc');
                break;
            case 'title_asc':
                $posts->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $posts->orderBy('title', 'desc');
                break;
            case 'category_asc':
                $posts->leftJoin('categories', 'posts.category_id', '=', 'categories.id')
                    ->select('posts.*')
                    ->orderByRaw('CASE WHEN categories.name IS NULL THEN 1 ELSE 0 END')
                    ->orderBy('categories.name', 'asc');
                break;
            case 'category_desc':
                $posts->leftJoin('categories', 'posts.category_id', '=', 'categories.id')
                    ->select('posts.*')
                    ->orderByRaw('CASE WHEN categories.name IS NULL THEN 1 ELSE 0 END')
                    ->orderBy('categories.name', 'desc');
                break;
            default:
                $posts->latest();
                break;
        }

        $posts = $posts->paginate(10)->withQueryString();

        return view('admin.berita.index', compact('posts', 'categories', 'sort'));
    }

    public function create()
    {
        $categories = Category::where('type', 'post')->orderBy('name')->get();

        return view('admin.berita.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'category_id' => 'nullable|exists:categories,id',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['created_by'] = auth()->id();

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        Post::create($data);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function show(Post $beritum)
    {
        $beritum->load(['author', 'category']);

        return view('admin.berita.show', ['post' => $beritum]);
    }

    public function edit(Post $beritum)
    {
        $categories = Category::where('type', 'post')->orderBy('name')->get();

        return view('admin.berita.edit', [
            'post' => $beritum,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Post $beritum)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $beritum->id,
            'category_id' => 'nullable|exists:categories,id',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        if ($request->hasFile('featured_image')) {
            if ($beritum->featured_image && Storage::disk('public')->exists($beritum->featured_image)) {
                Storage::disk('public')->delete($beritum->featured_image);
            }

            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        if ($data['status'] === 'published' && ! $beritum->published_at) {
            $data['published_at'] = now();
        }

        $beritum->update($data);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Post $beritum)
    {
        if ($beritum->featured_image && Storage::disk('public')->exists($beritum->featured_image)) {
            Storage::disk('public')->delete($beritum->featured_image);
        }

        $beritum->delete();

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }
}