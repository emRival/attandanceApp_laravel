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
        Schema::create('students_attandances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students_databases')->cascadeOnDelete();
            $table->foreignId('times_config_id')->constrained('times_configs')->cascadeOnDelete();
            $table->date('date');
            $table->time('time')->nullable();
            $table->enum('status', ['attend', 'late', 'absent'])->default('absent');
            $table->longText('captured_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students_attandances');
    }
};