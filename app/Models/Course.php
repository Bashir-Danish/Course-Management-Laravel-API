<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'available_time_slots' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($course) {
            // Normalize duration format
            if ($course->duration) {
                $duration = strtolower(trim($course->duration));
                
                // Fix common typos
                $duration = str_replace('mounth', 'month', $duration);
                
                // Ensure proper format
                if (preg_match('/^(\d+)\s*(month|year|day|week)s?$/i', $duration, $matches)) {
                    $number = $matches[1];
                    $unit = $matches[2];
                    $course->duration = $number . ' ' . $unit . ($number > 1 ? 's' : '');
                }
            }
        });
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function getAvailableTimeSlotsAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    public function setAvailableTimeSlotsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['available_time_slots'] = json_encode($value);
        } else if (is_string($value)) {
            $decoded = json_decode($value);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->attributes['available_time_slots'] = $value;
            } else {
                $this->attributes['available_time_slots'] = '[]';
            }
        } else {
            $this->attributes['available_time_slots'] = '[]';
        }
    }
} 