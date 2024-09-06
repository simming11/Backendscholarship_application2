<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScholarshipType;

class ScholarshipTypeSeeder extends Seeder
{
    public function run()
    {
        // Create scholarship types for "ภายใน" (internal) and "ภายนอก" (external)
        ScholarshipType::create([
            'TypeName' => 'ภายใน', // Internal
        ]);

        ScholarshipType::create([
            'TypeName' => 'ภายนอก', // External
        ]);
    }
}
