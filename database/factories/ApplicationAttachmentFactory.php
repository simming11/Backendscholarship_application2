<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\ApplicationAttachment;
use App\Models\ApplicationInternal;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationAttachmentFactory extends Factory
{
    protected $model = ApplicationAttachment::class;

    public function definition()
    {
        return [
            'ApplicationID' => ApplicationInternal::factory(),
            'FilePath' => $this->faker->file('public/storage', 'uploads', false),
        ];
    }
}
