<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DemoVillageIdentitySeeder::class,
            DemoProfileSeeder::class,
            DemoContentPostSeeder::class,
            DemoDocsSeeder::class,
            DemoCommerceTourismSeeder::class,
            DemoOrganizationSeeder::class,
            DemoServiceInteractionSeeder::class,
            DemoInfographicsSeeder::class,
        ]);
    }
}
