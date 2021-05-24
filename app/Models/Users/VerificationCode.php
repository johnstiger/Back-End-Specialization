<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
