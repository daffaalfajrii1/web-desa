<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hamlet;
use App\Models\PopulationStat;
use App\Models\PopulationSummary;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PopulationImportController extends Controller
{
    public function templateSummaries(): StreamedResponse
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Ringkasan Penduduk');

    $headers = [
        'TAHUN',
        'DUSUN',
        'JUMLAH_KK',
        'LAKI_LAKI',
        'PEREMPUAN',
    ];

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }

    // Header utama hijau
    $sheet->getStyle('A1:E1')->applyFromArray([
        'font' => [
            'bold' => true,
            'color' => ['argb' => 'FFFFFFFF'],
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['argb' => '2E7D32'],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FFCCCCCC'],
            ],
        ],
    ]);

    // Blok keterangan cokelat
    $sheet->mergeCells('G1:J1');
    $sheet->setCellValue('G1', 'KETERANGAN / PETUNJUK PENGISIAN');

    $sheet->setCellValue('G2', 'Kolom');
    $sheet->setCellValue('H2', 'Isi');
    $sheet->setCellValue('I2', 'Keterangan');
    $sheet->setCellValue('J2', 'Contoh');

    $notes = [
        ['TAHUN', '4 digit', 'Isi tahun data', '2026'],
        ['DUSUN', 'Nama dusun', 'Harus sama dengan nama dusun di sistem', 'Dusun 1'],
        ['JUMLAH_KK', 'Angka', 'Jumlah kepala keluarga', '120'],
        ['LAKI_LAKI', 'Angka', 'Jumlah penduduk laki-laki', '245'],
        ['PEREMPUAN', 'Angka', 'Jumlah penduduk perempuan', '230'],
    ];

    $startRow = 3;
    foreach ($notes as $index => $note) {
        $row = $startRow + $index;
        $sheet->setCellValue('G' . $row, $note[0]);
        $sheet->setCellValue('H' . $row, $note[1]);
        $sheet->setCellValue('I' . $row, $note[2]);
        $sheet->setCellValue('J' . $row, $note[3]);
    }

    // Style header blok keterangan
    $sheet->getStyle('G1:J2')->applyFromArray([
        'font' => [
            'bold' => true,
            'color' => ['argb' => 'FFFFFFFF'],
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['argb' => '8D6E63'],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'wrapText' => true,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FFBCAAA4'],
            ],
        ],
    ]);

    // Style isi blok keterangan
    $sheet->getStyle('G3:J7')->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['argb' => 'EFEBE9'],
        ],
        'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
            'wrapText' => true,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FFD7CCC8'],
            ],
        ],
    ]);

    // Contoh baris input otomatis dari data dusun
    $hamlets = Hamlet::orderBy('sort_order')->orderBy('name')->get();
    $row = 2;

    foreach ($hamlets as $hamlet) {
        $sheet->setCellValue('A' . $row, date('Y'));
        $sheet->setCellValue('B' . $row, $hamlet->name);
        $sheet->setCellValue('C' . $row, 0);
        $sheet->setCellValue('D' . $row, 0);
        $sheet->setCellValue('E' . $row, 0);
        $row++;
    }

    // Kalau belum ada dusun, kasih 1 contoh kosong
    if ($hamlets->isEmpty()) {
        $sheet->setCellValue('A2', date('Y'));
        $sheet->setCellValue('B2', 'Dusun 1');
        $sheet->setCellValue('C2', 0);
        $sheet->setCellValue('D2', 0);
        $sheet->setCellValue('E2', 0);
    }

    // Auto size
    foreach (range('A', 'J') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    $sheet->getStyle('A:E')->getAlignment()->setWrapText(true);
    $sheet->getStyle('G:J')->getAlignment()->setWrapText(true);

    return response()->streamDownload(function () use ($spreadsheet) {
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }, 'template-ringkasan-penduduk.xlsx', [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ]);
}

    public function templateStats(): StreamedResponse
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Statistik Penduduk');

    $headers = [
        'TAHUN',
        'DUSUN',
        'KATEGORI',
        'ITEM',
        'NILAI',
    ];

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }

    // Header utama
    $sheet->getStyle('A1:E1')->applyFromArray([
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

    // Blok keterangan
    $sheet->mergeCells('G1:J1');
    $sheet->setCellValue('G1', 'KETERANGAN / PETUNJUK PENGISIAN');

    $sheet->setCellValue('G2', 'Kategori');
    $sheet->setCellValue('H2', 'Contoh Item');
    $sheet->setCellValue('I2', 'Keterangan');
    $sheet->setCellValue('J2', 'Contoh Nilai');

    $notes = [
        ['umur', '0-4, 5-9, 10-14, 15-19, 20-24, 25-29, 30-34, 35-39, 40-44, 45-49, 50-54, 55-59, 60-64, 65-69, 70-74, 75-79, 80-84, 85+', 'Isi sesuai kelompok umur', '12'],
        ['pendidikan', 'Tidak/Belum Sekolah, Belum Tamat SD/Sederajat, Tamat SD/Sederajat, SLTP/Sederajat, SLTA/Sederajat, Diploma I/II, Diploma III/Sarjana Muda, Diploma IV/Strata I, Strata II, Strata III', 'Isi sesuai jenjang pendidikan', '30'],
        ['wajib_pilih', '2024, 2025, 2026', 'Item diisi tahun data wajib pilih', '120'],
        ['perkawinan', 'Belum Kawin, Kawin, Kawin Tercatat, Kawin Tidak Tercatat, Cerai Hidup, Cerai Mati', 'Isi status perkawinan', '45'],
        ['agama', 'Islam, Kristen, Katolik, Hindu, Buddha, Khonghucu, Kepercayaan', 'Isi agama penduduk', '80'],
    ];

    $startRow = 3;
    foreach ($notes as $index => $note) {
        $row = $startRow + $index;
        $sheet->setCellValue('G' . $row, $note[0]);
        $sheet->setCellValue('H' . $row, $note[1]);
        $sheet->setCellValue('I' . $row, $note[2]);
        $sheet->setCellValue('J' . $row, $note[3]);
    }

    // Style header keterangan
    $sheet->getStyle('G1:J2')->applyFromArray([
        'font' => [
            'bold' => true,
            'color' => ['argb' => 'FFFFFFFF'],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => '8D6E63'], // cokelat
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FFBCAAA4'],
            ],
        ],
    ]);

    // Style isi keterangan
    $sheet->getStyle('G3:J7')->applyFromArray([
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'EFEBE9'], // cokelat muda
        ],
        'alignment' => [
            'vertical' => Alignment::VERTICAL_TOP,
            'wrapText' => true,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FFD7CCC8'],
            ],
        ],
    ]);

    // Contoh baris input
    $hamlets = Hamlet::orderBy('sort_order')->orderBy('name')->get();
    $row = 2;

    foreach ($hamlets as $hamlet) {
        $sheet->setCellValue('A' . $row, date('Y'));
        $sheet->setCellValue('B' . $row, $hamlet->name);
        $sheet->setCellValue('C' . $row, 'umur');
        $sheet->setCellValue('D' . $row, '0-4');
        $sheet->setCellValue('E' . $row, 0);
        $row++;
    }

    // Auto size
    foreach (range('A', 'J') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    $sheet->getStyle('A:E')->getAlignment()->setWrapText(true);
    $sheet->getStyle('G:J')->getAlignment()->setWrapText(true);

    return response()->streamDownload(function () use ($spreadsheet) {
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }, 'template-statistik-penduduk.xlsx', [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ]);
}

    public function importSummaries(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        unset($rows[0]);

        foreach ($rows as $row) {
            $year = trim((string) ($row[0] ?? ''));
            $hamletName = trim((string) ($row[1] ?? ''));
            $totalKk = (int) ($row[2] ?? 0);
            $maleCount = (int) ($row[3] ?? 0);
            $femaleCount = (int) ($row[4] ?? 0);

            if ($year === '' || $hamletName === '') {
                continue;
            }

            $hamlet = Hamlet::where('name', $hamletName)->first();

            if (! $hamlet) {
                continue;
            }

            PopulationSummary::updateOrCreate(
                [
                    'hamlet_id' => $hamlet->id,
                    'year' => $year,
                ],
                [
                    'total_kk' => $totalKk,
                    'male_count' => $maleCount,
                    'female_count' => $femaleCount,
                ]
            );
        }

        return redirect()->route('admin.population-summaries.index')
            ->with('success', 'Import ringkasan penduduk berhasil.');
    }

    public function importStats(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        unset($rows[0]);

        foreach ($rows as $row) {
            $year = trim((string) ($row[0] ?? ''));
            $hamletName = trim((string) ($row[1] ?? ''));
            $category = trim((string) ($row[2] ?? ''));
            $itemName = trim((string) ($row[3] ?? ''));
            $value = (int) ($row[4] ?? 0);

            if ($year === '' || $hamletName === '' || $category === '' || $itemName === '') {
                continue;
            }

            $hamlet = Hamlet::where('name', $hamletName)->first();

            if (! $hamlet) {
                continue;
            }

            PopulationStat::updateOrCreate(
                [
                    'hamlet_id' => $hamlet->id,
                    'year' => $year,
                    'category' => $category,
                    'item_name' => $itemName,
                ],
                [
                    'value' => $value,
                ]
            );
        }

        return redirect()->route('admin.population-stats.index')
            ->with('success', 'Import statistik penduduk berhasil.');
    }
}