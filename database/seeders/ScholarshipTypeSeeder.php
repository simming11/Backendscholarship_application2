<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipType;

class ScholarshipTypeSeeder extends Seeder
{
    public function run()
    {
        ScholarshipType::factory()->create();
    }
}
