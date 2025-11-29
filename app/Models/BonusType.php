<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function payrollBonuses()
    {
        return $this->hasMany(PayrollBonus::class);
    }
}