<?php

namespace Database\Factories;

use App\Models\ApplicationsExternal;
use App\Models\Student;
use App\Models\ScholarshipType;
use App\Models\Scholarship;
use App\Models\ApplicationFile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationsExternalFactory extends Factory
{
    protected $model = ApplicationsExternal::class;

    public function definition()
    {
        return [
            'StudentID' => Student::factory(),
            'Status' => 'pending',
            'ScholarshipID' => Scholarship::factory(),
            'ApplicationDate' => $this->faker->date(),
        ];
    }

    public function withApplicationFile()
    {
        return $this->afterCreating(function (ApplicationsExternal $application) {
            ApplicationFile::factory()->create([
                'ApplicationID' => $application->Application_EtID, // ใช้ Application_EtID จาก ApplicationsExternal
                'DocumentName' => $this->faker->sentence(3),
                'DocumentType' => $this->faker->randomElement(['ไฟล์', 'รูปภาพ', 'ไปสมัคร', 'transcript']),
                'FilePath' => $this->faker->filePath(),
            ]);
        });
    }
}
