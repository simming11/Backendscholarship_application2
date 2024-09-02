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
        Schema::create('scholarship_qualifications', function (Blueprint $table) {
            $table->id('QualificationID');
            $table->unsignedBigInteger('ScholarshipID');
            $table->longText('QualificationText')->nullable();
            $table->boolean('IsActive')->default(false);
            $table->timestamps(); // Adds created_at and updated_at columns
            $table->foreign('ScholarshipID')->references('ScholarshipID')->on('scholarships')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_qualifications');
    }
};
