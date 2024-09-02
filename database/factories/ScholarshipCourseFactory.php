<?php
namespace Database\Factories;

use App\Models\ScholarshipCourse;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipCourseFactory extends Factory
{
    protected $model = ScholarshipCourse::class;

    public function definition()
    {
        return [
            'ScholarshipID' => \App\Models\Scholarship::inRandomOrder()->first()->ScholarshipID, // Ensures a valid ScholarshipID
            'CourseName' => $this->faker->words(3, true), // Generates a string with 3 words
        ];
    }
}
