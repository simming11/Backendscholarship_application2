<?php

namespace Database\Factories;

use App\Models\ApplicationInternal;
use App\Models\ScholarshipHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipHistoryFactory extends Factory
{
    protected $model = ScholarshipHistory::class;

    public function definition()
    {
        return [
            'ApplicationID' => ApplicationInternal::factory()->create()->ApplicationID, // สุ่ม ApplicationID ที่มีอยู่
            'ScholarshipName' => $this->faker->sentence(3), // ชื่อทุนที่ได้รับ
            'AmountReceived' => $this->faker->randomFloat(2, 10000, 50000), // จำนวนเงินที่ได้รับ
            'AcademicYear' => $this->faker->year, // ปีการศึกษา
        ];
    }
}
