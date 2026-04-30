<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hamlet;
use App\Models\StuntingRecord;
use Illuminate\Http\Request;

class StuntingChartController extends Controller
{
    public function index(Request $request)
    {
        $hamlets = Hamlet::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        $items = StuntingRecord::with('hamlet')
            ->when($request->year, fn($q) => $q->where('year', $request->year))
            ->when($request->hamlet_id, fn($q) => $q->where('hamlet_id', $request->hamlet_id))
            ->get();

        $summary = [
            'total' => $items->count(),
            'stunting' => $items->where('stunting_status', 'stunting')->count(),
            'normal' => $items->where('stunting_status', 'normal')->count(),
            'berisiko' => $items->where('stunting_status', 'berisiko')->count(),
        ];

        $statusChart = [
            'Normal' => $summary['normal'],
            'Stunting' => $summary['stunting'],
            'Berisiko' => $summary['berisiko'],
        ];

        $hamletChart = $items->groupBy(fn ($item) => $item->hamlet?->name ?? 'Tanpa Dusun')
            ->map(fn ($rows) => $rows->count());

        $genderChart = [
            'Laki-laki' => $items->where('gender', 'L')->count(),
            'Perempuan' => $items->where('gender', 'P')->count(),
        ];

        return view('admin.infografis.stunting.chart-view', compact(
            'hamlets',
            'summary',
            'statusChart',
            'hamletChart',
            'genderChart'
        ));
    }
}