<?php

namespace Database\Factories;

use App\Models\Scholarship;
use App\Models\ScholarshipFile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipFileFactory extends Factory
{
    protected $model = ScholarshipFile::class;

    public function definition()
    {
        return [
            'ScholarshipID' => Scholarship::factory(), 
            'FileType' => $this->faker->randomElement(['document', 'image']),
            'FilePath' => $this->faker->filePath(),
            'Description' => $this->faker->sentence
        ];
    }
}
