<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sizes extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'product_id',
        'size',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
