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