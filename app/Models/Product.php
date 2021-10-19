<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'status',
        'description',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

     public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Sizes::class)->withPivot(['unit_measure','avail_unit_measure']);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function salesItem()
    {
        return $this->belongsTo(SalesItem::class);
    }


}
