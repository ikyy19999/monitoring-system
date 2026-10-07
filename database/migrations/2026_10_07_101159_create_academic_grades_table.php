<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_grades', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->foreignId('academic_calendar_id')
                ->constrained('academic_calendars')
                ->cascadeOnDelete();

            $table->decimal('score', 5, 2);

            $table->string('grade', 2);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'student_id',
                    'subject_id',
                    'academic_calendar_id',
                ],
                'academic_grade_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_grades');
    }
};
