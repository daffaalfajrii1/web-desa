<?php

namespace App\Http\Controllers\Public;

use App\Models\Shop;
use App\Models\ShopCategory;
use App\Services\Public\ViewCounterService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class LapakController extends BasePublicController
{
    public function index()
    {
        $search = trim((string) request('q', ''));
        $categories = $this->fromTable('shop_categories', fn () => ShopCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(), collect());

        $featured = $this->fromTable('shops', fn () => Shop::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->with('category')
            ->when($search !== '', fn (Builder $q) => $this->applySearch($q, $search))
            ->latest()
            ->limit(8)
            ->get(), collect());

        $latest = $this->fromTable('shops', fn () => Shop::query()
            ->where('is_active', true)
            ->with('category')
            ->when($search !== '', fn (Builder $q) => $this->applySearch($q, $search))
            ->latest()
            ->limit(12)
            ->get(), collect());

        $categoryId = request('kategori');
        $all = $this->fromTable('shops', function () use ($categoryId, $search) {
            return Shop::query()
                ->where('is_active', true)
                ->with('category')
                ->when($categoryId && Schema::hasColumn('shops', 'shop_category_id'), fn (Builder $q) => $q->where('shop_category_id', $categoryId))
                ->when($search !== '', fn (Builder $q) => $this->applySearch($q, $search))
                ->latest()
                ->paginate(18)
                ->withQueryString();
        });

        if (! $all instanceof LengthAwarePaginator) {
            $all = new LengthAwarePaginator(collect(), 0, 18, 1, [
                'path' => request()->url(),
                'pageName' => 'page',
            ]);
        }

        return view('public.shops.index', [
            'featured' => $featured,
            'latest' => $latest,
            'all' => $all,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'search' => $search,
            'imageUrl' => fn (?string $path): ?string => $this->imageUrl($path),
        ]);
    }

    public function show(Shop $shop, ViewCounterService $counter)
    {
        $this->abortUnlessVisible($shop);
        $shop->load(['category', 'images', 'author']);
        $counter->increment($shop);

        $related = $this->fromTable('shops', fn () => Shop::query()
            ->where('is_active', true)
            ->whereKeyNot($shop->getKey())
            ->with('category')
            ->when($shop->shop_category_id, fn (Builder $query) => $query->where('shop_category_id', $shop->shop_category_id))
            ->latest()
            ->limit(4)
            ->get(), collect());

        return view('public.shops.show', [
            'shop' => $shop,
            'related' => $related,
            'viewsDisplay' => number_format((int) ($shop->views ?? 0) + 1, 0, ',', '.'),
            'imageUrl' => fn (?string $path): ?string => $this->imageUrl($path),
        ]);
    }

    private function applySearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $subQuery) use ($search) {
            $subQuery->where('title', 'like', '%'.$search.'%')
                ->orWhere('excerpt', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%')
                ->orWhere('seller_name', 'like', '%'.$search.'%')
                ->orWhere('location', 'like', '%'.$search.'%')
                ->orWhereHas('category', fn (Builder $categoryQuery) => $categoryQuery->where('name', 'like', '%'.$search.'%'));
        });
    }
}
