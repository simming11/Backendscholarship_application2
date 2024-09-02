<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApplicationsExternal;

class ApplicationsExternalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        ApplicationsExternal::factory()->create();
    }
}
