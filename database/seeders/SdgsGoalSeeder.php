<?php

namespace Database\Seeders;

use App\Models\SdgsGoal;
use Illuminate\Database\Seeder;

class SdgsGoalSeeder extends Seeder
{
    public function run(): void
    {
        $goals = [
            1 => 'Desa Tanpa Kemiskinan',
            2 => 'Desa Tanpa Kelaparan',
            3 => 'Desa Sehat dan Sejahtera',
            4 => 'Pendidikan Desa Berkualitas',
            5 => 'Keterlibatan Perempuan Desa',
            6 => 'Desa Layak Air Bersih dan Sanitasi',
            7 => 'Desa Berenergi Bersih dan Terbarukan',
            8 => 'Pertumbuhan Ekonomi Desa Merata',
            9 => 'Infrastruktur dan Inovasi Desa Sesuai Kebutuhan',
            10 => 'Desa Tanpa Kesenjangan',
            11 => 'Kawasan Permukiman Desa Aman dan Nyaman',
            12 => 'Konsumsi dan Produksi Desa Sadar Lingkungan',
            13 => 'Desa Tanggap Perubahan Iklim',
            14 => 'Desa Peduli Lingkungan Laut',
            15 => 'Desa Peduli Lingkungan Darat',
            16 => 'Desa Damai Berkeadilan',
            17 => 'Kemitraan untuk Pembangunan Desa',
            18 => 'Kelembagaan Desa Dinamis dan Budaya Desa Adaptif',
        ];

        foreach ($goals as $number => $name) {
            SdgsGoal::updateOrCreate(
                ['goal_number' => $number],
                [
                    'goal_name' => $name,
                    'sort_order' => $number,
                    'is_active' => true,
                ]
            );
        }
    }
}