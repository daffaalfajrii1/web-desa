<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apbdes;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
class ApbdesController extends Controller
{
    public function index(Request $request)
{
    $year = $request->get('year');

    $items = Apbdes::query()
        ->when($year, fn ($q) => $q->where('year', $year))
        ->orderByDesc('year')
        ->paginate(10)
        ->withQueryString();

    return view('admin.infografis.apbdes.index', compact('items'));
}
    public function create()
    {
        return view('admin.infografis.apbdes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'year' => 'required|digits:4|unique:apbdes,year',
            'pendapatan' => 'required|numeric|min:0',
            'belanja' => 'required|numeric|min:0',
            'pembiayaan_penerimaan' => 'required|numeric|min:0',
            'pembiayaan_pengeluaran' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        Apbdes::create($data);

        return redirect()->route('admin.apbdes.index')
            ->with('success', 'Data APBDes berhasil ditambahkan.');
    }

    public function edit(Apbdes $apbde)
    {
        return view('admin.infografis.apbdes.edit', [
            'item' => $apbde,
        ]);
    }

    public function update(Request $request, Apbdes $apbde)
    {
        $data = $request->validate([
            'year' => 'required|digits:4|unique:apbdes,year,' . $apbde->id,
            'pendapatan' => 'required|numeric|min:0',
            'belanja' => 'required|numeric|min:0',
            'pembiayaan_penerimaan' => 'required|numeric|min:0',
            'pembiayaan_pengeluaran' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $apbde->update($data);

        return redirect()->route('admin.apbdes.index')
            ->with('success', 'Data APBDes berhasil diperbarui.');
    }

    public function destroy(Apbdes $apbde)
    {
        $apbde->delete();

        return redirect()->route('admin.apbdes.index')
            ->with('success', 'Data APBDes berhasil dihapus.');
    }
    public function exportExcel(Request $request): StreamedResponse
{
    $year = $request->get('year');

    $items = \App\Models\Apbdes::query()
        ->when($year, fn ($q) => $q->where('year', $year))
        ->orderBy('year')
        ->get();

    $spreadsheet = new Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Data APBDes');

    $headers = [
        'TAHUN',
        'PENDAPATAN',
        'BELANJA',
        'PEMBIAYAAN PENERIMAAN',
        'PEMBIAYAAN PENGELUARAN',
        'SURPLUS/DEFISIT',
        'PEMBIAYAAN NETTO',
        'SILPA',
    ];

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }

    $sheet->getStyle('A1:H1')->applyFromArray([
        'font' => [
            'bold' => true,
            'color' => ['argb' => 'FFFFFFFF'],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => '2E7D32'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FFCCCCCC'],
            ],
        ],
    ]);

    $row = 2;
    foreach ($items as $item) {
        $sheet->setCellValue('A' . $row, $item->year);
        $sheet->setCellValue('B' . $row, (float) $item->pendapatan);
        $sheet->setCellValue('C' . $row, (float) $item->belanja);
        $sheet->setCellValue('D' . $row, (float) $item->pembiayaan_penerimaan);
        $sheet->setCellValue('E' . $row, (float) $item->pembiayaan_pengeluaran);
        $sheet->setCellValue('F' . $row, (float) $item->surplus_defisit);
        $sheet->setCellValue('G' . $row, (float) $item->pembiayaan_netto);
        $sheet->setCellValue('H' . $row, (float) $item->silpa);
        $row++;
    }

    foreach (range('A', 'H') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    if ($row > 2) {
        $sheet->getStyle('B2:H' . ($row - 1))
            ->getNumberFormat()
            ->setFormatCode('"Rp"#,##0.00;[Red]-"Rp"#,##0.00');
    }

    // Chart 1: Pendapatan vs Belanja
    $labelSeries1 = [
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data APBDes'!\$B\$1", null, 1),
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data APBDes'!\$C\$1", null, 1),
    ];

    $xAxisTickValues1 = [
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data APBDes'!\$A\$2:\$A\$" . ($row - 1), null, max(0, $row - 2)),
    ];

    $dataSeriesValues1 = [
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data APBDes'!\$B\$2:\$B\$" . ($row - 1), null, max(0, $row - 2)),
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data APBDes'!\$C\$2:\$C\$" . ($row - 1), null, max(0, $row - 2)),
    ];

    $series1 = new DataSeries(
        DataSeries::TYPE_BARCHART,
        DataSeries::GROUPING_CLUSTERED,
        range(0, count($dataSeriesValues1) - 1),
        $labelSeries1,
        $xAxisTickValues1,
        $dataSeriesValues1
    );

    $plotArea1 = new PlotArea(null, [$series1]);
    $chart1 = new Chart(
        'chart1',
        new Title('Pendapatan vs Belanja'),
        new Legend(Legend::POSITION_RIGHT, null, false),
        $plotArea1
    );
    $chart1->setTopLeftPosition('J2');
    $chart1->setBottomRightPosition('Q18');

    $sheet->addChart($chart1);

    // Chart 2: Surplus/Defisit, Netto, SILPA
    $labelSeries2 = [
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data APBDes'!\$F\$1", null, 1),
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data APBDes'!\$G\$1", null, 1),
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data APBDes'!\$H\$1", null, 1),
    ];

    $xAxisTickValues2 = [
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Data APBDes'!\$A\$2:\$A\$" . ($row - 1), null, max(0, $row - 2)),
    ];

    $dataSeriesValues2 = [
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data APBDes'!\$F\$2:\$F\$" . ($row - 1), null, max(0, $row - 2)),
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data APBDes'!\$G\$2:\$G\$" . ($row - 1), null, max(0, $row - 2)),
        new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Data APBDes'!\$H\$2:\$H\$" . ($row - 1), null, max(0, $row - 2)),
    ];

    $series2 = new DataSeries(
        DataSeries::TYPE_LINECHART,
        DataSeries::GROUPING_STANDARD,
        range(0, count($dataSeriesValues2) - 1),
        $labelSeries2,
        $xAxisTickValues2,
        $dataSeriesValues2
    );

    $plotArea2 = new PlotArea(null, [$series2]);
    $chart2 = new Chart(
        'chart2',
        new Title('Surplus, Netto, SILPA'),
        new Legend(Legend::POSITION_RIGHT, null, false),
        $plotArea2
    );
    $chart2->setTopLeftPosition('J20');
    $chart2->setBottomRightPosition('Q36');

    $sheet->addChart($chart2);

    return response()->streamDownload(function () use ($spreadsheet) {
        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $writer->save('php://output');
    }, 'export-apbdes.xlsx', [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ]);
}

public function chartView(Request $request)
{
    $year = $request->get('year');

    $items = Apbdes::query()
        ->when($year, fn ($q) => $q->where('year', $year))
        ->orderBy('year')
        ->get();

    $chartData = [
        'labels' => $items->pluck('year')->values(),
        'pendapatan' => $items->pluck('pendapatan')->map(fn ($v) => (float) $v)->values(),
        'belanja' => $items->pluck('belanja')->map(fn ($v) => (float) $v)->values(),
        'pembiayaan_penerimaan' => $items->pluck('pembiayaan_penerimaan')->map(fn ($v) => (float) $v)->values(),
        'pembiayaan_pengeluaran' => $items->pluck('pembiayaan_pengeluaran')->map(fn ($v) => (float) $v)->values(),
        'surplus_defisit' => $items->map(fn ($item) => (float) $item->surplus_defisit)->values(),
        'pembiayaan_netto' => $items->map(fn ($item) => (float) $item->pembiayaan_netto)->values(),
        'silpa' => $items->map(fn ($item) => (float) $item->silpa)->values(),
    ];

    $summary = [
        'pendapatan' => (float) $items->sum('pendapatan'),
        'belanja' => (float) $items->sum('belanja'),
        'pembiayaan_penerimaan' => (float) $items->sum('pembiayaan_penerimaan'),
        'pembiayaan_pengeluaran' => (float) $items->sum('pembiayaan_pengeluaran'),
        'surplus_defisit' => (float) $items->sum(fn ($item) => $item->surplus_defisit),
        'pembiayaan_netto' => (float) $items->sum(fn ($item) => $item->pembiayaan_netto),
        'silpa' => (float) $items->sum(fn ($item) => $item->silpa),
    ];

    return view('admin.infografis.apbdes.chart-view', compact('items', 'chartData', 'summary', 'year'));
}
}