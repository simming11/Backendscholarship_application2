<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Academic;

class AcademicSeeder extends Seeder
{
    public function run()
    {
        Academic::factory()->create();

    }
}
