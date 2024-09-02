<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->string('StudentID', 10)->primary();
            $table->string('Password');
            $table->string('FirstName', 30);
            $table->string('LastName', 30);
            $table->string('Email', 50)->unique();
            $table->float('GPA', 3, 2)->nullable(); // GPA column with precision 3 and scale 2
            $table->unsignedInteger('Year_Entry')->nullable(); // Column for the year of entry
            $table->string('Religion', 50)->nullable(); // Religion column
            $table->string('PrefixName', 10)->nullable(); // Prefix name column
            $table->string('Phone', 15)->nullable(); // Phone column
            $table->date('DOB')->nullable(); // Date of birth column
            $table->string('Course', 50)->nullable(); // Course column
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
