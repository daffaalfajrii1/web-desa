<?php

namespace App\Http\Controllers\Public;

use App\Models\Category;
use App\Models\LegalProduct;
use App\Services\Public\ViewCounterService;

class LegalProductController extends BasePublicController
{
    public function index()
    {
        $categories = $this->fromTable('categories', fn () => Category::query()
            ->where('type', 'legal_product')
            ->orderBy('name')
            ->get(), collect());
        $types = $this->fromTable('legal_products', fn () => LegalProduct::query()
            ->whereNotNull('document_type')
            ->distinct()
            ->orderBy('document_type')
            ->pluck('document_type')
            ->filter()
            ->values(), collect());
        $years = $this->fromTable('legal_products', fn () => LegalProduct::query()
            ->whereNotNull('published_date')
            ->selectRaw('YEAR(published_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->filter()
            ->values(), collect());

        return $this->listPage(
            LegalProduct::class,
            'legal_products',
            'Produk Hukum',
            'Dokumen regulasi dan produk hukum desa.',
            'public.legal-products.show',
            'published_date',
            9,
            [
                'with' => ['category'],
                'search_columns' => ['title', 'number', 'document_type', 'description'],
                'filters' => [
                    'search_placeholder' => 'Cari judul, nomor, atau jenis dokumen...',
                    'category_options' => $categories,
                    'document_type_options' => $types,
                    'year_options' => $years,
                ],
            ]
        );
    }

    public function show(LegalProduct $legalProduct, ViewCounterService $counter)
    {
        $this->abortUnlessVisible($legalProduct);
        $counter->increment($legalProduct);

        return $this->detailPage($legalProduct, $legalProduct->title, 'public.legal-products.index', $legalProduct->description, [
            'Nomor' => $legalProduct->number,
            'Jenis' => $legalProduct->document_type,
            'Dilihat' => number_format((int) $legalProduct->views + 1, 0, ',', '.'),
        ]);
    }
}
