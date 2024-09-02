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
        Schema::create('guardians', function (Blueprint $table) {
            $table->id('GuardiansID'); // Primary key
            $table->string('PrefixName', 10)->nullable(); // Prefix name column, can be null
            $table->string('FirstName', 50)->nullable(); // ชื่อ, can be null
            $table->string('LastName', 50)->nullable(); // นามสกุล, can be null
            $table->string('Type')->nullable(); // ประเภท มี พ่อ, แม่, ผู้อุปการะ, can be null
            $table->string('Occupation', 100)->nullable(); // อาชีพ, can be null
            $table->decimal('Income', 10, 2)->nullable(); // รายได้, can be null
            $table->integer('Age')->nullable(); // อายุ, can be null
            $table->string('Status', 50)->nullable(); // สถานภาพ, สามารถเป็น null
            $table->string('Workplace', 100)->nullable(); // สถานที่ทำงาน, can be null
            $table->string('ApplicationID', 15)->nullable(); // Foreign key column, can be null
            $table->string('Phone', 15)->nullable(); // Phone column, can be null
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
        Schema::dropIfExists('guardians');
    }
};
