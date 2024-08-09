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
        Schema::create('session_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->references('id')->on('sessions');
            $table->foreignId('student_id')->references('id')->on('students');
            $table->integer('total_rating');
            $table->integer('obtained_rating');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_ratings');
    }
};
