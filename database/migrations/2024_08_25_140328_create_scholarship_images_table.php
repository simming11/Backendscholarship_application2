<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('scholarship_images', function (Blueprint $table) {
            $table->id('ImageID');
            $table->unsignedBigInteger('ScholarshipID');
            $table->string('ImagePath');
            $table->string('Description')->nullable();
            $table->timestamps();  // This will add created_at and updated_at columns

            // Foreign key constraint, linking to the scholarships table
            $table->foreign('ScholarshipID')
                  ->references('ScholarshipID')
                  ->on('scholarships')
                  ->onDelete('cascade'); // Cascade delete, so related images are deleted when a scholarship is deleted
        });
    }

    public function down()
    {
        Schema::dropIfExists('scholarship_images');
    }
};
