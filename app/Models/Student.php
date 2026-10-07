<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_class_id',
        'nis',
        'nisn',
        'full_name',
        'gender',
        'birth_date',
        'birth_place',
        'address',
        'phone',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(
            ParentProfile::class,
            'parent_student'
        )->withPivot('relationship')->withTimestamps();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function academicGrades(): HasMany
    {
        return $this->hasMany(AcademicGrade::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(StudentActivity::class);
    }
}
