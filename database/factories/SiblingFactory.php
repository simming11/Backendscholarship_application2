<?php

namespace Database\Factories;

use App\Models\ApplicationInternal;
use App\Models\Sibling;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiblingFactory extends Factory
{
    protected $model = Sibling::class;

    public function definition()
    {
        return [
            'ApplicationID' => ApplicationInternal::factory()->create()->ApplicationID, // สุ่ม ApplicationID ที่มีอยู่
            'PrefixName' => $this->faker->randomElement(['นาย', 'นาง', 'นางสาว']), // คำนำหน้า
            'Fname' => $this->faker->firstName, // ชื่อของพี่น้อง
            'Lname' => $this->faker->lastName, // นามสกุลของพี่น้อง
            'Occupation' => $this->faker->jobTitle, // อาชีพของพี่น้อง
            'EducationLevel' => $this->faker->randomElement(['ปริญญาตรี', 'ปริญญาโท', 'ปริญญาเอก']), // ระดับการศึกษาของพี่น้อง
            'Income' => $this->faker->randomFloat(2, 10000, 50000), // รายได้ของพี่น้อง
            'Status' => $this->faker->randomElement(['สมรส', 'โสด']), // สถานภาพของพี่น้อง
        ];
    }
}
