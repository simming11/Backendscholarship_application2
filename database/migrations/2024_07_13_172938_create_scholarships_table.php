<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id('ScholarshipID');
            $table->string('ScholarshipName');
            $table->integer('Year'); 
            $table->integer('Num_scholarship'); 
            $table->float('Minimum_GPA');
            $table->string('YearLevel')->nullable();
            $table->unsignedBigInteger('TypeID');
            $table->date('StartDate');
            $table->date('EndDate');
            $table->string('CreatedBy');
            $table->timestamps();

            $table->foreign('TypeID')->references('TypeID')->on('scholarship_types')->onDelete('cascade');
            $table->foreign('CreatedBy')->references('AcademicID')->on('academics')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('scholarships');
    }
};
