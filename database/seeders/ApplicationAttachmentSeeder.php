<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApplicationAttachment;

class ApplicationAttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApplicationAttachment::factory()->create();
    }
}
