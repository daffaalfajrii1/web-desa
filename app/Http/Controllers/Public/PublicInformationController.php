<?php

namespace App\Http\Controllers\Public;

use App\Models\PublicInformation;
use App\Services\Public\ViewCounterService;

class PublicInformationController extends BasePublicController
{
    public function index()
    {
        $years = $this->fromTable('public_informations', fn () => PublicInformation::query()
            ->whereNotNull('published_date')
            ->selectRaw('YEAR(published_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->filter()
            ->values(), collect());

        return $this->listPage(
            PublicInformation::class,
            'public_informations',
            'Informasi Publik',
            'Dokumen dan informasi yang dapat diakses masyarakat.',
            'public.public-informations.show',
            'published_date',
            9,
            [
                'search_columns' => ['title', 'description'],
                'filters' => [
                    'search_placeholder' => 'Cari judul atau isi informasi...',
                    'year_options' => $years,
                ],
            ]
        );
    }

    public function show(PublicInformation $publicInformation, ViewCounterService $counter)
    {
        $this->abortUnlessVisible($publicInformation);
        $counter->increment($publicInformation);

        return $this->detailPage($publicInformation, $publicInformation->title, 'public.public-informations.index', $publicInformation->description, [
            'Tanggal' => $publicInformation->published_date?->translatedFormat('d F Y'),
            'Dilihat' => number_format((int) $publicInformation->views + 1, 0, ',', '.'),
        ]);
    }
}
