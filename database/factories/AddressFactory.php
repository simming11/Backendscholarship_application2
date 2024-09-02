<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'ApplicationID' =>ApplicationInternalFactory::factory(),
            'AddressLine' => $this->faker->address, // ที่อยู่ เช่น 44/2 หมู่ 2
            'Subdistrict' => $this->faker->citySuffix, // ตำบล
            'District' => $this->faker->city, // อำเภอ
            'PostalCode' => $this->faker->postcode, // รหัสไปรษณีย์
            'Type' => $this->faker->randomElement(['ที่อยู่ปัจจุบัน', 'ที่อยู่ตามบัตรประชาชน']), // ประเภทที่อยู่
        ];
    }
}

