<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sso extends Model
{
    use HasFactory;
    protected $table = "sso";
    protected $fillable = [
        'user_uuid','session_token','start','end'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_uuid','uuid');
    }

    public function ssoActivity()
    {
        return $this->hasMany(SsoActivity::class,'sso_id');
    }
}
