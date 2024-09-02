<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('application_internals', function (Blueprint $table) {
            $table->string('ApplicationID', 15)->primary(); // Primary key
            $table->string('StudentID', 10);
            $table->unsignedBigInteger('ScholarshipID');
            $table->date('ApplicationDate');
            $table->string('Status', 20)->default('pending');
            $table->float('MonthlyIncome')->nullable();
            $table->float('MonthlyExpenses')->nullable();
            $table->integer('NumberOfSiblings')->nullable();
            $table->integer('NumberOfSisters')->nullable();
            $table->integer('NumberOfBrothers')->nullable();
            $table->float('GPAYear1', 3, 2);
            $table->float('GPAYear2', 3, 2);
            $table->float('GPAYear3', 3, 2);
            $table->string('AdvisorName', 100);
            $table->timestamps();
            
            // Foreign key references
            $table->foreign('StudentID')->references('StudentID')->on('students')->onDelete('cascade');
            $table->foreign('ScholarshipID')->references('ScholarshipID')->on('scholarships')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('application_internals');
    }
};
