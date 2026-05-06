<?php

namespace Database\Seeders;

use App\Models\Apbdes;
use App\Models\Hamlet;
use App\Models\IdmIndicator;
use App\Models\IdmSummary;
use App\Models\PopulationStat;
use App\Models\PopulationSummary;
use App\Models\SdgsGoal;
use App\Models\SdgsGoalValue;
use App\Models\SdgsSummary;
use App\Models\SocialAssistanceProgram;
use App\Models\SocialAssistanceRecipient;
use App\Models\StuntingRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class DemoInfographicsSeeder extends Seeder
{
    public function run(): void
    {
        $hamlets = $this->seedHamlets();
        $this->seedPopulationSummary($hamlets);
        $this->seedPopulationStats($hamlets);
        $this->seedApbdes();
        $this->seedBansos($hamlets);
        $this->seedStunting($hamlets);
        $this->seedIdm();
        $this->seedSdgs();
    }

    private function seedHamlets(): array
    {
        if (! Schema::hasTable('hamlets')) {
            return [];
        }

        $hamlets = [];
        foreach (['Dusun 1', 'Dusun 2', 'Dusun 3', 'Dusun 4'] as $i => $name) {
            $hamlets[] = Hamlet::query()->updateOrCreate(
                ['name' => $name],
                ['sort_order' => $i + 1, 'is_active' => true]
            );
        }

        return $hamlets;
    }

    private function seedPopulationSummary(array $hamlets): void
    {
        if (! Schema::hasTable('population_summaries')) {
            return;
        }

        $year = (int) now()->format('Y');
        foreach ($hamlets as $i => $hamlet) {
            PopulationSummary::query()->updateOrCreate(
                ['hamlet_id' => $hamlet->id, 'year' => $year],
                [
                    'total_kk' => 115 + ($i * 14),
                    'male_count' => 260 + ($i * 23),
                    'female_count' => 248 + ($i * 21),
                ]
            );
        }
    }

    private function seedPopulationStats(array $hamlets): void
    {
        if (! Schema::hasTable('population_stats')) {
            return;
        }

        $year = (int) now()->format('Y');
        $categories = [
            'umur' => ['0-5 Tahun' => 110, '6-17 Tahun' => 295, '18-59 Tahun' => 760, '60+ Tahun' => 132],
            'pendidikan' => ['Tidak Sekolah' => 24, 'SD/Sederajat' => 460, 'SMP/Sederajat' => 320, 'SMA/SMK' => 290, 'Diploma/S1+' => 110],
            'agama' => ['Islam' => 1190, 'Kristen' => 75, 'Katolik' => 20, 'Hindu' => 5, 'Budha' => 7],
        ];

        foreach ($hamlets as $hIndex => $hamlet) {
            foreach ($categories as $category => $items) {
                foreach ($items as $itemName => $baseValue) {
                    PopulationStat::query()->updateOrCreate(
                        [
                            'hamlet_id' => $hamlet->id,
                            'year' => $year,
                            'category' => $category,
                            'item_name' => $itemName,
                        ],
                        ['value' => max(1, $baseValue - ($hIndex * 8))]
                    );
                }
            }
        }
    }

    private function seedApbdes(): void
    {
        if (! Schema::hasTable('apbdes')) {
            return;
        }

        $rows = [
            ['year' => (int) now()->format('Y') - 1, 'pendapatan' => 1750000000, 'belanja' => 1635000000, 'pembiayaan_penerimaan' => 150000000, 'pembiayaan_pengeluaran' => 70000000, 'is_active' => false],
            ['year' => (int) now()->format('Y'), 'pendapatan' => 1925000000, 'belanja' => 1810000000, 'pembiayaan_penerimaan' => 120000000, 'pembiayaan_pengeluaran' => 55000000, 'is_active' => true],
        ];

        foreach ($rows as $row) {
            Apbdes::query()->updateOrCreate(['year' => $row['year']], $row);
        }
    }

    private function seedBansos(array $hamlets): void
    {
        if (! Schema::hasTable('social_assistance_programs')) {
            return;
        }

        $programs = [
            ['name' => 'BLT Dana Desa', 'quota' => 45, 'period' => 'Tahap 1', 'benefit_type' => 'cash', 'amount' => 300000],
            ['name' => 'Bantuan Pangan Sembako', 'quota' => 60, 'period' => 'Tahap 1', 'benefit_type' => 'goods', 'amount' => 0],
            ['name' => 'Bantuan Lansia Rentan', 'quota' => 25, 'period' => 'Tahap 1', 'benefit_type' => 'cash', 'amount' => 400000],
        ];

        foreach ($programs as $pIndex => $seed) {
            $program = SocialAssistanceProgram::query()->updateOrCreate(
                ['name' => $seed['name'], 'year' => (int) now()->format('Y')],
                [
                    'period' => $seed['period'],
                    'description' => 'Program bantuan sosial prioritas pemerintah desa.',
                    'quota' => $seed['quota'],
                    'start_date' => Carbon::now()->startOfYear()->addMonths($pIndex)->toDateString(),
                    'end_date' => Carbon::now()->startOfYear()->addMonths($pIndex + 2)->toDateString(),
                    'is_active' => $pIndex === 0,
                ]
            );

            if (! Schema::hasTable('social_assistance_recipients')) {
                continue;
            }

            for ($i = 1; $i <= min($seed['quota'], 18); $i++) {
                $hamlet = $hamlets[($i + $pIndex) % max(count($hamlets), 1)] ?? null;
                SocialAssistanceRecipient::query()->updateOrCreate(
                    [
                        'social_assistance_program_id' => $program->id,
                        'nik' => '17040'.str_pad((string) ($pIndex * 100 + $i), 10, '0', STR_PAD_LEFT),
                    ],
                    [
                        'hamlet_id' => $hamlet?->id,
                        'name' => 'Penerima '.$program->id.'-'.$i,
                        'kk_number' => '17040'.str_pad((string) ($pIndex * 100 + $i), 10, '0', STR_PAD_LEFT),
                        'address' => ($hamlet?->name ?? 'Dusun 1').' Desa Tanjung Beringin',
                        'amount' => $seed['amount'],
                        'benefit_type' => $seed['benefit_type'],
                        'item_description' => $seed['benefit_type'] === 'goods' ? 'Beras 10kg + minyak 2L + gula 1kg' : null,
                        'unit' => $seed['benefit_type'] === 'goods' ? 'paket' : null,
                        'quantity' => $seed['benefit_type'] === 'goods' ? 1 : null,
                        'phone' => '0812739900'.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                        'verification_status' => 'verified',
                        'distribution_status' => $i % 3 === 0 ? 'distributed' : 'ready',
                        'distributed_at' => $i % 3 === 0 ? Carbon::now()->subDays($i)->toDateString() : null,
                        'receiver_name' => $i % 3 === 0 ? 'Penerima '.$i : null,
                        'notes' => 'Data penerima hasil musyawarah desa.',
                    ]
                );
            }
        }
    }

    private function seedStunting(array $hamlets): void
    {
        if (! Schema::hasTable('stunting_records')) {
            return;
        }

        $year = (int) now()->format('Y');
        for ($i = 1; $i <= 12; $i++) {
            $hamlet = $hamlets[$i % max(count($hamlets), 1)] ?? null;
            StuntingRecord::query()->updateOrCreate(
                ['child_nik' => '17050'.str_pad((string) $i, 10, '0', STR_PAD_LEFT)],
                [
                    'year' => $year,
                    'hamlet_id' => $hamlet?->id,
                    'child_name' => 'Balita '.$i,
                    'parent_name' => 'Orang Tua '.$i,
                    'gender' => $i % 2 === 0 ? 'L' : 'P',
                    'birth_date' => Carbon::now()->subMonths(18 + $i)->toDateString(),
                    'age_in_months' => 18 + $i,
                    'height_cm' => 73 + $i,
                    'weight_kg' => 8.2 + ($i * 0.25),
                    'stunting_status' => $i % 5 === 0 ? 'stunting' : ($i % 4 === 0 ? 'berisiko' : 'normal'),
                    'nutrition_status' => $i % 6 === 0 ? 'kurang' : 'baik',
                    'notes' => 'Pemantauan rutin posyandu.',
                    'is_active' => true,
                ]
            );
        }
    }

    private function seedIdm(): void
    {
        if (! Schema::hasTable('idm_summaries')) {
            return;
        }

        $summary = IdmSummary::query()->updateOrCreate(
            ['year' => (int) now()->format('Y')],
            [
                'iks_score' => 0.8125,
                'ike_score' => 0.7360,
                'ikl_score' => 0.8640,
                'idm_score' => 0.8042,
                'idm_status' => 'Maju',
                'target_status' => 'Mandiri',
                'minimal_target_score' => 0.8156,
                'additional_score_needed' => 0.0114,
                'description' => 'Ringkasan IDM tahunan untuk pemantauan pembangunan desa.',
                'is_active' => true,
            ]
        );

        if (! Schema::hasTable('idm_indicators')) {
            return;
        }

        $indicatorSeeds = [
            ['category' => 'IKS', 'indicator_no' => 1, 'indicator_name' => 'Akses layanan kesehatan', 'score' => 5, 'value' => 0.0000],
            ['category' => 'IKS', 'indicator_no' => 2, 'indicator_name' => 'Akses pendidikan dasar', 'score' => 4, 'value' => 0.0160],
            ['category' => 'IKE', 'indicator_no' => 1, 'indicator_name' => 'Aktivitas ekonomi lokal', 'score' => 4, 'value' => 0.0180],
            ['category' => 'IKE', 'indicator_no' => 2, 'indicator_name' => 'Keterjangkauan pasar', 'score' => 3, 'value' => 0.0310],
            ['category' => 'IKL', 'indicator_no' => 1, 'indicator_name' => 'Kualitas lingkungan', 'score' => 5, 'value' => 0.0000],
            ['category' => 'IKL', 'indicator_no' => 2, 'indicator_name' => 'Kesiapsiagaan bencana', 'score' => 4, 'value' => 0.0120],
        ];

        foreach ($indicatorSeeds as $i => $seed) {
            IdmIndicator::query()->updateOrCreate(
                [
                    'idm_summary_id' => $summary->id,
                    'category' => $seed['category'],
                    'indicator_no' => $seed['indicator_no'],
                ],
                [
                    'indicator_name' => $seed['indicator_name'],
                    'score' => $seed['score'],
                    'description' => 'Indikator penilaian IDM desa.',
                    'activity' => 'Penguatan intervensi program sesuai domain indikator.',
                    'value' => $seed['value'],
                    'executor_central' => null,
                    'executor_province' => 'OPD Provinsi',
                    'executor_regency' => 'OPD Kabupaten',
                    'executor_village' => 'Pemerintah Desa',
                    'executor_csr' => 'Mitra CSR',
                    'executor_other' => 'Komunitas',
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ]
            );
        }
    }

    private function seedSdgs(): void
    {
        if (! Schema::hasTable('sdgs_summaries')) {
            return;
        }

        $this->call(SdgsGoalSeeder::class);
        $summary = SdgsSummary::query()->updateOrCreate(
            ['year' => (int) now()->format('Y')],
            [
                'average_score' => 72.45,
                'total_good' => 8,
                'total_medium' => 7,
                'total_priority' => 3,
                'notes' => 'Pemutakhiran SDGs desa berbasis data lapangan.',
                'is_active' => true,
            ]
        );

        if (! Schema::hasTable('sdgs_goal_values')) {
            return;
        }

        $goals = SdgsGoal::query()->where('is_active', true)->orderBy('goal_number')->get();
        foreach ($goals as $i => $goal) {
            $score = 58 + (($i * 3) % 38);
            $status = SdgsGoalValue::resolveStatus($score);
            SdgsGoalValue::query()->updateOrCreate(
                ['sdgs_summary_id' => $summary->id, 'sdgs_goal_id' => $goal->id],
                [
                    'score' => $score,
                    'achievement_percent' => min(100, $score + 6),
                    'status' => $status,
                    'short_description' => 'Capaian indikator SDGs untuk tujuan '.$goal->goal_number.'.',
                    'sort_order' => $goal->goal_number,
                    'is_active' => true,
                ]
            );
        }

        $summary->recalculate();
    }
}
