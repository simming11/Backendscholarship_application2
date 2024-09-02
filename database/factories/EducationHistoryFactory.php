<?php

namespace Database\Factories;

use App\Models\ApplicationInternal;
use App\Models\EducationHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class EducationHistoryFactory extends Factory
{
    protected $model = EducationHistory::class;

    public function definition()
    {
        return [
            'ApplicationID' => ApplicationInternal::factory()->create()->ApplicationID, // สุ่ม ApplicationID ที่มีอยู่
            'EducationLevel' => $this->faker->randomElement(['ปริญญาตรี', 'ปริญญาโท', 'ปริญญาเอก']), // ระดับการศึกษา
            'AcademicYear' => $this->faker->year, // ปีการศึกษา
            'GPA' => $this->faker->randomFloat(2, 2.00, 4.00), // เกรดเฉลี่ยระหว่าง 2.00 - 4.00
            'AdvisorName' => $this->faker->name, // ชื่อ-สกุล อาจารย์ที่ปรึกษา
            'CourseName' => $this->faker->randomElement(['วิทยาศาสตร์คอมพิวเตอร์', 'วิศวกรรมคอมพิวเตอร์', 'วิศวกรรมศาสตร์']), // ชื่อหลักสูตร
        ];
    }
}
