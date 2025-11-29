<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'basic_salary',
        'worked_days',
        'unpaid_leave_days',
        'total_bonus',
        'total_deduction',
        'net_salary',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'month' => 'date',
        'basic_salary' => 'decimal:2',
        'total_bonus' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'approved_at' => 'datetime',
        'status' => 'string',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function bonuses()
    {
        return $this->hasMany(PayrollBonus::class);
    }

    public function deductions()
    {
        return $this->hasMany(PayrollDeduction::class);
    }
}