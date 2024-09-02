<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id('ActivitiesID'); // Primary key
            $table->string('AcademicYear')->nullable();
            $table->string('ActivityName', 100)->nullable();
            $table->string('Position', 50)->nullable();
            $table->string('ApplicationID', 15)->nullable(); // Foreign key column
            $table->timestamps();

            // Foreign key references
            $table->foreign('ApplicationID')->references('ApplicationID')->on('application_internals')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
};
