<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LineNotify;

class LineNotifySeeder extends Seeder
{
    public function run()
    {
        LineNotify::factory()->create();
    }
}
