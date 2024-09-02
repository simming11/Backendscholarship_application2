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
        Schema::create('scholarship_histories', function (Blueprint $table) {
            $table->id('HistoriesID'); // Primary key
            $table->string('ScholarshipName', 100)->nullable(); // ชื่อทุนที่ได้รับ, allows NULL
            $table->decimal('AmountReceived', 10, 2)->nullable(); // จำนวนเงินที่ได้รับ, allows NULL
            $table->string('AcademicYear')->nullable(); // ปีการศึกษา, allows NULL
            $table->string('ApplicationID', 15)->nullable(); // Foreign key column, allows NULL
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign key references
            $table->foreign('ApplicationID')->references('ApplicationID')->on('application_internals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_histories');
    }
};

