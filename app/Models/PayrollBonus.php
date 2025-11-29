<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollBonus extends Model
{
    use HasFactory;

    protected $fillable = ['payroll_id', 'bonus_type_id', 'amount'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function bonusType()
    {
        return $this->belongsTo(BonusType::class);
    }
}