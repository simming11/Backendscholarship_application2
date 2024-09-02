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
        Schema::create('academics', function (Blueprint $table) {
            $table->string('AcademicID', 13)->primary();
            $table->string('FirstName');
            $table->string('LastName');
            $table->string('Position')->nullable();
            $table->string('Email')->unique();
            $table->string('Phone', 20)->nullable(); // Increased length to 20
            $table->string('Password'); 
            $table->rememberToken(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academics');
    }
};
