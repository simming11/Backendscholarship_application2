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
        Schema::create('application_files', function (Blueprint $table) {
            $table->id('FilesID'); // ใช้ auto-increment primary key
            $table->string('DocumentName')->nullable(); // ชื่อเอกสาร
            $table->string('DocumentType')->nullable(); // ประเภทเอกสาร
            $table->string('FilePath')->nullable(); // ที่อยู่ไฟล์
            $table->string('ApplicationID', 15)->nullable(); // Foreign key column
            $table->string('Application_EtID', 15)->nullable(); // Foreign key column
            $table->timestamps();

            // Foreign key references
            $table->foreign('ApplicationID')->references('ApplicationID')->on('application_internals')->onDelete('cascade');
            $table->foreign('Application_EtID')->references('Application_EtID')->on('applications_external')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_files');
    }
};
