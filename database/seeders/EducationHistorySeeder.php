<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EducationHistory;

class EducationHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้างข้อมูลจำลองสำหรับ EducationHistory
        EducationHistory::factory()->create(); // สร้าง 10 records
    }
}
