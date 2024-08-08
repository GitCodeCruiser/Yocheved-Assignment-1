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
        Schema::create('student_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->integer('day')->comment(
                "
            Sunday => 0,   
            Monday => 1,   
            Tuesday => 2,  
            Wednesday => 3,
            Thursday => 4, 
            Friday => 5,   
            Saturday => 6"
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_availabilities');
    }
};
