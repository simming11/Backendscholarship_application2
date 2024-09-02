<?php

namespace Database\Factories;

use App\Models\ScholarshipType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScholarshipTypeFactory extends Factory
{
    protected $model = ScholarshipType::class;

    public function definition()
    {
        return [
            'TypeName' => $this->faker->unique()->words(3, true),
        ];
    }

    public function internal()
    {
        return $this->state([
            'TypeID' => 1,
            'TypeName' => 'ภายใน',
        ]);
    }

    public function external()
    {
        return $this->state([
            'TypeID' => 2,
            'TypeName' => 'ภายนอก',
        ]);
    }
}
