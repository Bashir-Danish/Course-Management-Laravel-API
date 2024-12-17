<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'registration_date',
        'fees_total',
        'fees_paid',
        'time_slot',
        'status'
    ];

    protected $casts = [
        'time_slot' => 'array',
        'fees_total' => 'decimal:2',
        'fees_paid' => 'decimal:2',
        'registration_date' => 'date'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
} 