<?php

namespace Database\Seeders;

use App\Models\ScholarshipDocument;
use Illuminate\Database\Seeder;

class ScholarshipDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ScholarshipDocument::factory()->create();
    }
}
