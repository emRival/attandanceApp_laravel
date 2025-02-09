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
        Schema::table('students_databases', function (Blueprint $table) {
            $table->foreignId('teacherprofile_id')->nullable()->constrained('teacher_profiles')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students_databases', function (Blueprint $table) {
            $table->dropConstrainedForeignId('teacherprofile_id');
        });
    }
};