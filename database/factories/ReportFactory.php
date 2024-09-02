<?php

namespace Database\Factories;

use App\Models\ApplicationInternal;
use App\Models\ApplicationsExternal;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition()
    {
        return [
            'ReportContent' => $this->faker->paragraph,
            'CreatedDate' => $this->faker->date(),
            'ApplicationID' => $this->faker->optional()->randomElement(ApplicationInternal::pluck('ApplicationID')->toArray()),
            'Application_EtID' => $this->faker->optional()->randomElement(ApplicationsExternal::pluck('Application_EtID')->toArray()),
        ];
    }
}
