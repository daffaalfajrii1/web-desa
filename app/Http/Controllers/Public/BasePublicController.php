<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

abstract class BasePublicController extends Controller
{
    protected function listPage(
        string $model,
        string $table,
        string $title,
        string $description,
        string $showRoute,
        string $dateColumn = 'created_at',
        int $perPage = 9,
        array $options = []
    ) {
        $search = trim((string) request('q', ''));
        $category = request('kategori');
        $documentType = trim((string) request('jenis', ''));
        $year = request('tahun');
        $featured = request('unggulan');
        $searchColumns = $options['search_columns'] ?? ['title', 'excerpt', 'description'];

        $items = $this->fromTable($table, function () use ($model, $table, $dateColumn, $perPage, $options, $search, $category, $documentType, $year, $featured, $searchColumns) {
            $query = $model::query();

            if (($options['with'] ?? []) !== []) {
                $query->with($options['with']);
            }

            return $query
                ->when(Schema::hasColumn($table, 'status') && $table !== 'shops', fn (Builder $query) => $query->where('status', 'published'))
                ->when(Schema::hasColumn($table, 'is_active'), fn (Builder $query) => $query->where('is_active', true))
                ->when($search !== '', function (Builder $query) use ($table, $search, $searchColumns) {
                    $columns = collect($searchColumns)
                        ->filter(fn (string $column) => Schema::hasColumn($table, $column))
                        ->values();

                    if ($columns->isEmpty()) {
                        return;
                    }

                    $query->where(function (Builder $subQuery) use ($columns, $search) {
                        foreach ($columns as $column) {
                            $subQuery->orWhere($column, 'like', '%'.$search.'%');
                        }
                    });
                })
                ->when($category && Schema::hasColumn($table, 'category_id'), fn (Builder $query) => $query->where('category_id', $category))
                ->when($documentType !== '' && Schema::hasColumn($table, 'document_type'), fn (Builder $query) => $query->where('document_type', $documentType))
                ->when($year && Schema::hasColumn($table, $dateColumn), fn (Builder $query) => $query->whereYear($dateColumn, $year))
                ->when($featured === '1' && Schema::hasColumn($table, 'is_featured'), fn (Builder $query) => $query->where('is_featured', true))
                ->when(Schema::hasColumn($table, $dateColumn), fn (Builder $query) => $query->orderByDesc($dateColumn))
                ->latest()
                ->paginate($perPage)
                ->withQueryString();
        });

        return view($options['view'] ?? 'public.pages.list', [
            'title' => $title,
            'description' => $description,
            'items' => $items,
            'showRoute' => $showRoute,
            'filters' => $options['filters'] ?? [],
            'activeFilters' => [
                'q' => $search,
                'kategori' => $category,
                'jenis' => $documentType,
                'tahun' => $year,
                'unggulan' => $featured,
            ],
            'imageUrl' => fn (?string $path): ?string => $this->imageUrl($path),
        ]);
    }

    protected function detailPage(Model $item, string $title, string $backRoute, ?string $body = null, array $meta = [])
    {
        return view('public.pages.detail', [
            'title' => $title,
            'item' => $item,
            'body' => $body,
            'meta' => $meta,
            'backRoute' => $backRoute,
            'imageUrl' => fn (?string $path): ?string => $this->imageUrl($path),
        ]);
    }

    protected function abortUnlessVisible(Model $model): void
    {
        $status = $model->getAttribute('status');

        if (array_key_exists('status', $model->getAttributes()) && in_array($status, ['draft', 'published'], true) && $status !== 'published') {
            abort(404);
        }

        if (array_key_exists('is_active', $model->getAttributes()) && ! $model->is_active) {
            abort(404);
        }
    }

    protected function imageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return Storage::url($path);
    }

    protected function fromTable(string $table, callable $callback, mixed $default = null): mixed
    {
        try {
            if (! Schema::hasTable($table)) {
                return $default;
            }

            return $callback();
        } catch (Throwable) {
            return $default;
        }
    }
}
