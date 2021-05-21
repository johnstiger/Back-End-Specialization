<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'total',
        'status',
        'payment_method'
    ];

    public function customers()
    {
        return $this->belongsToMany(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(["order_id","product_id","quantity","subtotal"]);
    }
}