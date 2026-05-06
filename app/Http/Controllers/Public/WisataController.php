<?php

namespace App\Http\Controllers\Public;

use App\Models\Tourism;
use App\Services\Public\ViewCounterService;
use Illuminate\Support\Str;

class WisataController extends BasePublicController
{
    public function index()
    {
        return $this->listPage(
            Tourism::class,
            'tourisms',
            'Wisata Desa',
            'Destinasi dan potensi wisata desa.',
            'public.tourism.show',
            'created_at',
            9,
            [
                'search_columns' => ['title', 'excerpt', 'description', 'address', 'facilities', 'open_days'],
                'filters' => [
                    'search_placeholder' => 'Cari nama wisata, lokasi, fasilitas...',
                    'featured_options' => true,
                ],
            ]
        );
    }

    public function show(Tourism $tourism, ViewCounterService $counter)
    {
        $this->abortUnlessVisible($tourism);
        $counter->increment($tourism);

        $tourism->load('images');

        $mainImage = $this->imageUrl($tourism->main_image);
        $gallery = $tourism->images->map(fn ($img) => $this->imageUrl($img->image_path))->filter()->values();

        $facilities = $tourism->facilities
            ? collect(preg_split('/\r\n|\r|\n/', (string) $tourism->facilities))->map(fn ($l) => trim($l))->filter()->values()
            : collect();

        $openDays = $tourism->open_days
            ? collect(preg_split('/\r\n|\r|\n|,/', (string) $tourism->open_days))->map(fn ($l) => trim($l))->filter()->values()
            : collect();

        $closedDays = $tourism->closed_days
            ? collect(preg_split('/\r\n|\r|\n|,/', (string) $tourism->closed_days))->map(fn ($l) => trim($l))->filter()->values()
            : collect();

        return view('public.tourism.show', [
            'tourism' => $tourism,
            'mainImage' => $mainImage,
            'gallery' => $gallery,
            'facilities' => $facilities,
            'openDays' => $openDays,
            'closedDays' => $closedDays,
            'mapEmbed' => $tourism->map_embed ? Str::of($tourism->map_embed)->trim() : null,
            'bodyHtml' => $tourism->description,
        ]);
    }
}
