<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Address;
use App\Models\ApplicationFile;
use App\Models\ApplicationInternal;
use App\Models\EducationHistory;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Scholarship;
use App\Models\ScholarshipHistory;
use App\Models\Sibling;
use App\Models\WorkExperience;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationInternalFactory extends Factory
{
    protected $model = ApplicationInternal::class;

    public function definition()
    {
        return [
            'StudentID' => Student::factory()->create()->StudentID,
            'ScholarshipID' => Scholarship::factory()->create()->ScholarshipID,
            'ApplicationDate' => $this->faker->date(),
            'Status' => 'pending',
            'MonthlyIncome' => $this->faker->randomFloat(2, 0, 10000),
            'MonthlyExpenses' => $this->faker->randomFloat(2, 0, 10000),
            'NumberOfSiblings' => $this->faker->numberBetween(0, 10),
            'NumberOfSisters' => $this->faker->numberBetween(0, 5),
            'NumberOfBrothers' => $this->faker->numberBetween(0, 5),
        ];
    }

    public function withRelatedData()
    {
        return $this->afterCreating(function (ApplicationInternal $application) {
            ApplicationFile::factory()->create(['ApplicationID' => $application->ApplicationID]);
            Address::factory()->create(['ApplicationID' => $application->ApplicationID]);
            Sibling::factory()->create(['ApplicationID' => $application->ApplicationID]);
            ScholarshipHistory::factory()->create(['ApplicationID' => $application->ApplicationID]);
            Guardian::factory()->create(['ApplicationID' => $application->ApplicationID]);
            Activity::factory()->create(['ApplicationID' => $application->ApplicationID]);
            EducationHistory::factory()->create(['ApplicationID' => $application->ApplicationID]);
            WorkExperience::factory()->create(['ApplicationID' => $application->ApplicationID]);
        });
    }
}
