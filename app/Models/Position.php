<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
    protected $table = "positions";
    protected $fillable = [
        'name'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function subordinates()
    {
        return $this->hasMany(Subordinate::class);
    }

    public function user()
    {
        return $this->hasMany(User::class,'position_id','id');
    }
}
