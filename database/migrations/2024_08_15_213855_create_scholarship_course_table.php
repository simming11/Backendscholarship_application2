<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scholarship_course', function (Blueprint $table) {
            $table->bigIncrements('CourseID'); // ใช้ 'CourseID' เป็น primary key
            $table->unsignedBigInteger('ScholarshipID'); // Foreign key reference to scholarships table
            $table->string('CourseName');
            $table->timestamps();

            // Define the foreign key constraint
            $table->foreign('ScholarshipID')->references('ScholarshipID')->on('scholarships')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_course');
    }
};

