<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'phone',
        'email',
        'gender',
        'salary'
    ];

    protected $casts = [
        'salary' => 'decimal:2'
    ];

    /**
     * Get the departments associated with the teacher.
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'teacher_departments')
                    ->withTimestamps();
    }
} 