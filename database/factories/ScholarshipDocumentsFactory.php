<?php

namespace Database\Factories;

use App\Models\Scholarship;
use App\Models\ScholarshipDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipDocumentFactory extends Factory
{
    protected $model = ScholarshipDocument::class;

    public function definition()
    {
        return [
            'ScholarshipID' => Scholarship::factory(), 
            'DocumentText' => $this->faker->paragraphs(3, true), // Adjusted to match the new context
            'IsActive' => $this->faker->boolean,
        ];
    }
}
