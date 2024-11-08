<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'fees',
        'duration',
        'department_id',
        'available_time_slots'
    ];

    protected $casts = [
        'fees' => 'decimal:2',
        'available_time_slots' => 'array'
    ];

    /**
     * Get the department that owns the course.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the registrations for the course.
     */
    public function registrations()
    {
        if (class_exists('App\Models\Registration')) {
            return $this->hasMany(Registration::class);
        }
        return null;
    }
} 