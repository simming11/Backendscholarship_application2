<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'StudentID' => $this->faker->unique()->numerify('##########'), // 10-digit student ID
            'Password' => Hash::make('password'), // Default password, hashed
            'FirstName' => $this->faker->firstName,
            'LastName' => $this->faker->lastName,
            'Email' => $this->faker->unique()->safeEmail,
            'GPA' => $this->faker->randomFloat(2, 0, 4), // GPA between 0.00 and 4.00
            'Year_Entry' => $this->faker->year,
            'Religion' => $this->faker->randomElement(['Buddhism', 'Christianity', 'Islam', 'Hinduism', 'Other']), // Random religion
            'PrefixName' => $this->faker->randomElement(['Mr.', 'Ms.', 'Dr.', 'Prof.']), // Random prefix
            'Phone' => $this->faker->numerify('##########'), // Generate a 10-digit phone number
            'DOB' => $this->faker->date(), // Random date of birth
            'Course' => $this->faker->randomElement([
                'B.Sc. in Computer Science', 
                'B.Sc. in Mathematics', 
                'B.Sc. in Environmental Science',
                'B.Sc. in Chemistry',
                'B.Sc. in Fisheries Science',
                'B.Sc. in Biological Science',
                'B.Sc. in Physics & Nanotechnology'
            ]), // Random course
        ];
    }
}
