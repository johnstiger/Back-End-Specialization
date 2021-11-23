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

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->with('sizes')->withPivot(["order_id","product_id","quantity","subtotal","size_id"]);
    }
}
