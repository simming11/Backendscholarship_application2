<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sibling;

class SiblingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้างข้อมูลจำลองสำหรับ Sibling
        Sibling::factory()->count(2)->create(); // สร้าง 10 records
    }
}
