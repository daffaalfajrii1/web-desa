<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hamlet;
use App\Models\StuntingRecord;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class StuntingRecordController extends Controller
{
    public function index(Request $request)
    {
        $hamlets = Hamlet::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        $items = StuntingRecord::with('hamlet')
            ->when($request->year, fn($q) => $q->where('year', $request->year))
            ->when($request->hamlet_id, fn($q) => $q->where('hamlet_id', $request->hamlet_id))
            ->when($request->stunting_status, fn($q) => $q->where('stunting_status', $request->stunting_status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.infografis.stunting.index', compact('items', 'hamlets'));
    }

    public function create()
    {
        $hamlets = Hamlet::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.infografis.stunting.create', compact('hamlets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'year' => 'required|digits:4',
            'hamlet_id' => 'nullable|exists:hamlets,id',
            'child_name' => 'required|string|max:255',
            'child_nik' => 'nullable|string|max:50',
            'parent_name' => 'nullable|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'age_in_months' => 'nullable|integer|min:0',
            'height_cm' => 'nullable|numeric|min:0',
            'weight_kg' => 'nullable|numeric|min:0',
            'stunting_status' => 'required|in:normal,stunting,berisiko',
            'nutrition_status' => 'required|in:baik,kurang,buruk',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        StuntingRecord::create($data);

        return redirect()->route('admin.stunting-records.index')->with('success', 'Data stunting berhasil ditambahkan.');
    }

    public function edit(StuntingRecord $stunting_record)
    {
        $hamlets = Hamlet::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.infografis.stunting.edit', [
            'item' => $stunting_record,
            'hamlets' => $hamlets,
        ]);
    }

    public function update(Request $request, StuntingRecord $stunting_record)
    {
        $data = $request->validate([
            'year' => 'required|digits:4',
            'hamlet_id' => 'nullable|exists:hamlets,id',
            'child_name' => 'required|string|max:255',
            'child_nik' => 'nullable|string|max:50',
            'parent_name' => 'nullable|string|max:255',
            'gender' => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'age_in_months' => 'nullable|integer|min:0',
            'height_cm' => 'nullable|numeric|min:0',
            'weight_kg' => 'nullable|numeric|min:0',
            'stunting_status' => 'required|in:normal,stunting,berisiko',
            'nutrition_status' => 'required|in:baik,kurang,buruk',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $stunting_record->update($data);

        return redirect()->route('admin.stunting-records.index')->with('success', 'Data stunting berhasil diperbarui.');
    }

    public function destroy(StuntingRecord $stunting_record)
    {
        $stunting_record->delete();
        return redirect()->route('admin.stunting-records.index')->with('success', 'Data stunting berhasil dihapus.');
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $items = StuntingRecord::with('hamlet')
            ->when($request->year, fn($q) => $q->where('year', $request->year))
            ->when($request->hamlet_id, fn($q) => $q->where('hamlet_id', $request->hamlet_id))
            ->when($request->stunting_status, fn($q) => $q->where('stunting_status', $request->stunting_status))
            ->orderBy('year')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Stunting');

        $headers = [
            'TAHUN', 'DUSUN', 'NAMA ANAK', 'NIK ANAK', 'NAMA ORANG TUA',
            'JK', 'TANGGAL LAHIR', 'USIA BULAN', 'TINGGI CM', 'BERAT KG',
            'STATUS STUNTING', 'STATUS GIZI', 'CATATAN'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '2E7D32']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        $row = 2;
        foreach ($items as $item) {
            $sheet->setCellValue('A'.$row, $item->year);
            $sheet->setCellValue('B'.$row, $item->hamlet?->name ?? '-');
            $sheet->setCellValue('C'.$row, $item->child_name);
            $sheet->setCellValue('D'.$row, $item->child_nik);
            $sheet->setCellValue('E'.$row, $item->parent_name);
            $sheet->setCellValue('F'.$row, $item->gender);
            $sheet->setCellValue('G'.$row, optional($item->birth_date)->format('d-m-Y'));
            $sheet->setCellValue('H'.$row, $item->age_in_months);
            $sheet->setCellValue('I'.$row, $item->height_cm);
            $sheet->setCellValue('J'.$row, $item->weight_kg);
            $sheet->setCellValue('K'.$row, $item->stunting_status);
            $sheet->setCellValue('L'.$row, $item->nutrition_status);
            $sheet->setCellValue('M'.$row, $item->notes);
            $row++;
        }

        foreach (range('A', 'M') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'data-stunting.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}