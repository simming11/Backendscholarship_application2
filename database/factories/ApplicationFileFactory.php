<?php

namespace Database\Factories;

use App\Models\ApplicationFile;
use App\Models\ApplicationInternal;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFileFactory extends Factory
{
    protected $model = ApplicationFile::class;

    public function definition()
    {
    
        return [
            'ApplicationID' => ApplicationInternal::factory()->create()->ApplicationID,
            'DocumentName' => $this->faker->sentence(3),
            'DocumentType' => $this->faker->randomElement(['ไฟล์', 'รูปภาพ', 'ไปสมัคร', 'transcript']),
            'FilePath' => $this->faker->filePath(),
        ];
    }
    
}
