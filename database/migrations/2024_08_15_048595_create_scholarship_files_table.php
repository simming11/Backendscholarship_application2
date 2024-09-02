<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('scholarship_files', function (Blueprint $table) {
            $table->id('FileID');
            $table->unsignedBigInteger('ScholarshipID');
            $table->string('FileType'); // e.g., 'document', 'image'
            $table->string('FilePath');
            $table->string('Description')->nullable();
            $table->timestamps();  // Add this line to create the timestamp columns

            $table->foreign('ScholarshipID')->references('ScholarshipID')->on('scholarships')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('scholarship_files');
    }
};
