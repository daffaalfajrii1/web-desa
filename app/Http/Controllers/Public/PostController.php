<?php

namespace App\Http\Controllers\Public;

use App\Models\Category;
use App\Models\Post;
use App\Services\Public\ViewCounterService;

class PostController extends BasePublicController
{
    public function index()
    {
        $categories = $this->fromTable('categories', fn () => Category::query()
            ->where('type', 'post')
            ->orderBy('name')
            ->get(), collect());

        return $this->listPage(
            Post::class,
            'posts',
            'Berita Desa',
            'Kabar resmi desa, kegiatan pembangunan, dan informasi untuk warga.',
            'public.posts.show',
            'published_at',
            9,
            [
                'view' => 'public.posts.index',
                'with' => ['category', 'author'],
                'search_columns' => ['title', 'excerpt', 'content'],
                'filters' => [
                    'search_placeholder' => 'Cari judul atau ringkasan berita…',
                    'category_options' => $categories,
                ],
            ]
        );
    }

    public function show(Post $post, ViewCounterService $counter)
    {
        $this->abortUnlessVisible($post);
        $counter->increment($post);
        $post->load(['category', 'author']);

        $related = $this->fromTable('posts', fn () => Post::query()
            ->where('status', 'published')
            ->whereKeyNot($post->getKey())
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->orderByDesc('published_at')
            ->limit(4)
            ->get(), collect());

        return view('public.posts.show', [
            'post' => $post,
            'related' => $related,
            'viewsDisplay' => number_format((int) ($post->views ?? 0) + 1, 0, ',', '.'),
            'imageUrl' => fn (?string $path): ?string => $this->imageUrl($path),
        ]);
    }
}
