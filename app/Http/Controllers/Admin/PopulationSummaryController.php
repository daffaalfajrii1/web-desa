<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hamlet;
use App\Models\PopulationSummary;
use Illuminate\Http\Request;

class PopulationSummaryController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year');
        $hamletId = $request->get('hamlet_id');

        $hamlets = Hamlet::where('is_active', true)->orderBy('sort_order')->get();

        $items = PopulationSummary::with('hamlet')
            ->when($year, fn ($q) => $q->where('year', $year))
            ->when($hamletId, fn ($q) => $q->where('hamlet_id', $hamletId))
            ->orderByDesc('year')
            ->paginate(10)
            ->withQueryString();

        return view('admin.infografis.population-summaries.index', compact('items', 'hamlets'));
    }

    public function create()
    {
        $hamlets = Hamlet::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.infografis.population-summaries.create', compact('hamlets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hamlet_id' => 'required|exists:hamlets,id',
            'year' => 'required|digits:4',
            'total_kk' => 'required|integer|min:0',
            'male_count' => 'required|integer|min:0',
            'female_count' => 'required|integer|min:0',
        ]);

        PopulationSummary::updateOrCreate(
            [
                'hamlet_id' => $data['hamlet_id'],
                'year' => $data['year'],
            ],
            [
                'total_kk' => $data['total_kk'],
                'male_count' => $data['male_count'],
                'female_count' => $data['female_count'],
            ]
        );

        return redirect()->route('admin.population-summaries.index')
            ->with('success', 'Ringkasan penduduk berhasil disimpan.');
    }

    public function edit(PopulationSummary $population_summary)
    {
        $hamlets = Hamlet::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.infografis.population-summaries.edit', [
            'item' => $population_summary,
            'hamlets' => $hamlets,
        ]);
    }

    public function update(Request $request, PopulationSummary $population_summary)
    {
        $data = $request->validate([
            'hamlet_id' => 'required|exists:hamlets,id',
            'year' => 'required|digits:4',
            'total_kk' => 'required|integer|min:0',
            'male_count' => 'required|integer|min:0',
            'female_count' => 'required|integer|min:0',
        ]);

        $population_summary->update($data);

        return redirect()->route('admin.population-summaries.index')
            ->with('success', 'Ringkasan penduduk berhasil diperbarui.');
    }

    public function destroy(PopulationSummary $population_summary)
    {
        $population_summary->delete();

        return redirect()->route('admin.population-summaries.index')
            ->with('success', 'Ringkasan penduduk berhasil dihapus.');
    }
}