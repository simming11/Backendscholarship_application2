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
        Schema::create('siblings', function (Blueprint $table) {
            $table->id('siblingsID'); // Primary key
            $table->string('ApplicationID', 15)->nullable(); // Foreign key column, can be null
            $table->string('PrefixName', 10)->nullable(); // คำนำหน้า, can be null
            $table->string('Fname', 50)->nullable(); // ชื่อ, can be null
            $table->string('Lname', 50)->nullable(); // นามสกุล, can be null
            $table->string('Occupation', 100)->nullable(); // อาชีพ, can be null
            $table->string('EducationLevel', 100)->nullable(); // ระดับการศึกษา, can be null
            $table->decimal('Income', 10, 2)->nullable(); // รายได้, can be null
            $table->enum('Status', ['สมรส', 'โสด'])->nullable(); // สถานภาพ, can be null
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
        Schema::dropIfExists('siblings');
    }
};
