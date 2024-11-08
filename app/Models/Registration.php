<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'student_id',
        'registration_date',
        'fees_total',
        'fees_paid',
        'time_slot',
        'status'
    ];

    protected $casts = [
        'registration_date' => 'date',
        'fees_total' => 'decimal:2',
        'fees_paid' => 'decimal:2',
        'time_slot' => 'array'
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