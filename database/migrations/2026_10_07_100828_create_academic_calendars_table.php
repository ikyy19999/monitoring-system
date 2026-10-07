<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_calendars', function (Blueprint $table) {
            $table->id();

            $table->string('academic_year', 9);

            $table->unsignedTinyInteger('semester');

            $table->date('start_date');

            $table->date('end_date');

            $table->date('grade_input_start_date')->nullable();

            $table->date('grade_input_end_date')->nullable();

            $table->boolean('is_active')->default(true);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(
                ['academic_year', 'semester'],
                'academic_calendar_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_calendars');
    }
};
