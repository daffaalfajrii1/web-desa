<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialAssistanceProgram;
use App\Models\SocialAssistanceRecipient;
use Illuminate\Http\Request;

class SocialAssistanceChartController extends Controller
{
    public function index(Request $request)
    {
        $programId = $request->get('program_id');

        $programs = SocialAssistanceProgram::orderByDesc('year')->orderBy('name')->get();

        $query = SocialAssistanceRecipient::with(['program', 'hamlet'])
            ->when($programId, fn ($q) => $q->where('social_assistance_program_id', $programId));

        $items = $query->get();

        $summary = [
            'total_recipients' => $items->count(),
            'total_distributed' => $items->where('distribution_status', 'distributed')->count(),
            'total_ready' => $items->where('distribution_status', 'ready')->count(),
            'total_pending' => $items->where('distribution_status', 'pending')->count(),
            'total_amount' => (float) $items->sum('amount'),
        ];

        $programChart = $items->groupBy(fn ($item) => $item->program?->name ?? 'Tanpa Program')
            ->map(fn ($rows) => $rows->count());

        $statusChart = [
            'Pending' => $items->where('distribution_status', 'pending')->count(),
            'Siap Diambil' => $items->where('distribution_status', 'ready')->count(),
            'Sudah Diambil' => $items->where('distribution_status', 'distributed')->count(),
            'Ditolak' => $items->where('distribution_status', 'rejected')->count(),
        ];

        $hamletChart = $items->groupBy(fn ($item) => $item->hamlet?->name ?? 'Tanpa Dusun')
            ->map(fn ($rows) => $rows->count());

        return view('admin.infografis.bansos-chart.index', compact(
            'programs',
            'summary',
            'programChart',
            'statusChart',
            'hamletChart',
            'programId'
        ));
    }
}