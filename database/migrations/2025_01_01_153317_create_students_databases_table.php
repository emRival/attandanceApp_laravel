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
        Schema::create('students_databases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('class');
            $table->string('nis')->nullable();
            $table->string('position')->default('student');
            $table->boolean('is_active')->default(true);
            $table->longText('face')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students_databases');
    }
};