<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'customer_id',
        'status'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
