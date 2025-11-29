<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class Designation extends Model
{
    protected $fillable = ['title'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}