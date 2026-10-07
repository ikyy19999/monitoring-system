<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'activity_date',
        'category',
        'title',
        'description',
        'notes',
    ];

    protected $casts = [
        'activity_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
