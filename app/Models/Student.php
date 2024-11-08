<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'address',
        'phone',
        'dob'
    ];

    protected $casts = [
        'dob' => 'date'
    ];

    /**
     * Get the registrations for the student.
     */
    public function registrations()
    {
        if (class_exists('App\Models\Registration')) {
            return $this->hasMany(Registration::class);
        }
        return null;
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'registrations')
            ->withPivot(['registration_date', 'fees_total', 'fees_paid', 'time_slot', 'status'])
            ->withTimestamps();
    }
} 