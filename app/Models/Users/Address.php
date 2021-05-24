<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'contact_number',
        'postal_code',
        'region',
        'province',
        'city',
        'municipality',
        'barangay',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}