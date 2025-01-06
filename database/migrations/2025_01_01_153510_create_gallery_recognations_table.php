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
        Schema::create('gallery_recognations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students_databases')->nullable()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers_databases')->nullable()->cascadeOnDelete();
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_recognations');
    }
};
