<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            StudentSeeder::class,           // Seed students
            AcademicSeeder::class,          // Seed academics
            ScholarshipSeeder::class,       // Seed scholarships after types and academics
            ApplicationInternalSeeder::class,
            ApplicationsExternalSeeder::class,
            ScholarshipTypeSeeder::class,  // Seed scholarship types first
            ScholarshipCourseSeeder::class, // Seed courses related to scholarships
            ScholarshipQualificationSeeder::class, // Seed qualifications related to scholarships
            ScholarshipFilesSeeder::class,  // Seed files related to scholarships
            ScholarshipDocumentSeeder::class, // Seed additional information related to scholarships
            AddressSeeder::class,           // Seed addresses for students
            GuardianSeeder::class,          // Seed guardians for students
            EducationHistorySeeder::class,  // Seed educational history for students
            WorkExperienceSeeder::class,    // Seed work experience for students
            ActivitySeeder::class,          // Seed activities for students
            NotificationSeeder::class,      // Seed notifications, probably depends on students or scholarships
            LineNotifySeeder::class,        // Seed line notifications, related to notifications
        ]);
    }
}
