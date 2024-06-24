<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analyses extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'content',
        'status',
        'employees_id',
        'patients_id',
        'branches_id',
    ];

    public function branch()
    {
        return $this->belongsTo(Branches::class,'branches_id','id');
    }
    public function doctor()
    {
        return $this->belongsTo(Employees::class,'employees_id','id');
    }
    public function patient()
    {
        return $this->belongsTo(Patients::class,'patients_id','id');
    }
}
