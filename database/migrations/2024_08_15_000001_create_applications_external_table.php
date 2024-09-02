<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('applications_external', function (Blueprint $table) {
            $table->string('Application_EtID', 15)->primary(); // ตั้งให้เป็น Primary Key และเป็น string
            $table->string('StudentID', 10);
            $table->unsignedBigInteger('ScholarshipID');
            $table->date('ApplicationDate'); // Add this line for ApplicationDate
            $table->string('Status', 20)->default('pending');
            $table->timestamps();
            $table->foreign('StudentID')->references('StudentID')->on('students')->onDelete('cascade');
            $table->foreign('ScholarshipID')->references('ScholarshipID')->on('scholarships')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications_external');
    }
};

