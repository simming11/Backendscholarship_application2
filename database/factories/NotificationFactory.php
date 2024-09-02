<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'StudentID' => Student::factory(),
            'Message' => $this->faker->sentence,
            'SentDate' => $this->faker->date,
        ];
    }
}
