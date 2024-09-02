<?php

namespace Database\Factories;

use App\Models\Scholarship;
use App\Models\ScholarshipQualification;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipQualificationFactory extends Factory
{
    protected $model = ScholarshipQualification::class;

    public function definition()
    {
        return [
            // Ensure that the ScholarshipID exists in the scholarships table
            'ScholarshipID' => Scholarship::factory(), 
            'QualificationText' => $this->faker->text(200),
            'IsActive' => $this->faker->boolean,
        ];
    }
}
