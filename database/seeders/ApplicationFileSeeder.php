<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApplicationFile;

class ApplicationFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้างข้อมูลจำลองสำหรับ ApplicationFile
        ApplicationFile::factory()->create(); // สร้าง 10 records
    }
}
