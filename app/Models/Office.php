<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;
    protected $table = "offices";
    protected $fillable = [
        'code', 'name', 'supervisor_uuid'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'office_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_uuid', 'uuid');
    }
}
