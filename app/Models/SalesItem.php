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
        'status',
        'total',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }


}
