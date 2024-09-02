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
        Schema::create('scholarship_documents', function (Blueprint $table) {
            $table->id('DocumentID'); // Renamed the primary key to DocumentID
            $table->unsignedBigInteger('ScholarshipID');
            $table->longText('DocumentText')->nullable(); // Renamed the text field to DocumentText
            $table->boolean('IsActive')->default(false);
            $table->timestamps(); // Adds created_at and updated_at columns
            
            $table->foreign('ScholarshipID')->references('ScholarshipID')->on('scholarships')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('scholarship_documents');
    }
};
