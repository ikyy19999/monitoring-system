<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year',
        'semester',
        'start_date',
        'end_date',
        'grade_input_start_date',
        'grade_input_end_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'grade_input_start_date' => 'date',
        'grade_input_end_date' => 'date',
        'is_active' => 'boolean',
        'semester' => 'integer',
    ];

    public function academicGrades(): HasMany
    {
        return $this->hasMany(AcademicGrade::class);
    }
}
