<?php

namespace Database\Factories;

use App\Models\Academic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AcademicFactory extends Factory
{
    protected $model = Academic::class;

    public function definition()
    {
        return [
            'AcademicID' => $this->faker->unique()->numerify('#############'), // 13 digits
            'FirstName' => $this->faker->firstName,
            'LastName' => $this->faker->lastName,
            'Position' => $this->faker->jobTitle,
            'Email' => $this->faker->unique()->safeEmail,
            'Phone' => $this->faker->phoneNumber, // Ensure phone numbers fit within 20 characters
            'Password' => Hash::make('password'), // Encrypted password
            'remember_token' => \Illuminate\Support\Str::random(10),
        ];
    }
}
