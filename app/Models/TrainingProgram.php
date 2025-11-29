<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'details',
        'instructor_employee_id',
        'available_days',               // ← just the string name
        'available_time',
        'available_total_employees',
        'status',
    ];

    protected $casts = [
        'available_days' => 'array',    // ← correct place for the cast
        'available_time' => 'string',
        'status'         => 'string',
    ];

    public function instructor()
    {
        return $this->belongsTo(Employee::class, 'instructor_employee_id');
    }

    public function assignments()
    {
        return $this->hasMany(TrainingAssignment::class);
    }

    // Optional helper: show full day names in Blade
    public function getAvailableDaysFullAttribute()
    {
        $shortToFull = [
            'Mon' => 'Monday',
            'Tue' => 'Tuesday',
            'Wed' => 'Wednesday',
            'Thu' => 'Thursday',
            'Fri' => 'Friday',
            'Sat' => 'Saturday',
            'Sun' => 'Sunday',
        ];

        return collect($this->available_days)->map(fn($day) => $shortToFull[$day] ?? $day)->all();
    }
}