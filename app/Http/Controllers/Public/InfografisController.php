<?php

namespace App\Http\Controllers\Public;

use App\Models\Apbdes;
use App\Models\Hamlet;
use App\Models\IdmIndicator;
use App\Models\IdmSummary;
use App\Models\PopulationStat;
use App\Models\PopulationSummary;
use App\Models\SdgsGoalValue;
use App\Models\SdgsSummary;
use App\Models\SocialAssistanceProgram;
use App\Models\SocialAssistanceRecipient;
use App\Models\StuntingRecord;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InfografisController extends BasePublicController
{
    public function index()
    {
        return view('public.pages.infographics', [
            'populationYear' => $this->fromTable('population_summaries', fn () => PopulationSummary::query()->max('year')),
            'populationTotal' => $this->fromTable('population_summaries', fn () => (int) PopulationSummary::query()->sum('male_count') + (int) PopulationSummary::query()->sum('female_count'), 0),
            'apbdes' => $this->fromTable('apbdes', fn () => Apbdes::query()->where('is_active', true)->latest('year')->first()),
            'idm' => $this->fromTable('idm_summaries', fn () => IdmSummary::query()->where('is_active', true)->latest('year')->first()),
            'sdgs' => $this->fromTable('sdgs_summaries', fn () => SdgsSummary::query()->where('is_active', true)->latest('year')->first()),
            'bansosCount' => $this->fromTable('social_assistance_programs', fn () => SocialAssistanceProgram::query()->where('is_active', true)->count(), 0),
        ]);
    }

    public function hamlets()
    {
        $year = $this->fromTable('population_summaries', fn () => PopulationSummary::query()->max('year'));

        $hamlets = $this->fromTable('hamlets', function () use ($year) {
            return Hamlet::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->with(['populationSummaries' => fn ($q) => $q->when($year, fn ($qq) => $qq->where('year', $year))])
                ->get()
                ->map(function (Hamlet $h) use ($year) {
                    $summary = $year
                        ? $h->populationSummaries->firstWhere('year', $year)
                        : $h->populationSummaries->sortByDesc('year')->first();

                    return [
                        'model' => $h,
                        'summary' => $summary,
                        'total' => $summary ? (int) $summary->male_count + (int) $summary->female_count : 0,
                        'kk' => $summary ? (int) $summary->total_kk : 0,
                    ];
                });
        }, collect());

        return view('public.infographics.hamlets', compact('hamlets', 'year'));
    }

    public function populationRingkas()
    {
        $year = request('tahun') ?: $this->fromTable('population_summaries', fn () => PopulationSummary::query()->max('year'));
        $years = $this->fromTable('population_summaries', fn () => PopulationSummary::query()->distinct()->orderByDesc('year')->pluck('year')->filter(), collect());
        if ($years->isEmpty() && $year) {
            $years = collect([$year]);
        }

        $rows = $this->fromTable('population_summaries', function () use ($year) {
            return PopulationSummary::query()
                ->when($year, fn ($q) => $q->where('year', $year))
                ->with('hamlet')
                ->get();
        }, collect());

        $totals = [
            'male' => (int) $rows->sum('male_count'),
            'female' => (int) $rows->sum('female_count'),
            'kk' => (int) $rows->sum('total_kk'),
            'total' => (int) $rows->sum(fn ($r) => (int) $r->male_count + (int) $r->female_count),
        ];

        return view('public.infographics.population-ringkas', compact('rows', 'year', 'years', 'totals'));
    }

    public function populationStatistik(Request $request)
    {
        $year = $request->input('tahun') ?: $this->fromTable('population_stats', fn () => PopulationStat::query()->max('year'));
        $years = $this->fromTable('population_stats', fn () => PopulationStat::query()->distinct()->orderByDesc('year')->pluck('year')->filter(), collect());
        if ($years->isEmpty() && $year) {
            $years = collect([$year]);
        }

        $byCategory = $this->fromTable('population_stats', function () use ($year) {
            if (! $year) {
                return collect();
            }

            return PopulationStat::query()
                ->where('year', $year)
                ->with('hamlet')
                ->orderBy('category')
                ->orderBy('hamlet_id')
                ->orderBy('item_name')
                ->get()
                ->groupBy('category')
                ->map(function ($rows, $categoryKey) {
                    $hamlets = $rows
                        ->pluck('hamlet.name')
                        ->filter()
                        ->unique()
                        ->values();

                    $items = $rows
                        ->pluck('item_name')
                        ->filter()
                        ->unique()
                        ->values();

                    $matrix = $items->map(function ($itemName) use ($hamlets, $rows) {
                        $perHamlet = $hamlets->mapWithKeys(function ($hamletName) use ($rows, $itemName) {
                            $value = (int) $rows
                                ->where('item_name', $itemName)
                                ->where('hamlet.name', $hamletName)
                                ->sum('value');

                            return [$hamletName => $value];
                        });

                        return [
                            'item' => $itemName,
                            'values' => $perHamlet,
                            'total' => (int) $perHamlet->sum(),
                        ];
                    })->values();

                    return [
                        'key' => $categoryKey,
                        'label' => ucfirst(str_replace('_', ' ', (string) $categoryKey)),
                        'hamlets' => $hamlets,
                        'rows' => $matrix,
                        'grand_total' => (int) $matrix->sum('total'),
                    ];
                })
                ->values();
        }, collect());

        return view('public.infographics.population-statistik', compact('byCategory', 'year', 'years'));
    }

    public function apbdes()
    {
        $rows = $this->fromTable('apbdes', fn () => Apbdes::query()->orderByDesc('year')->get(), collect());
        $active = $rows->firstWhere('is_active', true) ?? $rows->first();

        return view('public.infographics.apbdes', compact('rows', 'active'));
    }

    public function bansosProgram()
    {
        $programs = $this->fromTable('social_assistance_programs', fn () => SocialAssistanceProgram::query()
            ->withCount('recipients')
            ->orderByDesc('year')
            ->orderBy('name')
            ->get(), collect());

        return view('public.infographics.bansos-program', compact('programs'));
    }

    public function bansosPenerima(Request $request)
    {
        $programId = $request->query('program');
        $recipients = $this->fromTable('social_assistance_recipients', function () use ($programId) {
            return SocialAssistanceRecipient::query()
                ->with(['program', 'hamlet'])
                ->when($programId, fn ($q) => $q->where('social_assistance_program_id', $programId))
                ->orderBy('name')
                ->paginate(24)
                ->withQueryString();
        });

        if (! $recipients instanceof LengthAwarePaginator) {
            $recipients = new LengthAwarePaginator(collect(), 0, 24, 1, [
                'path' => $request->url(),
                'pageName' => 'page',
            ]);
        }

        $programs = $this->fromTable('social_assistance_programs', fn () => SocialAssistanceProgram::query()->orderBy('name')->get(), collect());

        return view('public.infographics.bansos-penerima', [
            'recipients' => $recipients,
            'programs' => $programs,
            'selectedProgram' => $programId,
        ]);
    }

    public function bansosChart()
    {
        $perProgram = $this->fromTable('social_assistance_programs', function () {
            return SocialAssistanceProgram::query()
                ->withCount('recipients')
                ->orderBy('name')
                ->get();
        }, collect());

        $distribution = $this->fromTable('social_assistance_recipients', function () {
            return SocialAssistanceRecipient::query()
                ->select('distribution_status', DB::raw('count(*) as c'))
                ->groupBy('distribution_status')
                ->pluck('c', 'distribution_status');
        }, collect());

        return view('public.infographics.bansos-chart', compact('perProgram', 'distribution'));
    }

    public function bansosCek(Request $request)
    {
        $result = collect();
        $q = trim((string) $request->input('q', ''));

        if ($q !== '') {
            $result = $this->fromTable('social_assistance_recipients', function () use ($q) {
                return SocialAssistanceRecipient::query()
                    ->with(['program', 'hamlet'])
                    ->where(function ($query) use ($q) {
                        $query->where('nik', $q)
                            ->orWhere('name', 'like', '%'.$q.'%')
                            ->orWhere('kk_number', $q);
                    })
                    ->limit(25)
                    ->get();
            }, collect());
        }

        return view('public.infographics.bansos-cek', [
            'result' => $result,
            'q' => $q,
        ]);
    }

    public function stunting(Request $request)
    {
        $year = $request->input('tahun');

        $payload = $this->fromTable('stunting_records', function () use ($year, $request) {
            $years = StuntingRecord::query()->distinct()->orderByDesc('year')->pluck('year');
            $base = StuntingRecord::query()
                ->with('hamlet')
                ->where('is_active', true)
                ->when($year, fn ($q) => $q->where('year', $year));

            $summary = (clone $base)
                ->select('stunting_status', DB::raw('count(*) as c'))
                ->groupBy('stunting_status')
                ->pluck('c', 'stunting_status');

            $records = (clone $base)
                ->orderBy('hamlet_id')
                ->orderBy('child_name')
                ->paginate(20)
                ->withQueryString();

            return compact('years', 'summary', 'records');
        }, [
            'years' => collect(),
            'summary' => collect(),
            'records' => null,
        ]);

        return view('public.infographics.stunting', [
            'records' => $payload['records'],
            'summary' => $payload['summary'],
            'years' => $payload['years'],
            'year' => $year,
        ]);
    }

    public function idmPage()
    {
        $summary = $this->fromTable('idm_summaries', fn () => IdmSummary::query()->where('is_active', true)->latest('year')->first());
        $history = $this->fromTable('idm_summaries', fn () => IdmSummary::query()->orderBy('year')->get(), collect());

        $indicators = collect();
        if ($summary) {
            $indicators = $this->fromTable('idm_indicators', fn () => IdmIndicator::query()
                ->where('idm_summary_id', $summary->id)
                ->where('is_active', true)
                ->orderBy('category')
                ->orderBy('indicator_no')
                ->get()
                ->groupBy('category'));
        }

        return view('public.infographics.idm', compact('summary', 'history', 'indicators'));
    }

    public function sdgsPage()
    {
        $summary = $this->fromTable('sdgs_summaries', fn () => SdgsSummary::query()->where('is_active', true)->latest('year')->first());

        $goals = collect();
        if ($summary) {
            $goals = $this->fromTable('sdgs_goal_values', fn () => SdgsGoalValue::query()
                ->where('sdgs_summary_id', $summary->id)
                ->where('is_active', true)
                ->with('goal')
                ->orderBy('sort_order')
                ->get());
        }

        return view('public.infographics.sdgs', compact('summary', 'goals'));
    }
}
