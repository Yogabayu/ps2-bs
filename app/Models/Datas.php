<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datas extends Model
{
    use HasFactory;
    protected $table = "datas";
    protected $fillable = [
        'user_uuid', 'transc_id', 'place_transc_id', 'date', 'start', 'end', 'evidence_file', 'nominal', 'customer_name', 'result', 'isActive'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class,'transc_id');
    }
    public function placeTransc()
    {
        return $this->belongsTo(Place_transcs::class,'place_transc_id');
    }
}
