<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\District;
use App\Models\Subdistrict;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $province = Province::create(['name' => 'เชียงใหม่']);

        $district1 = District::create(['name' => 'เมืองเชียงใหม่', 'province_id' => $province->id]);
        $district2 = District::create(['name' => 'สันทราย', 'province_id' => $province->id]);

        Subdistrict::create(['name' => 'ช้างม่อย', 'district_id' => $district1->id]);
        Subdistrict::create(['name' => 'สันป่าเปา', 'district_id' => $district2->id]);
    }
}
