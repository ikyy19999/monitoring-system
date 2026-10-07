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
        Schema::create('teaching_assignments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('teacher_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->foreignId('subject_id')
            ->constrained('subjects')
            ->cascadeOnDelete();

        $table->foreignId('school_class_id')
            ->constrained('school_classes')
            ->cascadeOnDelete();

        $table->timestamps();

        $table->unique(
            ['teacher_id', 'subject_id', 'school_class_id'],
            'teaching_assignment_unique'
        );
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teaching_assignments');
    }
};
