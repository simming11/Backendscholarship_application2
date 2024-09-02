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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id('AddressesID'); // Primary key
            $table->string('AddressLine', 255); // ที่อยู่ เช่น 44/2 หมู่ 2
            $table->string('Subdistrict', 100); // ตำบล
            $table->string('province', 100); // จังหวัด
            $table->string('District', 100); // อำเภอ
            $table->string('PostalCode', 10); // รหัสไปรษณีย์
            $table->enum('Type', ['ที่อยู่ปัจจุบัน', 'ที่อยู่ตามบัตรประชาชน']); // ประเภทที่อยู่
            $table->string('ApplicationID', 15); // Foreign key column
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
        Schema::dropIfExists('addresses');
    }
};
