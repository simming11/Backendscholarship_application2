<?php

namespace Database\Factories;

use App\Models\Scholarship;
use App\Models\Academic;
use App\Models\ScholarshipType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipFactory extends Factory
{
    protected $model = Scholarship::class;

    public function definition()
    {
        // ตรวจสอบว่ามี ScholarshipType หรือไม่ หากไม่มีก็สร้างขึ้นมา
        $scholarshipType = ScholarshipType::firstOrCreate(
            ['TypeID' => 1], // ค้นหาหรือสร้าง TypeID 1
            ['TypeName' => 'ภายใน']
        );

        if (!$scholarshipType) {
            $scholarshipType = ScholarshipType::firstOrCreate(
                ['TypeID' => 2], // ค้นหาหรือสร้าง TypeID 2
                ['TypeName' => 'ภายนอก']
            );
        }

        // สร้าง Academic ที่เกี่ยวข้อง
        $academic = Academic::factory()->create();

        return [
            'ScholarshipName' => $this->faker->words(3, true),
            'Year' => $this->faker->year,
            'Num_scholarship' => $this->faker->numberBetween(1, 10),
            'Minimum_GPA' => $this->faker->randomFloat(2, 0, 4),
            'YearLevel' => $this->faker->randomElement(['1', '2', '3', '4']),
            'TypeID' => $scholarshipType->TypeID,
            'StartDate' => $this->faker->date(),
            'EndDate' => $this->faker->date(),
            'CreatedBy' => $academic->AcademicID,
        ];
    }
}
