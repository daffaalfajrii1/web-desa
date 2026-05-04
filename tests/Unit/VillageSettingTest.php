<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\VillageSetting;
use Tests\TestCase;

class VillageSettingTest extends TestCase
{
    public function test_village_head_name_prioritizes_selected_employee(): void
    {
        $setting = new VillageSetting([
            'village_head_name_manual' => 'Nama Manual',
            'head_name' => 'Nama Lama',
        ]);
        $setting->setRelation('villageHeadEmployee', new Employee(['name' => 'Nama Pegawai']));

        $this->assertSame('Nama Pegawai', $setting->village_head_name);
    }

    public function test_village_head_name_falls_back_to_manual_name(): void
    {
        $setting = new VillageSetting([
            'village_head_name_manual' => 'Nama Manual',
            'head_name' => 'Nama Lama',
        ]);

        $this->assertSame('Nama Manual', $setting->village_head_name);
    }
}
