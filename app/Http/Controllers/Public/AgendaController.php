<?php

namespace App\Http\Controllers\Public;

use App\Models\Agenda;
use App\Services\Public\ViewCounterService;
use Illuminate\Database\Eloquent\Builder;

class AgendaController extends BasePublicController
{
    public function index()
    {
        $search = trim((string) request('q', ''));
        $items = $this->fromTable('agendas', function () use ($search) {
            return Agenda::query()
                ->where('status', 'published')
                ->when($search !== '', function (Builder $query) use ($search) {
                    $query->where(function (Builder $subQuery) use ($search) {
                        $subQuery->where('title', 'like', '%'.$search.'%')
                            ->orWhere('description', 'like', '%'.$search.'%')
                            ->orWhere('location', 'like', '%'.$search.'%')
                            ->orWhere('organizer', 'like', '%'.$search.'%');
                    });
                })
                ->orderByDesc('start_date')
                ->orderByDesc('start_time')
                ->paginate(12)
                ->withQueryString();
        });

        return view('public.agendas.index', [
            'items' => $items,
            'search' => $search,
            'imageUrl' => fn (?string $path): ?string => $this->imageUrl($path),
        ]);
    }

    public function show(Agenda $agenda, ViewCounterService $counter)
    {
        $this->abortUnlessVisible($agenda);
        $counter->increment($agenda);

        return $this->detailPage($agenda, $agenda->title, 'public.agendas.index', $agenda->description, [
            'Tanggal' => $agenda->start_date?->translatedFormat('d F Y'),
            'Lokasi' => $agenda->location,
            'Dilihat' => number_format((int) $agenda->views + 1, 0, ',', '.'),
        ]);
    }
}
