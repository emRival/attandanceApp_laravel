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
        Schema::create('teachers_attandances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers_databases')->cascadeOnDelete();
            $table->foreignId('times_config_id')->constrained('times_configs')->cascadeOnDelete();
            $table->date('date');
            $table->time('time')->nullable();
            $table->enum('status', ['attend', 'late', 'absent'])->default('absent');
            $table->string('captured_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers_attandances');
    }
};