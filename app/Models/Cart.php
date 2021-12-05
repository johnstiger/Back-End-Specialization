<?php

namespace App\Models;

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
                'status',
                'sizeId'
            ]
        );
    }

    public function customer()
    {
        return $this->belongsTo(User::class);
    }
}
