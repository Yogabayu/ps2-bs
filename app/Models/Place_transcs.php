<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place_transcs extends Model
{
    use HasFactory;
    protected $table = "place_transcs";
    protected $fillable = ['code','name'];

    public function data()
    {
        return $this->hasMany(Datas::class,'place_transc_id');
    }
}
