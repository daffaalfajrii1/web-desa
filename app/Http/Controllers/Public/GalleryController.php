<?php

namespace App\Http\Controllers\Public;

use App\Models\Gallery;
use App\Services\Public\ViewCounterService;
use Illuminate\Database\Eloquent\Builder;

class GalleryController extends BasePublicController
{
    public function index()
    {
        $search = trim((string) request('q', ''));
        $type = request('tipe');

        $items = Gallery::query()
            ->with('author')
            ->where('status', Gallery::STATUS_PUBLISHED)
            ->when(in_array($type, [Gallery::TYPE_PHOTO, Gallery::TYPE_VIDEO], true), fn (Builder $query) => $query->where('media_type', $type))
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $subQuery) use ($search) {
                    $subQuery->where('title', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('location', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Gallery::query()->where('status', Gallery::STATUS_PUBLISHED)->count(),
            'photo' => Gallery::query()->where('status', Gallery::STATUS_PUBLISHED)->where('media_type', Gallery::TYPE_PHOTO)->count(),
            'video' => Gallery::query()->where('status', Gallery::STATUS_PUBLISHED)->where('media_type', Gallery::TYPE_VIDEO)->count(),
        ];

        return view('public.galleries.index', [
            'items' => $items,
            'stats' => $stats,
            'search' => $search,
            'selectedType' => $type,
        ]);
    }

    public function show(Gallery $gallery, ViewCounterService $counter)
    {
        $this->abortUnlessVisible($gallery);
        $gallery->load('author');
        $counter->increment($gallery);

        $related = Gallery::query()
            ->with('author')
            ->where('status', Gallery::STATUS_PUBLISHED)
            ->whereKeyNot($gallery->getKey())
            ->orderByDesc('published_at')
            ->latest()
            ->limit(4)
            ->get();

        return view('public.galleries.show', [
            'gallery' => $gallery,
            'related' => $related,
            'viewsDisplay' => number_format((int) ($gallery->views ?? 0) + 1, 0, ',', '.'),
        ]);
    }
}
