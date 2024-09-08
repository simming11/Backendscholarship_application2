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
        Schema::create('line_notifies', function (Blueprint $table) {
            $table->id('LineNotifyID');
            $table->string('AcademicID', 13)->nullable();
            $table->string('LineToken')->nullable();
            $table->string('notify_client_id')->nullable(); // Column for storing the client ID
            $table->string('client_secret')->nullable();    // Column for storing the client secret
            $table->date('SentDate')->nullable();
            $table->foreign('AcademicID')->references('AcademicID')->on('academics')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_notifies');
    }
};
