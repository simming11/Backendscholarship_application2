<?php

namespace Database\Factories;

use App\Models\ApplicationInternal;
use App\Models\Guardian;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuardianFactory extends Factory
{
    protected $model = Guardian::class;

    public function definition()
    {
        return [
            'ApplicationID' => ApplicationInternal::factory()->create()->ApplicationID, // สุ่ม ApplicationID ที่มีอยู่
            'FirstName' => $this->faker->firstName, // ชื่อของผู้ปกครอง
            'LastName' => $this->faker->lastName, // นามสกุลของผู้ปกครอง
            'Type' => $this->faker->randomElement(['พ่อ', 'แม่', 'ผู้อุปการะ']), // ประเภทของผู้ปกครอง
            'Occupation' => $this->faker->jobTitle, // อาชีพของผู้ปกครอง
            'Income' => $this->faker->randomFloat(2, 10000, 50000), // รายได้ของผู้ปกครอง
            'Age' => $this->faker->numberBetween(30, 70), // อายุของผู้ปกครอง
            'Status' => $this->faker->randomElement(['มีชีวิต', 'ไม่มีชีวิต']), // สถานภาพของผู้ปกครอง
            'Workplace' => $this->faker->company, // สถานที่ทำงานของผู้ปกครอง
        ];
    }
}
