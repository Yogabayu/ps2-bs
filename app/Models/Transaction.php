<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = "transactions";
    protected $fillable = [
        "position_id", "code", "max_time"
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function datas()
    {
        return $this->hasMany(Datas::class,'transc_id');
    }
}
