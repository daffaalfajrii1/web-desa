<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IdmIndicator;
use App\Models\IdmSummary;
use Illuminate\Http\Request;

class IdmIndicatorController extends Controller
{
    public function index(Request $request)
    {
        $summaries = IdmSummary::orderByDesc('year')->get();

        $items = IdmIndicator::with('summary')
            ->when($request->idm_summary_id, function ($q) use ($request) {
                $q->where('idm_summary_id', $request->idm_summary_id);
            })
            ->when($request->category, function ($q) use ($request) {
                $q->where('category', $request->category);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.infografis.idm.indicators.index', compact('items', 'summaries'));
    }

    public function create()
    {
        $summaries = IdmSummary::orderByDesc('year')->get();

        return view('admin.infografis.idm.indicators.create', compact('summaries'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idm_summary_id' => 'required|exists:idm_summaries,id',
            'category' => 'required|in:IKS,IKE,IKL',
            'indicator_no' => 'required|integer|min:1',
            'indicator_name' => 'required|string|max:255',
            'score' => 'required|integer|min:0|max:5',
            'description' => 'nullable|string',
            'activity' => 'nullable|string',
            'value' => 'nullable|numeric|min:0|max:1',
            'executor_central' => 'nullable|string|max:255',
            'executor_province' => 'nullable|string|max:255',
            'executor_regency' => 'nullable|string|max:255',
            'executor_village' => 'nullable|string|max:255',
            'executor_csr' => 'nullable|string|max:255',
            'executor_other' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        IdmIndicator::create($data);

        return redirect()
            ->route('admin.idm-indicators.index')
            ->with('success', 'Indikator IDM berhasil ditambahkan.');
    }

    public function edit(IdmIndicator $idm_indicator)
    {
        $summaries = IdmSummary::orderByDesc('year')->get();

        return view('admin.infografis.idm.indicators.edit', [
            'item' => $idm_indicator,
            'summaries' => $summaries,
        ]);
    }

    public function update(Request $request, IdmIndicator $idm_indicator)
    {
        $data = $request->validate([
            'idm_summary_id' => 'required|exists:idm_summaries,id',
            'category' => 'required|in:IKS,IKE,IKL',
            'indicator_no' => 'required|integer|min:1',
            'indicator_name' => 'required|string|max:255',
            'score' => 'required|integer|min:0|max:5',
            'description' => 'nullable|string',
            'activity' => 'nullable|string',
            'value' => 'nullable|numeric|min:0|max:1',
            'executor_central' => 'nullable|string|max:255',
            'executor_province' => 'nullable|string|max:255',
            'executor_regency' => 'nullable|string|max:255',
            'executor_village' => 'nullable|string|max:255',
            'executor_csr' => 'nullable|string|max:255',
            'executor_other' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $idm_indicator->update($data);

        return redirect()
            ->route('admin.idm-indicators.index')
            ->with('success', 'Indikator IDM berhasil diperbarui.');
    }

    public function destroy(IdmIndicator $idm_indicator)
    {
        $idm_indicator->delete();

        return redirect()
            ->route('admin.idm-indicators.index')
            ->with('success', 'Indikator IDM berhasil dihapus.');
    }
}