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
            $table->string('StudentID', 10);
            $table->string('LineToken');
            $table->date('SentDate');
            $table->foreign('StudentID')->references('StudentID')->on('students')->onDelete('cascade');
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
