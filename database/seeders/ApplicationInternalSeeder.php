<?php

namespace Database\Seeders;

use App\Models\ApplicationInternal;
use Illuminate\Database\Seeder;

class ApplicationInternalSeeder extends Seeder
{
    public function run()
    {
        ApplicationInternal::factory()->withRelatedData()->create();
    }
}
