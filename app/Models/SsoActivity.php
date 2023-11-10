<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SsoActivity extends Model
{
    use HasFactory;

    protected $table = "sso_activities";
    protected $fillable = [
        "sso_id","activity","ip_address"
    ];

    public function sso() {
        return $this->belongsTo(Sso::class,"sso_id");
    }
}
