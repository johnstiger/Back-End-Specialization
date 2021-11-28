<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesItem extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'description',
        'percent_off',
        'unit_measure',
        'promo_type',
        'status',
        'price',
        'size',
        'total'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')->with(['category','sizes']);
    }


}
