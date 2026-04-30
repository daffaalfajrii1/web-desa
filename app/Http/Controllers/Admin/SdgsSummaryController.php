<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SdgsGoal;
use App\Models\SdgsGoalValue;
use App\Models\SdgsSummary;
use Illuminate\Http\Request;

class SdgsSummaryController extends Controller
{
    public function index(Request $request)
    {
        $items = SdgsSummary::query()
            ->when($request->year, fn ($q) => $q->where('year', $request->year))
            ->orderByDesc('year')
            ->paginate(10)
            ->withQueryString();

        return view('admin.infografis.sdgs.summaries.index', compact('items'));
    }

    public function create()
    {
        return view('admin.infografis.sdgs.summaries.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'year' => 'required|digits:4|unique:sdgs_summaries,year',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $summary = SdgsSummary::create($data);

        $goals = SdgsGoal::where('is_active', true)->orderBy('sort_order')->get();

        foreach ($goals as $goal) {
            SdgsGoalValue::create([
                'sdgs_summary_id' => $summary->id,
                'sdgs_goal_id' => $goal->id,
                'score' => 0,
                'achievement_percent' => 0,
                'status' => 'prioritas',
                'short_description' => null,
                'sort_order' => $goal->sort_order ?? $goal->goal_number,
                'is_active' => true,
            ]);
        }

        $summary->recalculate();

        return redirect()
            ->route('admin.sdgs-goal-values.index', ['sdgs_summary_id' => $summary->id])
            ->with('success', 'Ringkasan SDGS berhasil ditambahkan dan 18 tujuan otomatis dibuat.');
    }

    public function show(SdgsSummary $sdgs_summary)
    {
        $sdgs_summary->load(['goalValues.goal']);

        return view('admin.infografis.sdgs.summaries.show', [
            'item' => $sdgs_summary,
        ]);
    }

    public function edit(SdgsSummary $sdgs_summary)
    {
        return view('admin.infografis.sdgs.summaries.edit', [
            'item' => $sdgs_summary,
        ]);
    }

    public function update(Request $request, SdgsSummary $sdgs_summary)
    {
        $data = $request->validate([
            'year' => 'required|digits:4|unique:sdgs_summaries,year,' . $sdgs_summary->id,
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $sdgs_summary->update($data);
        $sdgs_summary->recalculate();

        return redirect()
            ->route('admin.sdgs-summaries.index')
            ->with('success', 'Ringkasan SDGS berhasil diperbarui.');
    }

    public function destroy(SdgsSummary $sdgs_summary)
    {
        $sdgs_summary->delete();

        return redirect()
            ->route('admin.sdgs-summaries.index')
            ->with('success', 'Ringkasan SDGS berhasil dihapus.');
    }
}