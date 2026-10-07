<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('teaching_assignment_id')
                ->constrained('teaching_assignments')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->date('date');

            $table->enum('status', [
                'hadir',
                'izin',
                'sakit',
                'alpa',
            ])->default('hadir');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique([
                'teaching_assignment_id',
                'student_id',
                'date',
            ], 'attendance_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
