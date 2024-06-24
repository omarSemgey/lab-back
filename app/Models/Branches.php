<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{
    use HasFactory;

    protected $fillable=[
        'title',
        'description',
    ];

    public function employees()
    {
        return $this->hasMany(Employees::class);
    }
    public function patients()
    {
        return $this->hasMany(Patients::class);
    }
    public function analyses()
    {
        return $this->hasMany(Analyses::class);
    }
}
