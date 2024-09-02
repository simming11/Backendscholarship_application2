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
        Schema::create('reports', function (Blueprint $table) {
            $table->id('ReportID');
            $table->text('ReportContent');
            $table->date('CreatedDate');
            $table->string('ApplicationID', 15)->nullable(); // Foreign key column
            $table->string('Application_EtID', 15)->nullable();; // ตั้งให้เป็น Primary Key และเป็น string
            $table->timestamps();

            $table->foreign('ApplicationID')->references('ApplicationID')->on('application_internals')->onDelete('cascade');
            $table->foreign('Application_EtID')->references('Application_EtID')->on('applications_external')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
