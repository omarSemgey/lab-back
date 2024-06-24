<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Employees extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable=[
        'name',
        'email',
        'role',
        'password',
        'branches_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function analyses()
    {
        return $this->hasMany(Analyses::class);
    }
    public function branch()
    {
        return $this->belongsTo(branches::class,'branches_id','id');
    }

            /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
