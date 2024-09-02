<?php

namespace Database\Factories;

use App\Models\LineNotify;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;

class LineNotifyFactory extends Factory
{
    protected $model = LineNotify::class;

    public function definition()
    {
        return [
            'StudentID' => Student::factory(),
            'LineToken' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'SentDate' => $this->faker->date,
        ];
    }
}
