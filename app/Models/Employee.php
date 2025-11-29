<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'designation_id',
        'first_name',
        'last_name',
        'dob',
        'address',
        'nrc',
        'phonenumber',
        'email',
        'status',
        'basic_salary',
    ];

    protected $casts = [
        'status' => 'string',
        'dob' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'employee_id');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['permanent', 'contracted', 'training', 'intern']);
    }

    public function trainingPrograms()
    {
        return $this->hasMany(TrainingProgram::class, 'instructor_employee_id');
    }

    public function trainingAssignments()
    {
        return $this->hasMany(TrainingAssignment::class);
    }
}