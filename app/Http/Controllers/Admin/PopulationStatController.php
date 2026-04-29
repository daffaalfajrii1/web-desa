<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hamlet;
use App\Models\PopulationStat;
use Illuminate\Http\Request;

class PopulationStatController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year');
        $hamletId = $request->get('hamlet_id');
        $category = $request->get('category');

        $hamlets = Hamlet::where('is_active', true)->orderBy('sort_order')->get();

        $items = PopulationStat::with('hamlet')
            ->when($year, fn ($q) => $q->where('year', $year))
            ->when($hamletId, fn ($q) => $q->where('hamlet_id', $hamletId))
            ->when($category, fn ($q) => $q->where('category', $category))
            ->orderByDesc('year')
            ->orderBy('category')
            ->orderBy('item_name')
            ->paginate(20)
            ->withQueryString();

        $categories = [
            'umur' => 'Umur',
            'pendidikan' => 'Pendidikan',
            'wajib_pilih' => 'Wajib Pilih',
            'perkawinan' => 'Perkawinan',
            'agama' => 'Agama',
        ];

        return view('admin.infografis.population-stats.index', compact('items', 'hamlets', 'categories'));
    }

    public function create()
    {
        $hamlets = Hamlet::where('is_active', true)->orderBy('sort_order')->get();

        $categories = [
            'umur' => [
                '0-4', '5-9', '10-14', '15-19', '20-24', '25-29', '30-34', '35-39',
                '40-44', '45-49', '50-54', '55-59', '60-64', '65-69', '70-74',
                '75-79', '80-84', '85+'
            ],
            'pendidikan' => [
                'Tidak/Belum Sekolah',
                'Belum Tamat SD/Sederajat',
                'Tamat SD/Sederajat',
                'SLTP/Sederajat',
                'SLTA/Sederajat',
                'Diploma I/II',
                'Diploma III/Sarjana Muda',
                'Diploma IV/Strata I',
                'Strata II',
                'Strata III'
            ],
            'wajib_pilih' => [
                '2024', '2025', '2026'
            ],
            'perkawinan' => [
                'Kawin Tidak Tercatat',
                'Belum Kawin',
                'Kawin',
                'Cerai Hidup',
                'Cerai Mati',
                'Kawin Tercatat'
            ],
            'agama' => [
                'Islam',
                'Kristen',
                'Katolik',
                'Hindu',
                'Buddha',
                'Khonghucu',
                'Kepercayaan'
            ],
        ];

        return view('admin.infografis.population-stats.create', compact('hamlets', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hamlet_id' => 'required|exists:hamlets,id',
            'year' => 'required|digits:4',
            'category' => 'required|string',
            'item_name' => 'required|string|max:255',
            'value' => 'required|integer|min:0',
        ]);

        PopulationStat::updateOrCreate(
            [
                'hamlet_id' => $data['hamlet_id'],
                'year' => $data['year'],
                'category' => $data['category'],
                'item_name' => $data['item_name'],
            ],
            [
                'value' => $data['value'],
            ]
        );

        return redirect()->route('admin.population-stats.index')
            ->with('success', 'Statistik penduduk berhasil disimpan.');
    }

    public function edit(PopulationStat $population_stat)
{
    $hamlets = Hamlet::where('is_active', true)->orderBy('sort_order')->get();

    $categories = [
        'umur' => [
            '0-4', '5-9', '10-14', '15-19', '20-24', '25-29', '30-34', '35-39',
            '40-44', '45-49', '50-54', '55-59', '60-64', '65-69', '70-74',
            '75-79', '80-84', '85+'
        ],
        'pendidikan' => [
            'Tidak/Belum Sekolah',
            'Belum Tamat SD/Sederajat',
            'Tamat SD/Sederajat',
            'SLTP/Sederajat',
            'SLTA/Sederajat',
            'Diploma I/II',
            'Diploma III/Sarjana Muda',
            'Diploma IV/Strata I',
            'Strata II',
            'Strata III'
        ],
        'wajib_pilih' => [
            '2024', '2025', '2026'
        ],
        'perkawinan' => [
            'Kawin Tidak Tercatat',
            'Belum Kawin',
            'Kawin',
            'Cerai Hidup',
            'Cerai Mati',
            'Kawin Tercatat'
        ],
        'agama' => [
            'Islam',
            'Kristen',
            'Katolik',
            'Hindu',
            'Buddha',
            'Khonghucu',
            'Kepercayaan'
        ],
    ];

    return view('admin.infografis.population-stats.edit', [
        'item' => $population_stat,
        'hamlets' => $hamlets,
        'categories' => $categories,
    ]);
}

    public function update(Request $request, PopulationStat $population_stat)
    {
        $data = $request->validate([
            'hamlet_id' => 'required|exists:hamlets,id',
            'year' => 'required|digits:4',
            'category' => 'required|string',
            'item_name' => 'required|string|max:255',
            'value' => 'required|integer|min:0',
        ]);

        $population_stat->update($data);

        return redirect()->route('admin.population-stats.index')
            ->with('success', 'Statistik penduduk berhasil diperbarui.');
    }

    public function destroy(PopulationStat $population_stat)
    {
        $population_stat->delete();

        return redirect()->route('admin.population-stats.index')
            ->with('success', 'Statistik penduduk berhasil dihapus.');
    }

    public function chartView(Request $request)
{
    $year = $request->get('year');
    $hamletId = $request->get('hamlet_id');

    $hamlets = \App\Models\Hamlet::where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

    // Query statistik
    $statQuery = \App\Models\PopulationStat::query()->with('hamlet');

    if ($year) {
        $statQuery->where('year', $year);
    }

    if ($hamletId) {
        $statQuery->where('hamlet_id', $hamletId);
    }

    $stats = $statQuery->get();

    // Query ringkasan penduduk
    $summaryQuery = \App\Models\PopulationSummary::query()->with('hamlet');

    if ($year) {
        $summaryQuery->where('year', $year);
    }

    if ($hamletId) {
        $summaryQuery->where('hamlet_id', $hamletId);
    }

    $summaries = $summaryQuery->get();

    $summaryCards = [
        'total_kk' => (int) $summaries->sum('total_kk'),
        'male_count' => (int) $summaries->sum('male_count'),
        'female_count' => (int) $summaries->sum('female_count'),
        'total_population' => (int) ($summaries->sum('male_count') + $summaries->sum('female_count')),
    ];

    $categoryOrders = [
        'umur' => [
            '0-4', '5-9', '10-14', '15-19', '20-24', '25-29', '30-34', '35-39',
            '40-44', '45-49', '50-54', '55-59', '60-64', '65-69', '70-74',
            '75-79', '80-84', '85+'
        ],
        'pendidikan' => [
            'Tidak/Belum Sekolah',
            'Belum Tamat SD/Sederajat',
            'Tamat SD/Sederajat',
            'SLTP/Sederajat',
            'SLTA/Sederajat',
            'Diploma I/II',
            'Diploma III/Sarjana Muda',
            'Diploma IV/Strata I',
            'Strata II',
            'Strata III',
        ],
        'wajib_pilih' => [
            '2024', '2025', '2026', '2027', '2028', '2029', '2030',
        ],
        'perkawinan' => [
            'Belum Kawin',
            'Kawin',
            'Kawin Tercatat',
            'Kawin Tidak Tercatat',
            'Cerai Hidup',
            'Cerai Mati',
        ],
        'agama' => [
            'Islam',
            'Kristen',
            'Katolik',
            'Hindu',
            'Buddha',
            'Khonghucu',
            'Kepercayaan',
        ],
    ];

    $chartData = [];

    foreach ($categoryOrders as $category => $items) {
        $grouped = $stats
            ->where('category', $category)
            ->groupBy('item_name')
            ->map(fn ($rows) => $rows->sum('value'));

        $labels = [];
        $values = [];

        foreach ($items as $item) {
            $labels[] = $item;
            $values[] = (int) ($grouped[$item] ?? 0);
        }

        foreach ($grouped as $itemName => $value) {
            if (!in_array($itemName, $labels)) {
                $labels[] = $itemName;
                $values[] = (int) $value;
            }
        }

        $chartData[$category] = [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    return view('admin.infografis.population-stats.chart-view', [
        'hamlets' => $hamlets,
        'chartData' => $chartData,
        'summaryCards' => $summaryCards,
        'selectedYear' => $year,
        'selectedHamlet' => $hamletId,
    ]);
}
}