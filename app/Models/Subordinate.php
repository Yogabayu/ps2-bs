<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subordinate extends Model
{
    use HasFactory;
    protected $table = "subordinates";
    protected $fillable = [
        "supervisor_id", "subordinate_uuid"
    ];

    public function supervisor()
    {
        return $this->belongsTo(Position::class, 'supervisor_id');
    }
    public function subordinate()
    {
        return $this->belongsTo(User::class, 'subordinate_uuid','uuid');
    }
}
