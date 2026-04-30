<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SdgsGoal;
use App\Models\SdgsGoalValue;
use App\Models\SdgsSummary;
use Illuminate\Http\Request;

class SdgsGoalValueController extends Controller
{
    public function index(Request $request)
    {
        $summaries = SdgsSummary::orderByDesc('year')->get();

        $items = SdgsGoalValue::with(['summary', 'goal'])
            ->when($request->sdgs_summary_id, fn ($q) => $q->where('sdgs_summary_id', $request->sdgs_summary_id))
            ->orderBy('sort_order')
            ->paginate(20)
            ->withQueryString();

        return view('admin.infografis.sdgs.goal-values.index', compact('items', 'summaries'));
    }

    public function create()
    {
        $summaries = SdgsSummary::orderByDesc('year')->get();
        $goals = SdgsGoal::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.infografis.sdgs.goal-values.create', compact('summaries', 'goals'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sdgs_summary_id' => 'required|exists:sdgs_summaries,id',
            'sdgs_goal_id' => 'required|exists:sdgs_goals,id',
            'score' => 'required|numeric|min:0|max:100',
            'achievement_percent' => 'nullable|numeric|min:0|max:100',
            'short_description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['status'] = SdgsGoalValue::resolveStatus($data['score']);
        $data['is_active'] = $request->boolean('is_active');

        SdgsGoalValue::updateOrCreate(
            [
                'sdgs_summary_id' => $data['sdgs_summary_id'],
                'sdgs_goal_id' => $data['sdgs_goal_id'],
            ],
            $data
        );

        $summary = SdgsSummary::find($data['sdgs_summary_id']);
        $summary?->recalculate();

        return redirect()
            ->route('admin.sdgs-goal-values.index', ['sdgs_summary_id' => $data['sdgs_summary_id']])
            ->with('success', 'Nilai tujuan SDGS berhasil disimpan.');
    }

    public function edit(SdgsGoalValue $sdgs_goal_value)
    {
        $summaries = SdgsSummary::orderByDesc('year')->get();
        $goals = SdgsGoal::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.infografis.sdgs.goal-values.edit', [
            'item' => $sdgs_goal_value,
            'summaries' => $summaries,
            'goals' => $goals,
        ]);
    }

    public function update(Request $request, SdgsGoalValue $sdgs_goal_value)
    {
        $data = $request->validate([
            'sdgs_summary_id' => 'required|exists:sdgs_summaries,id',
            'sdgs_goal_id' => 'required|exists:sdgs_goals,id',
            'score' => 'required|numeric|min:0|max:100',
            'achievement_percent' => 'nullable|numeric|min:0|max:100',
            'short_description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['status'] = SdgsGoalValue::resolveStatus($data['score']);
        $data['is_active'] = $request->boolean('is_active');

        $sdgs_goal_value->update($data);

        $summary = SdgsSummary::find($data['sdgs_summary_id']);
        $summary?->recalculate();

        return redirect()
            ->route('admin.sdgs-goal-values.index', ['sdgs_summary_id' => $data['sdgs_summary_id']])
            ->with('success', 'Nilai tujuan SDGS berhasil diperbarui.');
    }

    public function destroy(SdgsGoalValue $sdgs_goal_value)
    {
        $summaryId = $sdgs_goal_value->sdgs_summary_id;
        $sdgs_goal_value->delete();

        $summary = SdgsSummary::find($summaryId);
        $summary?->recalculate();

        return redirect()
            ->route('admin.sdgs-goal-values.index', ['sdgs_summary_id' => $summaryId])
            ->with('success', 'Nilai tujuan SDGS berhasil dihapus.');
    }
}