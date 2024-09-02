<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipHistory;

class ScholarshipHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้างข้อมูลจำลองสำหรับ ScholarshipHistory
        ScholarshipHistory::factory()->create(); // สร้าง 10 records
    }
}
