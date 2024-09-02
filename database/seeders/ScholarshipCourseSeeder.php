<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipCourse;

class ScholarshipCourseSeeder extends Seeder
{
    public function run()
    {
        ScholarshipCourse::factory()->create();
    }
}
