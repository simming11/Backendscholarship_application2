<?php

namespace Database\Factories;

use App\Models\ApplicationInternal;
use App\Models\WorkExperience;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkExperienceFactory extends Factory
{
    protected $model = WorkExperience::class;

    public function definition()
    {
        return [
            'ApplicationID' => ApplicationInternal::factory()->create()->ApplicationID, // สุ่ม ApplicationID ที่มีอยู่
            'Name' => $this->faker->jobTitle, // สุ่มชื่อของงาน
            'JobType' => $this->faker->word, // สุ่มลักษณะงานที่ทำ
            'Duration' => $this->faker->randomElement(['3 months', '7 days', '1 year']), // สุ่มระยะเวลาการทำงาน
            'Earnings' => $this->faker->randomFloat(2, 1000, 10000), // สุ่มจำนวนเงินที่ได้รับ
        ];
    }
}
