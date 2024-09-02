<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\ApplicationInternal;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition()
    {
        return [
            'ApplicationID' => ApplicationInternal::factory()->create()->ApplicationID, // สุ่ม ApplicationID ที่มีอยู่
            'AcademicYear' => $this->faker->year, // ปีการศึกษา
            'ActivityName' => $this->faker->sentence(3), // ชื่อกิจกรรมที่ทำ
            'Position' => $this->faker->randomElement(['หัวหน้า', 'สมาชิก', 'ผู้จัดการ', null]), // ตำแหน่งในกิจกรรม
        ];
    }
}
