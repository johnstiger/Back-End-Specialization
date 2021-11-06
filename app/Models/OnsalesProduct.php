<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnsalesProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "desciption",
        "percent_off",
        "unit_measure",
        "status",
        "size",
        "total"
    ];

    public function products()
    {
        return $this->belongsTo(Product::class);
    }


}
