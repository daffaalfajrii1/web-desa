<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IdmSummary;
use Illuminate\Http\Request;

class IdmSummaryController extends Controller
{
    public function index(Request $request)
    {
        $items = IdmSummary::when($request->year, function ($q) use ($request) {
                $q->where('year', $request->year);
            })
            ->orderByDesc('year')
            ->paginate(10)
            ->withQueryString();

        return view('admin.infografis.idm.summaries.index', compact('items'));
    }

    public function create()
    {
        return view('admin.infografis.idm.summaries.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'year' => 'required|digits:4|unique:idm_summaries,year',
            'iks_score' => 'required|numeric|min:0|max:1',
            'ike_score' => 'required|numeric|min:0|max:1',
            'ikl_score' => 'required|numeric|min:0|max:1',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $idmScore = IdmSummary::calculateIdmScore(
            $data['iks_score'],
            $data['ike_score'],
            $data['ikl_score']
        );

        $data['idm_score'] = $idmScore;
        $data['idm_status'] = IdmSummary::resolveStatus($idmScore);
        $data['target_status'] = IdmSummary::resolveTargetStatus($idmScore);
        $data['minimal_target_score'] = IdmSummary::resolveMinimalTargetScore($idmScore);
        $data['additional_score_needed'] = IdmSummary::resolveAdditionalNeeded($idmScore);
        $data['is_active'] = $request->boolean('is_active');

        IdmSummary::create($data);

        return redirect()->route('admin.idm-summaries.index')
            ->with('success', 'Ringkasan IDM berhasil ditambahkan.');
    }

    public function show(IdmSummary $idm_summary)
    {
        $idm_summary->load('indicators');

        return view('admin.infografis.idm.summaries.show', [
            'item' => $idm_summary
        ]);
    }

    public function edit(IdmSummary $idm_summary)
    {
        return view('admin.infografis.idm.summaries.edit', [
            'item' => $idm_summary
        ]);
    }

    public function update(Request $request, IdmSummary $idm_summary)
    {
        $data = $request->validate([
            'year' => 'required|digits:4|unique:idm_summaries,year,' . $idm_summary->id,
            'iks_score' => 'required|numeric|min:0|max:1',
            'ike_score' => 'required|numeric|min:0|max:1',
            'ikl_score' => 'required|numeric|min:0|max:1',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $idmScore = IdmSummary::calculateIdmScore(
            $data['iks_score'],
            $data['ike_score'],
            $data['ikl_score']
        );

        $data['idm_score'] = $idmScore;
        $data['idm_status'] = IdmSummary::resolveStatus($idmScore);
        $data['target_status'] = IdmSummary::resolveTargetStatus($idmScore);
        $data['minimal_target_score'] = IdmSummary::resolveMinimalTargetScore($idmScore);
        $data['additional_score_needed'] = IdmSummary::resolveAdditionalNeeded($idmScore);
        $data['is_active'] = $request->boolean('is_active');

        $idm_summary->update($data);

        return redirect()->route('admin.idm-summaries.index')
            ->with('success', 'Ringkasan IDM berhasil diperbarui.');
    }

    public function destroy(IdmSummary $idm_summary)
    {
        $idm_summary->delete();

        return redirect()->route('admin.idm-summaries.index')
            ->with('success', 'Ringkasan IDM berhasil dihapus.');
    }
}