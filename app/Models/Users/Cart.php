<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(
            [
                'quantity',
                'total',
                'status'
            ]
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}