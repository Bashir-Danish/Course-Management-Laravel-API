<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function monthlyReportings()
    {
        return $this->hasMany(MonthlyReporting::class);
    }
} 