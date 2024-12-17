<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'address',
        'phone',
        'gender',
        'salary'
    ];

    protected $casts = [
        'salary' => 'decimal:2'
    ];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'teacher_departments')
                    ->withTimestamps();
    }
} 