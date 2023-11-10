<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'uuid',
        'nik',
        'photo',
        'name',
        'email',
        'password',
        'office_id',
        'position_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id','id');
    }

    public function subordinates()
    {
        return $this->hasMany(Subordinate::class, 'uuid');
    }

    public function data()
    {
        return $this->hasMany(Data::class,'user_uuid','uuid');
    }

    public function sso()
    {
        return $this->hasMany(Sso::class,'user_uuid','uuid');
    }

    public function userActivity()
    {
        return $this->hasMany(UserActivity::class,'user_uuid','uuid');
    }
}
