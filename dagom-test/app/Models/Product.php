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
        'unit_measure',
        'avail_unit_measure',
        'price',
        'status',
        'description',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

     public function cart()
    {
        return $this->belongsToMany(Cart::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
